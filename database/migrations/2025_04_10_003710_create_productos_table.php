<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            $table->text('tipo');
            $table->text('ruta');
            $table->integer('estado');
            $table->text('titulo');
            $table->text('titular');
            $table->text('descripcion');
            $table->text('multimedia');
            $table->text('detalles');
            $table->float('precio');
            $table->text('portada');
            $table->integer('vistas')->default(0);
            $table->integer('ventas')->default(0);
            $table->integer('vistasGratis')->default(0);
            $table->integer('ventasGratis')->default(0);
            $table->integer('ofertadoPorCategoria')->default(0);
            $table->integer('ofertadoPorSubCategoria')->default(0);
            $table->integer('oferta')->default(0);
            $table->float('precioOferta')->default(0);
            $table->integer('descuentoOferta')->default(0);
            $table->text('imgOferta')->nullable();
            $table->dateTime('finOferta')->nullable();
            $table->float('peso')->nullable();
            $table->float('entrega')->nullable();

            // Relacionadas
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('subcategoria_id');
            $table->unsignedBigInteger('marca_id')->nullable();
            $table->unsignedBigInteger('proveedor_id')->nullable();

            // Relaciones con claves foráneas
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
            $table->foreign('subcategoria_id')->references('id')->on('subcategorias')->onDelete('cascade');
            $table->foreign('marca_id')->references('id')->on('marcas')->onDelete('set null');
            $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('set null');

            // Timestamps
            $table->timestamp('fecha')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void {
        Schema::dropIfExists('productos');
    }
};
