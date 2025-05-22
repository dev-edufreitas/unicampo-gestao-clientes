<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Endereco;
use Faker\Factory as Faker;

class EnderecoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('pt_BR');
        
        for ($i = 0; $i < 20; $i++) {
            Endereco::create([
                'endereco'    => $faker->streetName,
                'numero'      => $faker->buildingNumber,
                'bairro'      => $faker->cityPrefix . ' ' . $faker->lastName,
                'complemento' => $faker->boolean(30) ? $faker->secondaryAddress : null,
                'cidade'      => $faker->city,
                'uf'          => $faker->stateAbbr,
            ]);
        }
    }
}