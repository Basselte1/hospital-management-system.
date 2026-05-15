<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddStatusFlagsToExamensLaboratoireTable extends Migration


{
    public function up(): void
    {
        Schema::table('examens_laboratoire', function (Blueprint $table) {
            // Granular flags – all default false
            $table->boolean('is_en_cours')->default(false)->after('statut')
                ->comment('Analyse en cours');
            $table->boolean('is_valide')->default(false)->after('is_en_cours')
                ->comment('Résultats validés par le biologiste');
            $table->boolean('is_remis')->default(false)->after('is_valide')
                ->comment('Résultats remis au patient / transmis au clinicien');
            $table->boolean('is_archive')->default(false)->after('is_remis')
                ->comment('Dossier archivé');

            // Composite index to speed up the most common dashboard query:
            // "show me everything validated but not yet remis"
            $table->index(['is_valide', 'is_remis'], 'idx_valide_remis');
        });

        // ── Back-fill flags from the existing `statut` string ──────────
        DB::statement("
            UPDATE examens_laboratoire SET
                is_en_cours = (statut = 'en_cours'),
                is_valide   = (statut IN ('valide', 'remis', 'archive')),
                is_remis    = (statut IN ('remis', 'archive')),
                is_archive  = (statut = 'archive')
        ");
    }

    public function down(): void
    {
        Schema::table('examens_laboratoire', function (Blueprint $table) {
            $table->dropIndex('idx_valide_remis');
            $table->dropColumn(['is_en_cours', 'is_valide', 'is_remis', 'is_archive']);
        });
    }
}