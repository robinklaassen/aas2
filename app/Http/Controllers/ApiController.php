<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Helpers\Payment\EventPayment;
use App\Models\Event;
use App\Models\Participant;
use App\ValueObjects\Pricing\Discount;

class ApiController extends Controller
{
    // Exposes upcoming events as JSON for website integration
    public function websiteUpcomingEvents($type)
    {
        if (! in_array($type, ['part', 'full'], true)) {
            return null;
        }

        $events = Event::public()
            ->when($type === 'part', function ($q) {
                return $q->participantEvent();
            })
            ->where('datum_eind', '>=', date('Y-m-d'))
            ->orderBy('datum_start', 'asc')
            ->get();

        $data = [];
        $k = 1;

        foreach ($events as $event) {
            // Add '(VOL)' to camp name if camp is full
            $naam = $event->naam;
            if ($type === 'part' && $event->vol) {
                $naam .= ' (VOL)';
            }
            if ($event->cancelled) {
                $naam .= ' (AFGELAST)';
            }

            if ($event->prijs === null) {
                $prijs_html = '<td>Prijs</td><td>Wordt nog vastgesteld</td>';
            } elseif ($event->prijs === 0) {
                $prijs_html = '';
            } elseif ($event->prijs > 0) {
                $prijs_html = "<td style='white-space: nowrap;'>Prijs<br />";

                $prijs_html .= implode('', array_map(function (int $disc) {
                    return sprintf('- %s korting<br />', (string) Discount::fromPercentage($disc));
                }, Participant::INCOME_DISCOUNT_TABLE));
                $prijs_html .= '</td>';

                $prijs_html .= implode('', array_map(function (int $disc) use ($event) {
                    return sprintf('&euro; %d<br />', EventPayment::calculatePrice($event->prijs, Discount::fromPercentage($disc)));
                }, Participant::INCOME_DISCOUNT_TABLE));
                $prijs_html .= '</td>';
            }

            // Create a string with Google Maps hyperlink for the members agenda
            $adres = $event->location->adres;
            $plaats = $event->location->plaats;
            if ($plaats === 'Onbekend') {
                $kamphuis_link = $plaats;
            } else {
                $string = str_replace(' ', '+', $adres . ' ' . $plaats);
                $kamphuis_link = "<a href='https://www.google.com/maps?q=" . $string . "' target='_blank'>" . $plaats . '</a>';
            }

            // Weekday table
            $weekdays = [
                '',
                'Maandag',
                'Dinsdag',
                'Woensdag',
                'Donderdag',
                'Vrijdag',
                'Zaterdag',
                'Zondag',
            ];

            // Create data array to return
            $record = [
                'id' => $k,
                'naam' => $naam,
                'code' => $event->code,
                'voordag_tekst' => ($event->type === 'kamp') ? 'Voordag:<br/>' : null,
                'datum_voordag' => ($event->type === 'kamp') ? DateHelper::Format($event->datum_voordag) . '<br/>' : null,
                'tijd_voordag' => ($event->type === 'kamp') ?
                    '&nbsp;<br/>' : null,
                'weekdag_start' => $weekdays[$event->datum_start->format('N')],
                'datum_start' => DateHelper::Format($event->datum_start),
                'tijd_start' => substr($event->tijd_start, 0, 5),
                'weekdag_eind' => $weekdays[$event->datum_eind->format('N')],
                'datum_eind' => DateHelper::Format($event->datum_eind),
                'tijd_eind' => substr($event->tijd_eind, 0, 5),
                'aantal_dagen' => $event->datum_eind->diffInDays($event->datum_start),
                'kamphuis_naam' => $event->location->naam,
                'kamphuis_adres' => $event->location->adres,
                'kamphuis_postcode' => $event->location->postcode,
                'kamphuis_plaats' => $event->location->plaats,
                'kamphuis_telefoon' => $event->location->telefoon,
                'kamphuis_website' => $event->location->website,
                'kamphuis_mapslink' => $kamphuis_link,
                'prijs' => $prijs_html,
                'beschrijving' => $event->beschrijving,
                'prijzen' => [
                    [
                        'type' => 'Standaard prijs',
                        'prijzen' => [
                            [
                                'omschrijving' => 'Standaard kampprijs',
                                'prijs' => $event->prijs,
                            ],
                        ],
                    ],
                    [
                        'type' => 'Inkomensafhankelijke korting',
                        'prijzen' => array_map(static function (int $key) use ($event): array {
                            $discount = Discount::fromPercentage(Participant::INCOME_DISCOUNT_TABLE[$key]);
                            return [
                                'omschrijving' => Participant::INCOME_DESCRIPTION_TABLE[$key],
                                'korting' => (string) $discount,
                                'prijs' => EventPayment::calculatePrice($event->prijs, $discount),
                            ];
                        }, array_keys(Participant::INCOME_DISCOUNT_TABLE)),
                    ],
                    $event->hasEarlybirdDiscount ? [
                        'type' => 'Vroegboek korting',
                        'prijzen' => [
                            [
                                'omschrijving' => sprintf('Vroegboek korting tot %s', $event->vroegboek_korting_datum_eind->format('d-m-Y')),
                                'korting' => (string) $event->earlybirdDiscount,
                                'prijs' => EventPayment::calculatePrice($event->prijs, $event->earlybirdDiscount),
                            ],
                        ],
                    ] : null,
                ],
            ];

            if (in_array($event->type, ['kamp', 'online'], true)) {
                $record['structured_data'] = json_encode($this->getStructuredData($event));
            }

            $data[] = $record;
            $k++;
        }

        return response()->json($data);
    }

