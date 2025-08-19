-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 23-06-2025 a las 16:23:59
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
-- Base de datos: `ims_prod`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `company_data`
--

DROP TABLE IF EXISTS `company_data`;
CREATE TABLE IF NOT EXISTS `company_data` (
  `company_id` int NOT NULL AUTO_INCREMENT,
  `company_name` varchar(75) COLLATE utf8mb4_general_ci NOT NULL,
  `company_address` varchar(125) COLLATE utf8mb4_general_ci NOT NULL,
  `company_phone` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `company_email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `company_website` varchar(75) COLLATE utf8mb4_general_ci NOT NULL,
  `company_terms` int DEFAULT NULL,
  `company_bill_to_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `company_ship_to_address` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `company_data`
--

INSERT INTO `company_data` (`company_id`, `company_name`, `company_address`, `company_phone`, `company_email`, `company_website`, `company_terms`, `company_bill_to_address`, `company_ship_to_address`) VALUES
(1, 'Entheospace LLC', '11607 Pelicano', '915-229-0899', 'support@entheospace.co', '', NULL, '', ''),
(2, 'Siemens Industry Inc', '9494 Escobar Dr', '915-123-4567', 'sales@siemens.com', '', NULL, '', ''),
(3, 'Johnson Controls | Air System', '111 Uknown', '000-000-0000', 'uknown@mail.com', '', NULL, '', ''),
(4, 'MFG Solutions', '111 Ave', '111-111-1111', 'mfg@mail.com', '', NULL, '', ''),
(5, 'Intermatic', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(6, 'Johnson Control Reynosa', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(7, 'Siemens Industrial', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(8, 'Strattec', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(9, 'Johnson Control De Presion', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(10, 'Maxima', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(11, 'Honeywell International', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(12, 'Honeywell HPS', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(13, 'Auto Kabel', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(14, 'Regal Beloit', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(15, 'Likom', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(16, 'MSSL', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(17, 'Ruskin', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(18, 'Bi Technologies', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(19, 'S&A', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(20, 'Foxconn', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(21, 'Furukawa', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(22, 'Glezco Supplies', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(23, 'Xylem', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(24, 'Gobar', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(25, 'Facil', 'Unknown', 'Unknown', 'Unknown', '', 120, '', ''),
(26, 'Mersen', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(27, 'Neptune', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(28, 'Eaton', 'Unknown', 'Unknown', 'Unknown', '', NULL, 'UNKNOWN', 'UNKNOWN'),
(29, 'Electrolux', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(30, 'AO Smith', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(31, 'ECI', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(32, 'LAU', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(33, 'Mayfair', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(34, 'Durham', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(35, 'Siemens Canada', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(36, 'Trico', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(37, 'Quantum Plastics', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(38, 'Dynamco', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(39, 'Optek', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(40, 'Nexans', 'Unknown', 'Unknown', 'Unknown', '', NULL, '', ''),
(41, 'UPS', 'uknown', '', 'uknown', 'uknown', NULL, '', ''),
(42, 'UPS', 'NA', '0', 'NA', 'ups.com', NULL, '', ''),
(43, 'Fedex', '', '', '', 'https://www.fedex.com/en-us/tracking.html', NULL, '', ''),
(44, 'R&L Carriers', '', '', '', '', NULL, '', ''),
(45, 'Southeastern Freight Lines ', '', '', '', 'https://www.sefl.com/Tracing/index.jsp', NULL, '', ''),
(46, 'Protrans', '', '', '', '', NULL, '', ''),
(47, 'ESTES freight', '12273 GATEWAY WEST SUITE 100', '9155464050', 'carmen@mfg-solutionsinc.com', 'https://www.estes-express.com/myestes/shipment-tracking/', NULL, '', ''),
(48, 'XPO Logistics ', '.', '9155464050', 'jamaro@mfg-solutionsinc.com', '', NULL, '', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
