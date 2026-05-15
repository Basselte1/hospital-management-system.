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
        Schema::table('historique_factures', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('historique_factures', 'mode_paiement')) {
                $table->string('mode_paiement')->default('espèce');
            }
             //
            if (!Schema::hasColumn('historique_factures', 'mode_paiement_info_sup')) {
                $table->string('mode_paiement_info_sup')->nullable();
            }
             
            if (!Schema::hasColumn('historique_factures', 'devise')) {
                $table->string('devise', 10)->default('XAF');
            }
            if (!Schema::hasColumn('historique_factures', 'taux_conversion')) {
                $table->decimal('taux_conversion', 12, 4)->nullable();
            }
            if (!Schema::hasColumn('historique_factures', 'montant_devise')) {
                $table->decimal('montant_devise', 12, 2)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historique_factures', function (Blueprint $table) {
            //
            $table->dropColumn([
                'mode_paiement',
                'mode_paiement_info_sup',
                'devise',
                'taux_conversion',
                'montant_devise'
            ]);
        });
    }
};
