<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCountriesTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::hasTable('assigned_countries', function(Blueprint $table) {
            $table->dropForeign('assigned_countries_user_id_foreign');
            $table->dropForeign('assigned_countries_role_id_foreign');
        });
        if (Schema::hasTable('countries'))
        {

            Schema::drop('countries');
        }
        Schema::create('countries', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('code_a2',2)->nullable();
            $table->string('code_a3',3);
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
        Schema::hasTable('assigned_countries', function(Blueprint $table) {
            $table->dropForeign('assigned_countries_user_id_foreign');
            $table->dropForeign('assigned_countries_role_id_foreign');
        });
        Schema::drop('countries');
    }

}
