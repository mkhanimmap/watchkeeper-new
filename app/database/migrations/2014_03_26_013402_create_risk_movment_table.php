<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRiskMovmentTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('risk_movement'))
        {
            Schema::drop('risk_movement');
        }
        Schema::create('risk_movement', function(Blueprint $table) {
            $table->increments('id');
            $table->datetime('risk_datetime');
            $table->text('description');
            $table->string('location');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('country_id')->unsigned()->index();
            $table->integer('risklevel_id')->unsigned()->index();
            $table->integer('movement_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('risklevel_id')->references('id')->on('classifications')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('movement_id')->references('id')->on('classifications')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::table('risk_movement', function(Blueprint $table) {
            $table->dropForeign('risk_movement_user_id_foreign');
            $table->dropForeign('risk_movement_risklevel_id_foreign');
            $table->dropForeign('risk_movement_country_id_foreign');
            $table->dropForeign('risk_movement_movement_id_foreign');
        });

        Schema::drop('risk_movement');
    }

}
