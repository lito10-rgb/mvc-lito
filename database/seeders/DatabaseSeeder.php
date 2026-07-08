<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
public function run()
{
        $this->call([
            AdministradorSeeder::class,
            CategoriaSeeder::class,
            EximInitialDataSeeder::class,
            // SubcategoriaSeeder::class,
            // MarcaSeeder::class,        
            // ProveedorSeeder::class,
            // ProductoSeeder::class,
        ]);
    //$this->call(AdministradorSeeder::class);
 }
}



