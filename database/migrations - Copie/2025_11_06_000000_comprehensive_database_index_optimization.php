<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Comprehensive Database Index Optimization
 * 
 * Applies Universal Database Index Optimization Rules:
 * - Index foreign keys, dates, status columns, unique identifiers
 * - Avoid indexing TEXT/BLOB columns, mostly NULL columns
 * - Create composite indexes for common query patterns
 */
return new class extends Migration
{
    public function up(): void
    {
        // PATIENTS TABLE
        if (Schema::hasTable('patients')) {
            Schema::table('patients', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'created_at', 'patients_created_at_index');
                $this->addIndexIfNotExists($table, 'date_insertion', 'patients_date_insertion_index');
                $this->addIndexIfNotExists($table, 'assurance', 'patients_assurance_index');
                $this->addIndexIfNotExists($table, 'motif', 'patients_motif_index');
                $this->addIndexIfNotExists($table, 'user_id', 'patients_user_id_index');
                $this->addCompositeIndexIfNotExists($table, ['created_at', 'assurance'], 'patients_date_assurance_idx');
            });
        }

        // CONSULTATIONS TABLE
        if (Schema::hasTable('consultations')) {
            Schema::table('consultations', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'date_consultation', 'consultations_date_consultation_index');
                $this->addIndexIfNotExists($table, 'date_intervention', 'consultations_date_intervention_index');
                $this->addIndexIfNotExists($table, 'date_consultation_anesthesiste', 'consultations_date_anesthesiste_idx');
                $this->addIndexIfNotExists($table, 'created_at', 'consultations_created_at_index');
                $this->addIndexIfNotExists($table, 'medecin_r', 'consultations_medecin_r_index');
                $this->addIndexIfNotExists($table, 'type_intervention', 'consultations_type_intervention_idx');
                $this->addCompositeIndexIfNotExists($table, ['patient_id', 'date_consultation'], 'consultations_patient_date_idx');
            });
        }

        // FACTURE_CONSULTATIONS TABLE
        if (Schema::hasTable('facture_consultations')) {
            Schema::table('facture_consultations', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'created_at', 'facture_consultations_created_at_idx');
                $this->addIndexIfNotExists($table, 'date_insertion', 'facture_consultations_date_insertion_idx');
                $this->addIndexIfNotExists($table, 'statut', 'facture_consultations_statut_index');
                $this->addIndexIfNotExists($table, 'deleted_at', 'facture_consultations_deleted_at_idx');
                $this->addIndexIfNotExists($table, 'motif', 'facture_consultations_motif_index');
                $this->addIndexIfNotExists($table, 'assurance', 'facture_consultations_assurance_idx');
                $this->addCompositeIndexIfNotExists($table, ['created_at', 'statut'], 'facture_consultations_date_statut_idx');
                $this->addCompositeIndexIfNotExists($table, ['patient_id', 'created_at'], 'facture_consultations_patient_date_idx');
            });
        }

        // HISTORIQUE_FACTURES TABLE
        if (Schema::hasTable('historique_factures')) {
            Schema::table('historique_factures', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'created_at', 'historique_factures_created_at_idx');
                $this->addIndexIfNotExists($table, 'date_insertion', 'historique_factures_date_insertion_idx');
                $this->addIndexIfNotExists($table, 'user_id', 'historique_factures_user_id_index');
                $this->addIndexIfNotExists($table, 'patient_id', 'historique_factures_patient_id_index');
                $this->addCompositeIndexIfNotExists($table, ['facture_consultation_id', 'created_at'], 'historique_factures_invoice_date_idx');
            });
        }

        // EVENTS TABLE
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'date', 'events_date_index');
                $this->addIndexIfNotExists($table, 'created_at', 'events_created_at_index');
                $this->addIndexIfNotExists($table, 'user_id', 'events_user_id_index');
                $this->addIndexIfNotExists($table, 'patient_id', 'events_patient_id_index');
                if (in_array('statut', Schema::getColumnListing('events'))) {
                    $this->addIndexIfNotExists($table, 'statut', 'events_statut_index');
                }
                $this->addCompositeIndexIfNotExists($table, ['user_id', 'date', 'start_time'], 'events_user_date_time_idx');
                $this->addCompositeIndexIfNotExists($table, ['patient_id', 'date'], 'events_patient_date_idx');
            });
        }

        // EXAMENS TABLE
        if (Schema::hasTable('examens')) {
            Schema::table('examens', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'created_at', 'examens_created_at_index');
                $this->addIndexIfNotExists($table, 'type', 'examens_type_index');
                $this->addCompositeIndexIfNotExists($table, ['patient_id', 'created_at'], 'examens_patient_date_idx');
            });
        }

        // ORDONANCES TABLE
        if (Schema::hasTable('ordonances')) {
            Schema::table('ordonances', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'user_id', 'ordonances_user_id_index');
                $this->addIndexIfNotExists($table, 'patient_id', 'ordonances_patient_id_index');
                $this->addIndexIfNotExists($table, 'created_at', 'ordonances_created_at_index');
                $this->addCompositeIndexIfNotExists($table, ['patient_id', 'created_at'], 'ordonances_patient_date_idx');
            });
        }

        // USERS TABLE
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'role_id', 'users_role_id_index');
                $this->addIndexIfNotExists($table, 'specialite', 'users_specialite_index');
                $this->addCompositeIndexIfNotExists($table, ['role_id', 'specialite'], 'users_role_specialite_idx');
            });
        }

        // DOSSIERS TABLE
        if (Schema::hasTable('dossiers')) {
            Schema::table('dossiers', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'patient_id', 'dossiers_patient_id_index');
                $this->addIndexIfNotExists($table, 'date_naissance', 'dossiers_date_naissance_index');
                $this->addIndexIfNotExists($table, 'created_at', 'dossiers_created_at_index');
                $this->addIndexIfNotExists($table, 'sexe', 'dossiers_sexe_index');
            });
        }

        // CHAMBRES TABLE
        if (Schema::hasTable('chambres')) {
            Schema::table('chambres', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'statut', 'chambres_statut_index');
                $this->addIndexIfNotExists($table, 'categorie', 'chambres_categorie_index');
                $this->addCompositeIndexIfNotExists($table, ['statut', 'categorie'], 'chambres_statut_categorie_idx');
            });
        }

        // PRODUITS TABLE
        if (Schema::hasTable('produits')) {
            Schema::table('produits', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'categorie', 'produits_categorie_index');
                $this->addIndexIfNotExists($table, 'qte_stock', 'produits_qte_stock_index');
                $this->addCompositeIndexIfNotExists($table, ['categorie', 'qte_stock'], 'produits_categorie_stock_idx');
            });
        }

        // DEVIS TABLE
        if (Schema::hasTable('devis')) {
            Schema::table('devis', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'created_at', 'devis_created_at_index');
                if (in_array('patient_id', Schema::getColumnListing('devis'))) {
                    $this->addIndexIfNotExists($table, 'patient_id', 'devis_patient_id_index');
                }
            });
        }

        // PRESCRIPTIONS TABLE
        if (Schema::hasTable('prescriptions')) {
            Schema::table('prescriptions', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'created_at', 'prescriptions_created_at_index');
                $columns = Schema::getColumnListing('prescriptions');
                if (in_array('patient_id', $columns)) {
                    $this->addIndexIfNotExists($table, 'patient_id', 'prescriptions_patient_id_index');
                }
                if (in_array('user_id', $columns)) {
                    $this->addIndexIfNotExists($table, 'user_id', 'prescriptions_user_id_index');
                }
            });
        }

        // SOINS TABLE
        if (Schema::hasTable('soins')) {
            Schema::table('soins', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'user_id', 'soins_user_id_index');
                $this->addIndexIfNotExists($table, 'patient_id', 'soins_patient_id_index');
                $this->addIndexIfNotExists($table, 'created_at', 'soins_created_at_index');
                if (in_array('contexte', Schema::getColumnListing('soins'))) {
                    $this->addIndexIfNotExists($table, 'contexte', 'soins_contexte_index');
                }
                $this->addCompositeIndexIfNotExists($table, ['patient_id', 'created_at'], 'soins_patient_date_idx');
            });
        }

        // VISITE_PREANESTHESIQUES TABLE
        if (Schema::hasTable('visite_preanesthesiques')) {
            Schema::table('visite_preanesthesiques', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'created_at', 'visite_preanesthesiques_created_at_idx');
                $columns = Schema::getColumnListing('visite_preanesthesiques');
                if (in_array('date_visite', $columns)) {
                    $this->addIndexIfNotExists($table, 'date_visite', 'visite_preanesthesiques_date_visite_idx');
                }
                if (in_array('patient_id', $columns)) {
                    $this->addIndexIfNotExists($table, 'patient_id', 'visite_preanesthesiques_patient_id_idx');
                }
                if (in_array('user_id', $columns)) {
                    $this->addIndexIfNotExists($table, 'user_id', 'visite_preanesthesiques_user_id_idx');
                }
            });
        }

        // ADAPTATION_TRAITEMENTS TABLE
        if (Schema::hasTable('adaptation_traitements')) {
            Schema::table('adaptation_traitements', function (Blueprint $table) {
                $columns = Schema::getColumnListing('adaptation_traitements');
                if (in_array('date', $columns)) {
                    $this->addIndexIfNotExists($table, 'date', 'adaptation_traitements_date_index');
                }
                if (in_array('patient_id', $columns)) {
                    $this->addIndexIfNotExists($table, 'patient_id', 'adaptation_traitements_patient_id_idx');
                }
            });
        }

        // PREMEDICATIONS TABLE
        if (Schema::hasTable('premedications')) {
            Schema::table('premedications', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'created_at', 'premedications_created_at_index');
                if (in_array('patient_id', Schema::getColumnListing('premedications'))) {
                    $this->addIndexIfNotExists($table, 'patient_id', 'premedications_patient_id_index');
                }
            });
        }

        // IMAGERIES TABLE
        if (Schema::hasTable('imageries')) {
            Schema::table('imageries', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'created_at', 'imageries_created_at_index');
                $columns = Schema::getColumnListing('imageries');
                if (in_array('patient_id', $columns)) {
                    $this->addIndexIfNotExists($table, 'patient_id', 'imageries_patient_id_index');
                }
                if (in_array('user_id', $columns)) {
                    $this->addIndexIfNotExists($table, 'user_id', 'imageries_user_id_index');
                }
            });
        }

        // INTERVENTIONS TABLE
        if (Schema::hasTable('interventions')) {
            Schema::table('interventions', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'created_at', 'interventions_created_at_index');
                if (in_array('patient_id', Schema::getColumnListing('interventions'))) {
                    $this->addIndexIfNotExists($table, 'patient_id', 'interventions_patient_id_index');
                }
            });
        }

        // FICHES TABLE
        if (Schema::hasTable('fiches')) {
            Schema::table('fiches', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'created_at', 'fiches_created_at_index');
                if (in_array('patient_id', Schema::getColumnListing('fiches'))) {
                    $this->addIndexIfNotExists($table, 'patient_id', 'fiches_patient_id_index');
                }
            });
        }

        // CLIENTS TABLE
        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'created_at', 'clients_created_at_index');
                $columns = Schema::getColumnListing('clients');
                if (in_array('date_insertion', $columns)) {
                    $this->addIndexIfNotExists($table, 'date_insertion', 'clients_date_insertion_index');
                }
                if (in_array('assurance', $columns)) {
                    $this->addIndexIfNotExists($table, 'assurance', 'clients_assurance_index');
                }
            });
        }

        // FACTURE_CLIENTS TABLE
        if (Schema::hasTable('facture_clients')) {
            Schema::table('facture_clients', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'created_at', 'facture_clients_created_at_index');
                $columns = Schema::getColumnListing('facture_clients');
                if (in_array('date_insertion', $columns)) {
                    $this->addIndexIfNotExists($table, 'date_insertion', 'facture_clients_date_insertion_idx');
                }
                if (in_array('client_id', $columns)) {
                    $this->addIndexIfNotExists($table, 'client_id', 'facture_clients_client_id_index');
                }
            });
        }

        // FACTURES TABLE
        if (Schema::hasTable('factures')) {
            Schema::table('factures', function (Blueprint $table) {
                $this->addIndexIfNotExists($table, 'created_at', 'factures_created_at_index');
                $columns = Schema::getColumnListing('factures');
                if (in_array('numero', $columns)) {
                    $this->addIndexIfNotExists($table, 'numero', 'factures_numero_index');
                }
                if (in_array('patient', $columns)) {
                    $this->addIndexIfNotExists($table, 'patient', 'factures_patient_index');
                }
            });
        }

        // Optimize tables (MySQL)
        if (DB::connection()->getDriverName() === 'mysql') {
            $tables = ['patients', 'consultations', 'facture_consultations', 'historique_factures', 
                      'events', 'examens', 'ordonances', 'users', 'dossiers', 'chambres', 'produits'];
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::statement("OPTIMIZE TABLE {$table}");
                }
            }
        }
    }

    public function down(): void
    {
        // Drop indexes in reverse order
        $indexesToDrop = [
            'patients' => ['patients_created_at_index', 'patients_date_insertion_index', 'patients_assurance_index', 
                          'patients_motif_index', 'patients_user_id_index', 'patients_date_assurance_idx'],
            'consultations' => ['consultations_date_consultation_index', 'consultations_date_intervention_index',
                               'consultations_date_anesthesiste_idx', 'consultations_created_at_index',
                               'consultations_medecin_r_index', 'consultations_type_intervention_idx',
                               'consultations_patient_date_idx'],
            'facture_consultations' => ['facture_consultations_created_at_idx', 'facture_consultations_date_insertion_idx',
                                       'facture_consultations_statut_index', 'facture_consultations_deleted_at_idx',
                                       'facture_consultations_motif_index', 'facture_consultations_assurance_idx',
                                       'facture_consultations_date_statut_idx', 'facture_consultations_patient_date_idx'],
            // Add more as needed
        ];

        foreach ($indexesToDrop as $table => $indexes) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table) use ($indexes) {
                    foreach ($indexes as $index) {
                        if ($this->indexExists($table->getTable(), $index)) {
                            $table->dropIndex($index);
                        }
                    }
                });
            }
        }
    }

    private function addIndexIfNotExists($table, string $column, string $indexName): void
    {
        if (!$this->indexExists($table->getTable(), $indexName)) {
            $table->index($column, $indexName);
        }
    }

    private function addCompositeIndexIfNotExists($table, array $columns, string $indexName): void
    {
        if (!$this->indexExists($table->getTable(), $indexName)) {
            $table->index($columns, $indexName);
        }
    }

    private function indexExists(string $table, string $index): bool
    {
        try {
            $connection = Schema::getConnection();
            /*
             $schemaManager = $connection->getDoctrineSchemaManager();
-            $indexes = $schemaManager->listTableIndexes($table);
-            return isset($indexes[$index]);
            */
            if ($connection->getDriverName() === 'sqlite') {
                // For SQLite, check using PRAGMA index_list
                $indexes = $connection->select("PRAGMA index_list({$table})");
                foreach ($indexes as $idx) {
                    if ($idx->name === $index) {
                        return true;
                    }
                }
                return false;
            } else {
                $schemaManager = $connection->getDoctrineSchemaManager();
                $indexes = $schemaManager->listTableIndexes($table);
                return isset($indexes[$index]);
            }
        } catch (\Exception $e) {
            return false;
        }
    }
};
