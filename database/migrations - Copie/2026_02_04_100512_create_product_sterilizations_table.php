<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSterilizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_sterilizations', function (Blueprint $table) {
            $table->id();
            $table->integer('produit_id');
            $table->integer('quantite'); // Number of units being sterilized
            $table->string('numero_lot')->nullable(); // Sterilization batch number
            
            // Sterilization details
            $table->enum('methode_sterilisation', [
                'autoclave',
                'chaleur_seche',
                'gaz_eto',
                'plasma',
                'chimique',
                'autre'
            ]);
            $table->date('date_sterilisation');
            $table->time('heure_debut')->nullable();
            $table->time('heure_fin')->nullable();
            $table->integer('temperature')->nullable(); // In Celsius
            $table->integer('duree_minutes')->nullable(); // Sterilization duration
            
            // Operator information
            $table->integer('sterilise_par'); // User who performed sterilization
            $table->integer('verifie_par')->nullable(); // User who verified
            
            // Quality control
            $table->enum('resultat_test', ['conforme', 'non_conforme', 'en_attente'])->default('en_attente');
            $table->string('type_indicateur')->nullable(); // Type of sterilization indicator used
            $table->text('observations')->nullable();
            
            // Return to stock tracking
            $table->enum('statut', [
                'en_cours',           // Being sterilized
                'termine_en_attente', // Sterilized, awaiting verification
                'valide',             // Validated, ready to return
                'retourne',           // Returned to stock
                'rejete'              // Failed sterilization
            ])->default('en_cours');
            
            $table->integer('retourne_par')->nullable(); // User who returned to stock
            $table->timestamp('retourne_at')->nullable();
            $table->text('raison_rejet')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('produit_id');
            $table->index('date_sterilisation');
            $table->index('statut');
            $table->index('sterilise_par');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_sterilizations');
    }
}