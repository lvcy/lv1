-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 28, 2009 at 10:55 PM
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

SET AUTOCOMMIT=0;
START TRANSACTION;

--
-- Database: `lv_db`
--
CREATE DATABASE `lv_db` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `lv_db`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_roles`
--

DROP TABLE IF EXISTS `tbl_roles`;
CREATE TABLE IF NOT EXISTS `tbl_roles` (
  `role_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `role_description` tinytext CHARACTER SET latin1,
  `user_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_id` (`role_id`),
  KEY `FK_roles_to_users` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tbl_roles`
--

INSERT INTO `tbl_roles` (`role_id`, `role_name`, `role_description`, `user_id`) VALUES
(1, 'زوج', NULL, 1),
(2, 'طبيب', NULL, 1),
(3, 'طبيب', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
CREATE TABLE IF NOT EXISTS `tbl_users` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_password` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  `user_activity` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `user_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `creation_ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`) USING BTREE,
  UNIQUE KEY `user_name` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=53 ;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `username`, `user_password`, `user_activity`, `user_type`, `creation_ts`) VALUES
(1, 'tariq', '123', 1, 'user', '0000-00-00 00:00:00'),
(32, '111', 'tariq0000', 1, 'user', '2009-12-25 21:56:40'),
(34, '', 'tariq00007', 1, 'user', '2009-12-25 21:58:25'),
(35, 'tariq00007', '111', 1, 'user', '2009-12-25 21:59:30'),
(37, 'tariq000072', '111', 1, 'user', '2009-12-25 22:01:30'),
(38, 'tariq000079', '111', 1, 'user', '2009-12-28 20:39:34'),
(45, 'tariq032', '111', 1, 'user', '2009-12-28 21:05:15'),
(46, 'tariq03dd2', '111', 1, 'user', '2009-12-28 21:06:57'),
(47, 'tariq03ddd2', '111', 1, 'user', '2009-12-28 21:15:02'),
(48, 'tariquuy', '111', 1, 'user', '2009-12-28 21:19:54'),
(51, 'tariquuay', '111', 1, 'user', '2009-12-28 21:35:43'),
(52, 'tariquuaccy', '111', 1, 'user', '2009-12-28 21:47:24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users_profiles`
--

