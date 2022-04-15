<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Participant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParticipantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('participants')->delete();

        Participant::create([
            'voornaam' => 'Jaap',
            'tussenvoegsel' => 'de',
            'achternaam' => 'Vries',
            'geslacht' => 'M',
            'geboortedatum' => '2000-10-05',
            'adres' => 'Regenwoudlaan 17',
            'postcode' => '9187 CC',
            'plaats' => 'Elst',
            'telefoon_deelnemer' => '0690287549',
            'telefoon_ouder_vast' => '0698275982',
            'telefoon_ouder_mobiel' => '0692837982',
            'email_deelnemer' => 'jaapiejee@hotmail.com',
            'email_ouder' => 'vries@mijndomein.nl',
            'inkomen' => 0,
            'school' => 'Planckgas Lyceum',
            'niveau' => 'VWO',
            'klas' => 4,
            'hoebij' => 'Google',
            'opmerkingen' => 'Ik eet het liefst heel veel vlees!',
        ]);

        Participant::create([
            'voornaam' => 'Annabelle',
            'tussenvoegsel' => '',
            'achternaam' => 'Zomers',
            'geslacht' => 'V',
            'geboortedatum' => '2003-06-17',
            'adres' => 'Strijkbout 1',
            'postcode' => '0892 PC',
            'plaats' => 'Hoofddorp',
            'telefoon_deelnemer' => '0698023759',
            'telefoon_ouder_vast' => '0686723998',
            'telefoon_ouder_mobiel' => '0612345678',
            'email_deelnemer' => 'annabelle@zomers.nl',
            'email_ouder' => 'peter@zomers.nl',
            'mag_gemaild' => 0,
            'inkomen' => 2,
            'school' => 'Vreemans College',
            'niveau' => 'HAVO',
            'klas' => 1,
            'hoebij' => 'Zusje',
            'opmerkingen' => 'Enorm veel zin in kamp.',
        ]);

        Participant::create([
            'voornaam' => 'Iris',
            'tussenvoegsel' => '',
            'achternaam' => 'Anders',
            'geslacht' => 'V',
            'geboortedatum' => '2007-06-17',
            'adres' => 'Mediarena 2',
            'postcode' => '1114 BC',
            'plaats' => 'Amsterdam',
            'telefoon_deelnemer' => '0698023759',
            'telefoon_ouder_vast' => '0686723998',
            'telefoon_ouder_mobiel' => '0612345678',
            'email_deelnemer' => 'iris@anders.nl',
            'email_ouder' => 'iris@anders.nl',
            'mag_gemaild' => 0,
            'inkomen' => 2,
            'school' => 'Vreemans College',
            'niveau' => 'HAVO',
            'klas' => 1,
            'hoebij' => 'Anders',
            'opmerkingen' => 'Geen zin in kamp.',
        ]);

        Participant::create([
            'voornaam' => 'Henk',
            'tussenvoegsel' => '',
            'achternaam' => 'Janssen',
            'geslacht' => 'M',
            'geboortedatum' => '2004-12-01',
            'adres' => 'Beukenstraat',
            'postcode' => '0101 AA',
            'plaats' => 'Almelo',
            'telefoon_deelnemer' => '0698023759',
            'telefoon_ouder_vast' => '0686723998',
            'telefoon_ouder_mobiel' => '0612345678',
            'email_deelnemer' => 'henk@janssen.nl',
            'email_ouder' => 'piet@janssen.nl',
            'mag_gemaild' => 0,
            'inkomen' => 2,
            'school' => 'Vreemans College',
            'niveau' => 'HAVO',
            'klas' => 4,
            'hoebij' => 'Zusje',
            'opmerkingen' => 'Enorm veel zin in kamp.',
        ]);

        Participant::create([
            'voornaam' => 'Jan',
            'tussenvoegsel' => '',
            'achternaam' => 'Janssen',
            'geslacht' => 'M',
            'geboortedatum' => '2004-12-01',
            'adres' => 'Beukenstraat',
            'postcode' => '0101 AA',
            'plaats' => 'Almelo',
            'telefoon_deelnemer' => '0698023759',
            'telefoon_ouder_vast' => '0686723998',
            'telefoon_ouder_mobiel' => '0612345678',
            'email_deelnemer' => 'jan@janssen.nl',
            'email_ouder' => 'piet@janssen.nl',
            'mag_gemaild' => 0,
            'inkomen' => 2,
            'school' => 'Vreemans College',
            'niveau' => 'HAVO',
            'klas' => 1,
            'hoebij' => 'Zusje',
            'opmerkingen' => 'Enorm veel zin in kamp.',
        ]);
    }
}
