<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamensLaboratoireTable extends Migration
{
    public function up()
    {
        Schema::create('examens_laboratoire', function (Blueprint $table) {
            $table->increments('id');

            // Identity
            $table->unsignedInteger('patient_id')->index();
            $table->unsignedInteger('user_id')->index();
            $table->unsignedInteger('prescripteur_id')->nullable()->index();
            $table->string('numero_bon')->nullable()->index();

            // Phase 1 – Pré-analytique
            $table->string('prescription_source')->nullable();
            $table->date('date_prescription')->nullable();
            $table->text('preparation_requise')->nullable();
            $table->dateTime('date_prelevement')->nullable();
            $table->string('heure_prelevement')->nullable();
            $table->string('technicien_prelevement')->nullable();
            $table->string('tube_type')->nullable();
            $table->string('site_prelevement')->nullable();
            $table->string('statut_specimen')->default('accepté');
            $table->text('motif_rejet')->nullable();

            // Phase 2 – Analytique (test names + JSON results per discipline)
            $table->string('hematologie')->nullable();
            $table->json('hematologie_resultats')->nullable();
            $table->string('hemostase')->nullable();
            $table->json('hemostase_resultats')->nullable();
            $table->string('biochimie')->nullable();
            $table->json('biochimie_resultats')->nullable();
            $table->string('hormonologie')->nullable();
            $table->json('hormonologie_resultats')->nullable();
            $table->string('marqueurs')->nullable();
            $table->json('marqueurs_resultats')->nullable();
            $table->string('bacteriologie')->nullable();
            $table->json('bacteriologie_resultats')->nullable();
            $table->text('antibiogramme')->nullable();
            $table->string('spermiologie')->nullable();
            $table->json('spermiologie_resultats')->nullable();
            $table->string('urines')->nullable();
            $table->json('urines_resultats')->nullable();
            $table->string('serologie')->nullable();
            $table->json('serologie_resultats')->nullable();
            $table->string('parasitologie')->nullable();
            $table->json('parasitologie_resultats')->nullable();
            $table->string('instrument_utilise')->nullable();
            $table->string('lot_reactif')->nullable();
            $table->string('cqi_status')->default('non_effectue');
            $table->text('cqi_note')->nullable();

            // Phase 3 – Post-analytique
            $table->text('observations')->nullable();
            $table->json('valeurs_critiques')->nullable();
            $table->string('clinicien_notifie')->nullable();
            $table->dateTime('date_notification')->nullable();
            $table->string('valide_par')->nullable();
            $table->dateTime('date_validation')->nullable();
            $table->dateTime('date_remise_resultat')->nullable();

            $table->string('statut')->default('en_attente');

            // Merged from 2026_04_07 – payment fields
            $table->decimal('montant_paye', 12, 0)->default(0)
                ->comment('Montant encaissé en FCFA lors de la création du bon');
            $table->string('mode_paiement', 50)->default('espèces')
                ->comment('espèces | mobile_money | carte | cheque | virement | assurance');
            $table->string('reference_paiement', 100)->nullable()
                ->comment('N° reçu ou référence transaction facultatif');
            $table->boolean('paiement_confirme')->default(false)
                ->comment('TRUE = secrétaire a confirmé avoir encaissé le paiement');

            $table->timestamps();

            // Indexes
            $table->index('statut');
            $table->index('created_at');
            $table->index(['patient_id', 'statut']);
            $table->index(['user_id', 'created_at']);

            // Foreign keys
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('prescripteur_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('examens_laboratoire');
    }
}