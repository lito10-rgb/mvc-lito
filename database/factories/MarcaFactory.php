<?php

namespace Database\Factories;

use App\Models\Marca;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarcaFactory extends Factory
{
    protected $model = Marca::class;

    public function definition()
    {
        
        return [
            // 'nombre' => $this->faker->company,
            // 'descripcion' => $this->faker->sentence,
            // 'detalle' => $this->faker->paragraph,
            // 'fecha' => $this->faker->date(),
            // 'estado' => $this->faker->randomElement([1, 0]),
            // 'imgMarca' => $this->faker->imageUrl(640, 480, 'technics'),
            'nombre' => $this->faker->company,
            'ruta' => $this->faker->slug,
            'descripcion' => $this->faker->sentence,
            'detalle' => $this->faker->paragraph,
            'fecha' => $this->faker->date(),
            'estado' => $this->faker->randomElement([1, 0]),
            'imgMarca' => $this->faker->imageUrl(640, 480, 'brands', true, 'Marca'),
        ];

    }
}
