<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecurityAdvisoryTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('security_advisories'))
        {
            Schema::drop('security_advisories');
        }
        Schema::create('security_advisories', function(Blueprint $table) {
            $table->increments('id');
            $table->datetime('security_advisory_datetime');
            $table->text('description');
            $table->text('advice');
            $table->string('location');
            $table->boolean('send_sms')->default(false);
            $table->boolean('send_email')->default(false);
            $table->integer('user_id')->unsigned()->index();
            $table->integer('country_id')->unsigned()->index();
            $table->integer('pointarea_id')->nullable()->unsigned()->index();
            $table->integer('incidenttype_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('pointarea_id')->references('id')->on('pointareas')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('incidenttype_id')->references('id')->on('classifications')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::table('security_advisories', function(Blueprint $table) {
            $table->dropForeign('security_advisories_user_id_foreign');
            $table->dropForeign('security_advisories_incidenttype_id_foreign');
            $table->dropForeign('security_advisories_pointarea_id_foreign');
            $table->dropForeign('security_advisories_country_id_foreign');
        });
        if (Schema::hasTable('security_advisories'))
        {
                Schema::drop('security_advisories');
        }
    }

}
