<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Endereco;
use App\Models\Profissao;
use Faker\Factory as Faker;

class ClienteSeeder extends Seeder
{
    public function run()
    {
        $faker      = Faker::create('pt_BR');
        $enderecos  = Endereco::all();
        $profissoes = Profissao::all();
        
        for ($i = 0; $i < 10; $i++) {
            $tipoPessoa = $faker->randomElement(['fisica', 'juridica']);
            $documento  = $tipoPessoa === 'fisica' ? $faker->cpf : $faker->cnpj;
            
            Cliente::create([
                'nome'            => $faker->name,
                'data_nascimento' => $faker->date('Y-m-d', '-18 years'),
                'tipo_pessoa'     => $tipoPessoa,
                'documento'       => $documento,
                'email'           => $faker->unique()->safeEmail,
                'telefone'        => $faker->phoneNumber,
                'id_endereco'     => $enderecos->random()->id,
                'id_profissao'    => $profissoes->random()->id,
                'status'          => $faker->randomElement(['ativo', 'inativo']),
            ]);
        }
    }
}