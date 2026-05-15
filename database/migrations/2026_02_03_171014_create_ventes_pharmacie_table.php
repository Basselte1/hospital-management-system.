<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentesPharmacieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventes_pharmacie', function (Blueprint $table) {
            $table->id();
            $table->string('numero_vente')->unique(); // VP-YYYY-NNNN
            $table->unsignedBigInteger('pharmacien_id'); // User who made the sale
            $table->unsignedBigInteger('patient_id')->nullable(); // For patient sales
            $table->unsignedBigInteger('client_id')->nullable(); // For external client sales
            $table->unsignedBigInteger('ordonance_id')->nullable(); // Prescription reference
            $table->enum('type_vente', ['patient', 'client_externe']); // Type of sale
            $table->integer('montant_total')->default(0); // Total amount
            $table->integer('montant_paye')->default(0); // Amount paid
            $table->integer('montant_reste')->default(0); // Remaining amount
            $table->enum('statut_paiement', ['en_attente', 'partiel', 'soldee'])->default('en_attente');
            $table->unsignedBigInteger('caissier_id')->nullable(); // Cashier who processed payment
            $table->datetime('date_paiement')->nullable(); // Payment date
            $table->string('mode_paiement')->nullable(); // Payment method
            $table->string('reference_paiement')->nullable(); // Payment reference
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('pharmacien_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('ordonance_id')->references('id')->on('ordonances')->onDelete('set null');
            $table->foreign('caissier_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventes_pharmacie');
    }
}











