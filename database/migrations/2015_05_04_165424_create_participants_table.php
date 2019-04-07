<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('participants', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('voornaam');
			$table->string('tussenvoegsel')->nullable();
			$table->string('achternaam');
			$table->enum('geslacht', ['M','V']);
			$table->date('geboortedatum');
			$table->string('adres');
			$table->string('postcode');
			$table->string('plaats');
			$table->string('telefoon_deelnemer');
			$table->string('telefoon_ouder');
			$table->string('email_deelnemer');
			$table->string('email_ouder');
			$table->integer('inkomen');
			//$table->date('inkomensverklaring')->default('0000-00-00');
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
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('participants');
	}

}
