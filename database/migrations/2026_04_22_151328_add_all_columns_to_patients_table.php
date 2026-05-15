<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
              // Pas de ->after() — SQLite ne le supporte pas
            if (!Schema::hasColumn('patients', 'devise')) {
                $table->string('devise', 10)->default('XAF');
            }
            if (!Schema::hasColumn('patients', 'taux_conversion')) {
                $table->decimal('taux_conversion', 12, 4)->nullable();
            }
            if (!Schema::hasColumn('patients', 'montant_devise')) {
                $table->decimal('montant_devise', 12, 2)->nullable();
            }
            if(!Schema::hasColumn('patients', 'infirmier')){
                $table->string('infirmier')->nullable();
            }
            if (!Schema::hasColumn('patients', 'deleted_at')) {
                $table->softDeletes(); // adds nullable deleted_at timestamp column
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            //
            $table->dropColum(['devise','taux_conversion','montant_devise','infirmier']);
            $table->dropSoftDeletes();
        });
    }
};
