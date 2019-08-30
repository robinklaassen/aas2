<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Event;

class EventTableSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('events')->delete();

		Event::create([
			'naam' => 'Meikamp',
			'code' => 'M1415',
			'type' => 'kamp',
			'datum_voordag' => '2015-05-01',
			'datum_start' => '2015-05-03',
			'tijd_start' => '13:00:00',
			'datum_eind' => '2015-05-10',
			'tijd_eind' => '17:00:00',
			'location_id' => 1,
			'prijs' => 400,
			'streeftal' => 8,
			'vol' => '1',
			'beschrijving' => 'Ga mee op kamp!',
			'opmerkingen' => 'Beste kamp ooit!'
		]);

		Event::create([
			'naam' => 'Zomerkamp 1',
			'code' => 'Z1415-1',
			'type' => 'kamp',
			'datum_voordag' => '2015-07-28',
			'datum_start' => '2015-08-01',
			'datum_eind' => '2015-08-08',
			'location_id' => 2,
			'prijs' => 350,
			'openbaar' => '0',
			'opmerkingen' => 'Dit gaat natuurlijk nooit goed.'
		]);


		Event::create([
			'naam' => 'Training Meikamp',
			'code' => 'TM1415',
			'type' => 'training',
			'datum_start' => '2015-04-24',
			'datum_eind' => '2015-04-26',
			'location_id' => 2,
			'opmerkingen' => 'Wat een vette training!'
		]);

		Event::create([
			'naam' => 'VW zomer 2015',
			'code' => 'VWZ1415',
			'type' => 'overig',
			'datum_start' => '2015-06-12',
			'datum_eind' => '2015-06-14',
			'location_id' => 1,
			'opmerkingen' => 'Dit is geen kamp en ook geen training.'
		]);


		Event::create([
			'naam' => 'Nieuwjaarskamp',
			'code' => 'N8990',
			'type' => 'kamp',
			'datum_voordag' => '2090-01-01',
			'datum_start' => '2090-01-02',
			'datum_eind' => '2090-01-05',
			'location_id' => 2,
			'prijs' => 1000,
			'streeftal' => 10,
			'openbaar' => '1',
			'opmerkingen' => 'Dit ligt wel heel ver in de toekomst!'
		]);
	}
}
