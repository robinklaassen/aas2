<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class EventNightRegisterSheetParticipants implements FromCollection, WithHeadings, WithTitle
{

    protected $event;

    public function __construct(\App\Event $event) {
        $this->event = $event;
    }

    public function headings(): array
    {
        return [
            'Voornaam', 'Tussenvoegsel', 'Achternaam', 'Geboortedatum', 'Adres', 'Postcode', 'Plaats'
        ];
    }

    public function collection()
    {
        $headerLowercase = array_map('strtolower', $this->headings());
        return $this->event->participants()->orderBy('voornaam')->get($headerLowercase);
    }


    public function title(): string
    {
        return 'Deelnemers';
    }

}