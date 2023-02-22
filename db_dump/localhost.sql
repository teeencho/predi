-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 08-02-2023 a las 23:41:35
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
CREATE DATABASE IF NOT EXISTS `edifici2_predi` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `edifici2_predi`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `edificios`
--

CREATE TABLE `edificios` (
  `id` int(10) UNSIGNED NOT NULL,
  `calle` varchar(50) NOT NULL DEFAULT '',
  `altura` int(11) NOT NULL,
  `territorio` int(11) NOT NULL,
  `manzana` char(1) DEFAULT '',
  `pisos_letras` tinyint(1) NOT NULL,
  `deptos_letras` tinyint(1) NOT NULL,
  `encargado` tinyint(1) NOT NULL,
  `pisos` int(11) NOT NULL,
  `deptos` int(11) NOT NULL,
  `planta_baja` tinyint(1) NOT NULL,
  `solo_pisos` tinyint(1) DEFAULT NULL,
  `deptos_numerados` tinyint(1) NOT NULL DEFAULT 0,
  `inicio_numeracion` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `llamadas`
--

CREATE TABLE `llamadas` (
  `id` int(10) UNSIGNED NOT NULL,
  `timbreid` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `atendio` tinyint(1) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `tiempo` mediumint(11) DEFAULT NULL,
  `notas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `markers`
--

CREATE TABLE `markers` (
  `id` int(11) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `revisita` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id` int(11) UNSIGNED NOT NULL,
  `nombre` varchar(20) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `revisitas`
--

CREATE TABLE `revisitas` (
  `id` int(11) UNSIGNED NOT NULL,
  `nombre` varchar(50) DEFAULT '',
  `direccion` varchar(50) DEFAULT '',
  `comentarios` text DEFAULT NULL,
  `clase` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `timbres`
--

CREATE TABLE `timbres` (
  `id` int(10) UNSIGNED NOT NULL,
  `edificioid` int(11) DEFAULT NULL,
  `row` int(11) DEFAULT NULL,
  `col` int(11) DEFAULT NULL,
  `visitas_count` int(11) DEFAULT NULL,
  `ultima_visita` datetime DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `llamadas_count` int(11) DEFAULT NULL,
  `notocar` tinyint(1) DEFAULT NULL,
  `notocar_fecha` date DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `nombre` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visitas`
--

CREATE TABLE `visitas` (
  `id` int(10) UNSIGNED NOT NULL,
  `timbreid` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `atendio` tinyint(1) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `edificios`
--
ALTER TABLE `edificios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `llamadas`
--
ALTER TABLE `llamadas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `markers`
--
ALTER TABLE `markers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notas`
--
ALTER TABLE `notas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `revisitas`
--
ALTER TABLE `revisitas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `timbres`
--
ALTER TABLE `timbres`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `visitas`
--
ALTER TABLE `visitas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `edificios`
--
ALTER TABLE `edificios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `llamadas`
--
ALTER TABLE `llamadas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `markers`
--
ALTER TABLE `markers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notas`
--
ALTER TABLE `notas`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `revisitas`
--
ALTER TABLE `revisitas`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `timbres`
--
ALTER TABLE `timbres`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `visitas`
--
ALTER TABLE `visitas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
