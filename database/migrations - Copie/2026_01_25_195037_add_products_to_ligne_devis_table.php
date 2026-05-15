<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductsToLigneDevisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ligne_devis', function (Blueprint $table) {
            // Add type to distinguish between procedures and products
            $table->enum('type', ['procedure', 'medication', 'material', 'anesthesie'])
                  ->default('procedure')
                  ->after('id');
            
            // Link to products table (nullable for procedures)
            $table->integer('produit_id')->nullable()->after('type');
            
            // Track if stock was deducted when devis was validated
            $table->boolean('stock_deducted')->default(false)->after('prix_u');
            
            // Add index for better performance
            $table->index('produit_id');
            $table->index('type');
            
            // Foreign key constraint
            $table->foreign('produit_id')
                  ->references('id')
                  ->on('produits')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ligne_devis', function (Blueprint $table) {
            $table->dropForeign(['produit_id']);
            $table->dropIndex(['produit_id']);
            $table->dropIndex(['type']);
            $table->dropColumn(['type', 'produit_id', 'stock_deducted']);
        });
    }
}