<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDevisTableForValidation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->integer('patient_id')->nullable()->after('user_id');
            $table->integer('medecin_id')->nullable()->after('patient_id'); // Doctor assigned
            $table->enum('statut', ['brouillon', 'en_attente', 'valide', 'refuse'])->default('brouillon')->after('acces');
            $table->integer('pourcentage_reduction')->default(0)->after('statut'); // Reduction percentage
            $table->integer('montant_avant_reduction')->default(0)->after('pourcentage_reduction');
            $table->integer('montant_apres_reduction')->default(0)->after('montant_avant_reduction');
            $table->timestamp('date_validation')->nullable()->after('montant_apres_reduction');
            $table->integer('validateur_id')->nullable()->after('date_validation'); // Doctor who validated
            $table->text('commentaire_medecin')->nullable()->after('validateur_id');
            
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('medecin_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('validateur_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devis', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['medecin_id']);
            $table->dropForeign(['validateur_id']);
            
            $table->dropColumn([
                'patient_id',
                'medecin_id',
                'statut',
                'pourcentage_reduction',
                'montant_avant_reduction',
                'montant_apres_reduction',
                'date_validation',
                'validateur_id',
                'commentaire_medecin'
            ]);
        });
    }
}