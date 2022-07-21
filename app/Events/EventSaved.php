<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Event as EventModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class EventSaved extends Event
{
    use Dispatchable;

    use InteractsWithSockets;

    use SerializesModels;

    private const PUBLIC_AVAILABLE_FIELDS = [
        'naam',
        'type',
        'datum_start',
        'datum_eind',
        'beschrijving',
        'location_id',
        'vol',
        'openbaar',
    ];

    public function __construct(
        private EventModel $event
    ) {
    }

    public function publicDataWasChanged(): bool
    {
        return $this->event->wasChanged(self::PUBLIC_AVAILABLE_FIELDS);
    }
}
