<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\Endereco;
use App\Models\Profissao;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition(): array
    {
        $tipoPessoa = $this->faker->randomElement(['fisica', 'juridica']);
        $documento = $tipoPessoa === 'fisica' 
            ? $this->faker->cpf(false) 
            : $this->faker->cnpj(false);

        return [
            'nome'            => $this->faker->name(),
            'data_nascimento' => $this->faker->date('Y-m-d', '-18 years'),
            'tipo_pessoa'     => $tipoPessoa,
            'documento'       => $documento,
            'email'           => $this->faker->unique()->safeEmail(),
            'telefone'        => $this->faker->phoneNumber(),
            'id_endereco'     => Endereco::factory(),
            'id_profissao'    => Profissao::factory(),
            'status'          => $this->faker->randomElement(['ativo', 'inativo']),
        ];
    }
}