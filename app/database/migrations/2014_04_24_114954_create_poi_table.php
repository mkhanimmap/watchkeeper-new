<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePoiTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    if (Schema::hasTable('pois'))
	    {
	        Schema::drop('pois');
	    }
	    Schema::create('pois', function(Blueprint $table) {
	        $table->increments('id');
	        $table->datetime('poi_datetime');
	        $table->text('description');
	        $table->string('location');
	        $table->boolean('sent_alert')->default(false);
	        $table->boolean('immap_asset')->default(false);
	        $table->float('distance_km')->default(0.00);
	        $table->string('file_path')->default('')->nullable();
	        $table->integer('user_id')->unsigned()->index();
	        $table->integer('country_id')->unsigned()->index();
	        $table->integer('poi_type_id')->unsigned()->index();
	        $table->integer('pointarea_id')->nullable()->unsigned()->index();
	        $table->text('geojson')->default('');
	        $table->text('wkt','')->default('');
	        $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
	        $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict')->onUpdate('cascade');
	        $table->foreign('poi_type_id')->references('id')->on('classifications')->onDelete('restrict')->onUpdate('cascade');
	        $table->foreign('pointarea_id')->references('id')->on('pointareas')->onDelete('restrict')->onUpdate('cascade');
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
	    Schema::hasTable('pois', function(Blueprint $table) {
	        $table->dropForeign('pois_user_id_foreign');
	        $table->dropForeign('pois_country_id_foreign');
	        $table->dropForeign('pois_poi_type_id_foreign');
	        $table->dropForeign('pois_pointarea_id_foreign');
	    });

	    Schema::drop('pois');
	}
}
