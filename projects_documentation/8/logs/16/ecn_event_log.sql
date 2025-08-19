-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 23-06-2025 a las 18:21:07
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `ecn_db2`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ecn_event_log`
--

DROP TABLE IF EXISTS `ecn_event_log`;
CREATE TABLE IF NOT EXISTS `ecn_event_log` (
  `event_id` int NOT NULL AUTO_INCREMENT,
  `ecn_id` int NOT NULL,
  `event_user_id` int NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `event_description` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ecn_event_log`
--

INSERT INTO `ecn_event_log` (`event_id`, `ecn_id`, `event_user_id`, `event_date`, `event_time`, `event_description`) VALUES
(1, 49, 18, '2024-12-18', '11:11:58', 'Created ECN 24-000J2-01'),
(2, 49, 18, '2024-12-18', '11:15:49', 'Created Task for TEST USER In Finance Department for Current/Standard Cost Updates - SAP'),
(3, 49, 110, '2024-12-18', '11:17:34', 'Added new task log: test '),
(4, 49, 18, '2024-12-18', '11:20:31', 'Closed Current/Standard Cost Updates - SAP'),
(5, 49, 110, '2024-12-18', '11:21:37', 'Updated Task Log: test ediyt'),
(6, 49, 18, '2024-12-18', '11:25:28', 'Created Task for TEST USER In Finance Department for Current/Standard Cost Updates - SAP'),
(7, 49, 18, '2024-12-18', '11:29:00', 'Uploaded ECN Files to costs With the following: doc de costo'),
(8, 49, 18, '2024-12-18', '11:31:54', 'ECN status updated from in-progress to complete'),
(9, 50, 18, '2024-12-18', '11:50:02', 'Created ECN 24-001J4-01'),
(10, 50, 18, '2024-12-18', '11:51:43', 'Gustavo Cortes transferred ECN #24-001J4-01 to Hugo Ramos from Gustavo Cortes. Reason: transfer teds'),
(11, 49, 1, '2024-12-19', '15:39:34', 'ECN closure comment added: kop'),
(12, 49, 1, '2024-12-19', '15:39:34', 'ECN closure comment added: kop'),
(13, 49, 1, '2024-12-19', '15:40:10', 'ECN closure comment added: cdfdg'),
(14, 49, 1, '2024-12-19', '15:40:10', 'ECN closure comment added: cdfdg'),
(15, 50, 1, '2024-12-19', '15:44:16', 'ECN closure comment added: lkkl;'),
(16, 50, 1, '2024-12-19', '15:44:16', 'ECN closure comment added: lkkl;'),
(17, 51, 1, '2024-12-19', '15:46:46', 'Created ECN 24-002J2-01'),
(18, 51, 1, '2024-12-19', '15:47:10', 'ECN closure comment added: 67867'),
(19, 51, 1, '2024-12-19', '15:47:10', 'ECN closure comment added: 67867'),
(20, 51, 1, '2024-12-19', '16:16:53', 'Created Task for TEST USER In Finance Department for Current/Standard Cost Updates - SAP'),
(21, 51, 1, '2024-12-19', '16:16:53', 'Created Task for TEST USER In Finance Department for Current/Standard Cost Updates - SAP'),
(22, 52, 48, '2025-01-21', '13:10:50', 'Created ECN 25-001J2-01'),
(23, 52, 48, '2025-01-21', '13:12:39', 'ECN due date updated from 2025-05-10 to 2025-05-15'),
(24, 52, 48, '2025-01-21', '13:15:49', 'A new revision for the ECN has been successfully generated with ECN number: 25-001J2-02'),
(25, 54, 48, '2025-01-21', '13:15:49', 'Created ECN revision with ECN number: 25-001J2-02'),
(26, 55, 17, '2025-01-21', '14:25:12', 'Created ECN 25-003J2-01'),
(28, 52, 1, '2025-01-22', '09:55:18', 'Created Task for Eduardo Acosta In Product Eng Department for Drawings\r\n'),
(29, 56, 1, '2025-01-22', '09:55:58', 'Created ECN 25-004J2-01'),
(30, 56, 1, '2025-01-22', '09:57:15', 'Created Task for Tom Holland In Product Eng Department for Drawings\r\n'),
(31, 56, 1, '2025-01-22', '09:57:49', 'Created Task for Virginia Gonzalez In Quality Department for Quality Analysis Report (QAR)\r\n'),
(32, 56, 1, '2025-01-22', '09:58:58', 'Added new task log: ll'),
(33, 56, 1, '2025-01-22', '10:31:49', 'Created Task for Laura Hernandez In Finance Department for Current/Standard Cost Updates - SAP'),
(34, 56, 1, '2025-01-22', '10:50:18', 'A new revision for the ECN has been successfully generated with ECN number: 25-004J2-02'),
(35, 57, 1, '2025-01-22', '10:50:18', 'Created ECN revision with ECN number: 25-004J2-02'),
(37, 57, 1, '2025-01-22', '13:45:15', 'Created Task for Laura Hernandez In Finance Department for Current/Standard Cost Updates - SAP'),
(38, 57, 1, '2025-01-22', '13:45:30', 'Added new task log: op'),
(39, 57, 1, '2025-01-22', '13:45:42', 'Added new task log: ultre'),
(40, 57, 1, '2025-01-22', '13:46:15', 'Added new task log: enrique esqueda'),
(41, 57, 1, '2025-01-22', '15:48:54', 'Isaac Peña transferred ECN #25-004J2-02 to Gustavo Cortes. Reason: Porque si'),
(42, 57, 1, '2025-01-22', '15:49:41', 'TaskCurrent/Standard Cost Updates - SAP transferred to TEST USER. Reason: '),
(44, 57, 1, '2025-01-22', '15:51:19', 'Uploaded ECN Files to costs With the following: kilos'),
(45, 57, 1, '2025-01-22', '15:54:47', 'Uploaded ECN Files to drawings With the following: drawings '),
(46, 57, 1, '2025-01-22', '15:55:46', 'Uploaded ECN Files to plm_qpm With the following: plm test jalando'),
(47, 57, 1, '2025-01-22', '15:56:52', 'Uploaded ECN Files to material_master With the following: material master upload jalando ahora asi'),
(48, 57, 1, '2025-01-22', '16:27:18', 'Closed Current/Standard Cost Updates - SAP'),
(49, 58, 1, '2025-01-24', '15:40:37', 'Created ECN 25-006J2-01'),
(50, 58, 1, '2025-01-24', '15:41:03', 'Created Task for Laura Hernandez In Finance Department for Current/Standard Cost Updates - SAP'),
(51, 58, 1, '2025-01-24', '15:41:22', 'Created Task for Luis Delgado In Materials Department for KAN BAN\r\n'),
(52, 58, 1, '2025-01-24', '15:44:25', 'Created Task for Gustavo Cortes In Product Eng Department for Drawings\r\n'),
(53, 58, 1, '2025-01-24', '15:44:42', 'Created Task for Heber Ramirez In Finance Department for Current/Standard Cost Updates - AMAPS'),
(54, 58, 1, '2025-01-24', '16:04:40', 'Uploaded ECN Files to emails With the following: sds'),
(55, 58, 1, '2025-01-24', '16:04:49', 'Uploaded ECN Files to costs With the following: sdsd'),
(56, 58, 1, '2025-01-24', '16:04:59', 'Uploaded ECN Files to drawings With the following: sdsds'),
(57, 58, 1, '2025-01-24', '16:05:08', 'Uploaded ECN Files to additional_info With the following: sdsd'),
(58, 58, 1, '2025-01-24', '16:05:16', 'Uploaded ECN Files to material_master With the following: sds'),
(59, 58, 1, '2025-01-24', '16:05:24', 'Uploaded ECN Files to plm_qpm With the following: sdsds'),
(60, 58, 1, '2025-01-24', '16:05:34', 'Uploaded ECN Files to validations With the following: sdsd'),
(61, 58, 1, '2025-01-24', '16:13:08', 'Uploaded ECN Files to emails With the following: 435345'),
(62, 58, 1, '2025-01-24', '16:22:17', 'Added new task log: 345345'),
(63, 58, 1, '2025-01-24', '16:28:40', 'Added new task log: 798546'),
(64, 58, 1, '2025-01-24', '16:52:14', 'Uploaded ECN Files to drawings With the following: ds s'),
(65, 58, 1, '2025-01-28', '09:54:54', 'Added new task log: ll;l'),
(66, 60, 1, '2025-02-17', '11:33:22', 'Created ECN 25-007J2-01'),
(67, 60, 1, '2025-02-17', '11:36:30', 'Isaac Peña transferred ECN #25-007J2-01 to Hugo Ramos. Reason: 7u77'),
(68, 60, 1, '2025-02-17', '11:37:01', 'Added new progress log: testing progresslog'),
(69, 60, 48, '2025-03-28', '14:02:38', 'ECN closure comment added: Hola'),
(70, 61, 48, '2025-03-28', '14:34:54', 'Created ECN 25-008J2-01'),
(71, 61, 48, '2025-03-28', '14:35:04', 'Created Task for Jesus Perez Marquez In Manufacturing Department for Routing\r\n'),
(72, 61, 48, '2025-03-28', '14:41:43', 'Closed Routing\r\n'),
(73, 61, 48, '2025-03-28', '14:41:53', 'ECN closure comment added: Hola'),
(74, 62, 48, '2025-03-28', '14:50:06', 'Created ECN 25-009J2-01'),
(75, 63, 18, '2025-03-28', '15:07:23', 'Created ECN 25-010J2-01'),
(76, 63, 18, '2025-03-28', '15:09:10', 'Created Task for Laura Hernandez In Finance Department for Current/Standard Cost Updates - SAP'),
(77, 62, 48, '2025-03-28', '15:32:48', 'Created Task for Luis Delgado In Materials Department for KAN BAN\r\n'),
(78, 60, 48, '2025-03-28', '15:42:54', 'ECN status updated from close to hold'),
(79, 60, 48, '2025-03-28', '15:43:04', 'ECN status updated from hold to in-progress'),
(80, 62, 18, '2025-03-28', '16:22:09', 'Created Task for Gustavo Cortes In Product Eng Department for Item Master/BOM - SAP\r\n'),
(81, 57, 48, '2025-03-31', '10:04:33', 'Added new progress log: fsdgsrfsgf'),
(82, 62, 18, '2025-03-31', '10:27:37', 'Created Task for Luis Delgado In Materials Department for OMNIS Updates - Materials\r\n'),
(83, 62, 18, '2025-03-31', '10:28:47', 'Uploaded ECN Files to emails With the following: koliy'),
(84, 62, 18, '2025-03-31', '10:28:47', 'Uploaded ECN Files to emails With the following: koliy'),
(85, 62, 18, '2025-03-31', '10:29:58', 'Uploaded ECN Files to costs With the following: ytuyut'),
(86, 62, 18, '2025-03-31', '10:30:49', 'Uploaded ECN Files to material_master With the following: khgff'),
(87, 62, 18, '2025-03-31', '10:32:16', 'Uploaded ECN Files to validations With the following: 23434'),
(88, 52, 18, '2025-03-31', '10:43:01', 'ECN category updated from Major to '),
(89, 62, 18, '2025-03-31', '11:27:20', 'Created Task for Jesus Perez Marquez In Manufacturing Department for Routing\r\n'),
(90, 63, 18, '2025-03-31', '11:35:04', 'Created Task for Luis Delgado In Materials Department for AMAPS Inventory Update\r\n'),
(91, 62, 48, '2025-03-31', '11:36:45', 'Created Task for Gustavo Cortes In Product Eng Department for Engineering Specifications\r\n'),
(92, 62, 18, '2025-03-31', '11:56:01', 'Added new task log: holka'),
(93, 62, 18, '2025-03-31', '17:56:01', 'Added new task log: holka'),
(94, 62, 18, '2025-03-31', '18:00:06', 'Added new task log: hola1111'),
(95, 62, 18, '2025-03-31', '19:07:48', 'Added new task log: vista'),
(96, 62, 18, '2025-03-31', '19:11:41', 'Added new task log: dfghjl'),
(97, 62, 18, '2025-03-31', '19:12:54', 'Added new task log: ,vbnvbnvbnvbnvb'),
(98, 63, 18, '2025-03-31', '15:32:58', 'Closed Current/Standard Cost Updates - SAP'),
(99, 63, 18, '2025-03-31', '15:33:08', 'Closed AMAPS Inventory Update\r\n'),
(100, 63, 18, '2025-03-31', '15:33:22', 'ECN closure comment added: cerrado');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
