<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajoute les colonnes manquantes à facture_devis pour compatibilité
     * avec le trait HasFactureMontants et le lien obligatoire vers devis.
     */
    public function up(): void
    {
        if (! Schema::hasTable('facture_devis')) {
            return;
        }

        Schema::table('facture_devis', function (Blueprint $table) {
            // Lien obligatoire vers le devis source
            if (! Schema::hasColumn('facture_devis', 'devi_id')) {
                $table->integer('devi_id')->unsigned()->nullable()->index()->after('patient_id');
            }

            // Colonnes d'impression (requises par marquerCommeImprimee() du trait)
            if (! Schema::hasColumn('facture_devis', 'is_printed')) {
                $table->boolean('is_printed')->default(false)->after('statut');
            }
            if (! Schema::hasColumn('facture_devis', 'printed_at')) {
                $table->timestamp('printed_at')->nullable()->after('is_printed');
            }
            if (! Schema::hasColumn('facture_devis', 'printed_by')) {
                $table->integer('printed_by')->unsigned()->nullable()->after('printed_at');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('facture_devis')) {
            return;
        }

        Schema::table('facture_devis', function (Blueprint $table) {
            $cols = [];
            if (Schema::hasColumn('facture_devis', 'devi_id')) {
                $cols[] = 'devi_id';
            }
            if (Schema::hasColumn('facture_devis', 'is_printed')) {
                $cols[] = 'is_printed';
            }
            if (Schema::hasColumn('facture_devis', 'printed_at')) {
                $cols[] = 'printed_at';
            }
            if (Schema::hasColumn('facture_devis', 'printed_by')) {
                $cols[] = 'printed_by';
            }
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};

