<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientVisitsTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * TABLE patient_visits - Historique des visites des patients
     * Permet de tracer chaque passage d'un patient sans créer de doublon
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_visits', function (Blueprint $table) {
            $table->increments('id');
            
            // Lien avec le patient (FIXE - ne change jamais)
            $table->unsignedInteger('patient_id')->index();
            
            // Utilisateur qui a enregistré cette visite
            $table->unsignedInteger('user_id')->index();
            
            // Date de la visite
            $table->date('visit_date')->index();
            
            // Motif médical de la visite
            $table->string('motif')->nullable();
            $table->text('details_motif')->nullable();
            
            // Informations financières (peuvent changer à chaque visite)
            $table->integer('montant')->nullable()->default(0);
            $table->integer('avance')->nullable()->default(0);
            $table->integer('reste')->nullable()->default(0);
            
            // Informations d'assurance (peuvent changer)
            $table->string('assurance')->nullable();
            $table->string('numero_assurance')->nullable();
            $table->string('prise_en_charge')->nullable();
            $table->integer('assurancec')->nullable()->default(0);
            $table->integer('assurec')->nullable()->default(0);
            
            // Médecin traitant pour cette visite
            $table->string('medecin_r')->nullable()->index();
            
            // Mode de paiement
            $table->string('mode_paiement')->nullable()->default('espèce');
            $table->string('mode_paiement_info_sup')->nullable();
            
            // Démarcheur (si applicable)
            $table->string('demarcheur')->nullable();
            
            // Statut de la visite
            $table->enum('status', ['en_attente', 'en_cours', 'terminee', 'annulee'])
                ->default('en_attente');
            
            // Notes supplémentaires
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Contraintes de clés étrangères
            $table->foreign('patient_id')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');
                
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            
            // Index composés pour performance
            $table->index(['patient_id', 'visit_date']);
            $table->index(['medecin_r', 'visit_date']);
            $table->index(['status', 'visit_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_visits');
    }
}
