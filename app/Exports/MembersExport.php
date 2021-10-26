<?php

declare(strict_types=1);

namespace App\Exports;

use App\Member;
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

    // public function map($member): array
    // {
    //     return [
    //         $member->id,
    //         $member->voornaam,
    //         $member->tussenvoegsel,
    //         $member->achternaam,
    //         $member->geslacht,
    //         $member->geboortedatum,
    //         $member->adres,
    //         $member->postcode,
    //         $member->plaats,
    //         $member->telefoon,
    //         $member->email,
    //         $member->email_anderwijs,
    //         $member->iban,
    //         $member->soort,
    //         $member->eindexamen,
    //         $member->studie,
    //         $member->afgestudeerd,
    //         $member->hoebij,
    //         $member->kmg, $member->ranonkeltje, $member->vog, $member->ervaren_trainer, $member->incasso, $member->datum_af, $member->opmerkingen, $member->opmerkingen_admin, $member->created_at, $member->updated_at
    //     ];
    // }

    public function headings(): array
    {
        return ['id', 'voornaam', 'tussenvoegsel', 'achternaam', 'geslacht', 'geboortedatum', 'adres', 'postcode', 'plaats', 'telefoon', 'email', 'email_anderwijs', 'iban', 'soort', 'eindexamen', 'studie', 'afgestudeerd', 'hoebij', 'kmg', 'ranonkeltje', 'vog', 'ervaren_trainer', 'incasso', 'datum_af', 'opmerkingen', 'opmerkingen_admin', 'created_at', 'updated_at'];
    }

    public function title(): string
    {
        return 'members';
    }
}
