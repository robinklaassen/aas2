<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EventNightRegisterReport implements WithMultipleSheets
{
    use Exportable;

    protected $event;

    public function __construct(\App\Event $event)
    {
        $this->event = $event;
    }

    public function sheets(): array
    {
        return [
            new EventNightRegisterSheetParticipants($this->event),
            new EventNightRegisterSheetTrainers($this->event),
        ];
    }
}
