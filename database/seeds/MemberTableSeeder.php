<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
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
			'opmerkingen' => 'Ik ben het allergaafste spookje ooit! En ook nog eens heel bescheiden. (president)',
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
			'opmerkingen' => 'Winter is coming. (kantoor-ci)',
			'publish_birthday' => '0',
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
			'opmerkingen' => 'Ik ben doof aan één kant en slechtziend aan de andere.'
		]);

		Member::create([
			'voornaam' => 'Marlies',
			'tussenvoegsel' => '',
			'achternaam' => 'Overje',
			'geslacht' => 'V',
			'geboortedatum' => '1992-01-21',
			'adres' => 'De Donk 12',
			'postcode' => '4824 CS',
			'plaats' => 'Breda',
			'telefoon' => '0698763541',
			'email' => 'm.overje@freedom.nl',
			'email_anderwijs' => '',
			'soort' => 'normaal',
			'eindexamen' => 'VWO',
			'studie' => 'Econometrie',
			'afgestudeerd' => '1',
			'hoebij' => 'Gelubd door Ranonkeltje',
			'kmg' => '1',
			'ranonkeltje' => '1',
			'vog' => '1',
			'ervaren_trainer' => '0',
			'opmerkingen' => 'Ben goed met geld enzo. Penningmeester.'
		]);

		Member::create([
			'voornaam' => 'Suzanne',
			'tussenvoegsel' => 'van der',
			'achternaam' => 'Laars',
			'geslacht' => 'V',
			'geboortedatum' => '1994-06-14',
			'adres' => 'Haanderik 174',
			'postcode' => '3401 EZ',
			'plaats' => 'IJsselstein',
			'telefoon' => '0679561005',
			'email' => 's.vanderlaars@freedom.nl',
			'email_anderwijs' => '',
			'soort' => 'normaal',
			'eindexamen' => 'VWO',
			'studie' => 'Wiskunde',
			'afgestudeerd' => '1',
			'hoebij' => 'Oud deelnemer',
			'kmg' => '1',
			'ranonkeltje' => '1',
			'vog' => '1',
			'ervaren_trainer' => '1',
			'opmerkingen' => 'Kamp-ci.'
		]);

		Member::create([
			'voornaam' => 'Janus',
			'tussenvoegsel' => '',
			'achternaam' => 'Mathijssen',
			'geslacht' => 'M',
			'geboortedatum' => '1994-02-02',
			'adres' => 'Dorpsstraat 25',
			'postcode' => '1689 GG',
			'plaats' => 'Zwaag',
			'telefoon' => '0668547635',
			'email' => 'j.mathijssen@kpnmail.nl',
			'email_anderwijs' => '',
			'soort' => 'normaal',
			'eindexamen' => 'VWO',
			'studie' => 'Informatica',
			'afgestudeerd' => '1',
			'hoebij' => 'INTERNET',
			'kmg' => '1',
			'ranonkeltje' => '0',
			'vog' => '0',
			'ervaren_trainer' => '0',
			'opmerkingen' => 'Aasbaas.'
		]);

		Member::create([
			'voornaam' => 'Fleur',
			'tussenvoegsel' => '',
			'achternaam' => 'Appelhof',
			'geslacht' => 'V',
			'geboortedatum' => '1993-11-13',
			'adres' => 'Cornelis Matersweg 135',
			'postcode' => '1943 GZ',
			'plaats' => 'Beverwijk',
			'telefoon' => '0689446284',
			'email' => 'f.appelhof@kpnmail.nl',
			'email_anderwijs' => '',
			'soort' => 'normaal',
			'eindexamen' => 'VWO',
			'studie' => 'Communicatie wetenschappen',
			'afgestudeerd' => '1',
			'hoebij' => 'Google',
			'kmg' => '1',
			'ranonkeltje' => '0',
			'vog' => '0',
			'ervaren_trainer' => '0',
			'opmerkingen' => 'promo-ci.'
		]);

		Member::create([
			'voornaam' => 'Bouke',
			'tussenvoegsel' => '',
			'achternaam' => 'Bout',
			'geslacht' => 'M',
			'geboortedatum' => '1995-07-15',
			'adres' => 'Ringdijk Zuid 101',
			'postcode' => '4506 HD',
			'plaats' => 'Cadzand',
			'telefoon' => '0640932312',
			'email' => 'bouke@bout.nl',
			'email_anderwijs' => '',
			'soort' => 'normaal',
			'eindexamen' => 'VWO',
			'studie' => 'Communicatie wetenschappen',
			'afgestudeerd' => '1',
			'hoebij' => 'Google',
			'kmg' => '1',
			'ranonkeltje' => '0',
			'vog' => '0',
			'ervaren_trainer' => '0',
			'opmerkingen' => 'kantoor-ci.'
		]);

		Member::create([
			'voornaam' => 'Siep',
			'tussenvoegsel' => 'de',
			'achternaam' => 'Jong',
			'geslacht' => 'M',
			'geboortedatum' => '1994-11-04',
			'adres' => 'Ringdijk Zuid 101',
			'postcode' => '4506 HD',
			'plaats' => 'Cadzand',
			'telefoon' => '0640932312',
			'email' => 'siep@heeljong.nl',
			'email_anderwijs' => 'siep@anderwijs.nl',
			'soort' => 'normaal',
			'eindexamen' => 'VWO',
			'studie' => 'Communicatie wetenschappen',
			'afgestudeerd' => '1',
			'hoebij' => 'Google',
			'kmg' => '1',
			'ranonkeltje' => '2',
			'vog' => '0',
			'ervaren_trainer' => '0',
			'opmerkingen' => 'ranonkeltje redacteur.'
		]);
	}
}
