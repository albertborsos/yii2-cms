-- Create syntax for TABLE 'tbl_cms_languages'
CREATE TABLE `tbl_cms_languages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(5) DEFAULT NULL COMMENT 'Nyelv kódja',
  `name` varchar(50) DEFAULT NULL COMMENT 'Megnevezés',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Létrehozva',
  `created_user` int(11) DEFAULT NULL COMMENT 'Létrehozta',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Módosítva',
  `updated_user` int(11) DEFAULT NULL COMMENT 'Módosította',
  `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'tbl_cms_post_seo'
CREATE TABLE `tbl_cms_post_seo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) unsigned NOT NULL COMMENT 'Bejegyzés',
  `title` varchar(70) DEFAULT NULL COMMENT 'Title tag',
  `meta_description` varchar(255) DEFAULT NULL COMMENT 'Meta Description',
  `meta_keywords` varchar(100) DEFAULT NULL COMMENT 'Meta Keywords',
  `meta_robots` varchar(100) DEFAULT NULL COMMENT 'Meta Robots',
  `url` varchar(512) DEFAULT NULL COMMENT 'URL',
  `canonical_post_id` int(11) unsigned NOT NULL COMMENT 'Canonical Post',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Létrehozva',
  `created_user` int(11) DEFAULT NULL COMMENT 'Léterhozta',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Módosítva',
  `updated_user` int(11) DEFAULT NULL COMMENT 'Módosította',
  `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `canonical_post_id` (`canonical_post_id`),
  CONSTRAINT `tbl_cms_post_seo_ibfk_2` FOREIGN KEY (`canonical_post_id`) REFERENCES `tbl_cms_posts` (`id`),
  CONSTRAINT `tbl_cms_post_seo_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `tbl_cms_posts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'tbl_cms_posts'
CREATE TABLE `tbl_cms_posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `language_id` int(11) unsigned NOT NULL COMMENT 'Nyelv',
  `post_type` varchar(100) DEFAULT NULL COMMENT 'Típus',
  `name` varchar(160) DEFAULT NULL COMMENT 'Főcím',
  `content_preview` mediumtext COMMENT 'Előnézet',
  `content_main` mediumtext COMMENT 'Tartalom',
  `order_num` tinyint(4) DEFAULT NULL COMMENT 'Sorrend',
  `commentable` varchar(1) DEFAULT NULL COMMENT 'Hozzá lehet szólni?',
  `date_show` datetime DEFAULT NULL COMMENT 'Megjelenés ideje',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Létrehozva',
  `created_user` int(11) DEFAULT NULL COMMENT 'Létrehozta',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Módosítva',
  `updated_user` int(11) DEFAULT NULL COMMENT 'Módosította',
  `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
  PRIMARY KEY (`id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `tbl_cms_posts_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `tbl_cms_languages` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;