<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition()
    {
        // Creamos una subcategoría para asociarla a cada categoría
        $subcategoria = Subcategoria::inRandomOrder()->first(); // Obtener una subcategoría aleatoria

        return [
            'categoria' => $this->faker->word,
            'ruta' => $this->faker->slug,
            'estado' => 1,
            'oferta' => rand(0, 1),
            'precioOferta' => $this->faker->randomFloat(2, 10, 200),
            'descuentoOferta' => rand(5, 50),
            'imgOferta' => $this->faker->imageUrl(),
            'finOferta' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'fecha' => $this->faker->date(),
            // 'id_subcategoria' => Subcategoria::factory(),
          ];
    }
}
