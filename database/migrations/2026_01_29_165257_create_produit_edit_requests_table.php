<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduitEditRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produit_edit_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('requested_by')->constrained('users');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->foreignId('revoked_by')->nullable()->constrained('users');
            
            // Reason for requesting edit permission
            $table->text('reason');
            
            // Permission status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            // Can the user edit? (set to true when approved, false when revoked)
            $table->boolean('can_edit')->default(false);
            
            // Review information
            $table->text('review_comment')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index(['produit_id', 'status']);
            $table->index('requested_by');
            $table->index('reviewed_by');
            $table->index('status');
            $table->index(['requested_by', 'can_edit']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produit_edit_requests');
    }
}