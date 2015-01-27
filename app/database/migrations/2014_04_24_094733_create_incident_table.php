<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('incidents'))
        {
            Schema::drop('incidents');
        }
        Schema::create('incidents', function(Blueprint $table) {
            $table->increments('id');
            $table->datetime('incident_datetime');
            $table->text('description');
            $table->string('source');
            $table->string('location');
            $table->boolean('send_sms')->default(false);
            $table->boolean('send_email')->default(false);
            $table->integer('user_id')->unsigned()->index();
            $table->integer('country_id')->unsigned()->index();
            $table->integer('source_grade_id')->unsigned()->index();
            $table->integer('incident_type_id')->unsigned()->index();
            $table->integer('incident_category_id')->unsigned()->index();
            $table->integer('injured')->default(0);
            $table->integer('killed')->default(0);
            $table->integer('captured')->default(0);
            $table->text('geojson')->default('');
            $table->text('wkt','')->default('');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('source_grade_id')->references('id')->on('classifications')->onDelete('cascade');
            $table->foreign('incident_type_id')->references('id')->on('classifications')->onDelete('cascade');
            $table->foreign('incident_category_id')->references('id')->on('classifications')->onDelete('cascade');
            $table->integer('pointarea_id')->nullable()->unsigned()->index();
            $table->foreign('pointarea_id')->references('id')->on('pointareas')->onDelete('cascade');
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
        Schema::table('incidents', function(Blueprint $table) {
            $table->dropForeign('incidents_user_id_foreign');
            $table->dropForeign('incidents_country_id_foreign');
            $table->dropForeign('incidents_source_grade_id_foreign');
            $table->dropForeign('incidents_incident_type_id_foreign');
            $table->dropForeign('incidents_incident_category_id_foreign');
            $table->dropForeign('incidents_pointarea_id_foreign');
        });

        Schema::drop('incidents');
    }

}
