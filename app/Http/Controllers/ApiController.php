<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Helpers\Payment\EventPayment;
use App\Models\Event;
use App\Models\Participant;

class ApiController extends Controller
{
    // Exposes upcoming events as JSON for website integration
    public function cal($type)
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
                $prijs_html = "<td style='white-space: nowrap;'>Prijs<br />
                    - 25&percnt; korting<br/>
                    - 40&percnt; korting<br/>
                    - 60&percnt; korting</td><td>";
                $prijs_html .= implode('', array_map(function (float $disc) use ($event) {
                    return '&euro; ' . EventPayment::calculatePrice($event->prijs, $disc) . '<br/>';
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
                'vroegboek_korting' => $event->hasEarlybirdDiscount ? [
                    'percentage' => $event->vroegboek_korting_percentage,
                    'prijs' => EventPayment::calculatePrice($event->prijs, $event->earlybirdDiscountFactor),
                    'datum_eind' => $event->vroegboek_korting_datum_eind,
                ] : null,
            ];
            $k++;
        }

        return response()->json($data);
    }

    // Exposes information about one camp (by ID) for website integration
    public function campInfo($camp_id)
    {
        $camp = Event::find($camp_id);

        if ($camp->type !== 'kamp') {
            return null;
        }

        $data = [
            'id' => $camp->id,
            'naam' => $camp->naam,
            'prijs' => $camp->prijs,
            'vroegboek_korting' => $camp->hasEarlybirdDiscount ? [
                'percentage' => $camp->vroegboek_korting_percentage,
                'prijs' => EventPayment::calculatePrice($camp->prijs, $camp->earlybirdDiscountFactor),
                'datum_eind' => $camp->vroegboek_korting_datum_eind,
            ] : null,
        ];

        return response()->json($data);
    }

    // Expose list of all previous camps as report, for website integration
    public function campsReport()
    {
        $camps = Event::where('type', 'kamp')
            ->where('datum_eind', '<', date('Y-m-d'))
            ->public()
            ->orderBy('datum_start', 'desc')
            ->get();

        $data = [];
        foreach ($camps as $c) {
            $aantal_leiding_vol = $c->members()->where('wissel', 0)->count();
            $aantal_leiding_wissel = $c->members()->where('wissel', 1)->count();
            $leiding_string = (string) $aantal_leiding_vol;
            if ($aantal_leiding_wissel > 0) {
                $leiding_string .= ' + ' . $aantal_leiding_wissel;
            }

            $data[] = [
                'id' => $c->id,
                'naam' => $c->naam,
                'jaar' => $c->datum_start->format('Y'),
                'datum_start' => $c->datum_start->format('d-m-Y'),
                'datum_eind' => $c->datum_eind->format('d-m-Y'),
                'plaats' => $c->location->plaats,
                'aantal_leiding_vol' => $c->members()->where('wissel', 0)->count(),
                'aantal_leiding_wissel' => $c->members()->where('wissel', 1)->count(),
                'aantal_leiding_string' => $leiding_string,
                'aantal_deelnemers' => $c->participants()->where('geplaatst', 1)->count(),
            ];
        }

        return response()->json($data);
    }
}
