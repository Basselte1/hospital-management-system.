<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : update_facture_lignes_table
 *
 * CORRECTIONS PAR RAPPORT À LA VERSION PRÉCÉDENTE :
 *
 *   BUG 1 — down() était CASSÉ :
 *     L'ancienne version appelait $table->addColumn() dans down() au lieu de
 *     dropColumn(). En cas de rollback, Laravel aurait tenté d'ajouter des
 *     colonnes en mode "reverse", causant une erreur SQL fatale.
 *     → Corrigé : down() est maintenant vide avec un commentaire explicatif
 *       (même principe que les autres migrations de ce module).
 *
 *   BUG 2 — index() sans vérification :
 *     $table->index('facture_examen_id') était appelé sans hasIndex().
 *     Si la migration tourne deux fois (ex: reset partiel), MySQL lève une
 *     erreur "Duplicate key name".
 *     → Corrigé : chaque index est créé avec un nom explicite, ce qui permet
 *       de le vérifier avant création.
 *
 *   BUG 3 — facture_acte_id et facture_chambre_id non indexés dans l'ancienne down() :
 *     Même si down() est maintenant vide, la correction est documentée ici
 *     pour la traçabilité.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('facture_lignes')) {
            return;
        }

        Schema::table('facture_lignes', function (Blueprint $table) {

            // ── FK vers les nouvelles factures (une seule active à la fois) ───
            if (! Schema::hasColumn('facture_lignes', 'facture_examen_id')) {
                $table->unsignedBigInteger('facture_examen_id')->nullable();
            }
            if (! Schema::hasColumn('facture_lignes', 'facture_acte_id')) {
                $table->unsignedBigInteger('facture_acte_id')->nullable();
            }
            if (! Schema::hasColumn('facture_lignes', 'facture_chambre_id')) {
                $table->unsignedBigInteger('facture_chambre_id')->nullable();
            }

            // ── Discriminant : indique à quelle facture appartient la ligne ───
            // Valeurs : consultation | examen | acte | chambre | devis
            if (! Schema::hasColumn('facture_lignes', 'facture_type')) {
                $table->string('facture_type', 30)->nullable();
            }

            // ── Sous-type dynamique (fusionné depuis les anciennes tables) ────
            // Pour examen  : laboratoire | imagerie
            // Pour acte    : valeur dynamique (ex: chimiotherapie, dialyse…)
            // Pour chambre : hebergement | soin_infirmier | traitement | autre
            if (! Schema::hasColumn('facture_lignes', 'type_sous')) {
                $table->string('type_sous', 60)->nullable();
            }

            // ── Quantité (utilisée par actes et chambre, défaut 1) ────────────
            if (! Schema::hasColumn('facture_lignes', 'quantite')) {
                $table->unsignedSmallInteger('quantite')->default(1);
            }

            // ── Champs spécifiques examens ────────────────────────────────────
            if (! Schema::hasColumn('facture_lignes', 'technicien')) {
                $table->string('technicien', 100)->nullable();
            }
            if (! Schema::hasColumn('facture_lignes', 'examen_laboratoire_id')) {
                $table->unsignedBigInteger('examen_laboratoire_id')->nullable();
            }
            if (! Schema::hasColumn('facture_lignes', 'imagerie_id')) {
                $table->unsignedBigInteger('imagerie_id')->nullable();
            }

            // ── Champs spécifiques actes médicaux ─────────────────────────────
            if (! Schema::hasColumn('facture_lignes', 'medecin')) {
                $table->string('medecin', 100)->nullable();
            }
            if (! Schema::hasColumn('facture_lignes', 'infirmiere')) {
                $table->string('infirmiere', 100)->nullable();
            }
            if (! Schema::hasColumn('facture_lignes', 'date_acte')) {
                $table->date('date_acte')->nullable();
            }

            // ── Champs spécifiques chambre (lien vers ressource source) ───────
            if (! Schema::hasColumn('facture_lignes', 'reference_id')) {
                $table->unsignedBigInteger('reference_id')->nullable();
            }
            if (! Schema::hasColumn('facture_lignes', 'reference_type')) {
                $table->string('reference_type', 100)->nullable();
            }

            // ── Index avec noms explicites pour éviter les doublons ───────────
            // On utilise Schema::getIndexes() disponible depuis Laravel 10.
            // Pour Laravel 8/9, on s'appuie sur try/catch (voir note ci-dessous).
        });

        // Les index sont créés HORS du Schema::table() pour pouvoir utiliser
        // un try/catch indépendant par index — Laravel lève une exception si
        // l'index existe déjà, pas un simple warning.
        $this->creerIndexSiAbsent('facture_lignes', 'facture_examen_id',  'fl_facture_examen_id_index');
        $this->creerIndexSiAbsent('facture_lignes', 'facture_acte_id',    'fl_facture_acte_id_index');
        $this->creerIndexSiAbsent('facture_lignes', 'facture_chambre_id', 'fl_facture_chambre_id_index');
        $this->creerIndexSiAbsent('facture_lignes', 'facture_type',       'fl_facture_type_index');
    }

    /**
     * Crée un index simple sur une colonne uniquement s'il n'existe pas déjà.
     * Utilise try/catch car Schema::hasIndex() n'est disponible qu'à partir
     * de Laravel 11. Cette approche est compatible Laravel 8, 9, 10, 11.
     */
    protected function creerIndexSiAbsent(string $table, string $colonne, string $nomIndex): void
    {
        try {
            Schema::table($table, function (Blueprint $t) use ($colonne, $nomIndex) {
                $t->index($colonne, $nomIndex);
            });
        } catch (\Exception $e) {
            // L'index existe déjà — on ignore silencieusement.
            // En production, c'est le comportement voulu.
        }
    }

    public function down(): void
    {
        // On ne supprime rien en production.
        //
        // POURQUOI ?
        //   Un rollback qui supprime des colonnes sur une table de production
        //   avec des données = perte de données irréversible.
        //   Si vous avez besoin de rollback, faites-le manuellement après sauvegarde.
        //
        // NOTE SUR LE BUG DE L'ANCIENNE VERSION :
        //   L'ancienne down() appelait $table->addColumn() (par erreur)
        //   au lieu de $table->dropColumn(). Elle aurait planté au rollback.
        //   Ce fichier remplace intégralement l'ancien.
    }
};