-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 23-06-2025 a las 16:28:19
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
-- Estructura de tabla para la tabla `company_carrier`
--

DROP TABLE IF EXISTS `company_carrier`;
CREATE TABLE IF NOT EXISTS `company_carrier` (
  `comp_carrier_rel_id` int NOT NULL AUTO_INCREMENT,
  `carrier_comp_id` int NOT NULL,
  `shipper_comp_id` int NOT NULL,
  `has_shipment_tracking` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `shipment_tracking_url` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `active_carrier` varchar(1) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`comp_carrier_rel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `company_carrier`
--

INSERT INTO `company_carrier` (`comp_carrier_rel_id`, `carrier_comp_id`, `shipper_comp_id`, `has_shipment_tracking`, `shipment_tracking_url`, `active_carrier`) VALUES
(1, 41, 4, 'Y', 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=', 'Y'),
(2, 42, 4, 'N', '', 'N'),
(3, 43, 4, 'N', '', 'Y'),
(4, 44, 4, 'N', '', 'Y'),
(5, 45, 4, 'N', '', 'Y'),
(6, 46, 4, 'N', '', 'Y'),
(7, 47, 4, 'N', '', 'Y'),
(8, 48, 4, 'N', '', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
