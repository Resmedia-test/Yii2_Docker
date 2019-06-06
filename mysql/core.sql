# ************************************************************
# Sequel Pro SQL dump
# Версия 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Адрес: localhost (MySQL 5.6.33)
# Схема: core
# Время создания: 2019-06-05 09:04:36 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Дамп таблицы auth_assignments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `auth_assignments`;

CREATE TABLE `auth_assignments` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `auth_assignments_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_items` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `auth_assignments` WRITE;
/*!40000 ALTER TABLE `auth_assignments` DISABLE KEYS */;

INSERT INTO `auth_assignments` (`item_name`, `user_id`, `created_at`)
VALUES
	('admin','1',1555924350);

/*!40000 ALTER TABLE `auth_assignments` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы auth_items
# ------------------------------------------------------------

DROP TABLE IF EXISTS `auth_items`;

CREATE TABLE `auth_items` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_items_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rules` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `auth_items` WRITE;
/*!40000 ALTER TABLE `auth_items` DISABLE KEYS */;

INSERT INTO `auth_items` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`)
VALUES
	('admin',1,NULL,NULL,NULL,1555924350,1555924350),
	('backend',2,NULL,NULL,NULL,1555924350,1555924350),
	('backend.main.main.error',2,NULL,NULL,NULL,1555924350,1555924350),
	('backend.main.main.login',2,NULL,NULL,NULL,1555924350,1555924350),
	('frontend.account',2,NULL,NULL,NULL,1555924350,1555924350),
	('frontend.account.login',2,NULL,NULL,NULL,1555924350,1555924350),
	('frontend.book',2,NULL,NULL,NULL,1555924350,1555924350),
	('frontend.page',2,NULL,NULL,NULL,1555924350,1555924350),
	('guest',1,NULL,NULL,NULL,1555924350,1555924350),
	('user',1,NULL,NULL,NULL,1555924350,1555924350);

/*!40000 ALTER TABLE `auth_items` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы auth_items_childs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `auth_items_childs`;

CREATE TABLE `auth_items_childs` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_items_childs_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_items` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_items_childs_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_items` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `auth_items_childs` WRITE;
/*!40000 ALTER TABLE `auth_items_childs` DISABLE KEYS */;

INSERT INTO `auth_items_childs` (`parent`, `child`)
VALUES
	('admin','backend'),
	('guest','backend.main.main.error'),
	('guest','backend.main.main.login'),
	('user','frontend.account'),
	('guest','frontend.account.login'),
	('guest','frontend.book'),
	('guest','frontend.page'),
	('user','guest'),
	('admin','user');

/*!40000 ALTER TABLE `auth_items_childs` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы auth_rules
# ------------------------------------------------------------

DROP TABLE IF EXISTS `auth_rules`;

CREATE TABLE `auth_rules` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Дамп таблицы books
# ------------------------------------------------------------

DROP TABLE IF EXISTS `books`;

CREATE TABLE `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `small_desc` text NOT NULL,
  `full_desc` text NOT NULL,
  `time_create` int(11) NOT NULL DEFAULT '0',
  `time_update` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;

INSERT INTO `books` (`id`, `section_id`, `parent_id`, `url`, `name`, `small_desc`, `full_desc`, `time_create`, `time_update`, `status`)
VALUES
	(1,2,0,'tets_new','Проаоот ттал . дло kljlkfj klj lkjlkj lkj ладолдоодло доадл','g ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ','<p>g ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопр</p>\r\n\r\n<p><img alt=\"\" src=\"/uploads/files/Case_N_243_%D0%9A%D0%B0%D0%BA-%D0%B7%D0%B0%D1%80%D0%B5%D0%B3%D0%B8%D1%81%D1%82%D1%80%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D1%82%D1%8C-%D1%80%D0%B5%D0%B1%D0%B5%D0%BD%D0%BA%D0%B0_Oksana-Kuzmina_Shutterstock-com(1).jpg\" style=\"width: 100%;\" /></p>\r\n\r\n<p>g ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопр</p>\r\n\r\n<p>g ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопрg ловарлп лвдп олдва полва лопр</p>\r\n',1556091600,1556091913,1);

/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы menus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `menus`;

CREATE TABLE `menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `levels` smallint(6) NOT NULL DEFAULT '0',
  `status` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;

INSERT INTO `menus` (`id`, `name`, `title`, `levels`, `status`)
VALUES
	(1,'main','Главное',0,1),
	(2,'footer','Нижнее',0,1);

/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы menus_links
# ------------------------------------------------------------

DROP TABLE IF EXISTS `menus_links`;

CREATE TABLE `menus_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `status` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `menus_links` WRITE;
/*!40000 ALTER TABLE `menus_links` DISABLE KEYS */;

