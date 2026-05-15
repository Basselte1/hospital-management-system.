<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevisTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * COMPLETE VERSION - Includes all columns needed by the Devi model:
     * - Base devis info (nom, code, acces)
     * - User relationships (user_id, patient_id, medecin_id, validateur_id)
     * - Pricing defaults (pu_chambre, pu_visite, pu_ami_jour)
     * - Quantity fields (nbr_chambre, nbr_visite, nbr_ami_jour) ← CRITICAL!
     * - Validation workflow (statut, pourcentage_reduction, etc.)
     * - All foreign keys
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devis', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            
            // User relationships
            $table->integer('user_id')->index();
            $table->integer('patient_id')->nullable()->index();
            $table->integer('medecin_id')->nullable()->index();
            $table->integer('validateur_id')->nullable()->index();
            
            // Basic info
            $table->string('nom');
            $table->string('acces');
            $table->string('code');
            
            // Hospitalization duration
            $table->integer('nbr_jour_hosp')->default(0);
            
            // Unit prices (prix unitaires)
            $table->integer('pu_chambre')->default(30000);
            $table->integer('pu_visite')->default(10000);
            $table->integer('pu_ami_jour')->default(9000);
            
            // CRITICAL: Quantity fields (nombre de...)
            // These are REQUIRED by DevisController and Devi model!
            $table->integer('nbr_chambre')->default(0);
            $table->integer('nbr_visite')->default(0);
            $table->integer('nbr_ami_jour')->default(0);
            
            // Validation workflow
            $table->enum('statut', ['brouillon', 'en_attente', 'valide', 'refuse'])
                  ->default('brouillon');
            $table->integer('pourcentage_reduction')->default(0);
            $table->integer('montant_avant_reduction')->default(0);
            $table->integer('montant_apres_reduction')->default(0);
            $table->timestamp('date_validation')->nullable();
            $table->text('commentaire_medecin')->nullable();
            
            // Indexes for queries
            $table->index('statut');
            $table->index('created_at');
            $table->index(['medecin_id', 'statut']);
            $table->index(['patient_id', 'created_at']);
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('medecin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('validateur_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devis');
    }
}