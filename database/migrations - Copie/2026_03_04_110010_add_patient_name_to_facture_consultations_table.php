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
            if (!Schema::hasColumn('facture_consultations', 'patient_name')) {
                $table->string('patient_name')->nullable()->after('patient_id');
            }
        });

        // Back-fill existing rows where the patient still exists (incl. soft-deleted)
        DB::statement("
            UPDATE facture_consultations
            SET patient_name = (
                SELECT name
                FROM patients
                WHERE patients.id = facture_consultations.patient_id
                LIMIT 1
            )
            WHERE patient_name IS NULL
        ");

        // For rows whose patient is gone, leave a clear placeholder
        DB::statement("
            UPDATE facture_consultations
            SET patient_name = '[Patient supprimé]'
            WHERE patient_name IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('facture_consultations', function (Blueprint $table) {
            $table->dropColumn('patient_name');
        });
    }
};