<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChambresTable extends Migration
{
    public function up()
    {
        Schema::create('chambres', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->integer('user_id');
            $table->string('numero');
            $table->string('categorie');
            $table->string('patient')->nullable()->default('Vide');
            $table->integer('prix')->nullable();
            $table->integer('jour')->nullable();
            $table->string('statut')->default('libre');
            $table->timestamps();
        });

        // Foreign key (previously in AddForeignKeysToChambresTable)
        Schema::table('chambres', function (Blueprint $table) {
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::table('chambres', function (Blueprint $table) {
            $table->dropForeign('chambres_user_id_foreign');
        });
        Schema::dropIfExists('chambres');
    }
}