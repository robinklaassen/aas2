<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Participant;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ParticipantsExport implements FromCollection, WithTitle, WithHeadings
{

    use Exportable;

    public function collection()
    {
        return Participant::all();
    }

    public function title(): string
    {
        return 'deelnemers';
    }

    public function headings(): array
    {
        return ['id', 'voornaam', 'tussenvoegsel', 'achternaam', 'geslacht', 'geboortedatum', 'adres', 'postcode', 'plaats', 'telefoon_deelnemer', 'telefoon_ouder_vast', 'telefoon_ouder_mobiel', 'email_deelnemer', 'email_ouder', 'mag_gemaild', 'inkomen', 'inkomensverklaring', 'school', 'niveau', 'klas', 'hoebij', 'opmerkingen', 'opmerkingen_admin', 'created_at', 'updated_at'];
    }
}
