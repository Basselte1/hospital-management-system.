<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevisElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devis_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom'); // Element name (e.g., "CS ANESTHESIQUE EN INTERNE")
            $table->string('code')->nullable(); // Short code (e.g., "KC", "KA")
            $table->integer('prix_unitaire')->default(0); // Unit price in FCFA
            $table->text('description')->nullable(); // Optional description
            $table->boolean('actif')->default(true); // Active/Inactive
            $table->integer('user_id')->nullable(); // User who created it
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devis_elements');
    }
}