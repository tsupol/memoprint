<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstagramTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('fans', function(Blueprint $table)
        {
            $table->integer('id')->unsigned()->primary();
            $table->string('user_name');
            $table->string('full_name');
            $table->string('profile_picture');
            $table->string('bio');
            $table->string('website');
            $table->integer('count_media')->unsigned();
            $table->integer('count_following')->unsigned();
            $table->integer('count_follower')->unsigned();
            $table->boolean('is_private')->unsigned()->index;
            $table->timestamp('checked_at')->index();
            $table->timestamps();
        });

        Schema::create('media', function(Blueprint $table)
        {
            $table->string('id', 32)->primary();
            $table->string('media_type');
            $table->string('img_low');
            $table->string('img_high');
            $table->string('user_id');
            $table->integer('like_count')->unsigned();
            $table->text('tags');
            $table->text('caption');
            $table->integer('created_time')->unsigned();
            $table->float('lat');
            $table->float('lng');
            $table->string('file_path');
            $table->timestamps();

        });

        Schema::create('user_token', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('fan_id')->unsigned()->unique()->index();
            $table->string('token');
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
        Schema::drop('user_token');
        Schema::drop('fans');
        Schema::drop('media');
	}

}
