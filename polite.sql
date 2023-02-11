-- Adminer 4.7.7 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `id_group` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_in` date NOT NULL,
  `occurrences` int(11) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `id_user` int(11) NOT NULL,
  `first_name` char(255) DEFAULT NULL,
  `last_name` char(255) DEFAULT NULL,
  `username` char(191) DEFAULT NULL,
  `language_code` char(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2021-04-03 19:15:39