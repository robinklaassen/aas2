<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Location;
use DB;
use Illuminate\Database\Seeder;

class LocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('locations')->delete();

        Location::create([
            'naam' => 'De Limburgse Olifant',
            'adres' => 'Vaalsestraat 23',
            'postcode' => '8404 ZX',
            'plaats' => 'Venlo',
            'beheerder' => 'G. de Boer',
            'website' => 'http://www.delimburgseolifant.nl',
            'telefoon' => '0639083972',
            'email' => 'gerrit@delimburgseolifant.nl',
            'prijsinfo' => 'Kost helemaal niet duur dit niet nee.',
        ]);

        Location::create([
            'naam' => 'Het Schoone Schip',
            'adres' => 'Noordkade 15',
            'postcode' => '1344 PV',
            'plaats' => 'Sneek',
            'beheerder' => 'Douwe Sjoerdsma',
            'website' => 'http://www.hetschooneschip.nl',
            'telefoon' => '0623462428',
            'email' => 'gekkedouwe@hotmail.com',
            'prijsinfo' => 'Een weekend voor een prikkie!',
        ]);
    }
}
