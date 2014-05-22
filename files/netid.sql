-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 21, 2014 at 06:57 AM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `netid`
--
CREATE DATABASE IF NOT EXISTS `netid` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `netid`;

-- --------------------------------------------------------

--
-- Table structure for table `auditlog`
--

CREATE TABLE IF NOT EXISTS `auditlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `accesstype` varchar(100) DEFAULT NULL,
  `ipaddress` varchar(200) DEFAULT NULL,
  `affecteduser` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=165 ;

--
-- Dumping data for table `auditlog`
--

INSERT INTO `auditlog` (`id`, `username`, `timestamp`, `accesstype`, `ipaddress`, `affecteduser`) VALUES
(1, 'ajtalmazar', '2014-05-08 02:40:29', 'add role', '::1', 'ajtalmazar'),
(2, 'ajtalmazar', '2014-05-12 01:52:18', 'change password', '::1', 'ajtalmazar'),
(3, 'ajtalmazar', '2014-05-12 01:53:48', 'insert', '::1', 'aopinga'),
(4, 'ajtalmazar', '2014-05-12 02:45:26', 'edit profile', '::1', 'aopinga'),
(5, 'ajtalmazar', '2014-05-12 03:07:52', 'edit profile', '::1', 'aopinga'),
(6, 'ajtalmazar', '2014-05-12 03:12:37', 'edit profile', '::1', 'ajtalmazar'),
(7, 'ajtalmazar', '2014-05-12 03:13:32', 'edit profile', '::1', 'ajtalmazar'),
(8, 'ajtalmazar', '2014-05-12 03:17:33', 'edit profile', '::1', 'ajtalmazar'),
(9, 'ajtalmazar', '2014-05-12 05:43:32', 'edit profile', '::1', 'ajtalmazar'),
(10, 'ajtalmazar', '2014-05-12 05:44:09', 'edit profile', '::1', 'ajtalmazar'),
(11, 'ajtalmazar', '2014-05-12 05:53:08', 'edit profile', '::1', 'ajtalmazar'),
(12, 'ajtalmazar', '2014-05-12 06:07:04', 'edit profile', '::1', 'lcabello'),
(13, 'ajtalmazar', '2014-05-12 06:09:16', 'edit profile', '::1', 'lcabello'),
(14, 'ajtalmazar', '2014-05-12 06:12:24', 'edit profile', '::1', 'ajtalmazar'),
(15, 'ajtalmazar', '2014-05-12 06:14:06', 'edit profile', '::1', 'ajtalmazar'),
(16, 'ajtalmazar', '2014-05-12 06:14:18', 'edit profile', '::1', 'ajtalmazar'),
(17, 'ajtalmazar', '2014-05-12 06:15:23', 'edit profile', '::1', 'ajtalmazar'),
(18, 'ajtalmazar', '2014-05-12 06:27:04', 'edit profile', '::1', 'ajtalmazar'),
(19, 'ajtalmazar', '2014-05-12 06:27:53', 'edit profile', '::1', 'ajtalmazar'),
(20, 'ajtalmazar', '2014-05-12 06:28:10', 'edit profile', '::1', 'ajtalmazar'),
(21, 'ajtalmazar', '2014-05-12 06:43:19', 'edit profile', '::1', 'ajtalmazar'),
(22, 'ajtalmazar', '2014-05-12 06:43:37', 'edit profile', '::1', 'ajtalmazar'),
(23, 'ajtalmazar', '2014-05-12 06:44:50', 'edit profile', '::1', 'ajtalmazar'),
(24, 'ajtalmazar', '2014-05-12 06:45:35', 'edit profile', '::1', 'kearaguas'),
(25, 'ajtalmazar', '2014-05-12 06:46:08', 'edit profile', '::1', 'kearaguas'),
(26, 'ajtalmazar', '2014-05-13 01:08:49', 'add role', '::1', 'ajtalmazar'),
(27, 'ajtalmazar', '2014-05-13 01:09:06', 'add role', '::1', 'ajtalmazar'),
(28, 'ajtalmazar', '2014-05-13 01:26:15', 'change password', '::1', 'ajtalmazar'),
(29, 'ajtalmazar', '2014-05-13 01:29:44', 'change password', '::1', 'ajtalmazar'),
(30, 'ajtalmazar', '2014-05-13 07:24:56', 'insert', '::1', 'ajtalmazar'),
(31, 'ajtalmazar', '2014-05-13 07:32:31', 'insert', '::1', 'ajtalmazar'),
(32, 'ajtalmazar', '2014-05-14 03:41:38', 'delete', '::1', 'ajtalmazar'),
(33, 'ajtalmazar', '2014-05-14 03:42:48', 'delete', '::1', 'ajtalmazar'),
(34, 'ajtalmazar', '2014-05-14 06:02:35', 'delete', '::1', 'odabanto'),
(35, 'ajtalmazar', '2014-05-14 06:05:54', 'delete', '::1', 'odabanto'),
(36, 'ajtalmazar', '2014-05-14 06:06:01', 'delete', '::1', 'odabanto'),
(37, 'ajtalmazar', '2014-05-14 06:06:09', 'delete', '::1', 'odabanto'),
(38, 'ajtalmazar', '2014-05-14 06:06:59', 'delete', '::1', 'ibamatorio'),
(39, 'ajtalmazar', '2014-05-14 06:07:09', 'delete', '::1', 'ibamatorio'),
(40, 'ajtalmazar', '2014-05-14 06:15:32', 'delete', '::1', 'ibamatorio'),
(41, 'ajtalmazar', '2014-05-14 06:16:34', 'delete', '::1', 'ibamatorio'),
(42, 'ajtalmazar', '2014-05-14 06:16:45', 'delete', '::1', 'ibamatorio'),
(43, 'ajtalmazar', '2014-05-14 06:17:31', 'delete', '::1', 'ibamatorio'),
(44, 'ajtalmazar', '2014-05-14 06:19:20', 'delete', '::1', 'ibamatorio'),
(45, 'ajtalmazar', '2014-05-14 06:19:30', 'delete', '::1', 'ibamatorio'),
(46, 'ajtalmazar', '2014-05-14 06:20:06', 'delete', '::1', 'ibamatorio'),
(47, 'ajtalmazar', '2014-05-14 06:27:34', 'delete', '::1', 'ibamatorio'),
(48, 'ajtalmazar', '2014-05-14 06:27:41', 'delete', '::1', 'ibamatorio'),
(49, 'ajtalmazar', '2014-05-14 06:30:43', 'delete', '::1', 'ibamatorio'),
(50, 'ajtalmazar', '2014-05-14 06:31:06', 'delete', '::1', 'ibamatorio'),
(51, 'ajtalmazar', '2014-05-14 06:31:21', 'delete', '::1', 'ibamatorio'),
(52, 'ajtalmazar', '2014-05-14 06:32:14', 'delete', '::1', 'ibamatorio'),
(53, 'ajtalmazar', '2014-05-14 06:32:26', 'delete', '::1', 'ibamatorio'),
(54, 'ajtalmazar', '2014-05-14 06:44:12', 'delete', '::1', 'ibamatorio'),
(55, 'ajtalmazar', '2014-05-14 06:44:28', 'delete', '::1', 'vgamatorio'),
(56, 'ajtalmazar', '2014-05-14 06:45:05', 'delete', '::1', 'goanday'),
(57, 'ajtalmazar', '2014-05-14 06:45:56', 'delete', '::1', 'ibamatorio'),
(58, 'ajtalmazar', '2014-05-14 06:46:26', 'delete', '::1', 'vgamatorio'),
(59, 'ajtalmazar', '2014-05-14 06:47:36', 'delete', '::1', 'ibamatorio'),
(60, 'ajtalmazar', '2014-05-14 06:48:05', 'delete', '::1', 'vgamatorio'),
(61, 'ajtalmazar', '2014-05-14 06:48:19', 'delete', '::1', 'ibamatorio'),
(62, 'ajtalmazar', '2014-05-14 06:48:58', 'delete', '::1', 'ibamatorio'),
(63, 'ajtalmazar', '2014-05-14 06:49:21', 'delete', '::1', 'ibamatorio'),
(64, 'ajtalmazar', '2014-05-14 06:49:32', 'delete', '::1', 'vgamatorio'),
(65, 'ajtalmazar', '2014-05-14 06:56:01', 'delete', '::1', 'ajtalmazar'),
(66, 'ajtalmazar', '2014-05-14 06:57:24', 'delete', '::1', 'lpdabitona'),
(67, 'ajtalmazar', '2014-05-14 06:57:43', 'delete', '::1', 'lpdabitona'),
(68, 'ajtalmazar', '2014-05-14 06:58:19', 'delete', '::1', 'lcabello'),
(69, 'ajtalmazar', '2014-05-14 06:59:25', 'delete', '::1', 'lcabello'),
(70, 'ajtalmazar', '2014-05-14 06:59:34', 'delete', '::1', 'lcabello'),
(71, 'ajtalmazar', '2014-05-14 06:59:52', 'delete', '::1', 'lpdabitona'),
(72, 'ajtalmazar', '2014-05-14 07:12:19', 'delete', '::1', 'ajtalmazar'),
(73, 'jldgalag', '2014-05-14 07:39:53', 'insert', '::1', 'msgalvez'),
(74, 'jldgalag', '2014-05-14 07:43:37', 'insert', '::1', 'mkgalinsunurin'),
(75, 'jldgalag', '2014-05-14 07:46:25', 'delete', '::1', 'mkgalinsunurin'),
(76, 'jldgalag', '2014-05-14 08:41:00', 'insert', '::1', 'aldabejero'),
(77, 'jldgalag', '2014-05-14 08:41:26', 'delete', '::1', 'aldabejero'),
(78, 'jldgalag', '2014-05-14 08:58:53', 'insert', '::1', 'jhaabalos'),
(79, 'jldgalag', '2014-05-14 08:59:02', 'delete', '::1', 'jhaabalos'),
(80, 'jldgalag', '2014-05-14 09:08:00', 'delete', '::1', 'jhaabalos'),
(81, 'jldgalag', '2014-05-14 09:08:13', 'delete', '::1', 'jhaabalos'),
(82, 'jldgalag', '2014-05-14 09:08:22', 'delete', '::1', 'jhaabalos'),
(83, 'jldgalag', '2014-05-14 09:08:32', 'delete', '::1', 'jhaabalos'),
(84, 'jldgalag', '2014-05-14 09:09:27', 'delete', '::1', 'jhaabalos'),
(85, 'jldgalag', '2014-05-14 09:11:39', 'delete', '::1', 'jhaabalos'),
(86, 'jldgalag', '2014-05-14 09:14:55', 'delete', '::1', 'test'),
(87, 'jldgalag', '2014-05-14 09:15:06', 'delete', '::1', 'test'),
(88, 'jldgalag', '2014-05-14 09:17:09', 'insert', '::1', 'ljabarquez'),
(89, 'jldgalag', '2014-05-14 09:17:24', 'delete', '::1', 'ljabarquez'),
(90, 'jldgalag', '2014-05-14 09:18:17', 'delete', '::1', 'ljabarquez'),
(91, 'jldgalag', '2014-05-14 09:18:24', 'delete', '::1', 'ljabarquez'),
(92, 'jldgalag', '2014-05-14 09:18:45', 'delete', '::1', 'ljabarquez'),
(93, 'jldgalag', '2014-05-14 09:18:53', 'delete', '::1', 'ljabarquez'),
(94, 'jldgalag', '2014-05-14 09:19:08', 'delete', '::1', 'ljabarquez'),
(95, 'jldgalag', '2014-05-15 05:10:18', 'delete', '::1', 'jhaabalos'),
(96, 'jldgalag', '2014-05-15 05:11:20', 'delete', '::1', 'jhaabalos'),
(97, 'jldgalag', '2014-05-15 05:14:47', 'delete', '::1', 'jhaabalos'),
(98, 'jldgalag', '2014-05-15 05:17:58', 'delete', '::1', 'jhaabalos'),
(99, 'jldgalag', '2014-05-15 05:18:45', 'delete', '::1', 'jhaabalos'),
(100, 'jldgalag', '2014-05-15 05:20:22', 'delete', '::1', 'jhaabalos'),
(101, 'jldgalag', '2014-05-15 05:21:01', 'delete', '::1', 'jhaabalos'),
(102, 'jldgalag', '2014-05-15 05:24:42', 'delete', '::1', 'jhaabalos'),
(103, 'jldgalag', '2014-05-15 05:25:17', 'delete', '::1', 'jhaabalos'),
(104, 'jldgalag', '2014-05-15 05:27:11', 'delete', '::1', 'jhaabalos'),
(105, 'jldgalag', '2014-05-15 05:27:19', 'delete', '::1', 'jhaabalos'),
(106, 'jldgalag', '2014-05-15 05:27:26', 'delete', '::1', 'jhaabalos'),
(107, 'jldgalag', '2014-05-15 05:28:00', 'delete', '::1', 'jhaabalos'),
(108, 'jldgalag', '2014-05-15 05:28:18', 'delete', '::1', 'jhaabalos'),
(109, 'jldgalag', '2014-05-15 05:28:34', 'delete', '::1', 'jhaabalos'),
(110, 'jldgalag', '2014-05-15 05:30:21', 'delete', '::1', 'jhaabalos'),
(111, 'jldgalag', '2014-05-15 05:30:38', 'delete', '::1', 'jhaabalos'),
(112, 'jldgalag', '2014-05-15 05:42:07', 'delete', '::1', 'jhaabalos'),
(113, 'jldgalag', '2014-05-15 05:43:35', 'delete', '::1', 'jhaabalos'),
(114, 'jldgalag', '2014-05-15 05:44:19', 'delete', '::1', 'jhaabalos'),
(115, 'jldgalag', '2014-05-15 05:45:33', 'delete', '::1', 'jhaabalos'),
(116, 'jldgalag', '2014-05-15 05:47:08', 'delete', '::1', 'jhaabalos'),
(117, 'jldgalag', '2014-05-15 05:47:15', 'delete', '::1', 'jhaabalos'),
(118, 'jldgalag', '2014-05-15 05:54:03', 'delete', '::1', 'jhaabalos'),
(119, 'jldgalag', '2014-05-15 06:50:25', 'delete', '::1', 'mgcamit'),
(120, 'jldgalag', '2014-05-15 06:50:49', 'delete', '::1', 'mgcamit'),
(121, 'jldgalag', '2014-05-15 06:51:41', 'delete', '::1', 'mgcamit'),
(122, 'jldgalag', '2014-05-15 06:51:47', 'delete', '::1', 'mgcamit'),
(123, 'jldgalag', '2014-05-15 08:50:52', 'change password', '::1', 'jldgalag'),
(124, 'jldgalag', '2014-05-15 08:51:57', 'change password', '::1', 'jldgalag'),
(125, 'jldgalag', '2014-05-15 08:52:37', 'change password', '::1', 'jldgalag'),
(126, 'jldgalag', '2014-05-15 08:54:15', 'change password', '::1', 'jldgalag'),
(127, 'jldgalag', '2014-05-15 08:55:41', 'change password', '::1', 'jldgalag'),
(128, 'jldgalag', '2014-05-15 08:56:50', 'delete', '::1', 'jhaabalos'),
(129, 'jldgalag', '2014-05-15 08:56:57', 'delete', '::1', 'jhaabalos'),
(130, 'jldgalag', '2014-05-15 09:02:13', 'delete', '::1', 'mgcamit'),
(131, 'jldgalag', '2014-05-15 09:02:25', 'delete', '::1', 'mgcamit'),
(132, 'jldgalag', '2014-05-15 09:06:22', 'change password', '::1', 'jldgalag'),
(133, 'jldgalag', '2014-05-15 09:07:14', 'change password', '::1', 'jldgalag'),
(134, 'jldgalag', '2014-05-15 09:08:36', 'change password', '::1', 'jldgalag'),
(135, 'jldgalag', '2014-05-19 06:33:08', 'delete', '::1', 'trparevalo'),
(136, 'jldgalag', '2014-05-19 06:41:44', 'delete', '::1', 'jhaabalos'),
(137, 'jldgalag', '2014-05-19 06:41:53', 'delete', '::1', 'ljabarquez'),
(138, 'jldgalag', '2014-05-20 03:23:45', 'delete', '::1', 'trparevalo'),
(139, 'jldgalag', '2014-05-20 03:24:07', 'delete', '::1', 'trparevalo'),
(140, 'jldgalag', '2014-05-20 03:59:09', 'delete', '::1', 'lcabello'),
(141, 'jldgalag', '2014-05-20 03:59:25', 'delete', '::1', 'lcabello'),
(142, 'jldgalag', '2014-05-20 04:51:48', 'insert', '::1', 'trparevalo'),
(143, 'jldgalag', '2014-05-20 04:51:48', 'deleted', '::1', 'trparevalo'),
(144, 'jldgalag', '2014-05-20 05:01:57', 'delete', '::1', 'aldabejero'),
(145, 'jldgalag', '2014-05-20 05:02:02', 'delete', '::1', 'mkgalinsunurin'),
(146, 'jldgalag', '2014-05-20 05:02:14', 'delete', '::1', 'useruser'),
(147, 'jldgalag', '2014-05-20 05:02:18', 'delete', '::1', 'msgalang'),
(148, 'jldgalag', '2014-05-20 05:02:22', 'delete', '::1', 'zia'),
(149, 'jldgalag', '2014-05-20 05:02:27', 'delete', '::1', 'dmopinga'),
(150, 'jldgalag', '2014-05-20 05:02:31', 'delete', '::1', 'hmopinga'),
(151, 'jldgalag', '2014-05-20 06:27:08', 'deleted', '::1', 'zia'),
(152, 'jldgalag', '2014-05-21 02:38:39', 'insert', '::1', 'vpalcantara'),
(153, 'jldgalag', '2014-05-21 02:41:31', 'insert', '::1', 'username1'),
(154, 'jldgalag', '2014-05-21 02:41:31', 'deleted', '::1', 'username1'),
(155, 'jldgalag', '2014-05-21 02:43:19', 'insert', '::1', 'ljabarquez'),
(156, 'jldgalag', '2014-05-21 02:43:19', 'deleted', '::1', 'ljabarquez'),
(157, 'jldgalag', '2014-05-21 02:43:44', 'insert', '::1', 'msgalvez'),
(158, 'jldgalag', '2014-05-21 02:43:45', 'deleted', '::1', 'msgalvez'),
(159, 'jldgalag', '2014-05-21 06:46:33', 'insert', '::1', 'dasdakjs'),
(160, 'jldgalag', '2014-05-21 06:49:40', 'insert', '::1', 'adsdas'),
(161, 'jldgalag', '2014-05-21 06:51:26', 'insert', '::1', 'ajtalmazar'),
(162, 'jldgalag', '2014-05-21 06:54:09', 'delete', '::1', 'ebabaojr'),
(163, 'jldgalag', '2014-05-21 06:55:07', 'delete', '::1', 'odabanto'),
(164, 'jldgalag', '2014-05-21 06:55:28', 'delete', '::1', 'rcaboabo');

-- --------------------------------------------------------

--
-- Table structure for table `college`
--

CREATE TABLE IF NOT EXISTS `college` (
  `gidnumber` double NOT NULL DEFAULT '0',
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`gidnumber`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `college`
--

INSERT INTO `college` (`gidnumber`, `name`) VALUES
(1006, 'CA'),
(1017, 'CACAS'),
(1007, 'CAS'),
(1008, 'CDC'),
(1009, 'CEAT'),
(1010, 'CEM'),
(1011, 'CFNR'),
(1012, 'CHE'),
(1013, 'CPAF'),
(1014, 'CVM'),
(1015, 'GS'),
(1016, 'SESAM');

-- --------------------------------------------------------

--
-- Table structure for table `degreeprograms`
--

CREATE TABLE IF NOT EXISTS `degreeprograms` (
  `gidnumber` double NOT NULL,
  `name` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`name`),
  UNIQUE KEY `name` (`name`,`title`),
  KEY `gidnumber` (`gidnumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `degreeprograms`
