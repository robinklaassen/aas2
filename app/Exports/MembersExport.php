<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class MembersExport implements FromCollection, WithTitle, WithHeadings
{
    use Exportable;

    public function collection()
    {
        return Member::all('id', 'voornaam', 'tussenvoegsel', 'achternaam', 'geslacht', 'geboortedatum', 'adres', 'postcode', 'plaats', 'telefoon', 'email', 'email_anderwijs', 'iban', 'soort', 'eindexamen', 'studie', 'afgestudeerd', 'hoebij', 'kmg', 'ranonkeltje', 'vog', 'ervaren_trainer', 'incasso', 'datum_af', 'opmerkingen', 'created_at', 'updated_at');
    }

    public function headings(): array
    {
        return ['id', 'voornaam', 'tussenvoegsel', 'achternaam', 'geslacht', 'geboortedatum', 'adres', 'postcode', 'plaats', 'telefoon', 'email', 'email_anderwijs', 'iban', 'soort', 'eindexamen', 'studie', 'afgestudeerd', 'hoebij', 'kmg', 'ranonkeltje', 'vog', 'ervaren_trainer', 'incasso', 'datum_af', 'opmerkingen', 'opmerkingen_admin', 'created_at', 'updated_at'];
    }

    public function title(): string
    {
        return 'members';
    }
}
