<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Compatible SQLite et MySQL
     */
    public function up(): void
    {
        // Vérifier si la colonne n'existe pas déjà
        if (!Schema::hasColumn('consultations', 'is_initial')) {
            Schema::table('consultations', function (Blueprint $table) {
                // SQLite supporte boolean qui sera converti en TINYINT
                $table->boolean('is_initial')
                    ->default(false)
                    ->after('type_intervention');
                
                // L'index fonctionne sur SQLite
                $table->index('is_initial');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('consultations', 'is_initial')) {
            Schema::table('consultations', function (Blueprint $table) {
                $table->dropIndex(['is_initial']);
                $table->dropColumn('is_initial');
            });
        }
    }
};