<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentePharmacieItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vente_pharmacie_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vente_pharmacie_id');
            $table->unsignedBigInteger('produit_id');
            $table->string('designation'); // Product name at time of sale
            $table->integer('quantite');
            $table->integer('prix_unitaire'); // Price at time of sale
            $table->integer('montant_ligne'); // Line total (quantite * prix_unitaire)
            $table->boolean('stock_deducted')->default(false); // Track if stock was deducted
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('vente_pharmacie_id')->references('id')->on('ventes_pharmacie')->onDelete('cascade');
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vente_pharmacie_items');
    }
}