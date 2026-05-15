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
        Schema::table('facture_consultations', function (Blueprint $table) {
            // Ajouter la colonne statut si elle n'existe pas déjà
            if (!Schema::hasColumn('facture_consultations', 'statut')) {
                $table->enum('statut', ['Soldée', 'Non soldée'])->default('Non soldée')->after('reste');
            }
            
            // Ajouter la colonne pour tracker les impressions
            if (!Schema::hasColumn('facture_consultations', 'is_printed')) {
                $table->boolean('is_printed')->default(false)->after('statut');
            }
            if (!Schema::hasColumn('facture_consultations', 'printed_at')) {
                $table->timestamp('printed_at')->nullable()->after('is_printed');
            }
            if (!Schema::hasColumn('facture_consultations', 'printed_by')) {
                $table->unsignedBigInteger('printed_by')->nullable()->after('printed_at');
                // Foreign key vers users pour savoir qui a imprimé
                $table->foreign('printed_by')->references('id')->on('users')->onDelete('set null');
            }
        });
        
        // Mettre à jour les factures existantes selon leur reste
        DB::statement("UPDATE facture_consultations SET statut = CASE WHEN reste = 0 THEN 'Soldée' ELSE 'Non soldée' END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facture_consultations', function (Blueprint $table) {
            $table->dropForeign(['printed_by']);
            $table->dropColumn(['is_printed', 'printed_at', 'printed_by']);
            
            if (Schema::hasColumn('facture_consultations', 'statut')) {
                $table->dropColumn('statut');
            }
        });
    }
};