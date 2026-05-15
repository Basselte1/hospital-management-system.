<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePatientVisitsTable extends Migration
{
    public function up()
    {
        Schema::create('patient_visits', function (Blueprint $table) {
            $table->increments('id');

            // Lien avec le patient (FIXE - ne change jamais)
            $table->unsignedInteger('patient_id')->index();

            // Snapshot columns (merged from add_patient_snapshot_to_patient_visits_table)
            $table->string('patient_name')->nullable()->after('patient_id');
            $table->string('patient_numero_dossier')->nullable()->after('patient_name');

            // Utilisateur qui a enregistré cette visite
            $table->unsignedInteger('user_id')->index();

            // Date de la visite
            $table->date('visit_date')->index();

            // Motif médical de la visite
            $table->string('motif')->nullable();
            $table->text('details_motif')->nullable();

            // Informations financières
            $table->integer('montant')->nullable()->default(0);
            $table->integer('avance')->nullable()->default(0);
            $table->integer('reste')->nullable()->default(0);

            // Informations d'assurance
            $table->string('assurance')->nullable();
            $table->string('numero_assurance')->nullable();
            $table->string('prise_en_charge')->nullable();
            $table->integer('assurancec')->nullable()->default(0);
            $table->integer('assurec')->nullable()->default(0);

            // Médecin traitant pour cette visite
            $table->string('medecin_r')->nullable()->index();

            // Mode de paiement
            $table->string('mode_paiement')->nullable()->default('espèce');
            $table->string('mode_paiement_info_sup')->nullable();

            // Démarcheur
            $table->string('demarcheur')->nullable();

            // Statut de la visite
            $table->enum('status', ['en_attente', 'en_cours', 'terminee', 'annulee'])
                ->default('en_attente');

            // Notes supplémentaires
            $table->text('notes')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('patient_id')
                ->references('id')
                ->on('patients')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            // Index composés
            $table->index(['patient_id', 'visit_date']);
            $table->index(['medecin_r', 'visit_date']);
            $table->index(['status', 'visit_date']);
        });

        // Back-fill snapshot columns from patients table (merged logic)
        DB::statement("
            UPDATE patient_visits
            SET patient_name = (
                SELECT name
                FROM patients
                WHERE patients.id = patient_visits.patient_id
                LIMIT 1
            )
            WHERE patient_name IS NULL
        ");

        DB::statement("
            UPDATE patient_visits
            SET patient_numero_dossier = (
                SELECT CAST(numero_dossier AS TEXT)
                FROM patients
                WHERE patients.id = patient_visits.patient_id
                LIMIT 1
            )
            WHERE patient_numero_dossier IS NULL
        ");

        DB::statement("
            UPDATE patient_visits
            SET patient_name = '[Patient supprimé]'
            WHERE patient_name IS NULL
        ");
    }

    public function down()
    {
        Schema::dropIfExists('patient_visits');
    }
}