<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnderecosTable extends Migration
{
    public function up()
    {
        Schema::create('enderecos', function (Blueprint $table) {
            $table->id();
            $table->string('endereco');
            $table->string('numero');
            $table->string('bairro');
            $table->string('complemento')->nullable();
            $table->string('cidade');
            $table->string('uf', 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('enderecos');
    }
}