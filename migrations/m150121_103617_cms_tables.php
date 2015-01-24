<?php

use yii\db\Schema;
use yii\db\Migration;

class m150121_103617_cms_tables extends Migration
{
    public function up()
    {
        $this->execute("# ************************************************************
                        # Sequel Pro SQL dump
                        # Version 4096
                        #
                        # http://www.sequelpro.com/
                        # http://code.google.com/p/sequel-pro/
                        #
                        # Host: 127.0.0.1 (MySQL 10.0.10-MariaDB)
                        # Database: yii2advanced
                        # Generation Time: 2014-08-17 13:36:35 +0000
                        # ************************************************************


                        /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
                        /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
                        /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
                        /*!40101 SET NAMES utf8 */;
                        /*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
                        /*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
                        /*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


                        # Dump of table auth_assignment
                        # ------------------------------------------------------------

                        DROP TABLE IF EXISTS `auth_assignment`;

                        CREATE TABLE `auth_assignment` (
                          `item_name` varchar(64) NOT NULL,
                          `user_id` varchar(64) NOT NULL,
                          `created_at` int(11) DEFAULT NULL,
                          PRIMARY KEY (`item_name`,`user_id`),
                          CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

                        # Dump of table auth_item
                        # ------------------------------------------------------------

                        DROP TABLE IF EXISTS `auth_item`;

                        CREATE TABLE `auth_item` (
                          `name` varchar(64) NOT NULL,
                          `type` int(11) NOT NULL,
                          `description` text,
                          `rule_name` varchar(64) DEFAULT NULL,
                          `data` text,
                          `created_at` int(11) DEFAULT NULL,
                          `updated_at` int(11) DEFAULT NULL,
                          PRIMARY KEY (`name`),
                          KEY `rule_name` (`rule_name`),
                          KEY `type` (`type`),
                          CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

                        LOCK TABLES `auth_item` WRITE;
                        /*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;

                        INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`)
                        VALUES
                            ('admin',2,'Adminisztrátor',NULL,NULL,NULL,NULL),
                            ('editor',2,'Szerkesztő',NULL,NULL,NULL,NULL),
                            ('guest',2,'Vendég',NULL,NULL,NULL,NULL),
                            ('reader',2,'Olvasó',NULL,NULL,NULL,NULL);

                        /*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
                        UNLOCK TABLES;


                        # Dump of table auth_item_child
                        # ------------------------------------------------------------

                        DROP TABLE IF EXISTS `auth_item_child`;

                        CREATE TABLE `auth_item_child` (
                          `parent` varchar(64) NOT NULL,
                          `child` varchar(64) NOT NULL,
                          PRIMARY KEY (`parent`,`child`),
                          KEY `child` (`child`),
                          CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
                          CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

                        LOCK TABLES `auth_item_child` WRITE;
                        /*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;

                        INSERT INTO `auth_item_child` (`parent`, `child`)
                        VALUES
                            ('admin','editor'),
                            ('editor','reader'),
                            ('reader','guest');

                        /*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
                        UNLOCK TABLES;


                        # Dump of table auth_rule
                        # ------------------------------------------------------------

                        DROP TABLE IF EXISTS `auth_rule`;

                        CREATE TABLE `auth_rule` (
                          `name` varchar(64) NOT NULL,
                          `data` text,
                          `created_at` int(11) DEFAULT NULL,
                          `updated_at` int(11) DEFAULT NULL,
                          PRIMARY KEY (`name`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


                        # Dump of table ext_tagger_assigns
                        # ------------------------------------------------------------

                        DROP TABLE IF EXISTS `ext_tagger_assigns`;

                        CREATE TABLE `ext_tagger_assigns` (
                          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                          `tag_id` int(11) unsigned NOT NULL COMMENT 'Cimke',
                          `model_class` varchar(160) NOT NULL DEFAULT '' COMMENT 'Osztály',
                          `model_id` int(11) NOT NULL COMMENT 'Példány',
                          `created_at` int(11) DEFAULT NULL COMMENT 'Létrehozva',
                          `created_user` int(11) DEFAULT NULL COMMENT 'Létrehozta',
                          `updated_at` int(11) DEFAULT NULL COMMENT 'Módosítva',
                          `updated_user` int(11) DEFAULT NULL COMMENT 'Módosította',
                          `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
                          PRIMARY KEY (`id`),
                          KEY `tag_id` (`tag_id`),
                          CONSTRAINT `ext_tagger_assigns_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `ext_tagger_tags` (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


                        # Dump of table ext_tagger_tags
                        # ------------------------------------------------------------

                        DROP TABLE IF EXISTS `ext_tagger_tags`;

                        CREATE TABLE `ext_tagger_tags` (
                          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                          `label` varchar(160) DEFAULT NULL COMMENT 'Cimke',
                          `created_at` int(11) DEFAULT NULL COMMENT 'Létrehozva',
                          `created_user` int(11) DEFAULT NULL COMMENT 'Létrehozta',
                          `updated_at` int(11) DEFAULT NULL COMMENT 'Módosítva',
                          `updated_user` int(11) DEFAULT NULL COMMENT 'Módosította',
                          `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


                        # Dump of table tbl_cms_galleries
                        # ------------------------------------------------------------

                        DROP TABLE IF EXISTS `tbl_cms_galleries`;

                        CREATE TABLE `tbl_cms_galleries` (
                          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                          `replace_id` varchar(50) DEFAULT NULL COMMENT 'Beillesztő kód',
                          `name` varchar(100) DEFAULT NULL COMMENT 'Név',
                          `order` varchar(5) DEFAULT NULL COMMENT 'Sorrend',
                          `pagesize` int(11) DEFAULT NULL COMMENT 'Oldalméret',
                          `itemsinarow` int(11) DEFAULT NULL COMMENT 'Képek száma egy sorban',
                          `created_at` int(11) DEFAULT NULL COMMENT 'Létrehozva',
                          `created_user` int(11) DEFAULT NULL COMMENT 'Létrehozta',
                          `updated_at` int(11) DEFAULT NULL COMMENT 'Módosítva',
                          `updated_user` int(11) DEFAULT NULL COMMENT 'Módosította',
                          `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


                        # Dump of table tbl_cms_gallery_photos
                        # ------------------------------------------------------------

                        DROP TABLE IF EXISTS `tbl_cms_gallery_photos`;

                        CREATE TABLE `tbl_cms_gallery_photos` (
                          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                          `gallery_id` int(10) unsigned DEFAULT NULL COMMENT 'Galéria',
                          `filename` varchar(255) DEFAULT NULL COMMENT 'Fájlnév',
                          `title` varchar(255) DEFAULT NULL COMMENT 'Cím',
                          `description` mediumtext COMMENT 'Leírás',
                          `created_at` int(11) DEFAULT NULL COMMENT 'Létrehozva',
                          `created_user` int(11) DEFAULT NULL COMMENT 'Létrehozta',
                          `updated_at` int(11) DEFAULT NULL COMMENT 'Módosítva',
                          `updated_user` int(11) DEFAULT NULL COMMENT 'Módosította',
                          `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
                          PRIMARY KEY (`id`),
                          KEY `gallery_id` (`gallery_id`),
                          CONSTRAINT `tbl_cms_gallery_photos_ibfk_1` FOREIGN KEY (`gallery_id`) REFERENCES `tbl_cms_galleries` (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


                        # Dump of table tbl_cms_languages
                        # ------------------------------------------------------------

                        DROP TABLE IF EXISTS `tbl_cms_languages`;

                        CREATE TABLE `tbl_cms_languages` (
                          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                          `code` varchar(5) DEFAULT NULL COMMENT 'Nyelv kódja',
                          `name` varchar(50) DEFAULT NULL COMMENT 'Megnevezés',
                          `created_at` int(11) DEFAULT NULL COMMENT 'Létrehozva',
                          `created_user` int(11) DEFAULT NULL COMMENT 'Létrehozta',
                          `updated_at` int(11) DEFAULT NULL COMMENT 'Módosítva',
                          `updated_user` int(11) DEFAULT NULL COMMENT 'Módosította',
                          `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
                          PRIMARY KEY (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


                        # Dump of table tbl_cms_post_seo
                        # ------------------------------------------------------------

                        DROP TABLE IF EXISTS `tbl_cms_post_seo`;

                        CREATE TABLE `tbl_cms_post_seo` (
                          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                          `post_id` int(11) unsigned NOT NULL COMMENT 'Bejegyzés',
                          `title` varchar(70) DEFAULT NULL COMMENT 'Title tag',
                          `meta_description` varchar(255) DEFAULT NULL COMMENT 'Meta Description',
                          `meta_keywords` varchar(100) DEFAULT NULL COMMENT 'Meta Keywords',
                          `meta_robots` varchar(100) DEFAULT NULL COMMENT 'Meta Robots',
                          `url` varchar(512) DEFAULT NULL COMMENT 'URL',
                          `canonical_post_id` int(11) unsigned NOT NULL COMMENT 'Canonical Post',
                          `created_at` int(11) DEFAULT NULL COMMENT 'Létrehozva',
                          `created_user` int(11) DEFAULT NULL COMMENT 'Léterhozta',
                          `updated_at` int(11) DEFAULT NULL COMMENT 'Módosítva',
                          `updated_user` int(11) DEFAULT NULL COMMENT 'Módosította',
                          `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
                          PRIMARY KEY (`id`),
                          KEY `post_id` (`post_id`),
                          KEY `canonical_post_id` (`canonical_post_id`),
                          CONSTRAINT `tbl_cms_post_seo_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `tbl_cms_posts` (`id`),
                          CONSTRAINT `tbl_cms_post_seo_ibfk_2` FOREIGN KEY (`canonical_post_id`) REFERENCES `tbl_cms_posts` (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;


                        # Dump of table tbl_cms_posts
                        # ------------------------------------------------------------

                        DROP TABLE IF EXISTS `tbl_cms_posts`;

                        CREATE TABLE `tbl_cms_posts` (
                          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                          `language_id` int(11) unsigned NOT NULL COMMENT 'Nyelv',
                          `post_type` varchar(100) DEFAULT NULL COMMENT 'Típus',
                          `parent_post_id` int(11) DEFAULT NULL COMMENT 'Szülő menüpont',
                          `name` varchar(160) DEFAULT NULL COMMENT 'Főcím',
                          `content_preview` mediumtext COMMENT 'Előnézet',
                          `content_main` mediumtext COMMENT 'Tartalom',
                          `order_num` tinyint(4) DEFAULT NULL COMMENT 'Sorrend',
                          `commentable` varchar(1) DEFAULT NULL COMMENT 'Hozzá lehet szólni?',
                          `date_show` datetime DEFAULT NULL COMMENT 'Megjelenés ideje',
                          `created_at` int(11) DEFAULT NULL COMMENT 'Létrehozva',
                          `created_user` int(11) DEFAULT NULL COMMENT 'Létrehozta',
                          `updated_at` int(11) DEFAULT NULL COMMENT 'Módosítva',
                          `updated_user` int(11) DEFAULT NULL COMMENT 'Módosította',
                          `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
                          PRIMARY KEY (`id`),
                          KEY `language_id` (`language_id`),
                          CONSTRAINT `tbl_cms_posts_ibfk_1` FOREIGN KEY (`language_id`) REFERENCES `tbl_cms_languages` (`id`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                        ");
    }

    public function down()
    {
        echo "m150121_103617_cms_tables cannot be reverted.\n";

        return false;
    }
}
