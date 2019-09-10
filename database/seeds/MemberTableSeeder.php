<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Member;

class MemberTableSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('members')->delete();

		Member::create([
			'voornaam' => 'Ranonkeltje',
			'tussenvoegsel' => 'van',
			'achternaam' => 'Anderwijs',
			'geslacht' => 'V',
			'geboortedatum' => '1985-09-01',
			'adres' => 'Plantsoen 5',
			'postcode' => '3456 BZ',
			'plaats' => 'Buren',
			'telefoon' => '0612345678',
			'email' => 'r@spookjes.nl',
			'email_anderwijs' => 'ranonkeltje@anderwijs.nl',
			'iban' => 'NL23ASNB0983238911',
			'soort' => 'normaal',
			'eindexamen' => 'VWO',
			'studie' => 'Spookjeskunde',
			'afgestudeerd' => '1',
			'rijbewijs' => '1',
			'hoebij' => 'Awesomeheid',
			'kmg' => '1',
			'ranonkeltje' => '1',
			'vog' => '1',
			'ervaren_trainer' => '1',
			'incasso' => '1',
			'opmerkingen' => 'Ik ben het allergaafste spookje ooit! En ook nog eens heel bescheiden.',
			'opmerkingen_admin' => 'Wauw wat is zij tof inderdaad.'
		]);

		Member::create([
			'voornaam' => 'Jön',
			'achternaam' => 'Snow',
			'geslacht' => 'M',
			'geboortedatum' => '2010-03-05',
			'adres' => 'Castle Black',
			'postcode' => '5555 XX',
			'plaats' => 'The Wall',
			'telefoon' => '0683926753',
			'email' => 'jon@snow.com',
			'email_anderwijs' => 'jonsnow@anderwijs.nl',
			'soort' => 'aspirant',
			'eindexamen' => 'VWO',
			'studie' => 'Knowing',
			'afgestudeerd' => '0',
			'hoebij' => 'Lord commander Mormont',
			'kmg' => '1',
			'ranonkeltje' => '0',
			'vog' => '1',
			'ervaren_trainer' => '1',
			'opmerkingen' => 'Winter is coming.',
			'opmerkingen_admin' => 'Hij weet echt niks.'
		]);

		Member::create([
			'voornaam' => 'Dingo',
			'achternaam' => 'Krijgsman',
			'geslacht' => 'M',
			'geboortedatum' => '1999-11-11',
			'adres' => 'Miernemoepie',
			'postcode' => '1313 PD',
			'plaats' => 'Flipstoeje',
			'telefoon' => '0690817342',
			'email' => 'dingo@jemoeder.nl',
			'email_anderwijs' => 'dingo@anderwijs.nl',
			'soort' => 'info',
			'eindexamen' => 'HAVO',
			'studie' => 'Jongleren',
			'afgestudeerd' => '1',
			'hoebij' => 'Mijn moeder',
			'kmg' => '0',
			'ranonkeltje' => '1',
			'vog' => '0',
			'ervaren_trainer' => '0',
			'opmerkingen' => 'YEEHAW!',
			'opmerkingen_admin' => 'Licht autistisch en een beetje gestoord.'
		]);

		Member::create([
			'voornaam' => 'Bert',
			'tussenvoegsel' => 'van der',
			'achternaam' => 'Ven',
			'geslacht' => 'M',
			'geboortedatum' => '1950-03-16',
			'adres' => 'Bejaardenstraat 17',
			'postcode' => '8866 ZX',
			'plaats' => 'Grandpatown',
			'telefoon' => '0689726239',
			'email' => 'b.vanderven@planetzz.nl',
			'email_anderwijs' => '',
			'soort' => 'oud',
			'eindexamen' => 'HAVO',
			'studie' => 'Geriatrie',
			'afgestudeerd' => '1',
			'hoebij' => 'De krant',
			'kmg' => '1',
			'ranonkeltje' => '0',
			'vog' => '1',
			'ervaren_trainer' => '1',
			'opmerkingen' => 'Ik ben doof aan één kant en slechtziend aan de andere.',
			'opmerkingen_admin' => 'Serieus, waar is deze vent vandaan gekomen?'
		]);
	}
}
