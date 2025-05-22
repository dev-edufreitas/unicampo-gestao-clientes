<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfissaosTable extends Migration
{
    public function up()
    {
        Schema::create('profissoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome_profissao');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('profissoes');
    }
}