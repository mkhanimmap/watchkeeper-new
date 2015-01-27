<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterThreatTableAddWktAndGeojson extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (Schema::hasTable('threats'))
		{
		    Schema::table('threats', function($table)
		    {
		        $table->text('geojson')->default('');
		        $table->text('wkt','')->default('');
		    });
		}
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
