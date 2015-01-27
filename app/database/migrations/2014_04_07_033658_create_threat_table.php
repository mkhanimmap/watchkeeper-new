<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreatTable extends Migration {


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('threats'))
        {
            Schema::drop('threats');
        }
        Schema::create('threats', function(Blueprint $table) {
            $table->increments('id');
            $table->datetime('threat_datetime');
            $table->text('description');
            $table->text('advice');
            $table->string('source');
            $table->string('location');
            $table->boolean('send_sms')->default(false);
            $table->boolean('send_email')->default(false);
            $table->integer('user_id')->unsigned()->index();
            $table->integer('country_id')->unsigned()->index();
            $table->integer('source_grade_id')->unsigned()->index();
            $table->integer('threat_type_id')->unsigned()->index();
            $table->integer('threat_category_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('source_grade_id')->references('id')->on('classifications')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('threat_type_id')->references('id')->on('classifications')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('threat_category_id')->references('id')->on('classifications')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::table('threats', function(Blueprint $table) {
            $table->dropForeign('threats_user_id_foreign');
            $table->dropForeign('threats_country_id_foreign');
            $table->dropForeign('threats_source_grade_id_foreign');
            $table->dropForeign('threats_threat_type_id_foreign');
            $table->dropForeign('threats_threat_category_id_foreign');
            $table->dropForeign('threats_pointarea_id_foreign');
        });

        Schema::drop('threats');
    }


}
