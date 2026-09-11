SET NAMES utf8mb4;

-- ============================================================
-- SYNC CAFE-PERUANO.COM (ejecutar COMPLETO en phpMyAdmin remoto)
-- Idempotente: se puede volver a ejecutar sin romper nada.
-- ============================================================

-- ============================================================
-- 1. ESTRUCTURA FALTANTE (equivalente a migraciones locales)
-- ============================================================

-- 1.1 ofertaCategoria + ofertaSubcategoria en productos
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='productos' AND COLUMN_NAME='ofertaCategoria');
SET @s = IF(@c=0, 'ALTER TABLE `productos` ADD COLUMN `ofertaCategoria` INT NULL, ADD COLUMN `ofertaSubcategoria` INT NULL', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 1.2 etiquetaOferta en productos
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='productos' AND COLUMN_NAME='etiquetaOferta');
SET @s = IF(@c=0, 'ALTER TABLE `productos` ADD COLUMN `etiquetaOferta` VARCHAR(255) NULL', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 1.3 etiquetaOferta + fechaInicioOferta en categorias
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='categorias' AND COLUMN_NAME='etiquetaOferta');
SET @s = IF(@c=0, 'ALTER TABLE `categorias` ADD COLUMN `etiquetaOferta` VARCHAR(255) NULL, ADD COLUMN `fechaInicioOferta` DATE NULL', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 1.4 etiquetaOferta + fechaInicioOferta en subcategorias
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='subcategorias' AND COLUMN_NAME='etiquetaOferta');
SET @s = IF(@c=0, 'ALTER TABLE `subcategorias` ADD COLUMN `etiquetaOferta` VARCHAR(255) NULL, ADD COLUMN `fechaInicioOferta` DATE NULL', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 1.5 envio_gratis en productos
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='productos' AND COLUMN_NAME='envio_gratis');
SET @s = IF(@c=0, 'ALTER TABLE `productos` ADD COLUMN `envio_gratis` TINYINT(1) NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 1.6 orden en categorias
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='categorias' AND COLUMN_NAME='orden');
SET @s = IF(@c=0, 'ALTER TABLE `categorias` ADD COLUMN `orden` INT UNSIGNED NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 1.7 Tabla cupones
CREATE TABLE IF NOT EXISTS `cupones` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` VARCHAR(50) NOT NULL,
  `tipo` ENUM('porcentaje','monto_fijo') NOT NULL DEFAULT 'porcentaje',
  `valor` DECIMAL(10,2) NOT NULL,
  `min_compra` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `max_usos` INT UNSIGNED NULL,
  `usos_actuales` INT UNSIGNED NOT NULL DEFAULT 0,
  `negocio_id` BIGINT UNSIGNED NULL,
  `fecha_inicio` DATETIME NULL,
  `fecha_fin` DATETIME NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cupones_codigo_unique` (`codigo`),
  KEY `cupones_negocio_id_index` (`negocio_id`),
  KEY `cupones_activo_index` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 1.8 Columnas cupon en orders
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='orders' AND COLUMN_NAME='cupon_id');
SET @s = IF(@c=0, 'ALTER TABLE `orders` ADD COLUMN `cupon_id` BIGINT UNSIGNED NULL, ADD COLUMN `cupon_codigo` VARCHAR(50) NULL, ADD COLUMN `cupon_descuento` DECIMAL(10,2) NULL', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 1.9 Tabla comentarios (con FK a usuarios, igual que local)
CREATE TABLE IF NOT EXISTS `comentarios` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_usuario` BIGINT UNSIGNED NOT NULL,
  `id_producto` BIGINT UNSIGNED NOT NULL,
  `calificacion` DECIMAL(2,1) NOT NULL DEFAULT 5.0,
  `comentario` LONGTEXT NULL,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `comentarios_id_usuario_foreign` (`id_usuario`),
  KEY `comentarios_id_producto_foreign` (`id_producto`),
  CONSTRAINT `comentarios_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comentarios_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 1.10 Tablas tipos_envio + tarifas_envio
CREATE TABLE IF NOT EXISTS `tipos_envio` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` TEXT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='tipos_envio' AND COLUMN_NAME='slug');
SET @s = IF(@c=0, 'ALTER TABLE `tipos_envio` ADD COLUMN `slug` VARCHAR(255) NOT NULL UNIQUE, ADD COLUMN `orden` INT NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='tipos_envio' AND COLUMN_NAME='orden');
SET @s = IF(@c=0, 'ALTER TABLE `tipos_envio` ADD COLUMN `orden` INT NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

CREATE TABLE IF NOT EXISTS `tarifas_envio` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tipo_envio_id` BIGINT UNSIGNED NOT NULL,
  `categoria_id` BIGINT UNSIGNED NULL,
  `subcategoria_id` BIGINT UNSIGNED NULL,
  `minimo` DECIMAL(12,2) NULL DEFAULT 0.00,
  `maximo` DECIMAL(12,2) NULL,
  `costo` DECIMAL(12,2) NOT NULL,
  `gratis` TINYINT(1) NOT NULL DEFAULT 0,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  KEY `tarifas_envio_tipo_envio_id_index` (`tipo_envio_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. DATOS (sincronizar desde local)
-- ============================================================

-- 2.1 tipos_envio
DELETE FROM `tarifas_envio`;
DELETE FROM `tipos_envio`;

INSERT INTO `tipos_envio` (`id`, `nombre`, `slug`, `descripcion`, `activo`, `orden`, `created_at`, `updated_at`) VALUES
(1, 'Nacional - Local', 'nacional-local', 'Envío dentro de Lima Metropolitana', 1, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(2, 'Nacional - Provincias', 'nacional-provincias', 'Envío a provincias dentro del país', 1, 2, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(3, 'Internacional', 'internacional', 'Envío internacional', 1, 3, '2026-09-06 19:10:46', '2026-09-06 19:10:46');

-- 2.2 tarifas_envio
INSERT INTO `tarifas_envio` (`id`, `tipo_envio_id`, `categoria_id`, `subcategoria_id`, `minimo`, `maximo`, `costo`, `gratis`, `activo`, `created_at`, `updated_at`) VALUES
(1, 1, 87, NULL, NULL, 100.00, 15.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(2, 1, 87, NULL, 100.00, NULL, 5.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(3, 1, NULL, NULL, NULL, 1000.00, 25.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(4, 1, NULL, NULL, 1000.00, 1500.00, 40.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(5, 1, NULL, NULL, 1500.00, 3000.00, 45.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(6, 1, NULL, NULL, 3000.00, 5000.00, 95.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(7, 1, NULL, NULL, 5000.00, 9000.00, 220.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(8, 1, NULL, NULL, 9000.00, 15000.00, 290.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(9, 1, NULL, NULL, 15000.00, NULL, 590.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(10, 2, NULL, NULL, NULL, 1000.00, 35.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(11, 2, NULL, NULL, 1000.00, 1500.00, 55.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(12, 2, NULL, NULL, 1500.00, 3000.00, 65.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(13, 2, NULL, NULL, 3000.00, 5000.00, 120.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(14, 2, NULL, NULL, 5000.00, 9000.00, 260.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(15, 2, NULL, NULL, 9000.00, 15000.00, 360.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(16, 2, NULL, NULL, 15000.00, NULL, 740.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(17, 3, NULL, NULL, NULL, 100.00, 60.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(18, 3, NULL, NULL, 100.00, 1500.00, 95.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(19, 3, NULL, NULL, 1500.00, 3000.00, 130.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(20, 3, NULL, NULL, 3000.00, NULL, 210.00, 0, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46');

-- 2.3 corregir portadas mal referenciadas
UPDATE `productos`
SET `portada` = REPLACE(`portada`, 'vistas/img/productos/', 'productos/portadas/')
WHERE `portada` LIKE 'vistas/img/productos/%';

-- 2.4 envio_gratis desde local
UPDATE `productos` SET `envio_gratis` = 1 WHERE `id` IN (36, 37, 59, 61, 162, 164, 165);

-- 2.5 banner_slides desde local
DELETE FROM `banner_slides`;

INSERT INTO `banner_slides` (`id`, `negocio_id`, `imagen`, `titulo`, `subtitulo`, `color_texto`, `boton_texto`, `boton_url`, `color_boton_fondo`, `color_boton_texto`, `posicion`, `orden`, `created_at`, `updated_at`, `categoria_id`) VALUES
(1, 2, 'negocios/slides/Zfji2YBqYdZP9w1bno1PnKtQ3w3P3Y07SSLabBIb.png', 'Sabor Único e Incomparable', 'Nuestra tierra produce el mejor café', NULL, 'Ver Productos', '/productos', '#d88506', NULL, 'center', 0, '2026-07-24 18:20:34', '2026-07-25 00:32:00', 87),
(2, 2, 'negocios/slides/afIbSxvBdwSfqpwa1ONpjW1OlZpIGmFIRVVRDFem.png', 'Tostadora de Cafe DCONDOR', 'Tostadora de Café Dcondor  "Hecho en Perú"', NULL, 'Ver Tostadoras', '/productos/buscar?categoria=&subcategoria=Tostadoras+de+Caf%C3%A9&marca=&precio_min=&precio_max=', '#d88506', NULL, 'left', 0, '2026-09-03 20:06:08', '2026-09-03 21:15:22', 92),
(3, 2, 'negocios/slides/AHS4fdFj08gEcUXVigc0q0TxS9kqvbVIdBE5l0gV.png', 'Café Mono Tingales', 'El Café que Despierta tus Sentidos', NULL, 'Ver Café', '/productos/buscar?subcategoria=Café%20Gourmet', '#d88506', NULL, 'left', 0, '2026-09-04 06:37:12', '2026-09-04 08:57:27', 87),
(4, 2, 'negocios/slides/xEPgRiLQk2jEQaKxe7QOtow0UiLtWFB2UjBUoBpn.jpg', 'De los Andes a tu taza, estés donde estés', 'El auténtico café peruano recorriendo cada rincón del país, hasta llegar a tu taza.', NULL, 'Ofertas', '/ofertas', '#d88506', NULL, 'right', 0, '2026-09-08 08:47:25', '2026-09-08 08:49:46', NULL);

-- ============================================================
-- 3. CORRECCIONES DE ENCODING (rubros)
-- ============================================================
UPDATE `rubros` SET `nombre` = 'Agronomía'     WHERE `id` = 1 AND `nombre` LIKE '%Agronom??%';
UPDATE `rubros` SET `nombre` = 'Plásticos'    WHERE `id` = 2 AND `nombre` LIKE '%Pl??sticos%';
UPDATE `rubros` SET `nombre` = 'Café'         WHERE `id` = 3 AND `nombre` LIKE '%Caf??%';
UPDATE `rubros` SET `nombre` = 'Metalmecánica' WHERE `id` = 4 AND `nombre` LIKE '%Metalmec??nica%';
UPDATE `rubros` SET `nombre` = 'Logística'    WHERE `id` = 5 AND `nombre` LIKE '%Log??stica%';
UPDATE `rubros` SET `nombre` = 'Insumos agrícolas' WHERE `id` = 6 AND `nombre` LIKE '%Insumos agr??colas%';

-- ============================================================
-- 4. CORRECCIONES DE ENCODING (nombres de usuarios)
-- ============================================================
UPDATE users SET nombre='Eglair', apellidos='Vascão Junior' WHERE id=12 AND apellidos LIKE '%Vasc??%';
UPDATE users SET nombre='Wilder', apellidos='Gaytán' WHERE id=21 AND apellidos LIKE '%Gayt??n%';
UPDATE users SET nombre='Guiliana', apellidos='Troya Velásquez' WHERE id=202 AND apellidos LIKE '%Vel??squez%';
UPDATE users SET nombre='Andrés', apellidos='Vega L' WHERE id=205 AND nombre LIKE '%Andr??s%';
UPDATE users SET nombre='Carlos', apellidos='Gómez Penedo' WHERE id=220 AND apellidos LIKE '%G??mez%';
UPDATE users SET nombre='Paco', apellidos='Girona Martínez' WHERE id=221 AND apellidos LIKE '%Mart??nez%';
UPDATE users SET nombre='Glendy', apellidos='Anabel Muñoz Escalante' WHERE id=227 AND apellidos LIKE '%Mu??oz%';
UPDATE users SET nombre='BYRON', apellidos='AGUSTÍN SOLÓRZANO' WHERE id=231 AND apellidos LIKE '%SOL??RZANO%';
UPDATE users SET nombre='Ascaso', apellidos='Perú' WHERE id=237 AND apellidos LIKE '%Per??%';
UPDATE users SET nombre='Sebastián', apellidos='Guzmán' WHERE id=239 AND nombre LIKE '%Sebasti??n%';
UPDATE users SET nombre='JULIO', apellidos='GARCÍA VICTORIO' WHERE id=241 AND apellidos LIKE '%GARC??A%';
UPDATE users SET nombre='Acner', apellidos='Peña Flores' WHERE id=248 AND apellidos LIKE '%Pe??a%';
UPDATE users SET nombre='MIGUEL', apellidos='CÁCERES' WHERE id=258 AND apellidos LIKE '%C??CERES%';
UPDATE users SET nombre='Jack', apellidos='Vásquez' WHERE id=274 AND apellidos LIKE '%V??squez%';
UPDATE users SET nombre='MIGUEL', apellidos='CÁCERES' WHERE id=276 AND apellidos LIKE '%C??CERES%';
UPDATE users SET email='info@cafesoriano.com' WHERE id=220 AND email LIKE '%Caf??soriano%';

-- ============================================================
-- 5. CUPÓN DE PRUEBA PRUEBA10 (idempotente)
-- ============================================================
INSERT INTO cupones (codigo, tipo, valor, min_compra, max_usos, usos_actuales, negocio_id, fecha_inicio, fecha_fin, activo, created_at, updated_at)
SELECT 'PRUEBA10', 'porcentaje', 10.00, 50.00, 100, 0, NULL, NULL, NULL, 1, NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM cupones WHERE codigo = 'PRUEBA10');

-- ============================================================
-- 6. VERIFICACIÓN
-- ============================================================
SELECT
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='productos' AND COLUMN_NAME='ofertaCategoria') AS tiene_ofertaCategoria,
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='productos' AND COLUMN_NAME='envio_gratis') AS tiene_envio_gratis,
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='productos' AND COLUMN_NAME='etiquetaOferta') AS tiene_etiquetaOferta,
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='categorias' AND COLUMN_NAME='orden') AS tiene_orden,
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='cupones' AND COLUMN_NAME='codigo') AS tiene_cupones,
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='comentarios' AND COLUMN_NAME='id_usuario') AS tiene_comentarios,
  (SELECT COUNT(*) FROM rubros WHERE nombre LIKE '%??%') AS rubros_rotos,
  (SELECT COUNT(*) FROM users WHERE nombre LIKE '%??%' OR apellidos LIKE '%??%') AS users_rotos,
  (SELECT COUNT(*) FROM cupones WHERE codigo='PRUEBA10') AS cupon_prueba10,
  (SELECT COUNT(*) FROM tipos_envio) AS tipos_envio;