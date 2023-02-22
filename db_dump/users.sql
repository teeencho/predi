-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 08-02-2023 a las 23:49:59
-- Versión del servidor: 10.3.38-MariaDB
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `edifici2_predi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--


--
-- Volcado de datos para la tabla `users`
-- martin / martin / 1234
--

INSERT INTO `users` (`username`, `password`, `role`, `created`, `modified`) VALUES
('martin', '$2y$10$2xrOjLFIkEwjJRB3N/BFOeeDir9ve6d.Immbn8Zm1FvsO9lJ5CukK', 'admin', NULL, NULL)

--
-- Índices para tablas volcadas
--


--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
