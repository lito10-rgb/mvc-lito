# AUDITORÍA COMPLETA DEL PROYECTO LARAVEL `mvc-lito`

**Fecha:** 12 de agosto de 2026  
**Versión Laravel:** 12.0  
**Versión PHP:** 8.2+  
**Tipo:** E-commerce multi-tenancy con módulo EXIM

---

## 📋 ÍNDICE

1. [Estructura General](#1-estructura-general)
2. [Backend](#2-backend)
3. [Frontend](#3-frontend)
4. [Base de Datos](#4-base-de-datos)
5. [Flujo de Funcionamiento](#5-flujo-de-funcionamiento)
6. [Configuración](#6-configuración)
7. [Problemas y Mejoras](#7-problemas-y-mejoras)
8. [Deploy](#8-deploy)
9. [Resumen Ejecutivo](#9-resumen-ejecutivo)

---

## 1. ESTRUCTURA GENERAL

### 1.1 Carpetas Principales

| Carpeta | Propósito |
|---------|-----------|
| `app/` | Lógica de la aplicación (Controladores, Modelos, Middleware, Helpers) |
| `config/` | Configuración de Laravel |
| `database/` | Migraciones, Seeders |
| `resources/` | Vistas Blade, SCSS, JavaScript, Vue components |
| `routes/` | Definición de rutas |
| `public/` | Archivos públicos y punto de entrada |
| `storage/` | Archivos generados por la aplicación |
| `vendor/` | Dependencias de Composer |
| `bootstrap/` | Archivos de inicio |
| `.github/workflows/` | Configuración de CI/CD para deploy |
| `deploy/` | Scripts personalizados de deployment |

### 1.2 Versiones Detectadas

- **PHP:** ^8.2 (requiere PHP 8.2 o superior)
- **Laravel:** ^12.0 (versión más reciente, lanzada en 2024)
- **Base de datos:** MySQL/MariaDB (XAMPP local), SQLite (configuración default)
- **Node.js:** Versión no especificada (Vite 6.0.11)

### 1.3 Arquitectura Multi-Negocio

El proyecto implementa un sistema de multi-tenancy a través de:

- **Tabla `negocios`:** Almacena información de diferentes negocios
- **Funciones helper:** `app/Helpers/negocio.php` con funciones como:
  - `negocio_actual_id()` - Determina el negocio actual
  - `negocio_actual_nombre()` - Retorna nombre del negocio
  - `negocio_color()` - Colores temáticos por negocio
  - `negocio_theme()` - Configuración de tema por negocio
- **Relaciones many-to-many:** Productos, categorías y subcategorías pueden pertenecer a múltiples negocios
- **Sesión:** El negocio actual se almacena en sesión

---

## 2. BACKEND

### 2.1 Rutas (`routes/web.php`)

#### Rutas Públicas
- `/` - Home (HomeController@menu)
- `/productos` - Listado de productos
- `/productos/buscar` - Búsqueda de productos
- `/producto/{ruta}` - Detalle de producto (SEO-friendly)
- `/categoria/{id}` - Categorías
- `/subcategoria/{id}` - Subcategorías
- `/blog` - Blog
- `/contacto` - Formulario de contacto

#### Rutas de Carrito
- `POST /carrito/agregar/{producto}` - Agregar al carrito
- `POST /carrito/actualizar/{id}` - Actualizar cantidad
- `GET /carrito` - Ver carrito
- `POST /carrito/eliminar/{id}` - Eliminar item
- `POST /carrito/vaciar` - Vaciar carrito
- `GET /carrito/count` - Conteo de items (JSON)

#### Rutas de Autenticación
- `GET /register` - Formulario de registro
- `POST /register` - Procesar registro
- `GET /login` - Formulario de login
- `POST /login` - Procesar login
- `POST /logout` - Cerrar sesión

#### Rutas de Checkout
- `GET /checkout` - Página de checkout (requiere auth)
- `POST /checkout/pay` - Procesar pago
- `GET /checkout/success` - Pago exitoso
- `GET /checkout/failure` - Pago fallido
- `GET /checkout/pending` - Pago pendiente
- `GET /checkout/mercadopago/*` - Callbacks MercadoPago
- `GET /checkout/paypal/*` - Callbacks PayPal
- `POST /checkout/mercadopago/notification` - Webhook MercadoPago

#### Rutas de Admin (Protegidas con middleware `admin`)
- `/admin/dashboard` - Dashboard principal
- `/admin/productos` - CRUD productos
- `/admin/categorias` - CRUD categorías
- `/admin/subcategorias` - CRUD subcategorías
- `/admin/marcas` - CRUD marcas
- `/admin/proveedores` - CRUD proveedores
- `/admin/cotizaciones` - CRUD cotizaciones
- `/admin/pedidos` - Gestión de pedidos
- `/admin/usuarios` - Gestión de usuarios
- `/admin/posts` - Blog posts
- `/admin/concursos` - Gestión de concursos
- `/admin/negocios` - Gestión de negocios
- `/admin/exim/*` - Módulo EXIM completo

#### Rutas Catch-All SEO
Al final del archivo, una ruta catch-all maneja URLs amigables:
```php
Route::get('/{ruta}', function ($ruta) {
    // Busca producto por ruta
    // Busca categoría por ruta
    // Busca subcategoría por ruta
    // Si no encuentra, 404
})->where('ruta', '^[a-zA-Z0-9\-]+$');
```

### 2.2 Controladores

#### Controladores Públicos (~20 controladores)
- `HomeController` - Página principal
- `ProductosController` - Gestión de productos públicos
- `CategoriaController` - Categorías públicas
- `CarritoController` - Lógica del carrito
- `CheckoutController` - Procesamiento de pagos
- `AuthController` - Autenticación de usuarios
- `PerfilController` - Perfil de usuario
- `PedidoController` - Pedidos de usuarios
- `ContactoController` - Formulario de contacto
- `BlogController` - Blog público
- `FavoritoController` - Favoritos de usuarios
- `CotizacionController` - Solicitudes de cotización
- `VisitaTecnicaController` - Solicitudes de visita técnica
- `SuscripcionController` - Boletín informativo
- `UbicacionController` - Ubicaciones geográficas

#### Controladores Admin (~30 controladores)
- `Admin/AuthController` - Autenticación admin
- `Admin/UserAdminController` - Gestión usuarios
- `Admin/ProductoController` - CRUD productos
- `Admin/CategoriaController` - CRUD categorías
- `Admin/SubcategoriaController` - CRUD subcategorías
- `Admin/MarcaController` - CRUD marcas
- `Admin/ProveedorController` - CRUD proveedores
- `Admin/CotizacionController` - CRUD cotizaciones
- `Admin/PedidoController` - Gestión pedidos
- `Admin/PostController` - CRUD posts
- `Admin/ConcursoController` - Gestión concursos
- `Admin/NegocioController` - Gestión negocios
- `Admin/BannerSlideController` - Sliders de banners
- `Admin/EmpresaLogoController` - Logos empresariales
- `Admin/PlantillaCorreoController` - Plantillas de correo
- `Admin/Exim/*` - Módulo EXIM completo (13 controladores)

### 2.3 Modelos

#### Modelos Principales (~50 modelos)

**Usuarios y Permisos:**
- `User` - Usuarios del sistema
- `UserProfile` - Perfiles extendidos
- `UserScore` - Puntuaciones de usuarios
- `Role` - Roles de usuario
- `Administrador` - Administradores del sistema
- `Usuario` - Usuarios alternativos

**Comercio:**
- `Producto` - Productos del catálogo
- `Categoria` - Categorías de productos
- `Subcategoria` - Subcategorías
- `Marca` - Marcas de productos
- `Proveedor` - Proveedores
- `Negocio` - Negocios (multi-tenancy)
- `Rubro` - Rubros de negocio

**Pedidos y Pagos:**
- `Order` - Órdenes de compra
- `OrderItem` - Items de orden
- `Carrito` - Carritos de compra
- `Compra` - Compras
- `Venta` - Ventas

**Cotizaciones:**
- `Cotizacion` - Cotizaciones (módulo principal)
- `CondicionesComerciale` - Condiciones comerciales

**Contenido:**
- `Post` - Posts del blog
- `Comentario` - Comentarios de posts
- `Banner` - Banners principales
- `BannerSlide` - Slides de banners
- `Slide` - Slides genéricos
- `Cabecera` - Cabeceras SEO

**Sistema:**
- `Moneda` - Monedas del sistema
- `Comercio` - Configuración comercial
- `Plantilla` - Plantillas genéricas
- `PlantillaCorreo` - Plantillas de correo
- `CorreoEnviado` - Registro de correos
- `Suscripcion` - Suscripciones al boletín
- `VisitaTecnica` - Solicitudes de visita técnica
- `Concurso` - Concursos
- `ConcursoParticipante` - Participantes de concursos
- `Favorito` - Favoritos de usuarios

**Ubicaciones:**
- `Pais` - Países
- `Departamento` - Departamentos
- `Provincia` - Provincias
- `Distrito` - Distritos

**Módulo EXIM (Namespace `App\Models\Exim`):**
- `Exim\Cliente` - Clientes EXIM
- `Exim\Cotizacion` - Cotizaciones EXIM
- `Exim\CotizacionItem` - Items de cotización EXIM
- `Exim\Contenedor` - Contenedores
- `Exim\Documento` - Documentación
- `Exim\GastoLogistico` - Gastos logísticos
- `Exim\GastoOperativo` - Gastos operativos
- `Exim\Incoterm` - Incoterms
- `Exim\Moneda` - Monedas EXIM
- `Exim\Muestra` - Muestras
- `Exim\Pallet` - Pallets
- `Exim\Producto` - Productos EXIM
- `Exim\Seguro` - Seguros
- `Exim\Transporte` - Transportes

### 2.4 Migraciones

#### Organización
- **Migraciones principales:** `database/migrations/` (~55 archivos)
- **Migraciones EXIM:** `database/migrations/exim/` (15 archivos)
- **Total:** ~70 archivos de migración

#### Migraciones Principales (por fecha)

**2025 (Migraciones iniciales):**
- `0001_01_01_000000_create_users_table.php` - Usuarios Laravel
- `2025_04_10_*` - Tablas base: administradores, proveedores, categorías, subcategorías, usuarios, ventas, marcas, monedas, comercio, slide, cabeceras, productos, compras
- `2025_05_*` - Plantillas, banners, timestamps
- `2025_06_*` - Relaciones, campos adicionales
- `2025_10_14` - Orders table
- `2026_01_27` - Posts y relaciones
- `2026_06_*` - Roles, rubros, perfiles, favoritos
- `2026_06_25` - Cotizaciones
- `2026_06_28` - Suscripciones, visitas técnicas
- `2026_07_*` - Mejoras: stock, order items, direcciones, proveedores, condiciones, logos, emails, negocios, relaciones many-to-many, temas, concursos, tipo de cambio

**Migraciones EXIM (2026-06-26):**
- `exim/2026_06_26_235901_create_exim_monedas_table.php`
- `exim/2026_06_26_235902_create_exim_incoterms_table.php`
- `exim/2026_06_26_235903_create_exim_transportes_table.php`
- `exim/2026_06_26_235904_create_exim_seguros_table.php`
- `exim/2026_06_26_235905_create_exim_pallets_table.php`
- `exim/2026_06_26_235906_create_exim_contenedores_table.php`
- `exim/2026_06_26_235907_create_exim_gastos_operativos_table.php`
- `exim/2026_06_26_235908_create_exim_gastos_logisticos_table.php`
- `exim/2026_06_26_235909_create_exim_clientes_table.php`
- `exim/2026_06_26_235910_create_exim_productos_table.php`
- `exim/2026_06_26_235911_create_exim_cotizaciones_table.php`
- `exim/2026_06_26_235912_create_exim_cotizacion_items_table.php`
- `exim/2026_06_26_235913_create_exim_muestras_table.php`
- `exim/2026_06_26_235914_create_exim_documentos_table.php`
- `exim/2026_06_26_235915_create_paises_table.php`

### 2.5 Middleware

#### Middleware Personalizado
- `AdminMiddleware` (`app/Http/Middleware/AdminMiddleware.php`)
  - Verifica autenticación
  - Verifica rol de administrador
  - Redirige a login si no cumple requisitos

#### Registro de Middleware
En `bootstrap/app.php`:
```php
$middleware->alias([
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
]);
```

### 2.6 Seeders

- `AdministradorSeeder` - Datos iniciales de administradores
- `CategoriaSeeder` - Categorías iniciales
- `MarcaSeeder` - Marcas iniciales
- `ProductoSeeder` - Productos de ejemplo
- `ProveedorSeeder` - Proveedores iniciales
- `SubcategoriaSeeder` - Subcategorías iniciales
- `NegocioSeeder` - Negocios iniciales
- `EximInitialDataSeeder` - Datos iniciales del módulo EXIM

---

## 3. FRONTEND

### 3.1 Tecnologías Detectadas

| Tecnología | Versión | Uso |
|------------|---------|-----|
| **Template Engine** | Blade | Vistas principales |
| **Framework CSS** | Bootstrap 5.3.6 | Layout base |
| **Framework CSS** | TailwindCSS 4.0.0 | Estilos adicionales |
| **Framework JS** | Vue 3.5.13 | Componentes interactivos |
| **Bundler** | Vite 6.0.11 | Build de assets |
| **Iconos** | FontAwesome 7.0.0 | Iconos UI |
| **Iconos** | Bootstrap Icons 1.10.5 | Iconos adicionales |
| **Slider** | Swiper 11.2.10 | Carruseles |
| **Micro-framework** | Alpine.js 3.x | Interactividad ligera |
| **HTTP Client** | Axios 1.8.2 | Peticiones AJAX |

### 3.2 Vistas

#### Layouts
- `layouts/app.blade.php` - Layout principal (frontend)
- `layouts/admin.blade.php` - Layout para administración
- `layouts/dashboard.blade.php` - Layout dashboard
- `layouts/volt/*` - Layouts alternativos (no usados principalmente)
- `layouts/app-backup.blade.php` - Layout backup (no usado)
- `layouts/test.blade.php` - Layout de pruebas

#### Vistas Públicas
- `home.blade.php` - Página principal
- `productos/index.blade.php` - Listado de productos
- `productos/detalle.blade.php` - Detalle de producto
- `productos/buscar.blade.php` - Búsqueda de productos
- `productos/comentarios.blade.php` - Comentarios de productos
- `checkout/index.blade.php` - Página de checkout
- `contacto/index.blade.php` - Formulario de contacto
- `blog/index.blade.php` - Listado de blog
- `blog/show.blade.php` - Detalle de post

#### Vistas Admin
- `admin/dashboard.blade.php` - Dashboard principal
- `admin/productos/*` - CRUD productos (index, create, edit, show, form)
- `admin/categorias/*` - CRUD categorías
- `admin/subcategorias/*` - CRUD subcategorías
- `admin/marcas/*` - CRUD marcas
- `admin/proveedores/*` - CRUD proveedores
- `admin/cotizaciones/*` - CRUD cotizaciones (con modales)
- `admin/pedidos/*` - Gestión de pedidos
- `admin/usuarios/*` - Gestión de usuarios
- `admin/posts/*` - CRUD posts
- `admin/concursos/*` - Gestión de concursos (con sorteo)
- `admin/negocios/*` - Gestión de negocios
- `admin/logos/*` - Gestión de logos empresariales
- `admin/plantillas/*` - Gestión de plantillas
- `admin/catalogos/*` - Catálogos imprimibles
- `admin/exim/*` - Módulo EXIM completo (clientes, cotizaciones, productos, etc.)

#### Partials
- `partials/header.blade.php` - Header dinámico
- `partials/footer.blade.php` - Footer dinámico

### 3.3 Estilos

#### Estructura SCSS
- `resources/scss/app.scss` - Archivo principal
- `resources/scss/_variables.scss` - Variables globales
- `resources/scss/_mixins.scss` - Mixins reutilizables
- `resources/scss/base/spacing.scss` - Espaciado
- `resources/scss/cart/cart.scss` - Estilos de carrito
- `resources/scss/componentes/buttons.scss` - Botones
- `resources/scss/header.scss` - Header
- `resources/scss/footer.scss` - Footer
- `resources/scss/breadcrumb.scss` - Breadcrumbs
- `resources/scss/megamenu.scss` - Menú megamenú

#### Sistema de Temas Dinámicos
El layout `app.blade.php` implementa variables CSS dinámicas por negocio:

```css
:root {
    --theme-primary: {{ negocio_color('primary', config('theme.colors.primary')) }};
    --theme-secondary: {{ negocio_color('secondary', config('theme.colors.secondary')) }};
    --theme-accent: {{ negocio_color('accent', config('theme.colors.accent')) }};
    --theme-header-bg: {{ negocio_theme('color_header_bg', '#1a1a1a') }};
    --theme-footer-bg: {{ negocio_theme('color_footer_bg', '#1a1a1a') }};
    /* ... más variables */
}
```

### 3.4 JavaScript

#### Estructura JS
- `resources/js/app.js` - Entry point principal
- `resources/js/bootstrap.js` - Bootstrap JS
- `resources/js/carrito.js` - Lógica de carrito
- `resources/js/carrito-actions.js` - Acciones de carrito
- `resources/js/fallback-counter.js` - Contador fallback
- `resources/js/fallback-thumbs.js` - Miniaturas fallback
- `resources/js/admin/productos.js` - Lógica admin productos
- `resources/js/marcas.js` - Lógica de marcas
- `resources/js/proveedores.js` - Lógica de proveedores
- `resources/js/subcategorias.js` - Lógica de subcategorías
- `resources/js/perfil.js` - Lógica de perfil

#### Componentes Vue
- `resources/js/components/CartCounter.vue` - Contador de carrito
- `resources/js/components/AddToCart.vue` - Botón agregar al carrito
- `resources/js/components/App.vue` - Componente principal

#### Configuración Axios
En `app.js`:
- CSRF token configurado
- Headers X-Requested-With configurados
- Base URL dinámico según entorno
- Soporte para local/ngrok

### 3.5 Configuración Vite

`vite.config.js`:
```javascript
export default defineConfig({
    base: '/',
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/scss/perfil.scss',
                'resources/js/app.js',
                'resources/js/perfil.js',
                'resources/js/carrito-actions.js',
                'resources/js/fallback-counter.js',
                'resources/js/fallback-thumbs.js',
                'resources/js/admin/productos.js',
                'resources/js/proveedores.js',
                'resources/js/marcas.js',
            ],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
```

---

## 4. BASE DE DATOS

### 4.1 Motor de Base de Datos

**Configuración en `config/database.php`:**

| Conexión | Driver | Uso |
|----------|--------|-----|
| `sqlite` | SQLite | Default (development) |
| `mysql` | MySQL | Producción principal |
| `mariadb` | MariaDB | Alternativa MySQL |
| `cafeperu` | MySQL | Base de datos temporal |
| `pgsql` | PostgreSQL | Opcional |
| `sqlsrv` | SQL Server | Opcional |

**Configuración actual:**
- **Default:** sqlite (según `.env.example`)
- **Producción:** MySQL/MariaDB (según contexto y AGENTS.md)
- **Charset:** utf8mb4
- **Collation:** utf8mb4_unicode_ci

### 4.2 Tablas Principales

#### Sistema de Usuarios
- `users` - Usuarios principales
- `user_profiles` - Perfiles extendidos
- `user_scores` - Puntuaciones
- `roles` - Roles del sistema
- `role_user` - Relación usuarios-roles
- `rubro_user` - Relación usuarios-rubros
- `rubros` - Rubros de negocio
- `administradores` - Administradores del sistema

#### Comercio Electrónico
- `productos` - Catálogo de productos
- `categorias` - Categorías
- `subcategorias` - Subcategorías
- `marcas` - Marcas
- `proveedores` - Proveedores
- `carritos` - Carritos de compra
- `orders` - Órdenes de compra
- `order_items` - Items de orden
- `compras` - Compras
- `ventas` - Ventas
- `favoritos` - Favoritos de usuarios

#### Cotizaciones
- `cotizaciones` - Cotizaciones principales
  - Campos: tipo_cambio, productos_json, condiciones, emisor_id, cliente_id
- `condiciones_comerciales` - Condiciones comerciales
- `empresa_logos` - Logos empresariales

#### Contenido
- `posts` - Posts del blog
- `comentarios` - Comentarios
- `banners` - Banners principales
- `banner_slides` - Slides de banners
- `slides` - Slides genéricos
- `cabeceras` - Cabeceras SEO
- `plantillas` - Plantillas genéricas
- `plantillas_correo` - Plantillas de correo
- `correos_enviados` - Registro de correos enviados

#### Sistema Multi-Negocio
- `negocios` - Negocios (multi-tenancy)
- `producto_negocio` - Relación productos-negocios
- `categoria_negocio` - Relación categorías-negocios
- `subcategoria_negocio` - Relación subcategorías-negocios
- `marca_negocio` - Relación marcas-negocios

#### Sistema de Concursos
- `concursos` - Concursos
- `concurso_participantes` - Participantes

#### Otros
- `suscripciones` - Suscripciones al boletín
- `visitas_tecnicas` - Solicitudes de visita técnica
- `monedas` - Monedas del sistema
- `comercio` - Configuración comercial
- `cache` - Caché del sistema
- `jobs` - Jobs de cola
- `failed_jobs` - Jobs fallidos

#### Ubicaciones
- `paises` - Países (módulo EXIM)
- `departamentos` - Departamentos
- `provincias` - Provincias
- `distritos` - Distritos

#### Módulo EXIM
- `exim_monedas` - Monedas EXIM
- `exim_incoterms` - Incoterms
- `exim_transportes` - Transportes
- `exim_seguros` - Seguros
- `exim_pallets` - Pallets
- `exim_contenedores` - Contenedores
- `exim_gastos_operativos` - Gastos operativos
- `exim_gastos_logisticos` - Gastos logísticos
- `exim_clientes` - Clientes EXIM
- `exim_productos` - Productos EXIM
- `exim_cotizaciones` - Cotizaciones EXIM
- `exim_cotizacion_items` - Items de cotización EXIM
- `exim_muestras` - Muestras
- `exim_documentos` - Documentación

### 4.3 Relaciones Principales

#### Modelo Producto
```php
public function categoria() {
    return $this->belongsTo(Categoria::class, 'categoria_id');
}

public function subcategoria() {
    return $this->belongsTo(Subcategoria::class, 'subcategoria_id');
}

public function marca() {
    return $this->belongsTo(Marca::class, 'marca_id');
}

public function proveedor() {
    return $this->belongsTo(Proveedor::class, 'proveedor_id');
}

public function negocios() {
    return $this->belongsToMany(Negocio::class, 'producto_negocio');
}
```

#### Modelo User
```php
public function profile() {
    return $this->hasOne(UserProfile::class, 'user_id');
}

public function scores() {
    return $this->hasOne(UserScore::class, 'user_id');
}

public function rubros() {
    return $this->belongsToMany(Rubro::class);
}

public function favoritos() {
    return $this->belongsToMany(Producto::class, 'favoritos', 'user_id', 'producto_id');
}

public function cotizaciones() {
    return $this->hasMany(Cotizacion::class, 'cliente_id');
}

public function orders() {
    return $this->hasMany(Order::class, 'user_id');
}

public function roles() {
    return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
}
```

#### Modelo Cotización
```php
public function emisor() {
    return $this->belongsTo(User::class, 'emisor_id');
}

public function cliente() {
    return $this->belongsTo(User::class, 'cliente_id');
}

public function logo() {
    return $this->belongsTo(EmpresaLogo::class, 'logo_id');
}

public function condicion() {
    return $this->belongsTo(CondicionesComerciale::class, 'condicion_id');
}
```

---

## 5. FLUJO DE FUNCIONAMIENTO

### 5.1 Ejemplo 1: Ver Producto (SEO-friendly)

```
1. Usuario accede: /cafe-tostado-molido-mono-tingales-500gr
   ↓
2. Route::get('/{ruta}') [catch-all al final de web.php]
   ↓
3. Closure busca en BD:
   - Producto::where('ruta', $ruta)->whereHas('negocios')->first()
   - Si no encuentra: Categoria::where('ruta', $ruta)->first()
   - Si no encuentra: Subcategoria::where('ruta', $ruta)->first()
   ↓
4. Si encuentra producto: ProductosController@mostrarProducto($ruta)
   ↓
5. Controller consulta:
   - Producto con relaciones (categoria, subcategoria, marca, proveedor)
   - Cabecera SEO para la ruta
   - Productos relacionados (misma categoría y subcategoría)
   ↓
6. Vista: resources/views/productos/detalle.blade.php
   ↓
7. Layout: resources/views/layouts/app.blade.php
   - Header dinámico con negocio actual
   - Meta tags SEO dinámicos
   - Variables CSS por negocio
   ↓
8. Componentes Vue montados en #app-vue
   ↓
9. Respuesta HTML completa al navegador
```

### 5.2 Ejemplo 2: Procesar Pago MercadoPago

```
1. Usuario en checkout hace clic en "Pagar con MercadoPago"
   ↓
2. POST /checkout/pay con metodo=mercadopago
   ↓
3. Route middleware('auth') → CheckoutController@pay()
   ↓
4. Controller valida:
   - Carrito no vacío
   - Método de pago válido
   - Datos de envío (si productos físicos)
   ↓
5. Controller crea preferencia MercadoPago:
   - Configura items del carrito
   - Configura URLs de retorno
   - Llama API de MercadoPago
   ↓
6. Redirección a MercadoPago para pago
   ↓
7. Usuario completa pago en MercadoPago
   ↓
8. MercadoPago redirige a: /checkout/mercadopago/success
   ↓
9. CheckoutController@mercadopagoSuccess()
   ↓
10. Controller:
    - Verifica estado del pago
    - Crea Order en BD
    - Crea OrderItems
    - Limpia carrito de sesión
    - Envía email de confirmación
    ↓
11. Redirección a vista de éxito
```

### 5.3 Ejemplo 3: Webhook MercadoPago

```
1. MercadoPago envía POST a /checkout/mercadopago/notification
   ↓
2. Route sin middleware → CheckoutController@mercadopagoNotification()
   ↓
3. Controller recibe notification_id y topic
   ↓
4. Consulta estado a API de MercadoPago
   ↓
5. Actualiza Order en BD según estado:
    - approved → Completar orden
    - rejected → Cancelar orden
    - pending → Mantener pendiente
   ↓
6. Retorna 200 OK
```

### 5.4 Ejemplo 4: Admin Crear Producto

```
1. Admin accede: /admin/productos/create
   ↓
2. Route middleware('admin') → AdminProductoController@create()
   ↓
3. Middleware verifica:
    - Usuario autenticado
    - Usuario tiene rol 'admin'
   ↓
4. Controller retorna vista: admin.productos.create
   ↓
5. Admin llena formulario y hace submit
   ↓
6. POST /admin/productos
   ↓
7. AdminProductoController@store()
   ↓
8. Controller valida datos
   ↓
9. Controller:
    - Producto::create($datos)
    - Cabecera::create(['ruta' => $producto->ruta, ...])
    - Asocia producto a negocio actual
   ↓
10. Redirección a /admin/productos con mensaje de éxito
```

### 5.5 Ejemplo 5: Sistema Multi-Negocio

```
1. Primer acceso al sitio
   ↓
2. negocio_actual_id() determina negocio:
   - Si sesión tiene negocio_id → usa ese
   - Si Request tiene negocio_id → guarda en sesión
   - Si no → detecta por dominio o usa default
   ↓
3. Todas las consultas filtran por negocio:
   - Producto::whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId))
   - Categoria::whereHas('negocios', ...)
   ↓
4. Vista usa colores/tema del negocio:
   - negocio_color('primary') → CSS variable
   - negocio_theme('logo') → Logo específico
   ↓
5. Usuario puede cambiar negocio:
   - GET /negocio/switch/{id}
   - Actualiza sesión negocio_id
   - Redirige back
```

---

## 6. CONFIGURACIÓN

### 6.1 composer.json

**Requerimientos principales:**
```json
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^12.0",
        "laravel/tinker": "^2.10.1",
        "mercadopago/dx-php": "^3.7",
        "paypal/paypal-checkout-sdk": "^1.0"
    }
}
```

**Requerimientos de desarrollo:**
```json
{
    "require-dev": {
        "barryvdh/laravel-debugbar": "^3.15",
        "fakerphp/faker": "^1.23",
        "laravel/breeze": "^2.3",
        "laravel/pail": "^1.2.2",
        "laravel/pint": "^1.13",
        "laravel/sail": "^1.41",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.6",
        "phpunit/phpunit": "^11.5.3"
    }
}
```

**Scripts personalizados:**
```json
{
    "scripts": {
        "dev": [
            "Composer\\Config::disableProcessTimeout",
            "npx concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1\" \"php artisan pail --timeout=0\" \"npm run dev\" --names=server,queue,logs,vite"
        ]
    }
}
```

**⚠️ Configuración problemática:**
```json
{
    "config": {
        "disable-tls": true,
        "secure-http": false
    }
}
```
**Problema:** Permite descargas HTTP inseguras.

### 6.2 package.json

**Dependencias de producción:**
```json
{
    "dependencies": {
        "@fortawesome/fontawesome-free": "^7.0.0",
        "@vitejs/plugin-vue": "^5.2.3",
        "bootstrap": "^5.3.6",
        "swiper": "^11.2.10",
        "vue": "^3.5.13"
    }
}
```

**Dependencias de desarrollo:**
```json
{
    "devDependencies": {
        "@tailwindcss/vite": "^4.0.0",
        "axios": "^1.8.2",
        "concurrently": "^9.0.1",
        "laravel-vite-plugin": "^1.2.0",
        "sass": "^1.89.2",
        "tailwindcss": "^4.0.0",
        "vite": "^6.0.11"
    }
}
```

**Scripts:**
```json
{
    "scripts": {
        "build": "vite build",
        "dev": "vite"
    }
}
```

### 6.3 .env.example

**Configuración principal:**
```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120

CACHE_STORE=database
QUEUE_CONNECTION=database

MAIL_MAILER=log
```

**⚠️ Problemas detectados:**
- `APP_DEBUG=true` (debería ser false en producción)
- `DB_CONNECTION=sqlite` (debería ser mysql en producción)
- `APP_LOCALE=en` (el proyecto está en español)

### 6.4 Configuración Laravel

**config/app.php:**
- `'name' => env('APP_NAME', 'Laravel')`
- `'env' => env('APP_ENV', 'production')`
- `'debug' => (bool) env('APP_DEBUG', false)`

**config/database.php:**
- Default: `sqlite`
- Soporta: sqlite, mysql, mariadb, pgsql, sqlsrv
- Conexión especial `cafeperu` para base temporal

**config/negocio.php** (inferred from helper functions):
- Sistema de multi-tenancy
- Mapeo de dominios a negocios
- Configuración de colores por negocio

### 6.5 Configuración de Bootstrap

**bootstrap/app.php:**
```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

---

## 7. PROBLEMAS Y MEJORAS

### 7.1 🔴 PROBLEMAS CRÍTICOS (Seguridad)

#### 1. APP_DEBUG=true en Producción
**Ubicación:** `.github/workflows/deploy.yml` línea 40  
**Problema:**
```yaml
APP_DEBUG=true
```
**Riesgo:** Expone información sensible (stack traces, variables de entorno, queries) en errores de producción.  
**Solución:**
```yaml
APP_DEBUG=false
```

#### 2. Display Errors Activado
**Ubicación:** `public/index.php` líneas 3-4  
**Problema:**
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```
**Riesgo:** Muestra errores PHP directamente en el navegador en producción.  
**Solución:**
```php
if (app()->environment('local')) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}
```

#### 3. Configuración Composer Insegura
**Ubicación:** `composer.json` líneas 75-76  
**Problema:**
```json
"disable-tls": true,
"secure-http": false
```
**Riesgo:** Permite descargas de paquetes por HTTP sin encriptación, vulnerable a MITM attacks.  
**Solución:** Eliminar estas líneas o establecerlas en `false` y `true` respectivamente.

#### 4. CSRF Token Comentado
**Ubicación:** `resources/views/layouts/app.blade.php` líneas 53-58  
**Problema:**
```blade
<!-- <meta name="csrf-token" content="{{ csrf_token() }}"> -->
```
**Riesgo:** Deshabilita protección CSRF, vulnerable a ataques de cross-site request forgery.  
**Solución:** Descomentar la línea y verificar que el token se esté generando correctamente.

### 7.2 🟡 ERRORES POTENCIALES

#### 5. Mezcla de Sistemas de Autenticación
**Problema:** El proyecto usa Laravel Breeze pero también tiene controladores de autenticación personalizados (`AuthController`, `Admin/AuthController`).  
**Riesgo:** Puede causar conflictos en la gestión de sesiones, cookies y middleware.  
**Solución:** Estandarizar en un solo sistema o documentar claramente la responsabilidad de cada uno.

#### 6. Múltiples Layouts sin Uso
**Problema:** Layouts como `app-backup.blade.php`, `test.blade.php`, `volt/` no se usan activamente.  
**Riesgo:** Contribuyen a la confusión y dificultan el mantenimiento.  
**Solución:** Eliminar o mover a carpeta de archivos obsoletos.

#### 7. Código Comentado Extensivo
**Problema:** Controladores como `ProductosController` tienen bloques grandes de código comentado.  
**Ejemplo:**
```php
// public function index()
// {
//     $productos = Producto::with([...])->get();
//     return response()->json($productos);
// }
```
**Riesgo:** Dificulta lectura y mantenimiento, aumenta tamaño de archivos.  
**Solución:** Eliminar código comentado innecesario o mover a documentación.

#### 8. URLs Hardcoded en Deploy
**Ubicación:** `.github/workflows/deploy.yml` líneas 116, 144  
**Problema:**
```yaml
curl -s https://equiposymaquinas.com/public/clear-cache.php
```
**Riesgo:** No es flexible para otros dominios, requiere cambio manual para cada deployment.  
**Solución:** Usar variable de entorno `${{ secrets.APP_URL }}`.

### 7.3 🟠 CÓDIGO DUPLICADO

#### 9. Funciones de Negocio Repetitivas
**Problema:** Lógica de `negocio_actual_id()` se repite en muchos controladores:
```php
$negocioId = negocio_actual_id();
$query->whereHas('negocios', fn($q) => $q->where('negocio_id', $negocioId));
```
**Solución:** Crear un Trait `HasNegocioScope` o un Query Scope global en el modelo.

#### 10. Validaciones Duplicadas
**Problema:** Mismas reglas de validación en múltiples controladores (productos, categorías, etc.).  
**Solución:** Crear Form Requests (`StoreProductoRequest`, `UpdateProductoRequest`).

### 7.4 🔵 SEGURIDAD

#### 11. Middleware Admin Débil
**Ubicación:** `app/Http/Middleware/AdminMiddleware.php`  
**Problema:**
```php
$isAdmin = $user->roles->contains(function ($role) {
    return strtolower($role->nombre) === 'admin' || strtolower($role->name) === 'admin';
});
```
**Riesgo:** Solo verifica nombre de rol, no permisos específicos por recurso.  
**Solución:** Implementar sistema de permisos granular (Spatie Laravel Permission).

#### 12. Sanitización de Inputs
**Problema:** No se evidencia sanitización explícita de inputs de usuario más allá de la validación de Laravel.  
**Riesgo:** Posible XSS si no se usa Blade correctamente en todas las vistas.  
**Solución:** Implementar middleware de sanitización o usar paquete como `spatie/laravel-html-sanitizer`.

#### 13. Timestamps Deshabilitados
**Ubicación:** `app/Models/User.php` línea 17  
**Problema:**
```php
public $timestamps = false;
```
**Riesgo:** Puede afectar tracking de cambios de contraseña, últimos logins, etc.  
**Solución:** Evaluar si realmente es necesario deshabilitarlos.

### 7.5 🟢 RENDIMIENTO

#### 14. N+1 Queries Potenciales
**Ubicación:** `app/Http/Controllers/HomeController.php`  
**Problema:**
```php
$categorias = Categoria::whereHas('negocios', ...)
    ->with(['subcategorias' => fn($q) => $q->whereHas('negocios', ...)])
    ->get();
```
**Riesgo:** Puede generar N+1 queries si no se usa eager loading correctamente.  
**Solución:** Usar `DebugBar` para identificar y agregar `with()` adicionales.

#### 15. Sin Caché Configurada Eficientemente
**Problema:** Cache driver es 'database' por defecto.  
**Riesgo:** No es tan eficiente como Redis para caché de alto tráfico.  
**Solución:** Configurar Redis para caché en producción.

#### 16. Assets No Optimizados
**Problema:** Vite está configurado pero no hay evidencia de optimización de producción (minificación, tree-shaking).  
**Riesgo:** Tiempos de carga más lentos.  
**Solución:** Verificar que `npm run build` esté optimizando correctamente.

#### 17. CDN Dependencies
**Problema:** Bootstrap JS, Alpine.js cargados vía CDN en lugar de bundlear.  
**Riesgo:** Additional HTTP requests, dependencia de CDN uptime.  
**Solución:** Instalar vía npm y bundlear con Vite.

### 7.6 🟣 SEO

#### 18. Meta Tags Básicos
**Ubicación:** `resources/views/layouts/app.blade.php`  
**Estado:** Implementación básica de Open Graph y Twitter Cards.  
**Mejora:** Hacerlos más específicos por página (ej: producto individual vs listado).

#### 19. Sitemap No Detectado
**Problema:** No hay evidencia de generación de sitemap.xml.  
**Impacto:** Motores de búsqueda pueden no indexar eficientemente.  
**Solución:** Implementar paquete como `spatie/laravel-sitemap`.

#### 20. Canonical URLs
**Estado:** Implementadas pero podrían ser más robustas para evitar duplicate content.  
**Mejora:** Implementar lógica más sofisticada para URLs canónicas.

#### 21. Structured Data Limitado
**Estado:** Solo hay JSON-LD para Organization.  
**Mejora:** Agregar Product, BreadcrumbList, Article schemas.

### 7.7 🟡 FRONTEND

#### 22. Mezcla de Frameworks CSS
**Problema:** Bootstrap 5 + TailwindCSS 4 simultáneamente.  
**Riesgo:** 
- Conflictos de estilos
- Aumento de bundle size
- Curva de aprendizaje más alta  
**Solución:** Elegir uno y migrar gradualmente, o usar Tailwind como complemento muy limitado.

#### 23. Componentes Vue Mínimos
**Problema:** Solo 2 componentes Vue (CartCounter, AddToCart).  
**Riesgo:** No se aprovecha todo el potencial de Vue 3.  
**Solución:** Migrar más interactividad a componentes Vue (filtros, formularios dinámicos).

#### 24. Alpine.js vs Vue
**Problema:** Uso simultáneo de Alpine.js y Vue puede causar conflictos.  
**Riesgo:** Duplicación de funcionalidad, comportamiento impredecible.  
**Solución:** Elegir uno para interactividad del lado del cliente.

### 7.8 🟠 MANTENIMIENTO

#### 25. Estructura de Carpetas Inconsistente
**Problema:** Algunos recursos en `resources/views/`, otros en subcarpetas profundas.  
**Ejemplo:** `admin/exim/cotizaciones/` vs `productos/`  
**Solución:** Estandarizar estructura de carpetas.

#### 26. Naming Conventions Inconsistentes
**Problema:** Mezcla de español e inglés en nombres.  
**Ejemplos:**
- `AdminController` vs `AuthController`
- `CategoriaController` vs `CategoryController` (no existe, pero inconsistente en lógica)  
**Solución:** Estandarizar en español (dado que el UI está en español) o inglés completamente.

#### 27. Documentación Mínima
**Problema:** README es el default de Laravel, no tiene documentación específica del proyecto.  
**Solución:** Crear README personalizado con:
- Instrucciones de setup
- Arquitectura del proyecto
- Guía de desarrollo
- Instrucciones de deploy

#### 28. Helpers Globales
**Problema:** Funciones en `app/Helpers/negocio.php` cargadas globalmente.  
**Riesgo:** Difícil de testear, puede causar conflictos.  
**Solución:** Considerar usar Service Providers o Facades.

### 7.9 🔵 DEPLOY

#### 29. Workflow de Deploy Complejo
**Ubicación:** `.github/workflows/deploy.yml`  
**Problema:** Múltiples pasos manuales y scripts personalizados en carpeta `deploy/`.  
**Riesgo:** Propenso a errores, difícil de mantener.  
**Solución:** Simplificar o considerar Laravel Forge/Vapor.

#### 30. Dependencia de FTP
**Problema:** Deploy usa FTP, que no es el método más moderno/reliable.  
**Riesgo:** Lento, no atómico, problemas con conexiones.  
**Solución:** Considerar SSH deploy, Git pull en servidor, o servicios gestionados.

#### 31. Sin Rollback Automático
**Problema:** Si deploy falla, no hay mecanismo de reversión automática.  
**Riesgo:** Downtime prolongado si hay errores.  
**Solución:** Implementar rollback en workflow o usar zero-downtime deploy.

#### 32. Sin Staging Environment
**Problema:** Deploy directo a producción sin staging.  
**Riesgo:** Errores en producción que podrían detectarse en staging.  
**Solución:** Configurar workflow de staging adicional.

#### 33. Secrets no Validados
**Problema:** Workflow referencia secrets pero no valida que existan.  
**Riesgo:** Falla silenciosa si secrets no están configurados.  
**Solución:** Agregar validación de secrets al inicio del workflow.

---

## 8. DEPLOY

### 8.1 Configuración Actual

**Plataforma:** cPanel hosting  
**Método:** GitHub Actions + FTP  
**Trigger:** Push a `master` o `main`  
**Workflow:** `.github/workflows/deploy.yml`

### 8.2 Proceso de Deploy

**Pasos actuales:**
1. Checkout del código
2. Setup PHP 8.2 con extensiones
3. Install Composer dependencies (no-dev)
4. Build assets con Vite
5. Remove dev files
6. Create .env from secrets
7. Generate APP_KEY
8. Deploy root bootstrap files
9. Upload .env + clear-cache.php (pre-deploy)
10. Clear cache (pre-deploy)
11. Deploy via FTP
12. Upload root + .env backup
13. Clear cache (post-deploy)

### 8.3 Secrets Requeridos

El workflow requiere 9 secrets de GitHub:
- `FTP_HOST` - Host FTP
- `FTP_USERNAME` - Usuario FTP
- `FTP_PASSWORD` - Contraseña FTP
- `FTP_TARGET_DIR` - Directorio destino
- `APP_URL` - URL de la aplicación
- `DB_HOST` - Host de base de datos
- `DB_DATABASE` - Nombre de base de datos
- `DB_USERNAME` - Usuario de base de datos
- `DB_PASSWORD` - Contraseña de base de datos
- `MERCADOPAGO_ACCESS_TOKEN` - Token MercadoPago
- `MERCADOPAGO_PUBLIC_KEY` - Key pública MercadoPago
- `PAYPAL_CLIENT_ID` - Client ID PayPal
- `PAYPAL_CLIENT_SECRET` - Client Secret PayPal

### 8.4 Aspectos Positivos

✅ **Ventajas:**
- Workflow automatizado con GitHub Actions
- Build de assets optimizado con Vite
- Optimización de autoloader de Composer
- Generación automática de APP_KEY
- Limpieza de archivos de desarrollo
- Cache clearing antes y después del deploy
- Separación de archivos de deploy en carpeta específica

### 8.5 Problemas Detectados

❌ **Desventajas:**
- APP_DEBUG=true en producción
- Display errors activado
- URLs hardcoded (equiposymaquinas.com)
- Dependencia de FTP (no es el método más moderno)
- Sin staging environment
- Sin rollback automático
- Sin validación de secrets
- Scripts de deploy personalizados poco mantenibles
- Sin pruebas automatizadas en el workflow
- Sin monitoreo post-deploy

### 8.6 Requisitos Antes de Deploy

**🔴 Críticos:**
1. Cambiar APP_DEBUG a false
2. Eliminar display_errors en producción
3. Verificar todos los secrets configurados
4. Probar workflow en ambiente de desarrollo

**🟡 Importantes:**
5. Configurar backup de base de datos
6. Implementar monitoreo de errores (Sentry, Bugsnag)
7. Configurar CDN para assets estáticos
8. Implementar HTTPS correcto
9. Configurar cron jobs para tareas programadas
10. Verificar permisos de archivos/carpetas

**🟢 Recomendados:**
11. Implementar staging environment
12. Agregar pruebas automatizadas
13. Configurar rollback automático
14. Implementar health checks
15. Configurar logs centralizados

### 8.7 Scripts de Deploy

**Carpeta `deploy/` contiene:**
- `boot-test.php` - Test de boot
- `cafe-root-htaccess` - Configuración htaccess
- `cafe-root-index.php` - Index personalizado
- `check-laravel.php` - Verificación Laravel
- `check.php` - Check general
- `clear-cache.php` - Limpieza de caché
- `debug.php` - Debug info
- `deploy.php` - Script de deploy
- `extract-vendor.php` - Extracción de vendor
- `get-log.php` - Obtener logs
- `migrate.php` - Ejecutar migraciones
- `run-migrate.php` - Runner de migraciones
- `test-base.php` - Test base
- `test-db.php` - Test de base de datos
- `test-laravel.php` - Test Laravel
- `test-php.php` - Test PHP
- `test-request.php` - Test de request
- `test.php` - Test general
- `trace-request.php` - Trazado de request

**Problema:** Muchos scripts que dificultan mantenimiento y pueden causar confusión.

---

## 9. RESUMEN EJECUTIVO

### 9.1 Arquitectura Actual

**Tipo de Aplicación:** E-commerce multi-tenancy con módulo EXIM (Exportación/Importación)

**Stack Tecnológico:**
- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Blade + Vue 3 + Bootstrap 5 + TailwindCSS 4 + Vite
- **Base de datos:** MySQL/MariaDB (~70 tablas)
- **Deploy:** GitHub Actions + FTP a cPanel
- **Pagos:** MercadoPago + PayPal

**Características Principales:**
- Multi-tenancy (múltiples negocios en una instancia)
- Sistema de cotizaciones con tipo de cambio
- Módulo EXIM completo para comercio exterior
- Blog con posts y comentarios
- Sistema de concursos con sorteos
- Gestión de visitas técnicas
- Boletín informativo
- Carrito de compras
- Favoritos de usuarios
- SEO-friendly URLs

### 9.2 Tecnologías Detectadas

**Backend:**
- Laravel 12.0
- PHP 8.2+
- MercadoPago SDK 3.7
- PayPal Checkout SDK 1.0
- Laravel Breeze 2.3
- Laravel DebugBar 3.15

**Frontend:**
- Vue 3.5.13
- Bootstrap 5.3.6
- TailwindCSS 4.0.0
- Vite 6.0.11
- Alpine.js 3.x
- Swiper 11.2.10
- FontAwesome 7.0.0
- Axios 1.8.2

**DevOps:**
- GitHub Actions
- FTP deployment
- Composer
- npm

### 9.3 Partes que Están Bien ✅

**✅ Arquitectura:**
- Sistema multi-negocio bien implementado
- Separación clara de módulos (principal vs EXIM)
- Estructura MVC tradicional y organizada
- Namespaces bien definidos

**✅ Funcionalidad:**
- Integración completa con pasarelas de pago
- Sistema de cotizaciones robusto con tipo de cambio
- Módulo EXIM completo y bien separado
- Sistema de rutas SEO-friendly
- Gestión de estados y workflows

**✅ Base de datos:**
- Migraciones bien estructuradas
- Relaciones correctamente definidas
- Foreign keys implementadas
- Índices apropiados

**✅ Frontend:**
- Responsive design con Bootstrap
- Sistema de temas dinámicos por negocio
- Componentes Vue para interactividad
- Optimización de assets con Vite

**✅ Deploy:**
- Workflow automatizado con GitHub Actions
- Build de assets optimizado
- Limpieza de archivos dev
- Generación automática de APP_KEY

### 9.4 Problemas Encontrados ❌

**Total de problemas detectados:** 33

**Por severidad:**
- 🔴 **Críticos (Seguridad):** 4
- 🟡 **Errores potenciales:** 4  
- 🟠 **Código duplicado:** 2
- 🔵 **Seguridad adicional:** 3
- 🟢 **Rendimiento:** 4
- 🟣 **SEO:** 4
- 🟡 **Frontend:** 3
- 🟠 **Mantenimiento:** 4
- 🔵 **Deploy:** 5

### 9.5 Problemas Críticos que Solucionar Primero 🚨

**1. APP_DEBUG=true en producción**
- **Ubicación:** `.github/workflows/deploy.yml:40`
- **Riesgo:** Expone información sensible
- **Solución:** Cambiar a `APP_DEBUG=false`

**2. Display errors activado**
- **Ubicación:** `public/index.php:3-4`
- **Riesgo:** Muestra errores en producción
- **Solución:** Condicionar a entorno local

**3. Configuración Composer insegura**
- **Ubicación:** `composer.json:75-76`
- **Riesgo:** Permite descargas HTTP inseguras
- **Solución:** Eliminar líneas o configurar correctamente

**4. CSRF token comentado**
- **Ubicación:** `resources/views/layouts/app.blade.php:53-58`
- **Riesgo:** Vulnerabilidad CSRF
- **Solución:** Descomentar y verificar

### 9.6 Mejoras Recomendadas por Prioridad 📋

#### 🔴 ALTA PRIORIDAD (Seguridad - Solucionar esta semana)

1. **Cambiar APP_DEBUG a false en producción**
   - Archivo: `.github/workflows/deploy.yml`
   - Impacto: Seguridad crítica

2. **Eliminar display_errors en producción**
   - Archivo: `public/index.php`
   - Impacto: Seguridad crítica

3. **Remover configuración insegura de Composer**
   - Archivo: `composer.json`
   - Impacto: Seguridad de dependencias

4. **Verificar y activar CSRF token**
   - Archivo: `resources/views/layouts/app.blade.php`
   - Impacto: Protección CSRF

5. **Implementar sanitización de inputs**
   - Paquete recomendado: `spatie/laravel-html-sanitizer`
   - Impacto: Prevención de XSS

6. **Mejorar middleware de admin**
   - Implementar permisos granulares
   - Paquete recomendado: `spatie/laravel-permission`
   - Impacto: Seguridad de acceso

#### 🟡 MEDIA PRIORIDAD (Rendimiento/SEO - Solucionar este mes)

7. **Implementar caché con Redis**
   - Configurar Redis para cache y sessions
   - Impacto: Mejor rendimiento

8. **Optimizar queries para evitar N+1**
   - Usar DebugBar para identificar
   - Agregar eager loading donde falta
   - Impacto: Reducción de carga de BD

9. **Generar sitemap automático**
   - Paquete recomendado: `spatie/laravel-sitemap`
   - Impacto: Mejor SEO

10. **Mejorar meta tags por página**
    - Hacer Open Graph más específicos
    - Agregar más structured data
    - Impacto: Mejor SEO

11. **Bundle assets de CDN**
    - Mover Alpine.js, Bootstrap JS a npm
    - Bundlear con Vite
    - Impacto: Menos HTTP requests

12. **Implementar lazy loading de imágenes**
    - Usar loading="lazy" en imágenes
    - Impacto: Mejor performance

#### 🟢 BAJA PRIORIDAD (Mantenimiento - Solucionar este trimestre)

13. **Limpiar código comentado**
    - Eliminar código comentado innecesario
    - Impacto: Mejor mantenibilidad

14. **Estandarizar naming conventions**
    - Elegir español o inglés completamente
    - Impacto: Mejor legibilidad

15. **Eliminar layouts no usados**
    - Remover layouts backup, test, volt
    - Impacto: Menos confusión

16. **Crear documentación del proyecto**
    - README personalizado
    - Guía de desarrollo
    - Impacto: Mejor onboarding

17. **Implementar Form Requests**
    - Mover validaciones a Form Requests
    - Impacto: Mejor organización

18. **Unificar lógica de negocio**
    - Crear traits para lógica repetitiva
    - Impacto: Menos duplicación

#### 🔵 MEJORAS DE ARCHITECTURA (Corto plazo)

19. **Separar módulo EXIM**
    - Considerar Laravel package
    - Impacto: Mejor separación de concerns

20. **Implementar sistema de permisos robusto**
    - Spatie Laravel Permission
    - Impacto: Mejor seguridad

21. **Agregar pruebas automatizadas**
    - PHPUnit + Feature tests
    - Impacto: Mejor calidad

22. **Implementar staging environment**
    - Workflow adicional para staging
    - Impacto: Menos errores en producción

23. **Migrar de FTP a deploy moderno**
    - Considerar Laravel Forge, Vapor, o SSH deploy
    - Impacto: Mejor reliability

### 9.7 Conclusión

El proyecto `mvc-lito` es una aplicación Laravel 12 compleja y bien estructurada con funcionalidades avanzadas de e-commerce multi-tenancy y un módulo EXIM completo. La arquitectura base es sólida y las funcionalidades principales están bien implementadas.

Sin embargo, existen **4 problemas críticos de seguridad** que deben ser solucionados inmediatamente antes de considerar el proyecto production-ready. Los problemas de rendimiento y SEO son moderados y pueden ser abordados en el corto plazo. Los problemas de mantenimiento son menores pero deberían ser abordados para mejorar la calidad del código a largo plazo.

**Recomendación general:** Solucionar los 4 problemas críticos de seguridad esta semana, luego abordar las mejoras de rendimiento/SEO el próximo mes, y finalmente las mejoras de mantenimiento y arquitectura en el siguiente trimestre.

---

**Auditoría realizada por:** Devin AI  
**Fecha:** 12 de agosto de 2026  
**Versión del documento:** 1.0