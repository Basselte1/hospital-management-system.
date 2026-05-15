<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonCommandeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bon_commande_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bon_commande_id');
            $table->integer('produit_id')->nullable(); // Reference to existing product
            
            // Product details (stored for historical reference)
            $table->string('designation');
            $table->string('categorie');
            $table->integer('quantite_commandee');
            $table->integer('quantite_recue')->default(0); // Filled during reception
            $table->integer('prix_unitaire');
            $table->integer('montant_ligne'); // quantite * prix_unitaire
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Foreign key
            $table->foreign('bon_commande_id')
                  ->references('id')
                  ->on('bon_commandes')
                  ->onDelete('cascade');
            
            // Indexes
            $table->index('bon_commande_id');
            $table->index('produit_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bon_commande_items');
    }
}