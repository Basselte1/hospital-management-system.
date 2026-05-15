<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * COMPLETE VERSION - Includes all columns from the start:
     * - Base patient info
     * - Financial tracking
     * - Payment methods
     * - Insurance details
     * - Doctor assignment
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->integer('numero_dossier')->nullable();
            
            // Patient basic info
            $table->string('name');
            $table->string('prenom')->nullable();
            
            // Insurance info
            $table->string('assurance')->nullable();
            $table->string('numero_assurance')->nullable();
            $table->string('prise_en_charge')->nullable();
            $table->integer('assurancec')->nullable()->default(0);
            $table->integer('assurec')->nullable()->default(0);
            
            // Financial tracking
            $table->integer('montant')->nullable()->default(0);
            $table->integer('avance')->nullable()->default(0);
            $table->integer('reste')->nullable()->default(0);
            
            // Consultation details
            $table->string('motif')->nullable();
            $table->text('details_motif')->nullable();
            
            // Referral info
            $table->string('demarcheur')->nullable();
            $table->integer('medecin_r')->nullable();
            
            // Administrative
            $table->date('date_insertion')->nullable();
            
            // Payment method fields (added in 2020-02)
            $table->string('mode_paiement')->nullable()->default('espèce');
            $table->string('mode_paiement_info_sup')->nullable();
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('numero_dossier');
            $table->index('name');
            $table->index('medecin_r');
            $table->index('date_insertion');
            $table->index('created_at');
            
            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}