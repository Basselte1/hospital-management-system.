<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockReceptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_receptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bon_commande_id');
            $table->string('numero_reception')->unique(); // REC-2026-0001
            $table->integer('received_by'); // User who received the stock
            $table->date('date_reception');
            
            // Delivery information
            $table->string('numero_bl')->nullable(); // Bon de livraison number
            $table->string('livreur_nom')->nullable();
            $table->string('livreur_telephone')->nullable();
            $table->text('condition_livraison')->nullable(); // Delivery condition notes
            
            // Reception details
            $table->enum('statut_reception', ['partielle', 'complete', 'avec_probleme'])->default('complete');
            $table->text('commentaire')->nullable();
            $table->text('problemes_constates')->nullable(); // Issues found
            
            // Validation
            $table->integer('validated_by')->nullable(); // Gestionnaire who validated
            $table->timestamp('validated_at')->nullable();
            $table->text('validation_notes')->nullable();
            
            $table->timestamps();
            
            // Foreign key
            $table->foreign('bon_commande_id')
                  ->references('id')
                  ->on('bon_commandes')
                  ->onDelete('cascade');
            
            // Indexes
            $table->index('bon_commande_id');
            $table->index('received_by');
            $table->index('date_reception');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_receptions');
    }
}