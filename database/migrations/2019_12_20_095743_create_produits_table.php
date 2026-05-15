<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduitsTable extends Migration
{
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->string('designation')->unique();
            $table->string('categorie');
            $table->integer('qte_stock')->default(0);
            $table->integer('qte_alerte');
            $table->integer('prix_unitaire');
            $table->integer('user_id')->nullable();
            $table->timestamps();

            // Reusable fields (merged from AddReusableFieldsToProduitsTable)
            $table->boolean('is_reusable')->default(false)->after('prix_unitaire');
            $table->integer('nombre_utilisations_max')->nullable()->after('is_reusable');
            $table->text('notes_utilisation')->nullable()->after('nombre_utilisations_max');

            $table->enum('methode_sterilisation_recommandee', [
                'autoclave',
                'chaleur_seche',
                'gaz_eto',
                'plasma',
                'chimique',
                'autre',
                'non_applicable'
            ])->nullable()->after('notes_utilisation');

            $table->integer('duree_sterilisation_recommandee')->nullable()->after('methode_sterilisation_recommandee');
            $table->integer('temperature_sterilisation')->nullable()->after('duree_sterilisation_recommandee');

            $table->integer('qte_en_utilisation')->default(0)->after('qte_stock');
            $table->integer('qte_en_sterilisation')->default(0)->after('qte_en_utilisation');

            // Removed virtualAs column – qte_disponible will be computed in the model

            $table->index('is_reusable');
        });
    }

    public function down()
    {
        Schema::dropIfExists('produits');
    }
}