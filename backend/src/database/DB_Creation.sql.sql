-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: newasimadrid
-- ------------------------------------------------------
-- Server version	8.0.34

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `centros`
--

DROP TABLE IF EXISTS `centros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `centros` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `published_at` datetime(6) DEFAULT NULL,
  `created_by_id` int unsigned DEFAULT NULL,
  `updated_by_id` int unsigned DEFAULT NULL,
  `codigo` bigint DEFAULT NULL,
  `titularidad` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `titular` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion_via_tipo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion_via_nombre` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion_via_numero` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cod_postal` int DEFAULT NULL,
  `telf_1` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telf_2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telf_3` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telf_4` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `web` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `centros_created_by_id_fk` (`created_by_id`),
  KEY `centros_updated_by_id_fk` (`updated_by_id`),
  CONSTRAINT `centros_created_by_id_fk` FOREIGN KEY (`created_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `centros_updated_by_id_fk` FOREIGN KEY (`updated_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centros`
--

LOCK TABLES `centros` WRITE;
/*!40000 ALTER TABLE `centros` DISABLE KEYS */;
/*!40000 ALTER TABLE `centros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `centros_destinatarios_links`
--

DROP TABLE IF EXISTS `centros_destinatarios_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `centros_destinatarios_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `centro_id` int unsigned DEFAULT NULL,
  `destinatario_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `centros_destinatario_links_unique` (`centro_id`,`destinatario_id`),
  KEY `centros_destinatario_links_fk` (`centro_id`),
  KEY `centros_destinatario_links_inv_fk` (`destinatario_id`),
  CONSTRAINT `centros_destinatario_links_fk` FOREIGN KEY (`centro_id`) REFERENCES `centros` (`id`) ON DELETE CASCADE,
  CONSTRAINT `centros_destinatario_links_inv_fk` FOREIGN KEY (`destinatario_id`) REFERENCES `destinatarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centros_destinatarios_links`
--

LOCK TABLES `centros_destinatarios_links` WRITE;
/*!40000 ALTER TABLE `centros_destinatarios_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `centros_destinatarios_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `centros_municipios_links`
--

DROP TABLE IF EXISTS `centros_municipios_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `centros_municipios_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `centro_id` int unsigned DEFAULT NULL,
  `municipio_id` int unsigned DEFAULT NULL,
  `centro_order` double unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `centros_municipio_links_unique` (`centro_id`,`municipio_id`),
  KEY `centros_municipio_links_fk` (`centro_id`),
  KEY `centros_municipio_links_inv_fk` (`municipio_id`),
  KEY `centros_municipio_links_order_inv_fk` (`centro_order`),
  CONSTRAINT `centros_municipio_links_fk` FOREIGN KEY (`centro_id`) REFERENCES `centros` (`id`) ON DELETE CASCADE,
  CONSTRAINT `centros_municipio_links_inv_fk` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centros_municipios_links`
--

LOCK TABLES `centros_municipios_links` WRITE;
/*!40000 ALTER TABLE `centros_municipios_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `centros_municipios_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `centros_typecentros_links`
--

DROP TABLE IF EXISTS `centros_typecentros_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `centros_typecentros_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `centro_id` int unsigned DEFAULT NULL,
  `tipo_centro_id` int unsigned DEFAULT NULL,
  `centro_order` double unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `centros_tipo_centro_links_unique` (`centro_id`,`tipo_centro_id`),
  KEY `centros_tipo_centro_links_fk` (`centro_id`),
  KEY `centros_tipo_centro_links_inv_fk` (`tipo_centro_id`),
  KEY `centros_tipo_centro_links_order_inv_fk` (`centro_order`),
  CONSTRAINT `centros_tipo_centro_links_fk` FOREIGN KEY (`centro_id`) REFERENCES `centros` (`id`) ON DELETE CASCADE,
  CONSTRAINT `centros_tipo_centro_links_inv_fk` FOREIGN KEY (`tipo_centro_id`) REFERENCES `typecentros` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `centros_typecentros_links`
--

LOCK TABLES `centros_typecentros_links` WRITE;
/*!40000 ALTER TABLE `centros_typecentros_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `centros_typecentros_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `correos`
--

DROP TABLE IF EXISTS `correos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `correos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `direccion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `servidor_smtp` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `puerto` int DEFAULT NULL,
  `user` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `limite_correos` int DEFAULT NULL,
  `limite_timepo` int DEFAULT NULL,
  `hora_inicio` time(3) DEFAULT NULL,
  `hora_fin` time(3) DEFAULT NULL,
  `limite_tamanio` int DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `published_at` datetime(6) DEFAULT NULL,
  `created_by_id` int unsigned DEFAULT NULL,
  `updated_by_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `correos_created_by_id_fk` (`created_by_id`),
  KEY `correos_updated_by_id_fk` (`updated_by_id`),
  CONSTRAINT `correos_created_by_id_fk` FOREIGN KEY (`created_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `correos_updated_by_id_fk` FOREIGN KEY (`updated_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `correos`
--

LOCK TABLES `correos` WRITE;
/*!40000 ALTER TABLE `correos` DISABLE KEYS */;
/*!40000 ALTER TABLE `correos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `correos_users_links`
--

DROP TABLE IF EXISTS `correos_users_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `correos_users_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `correo_id` int unsigned DEFAULT NULL,
  `user_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correos_admin_links_unique` (`correo_id`,`user_id`),
  KEY `correos_admin_links_fk` (`correo_id`),
  KEY `correos_admin_links_inv_fk` (`user_id`),
  CONSTRAINT `correos_admin_links_fk` FOREIGN KEY (`correo_id`) REFERENCES `correos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `correos_admin_links_inv_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `correos_users_links`
--

LOCK TABLES `correos_users_links` WRITE;
/*!40000 ALTER TABLE `correos_users_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `correos_users_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `destinatarios`
--

DROP TABLE IF EXISTS `destinatarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `destinatarios` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `published_at` datetime(6) DEFAULT NULL,
  `created_by_id` int unsigned DEFAULT NULL,
  `updated_by_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `destinatarios_created_by_id_fk` (`created_by_id`),
  KEY `destinatarios_updated_by_id_fk` (`updated_by_id`),
  CONSTRAINT `destinatarios_created_by_id_fk` FOREIGN KEY (`created_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `destinatarios_updated_by_id_fk` FOREIGN KEY (`updated_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `destinatarios`
--

LOCK TABLES `destinatarios` WRITE;
/*!40000 ALTER TABLE `destinatarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `destinatarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distritos`
--

DROP TABLE IF EXISTS `distritos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `distritos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `published_at` datetime(6) DEFAULT NULL,
  `created_by_id` int unsigned DEFAULT NULL,
  `updated_by_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `distritos_created_by_id_fk` (`created_by_id`),
  KEY `distritos_updated_by_id_fk` (`updated_by_id`),
  CONSTRAINT `distritos_created_by_id_fk` FOREIGN KEY (`created_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `distritos_updated_by_id_fk` FOREIGN KEY (`updated_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distritos`
--

LOCK TABLES `distritos` WRITE;
/*!40000 ALTER TABLE `distritos` DISABLE KEYS */;
/*!40000 ALTER TABLE `distritos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `distritos_municipios_links`
--

DROP TABLE IF EXISTS `distritos_municipios_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `distritos_municipios_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `distrito_id` int unsigned DEFAULT NULL,
  `municipio_id` int unsigned DEFAULT NULL,
  `distrito_order` double unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `distritos_municipio_links_unique` (`distrito_id`,`municipio_id`),
  KEY `distritos_municipio_links_fk` (`distrito_id`),
  KEY `distritos_municipio_links_inv_fk` (`municipio_id`),
  KEY `distritos_municipio_links_order_inv_fk` (`distrito_order`),
  CONSTRAINT `distritos_municipio_links_fk` FOREIGN KEY (`distrito_id`) REFERENCES `distritos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `distritos_municipio_links_inv_fk` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `distritos_municipios_links`
--

LOCK TABLES `distritos_municipios_links` WRITE;
/*!40000 ALTER TABLE `distritos_municipios_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `distritos_municipios_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `listas`
--

DROP TABLE IF EXISTS `listas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `listas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `published_at` datetime(6) DEFAULT NULL,
  `created_by_id` int unsigned DEFAULT NULL,
  `updated_by_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `listas_created_by_id_fk` (`created_by_id`),
  KEY `listas_updated_by_id_fk` (`updated_by_id`),
  CONSTRAINT `listas_created_by_id_fk` FOREIGN KEY (`created_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `listas_updated_by_id_fk` FOREIGN KEY (`updated_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `listas`
--

LOCK TABLES `listas` WRITE;
/*!40000 ALTER TABLE `listas` DISABLE KEYS */;
/*!40000 ALTER TABLE `listas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `listas_destinatarios_links`
--

DROP TABLE IF EXISTS `listas_destinatarios_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `listas_destinatarios_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lista_id` int unsigned DEFAULT NULL,
  `destinatario_id` int unsigned DEFAULT NULL,
  `destinatario_order` double unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `listas_destinatarios_links_unique` (`lista_id`,`destinatario_id`),
  KEY `listas_destinatarios_links_fk` (`lista_id`),
  KEY `listas_destinatarios_links_inv_fk` (`destinatario_id`),
  KEY `listas_destinatarios_links_order_fk` (`destinatario_order`),
  CONSTRAINT `listas_destinatarios_links_fk` FOREIGN KEY (`lista_id`) REFERENCES `listas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `listas_destinatarios_links_inv_fk` FOREIGN KEY (`destinatario_id`) REFERENCES `destinatarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `listas_destinatarios_links`
--

LOCK TABLES `listas_destinatarios_links` WRITE;
/*!40000 ALTER TABLE `listas_destinatarios_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `listas_destinatarios_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mails`
--

DROP TABLE IF EXISTS `mails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mails` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb4_general_ci,
  `asunto` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `published_at` datetime(6) DEFAULT NULL,
  `created_by_id` int unsigned DEFAULT NULL,
  `updated_by_id` int unsigned DEFAULT NULL,
  `envio` datetime(6) DEFAULT NULL,
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `mails_created_by_id_fk` (`created_by_id`),
  KEY `mails_updated_by_id_fk` (`updated_by_id`),
  CONSTRAINT `mails_created_by_id_fk` FOREIGN KEY (`created_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `mails_updated_by_id_fk` FOREIGN KEY (`updated_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mails`
--

LOCK TABLES `mails` WRITE;
/*!40000 ALTER TABLE `mails` DISABLE KEYS */;
INSERT INTO `mails` VALUES (41,'Me gustan los platanos','esto es una verdadera maravilla','draft',NULL,NULL,NULL,NULL,NULL,NULL,1),(42,'UwuOwo','Menudo asuntazo','sent',NULL,NULL,NULL,NULL,NULL,NULL,0),(43,'UwuOwo','Menudo asuntazo','trash',NULL,NULL,NULL,NULL,NULL,NULL,0),(44,'UwuOwo','Menudo asuntazo','snoozed',NULL,NULL,NULL,NULL,NULL,NULL,0),(45,'UwuOwo','Menudo asuntazo','sent',NULL,NULL,NULL,NULL,NULL,NULL,0),(46,'UwuOwo','Menudo asuntazo','scheduled',NULL,NULL,NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `mails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mails_destinatarios_links`
--

DROP TABLE IF EXISTS `mails_destinatarios_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mails_destinatarios_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `mail_id` int unsigned DEFAULT NULL,
  `destinatario_id` int unsigned DEFAULT NULL,
  `destinatario_order` double unsigned DEFAULT NULL,
  `type` enum('n','cc','cco') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mails_destinatarios_links_unique` (`mail_id`,`destinatario_id`),
  KEY `mails_destinatarios_links_fk` (`mail_id`),
  KEY `mails_destinatarios_links_inv_fk` (`destinatario_id`),
  KEY `mails_destinatarios_links_order_fk` (`destinatario_order`),
  CONSTRAINT `mails_destinatarios_links_fk` FOREIGN KEY (`mail_id`) REFERENCES `mails` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mails_destinatarios_links_inv_fk` FOREIGN KEY (`destinatario_id`) REFERENCES `destinatarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mails_destinatarios_links`
--

LOCK TABLES `mails_destinatarios_links` WRITE;
/*!40000 ALTER TABLE `mails_destinatarios_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `mails_destinatarios_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `municipios`
--

DROP TABLE IF EXISTS `municipios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `municipios` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `published_at` datetime(6) DEFAULT NULL,
  `created_by_id` int unsigned DEFAULT NULL,
  `updated_by_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `municipios_created_by_id_fk` (`created_by_id`),
  KEY `municipios_updated_by_id_fk` (`updated_by_id`),
  CONSTRAINT `municipios_created_by_id_fk` FOREIGN KEY (`created_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `municipios_updated_by_id_fk` FOREIGN KEY (`updated_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `municipios`
--

LOCK TABLES `municipios` WRITE;
/*!40000 ALTER TABLE `municipios` DISABLE KEYS */;
/*!40000 ALTER TABLE `municipios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `municipios_zonas_links`
--

DROP TABLE IF EXISTS `municipios_zonas_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `municipios_zonas_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `municipio_id` int unsigned DEFAULT NULL,
  `zona_id` int unsigned DEFAULT NULL,
  `municipio_order` double unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `municipios_zona_links_unique` (`municipio_id`,`zona_id`),
  KEY `municipios_zona_links_fk` (`municipio_id`),
  KEY `municipios_zona_links_inv_fk` (`zona_id`),
  KEY `municipios_zona_links_order_inv_fk` (`municipio_order`),
  CONSTRAINT `municipios_zona_links_fk` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `municipios_zona_links_inv_fk` FOREIGN KEY (`zona_id`) REFERENCES `zonas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `municipios_zonas_links`
--

LOCK TABLES `municipios_zonas_links` WRITE;
/*!40000 ALTER TABLE `municipios_zonas_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `municipios_zonas_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `created_by_id` int unsigned DEFAULT NULL,
  `updated_by_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'auth.changePassword','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(2,'auth.callback','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(3,'auth.connect','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(4,'auth.forgotPassword','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(5,'auth.resetPassword','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(6,'auth.register','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(7,'auth.emailConfirmation','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(8,'auth.sendEmailConfirmation','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(9,'centros.create','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(10,'centros.update','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(11,'centros.find','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(12,'centros.findOne','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(13,'centros.delete','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(14,'correos.create','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(15,'correos.update','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(16,'correos.find','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(17,'correos.findOne','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(18,'correos.delete','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(19,'destinatarios.create','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(20,'destinatarios.update','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(21,'destinatarios.find','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(22,'destinatarios.findOne','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(23,'destinatarios.delete','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(24,'distritos.create','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(25,'distritos.update','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(26,'distritos.find','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(27,'distritos.findOne','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(28,'distritos.delete','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(29,'listas.create','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(30,'listas.update','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(31,'listas.find','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(32,'listas.findOne','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(33,'listas.delete','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(34,'mails.send','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(35,'mails.destinatario','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(36,'mails.abort','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(37,'mails.create','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(38,'mails.update','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(39,'mails.find','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(40,'mails.findOne','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(41,'mails.delete','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(42,'municipios.create','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(43,'municipios.update','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(44,'municipios.find','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(45,'municipios.findOne','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(46,'municipios.delete','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(47,'queue.create','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(48,'queue.update','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(49,'queue.find','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(50,'queue.findOne','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(51,'queue.delete','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(52,'typecentro.create','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(53,'typecentro.update','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(54,'typecentro.find','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(55,'typecentro.findOne','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(56,'typecentro.delete','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(57,'permissions.create','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(58,'permissions.update','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(59,'permissions.find','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(60,'permissions.findOne','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(61,'permissions.delete','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(62,'roles.create','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(63,'roles.update','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(64,'roles.find','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(65,'roles.findOne','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(66,'roles.delete','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(67,'users.me','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(68,'users.create','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(69,'users.update','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(70,'users.find','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(71,'users.findOne','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(72,'users.delete','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(73,'zonas.create','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(74,'zonas.update','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(75,'zonas.find','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(76,'zonas.findOne','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(77,'zonas.delete','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions_roles_links`
--

DROP TABLE IF EXISTS `permissions_roles_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions_roles_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` int unsigned DEFAULT NULL,
  `role_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `up_permissions_role_links_fk` (`permission_id`),
  KEY `FK_rol_perm_idx` (`role_id`),
  CONSTRAINT `FK_perm_rol` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_rol_perm` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions_roles_links`
--

LOCK TABLES `permissions_roles_links` WRITE;
/*!40000 ALTER TABLE `permissions_roles_links` DISABLE KEYS */;
INSERT INTO `permissions_roles_links` VALUES (1,2,1),(2,3,1),(3,4,1),(4,5,1),(6,7,1),(7,8,1),(8,1,2),(9,11,2),(10,16,2),(11,19,2),(12,20,2),(13,21,2),(14,23,2),(15,31,2),(16,34,2),(17,35,2),(18,36,2),(19,37,2),(20,38,2),(21,39,2),(22,40,2),(23,49,2),(24,67,2),(25,1,4),(33,9,4),(34,10,4),(35,11,4),(36,12,4),(37,13,4),(38,14,4),(39,15,4),(40,16,4),(41,17,4),(42,18,4),(43,19,4),(44,20,4),(45,21,4),(46,22,4),(47,23,4),(48,24,4),(49,25,4),(50,26,4),(51,27,4),(52,28,4),(53,29,4),(54,30,4),(55,31,4),(56,32,4),(57,33,4),(58,34,4),(59,35,4),(60,36,4),(61,37,4),(62,38,4),(63,39,4),(64,40,4),(65,41,4),(66,42,4),(68,44,4),(69,45,4),(70,46,4),(71,47,4),(72,48,4),(73,49,4),(74,50,4),(75,51,4),(76,52,4),(77,53,4),(78,54,4),(79,55,4),(80,56,4),(81,57,4),(82,58,4),(83,59,4),(84,60,4),(85,61,4),(86,62,4),(87,63,4),(88,64,4),(89,65,4),(90,66,4),(91,67,4),(92,68,4),(93,69,4),(94,70,4),(95,71,4),(96,72,4),(97,73,4),(98,74,4),(99,75,4),(100,76,4),(101,77,4);
/*!40000 ALTER TABLE `permissions_roles_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queue`
--

DROP TABLE IF EXISTS `queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `queue` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `published_at` datetime(6) DEFAULT NULL,
  `created_by_id` int unsigned DEFAULT NULL,
  `updated_by_id` int unsigned DEFAULT NULL,
  `timestamp` datetime(6) DEFAULT NULL,
  `type` enum('n','cc','cco') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'n',
  `destinatario` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `queue_created_by_id_fk` (`created_by_id`),
  KEY `queue_updated_by_id_fk` (`updated_by_id`),
  CONSTRAINT `bandeja_de_salidas_created_by_id_fk` FOREIGN KEY (`created_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `bandeja_de_salidas_updated_by_id_fk` FOREIGN KEY (`updated_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queue`
--

LOCK TABLES `queue` WRITE;
/*!40000 ALTER TABLE `queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `queue_mails_links`
--

DROP TABLE IF EXISTS `queue_mails_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `queue_mails_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `queue_id` int unsigned DEFAULT NULL,
  `mail_id` int unsigned DEFAULT NULL,
  `mail_order` double unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `queue_mail_links_unique` (`queue_id`,`mail_id`),
  KEY `queue_mail_links_fk` (`queue_id`),
  KEY `queue_mail_links_inv_fk` (`mail_id`),
  KEY `queue_mail_links_order_fk` (`mail_order`),
  CONSTRAINT `queue_mail_links_inv_fk` FOREIGN KEY (`mail_id`) REFERENCES `mails` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `queue_mails_links`
--

LOCK TABLES `queue_mails_links` WRITE;
/*!40000 ALTER TABLE `queue_mails_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `queue_mails_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `created_by_id` int unsigned DEFAULT NULL,
  `updated_by_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Public','Default role given to unauthenticated user.','public','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(2,'Authenticathed','Default role given to authenticated user.','authenticathed','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(3,'Admin','Role given to admin users.','admin','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL),(4,'SuperAdmin','Role given to the superadmin user.','superadmin','2023-11-07 19:51:13.000000','2023-11-07 19:51:13.000000',NULL,NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `typecentros`
--

DROP TABLE IF EXISTS `typecentros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `typecentros` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `siglas` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `es_de_interes` tinyint(1) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `published_at` datetime(6) DEFAULT NULL,
  `created_by_id` int unsigned DEFAULT NULL,
  `updated_by_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_centros_created_by_id_fk` (`created_by_id`),
  KEY `tipo_centros_updated_by_id_fk` (`updated_by_id`),
  CONSTRAINT `tipo_centros_created_by_id_fk` FOREIGN KEY (`created_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `tipo_centros_updated_by_id_fk` FOREIGN KEY (`updated_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `typecentros`
--

LOCK TABLES `typecentros` WRITE;
/*!40000 ALTER TABLE `typecentros` DISABLE KEYS */;
/*!40000 ALTER TABLE `typecentros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reset_password_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `confirmed` tinyint(1) DEFAULT NULL,
  `blocked` tinyint(1) DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `created_by_id` int unsigned DEFAULT NULL,
  `updated_by_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `up_users_created_by_id_fk` (`created_by_id`),
  KEY `up_users_updated_by_id_fk` (`updated_by_id`),
  CONSTRAINT `up_users_created_by_id_fk` FOREIGN KEY (`created_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `up_users_updated_by_id_fk` FOREIGN KEY (`updated_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (3,'Javer Herrero','javito774@gmail.com',NULL,'$2y$10$5o/rt5Mkhw3HTeFS7i2JA.sYdIgwVnxFFIMvAl2RldI0.hUrBvS3i',NULL,NULL,0,0,'2023-10-10 21:25:23.000000','2023-10-10 21:25:23.000000',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_roles_links`
--

DROP TABLE IF EXISTS `users_roles_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_roles_links` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int unsigned DEFAULT NULL,
  `role_id` int unsigned DEFAULT NULL,
  `user_order` double unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `up_users_role_links_unique` (`user_id`,`role_id`),
  KEY `up_users_role_links_fk` (`user_id`),
  KEY `up_users_role_links_inv_fk` (`role_id`),
  KEY `up_users_role_links_order_inv_fk` (`user_order`),
  CONSTRAINT `up_users_role_links_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `up_users_role_links_inv_fk` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_roles_links`
--

LOCK TABLES `users_roles_links` WRITE;
/*!40000 ALTER TABLE `users_roles_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_roles_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zonas`
--

DROP TABLE IF EXISTS `zonas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zonas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime(6) DEFAULT NULL,
  `updated_at` datetime(6) DEFAULT NULL,
  `published_at` datetime(6) DEFAULT NULL,
  `created_by_id` int unsigned DEFAULT NULL,
  `updated_by_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zonas_created_by_id_fk` (`created_by_id`),
  KEY `zonas_updated_by_id_fk` (`updated_by_id`),
  CONSTRAINT `zonas_created_by_id_fk` FOREIGN KEY (`created_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `zonas_updated_by_id_fk` FOREIGN KEY (`updated_by_id`) REFERENCES `admin_users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zonas`
--

LOCK TABLES `zonas` WRITE;
/*!40000 ALTER TABLE `zonas` DISABLE KEYS */;
/*!40000 ALTER TABLE `zonas` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-11-07 19:56:18
