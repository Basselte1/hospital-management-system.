<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facture_consultations', function (Blueprint $table) {
            // From 2019_12_23_160247 (except text 'statut' – we keep only enum later)
            if (!Schema::hasColumn('facture_consultations', 'details_motif')) {
                $table->text('details_motif')->nullable();
            }
            if (!Schema::hasColumn('facture_consultations', 'deleted_at')) {
                $table->softDeletes();
            }

            // From 2020_02_05_110201
            if (!Schema::hasColumn('facture_consultations', 'mode_paiement')) {
                $table->string('mode_paiement')->default('espèce');
            }

            // From 2020_02_18_180353
            if (!Schema::hasColumn('facture_consultations', 'mode_paiement_info_sup')) {
                $table->string('mode_paiement_info_sup')->nullable();
            }

            // From 2026_02_16_114936 (enum statut, print tracking)
            if (!Schema::hasColumn('facture_consultations', 'statut')) {
                $table->enum('statut', ['Soldée', 'Non soldée'])->default('Non soldée')->after('reste');
            }
            if (!Schema::hasColumn('facture_consultations', 'is_printed')) {
                $table->boolean('is_printed')->default(false)->after('statut');
            }
            if (!Schema::hasColumn('facture_consultations', 'printed_at')) {
                $table->timestamp('printed_at')->nullable()->after('is_printed');
            }
            if (!Schema::hasColumn('facture_consultations', 'printed_by')) {
                $table->unsignedBigInteger('printed_by')->nullable()->after('printed_at');
                $table->foreign('printed_by')->references('id')->on('users')->onDelete('set null');
            }

            // From 2026_03_04_110010
            if (!Schema::hasColumn('facture_consultations', 'patient_name')) {
                $table->string('patient_name')->nullable()->after('patient_id');
            }
        });

        // Backfill: update statut based on 'reste'
        DB::statement("
            UPDATE facture_consultations
            SET statut = CASE WHEN reste = 0 THEN 'Soldée' ELSE 'Non soldée' END
            WHERE statut IS NULL
        ");

        // Backfill: copy patient name from patients table
        DB::statement("
            UPDATE facture_consultations
            SET patient_name = (
                SELECT name FROM patients WHERE patients.id = facture_consultations.patient_id LIMIT 1
            )
            WHERE patient_name IS NULL
        ");

        // Backfill: placeholder for missing patients
        DB::statement("
            UPDATE facture_consultations
            SET patient_name = '[Patient supprimé]'
            WHERE patient_name IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('facture_consultations', function (Blueprint $table) {
            $table->dropForeign(['printed_by']);
            $table->dropColumn([
                'details_motif',
                'deleted_at',
                'mode_paiement',
                'mode_paiement_info_sup',
                'statut',
                'is_printed',
                'printed_at',
                'printed_by',
                'patient_name',
            ]);
        });
    }
};