INSERT INTO `menus_links` (`id`, `menu_id`, `parent_id`, `url`, `class`, `title`, `order`, `status`)
VALUES
	(1,1,0,'/contacts','','Контакты',1,1),
	(2,1,0,'/about','','О сайте',2,1),
	(3,1,0,'/handbook','','Справочник',3,1);

/*!40000 ALTER TABLE `menus_links` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы meta_tags
# ------------------------------------------------------------

DROP TABLE IF EXISTS `meta_tags`;

CREATE TABLE `meta_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model` varchar(255) NOT NULL,
  `model_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `time_update` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`model`,`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `meta_tags` WRITE;
/*!40000 ALTER TABLE `meta_tags` DISABLE KEYS */;

INSERT INTO `meta_tags` (`id`, `model`, `model_id`, `title`, `keywords`, `description`, `time_update`)
VALUES
	(1,'Section',1,'Главная страница!','Ключи главной страницы','Описание главной страницы',1556091567),
	(2,'Page',2,'Процесс покупки недвижимости в Испании','д лор лор олд','жл ро kljk о ол ',1555939197),
	(3,'Section',2,'?Процесс покупки недвижимости в Испании','; klj;kl','lk jh',1555940162),
	(4,'Page',3,'','','',1555940444),
	(6,'Book',1,'Процесс покупки недвижимости в Испании','апжвда лп аоплдаов ждлпо авдлжо плджвао','жа влопдло плдвао длпо валджп',1556091913);

/*!40000 ALTER TABLE `meta_tags` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы migration
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migration`;

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `migration` WRITE;
/*!40000 ALTER TABLE `migration` DISABLE KEYS */;

INSERT INTO `migration` (`version`, `apply_time`)
VALUES
	('m130524_201442_table_users',1555924350),
	('m140506_102106_rbac_init',1555924350),
	('m150901_071950_settings_table',1555924350),
	('m150914_165758_table_pages',1555924350),
	('m150914_174649_table_menus',1555924350),
	('m150914_174655_table_menus_items',1555924350),
	('m160129_023150_rbac_roles',1555924350),
	('m160205_084016_meta_tags',1555924350),
	('m160329_203900_table_sections',1555924350),
	('m160330_171542_table_books',1555924350),
	('m160425_002955_rbac_admin_user_guest',1555924350),
	('m161121_190905_add_table_request_contacts',1555924350),
	('m190422_084305_add_fields_tables',1555924350);

/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы pages
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pages`;

CREATE TABLE `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `time_update` int(11) NOT NULL DEFAULT '0',
  `status` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;

INSERT INTO `pages` (`id`, `url`, `title`, `content`, `time_update`, `status`)
VALUES
	(2,'contacts','Контакты','<p>&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;&nbsp;лорд лор олдр лор ол рлор олдол рол рол олр лдролдрол&nbsp;</p>\r\n',1555924350,1),
	(3,'about','О сайте','<p>l лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолрl лор о лорлолр</p>\r\n',1555924350,1);

/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы requests_contacts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `requests_contacts`;

CREATE TABLE `requests_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `phone` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `time_create` int(11) NOT NULL DEFAULT '0',
  `status` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Дамп таблицы sections
# ------------------------------------------------------------

DROP TABLE IF EXISTS `sections`;

CREATE TABLE `sections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `time_update` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;

INSERT INTO `sections` (`id`, `module`, `controller`, `action`, `name`, `url`, `status`, `time_update`)
VALUES
	(1,'content','page','index','Главная','/',1,1556091567),
	(2,'library','book','index','Справочник','handbook',1,1555942752);

/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы settings
# ------------------------------------------------------------

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  `element` enum('text','textarea','editor') COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;

INSERT INTO `settings` (`id`, `module_id`, `code`, `name`, `value`, `element`, `status`)
VALUES
	(1,'','site_name','Название сайта','Название Сайта','text',1),
	(2,'','copy','Копирайт','Название Сайта','text',1),
	(3,'','rules','Права','Все права защищены','text',1),
	(4,'','yandex','Счетчик Яндекс','','textarea',1),
	(5,'','google','Счетчик Google','','textarea',1);

/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;


# Дамп таблицы users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `roleName` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `last_login` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `name`, `email`, `auth_key`, `password_hash`, `password_reset_token`, `roleName`, `status`, `last_login`)
VALUES
	(1,'Admin','root@resmedia.ru','','$2y$13$D7t0GZveLzy3Quhi4LD54O/Suhobff4IscSNP12cQWFZj3KyJHGYO','','',1,0);

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
