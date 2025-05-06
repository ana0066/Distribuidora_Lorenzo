-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 06-05-2025 a las 07:05:48
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

use distribuidoral;

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`id`, `id_usuario`, `id_producto`, `cantidad`, `fecha_agregado`) VALUES
(5, 9, 28, 5, '2025-05-06 00:02:26'),
(6, 9, 3, 4, '2025-05-06 02:18:13'),
(7, 9, 29, 4, '2025-05-06 02:18:34'),
(8, 9, 2, 1, '2025-05-06 02:59:33'),
(9, 4, 29, 3, '2025-05-06 03:02:40'),
(10, NULL, 28, 1, '2025-05-06 03:19:09'),
(11, 1, 28, 12, '2025-05-06 04:52:46'),
(12, 1, 29, 1, '2025-05-06 05:00:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `id_usuario`, `total`, `fecha`) VALUES
(1, 9, 50000.00, '2025-05-05 19:30:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_items`
--

CREATE TABLE `pedido_items` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_items`
--

INSERT INTO `pedido_items` (`id`, `id_pedido`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 28, 1, 50000.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `existencia` int(11) NOT NULL,
  `urlImagen` varchar(255) NOT NULL,
  `categoria` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `nombre`, `valor`, `existencia`, `urlImagen`, `categoria`) VALUES
(2, 'mesedora', 1000.00, 2, 'https://www.ventamueblesvintage.com/wp-content/uploads/2021/02/NELO-scaled.jpg', NULL),
(3, 'Cama', 3000.00, 5, 'https://www.ilumeloutlet.com/cdn/shop/files/EMELYTN003FULL_600x.jpg?v=1714660536', NULL),
(28, 'Cama Queen size pillowtop', 50000.00, 4, '../uploads/1746416412_pillowtop.jfif', 'mobiliaria'),
(29, 'set de cubiertos de acero inoxidable', 500.00, 5, 'https://supplyrd.com/cdn/shop/products/image_7366ef4e-4378-4c52-99c5-f0252178b46b.jpg?v=1592137083', 'vajilla');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('usuario','admin','superadmin') DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `verificado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `contraseña`, `rol`, `token`, `verificado`) VALUES
(1, 'Brenda', 'brenda@gmail.com', '$2y$10$CtUvo/xHNW8jwFDoyDY3.Oqh0gpsI30HCMonEOY7WdmqF.faM9Kb.', 'usuario', NULL, 1),
(2, 'zaid', 'zaid@gmail.com', '$2y$10$DHXIIlGwabUn9EpwOqUpgubwl2PRcP8FMRohF4N/m3syD.Kf7BQxq', 'admin', 'ce8481fddc26f2d0adca4cf2157b7d9f', 0),
(3, 'nuevo prueba', 'prueba@gmail.com', '$2y$10$qKwpZ9tY0Iz4wakPNgNcU.TMr.6JDpoIA2Lihvc0IfRP3ZsxTTQ/u', '', NULL, 0),
(4, 'Cristina', 'mariaanabelmencia@gmail.com', '$2y$10$SRgA0Kv843PxVdjeGbyBreZqqiIJUakTS8.l4HFChMh.fmF3KdU9O', 'usuario', NULL, 1),
(5, 'prueba23', 'anibelmencializ@gmail.com', '$2y$10$0x8QOP5B8pS778Lr2k1kJO9Cs4J/anF28I/z0bChYbyiY.e/AwD/a', 'usuario', '0a065f9ddc3209f847fe88f895e222da', 0),
(9, 'ana', 'anabelmencia06@gmail.com', '$2y$10$WMD8p3C.1qACXOyJgRsQBO6ZTHSz6ug3XqMEQ6FYt.UPt/5onelM6', 'usuario', NULL, 1),
(10, 'Distribuidora Lorenzo', 'distribuidoralorenzo19@gmail.com', '$2y$10$eOHupHo9Ai5UrOXJozgfX.rgrPaW1PIM26iaY4s08MABQ45SRJHCa', 'superadmin', NULL, 1),
(13, 'Brenda Mencia', 'brendamencia31@gmail.com', '$2y$10$bYEoUIeTSYnnDDRYr0XgSu/5VYjHJnQVjg2OPo.1bON7sdtP85CJ2', 'usuario', NULL, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `products` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  ADD CONSTRAINT `pedido_items_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `pedido_items_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
