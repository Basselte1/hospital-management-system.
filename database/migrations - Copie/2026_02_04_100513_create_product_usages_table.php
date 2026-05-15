<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_usages', function (Blueprint $table) {
            $table->id();
            $table->integer('produit_id');
            $table->integer('quantite');
            
            // Usage context
            $table->integer('patient_id')->nullable(); // If used for a patient
            $table->enum('type_utilisation', [
                'intervention_chirurgicale',
                'consultation',
                'hospitalisation',
                'urgence',
                'autre'
            ]);
            
            // Department/Service information
            $table->string('service')->nullable(); // Department where used
            $table->integer('medecin_id')->nullable(); // Doctor who used it
            $table->integer('infirmier_id')->nullable(); // Nurse who used it
            
            // Usage details
            $table->date('date_utilisation');
            $table->time('heure_utilisation')->nullable();
            $table->text('motif')->nullable(); // Reason for use
            $table->text('observations')->nullable();
            
            // Reusable tracking
            $table->integer('quantite_retournable')->default(0); // How many can be returned/sterilized
            $table->integer('quantite_perdue')->default(0); // How many lost/damaged
            $table->enum('statut_retour', [
                'en_attente',    // Used, waiting to be collected
                'collecte',      // Collected for sterilization
                'sterilise',     // In sterilization process
                'retourne',      // Returned to stock
                'non_retournable' // Damaged/lost
            ])->default('en_attente');
            
            // User tracking
            $table->integer('enregistre_par'); // User who recorded the usage
            $table->integer('collecte_par')->nullable(); // User who collected for sterilization
            $table->timestamp('collecte_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('produit_id');
            $table->index('patient_id');
            $table->index('date_utilisation');
            $table->index('statut_retour');
            $table->index('type_utilisation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_usages');
    }
}