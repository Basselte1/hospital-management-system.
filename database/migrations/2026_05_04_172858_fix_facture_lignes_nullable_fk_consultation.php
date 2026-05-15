<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : fix_facture_lignes_nullable_fk_consultation
 *
 * OBJECTIF :
 *   Rendre facture_consultation_id nullable dans facture_lignes.
 *   SQLite ne supporte pas ALTER COLUMN → stratégie : créer une table temporaire,
 *   copier les données, supprimer l'ancienne, renommer.
 *
 * CORRECTIONS appliquées :
 *
 *   BUG 1 — Table temporaire déjà existante
 *     → DROP TABLE IF EXISTS avant CREATE.
 *
 *   BUG 2 — Migration non idempotente
 *     → Vérification via PRAGMA table_info avant de commencer.
 *
 *   BUG 3 — PRAGMA foreign_keys non restauré en cas d'exception
 *     → try/finally garantit la restauration.
 *
 *   BUG 4 — INSERT INTO ... SELECT * échoue si des colonnes NOT NULL
 *     contiennent des NULL en base (données legacy).
 *     "NOT NULL DEFAULT 0" ne s'applique qu'aux INSERT sans valeur explicite.
 *     SELECT * copie les NULL tels quels → contrainte violée.
 *     → SELECT avec COALESCE(colonne, valeur_defaut) sur chaque colonne NOT NULL.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── Garde-fou : table source absente ─────────────────────────────────
        if (! Schema::hasTable('facture_lignes')) {
            return;
        }

        // ── Garde-fou idempotence ─────────────────────────────────────────────
        // Si facture_consultation_id est déjà nullable (notnull = 0), on sort.
        $colonneInfo = collect(DB::select('PRAGMA table_info(facture_lignes)'))
            ->firstWhere('name', 'facture_consultation_id');

        if ($colonneInfo && $colonneInfo->notnull == 0) {
            return;
        }

        // ── Nettoyage préventif ────────────────────────────────────────────────
        // Supprime la table temporaire si une exécution précédente a planté.
        DB::statement('DROP TABLE IF EXISTS facture_lignes_fixed');

        try {
            DB::statement('PRAGMA foreign_keys = OFF');

            // ── Étape 1 : nouvelle table avec facture_consultation_id nullable ──
            DB::statement('
                CREATE TABLE facture_lignes_fixed (
                    id                      INTEGER PRIMARY KEY AUTOINCREMENT,
                    facture_consultation_id INTEGER NULL,
                    facture_examen_id       INTEGER NULL,
                    facture_acte_id         INTEGER NULL,
                    facture_chambre_id      INTEGER NULL,
                    facture_type            VARCHAR(30)  NULL,
                    type_acte               VARCHAR(100) NULL,
                    type_sous               VARCHAR(60)  NULL,
                    libelle                 VARCHAR(255) NOT NULL DEFAULT "",
                    montant                 INTEGER      NOT NULL DEFAULT 0,
                    quantite                SMALLINT     NOT NULL DEFAULT 1,
                    ordre                   INTEGER      NOT NULL DEFAULT 0,
                    technicien              VARCHAR(100) NULL,
                    examen_laboratoire_id   INTEGER NULL,
                    imagerie_id             INTEGER NULL,
                    medecin                 VARCHAR(100) NULL,
                    infirmiere              VARCHAR(100) NULL,
                    date_acte               DATE NULL,
                    acte_type               VARCHAR(100) NULL,
                    acte_id                 INTEGER NULL,
                    reference_id            INTEGER NULL,
                    reference_type          VARCHAR(100) NULL,
                    created_at              DATETIME NULL,
                    updated_at              DATETIME NULL
                )
            ');

            // ── Étape 2 : copie des données avec COALESCE sur les colonnes NOT NULL ──
            //
            // POURQUOI COALESCE et pas SELECT * ?
            //   SELECT * copie les valeurs telles quelles, y compris les NULL.
            //   Si une ligne en base a ordre = NULL (données créées avant la contrainte),
            //   SQLite lève "NOT NULL constraint failed" même si DEFAULT 0 est défini,
            //   car DEFAULT ne s'applique qu'aux INSERT sans valeur, pas aux SELECT.
            //   COALESCE(ordre, 0) remplace les NULL par 0 au moment de la copie.
            //   On fait pareil pour montant, quantite et libelle par sécurité.
            DB::statement("
                INSERT INTO facture_lignes_fixed (
                    id,
                    facture_consultation_id,
                    facture_examen_id,
                    facture_acte_id,
                    facture_chambre_id,
                    facture_type,
                    type_acte,
                    type_sous,
                    libelle,
                    montant,
                    quantite,
                    ordre,
                    technicien,
                    examen_laboratoire_id,
                    imagerie_id,
                    medecin,
                    infirmiere,
                    date_acte,
                    acte_type,
                    acte_id,
                    reference_id,
                    reference_type,
                    created_at,
                    updated_at
                )
                SELECT
                    id,
                    facture_consultation_id,
                    facture_examen_id,
                    facture_acte_id,
                    facture_chambre_id,
                    facture_type,
                    type_acte,
                    type_sous,
                    COALESCE(libelle,  ''),
                    COALESCE(montant,  0),
                    COALESCE(quantite, 1),
                    COALESCE(ordre,    0),
                    technicien,
                    examen_laboratoire_id,
                    imagerie_id,
                    medecin,
                    infirmiere,
                    date_acte,
                    acte_type,
                    acte_id,
                    reference_id,
                    reference_type,
                    created_at,
                    updated_at
                FROM facture_lignes
            ");

            // ── Étape 3 : remplacer l'ancienne table ──────────────────────────
            DB::statement('DROP TABLE facture_lignes');
            DB::statement('ALTER TABLE facture_lignes_fixed RENAME TO facture_lignes');

            // ── Étape 4 : recréer les index (perdus avec la table) ────────────
            DB::statement('CREATE INDEX IF NOT EXISTS fl_facture_consultation_id_index ON facture_lignes (facture_consultation_id)');
            DB::statement('CREATE INDEX IF NOT EXISTS fl_facture_examen_id_index       ON facture_lignes (facture_examen_id)');
            DB::statement('CREATE INDEX IF NOT EXISTS fl_facture_acte_id_index         ON facture_lignes (facture_acte_id)');
            DB::statement('CREATE INDEX IF NOT EXISTS fl_facture_chambre_id_index      ON facture_lignes (facture_chambre_id)');
            DB::statement('CREATE INDEX IF NOT EXISTS fl_facture_type_index            ON facture_lignes (facture_type)');

        } finally {
            // Toujours restaurer les FK, même en cas d'exception.
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }

    public function down(): void
    {
        throw new \RuntimeException(
            'Rollback impossible : des lignes ont facture_consultation_id = NULL depuis ce correctif. ' .
            'Restaurez depuis une sauvegarde si nécessaire.'
        );
    }
};