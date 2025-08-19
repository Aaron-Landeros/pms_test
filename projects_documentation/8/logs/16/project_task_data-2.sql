-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 23, 2025 at 03:48 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pms_db1`
--

-- --------------------------------------------------------

--
-- Table structure for table `project_task_data`
--

DROP TABLE IF EXISTS `project_task_data`;
CREATE TABLE IF NOT EXISTS `project_task_data` (
  `task_id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `dept_id` int NOT NULL,
  `assigned_user_id` int NOT NULL,
  `activity_id` int NOT NULL,
  `assigned_date` date NOT NULL,
  `due_date` date NOT NULL,
  `task_completion_percent` int NOT NULL,
  `task_status` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`task_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `project_task_data`
--

INSERT INTO `project_task_data` (`task_id`, `project_id`, `dept_id`, `assigned_user_id`, `activity_id`, `assigned_date`, `due_date`, `task_completion_percent`, `task_status`) VALUES
(4, 1, 0, 1, 0, '2025-06-13', '0000-00-00', 0, ''),
(5, 1, 0, 1, 0, '2025-06-13', '0000-00-00', 0, ''),
(6, 1, 0, 1, 0, '2025-06-16', '0000-00-00', 0, ''),
(7, 1, 0, 1, 0, '2025-06-16', '0000-00-00', 0, ''),
(8, 1, 0, 1, 0, '2025-06-16', '0000-00-00', 0, ''),
(9, 1, 1, 1, 1, '2025-06-18', '2025-07-01', 30, 'ACTIVE'),
(10, 1, 1, 7, 1, '2025-06-18', '2025-07-02', 20, 'ACTIVE'),
(11, 1, 1, 1, 1, '2025-06-18', '2025-07-03', 10, 'ACTIVE'),
(12, 1, 1, 7, 1, '2025-06-18', '2025-07-04', 0, 'ACTIVE'),
(13, 1, 1, 1, 1, '2025-06-18', '2025-07-05', 0, 'ACTIVE'),
(14, 1, 1, 7, 1, '2025-06-18', '2025-07-06', 0, 'ACTIVE'),
(15, 1, 1, 1, 1, '2025-06-18', '2025-07-07', 0, 'ACTIVE'),
(16, 1, 1, 1, 1, '2025-06-18', '2025-07-10', 0, 'ACTIVE'),
(17, 1, 1, 1, 1, '2025-06-18', '2025-07-11', 0, 'COMPLETE'),
(18, 1, 1, 1, 1, '2025-06-19', '2025-07-15', 0, 'COMPLETE'),
(19, 1, 1, 1, 1, '2025-06-19', '2025-07-11', 0, 'ACTIVE'),
(20, 1, 1, 1, 1, '2025-06-20', '2025-07-20', 0, 'COMPLETE'),
(21, 1, 1, 1, 1, '2025-06-20', '2025-06-30', 0, 'ACTIVE');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
