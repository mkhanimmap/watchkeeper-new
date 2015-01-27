<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePermissionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('permissions'))
        {
            Schema::drop('permissions');
        }
        Schema::create('permissions', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('display_name');
            $table->string('action_name');
            $table->string('group_name');
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
        Schema::drop('permissions');
    }

}
