<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('produit_id');
            $table->integer('user_id'); // User who performed the transaction
            
            // Transaction details
            $table->enum('type_transaction', [
                'entree_commande',       // Stock entry from purchase order
                'sortie_vente',          // Stock exit from external sale
                'sortie_vente_patient',  // Stock exit from patient sale
                'sortie_utilisation',    // Stock exit from hospital use
                'retour_reutilisable',   // Return of reusable item
                'ajustement_inventaire', // Inventory adjustment
                'transfert',             // Transfer between departments
                'perte',                 // Loss/damage
                'perime'                 // Expired products
            ]);
            
            $table->integer('quantite'); // Can be positive (entry) or negative (exit)
            $table->integer('stock_avant'); // Stock before transaction
            $table->integer('stock_apres');  // Stock after transaction
            
            // Reference information
            $table->string('reference_type')->nullable(); // Type of reference document
            $table->integer('reference_id')->nullable();   // ID of reference document
            $table->string('numero_document')->nullable(); // Document number
            
            // Additional details
            $table->text('motif')->nullable();
            $table->text('commentaire')->nullable();
            $table->date('date_transaction');
            
            $table->timestamps();
            
            // Indexes
            $table->index('produit_id');
            $table->index('user_id');
            $table->index('type_transaction');
            $table->index('date_transaction');
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_transactions');
    }
}