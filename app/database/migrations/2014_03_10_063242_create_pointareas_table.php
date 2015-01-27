<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePointareasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (Schema::hasTable('pointareas'))
		{
		    Schema::drop('pointareas');
		}
		Schema::create('pointareas', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name')->unique();
			$table->text('description')->nullable();
			$table->text('geojson');
			$table->text('wkt')->nullable();
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
		Schema::drop('pointareas');
	}

}
