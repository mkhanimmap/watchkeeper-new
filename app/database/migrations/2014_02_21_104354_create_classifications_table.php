<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClassificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (Schema::hasTable('classifications'))
		{
		    Schema::drop('classifications');
		}
		Schema::create('classifications', function(Blueprint $table) {
			$table->increments('id');
			$table->string('code');
			$table->string('name');
			$table->string('group_id');
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
		Schema::drop('classifications');
	}

}
