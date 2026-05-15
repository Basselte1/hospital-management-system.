<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonCommandesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bon_commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_bon')->unique(); // BC-2026-0001
            $table->integer('created_by'); // Logistique user_id
            $table->enum('statut', ['brouillon', 'envoye', 'valide', 'receptionne', 'annule'])->default('brouillon');
            
            // Supplier information
            $table->string('fournisseur_nom');
            $table->string('fournisseur_email')->nullable();
            $table->string('fournisseur_telephone')->nullable();
            $table->text('fournisseur_adresse')->nullable();
            
            // Order details
            $table->date('date_commande');
            $table->date('date_livraison_souhaitee')->nullable();
            $table->integer('montant_total')->default(0);
            $table->text('notes')->nullable();
            
            // Validation tracking
            $table->integer('validated_by')->nullable(); // Gestionnaire user_id
            $table->timestamp('validated_at')->nullable();
            $table->text('validation_comment')->nullable();
            
            // Reception tracking
            $table->integer('received_by')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->text('reception_comment')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('statut');
            $table->index('created_by');
            $table->index('validated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bon_commandes');
    }
}