<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnuncios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_adv', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->timestamps();
        });

        Schema::create('anuncios', function (Blueprint $table) {
            $table->id();
            $table->uuid('id_anuncio');
            $table->string('titulo');
            $table->string('descricao');
            $table->double('preco');
            $table->double('quantidade');
            $table->boolean('ativo');
            $table->boolean('destaque');
            $table->integer('user_id');
            $table->integer('type_id');
            $table->timestamps();
            $table->double('altura');
            $table->double('largura');
            $table->double('peso');
            $table->foreign('type_id')->references('id')->on('type_adv');//
            $table->foreign('user_id')->references('id')->on('users');//
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anuncios');
        Schema::dropIfExists('type_adv');
    }
}
