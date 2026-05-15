<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();

            // numero_dossier as string from the start (merged from later migration)
            $table->string('numero_dossier', 20)->nullable()->index();

            // Patient basic info
            $table->string('name');
            $table->string('prenom')->nullable();

            // Insurance info
            $table->string('assurance')->nullable();
            $table->string('numero_assurance')->nullable();
            $table->string('prise_en_charge')->nullable();
            $table->integer('assurancec')->nullable()->default(0);
            $table->integer('assurec')->nullable()->default(0);

            // Financial tracking
            $table->integer('montant')->nullable()->default(0);
            $table->integer('avance')->nullable()->default(0);
            $table->integer('reste')->nullable()->default(0);

            // Consultation details
            $table->string('motif')->nullable();
            $table->text('details_motif')->nullable();

            // Referral info
            $table->string('demarcheur')->nullable();
            $table->integer('medecin_r')->nullable();

            // Administrative
            $table->date('date_insertion')->nullable();

            // Payment method fields
            $table->string('mode_paiement')->nullable()->default('espèce');
            $table->string('mode_paiement_info_sup')->nullable();

            $table->timestamps();

            // Soft deletes (merged from add_soft_deletes_to_patients_table)
            $table->softDeletes();

            // Indexes
            $table->index('name');
            $table->index('medecin_r');
            $table->index('date_insertion');
            $table->index('created_at');

            // Foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('patients');
    }
}