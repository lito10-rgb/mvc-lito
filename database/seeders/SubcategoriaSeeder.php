<?php

// namespace Database\Seeders;

// use Illuminate\Database\Seeder;
// use App\Models\Subcategoria;

// class SubcategoriaSeeder extends Seeder
// {
//     public function run()
//     {
//         Subcategoria::factory()->count(5)->create();
//     }
// }
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subcategoria;

class SubcategoriaSeeder extends Seeder
{
    public function run(): void
    {
        Subcategoria::factory()->count(10)->create();
    }
}
