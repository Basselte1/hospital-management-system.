<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamensTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * COMPLETE VERSION - Uses 'nom' instead of 'type' to match the model:
     * - Column 'nom' matches Examen.php fillable array
     * - Includes 'description' field (added in separate migration originally)
     * - All fields nullable to prevent transfer errors
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->index();
            
            // IMPORTANT: Use 'nom' not 'type' (matches model fillable)
            $table->string('nom')->nullable();
            
            // Image path
            $table->string('image')->nullable();
            
            // Description field (added in migration 2019_12_30_153343 originally)
            $table->text('description')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('created_at');
            
            // Foreign key
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
        Schema::dropIfExists('examens');
    }
}