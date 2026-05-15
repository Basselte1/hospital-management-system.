<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      
        // Table centrale de paiement.
        // Liée à n'importe quel type de facture via (facture_type + facture_id).
        //
        // Valeurs de facture_type attendues :
        //   consultation | examen | acte | chambre | devis
        //
        // On utilise des clés courtes (pas le nom complet de classe PHP)
        // pour rester stable si le namespace change.
 
        if (! Schema::hasTable('paiements')) {
            Schema::create('paiements', function (Blueprint $table) {
                $table->id();
 
                $table->unsignedBigInteger('patient_id');
                $table->unsignedBigInteger('user_id')->nullable(); // caissier
 
                // Polymorphisme manuel
                $table->string('facture_type', 30); // consultation | examen | acte | chambre | devis
                $table->unsignedBigInteger('facture_id');
 
                $table->decimal('montant', 12, 0);
                $table->string('mode_paiement', 50);
                $table->string('mode_paiement_info_sup')->nullable();
                $table->string('reference', 100)->nullable(); // référence externe (reçu, virement…)
                $table->text('notes')->nullable();
                $table->timestamp('paye_le')->useCurrent();
 
                $table->timestamps();
 
                $table->foreign('patient_id')
                      ->references('id')->on('patients')
                      ->onDelete('restrict');
                $table->foreign('user_id')
                      ->references('id')->on('users')
                      ->onDelete('set null');
 
                // Index composite essentiel : retrouver tous les paiements d'une facture
                $table->index(['facture_type', 'facture_id'], 'idx_paiements_facture');
                $table->index(['patient_id', 'paye_le'],      'idx_paiements_patient_date');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
