<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventMemberTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('event_member', function(Blueprint $table)
		{
			$table->integer('event_id')->unsigned();
			$table->integer('member_id')->unsigned();
			$table->timestamps();
			$table->boolean('wissel')->default(0);
			$table->date('wissel_datum_start')->nullable();
			$table->date('wissel_datum_eind')->nullable();
			
			$table->foreign('event_id')
					->references('id')
					->on('events')
					->onDelete('cascade');
					
			$table->foreign('member_id')
					->references('id')
					->on('members')
					->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('event_member');
	}

}
