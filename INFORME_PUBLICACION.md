# 📋 INFORME DE PUBLICACIÓN - PROYECTO MVC-LITO

**Fecha:** 6 de octubre de 2026  
**Estado:** Listo para publicar en remoto  
**Branch:** master  
**Sitios remotos:** equiposymaquinas.com y cafe-peruano.com (cPanel)  
**Despliegue:** Push a `master` → GitHub Actions → FTP (ver `.github/workflows/deploy.yml`)

---

## 🔄 RESUMEN EJECUTIVO

Esta publicación incluye los siguientes desarrollos principales:

1. **Envío masivo de correos a usuarios** con selección de **negocio/remitente** (desde la tabla `negocios`), plantillas, variables y adjuntos.
2. **Aviso masivo de cambio de contraseña** a usuarios con contraseña `password`, por lotes configurables.
3. **Módulo Ubigeo** (Países / Departamentos / Provincias / Distritos) con cascadas AJAX.
4. **Ficha Técnica de Café** descargable en PDF (dompdf).
5. **Fixes**: plantilla de correo (error `$cotizacion` null), filtro de subcategorías en modal de productos, y pie del recibo de cotización.

> ⚠️ **Importante:** el push desplegará **todos** los cambios no confirmados del repositorio local (módulo Ubigeo, Tareas/Captura, Permisos, modelos nuevos, etc.), no solo los de esta sesión.

---

## 📁 ARCHIVOS NUEVOS

### Controladores
- `app/Http/Controllers/Admin/UsuarioCorreoController.php` — Envío masivo de correos (modal + envío) con remitente por negocio.
- `app/Http/Controllers/Admin/FichaTecnicaController.php` — Genera PDF de ficha técnica de café.
- `app/Http/Controllers/Admin/UbigeoController.php` — CRUD Ubigeo + AJAX de cascadas.
- `app/Http/Controllers/Admin/PermisoController.php` — Gestión de permisos.
- `app/Http/Controllers/Admin/TareaController.php` — Tareas / Captura (proveedores y productos).

### Modelos / Middleware
- `app/Models/Permiso.php`, `app/Models/TareaContacto.php`
- `app/Http/Middleware/PermisoMiddleware.php`
- `app/Console/Commands/ImportarProductosCsv.php`

### Vistas
- `resources/views/admin/usuarios/correo-modal.blade.php` — Modal con Destinatarios, **Negocio/Remitente**, Plantillas, Adjuntos.
- `resources/views/admin/ficha-tecnica/cafe-pdf.blade.php` — Vista PDF de la ficha técnica.
- `resources/views/admin/ubigeo/*` — 4 vistas (paises, departamentos, provincias, distritos).
- `resources/views/admin/permisos/`, `resources/views/admin/tareas/`, `resources/views/emails/tarea_mensaje.blade.php`

### Base de datos
- `database/migrations/2026_09_12_000001_create_permisos_tables.php`
- `database/migrations/2026_09_13_000001_create_tareas_contactos_table.php`
- `database/seeders/PermisoSeeder.php`
- `database/permisos_remoto.sql` — Insert de permisos (incluye `ubigeo.gestionar` id 29 y asignación al rol admin).
- `database/cambios_remoto.sql`, `deploy/migracion_manual_categoria_subcategoria.sql`

---

## 🔧 CAMBIOS EN ARCHIVOS EXISTENTES

- `routes/web.php` — Rutas de correo masivo **movidas al grupo admin** (`admin.usuarios.bulk-correo` y `admin.usuarios.bulk-correo.modal` con middleware `permiso:usuarios.gestionar`). Nueva ruta `admin/ficha-tecnica/cafe` + rutas `admin/ubigeo/*`.
- `resources/views/partials/sidebar.blade.php` — Secciones Ubigeo y enlace "Ficha Técnica Café".
- `resources/views/admin/usuarios/index.blade.php` — Botón "Enviar Correo" + JS `abrirCorreoMasivo()`.
- `resources/views/emails/cotizacion.blade.php` — Eliminado bloque de pie que usaba `$cotizacion->id` (fallaba cuando `cotizacion` es nulo).
- `resources/views/admin/cotizaciones/_product_modal.blade.php` — Filtro de subcategorías N:M corregido.
- `resources/views/admin/cotizaciones/recibo.blade.php` — Pie con empresa/RUC.
- `config/negocio.php` — Mapeo de dominios a negocios.

