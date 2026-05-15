<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Adds a patient_name snapshot column to patient_visits.
     *
     * Same rationale as facture_consultations: if a patient is deleted (even
     * soft-deleted without a matching row), visits must still be displayable.
     * Storing the name at visit time makes each visit record self-contained.
     *
     * After migrating, run this Tinker snippet to back-fill existing rows:
     *
     * SQLite:
     *   DB::statement("UPDATE patient_visits SET patient_name =
     *     (SELECT name FROM patients WHERE patients.id = patient_visits.patient_id LIMIT 1)
     *     WHERE patient_name IS NULL");
     *   DB::statement("UPDATE patient_visits SET patient_name = '[Patient supprimé]'
     *     WHERE patient_name IS NULL");
     *
     * MySQL version replaces LIMIT 1 with nothing (subquery already unique by id).
     */
    public function up(): void
    {
        Schema::table('patient_visits', function (Blueprint $table) {
            if (!Schema::hasColumn('patient_visits', 'patient_name')) {
                $table->string('patient_name')->nullable()->after('patient_id');
            }
            if (!Schema::hasColumn('patient_visits', 'patient_numero_dossier')) {
                $table->string('patient_numero_dossier')->nullable()->after('patient_name');
            }
        });

        // Back-fill name from patients table (includes soft-deleted)
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

        // Back-fill numero_dossier
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

        // Placeholder for truly orphaned rows
        DB::statement("
            UPDATE patient_visits
            SET patient_name = '[Patient supprimé]'
            WHERE patient_name IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('patient_visits', function (Blueprint $table) {
            $table->dropColumn(['patient_name', 'patient_numero_dossier']);
        });
    }
};