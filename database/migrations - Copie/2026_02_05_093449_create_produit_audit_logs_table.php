<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduitAuditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produit_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->nullable()->constrained('produits')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users');
            $table->string('action'); // 'created', 'updated', 'deleted', 'approved', 'rejected'
            $table->text('description');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['produit_id', 'created_at']);
            $table->index('user_id');
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produit_audit_logs');
    }
}