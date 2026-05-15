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
            if (!Schema::hasColumn('historique_factures', 'facture_examen_id')) {
                $table->integer('facture_examen_id')->nullable();
                $table->foreign('facture_examen_id')
                  ->references('id')->on('factures_examen')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
            }

            if (!Schema::hasColumn('historique_factures', 'facture_acte_id')) {
                $table->integer('facture_acte_id')->nullable();
                $table->foreign('facture_acte_id')
                    ->references('id')->on('factures_acte')
                    ->onUpdate('CASCADE')->onDelete('CASCADE');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historique_factures', function (Blueprint $table) {
            if (Schema::hasColumn('historique_factures', 'facture_examen_id')) {
                $table->dropForeign(['facture_examen_id']);
                $table->dropColumn('facture_examen_id');
            }

            if (Schema::hasColumn('historique_factures', 'facture_acte_id')) {
                $table->dropForeign(['facture_acte_id']);
                $table->dropColumn('facture_acte_id');
            }
        });
    }
};
