-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-11-2020 a las 01:02:08
-- Versión del servidor: 10.4.14-MariaDB
-- Versión de PHP: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `myproject`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `id_alumno` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id`, `id_materia`, `id_alumno`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2020-11-17 02:46:38', '2020-11-17 02:46:38'),
(5, 1, 3, '2020-11-17 02:47:26', '2020-11-17 02:47:26'),
(7, 2, 3, '2020-11-17 02:48:26', '2020-11-17 02:48:26'),
(9, 2, 5, '2020-11-17 02:48:40', '2020-11-17 02:48:40'),
(10, 2, 6, '2020-11-17 02:50:37', '2020-11-17 02:50:37'),
(11, 2, 7, '2020-11-17 02:53:16', '2020-11-17 02:53:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id` int(11) NOT NULL,
  `materia` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `cupos` int(11) NOT NULL,
  `cuatrimestre` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id`, `materia`, `cupos`, `cuatrimestre`, `created_at`, `updated_at`) VALUES
(1, 'P3', 20, 3, '2020-11-17 02:29:16', '2020-11-17 02:29:16'),
(2, 'P4', 20, 3, '2020-11-17 02:29:31', '2020-11-17 02:29:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas`
--

CREATE TABLE `notas` (
  `id` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `nota` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_alumno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `notas`
--

INSERT INTO `notas` (`id`, `id_materia`, `nota`, `created_at`, `updated_at`, `id_alumno`) VALUES
(0, 2, 4, '2020-11-17 04:01:07', '2020-11-17 04:01:07', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(255) COLLATE utf8_spanish2_ci NOT NULL,
  `hash` varchar(250) COLLATE utf8_spanish2_ci NOT NULL,
  `area` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `nombre`, `hash`, `area`, `created_at`, `updated_at`) VALUES
(1, 'marcos', '', '355cb48fb7451157d6b6998ae30f0edd3e5b89b2ec4d84c55958237775a2bb0e86e8f0bb747ad579aa2d076fb7c46c9027fd9963e7718a46682a234dcb619864', -1, '2020-11-16 17:07:00', '2020-11-16 17:10:07'),
(3, 'flopa', '', '9cba574a2da0bd3f4ce8032db5f714826ec3cf512fe57db5cf8baf7d880aac72f1235283858b07fc6f76850ccc2d739439d927003c050e3d1e1da205c75c5dc5', 2, '2020-11-13 05:27:21', '2020-11-13 05:27:21'),
(4, 'pepe@mail.com', 'pepe', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', 1, '2020-11-17 01:56:11', '2020-11-17 01:56:11'),
(5, 'pep1e@mail.com', 'peeS', 'ba3253876aed6bc22d4a6ff53d8406c6ad864195ed144ab5c87621b6c233b548baeae6956df346ec8c17f5ea10f35ee3cbc514797ed7ddd3145464e2a0bab413', 1, '2020-11-17 01:56:58', '2020-11-17 01:56:58'),
(6, 'pep11e@mail.com', 'peeSd', 'adf1f830dbf41aa467d10c0a222029d5db682c18b93a05561912a355f69744402c54217e5ad71125ce4f88535b7263174783876ca450b4797636de8ee9276acc', 1, '2020-11-17 01:58:46', '2020-11-17 01:58:46'),
(7, 'admin@mail.com', 'Admin', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 1, '2020-11-17 02:02:05', '2020-11-17 02:02:05'),
(8, 'profe@mail.com', 'Profe', '23c7c16830c1094f9d583e5372d55f47790b6873f7a246621f5e0cc1ace01d3785a10ae4942796cfa3ab52aff7560aa3f9f5c562e69b84bd5138f397c755aca7', 2, '2020-11-17 03:06:20', '2020-11-17 03:06:20');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_materia` (`id_materia`,`id_alumno`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
