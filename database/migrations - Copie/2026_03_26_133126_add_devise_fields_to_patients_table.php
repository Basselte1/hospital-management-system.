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
            $table->string('devise', 10)->default('XAF')->after('montant');
            $table->decimal('taux_conversion',10, 2)->nullable()->after('devise');
            $table->decimal('montant_devise',12,2)->nullable()->after('taux_conversion');
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColum(['devise','taux_conversion','montant_devise']);
            //
        });
    }
};
