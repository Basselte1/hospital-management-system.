<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * MIGRATION 2 — Table facture_lignes + lien visit sur facture_consultations
 *
 * Principe fondamental :
 *   1 PatientVisit  →  1 FactureConsultation (en-tête : totaux, paiement, statut)
 *   1 FactureConsultation  →  N FactureLignes (détail : consultation, examen, soin…)
 *
 * Pourquoi une table facture_lignes et pas juste des colonnes ?
 * Un patient peut avoir une consultation + 2 examens + 1 soin infirmier.
 * C'est un nombre variable d'actes → on ne peut pas mettre ça dans des colonnes fixes.
 * La table de lignes est le pattern classique de toute facture commerciale.
 *
 * Compatibilité SQLite :
 * SQLite ne supporte pas ALTER TABLE ADD FOREIGN KEY.
 * On utilise uniquement des index simples pour les FK → pas de contrainte formelle,
 * mais Laravel Eloquent gère les relations au niveau applicatif.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Lier facture_consultations à la visite ──────────────────────
        if (!Schema::hasColumn('facture_consultations', 'patient_visit_id')) {
            Schema::table('facture_consultations', function (Blueprint $table) {
                // Lien vers la visite qui a généré cette facture
                $table->unsignedBigInteger('patient_visit_id')
                      ->nullable()
                      ->after('patient_id');

                // Index simple (SQLite ne supporte pas les FK via alter table)
                $table->index('patient_visit_id');
            });
        }

        // ── 2. Créer la table facture_lignes ──────────────────────────────
        if (!Schema::hasTable('facture_lignes')) {
            Schema::create('facture_lignes', function (Blueprint $table) {
                $table->id();

                // En-tête de facture parente
                $table->unsignedBigInteger('facture_consultation_id');
                $table->index('facture_consultation_id');

                /**
                 * Type de l'acte facturé.
                 * Valeurs possibles :
                 *   'consultation'   → première consultation
                 *   'consultation_suivi' → consultation de suivi
                 *   'examen_labo'    → analyse biologique
                 *   'examen_radio'   → imagerie / radiologie
                 *   'soin_infirmier' → soin réalisé par une infirmière
                 *   'chambre'        → hébergement
                 *   'pharmacie'      → médicaments délivrés
                 *   'autre'          → tout autre acte
                 */
                $table->string('type_acte')->default('consultation');

                // Libellé affiché sur la facture imprimée
                // ex : "Échographie rénale", "NFS + Créatinine", "Consultation urologie"
                $table->string('libelle');

                // Montant de cette ligne (en FCFA)
                $table->integer('montant')->default(0);

                /**
                 * Lien optionnel vers l'acte source dans sa table d'origine.
                 * Exemple : acte_type='Examen', acte_id=12
                 * Permet depuis la facture d'accéder au résultat de l'examen.
                 * Optionnel car certains actes (chambre, pharmacie) sont saisis
                 * directement sans passer par un modèle intermédiaire.
                 */
                $table->string('acte_type')->nullable();  // 'Examen', 'Imagerie', 'SoinsInfirmier'
                $table->unsignedBigInteger('acte_id')->nullable();

                // Médecin qui a prescrit ou réalisé cet acte
                $table->string('medecin')->nullable();

                // Ordre d'affichage dans le PDF (consultation d'abord, puis examens…)
                $table->unsignedSmallInteger('ordre')->default(0);

                $table->timestamps();

                // Index composite pour accélerer le chargement des lignes d'une facture
                $table->index(['facture_consultation_id', 'ordre']);

                // Index pour retrouver toutes les lignes d'un type (bilan par service)
                $table->index('type_acte');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('facture_lignes');

        if (Schema::hasColumn('facture_consultations', 'patient_visit_id')) {
            Schema::table('facture_consultations', function (Blueprint $table) {
                $table->dropIndex(['patient_visit_id']);
                $table->dropColumn('patient_visit_id');
            });
        }
    }
};