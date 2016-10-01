CREATE DATABASE `vulny` /*!40100 DEFAULT CHARACTER SET utf8 */

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `token` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `locked` int(1) DEFAULT '0',
  `alerts_limit` int(1) NOT NULL DEFAULT '5',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci


CREATE TABLE `alerts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id_owner` int(10) unsigned NOT NULL,
  `query` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `score` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id_owner` (`user_id_owner`) USING BTREE,
  CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`user_id_owner`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8


CREATE TABLE `vulns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ext-id` varchar(64) DEFAULT NULL,
  `origin` varchar(16) DEFAULT NULL,
  `published-datetime` varchar(32) DEFAULT NULL,
  `last-modified-datetime` varchar(32) DEFAULT NULL,
  `score` decimal(10,0) DEFAULT NULL,
  `summary` blob,
  `references` blob,
  `products` mediumblob,
  PRIMARY KEY (`id`),
  KEY `idx_extid` (`ext-id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8


CREATE TABLE `last_date_index_update` (
  `last_index` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT

