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
        if (Schema::hasTable('facture_devis')) {
            Schema::table('facture_devis', function (Blueprint $table) {
 
                if (! Schema::hasColumn('facture_devis', 'numero')) {
                    $table->string('numero', 30)->nullable();
                }
                if (! Schema::hasColumn('facture_devis', 'statut')) {
                    $table->string('statut', 20)->default('Non soldée');
                }
                if (! Schema::hasColumn('facture_devis', 'patient_name')) {
                    $table->string('patient_name')->nullable();
                }
                if (! Schema::hasColumn('facture_devis', 'patient_numero_dossier')) {
                    $table->string('patient_numero_dossier', 50)->nullable();
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
        Schema::table('facture_devis', function (Blueprint $table) {
            $table->dropColumn([
                'numero',
                'statut',
                'patient_name',
                'patient_numero_dossier',
            ]);
        });


    }
};