---

## ✉️ ENVÍO MASIVO DE CORREOS (DETALLE)

- **Destinatarios:** usuarios seleccionados desde la lista (tabla `users`), con `user_ids[]`.
- **Remitente por negocio:** selector `negocio_id` cargado desde la tabla `negocios`. Al elegir negocio se autocompletan:
  - `from_name` = nombre del negocio (ej. "Equipos y Maquinas").
  - `from_email` = primer correo de `footer_email` del negocio; si no hay, `informes@<dominio>`.
  - Precedencia: lo editado manualmente en el formulario gana sobre el negocio; si no hay nada, cae a `config('mail.*')`.
- **Plantillas:** `plantillas_correo` (`asunto`/`contenido`) con variables `{cliente},{nombre},{apellidos},{correo},{telefono},{empresa}`.
- **Adjuntos:** múltiples archivos con `$message->attach()`.
- **Comportamiento:** envío secuencial `Mail::send()`; al final muestra "X enviado(s), Y fallido(s)".

### 🚨 Aviso anti-spam
El envío es SMTP sincrónico en bucle. Se recomienda **no superar 20–30 correos por "tiro"**, con pausas entre lotes (límite diario típico en hosting compartido: 200–300 por remitente).

### ⏱ Límites configurables (nuevo)
Nuevo config `config/correo.php` (override por .env):
- `CORREO_LOTE` (default `25`) — correos por bloque antes de pausar.
- `CORREO_PAUSA` (default `90`) — segundos de espera entre bloques.
- `CORREO_MAX_TOTAL` (default `0` = sin tope) — tope total por ejecución.
El controlador envía en el bucle `enviarMasivo()` respetando lote/pausa/tope.

### 🔑 Aviso de cambio de contraseña (nuevo — modal completo de redacción)
- Botón **"Aviso Contraseña"** en Admin → Usuarios → abre `cambio-password-modal` (igual estructura que `correo-modal`).
- **Destinatarios**: lista de usuarios con contraseña `password` y email del negocio seleccionado (checkboxes, todos marcados, contador). Al cambiar el negocio en el select de destinatarios, recarga el modal con los nuevos usuarios.
- **Remitente**: selector `negocio_id` que autocompleta `from_name` + `from_email` (primer email de `footer_email` o `informes@<dominio>`). Precedencia: manual > negocio > config.
- **Cargar desde plantilla**: `plantillas_correo` rellenan asunto + contenido.
- **Asunto / Contenido**: editables; contenido por defecto incluye `{password}` para mostrar la contraseña actual.
- **Adjuntos**: múltiples archivos.
- **Variables**: `{cliente}, {nombre}, {apellidos}, {correo}, {telefono}, {empresa}, {password}`.
- **Validación**: solo se envía a la intersección `user_ids[] ∩ elegibles(password)`; si el admin desmarca, se omite; si marca alguien sin esa contraseña, se ignora.
- **Throttle**: usa `enviarMasivo()` con `CORREO_LOTE`/`CORREO_PAUSA`/`CORREO_MAX_TOTAL`.
- Verificación de `password` vía bcrypt, hash legacy (crypt con `$2a$07$asxx54...`) o texto plano.
- **Visitado:** 274 usuarios con `password` (248 equiposymaquinas.com, 26 cafe-peruano.com), 273 con email.

### 📋 Historial y exportación de envíos (nuevo)
- Tabla `email_logs`: registra cada correo enviado (bulk o aviso_password) con: usuario, email, nombre, asunto, contenido, tipo, negocio remitente, from_name/from_email, **batch_id** (agrupa lote), estado (enviado/fallido), error, fecha/hora.
- Modelo `EmailLog` + `EmailLogController` + vista `admin/email-logs/index.blade.php` + rutas `admin.email-logs.index` / `admin.email-logs.export`.
- Filtros: tipo, estado, negocio, batch ID, email, rango de fechas.
- **Exportación CSV** (UTF-8 con BOM) con todos los filtros aplicados.
- Enlace en sidebar: "Usuarios → Historial de Envíos".
- Prueba: 12 envíos registrados (bulk + aviso_password) con batch IDs correctos; index renderiza tabla; export genera CSV.

