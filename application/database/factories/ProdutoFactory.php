<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProdutoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'ean' => fake()->ean13(),
            'preco' => fake()->randomFloat(2, 10, 1000),
            'estoque' => fake()->numberBetween(0, 100),
        ];
    }
}
