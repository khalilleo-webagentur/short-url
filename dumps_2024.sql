-- MySQL dump 10.13  Distrib 8.0.36, for Linux (x86_64)
--
-- Host: localhost    Database: short_urls
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
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20240511203211','2024-05-11 20:32:22',472),('DoctrineMigrations\\Version20240513174557','2024-05-13 17:46:05',61),('DoctrineMigrations\\Version20240513200617','2024-05-13 20:06:26',156);
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
  `title` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `token` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `counter` int NOT NULL,
  `is_public` tinyint(1) NOT NULL,
  `is_reported` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_36AC99F15F37A13B` (`token`),
  KEY `IDX_36AC99F1A76ED395` (`user_id`),
  CONSTRAINT `FK_36AC99F1A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link`
--

LOCK TABLES `link` WRITE;
/*!40000 ALTER TABLE `link` DISABLE KEYS */;
INSERT INTO `link` VALUES (4,1,'khalilleo homepage','8765432k','https://khalilleo.com',1,1,0,'2024-05-12 09:55:21','2024-05-11 21:32:55'),(6,1,'bootstrap switchs','yVuALl8r','https://getbootstrap.com/docs/5.3/forms/checks-radios/#switches',0,1,0,'2024-05-11 22:22:04','2024-05-11 22:22:04'),(7,1,NULL,'Zzi1yKnE','https://getbootstrap.com/docs/5.3/forms/checks-radios/#switches',0,1,0,'2024-05-11 22:26:30','2024-05-11 22:26:30'),(8,1,NULL,'ReSnIw8D','https://getbootstrap.com/docs/5.3/forms/checks-radios/#switches',0,1,0,'2024-05-11 22:30:22','2024-05-11 22:30:22'),(10,1,NULL,'ai9djXke','https://getbootstrap.com/docs/5.3/forms/checks-radios/#switches',0,1,0,'2024-05-11 22:33:01','2024-05-11 22:33:01'),(11,1,NULL,'V5mWvzi9','https://getbootstrap.com/docs/5.3/forms/checks-radios/#switches',0,1,0,'2024-05-11 22:33:05','2024-05-11 22:33:05'),(12,1,NULL,'Uqbh6HJ4','https://getbootstrap.com/docs/5.3/forms/checks-radios/#switches',0,1,0,'2024-05-11 22:33:08','2024-05-11 22:33:08'),(13,NULL,NULL,'yRfZLwot','https://getbootstrap.com/docs/5.3/forms/checks-radios/#switches',0,1,0,'2024-05-11 23:01:33','2024-05-11 23:01:33'),(15,1,NULL,'1WgfFyeu','http://localhost:8080/admin/dashboard/z9l7a1k6f6d9y6c2/links/home',0,1,0,'2024-05-12 09:45:44','2024-05-12 09:45:44'),(16,1,'Eum.','Rz0dZKtc','http://www.dibbert.info/',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(17,1,'Autem.','YIUjWAtp','http://www.koepp.info/ut-aut-iste-ipsum-eveniet-velit-accusantium-rerum',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(18,1,'Totam.','jf3viCko','http://www.lemke.com/delectus-nemo-corrupti-tempora-non-est-itaque-pariatur.html',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(19,1,'Beatae.','XcbZUATN','https://reinger.com/similique-impedit-illo-aut-accusantium-fugit-reprehenderit.html',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(20,1,'Libero.','1o6NniUm','http://www.nader.com/',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(21,1,'Nemo.','pW7wvVq3','http://www.hegmann.com/nam-voluptate-delectus-vel-ut-sunt',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(22,1,'Culpa.','KpRz2fyA','http://www.emard.com/non-atque-optio-omnis-repellat-in-libero-assumenda.html',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(23,1,'At ab.','Kgl12VqI','http://stiedemann.biz/dolorum-cum-est-aut-ut-eveniet',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(24,1,'Est.','twe3Uxbo','http://kerluke.com/',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(25,1,'Ipsum.','1sT9eWmG','http://spinka.net/',1,1,0,'2024-05-12 09:50:54','2024-05-12 09:49:33'),(26,1,'In.','ARm6YpuE','https://dubuque.com/ullam-cupiditate-quis-unde-est-nesciunt-dignissimos-aut.html',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(27,1,'Ab.','tjiuF03N','http://monahan.biz/tenetur-sint-soluta-et',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(28,1,'Unde.','KkNUaT7B','https://hilpert.net/consequatur-ut-sequi-quis-maxime-inventore-dolores-repellendus.html',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(29,1,'In.','NqUtSigm','http://www.thompson.com/',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(30,1,'Et non.','wK0yNYXo','http://blanda.biz/consequuntur-debitis-sunt-nostrum-quo-voluptatibus-dolor.html',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(31,1,'Veniam.','qs3cGLfZ','http://bernhard.com/',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(32,1,'Nisi.','2I6dHREj','http://hayes.com/asperiores-quae-quibusdam-architecto-eum-sint-nisi-sequi.html',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(33,1,'A unde.','qOLzSkE4','http://www.hauck.com/libero-sequi-et-enim-minus-minima-quia-est',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(34,1,'Eum.','pMSYm1b9','http://www.smitham.org/',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(35,1,'Sed.','hHaSpYzy','http://mitchell.biz/et-eligendi-possimus-aut-esse-ipsum',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(36,1,'Rerum.','hVM48qyH','http://www.schaden.com/est-consectetur-consequatur-ipsa-itaque-et-voluptas-in-itaque',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(37,1,'Ex.','okdt8gvy','http://jacobs.biz/',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(38,1,'Earum.','KukNBq3E','http://www.schmitt.com/',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(39,1,'Autem.','7u1VQTmg','http://www.koch.org/mollitia-quo-corrupti-natus-excepturi-quasi-quia',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(40,1,'Sit.','GIFLu29D','http://oconnell.com/est-aut-est-molestias-illo-et-pariatur-laborum.html',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(41,1,'Fuga.','P07DbtNh','http://okon.com/qui-excepturi-rerum-aut-debitis-debitis-harum.html',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(42,1,'Modi.','576YxFyf','http://mccullough.info/',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(43,1,'Rerum.','RpmOE5Ly','http://www.hartmann.biz/atque-est-soluta-soluta-rerum-in-accusantium-consequatur',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(44,1,'Soluta.','7CuDEoIz','http://glover.com/magni-officia-repudiandae-dolores-iusto-sed-necessitatibus-illo.html',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(45,1,'Quia.','utKaSCwk','http://daniel.com/',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(46,1,'Non.','b2awIsZR','http://www.hills.info/',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(47,1,'Ut.','jfdSEen9','http://barton.com/enim-ad-natus-iusto-non-et',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(48,1,'Error.','WzvypJab','http://www.hickle.net/aspernatur-deleniti-quod-sunt-ut-quis.html',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(49,1,'Eum.','lL0oCWEv','http://www.ondricka.info/est-ut-libero-officia-vitae-porro-sint.html',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(50,1,'Veniam.','QwxRX2hN','http://www.gulgowski.com/error-et-nobis-quos-deserunt.html',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(51,1,'Sed.','0S2xzCO6','http://kiehn.com/est-necessitatibus-cupiditate-voluptate-nemo',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(52,1,'Aut.','mMEyCJ6I','https://roob.com/repellendus-praesentium-omnis-iure-et-maxime-qui-quibusdam.html',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(53,1,'Vitae.','ho0BXZ5f','http://www.lehner.com/et-voluptatem-deleniti-accusantium-voluptas-nisi-incidunt',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(54,1,'Et.','rW3y6cBG','http://reichert.com/quia-ea-at-dolorem-excepturi-cumque-enim-autem-amet',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(55,1,'Fugit.','xDJ0eCpb','http://www.lynch.com/doloribus-et-dolores-maiores-in',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(57,1,'Minima.','d2BGMor0','http://www.bechtelar.com/facilis-magni-molestias-magni-perferendis-adipisci-consequatur',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(58,1,'Est.','Abry6hcd','http://www.blanda.com/sit-non-earum-qui-adipisci-molestiae-aut',0,1,0,'2024-05-12 09:49:33','2024-05-12 09:49:33'),(60,1,'Eum.','fm43L9th','https://www.larson.info/facere-repudiandae-sint-deleniti-inventore-ex-sint',0,0,0,'2024-06-01 13:35:58','2024-05-12 09:49:33'),(63,1,'Ab.','0FBltaCK','http://www.jacobs.org/neque-repellat-dignissimos-non.html',1,1,0,'2024-05-13 15:42:44','2024-05-12 09:49:33');
/*!40000 ALTER TABLE `link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `link_statistic`
--

