<?php

namespace Database\Factories;

use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProveedorFactory extends Factory
{
    protected $model = Proveedor::class;

    public function definition()
    {
        return [
            //  'barraSuperior' => $this->faker->sentence,
            // 'textoSuperior' => $this->faker->sentence,
            // 'colorFondo' => $this->faker->hexColor,
            // 'colorTexto' => $this->faker->hexColor,
            // 'logo' => $this->faker->imageUrl(),
            // 'icono' => $this->faker->imageUrl(),
            // 'redesSociales' => json_encode(['facebook' => $this->faker->url]),
            // 'apiFacebook' => $this->faker->md5,
            // 'pixelFacebook' => $this->faker->md5,
            // 'googleAnalytics' => $this->faker->md5,
             'nombre' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'telefono' => $this->faker->phoneNumber,
            'direccion' => $this->faker->address,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
