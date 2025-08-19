-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 20-06-2025 a las 16:22:49
-- Versión del servidor: 10.11.10-MariaDB-log
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u897286317_pms`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `project_task_data`
--

CREATE TABLE `project_task_data` (
  `task_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `assigned_user_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `assigned_date` date NOT NULL,
  `due_date` date NOT NULL,
  `task_completion_percent` int(11) NOT NULL,
  `task_status` varchar(15) NOT NULL DEFAULT 'ACTIVE'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `project_task_data`
--

INSERT INTO `project_task_data` (`task_id`, `project_id`, `dept_id`, `assigned_user_id`, `activity_id`, `assigned_date`, `due_date`, `task_completion_percent`, `task_status`) VALUES
(9, 1, 1, 1, 1, '2025-06-18', '2025-07-01', 30, 'ACTIVE'),
(10, 1, 1, 7, 1, '2025-06-18', '2025-07-02', 20, 'ACTIVE'),
(11, 1, 1, 1, 1, '2025-06-18', '2025-07-03', 10, 'ACTIVE'),
(12, 1, 1, 7, 1, '2025-06-18', '2025-07-04', 0, 'ACTIVE'),
(13, 1, 1, 1, 1, '2025-06-18', '2025-07-05', 0, 'ACTIVE'),
(14, 1, 1, 7, 1, '2025-06-18', '2025-07-06', 0, 'ACTIVE'),
(15, 1, 1, 1, 1, '2025-06-18', '2025-07-07', 0, 'ACTIVE'),
(16, 1, 1, 1, 1, '2025-06-18', '2025-07-10', 0, 'ACTIVE'),
(17, 1, 1, 1, 1, '2025-06-18', '2025-07-11', 0, 'COMPLETE'),
(20, 2, 8, 1, 2, '2025-06-18', '2025-07-02', 0, 'ACTIVE'),
(21, 2, 8, 1, 1, '2025-06-18', '2025-07-03', 0, 'COMPLETE'),
(22, 2, 8, 1, 2, '2025-06-19', '2025-06-19', 0, 'COMPLETE'),
(23, 2, 8, 1, 1, '2025-06-19', '2025-06-19', 0, 'ACTIVE'),
(24, 3, 1, 7, 1, '2025-06-19', '2025-06-19', 0, 'ACTIVE'),
(25, 4, 1, 7, 6, '2025-06-19', '2025-07-31', 0, 'ACTIVE'),
(26, 4, 2, 10, 3, '2025-06-19', '2025-06-19', 0, 'ACTIVE'),
(27, 3, 3, 11, 4, '2025-06-19', '2025-06-19', 0, 'ACTIVE'),
(28, 3, 2, 8, 2, '2025-06-19', '2025-06-19', 0, 'ACTIVE'),
(29, 5, 3, 12, 5, '2025-06-19', '2025-06-18', 0, 'ACTIVE'),
(30, 5, 1, 7, 1, '2025-06-19', '2025-06-18', 0, 'ACTIVE'),
(31, 5, 4, 14, 8, '2025-06-19', '2025-06-20', 0, 'COMPLETE'),
(32, 4, 4, 12, 8, '2025-06-20', '2025-06-20', 0, 'COMPLETE'),
(33, 4, 4, 12, 7, '2025-06-20', '2025-06-20', 0, 'ACTIVE'),
(34, 4, 4, 12, 7, '2025-06-20', '2025-06-20', 0, 'ACTIVE'),
(35, 4, 4, 12, 7, '2025-06-20', '2025-06-20', 0, 'COMPLETE'),
(36, 4, 4, 12, 8, '2025-06-20', '2025-06-20', 0, 'ACTIVE'),
(37, 6, 4, 12, 7, '2025-06-20', '2025-06-17', 0, 'COMPLETE'),
(38, 6, 1, 8, 6, '2025-06-20', '2025-06-20', 0, 'ACTIVE');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `project_task_data`
--
ALTER TABLE `project_task_data`
  ADD PRIMARY KEY (`task_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `project_task_data`
--
ALTER TABLE `project_task_data`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
