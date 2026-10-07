SET NAMES utf8mb4;

-- Migraciones faltantes para remote cafe-peruano.com

-- 1. ofertaCategoria + ofertaSubcategoria en productos
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='productos' AND COLUMN_NAME='ofertaCategoria');
SET @s = IF(@c=0, 'ALTER TABLE `productos` ADD COLUMN `ofertaCategoria` INT NULL, ADD COLUMN `ofertaSubcategoria` INT NULL', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 2. etiquetaOferta en productos
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='productos' AND COLUMN_NAME='etiquetaOferta');
SET @s = IF(@c=0, 'ALTER TABLE `productos` ADD COLUMN `etiquetaOferta` VARCHAR(255) NULL', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 3. etiquetaOferta + fechaInicioOferta en categorias
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='categorias' AND COLUMN_NAME='etiquetaOferta');
SET @s = IF(@c=0, 'ALTER TABLE `categorias` ADD COLUMN `etiquetaOferta` VARCHAR(255) NULL, ADD COLUMN `fechaInicioOferta` DATE NULL', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 4. etiquetaOferta + fechaInicioOferta en subcategorias
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='subcategorias' AND COLUMN_NAME='etiquetaOferta');
SET @s = IF(@c=0, 'ALTER TABLE `subcategorias` ADD COLUMN `etiquetaOferta` VARCHAR(255) NULL, ADD COLUMN `fechaInicioOferta` DATE NULL', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 5. envio_gratis en productos
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='productos' AND COLUMN_NAME='envio_gratis');
SET @s = IF(@c=0, 'ALTER TABLE `productos` ADD COLUMN `envio_gratis` TINYINT(1) NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 6. orden en categorias
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='categorias' AND COLUMN_NAME='orden');
SET @s = IF(@c=0, 'ALTER TABLE `categorias` ADD COLUMN `orden` INT UNSIGNED NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 7. Tabla cupones
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

-- 8. Columnas cupon en orders
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='orders' AND COLUMN_NAME='cupon_id');
SET @s = IF(@c=0, 'ALTER TABLE `orders` ADD COLUMN `cupon_id` BIGINT UNSIGNED NULL, ADD COLUMN `cupon_codigo` VARCHAR(50) NULL, ADD COLUMN `cupon_descuento` DECIMAL(10,2) NULL', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- 9. Tabla tipos_envio
CREATE TABLE IF NOT EXISTS `tipos_envio` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `descripcion` TEXT NULL,
  `activo` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Tabla tarifas_envio
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

-- 11. Columnas slug + orden en tipos_envio (si no existen)
SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='tipos_envio' AND COLUMN_NAME='slug');
SET @s = IF(@c=0, 'ALTER TABLE `tipos_envio` ADD COLUMN `slug` VARCHAR(255) NOT NULL UNIQUE, ADD COLUMN `orden` INT NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

SET @c = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='tipos_envio' AND COLUMN_NAME='orden');
SET @s = IF(@c=0, 'ALTER TABLE `tipos_envio` ADD COLUMN `orden` INT NOT NULL DEFAULT 0', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- DATOS: tipos_envio (sincronizar desde local)
DELETE FROM `tarifas_envio`;
DELETE FROM `tipos_envio`;

INSERT INTO `tipos_envio` (`id`, `nombre`, `slug`, `descripcion`, `activo`, `orden`, `created_at`, `updated_at`) VALUES
(1, 'Nacional - Local', 'nacional-local', 'Envío dentro de Lima Metropolitana', 1, 1, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(2, 'Nacional - Provincias', 'nacional-provincias', 'Envío a provincias dentro del país', 1, 2, '2026-09-06 19:10:46', '2026-09-06 19:10:46'),
(3, 'Internacional', 'internacional', 'Envío internacional', 1, 3, '2026-09-06 19:10:46', '2026-09-06 19:10:46');

-- DATOS: tarifas_envio (sincronizar desde local)
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

-- DATOS: corregir portadas mal referenciadas (vistas/img/productos -> productos/portadas)
UPDATE `productos`
SET `portada` = REPLACE(`portada`, 'vistas/img/productos/', 'productos/portadas/')
WHERE `portada` LIKE 'vistas/img/productos/%';

-- DATOS: envio_gratis (sincronizar desde local)
UPDATE `productos` SET `envio_gratis` = 1 WHERE `id` IN (36, 37, 59, 61, 162, 164, 165);

-- DATOS: banner_slides (sincronizar desde local)
DELETE FROM `banner_slides`;

INSERT INTO `banner_slides` (`id`, `negocio_id`, `imagen`, `titulo`, `subtitulo`, `color_texto`, `boton_texto`, `boton_url`, `color_boton_fondo`, `color_boton_texto`, `posicion`, `orden`, `created_at`, `updated_at`, `categoria_id`) VALUES
(1, 2, 'negocios/slides/Zfji2YBqYdZP9w1bno1PnKtQ3w3P3Y07SSLabBIb.png', 'Sabor Único e Incomparable', 'Nuestra tierra produce el mejor café', NULL, 'Ver Productos', '/productos', '#d88506', NULL, 'center', 0, '2026-07-24 18:20:34', '2026-07-25 00:32:00', 87),
(2, 2, 'negocios/slides/afIbSxvBdwSfqpwa1ONpjW1OlZpIGmFIRVVRDFem.png', 'Tostadora de Cafe DCONDOR', 'Tostadora de Café Dcondor  "Hecho en Perú"', NULL, 'Ver Tostadoras', '/productos/buscar?categoria=&subcategoria=Tostadoras+de+Caf%C3%A9&marca=&precio_min=&precio_max=', '#d88506', NULL, 'left', 0, '2026-09-03 20:06:08', '2026-09-03 21:15:22', 92),
(3, 2, 'negocios/slides/AHS4fdFj08gEcUXVigc0q0TxS9kqvbVIdBE5l0gV.png', 'Café Mono Tingales', 'El Café que Despierta tus Sentidos', NULL, 'Ver Café', '/productos/buscar?subcategoria=Café%20Gourmet', '#d88506', NULL, 'left', 0, '2026-09-04 06:37:12', '2026-09-04 08:57:27', 87),
(4, 2, 'negocios/slides/xEPgRiLQk2jEQaKxe7QOtow0UiLtWFB2UjBUoBpn.jpg', 'De los Andes a tu taza, estés donde estés', 'El auténtico café peruano recorriendo cada rincón del país, hasta llegar a tu taza.', NULL, 'Ofertas', '/ofertas', '#d88506', NULL, 'right', 0, '2026-09-08 08:47:25', '2026-09-08 08:49:46', NULL);

-- VERIFICAR que las columnas existan
SELECT
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='productos' AND COLUMN_NAME='ofertaCategoria') AS tiene_ofertaCategoria,
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='productos' AND COLUMN_NAME='ofertaSubcategoria') AS tiene_ofertaSubcategoria,
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='productos' AND COLUMN_NAME='envio_gratis') AS tiene_envio_gratis,
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='productos' AND COLUMN_NAME='etiquetaOferta') AS tiene_etiquetaOferta,
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='categorias' AND COLUMN_NAME='orden') AS tiene_orden,
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='categorias' AND COLUMN_NAME='etiquetaOferta') AS tiene_cat_etiqueta,
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='cupones' AND COLUMN_NAME='codigo') AS tiene_cupones;
