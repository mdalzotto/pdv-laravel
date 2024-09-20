<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteFactory extends Factory
{
    public function definition(): array
    {
//        nome', 'ean', 'preco', 'estoque
        return [
            'name' => fake()->name(),
        ];
    }
}
