<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Participant;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ListsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // Stats
        $stats = [];

        $stats['most'] = [];
        $types = [
            'kamp' => 'kampen',
            'training' => 'trainingen',
            'overig' => 'overige activiteiten',
        ];
        foreach ($types as $type => $typename) {
            $most = DB::table('event_member')
                ->selectRaw('count(event_id) as count, member_id')
                ->join('events', 'event_member.event_id', '=', 'events.id')
                ->where('type', $type)
                ->groupBy('member_id')
                ->orderBy('count', 'desc')
                ->get();
            $highest_count = $most[0]->count;
            $mosts = $most
                ->filter(function ($el) use ($highest_count) {
                    return $el->count === $highest_count;
                });

            $members = [];
            foreach ($mosts as $el) {
                $members[] = Member::findOrFail($el->member_id);
            }
            $string = '';
            $stats['most'][$type] = [
                'count' => $highest_count,
                'members' => $members,
            ];
        }

        // Average number of days of participant registration before camp start date
        $regs = DB::table('event_participant')
            ->select('event_participant.created_at as reg_date', 'events.datum_start as camp_date')
            ->where('event_participant.created_at', '>', '2005-01-01')  // Old registrations with unknown date have 01-01-2000, filter them out
            ->join('events', 'event_participant.event_id', '=', 'events.id', 'left')
            ->get();
        $days_arr = [];
        foreach ($regs as $r) {
            $reg_date = Carbon::parse($r->reg_date);
            $camp_date = Carbon::parse($r->camp_date);
            $days_arr[] = $reg_date->diffInDays($camp_date, false);
        }
        $stats['average_days_reg'] = round(array_sum($days_arr) / count($days_arr));

        $stats['average_review_score'] = round(Review::pluck('cijfer')->avg(), 2);
        $stats['num_reviews'] = Review::all()->count();

        // Ranonkeltje
        $ranonkeltjePapier = Member::whereIn('ranonkeltje', ['papier', 'beide'])->where('soort', '<>', 'oud')->orderBy('voornaam', 'asc')->get();
        $ranonkeltjeDigitaal = Member::whereIn('ranonkeltje', ['digitaal', 'beide'])->where('soort', '<>', 'oud')->orderBy('voornaam', 'asc')->get();

        // Ervaren trainers
        $trainerList = Member::where('ervaren_trainer', 1)->where('soort', '<>', 'oud')->orderBy('voornaam', 'asc')->get();
        $oldTrainerList = Member::where('ervaren_trainer', 1)->where('soort', 'oud')->orderBy('voornaam', 'asc')->get();

        // Niet betaalde deelnemers
        $unpaidList = DB::table('event_participant')
            ->select('participant_id', 'voornaam', 'tussenvoegsel', 'achternaam', 'event_id', 'naam', 'code', 'event_participant.created_at as inschrijving')
            ->where('datum_betaling', '0000-00-00')
            ->join('events', 'event_participant.event_id', '=', 'events.id')
            ->join('participants', 'event_participant.participant_id', '=', 'participants.id')
            ->get();

        // Leden zonder KMG
        $kmgList = Member::where('kmg', 0)->orderBy('voornaam')->get();

        // Aspiranten
        $aspirantList = Member::where('soort', 'aspirant')->orderBy('voornaam')->get();

        // Verjaardagen
        $members = Member::whereIn('soort', ['normaal', 'aspirant'])->where('publish_birthday', 1)->get();
        foreach ($members as $member) {
            $datum = $member->geboortedatum;
            $dag = $datum->day;
            $maand = $datum->month;

            $vandaag = ($dag === date('d') && $maand === date('m')) ? 1 : 0;
            $leeftijd = $datum->age;

            $birthdayList[] = [
                'id' => $member->id,
                'naam' => str_replace('  ', ' ', $member->voornaam . ' ' . $member->tussenvoegsel . ' ' . $member->achternaam),
                'email' => $member->email,
                'dag' => $dag,
                'maand' => $maand,
                'vandaag' => $vandaag,
                'leeftijd' => $leeftijd,
            ];
        }

        $birthdayList = array_values(Arr::sort($birthdayList, function ($member) {
            return 100 * $member['maand'] + $member['dag'];
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
            12 => 'december',
        ];

        // Leden en deelnemers zonder gekoppelde kampen
        $membersWithoutEvents = Member::where('soort', '<>', 'oud')->orderBy('created_at')->get()->filter(function ($member) {
            return $member->events->count() === 0;
        });

        $oldMembers = Member::where('soort', 'oud')->orderBy('created_at')->get();

        $participantsWithoutCamps = Participant::orderBy('created_at')->get()->filter(function ($part) {
            return $part->events->count() === 0;
        });

        // Mailadressen voor een deelnemermailing (bijv. bij kortingsacties)
        $startDate = Carbon::now()->subYears(19);
        $participantMailingList = Participant::where('mag_gemaild', 1)->where('geboortedatum', '>', $startDate->toDateString())->get();

        $inschrijvingen_deelnemers = DB::table('event_participant')
            ->join('participants', 'event_participant.participant_id', '=', 'participants.id')
            ->join('events', 'event_participant.event_id', '=', 'events.id')
            ->where('event_participant.created_at', '>', Db::raw('DATE_SUB(NOW(), interval 1 year)'))
            ->select([
                'event_participant.participant_id',
                'event_participant.event_id',
                'event_participant.created_at as kamp_aanmeld_datum',
                'events.naam as kamp_naam',
                'participants.voornaam',
                'participants.achternaam',
                'participants.tussenvoegsel',
                'participants.hoebij',
                DB::raw('
					case when not exists (
						select * 
						  from event_participant _ep 
						  join events _e on _ep.event_id = _e.id
						 where 1=1
						   and _ep.participant_id = participants.id
						   and _ep.event_id != events.id
						   and _e.datum_start < events.datum_start
						) 
						then true
						else false 
					end as is_nieuw'),
            ])
            ->orderByDesc('event_participant.created_at')->get();

        $nieuwe_leiding = Member::where('members.created_at', '>', Carbon::now()->subYear())
            ->orderByDesc('members.created_at')->get();

        return view('pages.lists', compact(
            'stats',
            'types',
            'ranonkeltjePapier',
            'ranonkeltjeDigitaal',
            'trainerList',
            'oldTrainerList',
            'unpaidList',
            'kmgList',
            'aspirantList',
            'birthdayList',
            'monthName',
            'membersWithoutEvents',
            'inschrijvingen_deelnemers',
            'nieuwe_leiding',
            'participantsWithoutCamps',
            'participantMailingList',
            'oldMembers'
        ));
    }
}
