-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: prefecture
-- ------------------------------------------------------
-- Server version	8.0.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `arrondissements`
--

DROP TABLE IF EXISTS `arrondissements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `arrondissements` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lib_arrondissement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `departements_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `arrondissements_departements_id_foreign` (`departements_id`),
  CONSTRAINT `arrondissements_departements_id_foreign` FOREIGN KEY (`departements_id`) REFERENCES `departements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `arrondissements`
--

LOCK TABLES `arrondissements` WRITE;
/*!40000 ALTER TABLE `arrondissements` DISABLE KEYS */;
INSERT INTO `arrondissements` VALUES (1,'FOUNDOU-FOUNDOU',3,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(2,'YOULOU-POUNGUI',3,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(3,'BOUALI',3,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(4,'ITSIBOU',3,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(5,'SIBITI',4,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(6,'LUMUMBA',2,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(7,'MVOUMVOU',2,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(8,'TIE-TIE',2,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(9,'LOANDJILI',2,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(10,'MONGO-POUKOU',2,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(11,'NGOYO',2,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(12,'DJAMBALA',5,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(13,'KINKALA',6,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(14,'KINTELE',6,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(15,'IMPFONDO',7,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(16,'OWANDO',8,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(17,'OYO',8,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(18,'EWO',9,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(19,'OUESSO',10,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(20,'LOANGO',11,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(21,'MAKELEKELE',1,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(22,'BACONGO',1,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(23,'POTO-POTO',1,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(24,'MOUNGALI',1,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(25,'OUENZE',1,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(26,'TALANGAI',1,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(27,'MFILOU',1,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(28,'MADIBOU',1,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(29,'DJIRI',1,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(30,'MADINGOU',12,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(31,'MWANA-NTO',12,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(32,'SOULOUKA',12,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(33,'DOLISIE',3,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL),(34,'LOCALITE INDEFINIE',1,'2023-06-03 15:04:36','2023-06-03 15:04:36',NULL);
/*!40000 ALTER TABLE `arrondissements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contentieuxes`
--

DROP TABLE IF EXISTS `contentieuxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contentieuxes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `demandes_id` bigint unsigned NOT NULL,
  `motifs_id` int NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contentieuxes`
--

LOCK TABLES `contentieuxes` WRITE;
/*!40000 ALTER TABLE `contentieuxes` DISABLE KEYS */;
INSERT INTO `contentieuxes` VALUES (1,4,1,'Passeport fabriqué en cybercafé','2023-06-09 15:17:11','2023-06-09 15:17:11');
/*!40000 ALTER TABLE `contentieuxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demandes`
--

DROP TABLE IF EXISTS `demandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demandes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `impetrants_id` bigint unsigned NOT NULL,
  `numero_ancien_document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `validite` enum('1','3','5') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `etat_civil` enum('Célibataire','Marié(e)','Divorcé(e)','Veuf(-ve)') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_emission` date DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `quartiers_id` int unsigned DEFAULT NULL,
  `avenue_rue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `profession` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employeur` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse_employeur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_demande` enum('Carte de résident temporaire','Visa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_demande` date NOT NULL,
  `statut_demande` enum('En attente d''approbation','Approuvée','Rejetée','Envoyée au contentieux','Renvoyée à la saisie pour modification','Livrée') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'En attente d''approbation',
  `created_by` bigint unsigned DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approval_date` timestamp NULL DEFAULT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `demandes_impetrants_id_foreign` (`impetrants_id`),
  KEY `demandes_approved_by_foreign` (`approved_by`),
  KEY `demandes_created_by_foreign` (`created_by`),
  CONSTRAINT `demandes_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `demandes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `demandes_impetrants_id_foreign` FOREIGN KEY (`impetrants_id`) REFERENCES `impetrants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demandes`
--

LOCK TABLES `demandes` WRITE;
/*!40000 ALTER TABLE `demandes` DISABLE KEYS */;
INSERT INTO `demandes` VALUES (4,5,NULL,'demandes/kGrBO2ir1rabtLTHlSM90LcIYOwRriy2jMKliAda.png','00023145696','3','Célibataire','2023-06-09','2026-06-08',1,'Mbamou','103','055259519','mukinayiseth@gmail.com','Analyste Programmeur','ACSI','M\'pila','Carte de résident temporaire','2023-06-08','Approuvée',NULL,NULL,NULL,'001_08062023_0000000001','2023-06-08 21:45:36','2023-06-09 15:54:49',NULL),(5,15,NULL,'demandes/m0RABeyQFfs3iovpEFBYQsyXlVWWNFAmPlonkrd1.jpg','00023145697','1','Marié(e)','2023-06-08','2024-06-07',1,'Mbamou','103','055259517','mukinayibenejah@gmail.com','Caissière','Ecole Chretienne Daniel','Texaco','Carte de résident temporaire','2023-06-09','Approuvée',NULL,NULL,NULL,'001_08062023_0000000002','2023-06-09 11:27:45','2023-06-09 16:26:14',NULL),(6,15,NULL,'demandes/wApcVFLPPiaXwmFbhZIa83hDtI54xc8uVtyor4cp.jpg',NULL,'1','Marié(e)',NULL,NULL,3,'Bassoko','65','053108456','ben@gmail.com','Caissière','Ecole Chretienne Daniel','Texaco','Visa','2023-06-13','En attente d\'approbation',NULL,NULL,NULL,'001_08062023_0000000003','2023-06-13 20:53:58','2023-06-13 20:53:58',NULL),(7,5,NULL,'demandes/9W2y0cYXTbhyndD2odYfLGEqO6e1Ej0VbB0NXVQ0.jpg',NULL,'1','Marié(e)',NULL,NULL,1,'Mbamou','103','242055259519','','Software Engineer','ACSI','M\'pila','Visa','2023-06-18','En attente d\'approbation',NULL,NULL,NULL,'001_08062023_0000000004','2023-06-17 23:49:51','2023-06-17 23:49:51',NULL);
/*!40000 ALTER TABLE `demandes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departements`
--

DROP TABLE IF EXISTS `departements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departements` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lib_departement` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `departements_lib_departement_unique` (`lib_departement`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departements`
--

LOCK TABLES `departements` WRITE;
/*!40000 ALTER TABLE `departements` DISABLE KEYS */;
INSERT INTO `departements` VALUES (1,'BRAZZAVILLE','2023-06-03 14:49:43','2023-06-03 14:49:43'),(2,'POINTE-NOIRE','2023-06-03 14:49:43','2023-06-03 14:49:43'),(3,'NIARI','2023-06-03 14:49:43','2023-06-03 14:49:43'),(4,'LEKOUMOU','2023-06-03 14:49:43','2023-06-03 14:49:43'),(5,'PLATEAUX','2023-06-03 14:49:43','2023-06-03 14:49:43'),(6,'POOL','2023-06-03 14:49:43','2023-06-03 14:49:43'),(7,'LIKOUALA','2023-06-03 14:49:43','2023-06-03 14:49:43'),(8,'CUVETTE','2023-06-03 14:49:43','2023-06-03 14:49:43'),(9,'CUVETTE-OUEST','2023-06-03 14:49:43','2023-06-03 14:49:43'),(10,'SANGHA','2023-06-03 14:49:43','2023-06-03 14:49:43'),(11,'KOUILOU','2023-06-03 14:49:43','2023-06-03 14:49:43'),(12,'BOUENZA','2023-06-03 14:49:43','2023-06-03 14:49:43'),(13,'AUTRES','2023-06-03 14:49:43','2023-06-03 14:49:43');
/*!40000 ALTER TABLE `departements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_demandes`
--

DROP TABLE IF EXISTS `document_demandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_demandes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type_document` enum('Passeport','Carte consulaire') COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_document` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_emission` date NOT NULL,
  `date_expiration` date NOT NULL,
  `emis_par` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `demandes_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_demandes_demandes_id_foreign` (`demandes_id`),
  CONSTRAINT `document_demandes_demandes_id_foreign` FOREIGN KEY (`demandes_id`) REFERENCES `demandes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_demandes`
--

LOCK TABLES `document_demandes` WRITE;
/*!40000 ALTER TABLE `document_demandes` DISABLE KEYS */;
INSERT INTO `document_demandes` VALUES (1,'Passeport','OP1236092','2023-01-25','2028-01-24','MINAFET',4,'2023-06-08 21:45:36','2023-06-08 21:45:36',NULL),(2,'Carte consulaire','CR RDC 0018/23','2023-01-05','2025-01-04','Ambassade de la RDC',4,'2023-06-08 21:45:36','2023-06-09 10:32:50',NULL),(3,'Passeport','OP0369847','2022-01-05','2027-01-04','MINAFET',5,'2023-06-09 11:27:45','2023-06-09 11:27:45',NULL),(4,'Carte consulaire','CR RDC 0018/21','2022-05-04','2024-05-03','Ambassade de la RDC',5,'2023-06-09 11:27:45','2023-06-09 11:39:47',NULL),(5,'Passeport','OP1236072','2023-06-01','2028-05-30','MINAFET',6,'2023-06-13 20:53:58','2023-06-13 20:53:58',NULL),(6,'Carte consulaire','CR RDC 0018/28','2022-04-01','2024-03-28',NULL,6,'2023-06-13 20:53:58','2023-06-13 20:53:58',NULL),(7,'Passeport','OP1236092','2023-01-21','2028-01-20','MINAFET',7,'2023-06-17 23:49:51','2023-06-17 23:49:51',NULL);
/*!40000 ALTER TABLE `document_demandes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flux_migratoires`
--

DROP TABLE IF EXISTS `flux_migratoires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `flux_migratoires` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `frontieres_id` int unsigned NOT NULL,
  `total_entree` int NOT NULL DEFAULT '0',
  `total_sortie` int NOT NULL DEFAULT '0',
  `pays_id` int unsigned NOT NULL,
  `users_id` bigint unsigned NOT NULL,
  `date_movement` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `flux_migratoires_frontieres_id_foreign` (`frontieres_id`),
  KEY `flux_migratoires_pays_id_foreign` (`pays_id`),
  KEY `flux_migratoires_users_id_foreign` (`users_id`),
  CONSTRAINT `flux_migratoires_frontieres_id_foreign` FOREIGN KEY (`frontieres_id`) REFERENCES `frontiere_congos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `flux_migratoires_pays_id_foreign` FOREIGN KEY (`pays_id`) REFERENCES `pays` (`id`) ON DELETE CASCADE,
  CONSTRAINT `flux_migratoires_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flux_migratoires`
--

LOCK TABLES `flux_migratoires` WRITE;
/*!40000 ALTER TABLE `flux_migratoires` DISABLE KEYS */;
INSERT INTO `flux_migratoires` VALUES (1,1,15,3,51,1,'2023-06-18','2023-06-18 17:48:23','2023-06-18 17:48:23'),(2,2,35,5,138,1,'2023-06-18','2023-06-18 17:49:12','2023-06-18 17:49:12'),(3,3,45,30,119,1,'2023-06-18','2023-06-18 18:20:50','2023-06-18 18:20:50'),(4,4,28,30,51,1,'2023-06-18','2023-06-18 18:21:11','2023-06-18 18:21:11'),(5,5,45,30,77,1,'2023-06-18','2023-06-18 18:21:33','2023-06-18 18:21:33');
/*!40000 ALTER TABLE `flux_migratoires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fonctionnalites`
--

DROP TABLE IF EXISTS `fonctionnalites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fonctionnalites` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lib_fonctionnalite` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fonctionnalite_parent` int unsigned DEFAULT NULL,
  `modules_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fonctionnalites_lib_fonctionnalite_unique` (`lib_fonctionnalite`),
  KEY `fonctionnalites_modules_id_foreign` (`modules_id`),
  KEY `fonctionnalites_fonctionnalite_parent_foreign` (`fonctionnalite_parent`),
  CONSTRAINT `fonctionnalites_fonctionnalite_parent_foreign` FOREIGN KEY (`fonctionnalite_parent`) REFERENCES `fonctionnalites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fonctionnalites_modules_id_foreign` FOREIGN KEY (`modules_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fonctionnalites`
--

LOCK TABLES `fonctionnalites` WRITE;
/*!40000 ALTER TABLE `fonctionnalites` DISABLE KEYS */;
INSERT INTO `fonctionnalites` VALUES (1,'Utilisateurs',NULL,1,'2023-06-17 13:37:04','2023-06-17 13:37:05',NULL),(2,'Créer un utilisateur',1,1,'2023-06-17 13:37:27','2023-06-17 13:37:29',NULL),(3,'Modifier un utilisateur',2,1,'2023-06-17 13:37:51','2023-06-17 13:37:52',NULL),(4,'Supprimer un utilsiateur',1,1,'2023-06-17 13:38:16','2023-06-17 13:38:17',NULL),(5,'Activer ou désactiver un utilisateur',1,1,'2023-06-17 13:38:41','2023-06-17 13:38:43',NULL);
/*!40000 ALTER TABLE `fonctionnalites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `frontiere_congos`
--

DROP TABLE IF EXISTS `frontiere_congos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `frontiere_congos` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lib_frontiere` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `terminal` enum('Port','Aeroport','Terrestre') COLLATE utf8mb4_unicode_ci NOT NULL,
  `departements_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `frontiere_congos_lib_frontiere_unique` (`lib_frontiere`),
  KEY `frontiere_congos_departements_id_foreign` (`departements_id`),
  CONSTRAINT `frontiere_congos_departements_id_foreign` FOREIGN KEY (`departements_id`) REFERENCES `departements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `frontiere_congos`
--

LOCK TABLES `frontiere_congos` WRITE;
/*!40000 ALTER TABLE `frontiere_congos` DISABLE KEYS */;
INSERT INTO `frontiere_congos` VALUES (1,'Port autonome de Brazzaville','Port',1,'2023-06-18 16:15:40','2023-06-18 16:15:40'),(2,'Aéroport International de Maya Maya','Aeroport',1,'2023-06-18 16:17:42','2023-06-18 17:07:56'),(3,'Port artisanal de Maloukou','Port',1,'2023-06-18 18:18:20','2023-06-18 18:18:20'),(4,'Frontière terrestre d\'Oyo','Port',5,'2023-06-18 18:19:04','2023-06-18 18:19:04'),(5,'Aéroport International d\'Agostinho Neto','Aeroport',2,'2023-06-18 18:20:13','2023-06-18 18:20:13');
/*!40000 ALTER TABLE `frontiere_congos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historique_demandes`
--

DROP TABLE IF EXISTS `historique_demandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historique_demandes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `demandes_id` bigint unsigned NOT NULL,
  `statut_demande` enum('En attente d''approbation','Approuvée','Rejetée','Envoyée au contentieux','Renvoyée à la saisie pour modification','Livrée') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'En attente d''approbation',
  `users_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `historique_demandes_demandes_id_foreign` (`demandes_id`),
  KEY `historique_demandes_users_id_foreign` (`users_id`),
  CONSTRAINT `historique_demandes_demandes_id_foreign` FOREIGN KEY (`demandes_id`) REFERENCES `demandes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `historique_demandes_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historique_demandes`
--

LOCK TABLES `historique_demandes` WRITE;
/*!40000 ALTER TABLE `historique_demandes` DISABLE KEYS */;
/*!40000 ALTER TABLE `historique_demandes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `impetrants`
--

DROP TABLE IF EXISTS `impetrants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `impetrants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sexe` enum('Masculin','Féminin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_naissance` date NOT NULL,
  `lieu_naissance` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nationalites_id` int unsigned NOT NULL,
  `nom_pere` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom_pere` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom_mere` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom_mere` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unique_string` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `users_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `impetrants_users_id_foreign` (`users_id`),
  CONSTRAINT `impetrants_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `impetrants`
--

LOCK TABLES `impetrants` WRITE;
/*!40000 ALTER TABLE `impetrants` DISABLE KEYS */;
INSERT INTO `impetrants` VALUES (5,'Mukinayi Kasalu','Seth','Masculin','1990-12-25','LIKASI',51,'Thibuyi','Clément','Somp Kayakez','Véronique','MUKINAYI KASALUSETHMASCULIN1990-12-25LIKASI51THIBUYICLÉMENTSOMP KAYAKEZVÉRONIQUE',NULL,'2023-06-08 21:45:36','2023-06-08 21:45:36',NULL),(15,'Mukinayi Née Lembe Sansi','Benejah','Féminin','1994-03-23','Kinshasa',51,'Lembe Zinga','Alpha','Balosa Kembo','Blandine','MUKINAYI NÉE LEMBE SANSIBENEJAHFÉMININ1994-03-23KINSHASA51LEMBE ZINGAALPHABALOSA KEMBOBLANDINE',NULL,'2023-06-09 11:27:45','2023-06-09 11:27:45',NULL),(16,'Lembe Sansi','Benejah','Féminin','1993-03-23','KINSHASA',51,'Lembe Zinga','Alpha','Balosa Kembo','Blandine','LEMBE SANSIBENEJAHFÉMININ1993-03-23KINSHASA51LEMBE ZINGAALPHABALOSA KEMBOBLANDINE',NULL,'2023-06-13 20:53:58','2023-06-13 20:53:58',NULL);
/*!40000 ALTER TABLE `impetrants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_100000_create_password_reset_tokens_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2023_05_29_104059_create_roles_table',1),(6,'2023_05_29_104060_create_users_table',1),(7,'2023_05_29_104136_create_modules_table',1),(9,'2023_05_29_104956_create_pays_table',1),(10,'2023_05_29_105252_create_departements_table',1),(11,'2023_05_29_105317_create_arrondissements_table',1),(12,'2023_05_29_105329_create_quartiers_table',1),(13,'2023_05_29_105432_create_impetrants_table',1),(14,'2023_05_29_114126_create_demandes_table',1),(15,'2023_05_29_114127_create_document_demandes_table',1),(16,'2023_05_29_133509_create_motif_contentieuxes_table',1),(17,'2023_05_29_133526_create_contentieuxes_table',1),(18,'2023_05_29_104150_create_fonctionnalites_table',2),(23,'2023_06_17_195736_create_historique_demandes_table',3),(24,'2023_06_17_195821_create_frontiere_congos_table',3),(25,'2023_06_17_195850_create_flux_migratoires_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lib_module` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modules_lib_module_unique` (`lib_module`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,'Gestion des Utilisateurs','2023-06-17 13:28:11','2023-06-17 13:28:12',NULL),(2,'Gestion des demandes','2023-06-17 13:28:27','2023-06-17 13:28:28',NULL);
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `motif_contentieuxes`
--

DROP TABLE IF EXISTS `motif_contentieuxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `motif_contentieuxes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lib_motif` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `motif_contentieuxes_lib_motif_unique` (`lib_motif`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `motif_contentieuxes`
--

LOCK TABLES `motif_contentieuxes` WRITE;
/*!40000 ALTER TABLE `motif_contentieuxes` DISABLE KEYS */;
INSERT INTO `motif_contentieuxes` VALUES (1,'Faux Passeport','2023-06-09 15:58:47','2023-06-09 15:58:49',NULL),(2,'Fausse Carte Consulaire','2023-06-09 15:59:02','2023-06-09 15:59:03',NULL);
/*!40000 ALTER TABLE `motif_contentieuxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pays`
--

DROP TABLE IF EXISTS `pays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pays` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lib_pays` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nationalite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pays_lib_pays_unique` (`lib_pays`)
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pays`
--

LOCK TABLES `pays` WRITE;
/*!40000 ALTER TABLE `pays` DISABLE KEYS */;
INSERT INTO `pays` VALUES (1,'Afghanistan','Afghan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(2,'Îles Åland','Aalandais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(3,'Albanie','albanais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(4,'Algérie','Algérien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(5,'Samoa américaines','Samoan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(6,'Andorre','Andorran','2023-06-03 14:45:05','2023-06-03 14:45:05'),(7,'Angola','Angolais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(8,'Anguilla','Anguillais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(9,'Antarctique',NULL,'2023-06-03 14:45:05','2023-06-03 14:45:05'),(10,'Antigua-et-Barbuda','Antiguan ou Barbudan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(11,'Argentine','Argentin','2023-06-03 14:45:05','2023-06-03 14:45:05'),(12,'Arménie','Arménien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(13,'Aruba','Arubais, Arubain','2023-06-03 14:45:05','2023-06-03 14:45:05'),(14,'Australie','Australien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(15,'Autriche','Autrichien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(16,'Azerbaïdjan','Azerbaïdjanais, Azéri','2023-06-03 14:45:05','2023-06-03 14:45:05'),(17,'Bahamas','Bahamien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(18,'Bahreïn','Bahreïn','2023-06-03 14:45:05','2023-06-03 14:45:05'),(19,'Bangladesh','Bengali','2023-06-03 14:45:05','2023-06-03 14:45:05'),(20,'Barbade','Barbadien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(21,'Bélarus','Biélorusse, Bélarusse','2023-06-03 14:45:05','2023-06-03 14:45:05'),(22,'Belgique','Belge','2023-06-03 14:45:05','2023-06-03 14:45:05'),(23,'Belize','Belize','2023-06-03 14:45:05','2023-06-03 14:45:05'),(24,'Bénin','Béninois','2023-06-03 14:45:05','2023-06-03 14:45:05'),(25,'Bermudes','Bermudien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(26,'Bhoutan','Bhoutanais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(27,'Bolivie (État plurinational de)','Bolivien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(28,'Bonaire, Saint-Eustache et Saba','Eustachois','2023-06-03 14:45:05','2023-06-03 14:45:05'),(29,'Bosnie-Herzégovine','Bosnie-Herzégovine','2023-06-03 14:45:05','2023-06-03 14:45:05'),(30,'Botswana','Motswana, Botswanan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(31,'Bouvet (l\'Île)',NULL,'2023-06-03 14:45:05','2023-06-03 14:45:05'),(32,'Brésil','Brésilien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(33,'Indien (le Territoire britannique de l\'océan)','Chagossienne, Chagossien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(34,'Brunéi Darussalam','Bruneian','2023-06-03 14:45:05','2023-06-03 14:45:05'),(35,'Bulgarie','Bulgare','2023-06-03 14:45:05','2023-06-03 14:45:05'),(36,'Burkina Faso','Burkinabé','2023-06-03 14:45:05','2023-06-03 14:45:05'),(37,'Burundi','Burundi','2023-06-03 14:45:05','2023-06-03 14:45:05'),(38,'Cabo Verde','Cabo Verdean','2023-06-03 14:45:05','2023-06-03 14:45:05'),(39,'Cambodge','Cambodgien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(40,'Cameroun','Camerounais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(41,'Canada','Canadienne','2023-06-03 14:45:05','2023-06-03 14:45:05'),(42,'Îles Caïmans','Caïmanien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(43,'République centrafricaine','Afrique centrale','2023-06-03 14:45:05','2023-06-03 14:45:05'),(44,'Tchad','Tchadien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(45,'Chili','Chilien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(46,'Chine','Chinois','2023-06-03 14:45:05','2023-06-03 14:45:05'),(47,'Île Christmas','Insulaires de l\'Île Christmas','2023-06-03 14:45:05','2023-06-03 14:45:05'),(48,'Îles Cocos / Îles Keeling','Insulaires des Îles Cocos','2023-06-03 14:45:05','2023-06-03 14:45:05'),(49,'Colombie','Colombien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(50,'Comores','Comoran, Comorien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(51,'République démocratique du Congo','Congolais RDC','2023-06-03 14:45:05','2023-06-03 14:45:05'),(52,'Congo','Congolais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(53,'Îles Cook','Cookien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(54,'Costa Rica','Costaricain','2023-06-03 14:45:05','2023-06-03 14:45:05'),(55,'Côte d\'Ivoire','Ivoirien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(56,'Croatie','Croate','2023-06-03 14:45:05','2023-06-03 14:45:05'),(57,'Cuba','Cubain','2023-06-03 14:45:05','2023-06-03 14:45:05'),(58,'Curaçao','Chypriote','2023-06-03 14:45:05','2023-06-03 14:45:05'),(59,'Chypre','Chypriote ou Cypriote','2023-06-03 14:45:05','2023-06-03 14:45:05'),(60,'Tchéquie','Tchèque','2023-06-03 14:45:05','2023-06-03 14:45:05'),(61,'Danemark','Danois','2023-06-03 14:45:05','2023-06-03 14:45:05'),(62,'Djibouti','Djiboutien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(63,'Dominique','Dominicain','2023-06-03 14:45:05','2023-06-03 14:45:05'),(64,'République dominicaine','Dominicain','2023-06-03 14:45:05','2023-06-03 14:45:05'),(65,'Équateur','Équatorien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(66,'Égypte','Égyptien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(67,'El Salvador','Salvadorien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(68,'Guinée équatoriale','Équato-guinéenne','2023-06-03 14:45:05','2023-06-03 14:45:05'),(69,'Érythrée','Érythrée','2023-06-03 14:45:05','2023-06-03 14:45:05'),(70,'Estonie','Estonien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(71,'Eswatini','Eswatinien, Swatinien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(72,'Éthiopie','Éthiopien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(73,'Îles Falkland/ Îles Malouines','Malouins','2023-06-03 14:45:05','2023-06-03 14:45:05'),(74,'Îles Féroé','Féroïen','2023-06-03 14:45:05','2023-06-03 14:45:05'),(75,'Fidji','Fidjien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(76,'Finlande','Finlandais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(77,'France','Français','2023-06-03 14:45:05','2023-06-03 14:45:05'),(78,'Guyane française','Guyanais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(79,'Polynésie française','Polynésien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(80,'Terres australes françaises','Français','2023-06-03 14:45:05','2023-06-03 14:45:05'),(81,'Gabon','Gabonais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(82,'Gambie','Gambien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(83,'Géorgie','Géorgien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(84,'Allemagne','Allemand','2023-06-03 14:45:05','2023-06-03 14:45:05'),(85,'Ghana','Ghanéen','2023-06-03 14:45:05','2023-06-03 14:45:05'),(86,'Gibraltar','Gibraltar','2023-06-03 14:45:05','2023-06-03 14:45:05'),(87,'Grèce','Grec, hellénique','2023-06-03 14:45:05','2023-06-03 14:45:05'),(88,'Groenland','Groenlandais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(89,'Grenade','Grenadian','2023-06-03 14:45:05','2023-06-03 14:45:05'),(90,'Guadeloupe','Guadeloupéen','2023-06-03 14:45:05','2023-06-03 14:45:05'),(91,'Guam','Guamanien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(92,'Guatemala','Guatémaltèque','2023-06-03 14:45:05','2023-06-03 14:45:05'),(93,'Guernesey','Guernesiais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(94,'Guinée','Guinéenne','2023-06-03 14:45:05','2023-06-03 14:45:05'),(95,'Guinée-Bissau','Bissau-Guinéen','2023-06-03 14:45:05','2023-06-03 14:45:05'),(96,'Guyana','Guyanais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(97,'Haïti','Haïtien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(98,'Heard-et-Îles MacDonald','Inhabitées','2023-06-03 14:45:05','2023-06-03 14:45:05'),(99,'Saint-Siège','Vaticanais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(100,'Honduras','Honduras','2023-06-03 14:45:05','2023-06-03 14:45:05'),(101,'Hong Kong','Hongkongais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(102,'Hongrie','Hongrois, magyar','2023-06-03 14:45:05','2023-06-03 14:45:05'),(103,'Islande','Islandais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(104,'Inde','Indien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(105,'Indonésie','Indonesian','2023-06-03 14:45:05','2023-06-03 14:45:05'),(106,'République Islamique d\'Iran','Iranien, Persan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(107,'Iraq','Irakien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(108,'Irlande','Irlandais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(109,'Île de Man','Mannoise, Mannois','2023-06-03 14:45:05','2023-06-03 14:45:05'),(110,'Israël','Israélien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(111,'Italie','Italien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(112,'Jamaïque','Jamaïquain','2023-06-03 14:45:05','2023-06-03 14:45:05'),(113,'Japon','Japonais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(114,'Jersey','Jersiais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(115,'Jordanie','Jordanien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(116,'Kazakhstan','Kazakhstani, Kazakh','2023-06-03 14:45:05','2023-06-03 14:45:05'),(117,'Kenya','Kényen','2023-06-03 14:45:05','2023-06-03 14:45:05'),(118,'Kiribati','I-Kiribati','2023-06-03 14:45:05','2023-06-03 14:45:05'),(119,'République populaire démocratique de Corée','Nord coréen','2023-06-03 14:45:05','2023-06-03 14:45:05'),(120,'République de Corée','Corée du Sud','2023-06-03 14:45:05','2023-06-03 14:45:05'),(121,'Koweït','Koweïtien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(122,'Kirghizistan','Kirghizistan, Kirghize, Kirghiz, Kirghiz','2023-06-03 14:45:05','2023-06-03 14:45:05'),(123,'Lao, République démocratique populaire','Lao, laotien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(124,'Lettonie','Letton, Letton','2023-06-03 14:45:05','2023-06-03 14:45:05'),(125,'Liban','Libanais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(126,'Lesotho','Basotho','2023-06-03 14:45:05','2023-06-03 14:45:05'),(127,'Libéria','Libérienne','2023-06-03 14:45:05','2023-06-03 14:45:05'),(128,'Libye','Libye','2023-06-03 14:45:05','2023-06-03 14:45:05'),(129,'Liechtenstein','Liechtenstein','2023-06-03 14:45:05','2023-06-03 14:45:05'),(130,'Lituanie','Lituanien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(131,'Luxembourg','Luxembourg, luxembourgeois','2023-06-03 14:45:05','2023-06-03 14:45:05'),(132,'Macao','Macanais ou Macanéen','2023-06-03 14:45:05','2023-06-03 14:45:05'),(133,'Macédoine','Macédonien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(134,'Madagascar','Malgache','2023-06-03 14:45:05','2023-06-03 14:45:05'),(135,'Malawi','Malawite','2023-06-03 14:45:05','2023-06-03 14:45:05'),(136,'Malaisie','Malaisie','2023-06-03 14:45:05','2023-06-03 14:45:05'),(137,'Maldives','Maldives','2023-06-03 14:45:05','2023-06-03 14:45:05'),(138,'Mali','Malien, malinais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(139,'Malte','Maltais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(140,'Îles Marshall','Marshall','2023-06-03 14:45:05','2023-06-03 14:45:05'),(141,'Martinique','Martiniquais, Martinican','2023-06-03 14:45:05','2023-06-03 14:45:05'),(142,'Mauritanie','Mauritanien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(143,'Maurice','Mauricien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(144,'Mayotte','Mahorais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(145,'Mexique','Mexicain','2023-06-03 14:45:05','2023-06-03 14:45:05'),(146,'États fédérés de Micronésie','Micronésiens','2023-06-03 14:45:05','2023-06-03 14:45:05'),(147,'Moldova, République de','Moldave','2023-06-03 14:45:05','2023-06-03 14:45:05'),(148,'Monaco','Monégasque, Monacan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(149,'Mongolie','Mongol','2023-06-03 14:45:05','2023-06-03 14:45:05'),(150,'Monténégro','Monténégrin','2023-06-03 14:45:05','2023-06-03 14:45:05'),(151,'Montserrat','Montserratí, Montserratina Montserrater,','2023-06-03 14:45:05','2023-06-03 14:45:05'),(152,'Maroc','Marocain','2023-06-03 14:45:05','2023-06-03 14:45:05'),(153,'Mozambique','Mozambique','2023-06-03 14:45:05','2023-06-03 14:45:05'),(154,'Myanmar','Myanmarais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(155,'Namibie','Namibie','2023-06-03 14:45:05','2023-06-03 14:45:05'),(156,'Nauru','Nauruan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(157,'Népal','Népalais, népalais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(158,'Pays-Bas','Néerlandais, Néerlandais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(159,'Nouvelle-Calédonie','Néo-Calédonien, Néo-Calédonienne ou Calédonien, Calédonienne','2023-06-03 14:45:05','2023-06-03 14:45:05'),(160,'Nouvelle-Zélande','Nouvelle-Zélande, Zelanian','2023-06-03 14:45:05','2023-06-03 14:45:05'),(161,'Nicaragua','Nicaraguayen','2023-06-03 14:45:05','2023-06-03 14:45:05'),(162,'Niger','Nigerien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(163,'Nigéria','Nigeria','2023-06-03 14:45:05','2023-06-03 14:45:05'),(164,'Niue','Niuéen','2023-06-03 14:45:05','2023-06-03 14:45:05'),(165,'Île Norfolk','Norfolkais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(166,'Îles Mariannes du Nord','Norfolkais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(167,'Norvège','Norvégien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(168,'Oman','Oman','2023-06-03 14:45:05','2023-06-03 14:45:05'),(169,'Pakistan','Pakistanais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(170,'Palaos','Palaois, Paluan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(171,'État de Palestine','Palestinien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(172,'Panama','Panaméen','2023-06-03 14:45:05','2023-06-03 14:45:05'),(173,'Papouasie-Nouvelle-Guinée','Papouasie-Nouvelle-Guinée, Papouasie','2023-06-03 14:45:05','2023-06-03 14:45:05'),(174,'Paraguay','Paraguayen','2023-06-03 14:45:05','2023-06-03 14:45:05'),(175,'Pérou','Péruvien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(176,'Philippines','Philippin, philippin','2023-06-03 14:45:05','2023-06-03 14:45:05'),(177,'Pitcairn','Pitcairnais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(178,'Pologne','Polonais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(179,'Portugal','Portugais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(180,'Porto Rico','Portoricain','2023-06-03 14:45:05','2023-06-03 14:45:05'),(181,'Qatar','Qatari','2023-06-03 14:45:05','2023-06-03 14:45:05'),(182,'Réunion','Réunionais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(183,'Roumanie','Roumain','2023-06-03 14:45:05','2023-06-03 14:45:05'),(184,'Fédération de Russie','Russe','2023-06-03 14:45:05','2023-06-03 14:45:05'),(185,'Rwanda','Rwandais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(186,'Saint-Barthélemy','Saint-Barth','2023-06-03 14:45:05','2023-06-03 14:45:05'),(187,'Sainte-Hélène, Ascension et Tristan da Cunha','St. Helenian, Ascensionian, Tristanian','2023-06-03 14:45:05','2023-06-03 14:45:05'),(188,'Saint-Kitts-et-Nevis','Christophien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(189,'Sainte-Lucie','Saint Lucian','2023-06-03 14:45:05','2023-06-03 14:45:05'),(190,'Saint-Martin (partie française)','Saint-Martinois','2023-06-03 14:45:05','2023-06-03 14:45:05'),(191,'Saint-Pierre-et-Miquelon','Saint-Pierrais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(192,'Saint-Vincent-et-les Grenadines','Saint Vincentien, Vincentien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(193,'Samoa','Samoan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(194,'Saint-Marin','Saint-Marin','2023-06-03 14:45:05','2023-06-03 14:45:05'),(195,'Sao Tomé-et-Principe','São Toméan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(196,'Arabie saoudite','Arabie saoudite','2023-06-03 14:45:05','2023-06-03 14:45:05'),(197,'Sénégal','Sénégalais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(198,'Serbie','Serbe','2023-06-03 14:45:05','2023-06-03 14:45:05'),(199,'Seychelles','Seychellois','2023-06-03 14:45:05','2023-06-03 14:45:05'),(200,'Sierra Leone','Sierra Leone','2023-06-03 14:45:05','2023-06-03 14:45:05'),(201,'Singapour','Singapour, Singapourien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(202,'Saint-Martin (partie néerlandaise)','Saint-Martinois','2023-06-03 14:45:05','2023-06-03 14:45:05'),(203,'Slovaquie','Slovaque','2023-06-03 14:45:05','2023-06-03 14:45:05'),(204,'Slovénie','Slovène, Slovène','2023-06-03 14:45:05','2023-06-03 14:45:05'),(205,'Îles Salomon','Îles Salomon','2023-06-03 14:45:05','2023-06-03 14:45:05'),(206,'Somalie','Somali','2023-06-03 14:45:05','2023-06-03 14:45:05'),(207,'Afrique du Sud','Sud africain','2023-06-03 14:45:05','2023-06-03 14:45:05'),(208,'Géorgie du Sud-et-les Îles Sandwich du Sud','Sud-Géorgien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(209,'Soudan du Sud','Soudan du Sud','2023-06-03 14:45:05','2023-06-03 14:45:05'),(210,'Espagne','Espagnol','2023-06-03 14:45:05','2023-06-03 14:45:05'),(211,'Sri Lanka','Sri lankais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(212,'Soudan','Soudanais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(213,'Suriname','Surinamais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(214,'Svalbard et l\'Île Jan Mayen','Insulaires de Svalbard','2023-06-03 14:45:05','2023-06-03 14:45:05'),(215,'Suède','Suédois','2023-06-03 14:45:05','2023-06-03 14:45:05'),(216,'Suisse','Suisse','2023-06-03 14:45:05','2023-06-03 14:45:05'),(217,'République arabe syrienne','Syrien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(218,'Taïwan (Province de Chine)','Taïwanais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(219,'Tadjikistan','Tadjikistan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(220,'Tanzanie, République-Unie de','Tanzanien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(221,'Thaïlande','Thai','2023-06-03 14:45:05','2023-06-03 14:45:05'),(222,'Timor-Leste','Timorais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(223,'Togo','Togolais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(224,'Tokelau','Tokélaouan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(225,'Tonga','Tongan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(226,'Trinité-et-Tobago','Trinite-et-Tobago','2023-06-03 14:45:05','2023-06-03 14:45:05'),(227,'Tunisie','Tunisien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(228,'Turquie','Turc','2023-06-03 14:45:05','2023-06-03 14:45:05'),(229,'Turkménistan','Turkmène','2023-06-03 14:45:05','2023-06-03 14:45:05'),(230,'Îles Turks-et-Caïcos',NULL,'2023-06-03 14:45:05','2023-06-03 14:45:05'),(231,'Tuvalu','Tuvaluan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(232,'Ouganda','Ougandais','2023-06-03 14:45:05','2023-06-03 14:45:05'),(233,'Ukraine','Ukrainien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(234,'Émirats arabes unis','Emirati, Emirian, Emiri','2023-06-03 14:45:05','2023-06-03 14:45:05'),(235,'Royaume-Uni de Grande-Bretagne et d\'Irlande du Nord','UK, britannique','2023-06-03 14:45:05','2023-06-03 14:45:05'),(236,'Îles mineures éloignées des États-Unis',NULL,'2023-06-03 14:45:05','2023-06-03 14:45:05'),(237,'États-Unis d\'Amérique','États-Unis, États-Unis, américain','2023-06-03 14:45:05','2023-06-03 14:45:05'),(238,'Uruguay','Uruguayen','2023-06-03 14:45:05','2023-06-03 14:45:05'),(239,'Ouzbékistan','Ouzbékistan, ouzbek','2023-06-03 14:45:05','2023-06-03 14:45:05'),(240,'Vanuatu','Vanuatu, Vanuatuan','2023-06-03 14:45:05','2023-06-03 14:45:05'),(241,'République bolivarienne du Venezuela','Vénézuélien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(242,'Viet Nam','Vietnamien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(243,'Îles Vierges britanniques','Insulaire des Îles Vierges britanniques, Britannique','2023-06-03 14:45:05','2023-06-03 14:45:05'),(244,'Îles Vierges des États-Unis','Insulaire des Îles Vierges des États-Unis','2023-06-03 14:45:05','2023-06-03 14:45:05'),(245,'Wallis-et-Futuna',NULL,'2023-06-03 14:45:05','2023-06-03 14:45:05'),(246,'Sahara occidental','Sahraouie, Sahraoui','2023-06-03 14:45:05','2023-06-03 14:45:05'),(247,'Yémen','Yéménite','2023-06-03 14:45:05','2023-06-03 14:45:05'),(248,'Zambie','Zambien','2023-06-03 14:45:05','2023-06-03 14:45:05'),(249,'Zimbabwe','Zimbabwéen','2023-06-03 14:45:05','2023-06-03 14:45:05');
/*!40000 ALTER TABLE `pays` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quartiers`
--

DROP TABLE IF EXISTS `quartiers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quartiers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lib_quartier` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `arrondissements_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quartiers_arrondissements_id_foreign` (`arrondissements_id`),
  CONSTRAINT `quartiers_arrondissements_id_foreign` FOREIGN KEY (`arrondissements_id`) REFERENCES `arrondissements` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quartiers`
--

LOCK TABLES `quartiers` WRITE;
/*!40000 ALTER TABLE `quartiers` DISABLE KEYS */;
INSERT INTO `quartiers` VALUES (1,'Kibeliba',25,'2023-06-08 20:21:42','2023-06-08 20:21:44',NULL),(2,'Mandzandza',25,'2023-06-08 20:22:10','2023-06-08 20:22:12',NULL),(3,'Centre ville',23,'2023-06-08 20:22:38','2023-06-08 20:22:39',NULL),(4,'756',26,'2023-06-17 18:53:35','2023-06-17 18:53:35',NULL);
/*!40000 ALTER TABLE `quartiers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `lib_role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_lib_role_unique` (`lib_role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Capitaine','2023-06-17 12:09:00','2023-06-17 12:09:00',NULL),(2,'Lieutenant','2023-06-17 12:22:40','2023-06-17 12:22:40',NULL),(3,'Opérateur de saisi','2023-06-22 11:14:02','2023-06-22 11:14:02',NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles_fonctionnalites`
--

DROP TABLE IF EXISTS `roles_fonctionnalites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles_fonctionnalites` (
  `roles_id` int unsigned NOT NULL,
  `fonctionnalites_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`roles_id`,`fonctionnalites_id`),
  KEY `roles_fonctionnalites_fonctionnalites_id_foreign` (`fonctionnalites_id`),
  CONSTRAINT `roles_fonctionnalites_fonctionnalites_id_foreign` FOREIGN KEY (`fonctionnalites_id`) REFERENCES `fonctionnalites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `roles_fonctionnalites_roles_id_foreign` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles_fonctionnalites`
--

LOCK TABLES `roles_fonctionnalites` WRITE;
/*!40000 ALTER TABLE `roles_fonctionnalites` DISABLE KEYS */;
INSERT INTO `roles_fonctionnalites` VALUES (1,2,'2023-06-17 12:59:44','2023-06-17 12:59:44'),(1,5,'2023-06-17 12:59:44','2023-06-17 12:59:44'),(2,4,'2023-06-17 13:05:28','2023-06-17 13:05:28'),(3,2,'2023-06-22 11:14:16','2023-06-22 11:14:16'),(3,5,'2023-06-22 11:14:16','2023-06-22 11:14:16');
/*!40000 ALTER TABLE `roles_fonctionnalites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `prenom` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles_id` int unsigned NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_roles_id_foreign` (`roles_id`),
  CONSTRAINT `users_roles_id_foreign` FOREIGN KEY (`roles_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Seth','Mukinayi Kasalu','mukinayiseth@gmail.com',1,NULL,'$2y$10$dJ2LYOng0PuNyTiCtdrhiuwYvrBPK8/YiWfJFWPXwbEdbhw8pGFP.',NULL,1,'2023-06-18 12:16:09','2023-06-18 12:16:09',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_fonctionnalites`
--

DROP TABLE IF EXISTS `users_fonctionnalites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_fonctionnalites` (
  `users_id` bigint unsigned NOT NULL,
  `fonctionnalites_id` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`users_id`,`fonctionnalites_id`),
  KEY `users_fonctionnalites_fonctionnalites_id_foreign` (`fonctionnalites_id`),
  CONSTRAINT `users_fonctionnalites_fonctionnalites_id_foreign` FOREIGN KEY (`fonctionnalites_id`) REFERENCES `fonctionnalites` (`id`) ON DELETE CASCADE,
  CONSTRAINT `users_fonctionnalites_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_fonctionnalites`
--

LOCK TABLES `users_fonctionnalites` WRITE;
/*!40000 ALTER TABLE `users_fonctionnalites` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_fonctionnalites` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-06-22 19:28:20
