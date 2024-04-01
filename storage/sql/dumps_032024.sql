DROP TABLE IF EXISTS `doctrine_migration_versions`;

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

LOCK TABLES `doctrine_migration_versions` WRITE;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20240401160559','2024-04-01 16:06:05',283);
UNLOCK TABLES;

DROP TABLE IF EXISTS `link`;

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

DROP TABLE IF EXISTS `link_statistic`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

LOCK TABLES `link_statistic` WRITE;
UNLOCK TABLES;


DROP TABLE IF EXISTS `user`;

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

LOCK TABLES `user` WRITE;
INSERT INTO `user` VALUES (1,'Mr. Kelton Ankunding IV','khalil.gleason@example.com','$2y$10$0fu5uC.4OUL0LZ3msbfkF.mB/6X6dUxUxu4gCq/BND/7VUNkKKHZG','[\"ROLE_SUPER_ADMIN\"]',NULL,1,'2024-04-01 17:22:08','2024-04-01 16:06:15');
UNLOCK TABLES;