--

INSERT INTO `degreeprograms` (`gidnumber`, `name`, `title`) VALUES
(1007, 'BACA', 'BA Communication Arts'),
(1007, 'BAPHLO', 'BA Philosophy'),
(1007, 'BASOC', 'BA Sociology'),
(1006, 'BSA', 'BS Agriculture'),
(1009, 'BSABE', 'BS Agricultural and Biosystems Engineering'),
(1010, 'BSABM', 'BS Agribusiness Management'),
(1006, 'BSABT', 'BS Agricultural Biotechnology'),
(1017, 'BSAC', 'BS Agricultural Chemistry'),
(1010, 'BSAECO', 'BS Agricultural Economics'),
(1007, 'BSAM', 'BS Applied Mathematics'),
(1007, 'BSAP', 'BS Applied Physics'),
(1007, 'BSBIO', 'BS Biology'),
(1009, 'BSCE', 'BS Civil Engineering'),
(1009, 'BSCHE', 'BS Chemical Engineering'),
(1007, 'BSCHEM', 'BS Chemistry'),
(1007, 'BSCS', 'BS Computer Science'),
(1008, 'BSDC', 'BS Development Communication'),
(1010, 'BSECO', 'BS Economics'),
(1009, 'BSEE', 'BS Electrical Engineering'),
(1011, 'BSF', 'BS Forestry'),
(1006, 'BSFT', 'BS Food Technology'),
(1012, 'BSHE', 'BS Human Ecology'),
(1007, 'BSMATH', 'BS Mathematics'),
(1007, 'BSMST', 'BS Mathematics and Science Teaching'),
(1012, 'BSN', 'BS Nutrition'),
(1014, 'DVM', 'Doctor of Veterinary Medicine'),
(1015, 'MA', 'Master of Arts'),
(1015, 'MACA', 'Master of Communication Arts'),
(1015, 'MAG', 'Master of Agriculture'),
(1013, 'MASTER OF PUBLIC AFFAIRS', 'Master of Public Affairs'),
(1015, 'MDMG', 'Master of Development Management and Governance'),
(1015, 'MF', 'Master of Forestry'),
(1015, 'MIT', 'Master of Information Technology'),
(1015, 'MM', 'Master of Management'),
(1015, 'MPAF', 'Master in Public Affairs'),
(1015, 'MPROS', 'Master of Professional Studies'),
(1015, 'MS', 'Master of Science'),
(1013, 'MS AGRICULTURAL EDUCATION', 'MS Agricultural Education'),
(1013, 'MS COMMUNITY DEVELOPMENT', 'MS Community Development'),
(1013, 'MS DEVELOPMENT MANAGEMENT AND GOVERNANCE', 'MS Development\r\nManagement and Governance'),
(1016, 'MS ENVIRONMENTAL SCIENCE', 'MS Environmental Science'),
(1013, 'MS EXTENSION EDUCATION', 'MS Extension Education'),
(1015, 'MV', 'Master in Veterinary Epidemiology'),
(1015, 'PHD', 'Doctor of Philosophy'),
(1013, 'PHD AGRICULTURAL EDUCATION', 'Ph.D Agricultural Education'),
(1015, 'PHD by Research', 'Doctor of Philosophy by Research'),
(1013, 'PHD COMMUNITY DEVELOPMENT', 'Ph.D Community Development'),
(1016, 'PHD ENVIRONMENTAL SCIENCE', 'Ph.D Environmental Science'),
(1013, 'PHD EXTENSION EDUCATION', 'Ph.D Extension Education'),
(1014, 'PREVM', 'Pre-veterinary Medicine'),
(1015, 'Straight PHD', 'Straight Doctor of Philosophy');

