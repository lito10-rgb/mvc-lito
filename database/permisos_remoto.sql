-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: mqyeq
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permisos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clave` varchar(255) NOT NULL,
  `etiqueta` varchar(255) NOT NULL,
  `modulo` varchar(255) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permisos_clave_unique` (`clave`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
INSERT INTO `permisos` VALUES (1,'dashboard.ver','Ver Dashboard','dashboard','Acceso al dashboard principal',NULL,NULL),(2,'productos.ver','Ver Productos','productos','Listar y ver productos',NULL,NULL),(3,'productos.crear','Crear Productos','productos','Crear nuevos productos',NULL,NULL),(4,'productos.editar','Editar Productos','productos','Editar productos existentes',NULL,NULL),(5,'productos.eliminar','Eliminar Productos','productos','Eliminar productos',NULL,NULL),(6,'ofertas.gestionar','Gestionar Ofertas','ofertas','Administrar ofertas y herencia de descuentos',NULL,NULL),(7,'cupones.gestionar','Gestionar Cupones','cupones','Crear y administrar cupones',NULL,NULL),(8,'categorias.gestionar','Gestionar Categorías','categorias','CRUD de categorías',NULL,NULL),(9,'subcategorias.gestionar','Gestionar Subcategorías','categorias','CRUD de subcategorías',NULL,NULL),(10,'marcas.gestionar','Gestionar Marcas','marcas','CRUD de marcas',NULL,NULL),(11,'catalogos.ver','Ver Catálogo PDF','catalogos','Ver y descargar catálogo PDF',NULL,NULL),(12,'proveedores.gestionar','Gestionar Proveedores','proveedores','CRUD de proveedores',NULL,NULL),(13,'cotizaciones.gestionar','Gestionar Cotizaciones','cotizaciones','Crear, editar, imprimir y enviar cotizaciones',NULL,NULL),(14,'plantillas.gestionar','Gestionar Plantillas Correo','correos','CRUD de plantillas de correo',NULL,NULL),(15,'condiciones.gestionar','Gestionar Condiciones','cotizaciones','CRUD de condiciones comerciales',NULL,NULL),(16,'logos.gestionar','Gestionar Logos Empresa','negocios','CRUD de logos de empresa',NULL,NULL),(17,'exim.gestionar','Gestionar EXIM Exportaciones','exim','Acceso al módulo de exportaciones EXIM',NULL,NULL),(18,'visitas.gestionar','Gestionar Visitas Técnicas','visitas','Ver y administrar visitas técnicas',NULL,NULL),(19,'suscripciones.gestionar','Gestionar Suscripciones','suscripciones','Ver y administrar suscripciones/boletín',NULL,NULL),(20,'concursos.gestionar','Gestionar Concursos','concursos','Administrar concursos y sorteo en vivo',NULL,NULL),(21,'pedidos.gestionar','Gestionar Pedidos','pedidos','Ver, editar y actualizar pedidos',NULL,NULL),(22,'envios.gestionar','Gestionar Envíos','envios','Tipos de envío y tarifas',NULL,NULL),(23,'usuarios.ver','Ver Usuarios','usuarios','Listar y ver usuarios',NULL,NULL),(24,'usuarios.gestionar','Gestionar Usuarios','usuarios','Crear, editar y eliminar usuarios y asignar roles',NULL,NULL),(25,'permisos.gestionar','Gestionar Permisos','usuarios','Administrar permisos por rol y por usuario',NULL,NULL),(26,'rubros.gestionar','Gestionar Rubros','usuarios','CRUD de rubros',NULL,NULL),(27,'posts.gestionar','Gestionar Posts / Blog','contenido','CRUD de posts del blog',NULL,NULL),(28,'negocios.gestionar','Gestionar Negocios','negocios','Configurar negocios/sitios',NULL,NULL);
INSERT INTO `permisos` VALUES (29,'ubigeo.gestionar','Gestionar Ubigeo','usuarios','CRUD de países, departamentos, provincias y distritos',NULL,NULL);
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso_role`
--

DROP TABLE IF EXISTS `permiso_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permiso_role` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permiso_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `permiso_role_role_id_permiso_id_index` (`role_id`,`permiso_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso_role`
--

LOCK TABLES `permiso_role` WRITE;
/*!40000 ALTER TABLE `permiso_role` DISABLE KEYS */;
INSERT INTO `permiso_role` VALUES (31,1,3),(35,2,3),(33,3,3),(34,4,3),(32,6,3),(29,11,3),(30,13,3),(1,1,4),(2,2,4),(3,3,4),(4,4,4),(5,5,4),(6,6,4),(7,7,4),(8,8,4),(9,9,4),(10,10,4),(11,11,4),(12,12,4),(13,13,4),(14,14,4),(15,15,4),(16,16,4),(17,17,4),(18,18,4),(19,19,4),(20,20,4),(21,21,4),(22,22,4),(23,23,4),(24,24,4),(25,25,4),(26,26,4),(27,27,4),(28,28,4),(38,29,4),(37,1,5),(36,13,5);
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-12 17:28:47
