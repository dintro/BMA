<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurnamentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('turnaments', function($table){
			$table->increments('id');
			$table->integer('package_id')->unsigned();
		    $table->foreign('package_id')->references('id')->on('packages');
		    $table->string('name', 20);		   
		    $table->decimal('buyin', 13, 2);
		    $table->decimal('buyin_percent', 13, 2);
		    $table->decimal('buyin_custom', 13, 2);
		    $table->decimal('markup', 13, 2);
		    $table->tinyInteger('active');
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
		//
		Schema::drop('turnaments');
	}

}
