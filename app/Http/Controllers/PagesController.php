<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mollie\Mollie_API_Client;
use Carbon\Carbon;
use App\Event;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

# The PagesController is for static pages that do not fall under any other type of controller, like members or events.
class PagesController extends Controller
{

	public function __construct()
	{ }

	# Homepage
	public function home()
	{
		if (\Auth::user()->profile_type == "App\Member") {
			// Member homepage

			// Today's birthdays
			$bdates = \App\Member::where('soort', '<>', 'oud')->pluck('geboortedatum', 'id')->toArray();
			$m = date('m');
			$d = date('d');
			$today = [];
			foreach ($bdates as $id => $date) {
				if ($date->isBirthday()) {
					$today[] = $id;
				}
			}

			foreach ($today as $k => $id) {
				$member = \App\Member::find($id);
				$member->leeftijd = date('Y') - $member->geboortedatum->year;
				$member->ikjarig = false;
				if (\Auth::user()->profile->id == $id) {
					$member->ikjarig = true;
				}
				$today[$k] = $member;
			}

			// Camp thermometer
			$camps = \App\Event::where('type', 'kamp')
				->where('datum_eind', '>=', date('Y-m-d'))
				->where('openbaar', 1)
				->orderBy('datum_start')
				->take(2)
				->get();
			$thermo = [];
			foreach ($camps as $camp) {
				$naam = $camp->naam;
				$id = $camp->id;

				// Admins and members who go on camp can click the link
				$klikbaar = false;

				$events = \Auth::user()->profile->events->pluck('id');

				if (\Auth::user()->is_admin) {
					$klikbaar = true;
				} else if ($events->contains($id)) {
					$klikbaar = true;
				}

				$streef_L = $camp->streeftal;
				$streef_D = ($streef_L - 1) * 3;

				# Hack for 'double camp' event (Winterkampen 2017-2018)
				if ($id == 129) {
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

				$thermo[] = compact('naam', 'id', 'klikbaar', 'streef_L', 'streef_D', 'num_L_goed', 'perc_L_goed', 'num_L_bijna', 'perc_L_bijna', 'num_D_goed', 'perc_D_goed', 'num_D_bijna', 'perc_D_bijna');
			}

			return view('pages.home-member', compact('today', 'thermo'));
		} else {
			// Participant homepage

			// Birthday check!
			$bday = \Auth::user()->profile->geboortedatum;
			$congrats = ($bday->isBirthday()) ? 1 : 0;

			return view('pages.home-participant', compact('congrats'));
		}
	}

	# Info page
	public function info()
	{
		return view('pages.info');
	}

	# Useful lists
	public function lists()
	{
		// Stats
		$stats = [];

		$stats['most'] = [];
		$types = [
			'kamp' => 'kampen',
			'training' => 'trainingen',
			'overig' => 'overige activiteiten'
		];
		foreach ($types as $type => $typename) {
			$most = \DB::table('event_member')
				->selectRaw('count(event_id) as count, member_id')
				->join('events', 'event_member.event_id', '=', 'events.id')
				->where('type', $type)
				->groupBy('member_id')
				->orderBy('count', 'desc')
				->get();
			$highest_count = $most[0]->count;
			$mosts = $most
				->filter(function ($el) use ($highest_count) {
					return $el->count == $highest_count;
				});

			$members = [];
			foreach ($mosts as $el) {
				$members[] = \App\Member::findOrFail($el->member_id);
			}
			$string = '';
			$stats['most'][$type] = [
				'count' => $highest_count,
				'members' => $members
			];
		}

		// Average number of days of participant registration before camp start date
		$regs = DB::table('event_participant')
			->select('event_participant.created_at as reg_date', 'events.datum_start as camp_date')
			->join('events', 'event_participant.event_id', '=', 'events.id', 'left')
			->get();
		$days_arr = [];
		foreach ($regs as $r) {
			$reg_date = Carbon::parse($r->reg_date);
			$camp_date = Carbon::parse($r->camp_date);
			$days_arr[] = $reg_date->diffInDays($camp_date, false);
		}
		$stats['average_days_reg'] = round(array_sum($days_arr) / count($days_arr));

		// Ranonkeltje
		$ranonkeltjePapier = \App\Member::whereIn('ranonkeltje', ['papier', 'beide'])->orderBy('voornaam', 'asc')->get();
		$ranonkeltjeDigitaal = \App\Member::whereIn('ranonkeltje', ['digitaal', 'beide'])->orderBy('voornaam', 'asc')->get();

		// Ervaren trainers
		$trainerList = \App\Member::where('ervaren_trainer', 1)->orderBy('voornaam', 'asc')->get();

		// Niet betaalde deelnemers
		$unpaidList = \DB::table('event_participant')
			->select('participant_id', 'voornaam', 'tussenvoegsel', 'achternaam', 'event_id', 'naam', 'code', 'event_participant.created_at as inschrijving')
			->where('datum_betaling', '0000-00-00')
			->join('events', 'event_participant.event_id', '=', 'events.id')
			->join('participants', 'event_participant.participant_id', '=', 'participants.id')
			->get();

		// Leden zonder KMG
		$kmgList = \App\Member::where('kmg', 0)->orderBy('voornaam')->get();

		// Aspiranten
		$aspirantList = \App\Member::where('soort', 'aspirant')->orderBy('voornaam')->get();

		// Verjaardagen
		$members = \App\Member::whereIn('soort', ['normaal', 'aspirant'])->get();
		foreach ($members as $member) {
			$datum = $member->geboortedatum;
			$dag = $datum->day;
			$maand = $datum->month;

			$vandaag = ($dag == date('d') && $maand == date('m')) ? 1 : 0;
			$leeftijd = $datum->age;

			$birthdayList[] = [
				'id' => $member->id,
				'naam' => str_replace('  ', ' ', $member->voornaam . ' ' . $member->tussenvoegsel . ' ' . $member->achternaam),
				'email' => $member->email,
				'dag' => $dag,
				'maand' => $maand,
				'vandaag' => $vandaag,
				'leeftijd' => $leeftijd
			];
		}

		$birthdayList = array_values(array_sort($birthdayList, function ($member) {
			return (100 * $member['maand'] + $member['dag']);
		}));

		// Maanden (voor verjaardagen)
		$monthName = [
			1 => 'januari',
			2 => 'februari',
			3 => 'maart',
			4 => 'april',
			5 => 'mei',
			6 => 'juni',
			7 => 'juli',
			8 => 'augustus',
			9 => 'september',
			10 => 'oktober',
			11 => 'november',
			12 => 'december'
		];

		// Leden en deelnemers zonder gekoppelde kampen
		$membersWithoutEvents = \App\Member::where('soort', '<>', 'oud')->orderBy('created_at')->get()->filter(function ($member) {
			return $member->events->count() == 0;
		});

		$participantsWithoutCamps = \App\Participant::orderBy('created_at')->get()->filter(function ($part) {
			return $part->events->count() == 0;
		});

		// Mailadressen voor een deelnemermailing (bijv. bij kortingsacties)
		$startDate = Carbon::now()->subYears(19);
		$participantMailingList = \App\Participant::where('mag_gemaild', 1)->where('geboortedatum', '>', $startDate->toDateString())->get();

		return view('pages.lists', compact('stats', 'types', 'ranonkeltjePapier', 'ranonkeltjeDigitaal', 'trainerList', 'unpaidList', 'kmgList', 'aspirantList', 'birthdayList', 'courses', 'monthName', 'membersWithoutEvents', 'participantsWithoutCamps', 'participantMailingList'));
	}

	# Analytical graphs
	public function graphs()
	{
		// Determine end date for graph range; from 1st of August we include the current Anderwijs year
		$maxYear = Carbon::now()->addMonths(5)->year - 1;

		$minDate = "2009-09-01";
		$maxDate = $maxYear . "-08-31";

		$camps = \App\Event::where('type', 'kamp')
			->where('datum_start', '<=', $maxDate)
			->where('datum_start', '>=', $minDate)
			->orderBy('datum_start')->get();
		$num_members = [];
		foreach ($camps as $camp) {
			preg_match('/\d{4}/', $camp->code, $matches); // obtains the year string from the camp code, e.g. '1415'
			$year = $matches[0];
			$m = $camp->members()->count();
			$m_m = $camp->members()->where('geslacht', 'M')->count();
			$m_f = $camp->members()->where('geslacht', 'V')->count();
			$mids = $camp->members()->pluck('id')->toArray();
			$pids = $camp->participants()->pluck('id')->toArray();
			$p = $camp->participants()->count();
			$p_m = $camp->participants()->where('geslacht', 'M')->count();
			$p_f = $camp->participants()->where('geslacht', 'V')->count();

			// New members
			$m_n = 0;
			foreach ($camp->members as $member) {
				$num = $member->events()->where('type', 'kamp')->where('datum_eind', '<', $camp->datum_start)->count();
				if ($num == 0) {
					$m_n++;
				}
			}

			// New participants
			$p_n = 0;
			foreach ($camp->participants as $participant) {
				$num = $participant->events()->where('datum_eind', '<', $camp->datum_start)->count();
				if ($num == 0) {
					$p_n++;
				}
			}

			if (array_key_exists($year, $num_members)) {
				$num_members[$year] += $m;
				$num_members_new[$year] += $m_n;
				$num_members_male[$year] += $m_m;
				$num_members_female[$year] += $m_f;
				$num_participants[$year] += $p;
				$num_participants_new[$year] += $p_n;
				$num_participants_male[$year] += $p_m;
				$num_participants_female[$year] += $p_f;
				$member_ids[$year] = array_merge($member_ids[$year], $mids);
				$participant_ids[$year] = array_merge($participant_ids[$year], $pids);
			} else {
				$num_members[$year] = $m;
				$num_members_new[$year] = $m_n;
				$num_members_male[$year] = $m_m;
				$num_members_female[$year] = $m_f;
				$num_participants[$year] = $p;
				$num_participants_new[$year] = $p_n;
				$num_participants_male[$year] = $p_m;
				$num_participants_female[$year] = $p_f;
				$member_ids[$year] = $mids;
				$participant_ids[$year] = $pids;
			}
		}

		$data['membGrowth'][] = ['Jaar', 'Totaal', 'Uniek', 'Nieuw'];
		$data['partGrowth'][] = ['Jaar', 'Totaal', 'Uniek', 'Nieuw'];
		$data['percNew'][] = ['Jaar', 'Leiding', 'Deelnemers'];
		$data['membPartRatio'][] = ['Jaar', 'Ratio'];
		$data['aveNumCamps'][] = ['Jaar', 'Leiding', 'Deelnemers'];
		$data['maleFemaleRatio'][] = ['Jaar', 'Leiding', 'Deelnemers'];
		foreach ($num_members as $k => $v) {
			if ($num_members[$k] != 0 && $num_participants[$k] != 0) {
				$year = substr($k, 0, 2) . '-' . substr($k, 2, 2);
				$data['membGrowth'][] = [$year, $num_members[$k], count(array_unique($member_ids[$k])), $num_members_new[$k]];
				$data['partGrowth'][] = [$year, $num_participants[$k], count(array_unique($participant_ids[$k])), $num_participants_new[$k]];
				$data['percNew'][] = [$year, round(($num_members_new[$k] / $num_members[$k]) * 100, 1), round(($num_participants_new[$k] / $num_participants[$k]) * 100, 1)];
				$data['membPartRatio'][] = [$year, round($num_members[$k] / $num_participants[$k], 2)];
				$data['aveNumCamps'][] = [$year, round(count($member_ids[$k]) / count(array_unique($member_ids[$k])), 2), round(count($participant_ids[$k]) / count(array_unique($participant_ids[$k])), 2)];
				if ($num_members_female[$k] > 0 && $num_participants_female[$k] > 0) {
					$data['maleFemaleRatio'][] = [$year, round($num_members_male[$k] / $num_members_female[$k], 2), round($num_participants_male[$k] / $num_participants_female[$k], 2)];
				}
			}
		}

		// Same round for trainings
		$trainings = \App\Event::where('type', 'training')
			->where('datum_start', '>=', $minDate)
			->where('datum_start', '<=', $maxDate)
			->orderBy('datum_start')
			->get();
		$member_ids = [];

		foreach ($trainings as $training) {
			preg_match('/\d{4}/', $training->code, $matches); // obtains the year string from the camp code, e.g. '1415'
			$year = $matches[0];
			$mids = $training->members()->pluck('id')->toArray();
			$t = $training->members()->count();

			// New trainers
			$t_n = 0;
			foreach ($training->members as $member) {
				$num = $member->events()->where('type', 'training')->where('datum_eind', '<', $training->datum_start)->count();
				if ($num == 0) {
					$t_n++;
				}
			}

			if (array_key_exists($year, $member_ids)) {
				$num_trainers[$year] += $t;
				$num_trainers_new[$year] += $t_n;
				$member_ids[$year] = array_merge($member_ids[$year], $mids);
			} else {
				$num_trainers[$year] = $t;
				$num_trainers_new[$year] = $t_n;
				$member_ids[$year] = $mids;
			}
		}

		$data['trainerGrowth'][] = ['Jaar', 'Totaal', 'Uniek', 'Nieuw'];
		$data['aveNumTrainings'][] = ['Jaar', 'Aantal'];
		foreach ($member_ids as $k => $v) {
			if ($v != []) {
				$year = substr($k, 0, 2) . '-' . substr($k, 2, 2);
				$data['trainerGrowth'][] = [$year, $num_trainers[$k], count(array_unique($member_ids[$k])), $num_trainers_new[$k]];
				$data['aveNumTrainings'][] = [$year, round(count($v) / count(array_unique($v)), 2)];
			}
		}

		// Analysis of participant registration
		$graphStart = -90;
		$camps = \App\Event::where('type', 'kamp')->where('datum_start', '>', '2012-11-01')->orderBy('datum_start', 'asc')->get();
		$registration_series = [];

		foreach ($camps as $camp) {

			// Make an array of the registration days for this camp
			$daysArray = [];
			foreach ($camp->participants()->get() as $participant) {
				$daysArray[] = -$camp->datum_start->diffInDays($participant->pivot->created_at);
			}
			sort($daysArray);

			// Create the data series
			$data_series = [];
			$p = 0;

			foreach ($daysArray as $days) {
				($days <= $graphStart) ? $p++ : null;
			}
			$data_series[] = [$graphStart, $p];

			for ($i = $graphStart; $i <= 0; $i++) {
				if (in_array($i, $daysArray)) {
					$p = $p + count(array_keys($daysArray, $i));
					$data_series[] = [$i, $p];
				}
			}

			$var = -Carbon::now()->diffInDays($camp->datum_start);
			if ($camp->datum_start <= date('Y-m-d')) {
				$data_series[] = [0, $p];
			} elseif ($var > $graphStart) {
				$data_series[] = [$var, $p];
			}

			// Add to registration_series
			$registration_series[] = [
				'name' => $camp->naam . ' ' . $camp->datum_start->format('Y'),
				'step' => 'left',
				'marker' => [
					'enabled' => false
				],
				'states' => [
					'hover' => [
						'lineWidth' => 4
					]
				],
				'data' => $data_series
			];
		}

		// Analysis of participants' preference for camp type (from reviews)
		$camp_prefs = [];
		$revs = \App\Review::whereNotNull('kampkeuze')->pluck('kampkeuze')->toArray();
		$prefs_rev_count = count($revs);
		foreach ($revs as $opts) {
			foreach ($opts as $opt) {
				if (array_key_exists($opt, $camp_prefs)) {
					$camp_prefs[$opt]++;
				} else {
					$camp_prefs[$opt] = 1;
				}
			}
		}

		$dt = \Lava::DataTable();
		$dt->addStringColumn('Optie');
		$dt->addNumberColumn('Aantal');

		foreach ($camp_prefs as $type => $count) {
			$dt->addRow([$type, $count]);
		}

		\Lava::BarChart("kampkeuze", $dt, [
			'width' => '100%',
			'height' => '450',
			'chartArea' => [
				'top' => 25,
				'left' => '25%',
				//	'height' => 180,
				'width' => '70%'
			],
			'fontSize' => 14,
			'hAxis' => [
				'minValue' => 0,
				'gridlines' => [
					'count' => -1
				]
			],
			'legend' => [
				'position' => 'none'
			]
		]);

		return view('pages.graphs', compact('data', 'registration_series', 'camp_prefs', 'prefs_rev_count'));
	}

	# Exposes upcoming events as JSON for website integration
	public function cal($type)
	{
		$data = [];

		if ($type == 'part') {
			// Only coming camps, for participants and their parents
			$events = \App\Event::where('type', 'kamp')
				->where('datum_eind', '>=', date('Y-m-d'))
				->where('openbaar', 1)
				->orderBy('datum_start', 'asc')
				->get();
		} elseif ($type == 'full') {
			// All coming events, for members
			$events = \App\Event::where('datum_eind', '>=', date('Y-m-d'))
				->orderBy('datum_start', 'asc')
				->where('openbaar', 1)
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
				case 'training':
					$color = '#1E5027';
					break;
				case 'overig':
					$color = '#AA1E58';
					break;
			}

			// Add '(VOL)' to camp name if camp is full
			$naam = $event->naam;
			if ($type == 'part' && $event->vol) {
				$naam .= ' (VOL)';
			}

			// Determine the 'price' table cells
			$pr = $event->prijs;
			$pr15 = round((0.85 * $pr) / 5) * 5;
			$pr30 = round((0.7 * $pr) / 5) * 5;
			$pr50 = round((0.5 * $pr) / 5) * 5;

			$prijs_html = "<td style='white-space: nowrap;'>Prijs";

			if ($pr == 0) {
				$prijs_html .= "</td><td>Wordt nog vastgesteld</td>";
			} else {
				$prijs_html .= "<br/>- 15% korting<br/>- 30% korting<br/>- 50% korting</td><td>€ " . $pr . "<br/>€ " . $pr15 . "<br/>€ " . $pr30 . "<br/>€ " . $pr50 . "</td>";
			}

			// Create a string with Google Maps hyperlink for the members agenda
			$adres = $event->location->adres;
			$plaats = $event->location->plaats;
			if ($plaats == "Onbekend") {
				$kamphuis_link = $plaats;
			} else {
				$string = str_replace(" ", "+", $adres . " " . $plaats);
				$kamphuis_link = "<a href='https://www.google.com/maps?q=" . $string . "' target='_blank'>" . $plaats . "</a>";
			}

			// Weekday table
			$weekdays = [
				"",
				"Maandag",
				"Dinsdag",
				"Woensdag",
				"Donderdag",
				"Vrijdag",
				"Zaterdag",
				"Zondag"
			];

			// Create data array to return
			$data[] = [
				'id' => $k,
				'naam' => $naam,
				'code' => $event->code,
				'voordag_tekst' => ($event->type == 'kamp') ? 'Voordag:<br/>' : null,
				'datum_voordag' => ($event->type == 'kamp') ? $event->datum_voordag->format('d-m-Y') . '<br/>' : null,
				'tijd_voordag' => ($event->type == 'kamp') ?
					'&nbsp;<br/>' : null,
				'weekdag_start' => $weekdays[$event->datum_start->format('N')],
				'datum_start' => $event->datum_start->format('d-m-Y'),
				'tijd_start' => substr($event->tijd_start, 0, 5),
				'weekdag_eind' => $weekdays[$event->datum_eind->format('N')],
				'datum_eind' => $event->datum_eind->format('d-m-Y'),
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
				'kleur' => $color
			];
			$k++;
		}

		return response()->json($data);
	}

