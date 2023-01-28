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
    public function websiteCalendar($type)
    {
        $data = [];

        if ($type === 'part') {
            // Only coming camps, for participants and their parents
            $events = Event::whereIn('type', ['kamp', 'online'])
                ->where('datum_eind', '>=', date('Y-m-d'))
                ->public()
                ->orderBy('datum_start', 'asc')
                ->get();
        } elseif ($type === 'full') {
            // All coming events, for members
            $events = Event::where('datum_eind', '>=', date('Y-m-d'))
                ->orderBy('datum_start', 'asc')
                ->public()
                ->get();
        } else {
            return null;
        }

        $k = 1;

        foreach ($events as $event) {
            // Determine background color
            switch ($event->type) {
                case 'kamp':
                    $color = '#50B848';
                    break;
                case 'online':
                    $color = '#1eaa97';
                    break;
                case 'training':
                    $color = '#1E5027';
                    break;
                case 'overig':
                    $color = '#AA1E58';
                    break;
            }

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
            $data[] = [
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
                'kleur' => $color,
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
                'structured_data' => json_encode($this->getStructuredData($event)),
            ];
            $k++;
        }

        return response()->json($data);
    }

    // Construct event data as JSON-LD to please our Google overlords
    // https://developers.google.com/search/docs/appearance/structured-data/event
    private function getStructuredData(Event $event): ?array
    {
        if (! in_array($event->type, ['kamp', 'online'], true)) {
            return null;
        }

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
                // TODO link to static images on main website?
            ],
            'description' => $event->omschrijving,
            'offers' => [
                '@type' => 'Offer',
                'url' => 'https://anderwijs.nl/inschrijven/inschrijven-scholieren/',
                'price' => $event->prijs,
                'priceCurrency' => 'EUR',
                'availability' => $event->vol ? 'https://schema.org/SoldOut' : 'https://schema.org/InStock',
                // TODO add discounts and early bird offers
            ],
            'organizer' => [
                '@type' => 'Organization',
                'name' => 'Anderwijs',
                'url' => 'https://anderwijs.nl',
            ],
        ];
    }
}
