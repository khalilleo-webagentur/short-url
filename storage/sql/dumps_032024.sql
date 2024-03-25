-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: localhost    Database: links
-- ------------------------------------------------------
-- Server version	8.0.36-0ubuntu0.22.04.1

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
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20240321224210','2024-03-21 22:42:15',162),('DoctrineMigrations\\Version20240321224507','2024-03-21 22:45:10',95),('DoctrineMigrations\\Version20240321224626','2024-03-21 22:46:29',43),('DoctrineMigrations\\Version20240321233950','2024-03-21 23:39:53',42),('DoctrineMigrations\\Version20240324155837','2024-03-24 15:58:41',55);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link`
--

DROP TABLE IF EXISTS `link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `link` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `token` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `counter` int NOT NULL,
  `ip_adress` varchar(150) COLLATE utf8mb3_unicode_ci NOT NULL,
  `user_agent` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `is_reported` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_36AC99F1A76ED395` (`user_id`),
  CONSTRAINT `FK_36AC99F1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link`
--

LOCK TABLES `link` WRITE;
/*!40000 ALTER TABLE `link` DISABLE KEYS */;
INSERT INTO `link` VALUES (133,13,'5CLGgyBb','http://www.kub.net/quos-aut-perspiciatis-aut-assumenda',0,'local','local',0,'2024-03-25 21:45:43','2024-03-25 21:45:43','Eum.'),(134,13,'jfgaFOqd','https://www.koepp.com/et-aliquam-expedita-aut',0,'local','local',0,'2024-03-25 21:45:43','2024-03-25 21:45:43','Non.'),(135,13,'3Tj9PJ68','http://www.schmitt.com/laudantium-pariatur-provident-maxime-eaque-pariatur-quos-consectetur',0,'local','local',0,'2024-03-25 21:45:43','2024-03-25 21:45:43','Nihil.'),(136,13,'yFCohDMR','http://www.schimmel.net/modi-et-eligendi-est-fugiat',0,'local','local',0,'2024-03-25 21:45:43','2024-03-25 21:45:43','Error.'),(137,13,'GV0vUyge','http://schimmel.com/sunt-cumque-molestias-quo',0,'local','local',0,'2024-03-25 21:45:43','2024-03-25 21:45:43','Omnis.'),(138,13,'6zscQbCl','http://connelly.com/est-asperiores-blanditiis-modi-velit-ut-veniam-qui',0,'local','local',0,'2024-03-25 21:45:43','2024-03-25 21:45:43','Autem.'),(139,13,'mb9IfuwK','http://www.windler.com/',0,'local','local',0,'2024-03-25 21:45:43','2024-03-25 21:45:43','Omnis.'),(140,13,'jU5Z9Bi6','http://www.gerlach.com/',0,'local','local',0,'2024-03-25 21:45:43','2024-03-25 21:45:43','Id.'),(141,13,'Qle3CNkv','http://hintz.com/adipisci-eum-et-qui-aut-dolor',0,'local','local',0,'2024-03-25 21:45:43','2024-03-25 21:45:43','Aut.'),(142,13,'KRlTJhxr','http://www.schaden.com/facere-culpa-modi-a-dolorem-eius-quia',0,'local','local',0,'2024-03-25 21:45:43','2024-03-25 21:45:43','Quia.'),(143,13,'hKJvcXPI','https://vonrueden.com/sint-non-ut-error-quia.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Iste.'),(144,13,'cESkmurt','http://reynolds.org/',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Et.'),(145,13,'KEdZIQCF','https://www.feil.com/et-qui-et-repellendus-ea-repellat-sed',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Ea.'),(146,13,'M9i7tsRX','http://huels.com/omnis-qui-dolores-accusamus-et-quis-et-totam.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Non.'),(147,13,'h7WB3Sf9','https://www.wolff.biz/et-odio-ut-voluptas-omnis-animi-magnam',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Saepe.'),(148,13,'MovlwgB0','http://bayer.com/cumque-consequatur-non-qui-similique-perspiciatis-quae.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Dolore.'),(149,13,'n3fBOcsy','http://www.wintheiser.info/in-reiciendis-ad-omnis-hic-accusamus-eum-velit.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Non.'),(150,13,'evoMZ2Vy','http://fadel.com/sunt-a-cum-libero-vero-sunt',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Fuga.'),(151,13,'jhCWtT71','http://murray.biz/aut-et-aliquid-vel-nulla-laboriosam.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Itaque.'),(152,13,'RH95fsKj','http://www.rogahn.com/odio-explicabo-et-cum-dolore-est-dolorem.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Odio.'),(153,13,'hFO8Umv7','http://www.smitham.com/nemo-aut-non-est-asperiores',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Enim.'),(154,13,'d1a7SHIY','https://gislason.com/ad-eligendi-illum-eligendi-sunt-quae-consequatur.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Sit.'),(155,13,'2A34rkb6','http://dickinson.com/deleniti-hic-ipsam-voluptatibus-ipsum-quos-ex-voluptatum.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Ea.'),(156,13,'qJkIB4p6','http://www.bins.info/qui-voluptatem-quia-in-impedit-aut-id',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Rerum.'),(157,13,'LR65gMtJ','http://www.bergnaum.com/corporis-assumenda-earum-hic-dignissimos-accusantium-quis-doloremque',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Soluta.'),(158,13,'KBP05698','http://www.watsica.com/incidunt-modi-et-eligendi-voluptas-dolorem-fugiat.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Nobis.'),(159,13,'XuHYRhMm','http://marks.net/',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Ut.'),(160,13,'J94qXPrF','http://price.com/',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Sit.'),(161,13,'IWyq8MaU','http://www.gislason.com/maiores-rerum-at-reprehenderit-dolore-consequatur-delectus',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Illum.'),(162,13,'3Hy6q0NE','http://mcdermott.com/id-officia-nihil-consectetur-voluptas-ex-maiores-sint-vitae.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Animi.'),(163,13,'OU4j8cvA','http://will.com/',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Et aut.'),(164,13,'JTjX8MfI','http://www.schroeder.org/',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Atque.'),(165,13,'WgyKnDNs','http://murphy.net/ut-molestiae-laboriosam-quasi-rem-et-quia-suscipit',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Aut.'),(166,13,'vwJ6QhAo','http://www.quigley.info/enim-voluptatem-saepe-error-qui',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Ut.'),(167,13,'YNTwXDtc','http://wintheiser.biz/',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','At.'),(168,13,'iTSbKcgX','http://sporer.com/',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Vel.'),(169,13,'DbskyVqi','https://kunze.com/velit-tenetur-perspiciatis-laboriosam-dignissimos-impedit-dolorum-recusandae-et.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Aut et.'),(170,13,'tlXDf1ps','http://conn.com/voluptate-repudiandae-quaerat-accusamus-illo-neque-aut',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Illo.'),(171,13,'H7ymxgNf','http://damore.com/velit-ut-distinctio-est-libero-illo-sint',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Autem.'),(172,13,'An7MDVtH','http://www.mcglynn.info/assumenda-reprehenderit-voluptatem-quidem-quos',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Ipsum.'),(173,13,'0YWJqfve','http://steuber.com/dolore-magnam-itaque-optio-modi-aut-natus',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Beatae.'),(174,13,'cSN8KOxk','http://www.cole.com/qui-quisquam-vitae-temporibus-nihil-qui-provident-sunt',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Nemo.'),(175,13,'ENkRPVgi','http://www.nitzsche.com/voluptatibus-dolorum-aut-debitis-inventore-voluptates',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Ut.'),(176,13,'YIGZUta5','http://www.wyman.com/adipisci-non-ea-eos-officiis.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','In est.'),(177,13,'Jh5d3GEe','https://www.gleichner.info/culpa-minus-sed-consequatur-aliquid-et-tenetur-voluptates',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Omnis.'),(178,13,'QucGly4W','http://mcclure.com/',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Omnis.'),(179,13,'25zLhPKY','http://www.ankunding.com/laudantium-accusantium-deserunt-velit.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Ad qui.'),(180,13,'wyG9oFgW','http://pollich.com/dolorem-doloremque-neque-eligendi-eos-suscipit-accusantium-cumque-et.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Illo.'),(181,13,'6ncAkxtg','http://mante.com/quia-eveniet-quibusdam-non.html',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Quam.'),(182,13,'rWcIkTJS','http://moen.com/qui-perferendis-saepe-itaque-quis-quia',0,'local','local',0,'2024-03-25 21:45:44','2024-03-25 21:45:44','Sit.'),(183,13,'tEWNnPzO','https://stackoverflow.com/questions/2610497/change-an-html-inputs-placeholder-color-with-css',0,'Local','Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:123.0) Gecko/20100101 Firefox/123.0',0,'2024-03-25 22:36:00','2024-03-25 22:36:00',NULL);
/*!40000 ALTER TABLE `link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `token` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (13,'demo','dev@khalilleo.com','$2y$10$EAqMkhdIQk8.q8xvpLWly.jszfyFt16H7tIDTf2FebVu6Quwq0coy','[\"ROLE_SUPER_ADMIN\"]',NULL,'2024-03-25 22:40:07','2024-03-25 21:44:28',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-03-26  0:38:27
