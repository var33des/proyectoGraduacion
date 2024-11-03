-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-09-2024 a las 05:00:04
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `inventario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento_inventario`
--

CREATE TABLE `movimiento_inventario` (
  `idMovimiento` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `tipo` enum('entrada','salida') NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `movimiento_inventario`
--

INSERT INTO `movimiento_inventario` (`idMovimiento`, `idProducto`, `idUsuario`, `tipo`, `cantidad`, `fecha`) VALUES
(1, 2, 123, 'entrada', 50, '2024-09-19 22:44:49'),
(2, 3, 123, 'salida', 5, '2024-09-19 22:44:49'),
(3, 5, 788, 'entrada', 30, '2024-09-19 22:44:49'),
(4, 6, 123, 'entrada', 20, '2024-09-19 22:44:49'),
(5, 7, 788, 'salida', 10, '2024-09-19 22:44:49'),
(6, 8, 123, 'entrada', 15, '2024-09-19 22:44:49'),
(15, 2, 123, 'salida', 25, '2024-09-08 16:25:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idProducto` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `categoria` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `tipo_producto` varchar(255) NOT NULL,
  `idProveedor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idProducto`, `nombre`, `categoria`, `cantidad`, `precio`, `tipo_producto`, `idProveedor`) VALUES
(2, 'Smartphone Samsung Galaxy S21', 'Electrónica', 100, 999.99, '', 1),
(3, 'Televisor LG 75\" 4K', 'Electrónica', 28, 1999.99, '', 1),
(5, 'Silla de Oficina Ergonomica', 'Muebles', 75, 129.99, '', 3),
(6, 'Escritorio de bidrio', 'Muebles', 40, 249.99, '', NULL),
(7, 'Audífonos Sony WH-1000XM4', 'Electrónica', 120, 349.99, '', 2),
(8, 'Monitor dell 24 pulgadas', 'Electrónica', 60, 159.99, '', 2),
(9, 'Teclado Mecánico Corsair', 'Periféricos', 150, 99.99, '', 4),
(10, 'Mouse Logitech MX Master 3', 'Periféricos', 200, 89.99, '', 4),
(11, 'jbl ', 'bocinas-musica', 2, 1059.00, '', NULL),
(12, 'mouse', 'perifericos', 1, 200.00, '', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `idProveedor` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `contacto` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`idProveedor`, `nombre`, `contacto`, `telefono`, `direccion`) VALUES
(1, 'Proveedor Electrónica 1', 'Carlos Martínez', '555123456', 'Av. Electrónica 123'),
(2, 'Proveedor Electrónica 2', 'Laura Fernández', '555654321', 'Calle Tecnológica 456'),
(3, 'Proveedor Muebles 1', 'José Ramírez', '555789012', 'Calle Muebles 789'),
(4, 'Proveedor Periféricos 1', 'Ana Gómez', '555345678', 'Av. Periféricos 321');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `idReportes` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `fechaCreacion` datetime NOT NULL DEFAULT current_timestamp(),
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`idReportes`, `idUsuario`, `fechaCreacion`, `descripcion`) VALUES
(1, 123, '2024-09-19 22:45:08', 'Entrada de 50 Smartphones Samsung Galaxy S21.'),
(2, 123, '2024-09-19 22:45:08', 'Salida de 5 Televisores LG 75\".'),
(3, 788, '2024-09-19 22:45:08', 'Entrada de 30 Sillas de Oficina.'),
(4, 123, '2024-09-19 22:45:08', 'Salida de 10 Audífonos Sony WH-1000XM4.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idUsuario` int(6) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `correo_electronico` varchar(255) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `rol` enum('admin','usuario') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idUsuario`, `nombre`, `apellido`, `correo_electronico`, `clave`, `rol`) VALUES
(123, 'carlos', 'Varela', 'krlosvarela4@gmail.com', 'h4pypEqXOjZLl8UHSCZmRw==', 'admin'),
(788, 'fernando', 'Gonzales', 'hhhgdd', 'yLOTHW0kz6EcYfVfbiq8cw==', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `movimiento_inventario`
--
ALTER TABLE `movimiento_inventario`
  ADD PRIMARY KEY (`idMovimiento`),
  ADD KEY `idProducto` (`idProducto`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idProducto`),
  ADD KEY `productos_ibfk_1` (`idProveedor`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`idProveedor`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`idReportes`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idUsuario`),
  ADD UNIQUE KEY `correo_electronico` (`correo_electronico`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `movimiento_inventario`
--
ALTER TABLE `movimiento_inventario`
  MODIFY `idMovimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `idProveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `idReportes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `movimiento_inventario`
--
ALTER TABLE `movimiento_inventario`
  ADD CONSTRAINT `movimiento_inventario_ibfk_1` FOREIGN KEY (`idProducto`) REFERENCES `productos` (`idProducto`),
  ADD CONSTRAINT `movimiento_inventario_ibfk_2` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`idProveedor`) REFERENCES `proveedores` (`idProveedor`);

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
