<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AltertRiskAndSecuritAvisoryTableAddWktAndGeojson extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('risk_movement'))
        {
            Schema::table('risk_movement', function($table)
            {
                $table->text('geojson')->default('');
                $table->text('wkt','')->default('');
            });
            Schema::table('risk_movement', function($table)
            {
                $table->integer('pointarea_id')->nullable()->unsigned()->index();
                $table->foreign('pointarea_id')->references('id')->on('pointareas')->onDelete('restrict')->onUpdate('cascade');
            });
        }
        if (Schema::hasTable('security_advisories'))
        {
            Schema::table('security_advisories', function($table)
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
        Schema::hasColumn('security_advisories', 'pointarea_id',function(Blueprint $table) {
            $table->dropForeign('security_advisories_pointarea_id_foreign');
        });
        Schema::hasColumn('risk_movement', 'pointarea_id',function(Blueprint $table) {
            $table->dropForeign('risk_movement_pointarea_id_foreign');
        });
    }

}
