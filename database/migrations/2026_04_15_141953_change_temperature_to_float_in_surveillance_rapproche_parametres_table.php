<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTemperatureToFloatInSurveillanceRapprocheParametresTable extends Migration
{
    public function up()
    {
        Schema::table('surveillance_rapproche_parametres', function (Blueprint $table) {
            // Changer la colonne de integer à float (nullable si besoin)
            $table->decimal('temperature', 5, 2)->nullable()->change();
        });


    }

    public function down()
    {
        Schema::table('surveillance_rapproche_parametres', function (Blueprint $table) {
            $table->integer('temperature')->nullable()->change();
        });
    }
}