DROP TABLE IF EXISTS `tbl_users_profiles`;
CREATE TABLE IF NOT EXISTS `tbl_users_profiles` (
  `user_id` bigint(20) unsigned NOT NULL,
  `profile_key` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `profile_value` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`user_id`,`profile_key`,`profile_value`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_users_profiles`
--

INSERT INTO `tbl_users_profiles` (`user_id`, `profile_key`, `profile_value`) VALUES
(1, 'ar_name', 'طارق'),
(1, 'email', 'tariq.omaireeni@gmail.com'),
(1, 'mobile', '0504892602'),
(1, 'nationality', 'KSA'),
(38, 'age', '18'),
(45, 'age', '18'),
(47, 'age', '18'),
(48, 'age', '18'),
(51, 'age', '18'),
(52, 'age', '18'),
(52, 'job', 'dbdev'),
(52, 'nationality', 'saudi');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_roles`
--
ALTER TABLE `tbl_roles`
  ADD CONSTRAINT `FK_roles_to_users` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`);

--
-- Constraints for table `tbl_users_profiles`
--
ALTER TABLE `tbl_users_profiles`
  ADD CONSTRAINT `FK_profiles_to_users` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`user_id`);

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `usp_r_create_role`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_r_create_role`(
-- input
IN n_role_name VARCHAR(50),
IN n_role_description TINYTEXT,
IN n_user_id BIGINT
)
BEGIN
 INSERT INTO  `lv_db`.`tbl_roles` (
 `role_name` ,
 `role_description`,
 `user_id`
  )
 VALUES (
 n_role_name,
 n_role_description,
 n_user_id);

 SELECT LAST_INSERT_ID() AS 'roleId';
END$$

DROP PROCEDURE IF EXISTS `usp_r_get_role`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_r_get_role`(
-- input
IN n_role_id BIGINT
)
BEGIN
  SELECT role_id AS 'roleId',
  role_name AS 'roleName', 
  role_description AS 'roleDescription',  
  user_id AS 'userId'
  FROM tbl_roles
  WHERE role_id = n_role_id;
END$$

DROP PROCEDURE IF EXISTS `usp_r_update_role`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_r_update_role`(
-- input
IN n_role_id BIGINT,
IN n_role_name VARCHAR(50),
IN n_role_description TINYTEXT
-- output
)
BEGIN
	UPDATE tbl_roles
		SET roleName = n_role_name,
			  roleDescription = n_role_description
	WHERE role_id = n_role_id;
END$$

DROP PROCEDURE IF EXISTS `usp_up_get_user_profile`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_up_get_user_profile`(
-- input
IN n_user_id BIGINT
-- output
)
BEGIN
 (SELECT 'userId' AS 'key', CAST(user_id AS CHAR(20)) AS 'value'
 FROM tbl_users
 WHERE user_id = n_user_id)
 UNION
 (SELECT 'username', CAST(username AS CHAR(250))
 FROM tbl_users
 WHERE user_id = n_user_id)
 UNION
 (SELECT CAST(profile_key AS CHAR(50)), CAST(profile_value AS CHAR(500))
 FROM tbl_users_profiles
 WHERE user_id = n_user_id);
END$$

DROP PROCEDURE IF EXISTS `usp_up_update_user_profile`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_up_update_user_profile`(
-- input
IN n_user_id BIGINT,
IN n_profile_key VARCHAR(20),
IN n_profile_value VARCHAR(250)
-- output
)
BEGIN
	UPDATE tbl_users_profiles
		SET profile_value = n_profile_value
	WHERE user_id = n_user_id AND profile_key = n_profile_key;
END$$

DROP PROCEDURE IF EXISTS `usp_ur_get_user_roles`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_ur_get_user_roles`(
-- input
IN n_user_id BIGINT
)
BEGIN
  SELECT 
  role_id AS 'roleId', 
  role_name AS 'roleName', 
  role_description AS 'roleDescription'
  FROM tbl_roles
  WHERE user_id = n_user_id;
END$$

DROP PROCEDURE IF EXISTS `usp_user_adduser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_user_adduser`(
-- input
IN n_username VARCHAR(50),
IN n_user_passowrd VARCHAR(250)
-- output
)
BEGIN
	INSERT INTO  `lv_db`.`tbl_users` (
	`user_id` ,
	`username` ,
	`user_password`
  )
	VALUES (
	NULL ,
	n_username,
	n_user_passowrd);
  
  SELECT LAST_INSERT_ID() as '_id';
END$$

DROP PROCEDURE IF EXISTS `usp_user_profile_adduserprofileitem`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_user_profile_adduserprofileitem`(
-- input
IN n_user_id BIGINT,
IN n_profile_key VARCHAR(20),
IN n_profile_value VARCHAR(250)
-- output
)
BEGIN
	INSERT INTO  `lv_db`.`tbl_users_profiles` (
	`user_id` ,
	`profile_key` ,
	`profile_value`
  )
	VALUES (
	n_user_id,
	n_profile_key,
	n_profile_value);
  
  SELECT 'Done';
END$$

DROP PROCEDURE IF EXISTS `usp_u_authenticate`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_u_authenticate`(
-- input
IN n_username varchar(250),
IN n_user_password varchar(250)
-- output
)
BEGIN
  SELECT user_id as 'userId'
  FROM tbl_users
  WHERE username = n_username
  AND user_password = n_user_password
  AND user_activity = 1;
END$$

DROP PROCEDURE IF EXISTS `usp_u_get_user_id`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_u_get_user_id`(
-- input
IN n_username VARCHAR(50),
IN n_user_password VARCHAR(250)
-- output
)
BEGIN
  SELECT user_id as 'userId'
  FROM tbl_users
  WHERE username = n_username
  AND user_password = n_user_password
  AND user_activity = 1;
END$$

DROP PROCEDURE IF EXISTS `usp_u_is_username_available`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_u_is_username_available`(
-- input
IN n_username varchar(250)
-- output
)
BEGIN
  SELECT count(0) as 'available'
  FROM tbl_users
  WHERE user_name = n_username;
END$$

DROP PROCEDURE IF EXISTS `usp_u_update_user`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_u_update_user`(
-- input
IN n_user_id BIGINT,
IN n_user_passowrd VARCHAR(250),
IN n_user_activity TINYINT,
IN n_user_type VARCHAR(5)
-- output
)
BEGIN
	UPDATE tbl_users
		SET user_passowrd = n_user_passowrd,
			user_activity = n_user_activity,
			user_type = n_user_type
	WHERE user_id = n_user_id;
END$$

DROP PROCEDURE IF EXISTS `usp_u_update_user_activity`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `usp_u_update_user_activity`(
-- input
IN n_user_id BIGINT,
IN n_user_activity TINYINT
-- output
)
BEGIN
	UPDATE tbl_users
		SET user_activity = n_user_activity
	WHERE user_id = n_user_id;
END$$

DELIMITER ;

COMMIT;
