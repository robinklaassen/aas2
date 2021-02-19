<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\ViewModels\Api\PublicEvent;

class EventsController
{
    public function viewPublicParticipantEvents()
    {
        $events = Event::open()
				->participantEvent()
				->public()
				->orderBy('datum_start', 'asc')
				->get();

        return response()->json(
            $events->map(fn ($event) => PublicEvent::fromEvent($event))
        );
    }
}
