<?php

namespace Database\Factories;

use App\Models\Endereco;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnderecoFactory extends Factory
{
    protected $model = Endereco::class;

    public function definition(): array
    {
        return [
            'endereco'    => $this->faker->streetName(),
            'numero'      => $this->faker->buildingNumber(),
            'bairro'      => $this->faker->citySuffix(),
            'complemento' => $this->faker->boolean(30) ? $this->faker->secondaryAddress() : null,
            'cidade'      => $this->faker->city(),
            'uf'          => $this->faker->stateAbbr(),
        ];
    }
}