<?php

// namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Seeder;

// class AdministradorSeeder extends Seeder
// {
//     /**
//      * Run the database seeds.
//      */
//     public function run(): void
//     {
//         //
//     }
// }


namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Administrador;

class AdministradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 10 administradores falsos con la factory
        Administrador::factory()->count(10)->create();

        // Crear un administrador específico
        Administrador::create([
            'nombre' => 'Admin Principal',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'), // También puedes usar Hash::make('admin123')
        ]);
    }
}