DROP TABLE IF EXISTS `link_statistic`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `link_statistic` (
  `id` int NOT NULL AUTO_INCREMENT,
  `link_id` int DEFAULT NULL,
  `browser_name` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `browser_lang` varchar(20) COLLATE utf8mb3_unicode_ci NOT NULL,
  `platform` varchar(30) COLLATE utf8mb3_unicode_ci NOT NULL,
  `referer` varchar(150) COLLATE utf8mb3_unicode_ci NOT NULL,
  `is_mobile` tinyint(1) NOT NULL,
  `ip_address` varchar(100) COLLATE utf8mb3_unicode_ci NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_86EE96E8ADA40271` (`link_id`),
  CONSTRAINT `FK_86EE96E8ADA40271` FOREIGN KEY (`link_id`) REFERENCES `link` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `link_statistic`
--

LOCK TABLES `link_statistic` WRITE;
/*!40000 ALTER TABLE `link_statistic` DISABLE KEYS */;
INSERT INTO `link_statistic` VALUES (3,4,'Mozilla Firefox','en','linux','http://localhost:8080/',0,'','2024-05-11 22:44:33','2024-05-11 22:44:33'),(6,25,'Mozilla Firefox','en','linux','http://localhost:8080/',0,'','2024-05-12 09:50:54','2024-05-12 09:50:54'),(7,63,'Mozilla Firefox','en','linux','http://localhost:8080/',0,'','2024-05-13 15:42:44','2024-05-13 15:42:44');
/*!40000 ALTER TABLE `link_statistic` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `temp_user`
--

DROP TABLE IF EXISTS `temp_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `temp_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_249A5903A76ED395` (`user_id`),
  CONSTRAINT `FK_249A5903A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_user`
--

LOCK TABLES `temp_user` WRITE;
/*!40000 ALTER TABLE `temp_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `temp_user` ENABLE KEYS */;
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
  `is_verified` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'Huka','dev@khalilleo.com','$2y$10$EB2snqVOq9YAW6TA3se4/uR9y2z16OS/T4zOdxoRQXBvfo9Vibg8q','[\"ROLE_SUPER_ADMIN\"]',NULL,1,'2024-06-01 22:25:06','2024-05-11 20:33:36',0),(2,'H.Khalil','test@khalilleo.com','$2y$10$M.jnvOm14ST4ODISuchZ3O8f9csuLmmb89r4d/szbVYNUr1jb6EdG','[\"ROLE_SUPER_ADMIN\"]',NULL,0,'2024-05-13 21:07:07','2024-05-13 20:46:34',1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_setting`
--

DROP TABLE IF EXISTS `user_setting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_setting` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `allow_duplicated_urls` tinyint(1) NOT NULL,
  `allow_link_alias` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C779A692A76ED395` (`user_id`),
  CONSTRAINT `FK_C779A692A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_setting`
--

LOCK TABLES `user_setting` WRITE;
/*!40000 ALTER TABLE `user_setting` DISABLE KEYS */;
INSERT INTO `user_setting` VALUES (1,1,0,1,'2024-06-01 13:30:41','2024-05-11 22:37:12'),(2,2,0,0,'2024-05-13 20:46:34','2024-05-13 20:46:34');
/*!40000 ALTER TABLE `user_setting` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-02  0:50:07
