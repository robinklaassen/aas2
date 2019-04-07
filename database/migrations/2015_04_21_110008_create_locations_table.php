<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('locations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('naam');
			$table->string('adres');
			$table->string('postcode');
			$table->string('plaats');
			$table->string('beheerder');
			$table->string('website');
			$table->string('telefoon');
			$table->string('email');
			$table->text('prijsinfo')->nullable();
			$table->text('opmerkingen')->nullable();
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
		Schema::drop('locations');
	}

}