-- --------------------------------------------------------

--
-- Table structure for table `offices`
--

CREATE TABLE IF NOT EXISTS `offices` (
  `gidnumber` double NOT NULL DEFAULT '0',
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`gidnumber`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `offices`
--

INSERT INTO `offices` (`gidnumber`, `name`) VALUES
(1000, 'OC'),
(1018, 'Others'),
(1001, 'OVCA'),
(1002, 'OVCCA'),
(1003, 'OVCI'),
(1004, 'OVCPD'),
(1005, 'OVCRE');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `uid` text NOT NULL,
  `role` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Values for roles are OCS,\r\nOUR, HRDO, and Admin only ';

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`uid`, `role`) VALUES
('ljbtirazona', 'ADMIN'),
('ljbtirazona', 'OUR'),
('kepbautista', 'OCS'),
('rrdvcustodio', 'OCS'),
('ljbtirazona', 'OCS'),
('imaduka', 'OCS'),
('ljbtirazona', 'HRDO'),
('ravreveche', 'HRDO'),
('kepbautista', 'ADMIN'),
('mgcarandang', 'OUR'),
('mcspaterno', 'OCS'),
('ajtalmazar', 'ADMIN'),
('ajtalmazar', 'HRDO'),
('ajtalmazar', 'OUR'),
('ajtalmazar', 'OCS'),
('jldgalag', 'ADMIN'),
('jldgalag', 'HRDO'),
('jldgalag', 'OUR'),
('jldgalag', 'OCS');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `degreeprograms`
--
ALTER TABLE `degreeprograms`
  ADD CONSTRAINT `degreeprograms_ibfk_1` FOREIGN KEY (`gidnumber`) REFERENCES `college` (`gidnumber`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
