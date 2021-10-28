<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('voornaam');
            $table->string('tussenvoegsel')->nullable();
            $table->string('achternaam');
            $table->enum('geslacht', ['M', 'V']);
            $table->date('geboortedatum');
            $table->string('adres');
            $table->string('postcode');
            $table->string('plaats');
            $table->string('telefoon_deelnemer');
            $table->string('telefoon_ouder_vast');
            $table->string('telefoon_ouder_mobiel');
            $table->string('email_deelnemer');
            $table->string('email_ouder');
            $table->boolean('mag_gemaild')->default(1);
            $table->integer('inkomen');
            $table->date('inkomensverklaring')->nullable();
            $table->string('school');
            $table->enum('niveau', ['VMBO', 'HAVO', 'VWO']);
            $table->integer('klas');
            $table->string('hoebij');
            $table->text('opmerkingen')->nullable();
            $table->text('opmerkingen_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('participants');
    }
}
