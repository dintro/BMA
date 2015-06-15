<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table){
			$table->increments('id');
		    $table->string('firstname', 20);
		    $table->string('lastname', 20);
		    $table->string('displayname', 64)->unique();
		    $table->string('email', 100)->unique();
		    $table->string('password', 64);
		    $table->string('remember_token', 100);
		    $table->tinyInteger('email_verified');
		    $table->string('email_verified_token', 100);
		    $table->string('facebook_email', 100);
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
		Schema::drop('users');
	}

}
