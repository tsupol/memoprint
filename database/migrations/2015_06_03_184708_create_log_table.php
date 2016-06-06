<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('event_id')->unsigned();
			$table->string('message');
			$table->timestamps();
		});

		Schema::create('insta_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('type')->unsigned();
			$table->integer('event_id')->unsigned();
			$table->string('message');

			$table->timestamps();
		});

		Schema::create('print_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('type')->unsigned();
			$table->integer('event_id')->unsigned();
			$table->string('media_id', 32);
			$table->integer('amount')->unsigned();

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
		Schema::drop('logs');
		Schema::drop('insta_logs');
		Schema::drop('print_logs');
	}

}
