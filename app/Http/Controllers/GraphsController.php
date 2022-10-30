<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraphsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // Determine end date for graph range; from 1st of August we include the current Anderwijs year
        $maxYear = Carbon::now()->addMonths(5)->year - 1;

        $minDate = '2009-09-01';
        $maxDate = $maxYear . '-08-31';

        $avg_days_before_event = DB::select(
            DB::raw("
				SELECT s.* 
					 , e.code as code
				  FROM (
					SELECT e.id
					, AVG(DATEDIFF(e.datum_start, CAST(ep.created_at as date))) AS avg_participants_days
					, AVG(DATEDIFF(e.datum_start, CAST(em.created_at as date))) AS avg_members_days
					FROM events e 
					LEFT JOIN event_participant ep on ep.event_id = e.id
					LEFT JOIN event_member em on em.event_id = e.id
				   WHERE e.type in ('kamp')
					 AND e.datum_start > '2015-09-01'
					GROUP BY e.id DESC
					LIMIT 10
				) s
				join events e on s.id = e.id
				ORDER BY e.datum_start ASC
			")
        );

        // Construct arrays with statistics per year (e.g. '1415')
        $camps = Event::where('type', 'kamp')
            ->where('datum_start', '<=', $maxDate)
            ->where('datum_start', '>=', $minDate)
            ->notCancelled()
            ->orderBy('datum_start')
            ->get();
        $num_members = [];

        $num_participants = [];
        $num_members_new = [];
        $num_participants_new = [];
        $num_camps = [];
        $member_ids = [];
        $participant_ids = [];
        $num_trainers = [];
        $num_trainers_new = [];

        foreach ($camps as $camp) {
            preg_match('/\d{4}/', $camp->code, $matches); // obtains the year string from the camp code, e.g. '1415'
            $year = $matches[0];
            $m = $camp->members()->count();
            $mids = $camp->members()->pluck('id')->toArray();
            $pids = $camp->participants()->pluck('id')->toArray();
            $p = $camp->participants()->count();

            // New members
            $m_n = 0;
            foreach ($camp->members as $member) {
                $num = $member->events()->where('type', 'kamp')->where('datum_eind', '<', $camp->datum_start)->count();
                if ($num === 0) {
                    $m_n++;
                }
            }

            // New participants
            $p_n = 0;
            foreach ($camp->participants as $participant) {
                $num = $participant->events()->where('datum_eind', '<', $camp->datum_start)->count();
                if ($num === 0) {
                    $p_n++;
                }
            }

            if (array_key_exists($year, $num_members)) {
                $num_members[$year] += $m;
                $num_members_new[$year] += $m_n;
                $num_participants[$year] += $p;
                $num_participants_new[$year] += $p_n;
                $member_ids[$year] = array_merge($member_ids[$year], $mids);
                $participant_ids[$year] = array_merge($participant_ids[$year], $pids);
                ++$num_camps[$year];
            } else {
                $num_members[$year] = $m;
                $num_members_new[$year] = $m_n;
                $num_participants[$year] = $p;
                $num_participants_new[$year] = $p_n;
                $member_ids[$year] = $mids;
                $participant_ids[$year] = $pids;
                $num_camps[$year] = 1;
            }
        }

        // Construct graph data
        $data['membGrowth'][] = ['Jaar', 'Totaal', 'Uniek', 'Nieuw'];
        $data['partGrowth'][] = ['Jaar', 'Totaal', 'Uniek', 'Nieuw'];
        $data['percNew'][] = ['Jaar', 'Leiding', 'Deelnemers'];
        $data['membPartRatio'][] = ['Jaar', 'Ratio'];
        $data['aveNumCamps'][] = ['Jaar', 'Leiding', 'Deelnemers'];
        $data['aveNumPerCamp'][] = ['Jaar', 'Leiding', 'Deelnemers'];
        $data['maleFemaleRatio'][] = ['Jaar', 'Leiding', 'Deelnemers'];
        foreach ($num_members as $k => $v) {
            if ($num_members[$k] !== 0 && $num_participants[$k] !== 0) {
                $year = substr((string) $k, 0, 2) . '-' . substr((string) $k, 2, 2);
                $data['membGrowth'][] = [$year, $num_members[$k], count(array_unique($member_ids[$k])), $num_members_new[$k]];
                $data['partGrowth'][] = [$year, $num_participants[$k], count(array_unique($participant_ids[$k])), $num_participants_new[$k]];
                $data['percNew'][] = [$year, round(($num_members_new[$k] / $num_members[$k]) * 100, 1), round(($num_participants_new[$k] / $num_participants[$k]) * 100, 1)];
                $data['membPartRatio'][] = [$year, round($num_members[$k] / $num_participants[$k], 2)];
                $data['aveNumCamps'][] = [$year, round(count($member_ids[$k]) / count(array_unique($member_ids[$k])), 2), round(count($participant_ids[$k]) / count(array_unique($participant_ids[$k])), 2)];
                $data['aveNumPerCamp'][] = [$year, count($member_ids[$k]) / $num_camps[$k], count($participant_ids[$k]) / $num_camps[$k]];
            }
        }

        // Same round for trainings
        $trainings = Event::where('type', 'training')
            ->where('datum_start', '>=', $minDate)
            ->where('datum_start', '<=', $maxDate)
            ->notCancelled()
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
                if ($num === 0) {
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
            if ($v !== []) {
                $year = substr((string) $k, 0, 2) . '-' . substr((string) $k, 2, 2);
                $data['trainerGrowth'][] = [$year, $num_trainers[$k], count(array_unique($member_ids[$k])), $num_trainers_new[$k]];
                $data['aveNumTrainings'][] = [$year, round(count($v) / count(array_unique($v)), 2)];
            }
        }

        // Analysis of participant registration
        $graphStart = -90;
        $camps = Event::where('type', 'kamp')->where('datum_start', '>', '2012-11-01')->orderBy('datum_start', 'asc')->get();
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
                if (in_array($i, $daysArray, true)) {
                    $p = $p + count(array_keys($daysArray, $i, true));
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
                    'enabled' => false,
                ],
                'states' => [
                    'hover' => [
                        'lineWidth' => 4,
                    ],
                ],
                'data' => $data_series,
            ];
        }

        // Analysis of participants' preference for camp type (from reviews)
        $reviews = Review::whereNotNull('kampkeuze')->pluck('kampkeuze');
        $prefs_review_count = $reviews->count();
        $reviews_flat = $reviews->flatten()->toArray();

        $camp_prefs = [];
        foreach (array_count_values($reviews_flat) as $option => $count) {
            $camp_prefs[] = [
                'option' => $option,
                'votes' => $count,
            ];
        }

        $newUserStart = Carbon::now()->subYear(1);
        $raw_new_users_per_source = DB::select(
            DB::raw("
			select *
				from ( 
					select count(*) as amount
						, hoebij as source
						, 'members' as type 
					from members 
					where created_at > :1
					group by hoebij 
				union all
					select count(*) as amount
						, hoebij as source
						, 'participants' as type
					from participants
					where created_at > :2
					group by hoebij
				) x
				order by amount desc, type
			"),
            [
                $newUserStart,
                $newUserStart,
            ]
        );

        $new_users_per_source = [];
        foreach ($raw_new_users_per_source as $row) {
            $new_users_per_source[$row->source] = $new_users_per_source[$row->source] ?? [
                'source' => $row->source,
                'participants' => 0,
                'members' => 0,
            ];
            $new_users_per_source[$row->source][$row->type] = $row->amount;
        }
        $new_users_per_source = array_values($new_users_per_source);

        $camp_prices = DB::select(
            DB::raw("
			select cast(year(datum_start) as CHAR(4)) as year
				, code
				, prijs as price
				, naam as name
				, REGEXP_SUBSTR(code, '[A-Za-z]+') as type
				, REGEXP_SUBSTR(code, '[0-9]{4}') as commissie_year
				, case 
					when REGEXP_SUBSTR(code, '[A-Za-z]+') in ('N', 'K', 'W') then 'Winterkamp'
					when REGEXP_SUBSTR(code, '[A-Za-z]+') in ('P') then 'Paaskamp'
					when REGEXP_SUBSTR(code, '[A-Za-z]+') in ('V') then 'Voorjaarskamp'
					when REGEXP_SUBSTR(code, '[A-Za-z]+') in ('Z') then 'Zomerkamp'
					when REGEXP_SUBSTR(code, '[A-Za-z]+') in ('L') then 'Lentekamp'
					when REGEXP_SUBSTR(code, '[A-Za-z]+') in ('M') then 'Meikamp'
					when REGEXP_SUBSTR(code, '[A-Za-z]+') in ('H') then 'Herfstkamp'
					else 'Unknown'
				  end as label
				, DATEDIFF(datum_eind, datum_start) as days
				, cast(round(prijs / DATEDIFF(datum_eind, datum_start)) as int) as price_norm
				
			from events e
			where type = 'kamp'
			  and datum_start > '2015-01-01'	
			  order by datum_start asc   
			"),
            []
        );

        return view(
            'pages.graphs',
            compact(
                'data',
                'registration_series',
                'camp_prefs',
                'prefs_review_count',
                'avg_days_before_event',
                'new_users_per_source',
                'camp_prices'
            )
        );
    }
}
