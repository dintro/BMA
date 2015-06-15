<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMessages extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Schema::create('messages', function($table)
		// {
		// 	$table->increments('id');
		// 	$table->string('title');
		// 	$table->integer('sender_id')->unsigned();
		// 	$table->foreign('sender_id')->references('id')->on('users');
		// 	$table->integer('recipient_id')->unsigned();
		// 	$table->foreign('recipient_id')->references('id')->on('users');
		// 	$table->string('status');
		// 	$table->text('content');
		// 	$table->timestamps();
		// });

		// Schema::create('conversations', function($table){
		// 	$table->increments('id');
		// 	$table->string('name', 100);
		// 	$table->timestamps();
		// });

		// Schema::create('conversation_user', function($table){
		// 	$table->increments('id');
		// 	$table->integer('user_id')->unsigned();
		// 	$table->foreign('user_id')->references('id')->on('users');
		// 	$table->integer('conversation_id')->unsigned();
		// 	$table->foreign('conversation_id')->references('id')->on('conversations');
		// });

		// Schema::create('messages', function($table){
		// 	$table->increments('id');
		// 	$table->text("content");
		// 	$table->integer('user_id')->unsigned();
		// 	$table->foreign('user_id')->references('id')->on('users');
		// 	$table->integer('conversation_id')->unsigned();
		// 	$table->foreign('conversation_id')->references('id')->on('conversations');
		// 	$table->timestamps();
		// });


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Schema::drop('conversations');
		// Schema::drop('conversation_user');
		// Schema::drop('messages');
	}

}
