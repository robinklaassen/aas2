<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Event as EventModel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class FinishedEvent
{
    use Dispatchable;

    use InteractsWithSockets;

    use SerializesModels;

    private EventModel $event;

    public function __construct(
        EventModel $event
    ) {
        $this->event = $event;
    }

    public function getEvent(): EventModel
    {
        return $this->event;
    }
}
