<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class EventNightRegisterSheetTrainers implements FromCollection, WithHeadings, WithTitle
{
    protected $event;

    public function __construct(\App\Models\Event $event)
    {
        $this->event = $event;
    }

    public function headings(): array
    {
        return [
            'Voornaam', 'Tussenvoegsel', 'Achternaam', 'Geboortedatum', 'Adres', 'Postcode', 'Plaats',
        ];
    }

    public function collection()
    {
        $headerLowercase = array_map('strtolower', $this->headings());
        return $this->event->members()->orderBy('voornaam')->get($headerLowercase);
    }

    public function title(): string
    {
        return 'Leiding';
    }
}
