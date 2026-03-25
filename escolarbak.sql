-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         12.2.2-MariaDB - MariaDB Server
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.14.0.7165
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para escolar
CREATE DATABASE IF NOT EXISTS `escolar` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci */;
USE `escolar`;

-- Volcando estructura para tabla escolar.actividades_complementarias
DROP TABLE IF EXISTS `actividades_complementarias`;
CREATE TABLE IF NOT EXISTS `actividades_complementarias` (
  `id_actividad` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumno` int(11) DEFAULT NULL,
  `nombre_actividad` varchar(100) DEFAULT NULL,
  `creditos_obtenidos` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_actividad`),
  KEY `id_alumno` (`id_alumno`),
  CONSTRAINT `actividades_complementarias_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.actividades_complementarias: ~1 rows (aproximadamente)
INSERT INTO `actividades_complementarias` (`id_actividad`, `id_alumno`, `nombre_actividad`, `creditos_obtenidos`) VALUES
	(1, 1, 'Club de Atjedrez', 5);

-- Volcando estructura para tabla escolar.alumnos
DROP TABLE IF EXISTS `alumnos`;
CREATE TABLE IF NOT EXISTS `alumnos` (
  `id_alumno` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `matricula` int(11) DEFAULT NULL,
  `carrera` varchar(255) DEFAULT NULL,
  `semestre_actual` int(11) DEFAULT NULL,
  `telefono` varchar(10) DEFAULT NULL,
  `estatus` varchar(20) DEFAULT NULL,
  `creditos` int(11) DEFAULT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `id_horario` int(11) DEFAULT NULL,
  `nombre_completo` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_alumno`),
  KEY `id_usuario` (`id_usuario`),
  KEY `idgrupo_fk` (`id_grupo`),
  KEY `idhorario_fk` (`id_horario`),
  CONSTRAINT `alumnos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `idgrupo_fk` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`),
  CONSTRAINT `idhorario_fk` FOREIGN KEY (`id_horario`) REFERENCES `horarios` (`id_horario`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.alumnos: ~2 rows (aproximadamente)
INSERT INTO `alumnos` (`id_alumno`, `id_usuario`, `matricula`, `carrera`, `semestre_actual`, `telefono`, `estatus`, `creditos`, `id_grupo`, `id_horario`, `nombre_completo`) VALUES
	(1, 3, 221000100, 'Ingeniería en Sistemas Computacionales', 8, '8181787064', 'activo', 66, 1, 1, NULL),
	(4, 8, 266144, 'Ingeniería en Sistemas', 1, NULL, 'Activo', 0, 5, NULL, 'Gerardo Daniel Rodallegas Regalado'),
	(5, 9, 267109, 'Ingeniería en Sistemas', 1, NULL, 'Activo', 0, NULL, NULL, 'Lynda Mariel');

-- Volcando estructura para tabla escolar.asistencias
DROP TABLE IF EXISTS `asistencias`;
CREATE TABLE IF NOT EXISTS `asistencias` (
  `id_asistencia` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumno` int(11) DEFAULT NULL,
  `id_carga_academica` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `estatus` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_asistencia`),
  KEY `idalumno1_fk` (`id_alumno`),
  KEY `idcargaacademica_fk` (`id_carga_academica`),
  CONSTRAINT `idalumno1_fk` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`),
  CONSTRAINT `idcargaacademica_fk` FOREIGN KEY (`id_carga_academica`) REFERENCES `carga_academica` (`id_carga_academica`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.asistencias: ~0 rows (aproximadamente)
INSERT INTO `asistencias` (`id_asistencia`, `id_alumno`, `id_carga_academica`, `fecha`, `estatus`) VALUES
	(1, 1, 3, '2026-03-25 16:33:47', 'Faltó');

-- Volcando estructura para tabla escolar.aspirantes
DROP TABLE IF EXISTS `aspirantes`;
CREATE TABLE IF NOT EXISTS `aspirantes` (
  `id_aspirante` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(100) DEFAULT NULL,
  `ficha_referencia` varchar(255) DEFAULT NULL,
  `pago_ficha_realizada` varchar(255) DEFAULT NULL,
  `calificacion_examen` int(11) DEFAULT NULL,
  `docs_entregados` varchar(255) DEFAULT NULL,
  `pago_inscripcion_realizado` varchar(255) DEFAULT NULL,
  `aceptado` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_aspirante`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.aspirantes: ~0 rows (aproximadamente)
INSERT INTO `aspirantes` (`id_aspirante`, `nombre_completo`, `ficha_referencia`, `pago_ficha_realizada`, `calificacion_examen`, `docs_entregados`, `pago_inscripcion_realizado`, `aceptado`) VALUES
	(1, 'Gerardo Daniel Rodallegas Regalado', 'FCH-2026-5685', '1', 70, '1', '1', '1'),
	(2, 'Lynda Mariel', 'FCH-2026-9707', '1', 90, '1', '1', '1');

-- Volcando estructura para tabla escolar.aula_virtual_materiales
DROP TABLE IF EXISTS `aula_virtual_materiales`;
CREATE TABLE IF NOT EXISTS `aula_virtual_materiales` (
  `id_aula_material` int(11) NOT NULL AUTO_INCREMENT,
  `id_carga_academica` int(11) DEFAULT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `ruta_archivo` varchar(255) DEFAULT NULL,
  `fecha_limite` datetime DEFAULT NULL,
  PRIMARY KEY (`id_aula_material`),
  KEY `id_carga_academica` (`id_carga_academica`),
  CONSTRAINT `aula_virtual_materiales_ibfk_1` FOREIGN KEY (`id_carga_academica`) REFERENCES `carga_academica` (`id_carga_academica`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.aula_virtual_materiales: ~0 rows (aproximadamente)
INSERT INTO `aula_virtual_materiales` (`id_aula_material`, `id_carga_academica`, `titulo`, `tipo`, `ruta_archivo`, `fecha_limite`) VALUES
	(1, 1, 'Base de datos SQL', 'tarea', 'subirArch/1774308061_Document 2.pdf', '2026-03-23 23:59:00'),
	(2, 7, 'Buenas prácticas', 'proyecto', 'subirArch/1774369741_certificado_20260223.pdf', '2026-03-24 10:30:00');

-- Volcando estructura para tabla escolar.calificaciones_activas
DROP TABLE IF EXISTS `calificaciones_activas`;
CREATE TABLE IF NOT EXISTS `calificaciones_activas` (
  `id_calificacion_activa` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumno` int(11) DEFAULT NULL,
  `unidad_1` decimal(10,0) DEFAULT NULL,
  `unidad_2` decimal(10,0) DEFAULT NULL,
  `unidad_3` decimal(10,0) DEFAULT NULL,
  `unidad_4` decimal(10,0) DEFAULT NULL,
  `unidad_5` decimal(10,0) DEFAULT NULL,
  `unidad_6` decimal(10,0) DEFAULT NULL,
  `promedio_final` decimal(10,0) DEFAULT NULL,
  `acta_cerrada` varchar(255) DEFAULT NULL,
  `id_materia` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_calificacion_activa`),
  KEY `id_alumno` (`id_alumno`),
  KEY `idmateria1_fk` (`id_materia`),
  CONSTRAINT `calificaciones_activas_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`),
  CONSTRAINT `idmateria1_fk` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.calificaciones_activas: ~2 rows (aproximadamente)
INSERT INTO `calificaciones_activas` (`id_calificacion_activa`, `id_alumno`, `unidad_1`, `unidad_2`, `unidad_3`, `unidad_4`, `unidad_5`, `unidad_6`, `promedio_final`, `acta_cerrada`, `id_materia`) VALUES
	(1, 1, 70, 80, 90, 100, 70, NULL, 82, 'si', 1),
	(2, 1, 70, 70, 70, 100, NULL, NULL, 78, 'si', 3),
	(3, 1, 70, 80, 80, NULL, NULL, NULL, 77, 'si', 8);

-- Volcando estructura para tabla escolar.carga_academica
DROP TABLE IF EXISTS `carga_academica`;
CREATE TABLE IF NOT EXISTS `carga_academica` (
  `id_carga_academica` int(11) NOT NULL AUTO_INCREMENT,
  `id_docente` int(11) DEFAULT NULL,
  `id_materia` int(11) DEFAULT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `id_ciclo` int(11) DEFAULT NULL,
  `acta_abierta` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id_carga_academica`),
  KEY `id_docente` (`id_docente`),
  KEY `id_materia` (`id_materia`),
  KEY `id_grupo` (`id_grupo`),
  KEY `id_ciclo` (`id_ciclo`),
  CONSTRAINT `carga_academica_ibfk_1` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`),
  CONSTRAINT `carga_academica_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`),
  CONSTRAINT `carga_academica_ibfk_3` FOREIGN KEY (`id_grupo`) REFERENCES `grupos` (`id_grupo`),
  CONSTRAINT `carga_academica_ibfk_4` FOREIGN KEY (`id_ciclo`) REFERENCES `ciclos_escolares` (`id_ciclo`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.carga_academica: ~6 rows (aproximadamente)
INSERT INTO `carga_academica` (`id_carga_academica`, `id_docente`, `id_materia`, `id_grupo`, `id_ciclo`, `acta_abierta`) VALUES
	(1, 1, 1, 1, 1, 1),
	(2, 2, 2, 1, 1, 1),
	(3, 3, 3, 1, 1, 1),
	(4, 4, 4, 1, 1, 1),
	(6, 4, 5, 1, 1, 1),
	(7, 6, 7, 1, 1, 1),
	(8, 3, 8, 1, 6, 1);

-- Volcando estructura para tabla escolar.carga_alumnos
DROP TABLE IF EXISTS `carga_alumnos`;
CREATE TABLE IF NOT EXISTS `carga_alumnos` (
  `id_registro` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumno` int(11) DEFAULT NULL,
  `id_carga_academica` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_registro`),
  KEY `id_alumno` (`id_alumno`),
  KEY `id_carga_academica` (`id_carga_academica`),
  CONSTRAINT `1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`) ON DELETE CASCADE,
  CONSTRAINT `2` FOREIGN KEY (`id_carga_academica`) REFERENCES `carga_academica` (`id_carga_academica`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla escolar.carga_alumnos: ~0 rows (aproximadamente)
INSERT INTO `carga_alumnos` (`id_registro`, `id_alumno`, `id_carga_academica`) VALUES
	(1, 4, 1);

-- Volcando estructura para tabla escolar.ciclos_escolares
DROP TABLE IF EXISTS `ciclos_escolares`;
CREATE TABLE IF NOT EXISTS `ciclos_escolares` (
  `id_ciclo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_periodo` varchar(100) DEFAULT NULL,
  `activo` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_ciclo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.ciclos_escolares: ~3 rows (aproximadamente)
INSERT INTO `ciclos_escolares` (`id_ciclo`, `nombre_periodo`, `activo`) VALUES
	(1, 'febrero-junio 2026', 'si'),
	(3, 'agosto-diciembre 2025', 'no'),
	(4, 'agosto-diciembre 2025', 'no'),
	(5, 'enero-junio 2025', 'No'),
	(6, 'agosto-diciembre 2026', 'Sí');

-- Volcando estructura para tabla escolar.docentes
DROP TABLE IF EXISTS `docentes`;
CREATE TABLE IF NOT EXISTS `docentes` (
  `id_docente` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre_completo` varchar(100) DEFAULT NULL,
  `estatus` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_docente`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `docentes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.docentes: ~4 rows (aproximadamente)
INSERT INTO `docentes` (`id_docente`, `id_usuario`, `nombre_completo`, `estatus`) VALUES
	(1, 2, 'ING. ROMAN ANTONIO GOMEZ PERALTA', 'Activo'),
	(2, NULL, 'ING. NIRIA GONZALEZ ORTIZ', 'Activo'),
	(3, 11, 'ING. CESAR MOISES ROSALES RAMIREZ', 'Activo'),
	(4, NULL, 'M.C. OMAR ELIO TORRES CASTILLO', 'Activo'),
	(5, NULL, 'M.C. OMAR ELIO TORRES CASTILLO', 'Activo'),
	(6, 10, 'ING. PABLO ULISES', 'Activo');

-- Volcando estructura para tabla escolar.finanzas_adeudos
DROP TABLE IF EXISTS `finanzas_adeudos`;
CREATE TABLE IF NOT EXISTS `finanzas_adeudos` (
  `id_adeudo` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumno` int(11) DEFAULT NULL,
  `concepto_pago` varchar(100) DEFAULT NULL,
  `monto` int(11) DEFAULT NULL,
  `fecha_vencimiento` datetime DEFAULT NULL,
  `pagado` varchar(20) DEFAULT NULL,
  `referencia_bancaria` varchar(50) DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  PRIMARY KEY (`id_adeudo`),
  KEY `idalumno2_fk` (`id_alumno`),
  CONSTRAINT `idalumno2_fk` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.finanzas_adeudos: ~2 rows (aproximadamente)
INSERT INTO `finanzas_adeudos` (`id_adeudo`, `id_alumno`, `concepto_pago`, `monto`, `fecha_vencimiento`, `pagado`, `referencia_bancaria`, `fecha_pago`) VALUES
	(1, 1, 'ADSCRIPCION ACÁDEMICA', 3260, '2026-01-21 00:00:00', 'Pagado', '22100010087844841275', NULL),
	(2, 1, 'INGLÉS', 1200, '2026-01-21 00:00:00', 'Pendiente', '22100010087844841275', NULL);

-- Volcando estructura para tabla escolar.grupos
DROP TABLE IF EXISTS `grupos`;
CREATE TABLE IF NOT EXISTS `grupos` (
  `id_grupo` int(11) NOT NULL AUTO_INCREMENT,
  `id_ciclo` int(11) DEFAULT NULL,
  `nombre_grupo` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_grupo`),
  KEY `id_ciclo` (`id_ciclo`),
  CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`id_ciclo`) REFERENCES `ciclos_escolares` (`id_ciclo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.grupos: ~1 rows (aproximadamente)
INSERT INTO `grupos` (`id_grupo`, `id_ciclo`, `nombre_grupo`) VALUES
	(1, 1, '8A'),
	(4, 1, '5A'),
	(5, 6, '1A');

-- Volcando estructura para tabla escolar.horarios
DROP TABLE IF EXISTS `horarios`;
CREATE TABLE IF NOT EXISTS `horarios` (
  `id_horario` int(11) NOT NULL AUTO_INCREMENT,
  `id_carga_academica` int(11) DEFAULT NULL,
  `dia_semana` varchar(20) DEFAULT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `aula` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_horario`),
  KEY `id_carga_academica` (`id_carga_academica`),
  CONSTRAINT `horarios_ibfk_1` FOREIGN KEY (`id_carga_academica`) REFERENCES `carga_academica` (`id_carga_academica`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.horarios: ~4 rows (aproximadamente)
INSERT INTO `horarios` (`id_horario`, `id_carga_academica`, `dia_semana`, `hora_inicio`, `hora_fin`, `aula`) VALUES
	(1, 1, 'lunes', '07:50:00', '09:30:00', 'A7'),
	(2, 2, 'martes', '08:40:00', '09:30:00', 'LAB ISC'),
	(3, 3, 'martes', '11:10:00', '12:50:00', 'A7'),
	(4, 4, 'lunes', '07:00:00', '07:50:00', 'A7'),
	(6, 8, 'lunes', '10:20:00', '11:10:00', 'A7');

-- Volcando estructura para tabla escolar.kardex
DROP TABLE IF EXISTS `kardex`;
CREATE TABLE IF NOT EXISTS `kardex` (
  `id_kardex` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumno` int(11) DEFAULT NULL,
  `id_materia` int(11) DEFAULT NULL,
  `id_ciclo` int(11) DEFAULT NULL,
  `calificacion_definitiva` int(11) DEFAULT NULL,
  `estatus_aprobacion` varchar(20) DEFAULT NULL,
  `oportunidad` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_kardex`),
  UNIQUE KEY `idx_alumno_materia` (`id_alumno`,`id_materia`),
  KEY `idalumno_fk` (`id_alumno`),
  KEY `idciclo_fk` (`id_ciclo`),
  KEY `idmateria_fk` (`id_materia`),
  CONSTRAINT `idalumno_fk` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`),
  CONSTRAINT `idciclo_fk` FOREIGN KEY (`id_ciclo`) REFERENCES `ciclos_escolares` (`id_ciclo`),
  CONSTRAINT `idmateria_fk` FOREIGN KEY (`id_materia`) REFERENCES `materias` (`id_materia`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.kardex: ~4 rows (aproximadamente)
INSERT INTO `kardex` (`id_kardex`, `id_alumno`, `id_materia`, `id_ciclo`, `calificacion_definitiva`, `estatus_aprobacion`, `oportunidad`) VALUES
	(1, 1, 8, 6, 77, 'Aprobado', 'Ordinario');

-- Volcando estructura para tabla escolar.materias
DROP TABLE IF EXISTS `materias`;
CREATE TABLE IF NOT EXISTS `materias` (
  `id_materia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_materia` varchar(100) DEFAULT NULL,
  `creditos` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_materia`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.materias: ~6 rows (aproximadamente)
INSERT INTO `materias` (`id_materia`, `nombre_materia`, `creditos`) VALUES
	(1, 'Administración de Bases de Datos', 5),
	(2, 'Administración de Redes', 4),
	(3, 'Administración de Servidores', 7),
	(4, 'Programación Lógica y Funcional', 7),
	(5, 'Lenguajes Autómatas 2', 5),
	(7, 'Programación Web', 5),
	(8, 'Sistemas Gestores de Bases de Datos', 6);

-- Volcando estructura para tabla escolar.mensajes
DROP TABLE IF EXISTS `mensajes`;
CREATE TABLE IF NOT EXISTS `mensajes` (
  `id_mensaje` int(11) NOT NULL AUTO_INCREMENT,
  `id_docente` int(11) DEFAULT NULL,
  `id_alumno` int(11) DEFAULT NULL,
  `asunto` varchar(255) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `fecha_envio` datetime DEFAULT current_timestamp(),
  `leido` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id_mensaje`),
  KEY `id_docente` (`id_docente`),
  KEY `id_alumno` (`id_alumno`),
  CONSTRAINT `1` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`),
  CONSTRAINT `2` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla escolar.mensajes: ~0 rows (aproximadamente)
INSERT INTO `mensajes` (`id_mensaje`, `id_docente`, `id_alumno`, `asunto`, `mensaje`, `fecha_envio`, `leido`) VALUES
	(1, 1, 1, 'excelencia', 'gracias por estudiar :)', '2026-03-23 17:13:25', 0),
	(2, 6, 5, 'bienvenida', 'Bienvenida al ITS San Pedro!', '2026-03-24 10:29:43', 0);

-- Volcando estructura para tabla escolar.mensajes_admin
DROP TABLE IF EXISTS `mensajes_admin`;
CREATE TABLE IF NOT EXISTS `mensajes_admin` (
  `id_mensaje` int(11) NOT NULL AUTO_INCREMENT,
  `id_docente` int(11) DEFAULT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `asunto` varchar(255) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `fecha_envio` datetime DEFAULT current_timestamp(),
  `leido` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id_mensaje`),
  KEY `id_docente` (`id_docente`),
  KEY `id_admin` (`id_admin`),
  CONSTRAINT `1` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`),
  CONSTRAINT `2` FOREIGN KEY (`id_admin`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla escolar.mensajes_admin: ~2 rows (aproximadamente)
INSERT INTO `mensajes_admin` (`id_mensaje`, `id_docente`, `id_admin`, `asunto`, `mensaje`, `fecha_envio`, `leido`) VALUES
	(1, 6, 1, 'Solicitud para reabrir la acta', 'El docente ING. PABLO ULISES está solicitando abrir las actas para modificación.', '2026-03-25 09:02:04', 1),
	(2, 6, 1, 'Solicitud para reabrir la acta', 'El docente ING. PABLO ULISES está solicitando abrir las actas para modificación.', '2026-03-25 09:45:58', 0),
	(3, 3, 1, 'Solicitud para reabrir la acta', 'El docente ING. CESAR MOISES ROSALES RAMIREZ está solicitando abrir las actas para modificación.', '2026-03-25 10:34:55', 0);

-- Volcando estructura para tabla escolar.seguimiento_academico
DROP TABLE IF EXISTS `seguimiento_academico`;
CREATE TABLE IF NOT EXISTS `seguimiento_academico` (
  `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumno` int(11) DEFAULT NULL,
  `id_docente` int(11) DEFAULT NULL,
  `obersevaciones` varchar(255) DEFAULT NULL,
  `nivel_riesgo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_seguimiento`),
  KEY `id_alumno` (`id_alumno`),
  KEY `id_docente` (`id_docente`),
  CONSTRAINT `seguimiento_academico_ibfk_1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`),
  CONSTRAINT `seguimiento_academico_ibfk_2` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.seguimiento_academico: ~0 rows (aproximadamente)

-- Volcando estructura para tabla escolar.servicio_social
DROP TABLE IF EXISTS `servicio_social`;
CREATE TABLE IF NOT EXISTS `servicio_social` (
  `id_servicio` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumno` int(11) DEFAULT NULL,
  `horas_liberadas` int(11) DEFAULT NULL,
  `ruta_carta_aceptacion` varchar(255) DEFAULT NULL,
  `ruta_carta_liberacion` varchar(255) DEFAULT NULL,
  `ruta_reporte1` varchar(255) DEFAULT NULL,
  `ruta_reporte2` varchar(255) DEFAULT NULL,
  `ruta_reporte3` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_servicio`),
  KEY `idalumno3_fk` (`id_alumno`),
  CONSTRAINT `idalumno3_fk` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.servicio_social: ~0 rows (aproximadamente)

-- Volcando estructura para tabla escolar.usuarios
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `correo` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.usuarios: ~7 rows (aproximadamente)
INSERT INTO `usuarios` (`id_usuario`, `correo`, `password`, `rol`) VALUES
	(1, 'mmtz5818@gmail.com', '1234', 'administrativo'),
	(2, 'abc@gmail.com', '123456', 'docente'),
	(3, '1234@gmail.com', '123456789', 'alumno'),
	(6, 'takafallingjin@gmail.com', 'typhlosion', 'alumno'),
	(8, 'A266144@tecsanpedro.edu.mx', '12345678', 'alumno'),
	(9, 'A267109@tecsanpedro.edu.mx', '12345678', 'alumno'),
	(10, 'profe123@gmail.com', 'profe123', 'docente'),
	(11, 'profe1234@gmail.com', '123', 'docente');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
