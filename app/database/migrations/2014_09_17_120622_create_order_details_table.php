<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('order_details', function($table){
			$table->increments('id');
		    $table->integer('order_id')->references('id')->on('orders');;
		    $table->dateTime('package_id');
		    $table->string('package_name');
			$table->decimal('selling', 5, 2);
		    $table->decimal('selling_price', 15, 2);		    
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
	}

}
