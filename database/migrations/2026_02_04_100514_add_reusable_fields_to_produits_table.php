<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReusableFieldsToProduitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produits', function (Blueprint $table) {
            // Reusability tracking
            if (!Schema::hasColumn('produits', 'is_reusable')) {
                $table->boolean('is_reusable')->default(false)->after('prix_unitaire');
            }
            if (!Schema::hasColumn('produits', 'nombre_utilisations_max')) {
                $table->integer('nombre_utilisations_max')->nullable()->after('is_reusable'); // Max times reusable
            }  
            if (!Schema::hasColumn('produits', 'notes_utilisation')) {
                $table->text('notes_utilisation')->nullable()->after('nombre_utilisations_max');
            }
            
            // Sterilization requirements
            if (!Schema::hasColumn('produits', 'methode_sterilisation_recommandee')) {
                $table->enum('methode_sterilisation_recommandee', [
                    'autoclave',
                    'chaleur_seche',
                    'gaz_eto',
                    'plasma',
                    'chimique',
                    'autre',
                    'non_applicable'
                ])->nullable()->after('notes_utilisation');
            }
            
            if (!Schema::hasColumn('produits', 'duree_sterilisation_recommandee')) {
                $table->integer('duree_sterilisation_recommandee')->nullable()->after('methode_sterilisation_recommandee'); // In minutes
                }
            
            if (!Schema::hasColumn('produits', 'temperature_sterilisation')) {
                $table->integer('temperature_sterilisation')->nullable()->after('duree_sterilisation_recommandee'); // In Celsius
                }
            
            // Current status tracking
             if (!Schema::hasColumn('produits', 'qte_en_utilisation')) {
                $table->integer('qte_en_utilisation')->default(0)->after('qte_stock'); // Currently in use
             }

            if (!Schema::hasColumn('produits', 'qte_en_sterilisation')) {
                $table->integer('qte_en_sterilisation')->default(0)->after('qte_en_utilisation'); // Being sterilized
            }

            if (!Schema::hasColumn('produits', 'qte_disponible')) {
                $table->integer('qte_disponible')->virtualAs('qte_stock - qte_en_utilisation - qte_en_sterilisation'); // Available
            
            }
            // Index for quick filtering (safe creation)
            if (!Schema::hasIndex('produits', 'produits_is_reusable_index')) {
                $table->index('is_reusable');
            }
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn([
                'is_reusable',
                'nombre_utilisations_max',
                'notes_utilisation',
                'methode_sterilisation_recommandee',
                'duree_sterilisation_recommandee',
                'temperature_sterilisation',
                'qte_en_utilisation',
                'qte_en_sterilisation',
                'qte_disponible'
            ]);
            $table->dropIndex('produits_is_reusable_index');
        });
    }
}