<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionMedicalesTable extends Migration
{
    public function up()
    {
        Schema::create('prescription_medicales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->string('allergie')->nullable();
            $table->date('date');
            $table->string('medicament');
            $table->string('posologie');
            $table->string('voie');
            $table->integer('heure');
            $table->string('matin')->nullable();
            $table->string('apre_midi')->nullable();
            $table->string('soir')->nullable();
            $table->string('nuit')->nullable();
            $table->text('regime')->nullable();
            $table->text('consultation_specialise')->nullable();
            $table->text('protocole')->nullable();
            $table->timestamps();
            $table->index(['patient_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('prescription_medicales');
    }
}

