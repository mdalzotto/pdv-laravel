<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Produto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Cliente::factory()->count(5)->create();
        Produto::factory()->count(5)->create();
    }
}
