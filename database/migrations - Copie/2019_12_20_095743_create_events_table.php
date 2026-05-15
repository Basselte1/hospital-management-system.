<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * COMPLETE VERSION - Includes both legacy and new datetime fields:
     * - Legacy: date, start_time, end_time (for backward compatibility)
     * - New: start, end (datetime fields used by controllers)
     * 
     * This ensures no NOT NULL constraint violations during data transfer
     * and maintains compatibility with both old and new code.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            
            // Legacy date/time fields (kept for backward compatibility)
            // IMPORTANT: Made NULLABLE to prevent constraint violations
            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            
            // New datetime fields (used by current controllers)
            $table->datetime('start')->nullable();
            $table->datetime('end')->nullable();
            
            // Event details
            $table->string('statut')->nullable();
            $table->string('objet')->nullable();
            $table->string('description')->nullable();
            $table->string('state')->nullable();
            
            $table->timestamps();
            
            // Relationships
            $table->integer('user_id')->nullable()->index();
            $table->integer('patient_id')->nullable()->index();
            
            // Indexes for calendar queries
            $table->index('date');
            $table->index('start');
            $table->index('end');
            $table->index(['user_id', 'start']);
            $table->index(['patient_id', 'start']);
            $table->index('statut');
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}