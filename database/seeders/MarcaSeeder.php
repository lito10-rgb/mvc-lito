<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marca;

class MarcaSeeder extends Seeder
{
    public function run()
    {
        Marca::factory()->count(10)->create();  // Crea 10 marcas de prueba
    }
}
