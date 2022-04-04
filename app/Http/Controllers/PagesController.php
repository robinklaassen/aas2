<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\DateHelper;
use App\Helpers\Payment\EventPayment;
use App\Models\Event;
use App\Models\Member;
use App\Models\Participant;
use Carbon\Carbon;
use Illuminate\Http\Request;

// The PagesController is for static pages that do not fall under any other type of controller, like members or events.
class PagesController extends Controller
{
    // Homepage
    public function home(Request $request)
    {
        if ($request->user()->isMember()) {
            // Member homepage

            // Today's birthdays
            $bdates = Member::where('soort', '<>', 'oud')->where('publish_birthday', 1)->pluck('geboortedatum', 'id')->toArray();
            $today = [];
            foreach ($bdates as $id => $date) {
                if ($date->isBirthday()) {
                    $today[] = $id;
                }
            }

            foreach ($today as $k => $id) {
                $member = Member::find($id);
                $member->leeftijd = date('Y') - $member->geboortedatum->year;
                $member->ikjarig = false;
                if ($request->user()->profile->id === $id) {
                    $member->ikjarig = true;
                }
                $today[$k] = $member;
            }

            // Camp thermometer
            $camps = Event::ParticipantEvent()
                ->ongoing()
                ->notCancelled()
                ->public()
                ->orderBy('datum_start')
                ->take(2)
                ->get();
            $thermo = [];
            foreach ($camps as $camp) {
                $streef_L = $camp->streeftal;
                $streef_D = ($streef_L - 1) * 3;

                // Hack for 'double camp' event (Winterkampen 2017-2018)
                if ($id === 129) {
                    $streef_L = 12;
                    $streef_D = 28;
                }

                $num_L_goed = $camp->members()->wherePivot('wissel', 0)->where('vog', 1)->count();
                $num_L_bijna = $camp->members()->wherePivot('wissel', 0)->where('vog', 0)->count();

                $num_D_goed = $camp->participants()->wherePivot('datum_betaling', '>', '0000-00-00')->count();
                $num_D_bijna = $camp->participants()->wherePivot('datum_betaling', '0000-00-00')->count();

                $perc_L_goed = ($num_L_goed > $streef_L) ? 100 : ($num_L_goed / $streef_L) * 100;
                $perc_D_goed = ($num_D_goed > $streef_D) ? 100 : ($num_D_goed / $streef_D) * 100;

                $perc_L_bijna = ($num_L_goed + $num_L_bijna > $streef_L) ? 100 - $perc_L_goed : ($num_L_bijna / $streef_L) * 100;
                $perc_D_bijna = ($num_D_goed + $num_D_bijna > $streef_D) ? 100 - $perc_D_goed : ($num_D_bijna / $streef_D) * 100;

                $thermo[] = compact('camp', 'streef_L', 'streef_D', 'num_L_goed', 'perc_L_goed', 'num_L_bijna', 'perc_L_bijna', 'num_D_goed', 'perc_D_goed', 'num_D_bijna', 'perc_D_bijna');
            }

            return view('pages.home-member', compact('today', 'thermo'));
        } elseif ($request->user()->isParticipant()) {
            // Participant homepage

            // Birthday check!
            $bday = $request->user()->profile->geboortedatum;
            $congrats = ($bday->isBirthday()) ? 1 : 0;

            return view('pages.home-participant', compact('congrats'));
        }
    }

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
                    - 15&percnt; korting<br/>
                    - 30&percnt; korting<br/>
                    - 50&percnt; korting</td><td>";
                $prijs_html .= implode('', array_map(function (float $disc) use ($event) {
                    return '&euro; ' . EventPayment::calculate_price($event->prijs, $disc) . '<br/>';
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

    // Referrer page for registrations
    public function referrer()
    {
        return view('pages.referrer');
    }

    public function showPrivacyStatement()
    {
        return view('pages.privacy-statement');
    }

    public function showAcceptPrivacyStatement(Request $request)
    {
        $user = $request->user();
        $showForm = true;
        return view('pages.privacy-statement', compact('user', 'showForm'));
    }

    public function storePrivacyStatement(Request $request)
    {
        $privacyAccepted = $request->input('privacyAccepted') === '1';
        if (! $privacyAccepted) {
            return redirect('accept-privacy')->with([
                'flash_error' => 'De privacyvoorwaarden dienen geaccepteerd te worden om verder te kunnen.',
            ]);
        }

        $user = $request->user();
        $user->privacy = Carbon::now();
        $user->save();
        return redirect('home');
    }
}
