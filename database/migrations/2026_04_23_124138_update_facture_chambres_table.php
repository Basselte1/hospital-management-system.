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
        //

         if (Schema::hasTable('facture_chambres')) {
            Schema::table('facture_chambres', function (Blueprint $table) {
 
                if (! Schema::hasColumn('facture_chambres', 'chambre_id')) {
                    $table->unsignedBigInteger('chambre_id')->nullable();
                }
                if (! Schema::hasColumn('facture_chambres', 'montant_hebergement')) {
                    $table->decimal('montant_hebergement', 12, 0)->default(0);
                }
                if (! Schema::hasColumn('facture_chambres', 'montant_soins')) {
                    $table->decimal('montant_soins', 12, 0)->default(0);
                }
                if (! Schema::hasColumn('facture_chambres', 'montant_traitements')) {
                    $table->decimal('montant_traitements', 12, 0)->default(0);
                }
                if (! Schema::hasColumn('facture_chambres', 'montant_total')) {
                    $table->decimal('montant_total', 12, 0)->default(0);
                }
                if (! Schema::hasColumn('facture_chambres', 'statut')) {
                    $table->string('statut', 20)->default('Non soldée');
                }
                if (! Schema::hasColumn('facture_chambres', 'patient_name')) {
                    $table->string('patient_name')->nullable();
                }
                if (! Schema::hasColumn('facture_chambres', 'patient_numero_dossier')) {
                    $table->string('patient_numero_dossier', 50)->nullable();
                }
                if (! Schema::hasColumn('facture_chambres', 'numero')) {
                    $table->string('numero', 30)->nullable();
                }
                if (! Schema::hasColumn('facture_chambres', 'is_printed')) {
                    $table->boolean('is_printed')->default(false);
                }
                if (! Schema::hasColumn('facture_chambres', 'printed_at')) {
                    $table->timestamp('printed_at')->nullable();
                }
                if (! Schema::hasColumn('facture_chambres', 'printed_by')) {
                    $table->unsignedBigInteger('printed_by')->nullable();
                }
                if (! Schema::hasColumn('facture_chambres', 'date_entre')) {
                    $table->date('date_entre')->nullable();
                }
                if (! Schema::hasColumn('facture_chambres', 'date_sortie')) {
                    $table->date('date_sortie')->nullable();
                }
                if (! Schema::hasColumn('facture_chambres', 'notes')) {
                    $table->text('notes')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
