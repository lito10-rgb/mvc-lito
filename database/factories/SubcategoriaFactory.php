<?php

// namespace Database\Factories;

// use App\Models\Subcategoria;
// use App\Models\Categoria;
// use Illuminate\Database\Eloquent\Factories\Factory;

// class SubcategoriaFactory extends Factory
// {
//     protected $model = Subcategoria::class;

//     public function definition()
//     {
//         return [
//             'subcategoria' => $this->faker->word,
//             'id_categoria' => Categoria::factory(),
//             'ruta' => $this->faker->slug,
//             'estado' => 1,
//             'ofertadoPorCategoria' => 0,
//             'oferta' => rand(0, 1),
//             'precioOferta' => $this->faker->randomFloat(2, 10, 200),
//             'descuentoOferta' => rand(5, 50),
//             'imgOferta' => $this->faker->imageUrl(),
//             'finOferta' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
//             'detalle' => $this->faker->paragraph,
//         ];
//     }
// }
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SubcategoriaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'subcategoria' => $this->faker->words(2, true),
            'id_categoria' => $this->faker->numberBetween(1, 10), // Asegúrate que existan categorías con estos IDs
            'ruta' => $this->faker->slug(),
            'estado' => $this->faker->randomElement(['1', '0']),
            'ofertadoPorCategoria' => $this->faker->randomElement(['1', '0']),
            'oferta' => $this->faker->randomElement(['0', '1']),
            'precioOferta' => $this->faker->randomFloat(2, 10, 100),
            'descuentoOferta' => $this->faker->numberBetween(5, 50),
            'imgOferta' => $this->faker->imageUrl(640, 480, 'products', true, 'Oferta Subcat'),
            'finOferta' => $this->faker->dateTimeBetween('+1 week', '+1 month'),
            'fecha' => $this->faker->date(),
            'detalle' => $this->faker->sentence(10),
        ];
    }
}