### 🗂 Listas de envío guardadas (Campañas) (nuevo)
- Tabla `email_campaigns`: al enviar (bulk o aviso), campo opcional **"Nombre de la lista"** → guarda un registro con nombre, tipo, negocio, fecha/hora, totales (enviados/fallidos), batch_id, creador.
- Vista `admin/email-logs/index.blade.php` muestra **dos tablas**: arriba "Listas de envío guardadas" (paginadas), abajo "Envíos individuales" (detalle por destinatario).
- Exportación CSV de campañas (`admin.email-logs.export-campaigns`).
- Prueba: 2 campañas creadas ("Newsletter Octubre 2026" tipo bulk, "Aviso contraseña Octubre 2026" tipo aviso_password) con totales correctos; index muestra ambas tablas; export genera CSV.

---

## 🚀 PASOS PARA PUBLICAR EN REMOTO

### 1. Commit y push
```bash
git add -A
git commit -m "feat: correo masivo con remitente por negocio, ubigeo, ficha tecnica cafe"
git push origin master
```
Esto dispara `.github/workflows/deploy.yml` (requiere conflictos de secrets: FTP_HOST, FTP_USERNAME, FTP_PASSWORD, FTP_TARGET_DIR, APP_URL, DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD).

### 2. Base de datos en cPanel (terminal)
```bash
php artisan migrate --force
php artisan db:seed --class=PermisoSeeder --force   # opcional si ya se importó el SQL
```
O importar manualmente `database/permisos_remoto.sql` (permisos + asignación al rol admin).

### 3. ⚠️ Verificar configuración de correo en remoto
El `.env` generado por el workflow usa:
```
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
```
Con esto los correos **solo se registran en el log**, no se entregan. Para habilitar envío real en cPanel hay que actualizar el `.env` remoto con los datos SMTP reales:
```
MAIL_MAILER=smtp
MAIL_HOST=mail.equiposymaquinas.com
MAIL_PORT=587
MAIL_USERNAME=informes@equiposymaquinas.com
MAIL_PASSWORD=...
MAIL_ENCRYPTION=tls
```

### 4. Validación post-deploy
- URL del sitio responde `200`.
- Admin → Usuarios → seleccionar → "Enviar Correo" → elegir negocio → Enviar.
- Admin → Catálogo PDF → "Ficha Técnica Café" descarga el PDF.
- Admin → Ubigeo: CRUD y cascadas.
- Tareas / Captura.

---

## 🧪 PRUEBAS REALIZADAS (LOCAL)

| Prueba | Resultado |
|---|---|
| `php -l` en controladores y rutas | OK, sin errores |
| `php artisan route:list` | Rutas `admin.usuarios.bulk-correo[.modal]` y `admin.ficha-tecnica.cafe` OK |
| Modal de correo masivo | Renderiza con 4 negocios cargados desde BD |
| `enviar()` con `negocio_id=2` | "2 correo(s) enviado(s)" sin errores (mailer log) |
| Aviso de cambio de contraseña (modal completo, lote 2) | Renderiza 248 destinatarios (equiposymaquinas); POST 2 usuarios: "Enviados 2 aviso(s)." 0.4s |
| Historial de envíos (index + export CSV) | 12 logs registrados (bulk + aviso), batch IDs correctos, tabla renderiza, export 200 OK |
| Listas de envío (campañas) | 2 campañas creadas (bulk + aviso) con nombre/fecha/totales; tabla superior + tabla inferior; export CSV campañas 200 OK |
| Ficha técnica café (dompdf) | PDF válido, 886 KB, cabecera `%PDF-` |
| Envío directo SMTP local | `ok` |

---

## 🗂 PENDIENTE / NO INCLUIDO

- **Correo real en remoto**: el `.env` de producción usa `MAIL_MAILER=log`; cargar SMTP real antes de campañas (ver paso 3).
- `test_mail3.php` e `insertar_usuarios.php` son scripts de desarrollo — revisar si deben excluirse del despliegue.