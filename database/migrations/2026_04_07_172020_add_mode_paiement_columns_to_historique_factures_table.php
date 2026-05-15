<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historique_factures', function (Blueprint $table) {
            // From 2020_02_08_125355
            if (!Schema::hasColumn('historique_factures', 'mode_paiement')) {
                $table->string('mode_paiement')->default('espèce');
            }

            // From 2020_02_19_101817
            if (!Schema::hasColumn('historique_factures', 'mode_paiement_info_sup')) {
                $table->string('mode_paiement_info_sup')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('historique_factures', function (Blueprint $table) {
            $table->dropColumn(['mode_paiement', 'mode_paiement_info_sup']);
        });
    }
};