-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-11-2022 a las 09:24:09
-- Versión del servidor: 10.4.25-MariaDB
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pagos_bd`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20221111215812', '2022-11-11 22:59:29', 510);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `keys_save`
--

CREATE TABLE `keys_save` (
  `id` int(11) NOT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_key1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_key2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `api_key3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `keys_save`
--

INSERT INTO `keys_save` (`id`, `name`, `api_key1`, `api_key2`, `api_key3`) VALUES
(1, 'Paypal', 'AYtYXFBbT_NWqK8qk6fuXNOvpcsnWjSLbVcxVYc2VlMQU3QGSiJYX_JRdIuyAiaWG8QU9IGEnIHlMLSw', 'EGrsv5Thnft4G7F97OqRQHYNAMlJTwy9vdaxbL15Iy8RB9vY-U8CM5SJCLfXKAwI_nQrY7TKJ4PafYqG', 'QVl0WVhGQmJUX05XcUs4cWs2ZnVYTk92cGNzbldqU0xiVmN4VlljMlZsTVFVM1FHU2lKWVhfSlJkSXV5QWlhV0c4UVU5SUdFbklIbE1MU3c6RUdyc3Y1VGhuZnQ0RzdGOTdPcVJRSFlOQU1sSlR3eTl2ZGF4YkwxNUl5OFJCOXZZLVU4Q001U0pDTGZYS0F3SV9uUXJZN1RLSjRQYWZZcUc=');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `otra_info`
--

CREATE TABLE `otra_info` (
  `id` int(15) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `descripcion` varchar(60) NOT NULL,
  `cantidad` int(15) NOT NULL,
  `codigo_moneda` text NOT NULL,
  `monto_pagar` int(255) NOT NULL,
  `return_url` varchar(255) NOT NULL,
  `cancel_url` varchar(255) NOT NULL,
  `referencia` varchar(255) NOT NULL,
  `expira_dias` int(100) NOT NULL,
  `notificacion_url` varchar(255) NOT NULL,
  `lenguaje` text NOT NULL,
  `tipo_uso` tinyint(1) NOT NULL,
  `reason_id` int(15) NOT NULL,
  `name_metodo` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_contra`
--

CREATE TABLE `usuario_contra` (
  `usuario` varchar(60) DEFAULT NULL,
  `contraseña` varchar(60) DEFAULT NULL,
  `nombre_metodo` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario_contra`
--

INSERT INTO `usuario_contra` (`usuario`, `contraseña`, `nombre_metodo`) VALUES
('aherreramilet@gmail.com', 'Harold*1845', 'Tropipay');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `keys_save`
--
ALTER TABLE `keys_save`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `otra_info`
--
ALTER TABLE `otra_info`
  ADD PRIMARY KEY (`id`,`nombre`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `keys_save`
--
ALTER TABLE `keys_save`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `otra_info`
--
ALTER TABLE `otra_info`
  MODIFY `id` int(15) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
