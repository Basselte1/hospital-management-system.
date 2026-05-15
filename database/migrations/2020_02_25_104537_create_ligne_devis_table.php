<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLigneDevisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ligne_devis', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            
            // Type of line item
            $table->enum('type', ['procedure', 'medication', 'material', 'anesthesie'])->default('procedure');
            
            // References
            $table->integer('devi_id')->index();
            $table->integer('produit_id')->nullable()->index();
            
            // Item details
            $table->string('element');
            $table->integer('quantite');
            $table->integer('prix_u');
            
            // Stock tracking
            $table->boolean('stock_deducted')->default(false);
            
            // Indexes
            $table->index('type');
            
            // Foreign keys
            $table->foreign('devi_id')->references('id')->on('devis')->onDelete('cascade');
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ligne_devis');
    }
}



