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
			'beschrijving' => 'Ga mee op kamp!'
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
			'openbaar' => '0'
		]);

		Event::create([
			'naam' => 'Training Meikamp',
			'code' => 'TM1415',
			'type' => 'training',
			'datum_start' => '2015-04-24',
			'datum_eind' => '2015-04-26',
			'location_id' => 2
		]);

		Event::create([
			'naam' => 'VW zomer 2015',
			'code' => 'VWZ1415',
			'type' => 'overig',
			'datum_start' => '2015-06-12',
			'datum_eind' => '2015-06-14',
			'location_id' => 1
		]);


		Event::create([
			'naam' => 'Nieuwjaarskamp',
			'code' => 'N8990',
			'type' => 'kamp',
			'datum_voordag' => '2090-01-01',
			'datum_start' => '2090-01-02',
			'datum_eind' => '2090-01-05',
			'location_id' => 2,
			'prijs' => 500,
			'streeftal' => 10,
			'openbaar' => '1'
		]);

		Event::create([
			'naam' => 'Training Nieuwjaarskamp',
			'code' => 'TN8990',
			'type' => 'training',
			'datum_start' => '2089-12-17',
			'datum_eind' => '2089-12-18',
			'location_id' => 1
		]);

		Event::create([
			'naam' => 'Online CoronaKamp',
			'code' => 'OK0001',
			'type' => 'online',
			'package_type' => 'online-tutoring',
			'datum_start' => '2020-04-01',
			'datum_eind' => '2021-05-01',
			'location_id' => 1,
			'streeftal' => 10,
			'openbaar' => '1',
			'prijs' => 0,
		]);

		Event::create([
			'naam' => 'Meikamp',
			'code' => 'M1516',
			'type' => 'kamp',
			'datum_voordag' => '2016-05-01',
			'datum_start' => '2016-05-03',
			'tijd_start' => '13:00:00',
			'datum_eind' => '2016-05-10',
			'tijd_eind' => '17:00:00',
			'location_id' => 1,
			'prijs' => 420,
			'streeftal' => 8,
			'vol' => '1',
			'beschrijving' => 'Ga mee op kamp!'
		]);

		Event::create([
			'naam' => 'Meikamp',
			'code' => 'M1617',
			'type' => 'kamp',
			'datum_voordag' => '2017-05-01',
			'datum_start' => '2017-05-03',
			'tijd_start' => '13:00:00',
			'datum_eind' => '2017-05-10',
			'tijd_eind' => '17:00:00',
			'location_id' => 1,
			'prijs' => 450,
			'streeftal' => 8,
			'vol' => '1',
			'beschrijving' => 'Ga mee op kamp!'
		]);

		Event::create([
			'naam' => 'Meikamp',
			'code' => 'M1617',
			'type' => 'kamp',
			'datum_voordag' => '2017-05-01',
			'datum_start' => '2017-05-03',
			'tijd_start' => '13:00:00',
			'datum_eind' => '2017-05-10',
			'tijd_eind' => '17:00:00',
			'location_id' => 1,
			'prijs' => 550,
			'streeftal' => 8,
			'vol' => '1',
			'beschrijving' => 'Ga mee op kamp!'
		]);

		Event::create([
			'naam' => 'Zomerkamp 1',
			'code' => 'Z1516-1',
			'type' => 'kamp',
			'datum_voordag' => '2016-07-28',
			'datum_start' => '2016-08-01',
			'datum_eind' => '2016-08-08',
			'location_id' => 2,
			'prijs' => 520,
			'openbaar' => '0'
		]);

		Event::create([
			'naam' => 'Zomerkamp 2',
			'code' => 'Z1516-2',
			'type' => 'kamp',
			'datum_voordag' => '2016-07-28',
			'datum_start' => '2016-08-01',
			'datum_eind' => '2016-08-08',
			'location_id' => 2,
			'prijs' => 520,
			'openbaar' => '0'
		]);


		Event::create([
			'naam' => 'Yee-Oldy-kamp',
			'code' => 'M0910',
			'type' => 'kamp',
			'datum_voordag' => '2009-05-01',
			'datum_start' => '2009-05-03',
			'tijd_start' => '13:00:00',
			'datum_eind' => '2009-05-10',
			'tijd_eind' => '17:00:00',
			'location_id' => 1,
			'prijs' => 400,
			'streeftal' => 8,
			'vol' => '1',
			'beschrijving' => 'Ga mee op kamp!'
		]);

	}
}
