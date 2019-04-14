<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('naam');
			$table->string('code');
			$table->enum('type',['kamp', 'training', 'overig']);
			$table->date('datum_voordag')->nullable();
			$table->date('datum_start');
			$table->time('tijd_start');
			$table->date('datum_eind');
			$table->time('tijd_eind');
			$table->integer('location_id')->unsigned()->nullable();
			$table->integer('prijs')->nullable();
			$table->integer('streeftal')->nullable();
			$table->boolean('vol')->default(0);
			$table->boolean('openbaar')->default(1);
			$table->text('beschrijving')->nullable();
			$table->text('opmerkingen')->nullable();
			$table->timestamps();
			
			$table->foreign('location_id')
					->references('id')
					->on('locations')
					->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('events');
	}

}
