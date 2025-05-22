<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->date('data_nascimento');
            $table->enum('tipo_pessoa', ['fisica', 'juridica']);
            $table->string('documento'); 
            $table->string('email')->unique();
            $table->string('telefone');
            $table->foreignId('id_endereco')->constrained('enderecos');
            $table->foreignId('id_profissao')->constrained('profissoes');
            $table->enum('status', ['ativo', 'inativo'])->default('ativo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}