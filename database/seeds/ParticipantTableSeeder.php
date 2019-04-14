<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Participant;

class ParticipantTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
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
			'opmerkingen_admin' => 'Dit is een opmerking die wat zegt!'
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
			'opmerkingen_admin' => 'Af en toe wat vervelend stuiterig, maar verder een topmeid.'
		]);

	}

}
