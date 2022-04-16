<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];

    // A course belongs to many members
    public function members()
    {
        return $this->belongsToMany(Member::class)->withPivot('klas');
    }

    // Get participants on a specific camp that have this course
    public function participantsOnCamp(Event $camp, bool $onlyPlacedParticipants = false)
    {
        return Participant::whereIn(
            'id',
            DB::table('course_event_participant')
                ->where('event_id', $camp->id)
                ->where('course_id', $this->id)
                ->pluck('participant_id')
        )
            ->orderBy('klas', 'desc')
            ->get()
            ->filter(function ($p) use ($camp, $onlyPlacedParticipants) {
                return ! $onlyPlacedParticipants || $p->events()->find($camp->id)->pivot->geplaatst;
            });
    }
}
