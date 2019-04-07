<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Event;

class EventTableSeeder extends Seeder {

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
			'datum_eind' => '2015-05-10',
			'location_id' => 1,
			'prijs' => 400,
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
		
	}

}
