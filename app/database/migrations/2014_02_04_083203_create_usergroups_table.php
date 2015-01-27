<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsergroupsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('usergroups'))
        {
            Schema::drop('usergroups');
        }
        Schema::create('usergroups', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('code')->unique();
            $table->string('name')->unique();
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
        Schema::drop('usergroups');
    }

}
