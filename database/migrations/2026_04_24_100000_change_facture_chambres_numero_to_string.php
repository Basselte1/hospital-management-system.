<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration : changer numero de INTEGER à VARCHAR(30)
 *
 * PROBLÈME :
 *   La migration 2019 a créé `numero` comme INTEGER.
 *   La migration 2026 a tenté d'ajouter `numero` STRING mais hasColumn()
 *   a bloqué l'exécution → la colonne est restée INTEGER.
 *
 *   Le code a contourné le problème en créant `numero_facture` (STRING)
 *   et en laissant `numero` (INTEGER) inutilisé.
 *
 * CORRECTION :
 *   Cette migration change le type de `numero` en VARCHAR(30) nullable.
 *   Ensuite, on uniformise le code pour utiliser `numero` partout
 *   (comme FactureExamen, FactureActe, etc.).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('facture_chambres')) {
            return;
        }

        // Doctrine DBAL n'est pas toujours installé → on utilise une requête brute
        // MySQL/MariaDB : ALTER TABLE ... MODIFY COLUMN
        try {
            DB::statement('ALTER TABLE facture_chambres MODIFY numero VARCHAR(30) NULL');
        } catch (\Exception $e) {
            // Si la colonne est déjà VARCHAR ou si le driver ne supporte pas,
            // on ignore silencieusement.
        }

        // Optionnel : migrer les données de numero_facture vers numero
        // si numero_facture existe et numero est NULL
        if (Schema::hasColumn('facture_chambres', 'numero_facture')) {
            DB::statement("
                UPDATE facture_chambres
                SET numero = numero_facture
                WHERE numero IS NULL AND numero_facture IS NOT NULL
            ");
        }
    }

    public function down(): void
    {
        // On ne revient pas en arrière (perte de données alphanumériques possible)
    }
};

