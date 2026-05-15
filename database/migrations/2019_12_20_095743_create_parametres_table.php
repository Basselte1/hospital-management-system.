<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->integer('user_id');
            $table->integer('patient_id');

            // Float columns as requested (not decimal)
            // taille: e.g. 1.78 m → float with 4 total digits, 2 decimal places
            $table->float('taille', 4, 2);
            // poids: e.g. 71.5 kg → float with 5 total digits, 2 decimal places
            $table->float('poids', 5, 2);

            $table->string('bras_gauche');
            $table->string('bras_droit');
            $table->string('inc_bmi');
            $table->date('date_naissance');
            $table->integer('age');
            $table->string('temperature');
            $table->string('fr')->nullable();
            $table->string('fc')->nullable();
            $table->string('spo2')->nullable();
            $table->string('glycemie')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('patient_id')
                  ->references('id')->on('patients')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');

            // Indexes
            $table->index('user_id');
            $table->index('patient_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parametres');
    }
}