	# Exposes information about one camp (by ID) for website integration
	public function campInfo($camp_id)
	{

		$camp = Event::find($camp_id);

		if ($camp->type != 'kamp') {
			return null;
		}

		$data = [
			'id' => $camp->id,
			'naam' => $camp->naam,
			'prijs' => $camp->prijs
		];

		return response()->json($data);
	}

	# Expose list of all previous camps as report, for website integration
	public function campsReport()
	{

		$camps = Event::where('type', 'kamp')
			->where('datum_eind', '<', date('Y-m-d'))
			->where('openbaar', 1)
			->orderBy('datum_start', 'desc')
			->get();

		$data = [];
		foreach ($camps as $c) {

			$aantal_leiding_vol = $c->members()->where('wissel', 0)->count();
			$aantal_leiding_wissel = $c->members()->where('wissel', 1)->count();
			$leiding_string = (string) $aantal_leiding_vol;
			if ($aantal_leiding_wissel > 0) {
				$leiding_string .= " + " . $aantal_leiding_wissel;
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
				'aantal_deelnemers' => $c->participants()->where('geplaatst', 1)->count()
			];
		}

		return response()->json($data);
	}

	# Referrer page for registrations
	public function referrer()
	{
		return view('pages.referrer');
	}

	# Place for scripting
	public function runScript()
	{
		echo "Average rating per reviewer: " . round(\App\Review::pluck("cijfer")->avg(), 2) . " out of " . \App\Review::pluck("id")->count() . " total reviews";

		echo "<br/><br/>";

		echo "Average camp ratings:<br/><br/>";

		foreach (Event::where("type", "kamp")->orderBy("datum_start")->has("reviews")->get() as $event) {
			echo $event->naam . " " . $event->datum_start->year . " - " . $event->averageRating . "<br/>";
		}

		echo "<br/><br/>";

		echo "Members with most participants on camp<br/><br/>";

		$rank = [];
		$rank_unique = [];
		foreach (\App\Member::where('soort', '<>', 'oud')->has('events')->get() as $member) {
			$list = [];
			foreach ($member->events()->where('type', 'kamp')->get() as $event) {
				$list = array_merge($list, $event->participants()->where('geplaatst', 1)->pluck('id')->toArray());
			}
			$rank[$member->volnaam] = count($list);
			$rank_unique[$member->volnaam] = count(array_unique($list));
		}
		arsort($rank);
		arsort($rank_unique);

		echo "Cumulative<br/>";
		foreach ($rank as $m => $v) {
			echo $m . ": " . $v . "<br/>";
		}
		echo "<br/>";
		echo "Unique<br/>";
		foreach ($rank_unique as $m => $v) {
			echo $m . ": " . $v . "<br/>";
		}

		echo "<br/><br/>";

		echo "Most other unique members on camp" . "<br/><br/>";
		$res = [];
		$res2 = [];

		foreach (\App\Member::where('soort', '<>', 'oud')->has('events')->get() as $member) {
			// Who has this member been on camp with?
			$events = $member->events()->where('type', 'kamp')->where('datum_eind', '<', date('Y-m-d'))->get();
			$fellow_ids = [];
			foreach ($events as $event) {
				$fellow_ids = array_merge($fellow_ids, $event->members()->pluck('id')->toArray());
			}
			$fellow_ids = array_unique($fellow_ids);
			if (($key = array_search($member->id, $fellow_ids)) !== false) {
				unset($fellow_ids[$key]);
			}

			$res[$member->volnaam] = count($fellow_ids);
			#echo $member->volnaam . ": " . count($fellow_ids) . "<br/>";
			if ($events->count() != 0) {
				$res2[$member->volnaam] = count($fellow_ids) / $events->count();
			} else {
				$res2[$member->volnaam] = "-";
			}
		}

		arsort($res);
		foreach ($res as $m => $v) {
			echo $m . ": " . $v . "<br/>";
		}

		echo "<br/><br/>";
		echo "And now normalized per camp" . "<br/><br/>";

		arsort($res2);
		foreach ($res2 as $m => $v) {
			echo $m . ": " . round($v, 2) . "<br/>";
		}
	}


	public function showPrivacyStatement(Request $request)
	{
		return view("pages.privacy-statement");
	}

	public function showAcceptPrivacyStatement(Request $request)
	{
		$user = Auth::user();
		$showForm = true;
		return view("pages.privacy-statement", compact("user", "showForm"));
	}

	public function storePrivacyStatement(Request $request)
	{
		$privacyAccepted = $request->input("privacyAccepted") === "1";
		if (!$privacyAccepted) {
			return redirect("accept-privacy")->with([
				"flash_error" => "De privacyvoorwaarden dienen geaccepteerd te worden om verder te kunnen."
			]);
		}

		$user = Auth::user();
		$user->privacy = Carbon::now();
		$user->save();
		return redirect("home");
	}
}
