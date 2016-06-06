<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventTable extends Migration {

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
			$table->string('name');
			$table->string('description');
			$table->string('tag');
			$table->string('tag2');
			$table->integer('fan_id')->unsigned();
			$table->integer('tag_mode')->unsigned();
			$table->integer('mode')->unsigned();
			$table->boolean('is_active')->default(true);
			$table->timestamp('start_at')->index();
			$table->timestamp('finish_at')->index();
			$table->timestamps();
		});

		Schema::create('event_media', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('media_id', 32)->index();
			$table->foreign('media_id')->references('id')->on('media')->onDelete('CASCADE');

			$table->integer('event_id')->unsigned()->index();
			$table->foreign('event_id')->references('id')->on('events')->onDelete('CASCADE');

			$table->boolean('is_use')->default(true);
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
		Schema::drop('event_media');
		Schema::drop('events');
	}

}
