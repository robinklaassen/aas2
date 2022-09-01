<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('voornaam');
            $table->string('tussenvoegsel')->nullable();
            $table->string('achternaam');
            $table->enum('geslacht', ['M', 'V']);
            $table->date('geboortedatum');
            $table->string('adres');
            $table->string('postcode');
            $table->string('plaats');
            $table->string('telefoon');
            $table->string('email');
            $table->string('email_anderwijs');
            $table->string('iban')->nullable();
            $table->enum('soort', ['normaal', 'aspirant', 'info', 'oud'])->default('aspirant');
            $table->enum('eindexamen', ['VMBO', 'HAVO', 'VWO']);
            $table->string('studie');
            $table->boolean('afgestudeerd');
            $table->boolean('rijbewijs')->default(0);
            $table->string('hoebij');
            $table->boolean('kmg')->default(0);
            $table->enum('ranonkeltje', ['geen', 'digitaal', 'papier', 'beide'])->default('digitaal');
            $table->boolean('vog')->default(0);
            $table->boolean('ervaren_trainer')->default(0);
            $table->boolean('incasso')->default(0);
            $table->date('datum_af')->nullable();
            $table->text('opmerkingen')->nullable();
            $table->text('opmerkingen_admin')->nullable();
            $table->text('opmerkingen_geheim')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('members');
    }
}
