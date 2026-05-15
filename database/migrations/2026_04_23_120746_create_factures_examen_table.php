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
      
          
        if (! Schema::hasTable('factures_examen')) {
            Schema::create('factures_examen', function (Blueprint $table) {
                $table->id();
                $table->string('numero', 30)->unique()->nullable();
 
                $table->unsignedBigInteger('patient_id');
                $table->unsignedBigInteger('consultation_id')->nullable(); // consultation source
                $table->unsignedBigInteger('user_id')->nullable();
 
                // Snapshot patient (conservation du nom même si le patient est supprimé)
                $table->string('patient_name')->nullable();
                $table->string('patient_numero_dossier', 50)->nullable();
 
                // Montants
                $table->decimal('montant_total', 12, 0)->default(0);
                $table->decimal('avance', 12, 0)->default(0);
                $table->decimal('assurancec', 12, 0)->default(0);  // part prise par l'assurance
                $table->decimal('assurec', 12, 0)->default(0);     // part restant à la charge de l'assuré
                $table->decimal('reste', 12, 0)->default(0);
 
                // Statut & assurance
                $table->string('statut', 20)->default('Non soldée');
                $table->boolean('assurance')->default(false);
                $table->string('numero_assurance', 100)->nullable();
                $table->decimal('prise_en_charge', 5, 2)->default(0); // % pris en charge par l'assurance
 
                // Paiement (nullable — les nouveaux paiements passent par la table paiements)
                $table->string('mode_paiement', 50)->nullable();
                $table->string('mode_paiement_info_sup')->nullable();
 
                // Impression
                $table->boolean('is_printed')->default(false);
                $table->timestamp('printed_at')->nullable();
                $table->unsignedBigInteger('printed_by')->nullable();
 
                $table->text('notes')->nullable();
                $table->softDeletes();
                $table->timestamps();
 
                // Contraintes
                $table->foreign('patient_id')
                      ->references('id')->on('patients')
                      ->onDelete('restrict');
                $table->foreign('user_id')
                      ->references('id')->on('users')
                      ->onDelete('set null');
 
                $table->index(['patient_id', 'statut']);
                $table->index('consultation_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures_examen');
    }
};
