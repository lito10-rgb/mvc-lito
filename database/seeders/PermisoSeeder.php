<?php

namespace Database\Seeders;

use App\Models\Permiso;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermisoSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [
            ['clave' => 'dashboard.ver', 'etiqueta' => 'Ver Dashboard', 'modulo' => 'dashboard', 'descripcion' => 'Acceso al dashboard principal'],
            ['clave' => 'productos.ver', 'etiqueta' => 'Ver Productos', 'modulo' => 'productos', 'descripcion' => 'Listar y ver productos'],
            ['clave' => 'productos.crear', 'etiqueta' => 'Crear Productos', 'modulo' => 'productos', 'descripcion' => 'Crear nuevos productos'],
            ['clave' => 'productos.editar', 'etiqueta' => 'Editar Productos', 'modulo' => 'productos', 'descripcion' => 'Editar productos existentes'],
            ['clave' => 'productos.eliminar', 'etiqueta' => 'Eliminar Productos', 'modulo' => 'productos', 'descripcion' => 'Eliminar productos'],
            ['clave' => 'ofertas.gestionar', 'etiqueta' => 'Gestionar Ofertas', 'modulo' => 'ofertas', 'descripcion' => 'Administrar ofertas y herencia de descuentos'],
            ['clave' => 'cupones.gestionar', 'etiqueta' => 'Gestionar Cupones', 'modulo' => 'cupones', 'descripcion' => 'Crear y administrar cupones'],
            ['clave' => 'categorias.gestionar', 'etiqueta' => 'Gestionar Categorías', 'modulo' => 'categorias', 'descripcion' => 'CRUD de categorías'],
            ['clave' => 'subcategorias.gestionar', 'etiqueta' => 'Gestionar Subcategorías', 'modulo' => 'categorias', 'descripcion' => 'CRUD de subcategorías'],
            ['clave' => 'marcas.gestionar', 'etiqueta' => 'Gestionar Marcas', 'modulo' => 'marcas', 'descripcion' => 'CRUD de marcas'],
            ['clave' => 'catalogos.ver', 'etiqueta' => 'Ver Catálogo PDF', 'modulo' => 'catalogos', 'descripcion' => 'Ver y descargar catálogo PDF'],
            ['clave' => 'proveedores.gestionar', 'etiqueta' => 'Gestionar Proveedores', 'modulo' => 'proveedores', 'descripcion' => 'CRUD de proveedores'],
            ['clave' => 'cotizaciones.gestionar', 'etiqueta' => 'Gestionar Cotizaciones', 'modulo' => 'cotizaciones', 'descripcion' => 'Crear, editar, imprimir y enviar cotizaciones'],
            ['clave' => 'plantillas.gestionar', 'etiqueta' => 'Gestionar Plantillas Correo', 'modulo' => 'correos', 'descripcion' => 'CRUD de plantillas de correo'],
            ['clave' => 'condiciones.gestionar', 'etiqueta' => 'Gestionar Condiciones', 'modulo' => 'cotizaciones', 'descripcion' => 'CRUD de condiciones comerciales'],
            ['clave' => 'logos.gestionar', 'etiqueta' => 'Gestionar Logos Empresa', 'modulo' => 'negocios', 'descripcion' => 'CRUD de logos de empresa'],
            ['clave' => 'exim.gestionar', 'etiqueta' => 'Gestionar EXIM Exportaciones', 'modulo' => 'exim', 'descripcion' => 'Acceso al módulo de exportaciones EXIM'],
            ['clave' => 'visitas.gestionar', 'etiqueta' => 'Gestionar Visitas Técnicas', 'modulo' => 'visitas', 'descripcion' => 'Ver y administrar visitas técnicas'],
            ['clave' => 'suscripciones.gestionar', 'etiqueta' => 'Gestionar Suscripciones', 'modulo' => 'suscripciones', 'descripcion' => 'Ver y administrar suscripciones/boletín'],
            ['clave' => 'concursos.gestionar', 'etiqueta' => 'Gestionar Concursos', 'modulo' => 'concursos', 'descripcion' => 'Administrar concursos y sorteo en vivo'],
            ['clave' => 'pedidos.gestionar', 'etiqueta' => 'Gestionar Pedidos', 'modulo' => 'pedidos', 'descripcion' => 'Ver, editar y actualizar pedidos'],
            ['clave' => 'envios.gestionar', 'etiqueta' => 'Gestionar Envíos', 'modulo' => 'envios', 'descripcion' => 'Tipos de envío y tarifas'],
            ['clave' => 'usuarios.ver', 'etiqueta' => 'Ver Usuarios', 'modulo' => 'usuarios', 'descripcion' => 'Listar y ver usuarios'],
            ['clave' => 'usuarios.gestionar', 'etiqueta' => 'Gestionar Usuarios', 'modulo' => 'usuarios', 'descripcion' => 'Crear, editar y eliminar usuarios y asignar roles'],
            ['clave' => 'permisos.gestionar', 'etiqueta' => 'Gestionar Permisos', 'modulo' => 'usuarios', 'descripcion' => 'Administrar permisos por rol y por usuario'],
            ['clave' => 'rubros.gestionar', 'etiqueta' => 'Gestionar Rubros', 'modulo' => 'usuarios', 'descripcion' => 'CRUD de rubros'],
            ['clave' => 'ubigeo.gestionar', 'etiqueta' => 'Gestionar Ubigeo', 'modulo' => 'usuarios', 'descripcion' => 'CRUD de países, departamentos, provincias y distritos'],
            ['clave' => 'posts.gestionar', 'etiqueta' => 'Gestionar Posts / Blog', 'modulo' => 'contenido', 'descripcion' => 'CRUD de posts del blog'],
            ['clave' => 'negocios.gestionar', 'etiqueta' => 'Gestionar Negocios', 'modulo' => 'negocios', 'descripcion' => 'Configurar negocios/sitios'],
        ];

        foreach ($permisos as $data) {
            Permiso::updateOrCreate(['clave' => $data['clave']], $data);
        }

        $admin = Role::where('nombre', 'admin')->first();
        if ($admin) {
            $admin->permisos()->sync(Permiso::all()->pluck('id'));
        }

        $vendedor = Role::where('nombre', 'vendedor')->first();
        if ($vendedor) {
            $vendedor->permisos()->sync(
                Permiso::whereIn('clave', [
                    'dashboard.ver',
                    'productos.ver',
                    'productos.crear',
                    'productos.editar',
                    'ofertas.gestionar',
                    'cotizaciones.gestionar',
                    'catalogos.ver',
                ])->pluck('id')
            );
        }

        $cotizante = Role::where('nombre', 'cotizante')->first();
        if ($cotizante) {
            $cotizante->permisos()->sync(
                Permiso::whereIn('clave', [
                    'dashboard.ver',
                    'cotizaciones.gestionar',
                ])->pluck('id')
            );
        }
    }
}