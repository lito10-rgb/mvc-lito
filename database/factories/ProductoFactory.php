<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Marca;
use App\Models\Proveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition()
    {
        return [
            // 'tipo' => $this->faker->word(),
            // 'ruta' => $this->faker->slug(),
            // 'estado' => $this->faker->randomElement([0, 1]), // 0 = inactivo, 1 = activo
            // 'titulo' => $this->faker->sentence(),
            // 'titular' => $this->faker->name(),
            // 'descripcion' => $this->faker->paragraph(),
            // 'multimedia' => $this->faker->imageUrl(),
            // 'detalles' => $this->faker->paragraph(),
            // 'precio' => $this->faker->randomFloat(2, 10, 1000), // Precio entre 10 y 1000
            // 'portada' => $this->faker->imageUrl(),
            // 'vistas' => $this->faker->numberBetween(0, 1000),
            // 'ventas' => $this->faker->numberBetween(0, 500),
            // 'vistasGratis' => $this->faker->numberBetween(0, 1000),
            // 'ventasGratis' => $this->faker->numberBetween(0, 500),
            // 'ofertadoPorCategoria' => $this->faker->randomElement([0, 1]),
            // 'ofertadoPorSubCategoria' => $this->faker->randomElement([0, 1]),
            // 'oferta' => $this->faker->randomElement([0, 1]),
            // 'precioOferta' => $this->faker->randomFloat(2, 5, 500),
            // 'descuentoOferta' => $this->faker->numberBetween(0, 50), // 50% de descuento máximo
            // 'imgOferta' => $this->faker->imageUrl(),
            // 'finOferta' => $this->faker->dateTimeBetween('now', '+1 month'),
            // 'peso' => $this->faker->randomFloat(2, 0.5, 5), // Peso entre 0.5 kg y 5 kg
            // 'entrega' => $this->faker->randomFloat(2, 1, 10), // Tiempo de entrega entre 1 y 10 días

            // // Relaciones con categorías, subcategorías, marcas y proveedores
            // 'categoria_id' => Categoria::inRandomOrder()->first()->id,
            // 'subcategoria_id' => Subcategoria::inRandomOrder()->first()->id,
            // 'marca_id' => Marca::inRandomOrder()->first()->id,
            // 'proveedor_id' => Proveedor::inRandomOrder()->first()->id,
            'tipo' => $this->faker->randomElement(['físico', 'digital']),
            'ruta' => $this->faker->slug(),
            'estado' => $this->faker->randomElement(['1', '0']),
            'titulo' => $this->faker->sentence(3),
            'titular' => $this->faker->name(),
            'descripcion' => $this->faker->paragraph(),
            'multimedia' => json_encode([$this->faker->imageUrl()]),
            'detalles' => $this->faker->paragraphs(2, true),
            'precio' => $this->faker->randomFloat(2, 10, 500),
            'portada' => $this->faker->imageUrl(),
            'vistas' => $this->faker->numberBetween(0, 1000),
            'ventas' => $this->faker->numberBetween(0, 500),
            'vistasGratis' => $this->faker->numberBetween(0, 500),
            'ventasGratis' => $this->faker->numberBetween(0, 100),
            'ofertadoPorCategoria' => $this->faker->boolean(),
            'ofertadoPorSubCategoria' => $this->faker->boolean(),
            'oferta' => $this->faker->boolean(),
            'precioOferta' => $this->faker->randomFloat(2, 5, 300),
            'descuentoOferta' => $this->faker->numberBetween(5, 50),
            'imgOferta' => $this->faker->imageUrl(),
            'finOferta' => $this->faker->dateTimeBetween('now', '+1 month'),
            'peso' => $this->faker->randomFloat(2, 0.1, 10),
            'entrega' => $this->faker->numberBetween(1, 7),
            'categoria_id' => \App\Models\Categoria::factory(),
            'subcategoria_id' => \App\Models\Subcategoria::factory(),
            'marca_id' => \App\Models\Marca::factory(),
            'proveedor_id' => \App\Models\Proveedor::factory(),
            'fecha' => $this->faker->date(),
        ];
    }
}

