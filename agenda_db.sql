-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-10-2018 a las 09:34:21
-- Versión del servidor: 10.1.26-MariaDB
-- Versión de PHP: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `agenda_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `fechaInicio` date NOT NULL,
  `horaInicio` time DEFAULT NULL,
  `fechaFin` date DEFAULT NULL,
  `horaFin` time DEFAULT NULL,
  `fullday` tinyint(1) NOT NULL,
  `usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='AUTO_INCREMENT';

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id`, `titulo`, `fechaInicio`, `horaInicio`, `fechaFin`, `horaFin`, `fullday`, `usuario_id`) VALUES
(1, 'Prueba', '2018-10-08', '07:00:00', '2018-10-08', '19:00:00', 0, 4),
(30, 'Evento', '2018-10-18', '00:00:00', '0000-00-00', '00:00:00', 1, 4),
(33, 'Evento1', '2018-10-01', '00:00:00', '0000-00-00', '00:00:00', 1, 3),
(34, 'Evento2', '2018-10-17', '00:00:00', '0000-00-00', '00:00:00', 1, 3),
(39, 'Evento2', '2018-10-04', '00:00:00', '0000-00-00', '00:00:00', 1, 4),
(40, 'Evento3', '2018-10-06', '07:00:00', '2018-10-06', '09:30:00', 0, 4),
(46, 'Evento1', '2018-10-19', '07:00:00', '0000-00-00', '09:00:00', 0, 4),
(47, 'EventoPrueba', '2018-10-16', '07:00:00', '0000-00-00', '00:00:00', 0, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `psw` varchar(255) NOT NULL,
  `sexo` enum('Masculino','Femenino','','') NOT NULL,
  `nacimiento` date NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='AUTO_INCREMENT';

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `psw`, `sexo`, `nacimiento`, `nombre`) VALUES
(1, 'steven@gmail.com', '12345', 'Masculino', '1986-02-26', 'Steven Sevillano'),
(2, 'bella@hotmail.com', '123', 'Femenino', '1988-05-06', 'Bellanira Gamboa'),
(3, 'loida@mail.com', '$2y$10$Xl5PJNsww/7dHEvn0nGh/.rMlA34WAvMVNH3bgw4mvo.lfe88gLta', 'Femenino', '1950-10-16', 'Loida Renteria'),
(4, 'luis@mail.com', '$2y$10$b.90O7wmqw.ap7jmFagn4u2cE254CvkNDnTGYgAKcmQt38ytL2lJS', 'Masculino', '1976-10-18', 'Luis Eduardo'),
(5, 'jorge@mail.com', '$2y$10$eveqzCsMt9K0daX4w.nIR.oAWNhIAAQM5uSjvQhGr9vEbwlyxWnbW', 'Masculino', '1960-08-09', 'Jorge'),
(17, 'gloria@mail.com', '$2y$10$hOyYdH4XHGoB.6XrIYw10eCjJ/lv71eZ9kXMgR.zQAAXTynFrdMNq', 'Femenino', '1982-11-14', 'Gloria');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `eventos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
