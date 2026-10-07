-- Migracion manual equivalente a: 2026_08_21_180943_create_categoria_subcategoria_table
-- Ejecutar COMPLETO y EN ORDEN en la base de datos: equipyma_25

-- 1) Crear tabla pivote
CREATE TABLE IF NOT EXISTS `categoria_subcategoria` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `categoria_id` BIGINT UNSIGNED NOT NULL,
  `subcategoria_id` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categoria_subcategoria_unique` (`categoria_id`, `subcategoria_id`),
  CONSTRAINT `cs_categoria_id_foreign` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cs_subcategoria_id_foreign` FOREIGN KEY (`subcategoria_id`) REFERENCES `subcategorias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2) Backfill: cada subcategoria pertenece a su categoria primaria (id_categoria)
INSERT IGNORE INTO `categoria_subcategoria` (`categoria_id`, `subcategoria_id`)
SELECT `id_categoria`, `id` FROM `subcategorias` WHERE `id_categoria` IS NOT NULL;

-- 3) Mover productos de los duplicados vacios hacia los canonicos (si existen)
UPDATE `productos` SET `subcategoria_id` = 129 WHERE `subcategoria_id` = 187;
UPDATE `productos` SET `subcategoria_id` = 130 WHERE `subcategoria_id` = 188;
UPDATE `productos` SET `subcategoria_id` = 133 WHERE `subcategoria_id` = 189;
UPDATE `productos` SET `subcategoria_id` = 134 WHERE `subcategoria_id` = 190;
UPDATE `productos` SET `subcategoria_id` = 136 WHERE `subcategoria_id` = 191;

-- 4) Compartir los canonicos con Zona Cafeteria (103) y eliminar duplicados
INSERT IGNORE INTO `categoria_subcategoria` (`categoria_id`, `subcategoria_id`) VALUES
(103, 129), (103, 130), (103, 133), (103, 134), (103, 136);

DELETE FROM `subcategoria_negocio` WHERE `subcategoria_id` IN (187, 188, 189, 190, 191);
DELETE FROM `subcategorias` WHERE `id` IN (187, 188, 189, 190, 191);

-- 5) Registrar en la tabla migrations para que un futuro "php artisan migrate" no la reintente
--    (si ya aparece registrada, este INSERT se ignora por el unique key muk)
INSERT IGNORE INTO `migrations` (`migration`, `batch`)
SELECT '2026_08_21_180943_create_categoria_subcategoria_table', COALESCE(MAX(`batch`), 0) + 1
FROM `migrations`;

-- Verificacion
SELECT COUNT(*) AS pares_creados FROM `categoria_subcategoria`;
