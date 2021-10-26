<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // A course belongs to many members
    public function members()
    {
        return $this->belongsToMany('App\Member')->withPivot('klas');
    }

    // Get participants on a specific camp that have this course
    public function participantsOnCamp(Event $camp, bool $onlyPlacedParticipants = false)
    {
        $participants = DB::table('course_event_participant')
            ->where('event_id', $camp->id)
            ->where('course_id', $this->id)
            ->join('participants', 'course_event_participant.participant_id', '=', 'participants.id')
            ->orderBy('voornaam')
            ->get();

        if ($onlyPlacedParticipants) {
            $participants = $participants->filter(
                fn ($p) => $camp->participants()->find($p->id)->pivot->geplaatst
            );
        }

        return $participants;
    }
}
