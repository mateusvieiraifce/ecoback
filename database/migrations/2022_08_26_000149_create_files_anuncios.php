<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesAnuncios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_anuncios', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->integer('anuncio_id');
            $table->timestamps();
            $table->foreign('anuncio_id')->references('id')->on('anuncios');//
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files_anuncios');
    }
}