    // Construct event data as JSON-LD to please our Google overlords
    // https://developers.google.com/search/docs/appearance/structured-data/event
    private function getStructuredData(Event $event): array
    {
        $location = $event->location;

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $event->full_title,
            'startDate' => $event->datum_start->toIso8601String(),
            'endDate' => $event->datum_eind->toIso8601String(),
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'eventStatus' => $event->cancelled ? 'https://schema.org/EventCancelled' : 'https://schema.org/EventScheduled',
            'location' => [
                '@type' => 'Place',
                'name' => $location->naam,
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $location->adres,
                    'adressLocality' => $location->plaats,
                    'postalCode' => $location->postcode,
                    'addressRegion' => null,
                    'addressCountry' => 'NL',
                ],
            ],
            'image' => [
                'https://anderwijs.nl/static/kamp-1-c9492e85528942e71e4a76e6322463d0.jpg',
                'https://anderwijs.nl/static/kamp-2-444fdbaa3a159f91c29524462162996b.jpg',
            ],
            'description' => $event->omschrijving,
            'offers' => [
                array_map(function (int $key) use ($event): array {
                    $discount = Discount::fromPercentage(Participant::INCOME_DISCOUNT_TABLE[$key]);
                    return $this->getCampOffer($event, $discount);
                }, array_keys(Participant::INCOME_DISCOUNT_TABLE)),
                $event->hasEarlybirdDiscount ? $this->getCampOffer($event, $event->earlybirdDiscount) : null,
            ],
            'organizer' => [
                '@type' => 'Organization',
                'name' => 'Anderwijs',
                'url' => 'https://anderwijs.nl',
            ],
        ];
    }

    private function getCampOffer(Event $event, Discount $discount): array
    {
        return [
            '@type' => 'Offer',
            'url' => 'https://anderwijs.nl/inschrijven/inschrijven-scholieren/',
            'price' => EventPayment::calculatePrice($event->prijs, $discount),
            'priceCurrency' => 'EUR',
            'availability' => $event->vol ? 'https://schema.org/SoldOut' : 'https://schema.org/InStock',
        ];
    }
}
