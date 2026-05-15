<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * IMPORTANT : migration compatible SQLite uniquement.
     * SQLite ne supporte pas MODIFY COLUMN ni REGEXP_REPLACE.
     *
     * Stratégie pour convertir montant (string → integer) sur SQLite :
     * SQLite est faiblement typé — on peut stocker des entiers dans une
     * colonne déclarée VARCHAR. On crée une colonne temporaire, on copie
     * les données nettoyées, on supprime l'ancienne, on renomme.
     */
    public function up(): void
    {
        if (! Schema::hasTable('facture_consultations')) {
            return;
        }

        Schema::table('facture_consultations', function (Blueprint $table) {

            // ── 1. Nouvelles colonnes (ajout sécurisé) ───────────────────────

            if (! Schema::hasColumn('facture_consultations', 'deleted_at')) {
                $table->softDeletes(); // ← MANQUAIT dans les deux migrations
            }
            if (! Schema::hasColumn('facture_consultations', 'consultation_id')) {
                $table->unsignedBigInteger('consultation_id')->nullable();
            }
            if (! Schema::hasColumn('facture_consultations', 'type_consultation')) {
                $table->string('type_consultation', 60)->nullable();
            }
            if (! Schema::hasColumn('facture_consultations', 'patient_name')) {
                $table->string('patient_name')->nullable();
            }
            if (! Schema::hasColumn('facture_consultations', 'patient_numero_dossier')) {
                $table->string('patient_numero_dossier', 50)->nullable();
            }
            if (! Schema::hasColumn('facture_consultations', 'statut')) {
                $table->string('statut', 20)->default('Non soldée');
            }
            if (! Schema::hasColumn('facture_consultations', 'is_printed')) {
                $table->boolean('is_printed')->default(false);
            }
            if (! Schema::hasColumn('facture_consultations', 'printed_at')) {
                $table->timestamp('printed_at')->nullable();
            }
            if (! Schema::hasColumn('facture_consultations', 'printed_by')) {
                $table->unsignedBigInteger('printed_by')->nullable();
            }
            if (! Schema::hasColumn('facture_consultations', 'notes')) {
                $table->text('notes')->nullable();
            }
        });

        // ── 2. Nettoyage de montant (compatible SQLite) ───────────────────────
        // SQLite est faiblement typé : on peut y stocker des entiers dans une
        // colonne VARCHAR. On nettoie les valeurs avec PHP plutôt qu'avec SQL,
        // car SQLite n'a pas REGEXP_REPLACE ni MODIFY COLUMN.

        $this->nettoyerColonneMontant();
    }

    /**
     * Nettoie et normalise la colonne montant pour SQLite.
     * Utilise PHP pour le nettoyage — plus portable et plus lisible.
     */
    private function nettoyerColonneMontant(): void
    {
        // Récupère uniquement les lignes où montant n'est pas déjà un entier pur
        $lignes = DB::table('facture_consultations')
            ->whereRaw("montant GLOB '*[^0-9]*' OR montant = '' OR montant IS NULL")
            ->select(['id', 'montant'])
            ->get();

        if ($lignes->isEmpty()) {
            return; // Tout est déjà propre — rien à faire
        }

        $problemes = [];

        foreach ($lignes as $ligne) {
            // Supprime tout ce qui n'est pas un chiffre (FCFA, espaces, virgules...)
            $valeurPropre = preg_replace('/[^0-9\-]/', '', (string) $ligne->montant);

            // Si vide après nettoyage → 0
            if ($valeurPropre === '' || $valeurPropre === null) {
                $valeurPropre = '0';
            }

            // Vérifie que le résultat est bien un entier
            if (! preg_match('/^-?[0-9]+$/', $valeurPropre)) {
                $problemes[] = "id={$ligne->id} : '{$ligne->montant}'";
                continue;
            }

            DB::table('facture_consultations')
                ->where('id', $ligne->id)
                ->update(['montant' => (int) $valeurPropre]);
        }

        // Si des valeurs sont non convertibles → on bloque la migration
        if (! empty($problemes)) {
            throw new \RuntimeException(
                "Migration annulée — valeurs non convertibles dans montant :\n" .
                implode("\n", $problemes) . "\n" .
                "Corrigez ces lignes manuellement et relancez."
            );
        }
    }

    /**
     * Reverse the migrations.
     * Compatible SQLite : on ne fait rien sur montant (déjà stocké comme entier).
     * On supprime uniquement les colonnes ajoutées.
     */
    public function down(): void
    {
        if (! Schema::hasTable('facture_consultations')) {
            return;
        }

        Schema::table('facture_consultations', function (Blueprint $table) {
            $colonnesASupprimer = [
                'deleted_at',
                'consultation_id',
                'type_consultation',
                'patient_name',
                'patient_numero_dossier',
                'statut',
                'is_printed',
                'printed_at',
                'printed_by',
                'notes',
            ];

            foreach ($colonnesASupprimer as $colonne) {
                if (Schema::hasColumn('facture_consultations', $colonne)) {
                    $table->dropColumn($colonne);
                }
            }
        });
    }
};