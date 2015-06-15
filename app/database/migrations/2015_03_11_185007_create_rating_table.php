<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ratings', function($table){
			$table->increments('id');
			$table->integer('rater_id')->unsigned();
			$table->foreign('rater_id')->references('id')->on('users');
			$table->integer('ratee_id')->unsigned();
			$table->foreign('ratee_id')->references('id')->on('users');
			$table->integer('rate');
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
		Schema::drop('ratings');
	}

}
