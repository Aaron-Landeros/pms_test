-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 25-06-2025 a las 21:45:26
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
-- Base de datos: `pms`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_data`
--

DROP TABLE IF EXISTS `user_data`;
CREATE TABLE IF NOT EXISTS `user_data` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `user_email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_last_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `current_credits` decimal(10,1) NOT NULL,
  `user_role` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_status` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'ACTIVE',
  `user_pwrd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `company_id` int NOT NULL,
  `user_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dept_id` int NOT NULL DEFAULT '0',
  `supervisor_id` int NOT NULL DEFAULT '0',
  `hourly_rate` int NOT NULL DEFAULT '0',
  `user_avatar_bg` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'dedede',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `user_data`
--

INSERT INTO `user_data` (`user_id`, `user_email`, `user_first_name`, `user_last_name`, `current_credits`, `user_role`, `user_status`, `user_pwrd`, `company_id`, `user_location`, `dept_id`, `supervisor_id`, `hourly_rate`, `user_avatar_bg`) VALUES
(1, 'isaac@mail.com', 'Carlos', 'Peña', 1.0, 'ADMIN', 'ACTIVE', '$2y$10$grG1B3FZdQecDQQX4Sph7OH8AZ73.pAg9mIA9odXcR8a50IZhijEC', 0, 'El_Paso', 2, 0, 9, 'CE8B45'),
(7, 'test@mail.com', 'Iris', 'test', 0.0, 'ASSEMBLY', 'INACTIVE', '1234', 0, 'Ciudad_Juarez', 3, 0, 0, 'BAFB89'),
(8, 'test2@mail.com', 'test', 'modal insert a', 0.0, 'QUALITY', 'INACTIVE', '$2y$10$e1b0jjAWpDkwMQNHPpsw0..sddiVHdT7gnQ9NOdHAWgV6FcdGVIIC', 0, 'Ciudad_Juarez', 2, 0, 1, '981FE0'),
(9, 'test3@mail.com', 'test3', 'input submit', 0.0, 'PROJECT_MANAGER', 'ACTIVE', '$2y$10$KUkoglElZKat5bAaAOyzE.EUPmNHu346tJJPJyolCzICHPBSC/xLW', 0, 'El_Paso', 5, 1, 1, 'D32986'),
(14, 'test4@mail.com', 'test', 'prepend', 0.0, 'MACHINERY', 'ACTIVE', '$2y$10$gus7epxqtW4KdTPd7KK9ROO8frKriu95lcoERiiB3tNxX/AlbngmW', 0, 'Ciudad_Juarez', 1, 1, 1, 'EEED9D'),
(15, 'test6@mail.com', 'test', 'prepend2', 0.0, 'MACHINERY', 'INACTIVE', '$2y$10$lYJacmEhvY0fWGbEznBKseqhByhSEj6nrwV58YsyhmQjtlNoQuzp2', 0, 'Ciudad_Juarez', 2, 0, 0, '1736ED'),
(16, 'test@mail.com', 'Iris', 'test2', 0.0, 'QUALITY', 'ACTIVE', '$2y$10$LZ84NVaRPovK7teaPCOkz.iti3SzbmMEVv4OoVRM4LtClpqb2jVVm', 0, 'Ciudad_Juarez', 1, 0, 0, 'EA6BA6'),
(17, 'test6@mail.com', 'test', 'prepend2', 0.0, 'MACHINERY', 'INACTIVE', '1234', 0, 'El_Paso', 2, 0, 0, 'C364FE'),
(18, 'test_mail@mail.com', 'Nuevo ', 'User', 0.0, 'MACHINERY', 'ACTIVE', '$2y$10$RMXbAG66wSxk1RnLU2TBh.UPETu1cVHHS.4XgnPCskkjQOpwllj3C', 0, 'El_Paso', 2, 14, 12, '2793E2'),
(19, 'aaron@mail.com', 'q', 'w', 0.0, 'PROJECT_MANAGER', 'ACTIVE', '$2y$10$2IydGk6691/MchfhaU33DeQ8AWIa/xkGwiyAg8LuIRRjMPX.UUSr.', 0, 'Ciudad_Juarez', 1, 14, 123, '044632'),
(20, '3423@MAIL.COM', '32', '32432', 0.0, 'ADMIN', 'ACTIVE', '$2y$10$W7OkW2dU/io5O0hhsadwkOsVbjg.0xEQQoxavaYTe45RE.kM02ule', 0, 'Ciudad_Juarez', 1, 14, 3242, '26D4DC'),
(21, '24@mail.com', '235', '45245', 0.0, 'ADMIN', 'ACTIVE', '$2y$10$J4L.u6drMXrKVocLkIj2G.JIVdrxmYmUDQbgQv59oWYX2GEHVVDgy', 0, 'Ciudad_Juarez', 2, 14, 24, 'C36560'),
(22, 'aaron@mail.com', 'ahora si', 'ya va ajalar', 0.0, 'MACHINERY', 'ACTIVE', '$2y$10$ZBdLpnlkWnLwzbEMDMqyoeGrrKJ.1ZtA2YxTHA9xF8Qgp13PiHcAu', 0, 'Ciudad_Juarez', 3, 20, 133, '30075D'),
(23, 'aaron@mail.com', 'nuevo ', 'agreagado', 0.0, 'QUALITY', 'ACTIVE', '$2y$10$AyzZjAvWMfCzlL2Kv.sRmO0NZGrAxeBB2ISXpXFBg9d1fQzvjiK3C', 0, 'Ciudad_Juarez', 9, 0, 13, '375C0F'),
(24, 'delfino.gonzalez.olvera@jci.com', 'Iconos', 'Iconos', 0.0, 'MACHINERY', 'ACTIVE', '$2y$10$YFJGOPn0xKxo8Wpup/o19.tUPmkKD2WUsx9fxeDUH2gMbsqFs2pWu', 0, 'Ciudad_Juarez', 1, 14, 12, '3488B0'),
(25, 'jdoe@mail.com', '423', '3242', 0.0, 'PROJECT_MANAGER', 'ACTIVE', '$2y$10$nXwGtzjtXcJ2pyf2uIEW7.SQeBObzMSzOoy6xIaASTL7d4ze5HmNS', 0, 'Ciudad_Juarez', 2, 1, 32423, 'ED93B8'),
(26, 'jdoe@mail.com', '098765', '98765', 0.0, 'PROJECT_MANAGER', 'ACTIVE', '$2y$10$JyRWEtvKyatyWK6C3u9XTeb4.0.obyENRN9i1HoUbkdaJxXsaUveW', 0, 'El_Paso', 1, 14, 12, 'BCC401'),
(27, 'jdoe@mail.com', '987463524', 'yret', 0.0, 'PROJECT_MANAGER', 'ACTIVE', '$2y$10$t.bzXxelOjxbodCkNofrj.SNMVN/tTzjvAaHcg/m9YjlyWtIdxvAK', 0, 'Ciudad_Juarez', 3, 14, 1, '04E2F3'),
(28, 'jdoe@mail.com', 'bgfads', 'bcvxc', 0.0, 'PROJECT_MANAGER', 'ACTIVE', '$2y$10$sGxY0hkD3stInPhuHVsFJeJANNY63JJEydE7a/zfYKmNLJRCX2/VG', 0, 'Ciudad_Juarez', 3, 1, 12, '2093D3'),
(29, 'jdoe@mail.com', 'tesr ', 'controlller', 0.0, 'MACHINERY', 'ACTIVE', '$2y$10$DPzoTTZhMouK7Cf2gFDYVetgl1Qy9n.XswSeZPCBJLM4GVCBbEetu', 0, 'El_Paso', 1, 1, 12, '9989FE'),
(30, 'hector@alphabet.com', 'xHector', 'Gonzalesx', 0.0, 'CLIENT', 'ACTIVE', '$2y$10$RHaM3tyBCrLXQpksFs07Eetz7OS/yW2DeQMFDlYKFTbcgZsVwK4pW', 101, '', 0, 0, 0, '972522'),
(31, 'elon@open.ai', 'Elon', 'Cron', 0.0, 'CLIENT', 'ACTIVE', '$2y$10$T5iPYmp3WtILhAsTVVyxz..tiuQIokqVWK/dmR1hQhzh2w9UvwkpC', 100, '', 0, 0, 0, '7575FC'),
(32, 'jesus@alphabet.com', 'Jesus', 'Landeros', 0.0, 'CLIENT', 'ACTIVE', '$2y$10$rcM1sNhIMLCoZ0CeUH2LT.ysL8GlG4IFKKM9DylEgOEG3CvuNUhIm', 101, '', 0, 0, 0, '08096D'),
(33, 'dahan@nova.tech', 'Dahana', 'Martinez', 0.0, 'CLIENT', 'ACTIVE', '$2y$10$o01bxbY4zC3beNeWCsFw9ej1t56dWflGnqHEieIjyzFF31CzopaWm', 102, '', 0, 0, 0, '505102'),
(34, 'memo@alphabet.com', 'Memo', 'Medina', 0.0, 'CLIENT', 'ACTIVE', '$2y$10$D1BLH1R6CJwWFI5b0HdCquarlfSPpqsJNQ5vFbFoHvC3popHFTYYC', 101, '', 0, 0, 0, '053728'),
(35, 'bob@alphabet.com', 'Bob', 'Esponja', 0.0, 'CLIENT', 'ACTIVE', '$2y$10$KlMSzlXT4oAu6isIo3MEM.LQMxQtezaVt1l7z0m/TpT08.uNVjjkm', 101, '', 0, 0, 0, '53F1B1'),
(36, 'patricia@alphabet.com', 'Patricio', 'Estrella', 0.0, 'CLIENT', 'ACTIVE', '$2y$10$2T4NzRn5pEinvX/wODUT.Omt9Vm8HWZhziW9Uzm1XNWMuL1DYBvRK', 101, '', 0, 0, 0, 'ABA18C'),
(37, 'alvin@alphabet.com', 'Alvin', 'Ardilla', 0.0, 'CLIENT', 'ACTIVE', '$2y$10$eQ/atAdKWEhmD/5PR1QmL..i6vV5xGM52EgSVmcW4.DDGbZMkNGfa', 101, '', 0, 0, 0, '83158C');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
