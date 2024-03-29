<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateCapabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('capabilities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
        });

        $capa = [
            // participants
            ['participants::info::show::self', 'Deelnemersinfo - Inzien - Zelf'],
            ['participants::info::show::basic', 'Deelnemersinfo - Inzien - Basis'],
            ['participants::info::show::private', 'Deelnemersinfo - Inzien - Privé'],
            ['participants::info::show::finance', 'Deelnemersinfo - Inzien - Financieel'],
            ['participants::info::show::practical', 'Deelnemersinfo - Inzien - Praktisch'],
            ['participants::info::show::administrative', 'Deelnemersinfo - Inzien - Administratie'],

            ['participants::info::edit::self', 'Deelnemersinfo - Aanpassen - Zelf'],
            ['participants::info::edit::basic', 'Deelnemersinfo - Aanpassen - Basis'],
            ['participants::info::edit::private', 'Deelnemersinfo - Aanpassen - Privé'],
            ['participants::info::edit::finance', 'Deelnemersinfo - Aanpassen - Financieel'],
            ['participants::info::edit::practical', 'Deelnemersinfo - Aanpassen - Praktisch'],
            ['participants::info::edit::administrative', 'Deelnemersinfo - Aanpassen - Administratie'],
            ['participants::info::edit::password', 'Deelnemersinfo - Aanpassen - Wachtwoord'],

            ['participants::info::export', 'Deelnemersinfo - Export - Leiding'],

            ['participants::account::create', 'Deelnemersaccount - Aanmaken'],
            ['participants::account::delete', 'Deelnemersaccount - Verwijderen'],

            // members
            ['members::old::show', 'Leidinginfo - Inzien - Oudleden'],

            ['members::info::show::self', 'Leidinginfo - Inzien - Zelf'],
            ['members::info::show::basic', 'Leidinginfo - Inzien - Basis'],
            ['members::info::show::private', 'Leidinginfo - Inzien - Privé'],
            ['members::info::show::finance', 'Leidinginfo - Inzien - Financieel'],
            ['members::info::show::practical', 'Leidinginfo - Inzien - Praktisch'],
            ['members::info::show::administrative', 'Leidinginfo - Inzien - Administratie'],
            ['members::info::show::special', 'Leidinginfo - Inzien - Speciaal'],

            ['members::oud::show', 'Leidinginfo - Inzien - Oudleden'],

            ['members::info::edit::self', 'Leidinginfo - Aanpassen - Zelf'],
            ['members::info::edit::basic', 'Leidinginfo - Aanpassen - Basis'],
            ['members::info::edit::private', 'Leidinginfo - Aanpassen - Privé'],
            ['members::info::edit::finance', 'Leidinginfo - Aanpassen - Financieel'],
            ['members::info::edit::practical', 'Leidinginfo - Aanpassen - Praktisch'],
            ['members::info::edit::administrative', 'Leidinginfo - Aanpassen - Administratie'],
            ['members::info::edit::special', 'Leidinginfo - Aanpassen - Speciaal'],
            ['members::info::edit::password', 'Leidinginfo - Aanpassen - Wachtwoord'],

            ['members::account::create', 'Leidingsaccount - Aanmaken'],
            ['members::account::update', 'Leidingaccount - Rechten aanpassen'],
            ['members::account::delete', 'Leidingsaccount - Verwijderen'],

            // comments
            ['comments::show', 'Opmerkingen - Inzien'],
            ['comments::edit', 'Opmerkingen - Aanpassen'],
            ['comments::create', 'Opmerkingen - Aanmaken'],
            ['comments::delete', 'Opmerkingen - Verwijderen'],

            ['comments::show::secret', 'Opmerkingen - Inzien - geheim'],
            ['comments::edit::secret', 'Opmerkingen - Aanpassen - geheim'],

            // events
            ['event::show::participating', 'Evenement - Inzien - Deelnemend'],
            ['event::show::basic', 'Evenement - Inzien - basic'],
            ['event::show::advanced', 'Evenement - Inzien - uitgebreid'],
            ['event::show::review', 'Evenement - Inzien - reviews'],

            ['event::edit::basic', 'Evenement - Aanpassen - basic'],
            ['event::edit::advanced', 'Evenement - Aanpassen - uitgebreid'],

            ['event::create', 'Evenement - Aanmaken'],
            ['event::delete', 'Evenement - Verwijderen'],

            ['event::subjectcheck', 'Evenement - knop - Vakdekking'],
            ['event::mailing', 'Evenement - knop - Email addressen'],
            ['event::budget', 'Evenement - knop - Budget'],
            ['event::paymentoverview', 'Evenement - knop - Betalingsoverzicht'],
            ['event::placement', 'Evenement - knop - Plaatsen'],
            ['event::nightregister', 'Evenement - knop - Nachtregister'],

            ['event::members::add', 'Evenement - Leiding - Toevoegen'],
            ['event::members::edit', 'Evenement - Leiding - Aanpassen'],
            ['event::members::remove', 'Evenement - Leiding - Verwijderen'],

            ['event::participants::add', 'Evenement - Deelnemers - Toevoegen'],
            ['event::participants::edit', 'Evenement - Deelnemers - Wijzigen'],
            ['event::participants::remove', 'Evenement - Deelnemers - Verwijderen'],

            // roles
            ['roles::info', 'Rollen - Inzien'],
            ['roles::edit', 'Rollen - Aanpassen'],
            ['roles::create', 'Rollen - Aanmaken'],
            ['roles::delete', 'Rollen - Verwijderen'],

            // locations
            ['locations::info::basic', 'Locaties - Inzien - Basis'],
            ['locations::info::advanced', 'Locaties - Inzien - Uitgebreid'],
            ['locations::edit::basic', 'Locaties - Aanpassen - Basis'],
            ['locations::edit::advanced', 'Locaties - Aanpassen - Uitgebreid'],
            ['locations::create', 'Locaties - Aanmaken'],
            ['locations::delete', 'Locaties - Verwijderen'],

            ['courses::show', 'Courses - Inzien'],
            ['courses::create', 'Courses - Aanmaken'],
            ['courses::edit', 'Courses - Aanpassen'],
            ['courses::delete', 'Courses - Verwijderen'],

        ];
        $all = array_map(function ($i) {
            return [
                'name' => $i[0],
                'description' => $i[1],
            ];
        }, $capa);

        DB::table('capabilities')->insert($all);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('capabilities');
    }
}
