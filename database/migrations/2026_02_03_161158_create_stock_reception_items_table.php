<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockReceptionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_reception_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_reception_id');
            $table->unsignedBigInteger('bon_commande_item_id');
            $table->integer('produit_id')->nullable();
            
            // Reception details
            $table->integer('quantite_commandee');
            $table->integer('quantite_recue');
            $table->integer('quantite_acceptee')->default(0); // After quality check
            $table->integer('quantite_refusee')->default(0);
            
            // Quality control
            $table->enum('etat_produit', ['conforme', 'non_conforme', 'endommage'])->default('conforme');
            $table->date('date_peremption')->nullable();
            $table->string('numero_lot')->nullable();
            $table->text('observation')->nullable();
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('stock_reception_id')
                  ->references('id')
                  ->on('stock_receptions')
                  ->onDelete('cascade');
                  
            $table->foreign('bon_commande_item_id')
                  ->references('id')
                  ->on('bon_commande_items')
                  ->onDelete('cascade');
            
            // Indexes
            $table->index('stock_reception_id');
            $table->index('bon_commande_item_id');
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
        Schema::dropIfExists('stock_reception_items');
    }
}