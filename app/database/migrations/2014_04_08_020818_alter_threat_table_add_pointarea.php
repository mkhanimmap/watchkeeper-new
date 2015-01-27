<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterThreatTableAddPointarea extends Migration {

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
                $table->integer('pointarea_id')->nullable()->unsigned()->index();
                $table->foreign('pointarea_id')->references('id')->on('pointareas')->onDelete('restrict')->onUpdate('cascade');
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
        if (Schema::hasColumn('threats', 'pointarea_id'))
        {
            //$table->dropForeign('threats_pointarea_id_foreign');
        }
    }

}
