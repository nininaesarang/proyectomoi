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
DROP DATABASE IF EXISTS `escolar`;
CREATE DATABASE IF NOT EXISTS `escolar` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci */;
USE `escolar`;

-- Volcando estructura para tabla escolar.actividades_complementarias
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
REPLACE INTO `actividades_complementarias` (`id_actividad`, `id_alumno`, `nombre_actividad`, `creditos_obtenidos`) VALUES
	(1, 1, 'Club de Atletismo', 5);

-- Volcando estructura para procedimiento escolar.actualizar_club
DELIMITER //
CREATE PROCEDURE `actualizar_club`(IN p_id_usuario INT, IN p_actividad_nueva VARCHAR(100))
BEGIN
    UPDATE actividades_complementarias 
    SET nombre_actividad = p_actividad_nueva 
    WHERE id_alumno = (SELECT id_alumno FROM alumnos WHERE id_usuario = p_id_usuario);
END//
DELIMITER ;

-- Volcando estructura para procedimiento escolar.actualizar_perfil
DELIMITER //
CREATE PROCEDURE `actualizar_perfil`(IN p_id_usuario INT, IN p_nuevo_telefono VARCHAR(20), IN p_nuevo_correo VARCHAR(100))
BEGIN
    UPDATE alumnos 
    SET telefono = p_nuevo_telefono 
    WHERE id_usuario = p_id_usuario;

    UPDATE usuarios 
    SET correo = p_nuevo_correo 
    WHERE id_usuario = p_id_usuario;
END//
DELIMITER ;

-- Volcando estructura para tabla escolar.alumnos
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
REPLACE INTO `alumnos` (`id_alumno`, `id_usuario`, `matricula`, `carrera`, `semestre_actual`, `telefono`, `estatus`, `creditos`, `id_grupo`, `id_horario`, `nombre_completo`) VALUES
	(1, 3, 221000100, 'Ingeniería en Sistemas Computacionales', 8, '8711547123', 'activo', 66, 1, 1, NULL),
	(4, 8, 266144, 'Ingeniería en Sistemas', 1, NULL, 'Activo', 0, 5, NULL, 'Gerardo Daniel Rodallegas Regalado'),
	(5, 9, 267109, 'Ingeniería en Sistemas', 1, NULL, 'Activo', 0, NULL, NULL, 'Lynda Mariel');

-- Volcando estructura para tabla escolar.asistencias
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.asistencias: ~3 rows (aproximadamente)
REPLACE INTO `asistencias` (`id_asistencia`, `id_alumno`, `id_carga_academica`, `fecha`, `estatus`) VALUES
	(1, 1, 3, '2026-03-25 16:33:47', 'Faltó'),
	(2, 1, 1, '2026-04-13 19:34:05', 'Faltó'),
	(3, 1, 1, '2026-04-13 19:34:16', 'Retardo');

-- Volcando estructura para tabla escolar.aspirantes
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.aspirantes: ~0 rows (aproximadamente)
REPLACE INTO `aspirantes` (`id_aspirante`, `nombre_completo`, `ficha_referencia`, `pago_ficha_realizada`, `calificacion_examen`, `docs_entregados`, `pago_inscripcion_realizado`, `aceptado`) VALUES
	(1, 'Gerardo Daniel Rodallegas Regalado', 'FCH-2026-5685', '1', 70, '1', '1', '1'),
	(2, 'Lynda Mariel', 'FCH-2026-9707', '1', 90, '1', '1', '1'),
	(3, 'Brian Guadalupe', 'FCH-2026-8900', '0', NULL, '0', '0', '0');

-- Volcando estructura para tabla escolar.aula_virtual_materiales
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.aula_virtual_materiales: ~0 rows (aproximadamente)
REPLACE INTO `aula_virtual_materiales` (`id_aula_material`, `id_carga_academica`, `titulo`, `tipo`, `ruta_archivo`, `fecha_limite`) VALUES
	(1, 1, 'Base de datos SQL', 'tarea', 'subirArch/1774308061_Document 2.pdf', '2026-03-23 23:59:00'),
	(2, 7, 'Buenas prácticas', 'proyecto', 'subirArch/1774369741_certificado_20260223.pdf', '2026-03-24 10:30:00'),
	(3, 1, 'Buenas prácticas', 'material', 'subirArch/1776101725_mimo-c.pdf', '2026-04-14 11:35:00');

-- Volcando estructura para tabla escolar.calificaciones_activas
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
REPLACE INTO `calificaciones_activas` (`id_calificacion_activa`, `id_alumno`, `unidad_1`, `unidad_2`, `unidad_3`, `unidad_4`, `unidad_5`, `unidad_6`, `promedio_final`, `acta_cerrada`, `id_materia`) VALUES
	(1, 1, 70, 80, 90, 100, 70, 80, 82, 'si', 1),
	(2, 1, 70, 70, 70, 100, NULL, NULL, 78, 'si', 3),
	(3, 1, 70, 80, 80, NULL, NULL, NULL, 77, 'si', 8);

-- Volcando estructura para tabla escolar.carga_academica
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.carga_academica: ~6 rows (aproximadamente)
REPLACE INTO `carga_academica` (`id_carga_academica`, `id_docente`, `id_materia`, `id_grupo`, `id_ciclo`, `acta_abierta`) VALUES
	(1, 1, 1, 1, 1, 1),
	(2, 2, 2, 1, 1, 1),
	(3, 3, 3, 1, 1, 1),
	(4, 4, 4, 1, 1, 1),
	(6, 4, 5, 1, 1, 1),
	(7, 6, 7, 1, 1, 1),
	(8, 3, 8, 1, 6, 1),
	(9, 7, 7, 1, 1, 1);

-- Volcando estructura para tabla escolar.carga_alumnos
CREATE TABLE IF NOT EXISTS `carga_alumnos` (
  `id_registro` int(11) NOT NULL AUTO_INCREMENT,
  `id_alumno` int(11) DEFAULT NULL,
  `id_carga_academica` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_registro`),
  KEY `id_alumno` (`id_alumno`),
  KEY `id_carga_academica` (`id_carga_academica`),
  CONSTRAINT `1` FOREIGN KEY (`id_alumno`) REFERENCES `alumnos` (`id_alumno`) ON DELETE CASCADE,
  CONSTRAINT `2` FOREIGN KEY (`id_carga_academica`) REFERENCES `carga_academica` (`id_carga_academica`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla escolar.carga_alumnos: ~2 rows (aproximadamente)
REPLACE INTO `carga_alumnos` (`id_registro`, `id_alumno`, `id_carga_academica`) VALUES
	(2, 4, 1),
	(3, 4, 2);

-- Volcando estructura para tabla escolar.ciclos_escolares
CREATE TABLE IF NOT EXISTS `ciclos_escolares` (
  `id_ciclo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_periodo` varchar(100) DEFAULT NULL,
  `activo` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_ciclo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.ciclos_escolares: ~3 rows (aproximadamente)
REPLACE INTO `ciclos_escolares` (`id_ciclo`, `nombre_periodo`, `activo`) VALUES
	(1, 'febrero-junio 2026', 'si'),
	(3, 'agosto-diciembre 2025', 'no'),
	(4, 'agosto-diciembre 2025', 'no'),
	(5, 'enero-junio 2025', 'No'),
	(6, 'agosto-diciembre 2026', 'Sí'),
	(7, 'febrero-junio 2025', 'No');

-- Volcando estructura para procedimiento escolar.consultar_calificaciones
DELIMITER //
CREATE PROCEDURE `consultar_calificaciones`(IN p_id_usuario INT)
BEGIN
    SELECT 
        g.nombre_grupo,
        m.nombre_materia,
        d.nombre_completo,
        cal.unidad_1,
        cal.unidad_2,
        cal.unidad_3, 
        cal.unidad_4,
        cal.unidad_5,
        cal.unidad_6, 
        cal.promedio_final,
        ce.nombre_periodo
    FROM alumnos a
    INNER JOIN grupos g ON a.id_grupo = g.id_grupo
    INNER JOIN carga_academica ca ON g.id_grupo = ca.id_grupo
    INNER JOIN materias m ON m.id_materia = ca.id_materia
    INNER JOIN docentes d ON d.id_docente = ca.id_docente
    INNER JOIN ciclos_escolares ce ON ce.id_ciclo = ca.id_ciclo
    LEFT JOIN calificaciones_activas cal ON cal.id_alumno = a.id_alumno 
        AND cal.id_materia = m.id_materia
    WHERE a.id_usuario = p_id_usuario
    ORDER BY m.nombre_materia ASC;
END//
DELIMITER ;

-- Volcando estructura para procedimiento escolar.consultar_club
DELIMITER //
CREATE PROCEDURE `consultar_club`(IN p_id_usuario INT)
BEGIN
	SELECT a.*, g.nombre_grupo, u.correo, ac.nombre_actividad
   FROM
    alumnos a
   left join usuarios u on a.id_usuario = u.id_usuario
   left join grupos g on a.id_grupo = g.id_grupo
   left join actividades_complementarias ac on a.id_alumno = ac.id_alumno
   where a.id_usuario = p_id_usuario;
END//
DELIMITER ;

-- Volcando estructura para procedimiento escolar.consultar_comentarios
DELIMITER //
CREATE PROCEDURE `consultar_comentarios`(IN p_id_usuario INT)
BEGIN
    SELECT
        m.asunto,
        m.mensaje,
        m.fecha_envio,
        d.nombre_completo
    FROM mensajes m
    INNER JOIN docentes d ON m.id_docente = d.id_docente
    INNER JOIN alumnos a ON m.id_alumno = a.id_alumno
    WHERE a.id_usuario = p_id_usuario
    ORDER BY m.fecha_envio DESC;
END//
DELIMITER ;

-- Volcando estructura para procedimiento escolar.consultar_horario
DELIMITER //
CREATE PROCEDURE `consultar_horario`(IN p_id_usuario INT)
BEGIN
    SELECT 
        g.nombre_grupo,
        h.dia_semana,
        h.hora_inicio,
        h.hora_fin,
        h.aula,
        m.nombre_materia,
        d.nombre_completo,
        ce.nombre_periodo
    FROM alumnos a
    INNER JOIN grupos g ON a.id_grupo = g.id_grupo
    INNER JOIN carga_academica ca ON g.id_grupo = ca.id_grupo
    INNER JOIN horarios h ON h.id_carga_academica = ca.id_carga_academica
    INNER JOIN materias m ON m.id_materia = ca.id_materia
    INNER JOIN docentes d ON d.id_docente = ca.id_docente
    INNER JOIN ciclos_escolares ce ON ce.id_ciclo = ca.id_ciclo
    WHERE a.id_usuario = p_id_usuario
    ORDER BY 
        FIELD(h.dia_semana, 'lunes', 'martes', 'miercoles', 'jueves', 'viernes'), 
        h.hora_inicio ASC;
END//
DELIMITER ;

-- Volcando estructura para procedimiento escolar.consultar_kardex
DELIMITER //
CREATE PROCEDURE `consultar_kardex`(IN p_id_usuario INT)
BEGIN
	SELECT k.calificacion_definitiva,
    k.estatus_aprobacion, k.oportunidad, 
    m.nombre_materia, ce.nombre_periodo
   FROM alumnos a
   inner join kardex k on k.id_alumno = a.id_alumno
   inner join materias m on k.id_materia = m.id_materia
   inner join ciclos_escolares ce on ce.id_ciclo = k.id_ciclo
   where a.id_usuario = p_id_usuario
   ORDER BY ce.id_ciclo DESC, m.nombre_materia ASC;
END//
DELIMITER ;

-- Volcando estructura para procedimiento escolar.consultar_pagos
DELIMITER //
CREATE PROCEDURE `consultar_pagos`(IN p_id_usuario INT)
BEGIN
    SELECT 
        a.matricula,
        fa.concepto_pago,
        fa.fecha_vencimiento,
        fa.pagado,
        fa.referencia_bancaria,
        fa.monto
    FROM alumnos a
    LEFT JOIN finanzas_adeudos fa ON a.id_alumno = fa.id_alumno
    WHERE a.id_usuario = p_id_usuario
    ORDER BY fa.fecha_vencimiento ASC;
END//
DELIMITER ;

-- Volcando estructura para procedimiento escolar.consultar_perfil
DELIMITER //
CREATE PROCEDURE `consultar_perfil`(IN p_id_usuario INT)
BEGIN
    SELECT 
        a.id_alumno,
        a.id_usuario,
        a.matricula,
        a.carrera,
        a.semestre_actual,
        u.correo,
        a.telefono,
        a.estatus,
        a.creditos,
        g.nombre_grupo
    FROM alumnos a
    LEFT JOIN grupos g ON a.id_grupo = g.id_grupo
    LEFT JOIN usuarios u ON a.id_usuario = u.id_usuario
    WHERE a.id_usuario = p_id_usuario;
END//
DELIMITER ;

-- Volcando estructura para procedimiento escolar.consultar_tarea
DELIMITER //
CREATE PROCEDURE `consultar_tarea`()
BEGIN
    SELECT 
        titulo,
        tipo,
        ruta_archivo,
        fecha_limite
    FROM 
        aula_virtual_materiales
    ORDER BY 
        fecha_limite ASC;
END//
DELIMITER ;

-- Volcando estructura para tabla escolar.docentes
CREATE TABLE IF NOT EXISTS `docentes` (
  `id_docente` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) DEFAULT NULL,
  `nombre_completo` varchar(100) DEFAULT NULL,
  `estatus` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_docente`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `docentes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.docentes: ~4 rows (aproximadamente)
REPLACE INTO `docentes` (`id_docente`, `id_usuario`, `nombre_completo`, `estatus`) VALUES
	(1, 2, 'ING. ROMAN ANTONIO GOMEZ PERALTA', 'Activo'),
	(2, NULL, 'ING. NIRIA GONZALEZ ORTIZ', 'Activo'),
	(3, 11, 'ING. CESAR MOISES ROSALES RAMIREZ', 'Activo'),
	(4, NULL, 'M.C. OMAR ELIO TORRES CASTILLO', 'Activo'),
	(5, NULL, 'M.C. OMAR ELIO TORRES CASTILLO', 'Activo'),
	(6, 10, 'ING. PABLO ULISES', 'Activo'),
	(7, 12, 'Carlos Ruiz', 'Activo');

-- Volcando estructura para tabla escolar.finanzas_adeudos
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
REPLACE INTO `finanzas_adeudos` (`id_adeudo`, `id_alumno`, `concepto_pago`, `monto`, `fecha_vencimiento`, `pagado`, `referencia_bancaria`, `fecha_pago`) VALUES
	(1, 1, 'ADSCRIPCION ACÁDEMICA', 3260, '2026-01-21 00:00:00', 'Pagado', '22100010087844841275', NULL),
	(2, 1, 'INGLÉS', 1200, '2026-01-21 00:00:00', 'Pendiente', '22100010087844841275', NULL);

-- Volcando estructura para procedimiento escolar.formato_pdf
DELIMITER //
CREATE PROCEDURE `formato_pdf`(IN p_id_usuario INT)
BEGIN
	SELECT id_alumno, matricula FROM alumnos WHERE id_usuario = p_id_usuario;
END//
DELIMITER ;

-- Volcando estructura para tabla escolar.grupos
CREATE TABLE IF NOT EXISTS `grupos` (
  `id_grupo` int(11) NOT NULL AUTO_INCREMENT,
  `id_ciclo` int(11) DEFAULT NULL,
  `nombre_grupo` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id_grupo`),
  KEY `id_ciclo` (`id_ciclo`),
  CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`id_ciclo`) REFERENCES `ciclos_escolares` (`id_ciclo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.grupos: ~1 rows (aproximadamente)
REPLACE INTO `grupos` (`id_grupo`, `id_ciclo`, `nombre_grupo`) VALUES
	(1, 1, '8A'),
	(4, 1, '5A'),
	(5, 6, '1A'),
	(6, 6, '7A');

-- Volcando estructura para tabla escolar.horarios
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.horarios: ~4 rows (aproximadamente)
REPLACE INTO `horarios` (`id_horario`, `id_carga_academica`, `dia_semana`, `hora_inicio`, `hora_fin`, `aula`) VALUES
	(1, 1, 'lunes', '07:50:00', '09:30:00', 'A7'),
	(2, 2, 'martes', '08:40:00', '09:30:00', 'LAB ISC'),
	(3, 3, 'martes', '11:10:00', '12:50:00', 'A7'),
	(4, 4, 'lunes', '07:00:00', '07:50:00', 'A7'),
	(6, 8, 'lunes', '10:20:00', '11:10:00', 'A7'),
	(7, 8, 'lunes', '01:25:00', '02:25:00', 'LAB ISIC');

-- Volcando estructura para tabla escolar.kardex
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.kardex: ~2 rows (aproximadamente)
REPLACE INTO `kardex` (`id_kardex`, `id_alumno`, `id_materia`, `id_ciclo`, `calificacion_definitiva`, `estatus_aprobacion`, `oportunidad`) VALUES
	(1, 1, 8, 6, 77, 'Aprobado', 'Ordinario'),
	(5, 1, 1, 1, 77, 'Aprobado', 'Ordinario');

-- Volcando estructura para tabla escolar.materias
CREATE TABLE IF NOT EXISTS `materias` (
  `id_materia` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_materia` varchar(100) DEFAULT NULL,
  `creditos` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_materia`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.materias: ~6 rows (aproximadamente)
REPLACE INTO `materias` (`id_materia`, `nombre_materia`, `creditos`) VALUES
	(1, 'Administración de Bases de Datos', 5),
	(2, 'Administración de Redes', 4),
	(3, 'Administración de Servidores', 7),
	(4, 'Programación Lógica y Funcional', 7),
	(5, 'Lenguajes Autómatas 2', 5),
	(7, 'Programación Web', 5),
	(8, 'Sistemas Gestores de Bases de Datos', 6),
	(9, 'Sistemas Programables', 5);

-- Volcando estructura para tabla escolar.mensajes
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla escolar.mensajes: ~0 rows (aproximadamente)
REPLACE INTO `mensajes` (`id_mensaje`, `id_docente`, `id_alumno`, `asunto`, `mensaje`, `fecha_envio`, `leido`) VALUES
	(1, 1, 1, 'excelencia', 'gracias por estudiar :)', '2026-03-23 17:13:25', 0),
	(2, 6, 5, 'bienvenida', 'Bienvenida al ITS San Pedro!', '2026-03-24 10:29:43', 0),
	(3, 1, 4, 'riesgo', 'asiste a clase porfa', '2026-04-13 11:36:39', 0);

-- Volcando estructura para tabla escolar.mensajes_admin
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Volcando datos para la tabla escolar.mensajes_admin: ~2 rows (aproximadamente)
REPLACE INTO `mensajes_admin` (`id_mensaje`, `id_docente`, `id_admin`, `asunto`, `mensaje`, `fecha_envio`, `leido`) VALUES
	(1, 6, 1, 'Solicitud para reabrir la acta', 'El docente ING. PABLO ULISES está solicitando abrir las actas para modificación.', '2026-03-25 09:02:04', 1),
	(2, 6, 1, 'Solicitud para reabrir la acta', 'El docente ING. PABLO ULISES está solicitando abrir las actas para modificación.', '2026-03-25 09:45:58', 1),
	(3, 3, 1, 'Solicitud para reabrir la acta', 'El docente ING. CESAR MOISES ROSALES RAMIREZ está solicitando abrir las actas para modificación.', '2026-03-25 10:34:55', 1),
	(4, 1, 1, 'Solicitud para reabrir la acta', 'El docente ING. ROMAN ANTONIO GOMEZ PERALTA está solicitando abrir las actas para modificación.', '2026-04-13 11:32:56', 0);

-- Volcando estructura para procedimiento escolar.menu_ss
DELIMITER //
CREATE PROCEDURE `menu_ss`(IN p_id_usuario INT)
BEGIN
    SELECT COUNT(*) 
    FROM servicio_social ss 
    INNER JOIN alumnos a ON ss.id_alumno = a.id_alumno 
    WHERE a.id_usuario = p_id_usuario;
END//
DELIMITER ;

-- Volcando estructura para tabla escolar.seguimiento_academico
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

-- Volcando estructura para procedimiento escolar.servicio
DELIMITER //
CREATE PROCEDURE `servicio`(IN p_id_usuario INT)
BEGIN
	SELECT a.*,
    u.correo,
    ss.horas_liberadas,
    ss.ruta_carta_aceptacion,
    ss.ruta_carta_liberacion,
    ss.ruta_reporte1,
    ss.ruta_reporte2,
    ss.ruta_reporte3
   FROM
    alumnos a
   inner join usuarios u on a.id_usuario = u.id_usuario
   left join servicio_social ss on a.id_alumno = ss.id_alumno
   where a.id_usuario = p_id_usuario;
END//
DELIMITER ;

-- Volcando estructura para tabla escolar.servicio_social
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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.servicio_social: ~0 rows (aproximadamente)
REPLACE INTO `servicio_social` (`id_servicio`, `id_alumno`, `horas_liberadas`, `ruta_carta_aceptacion`, `ruta_carta_liberacion`, `ruta_reporte1`, `ruta_reporte2`, `ruta_reporte3`) VALUES
	(7, 1, 66, NULL, '../uploads/221000100_liberacion_1778352712.pdf', NULL, NULL, NULL);

-- Volcando estructura para procedimiento escolar.subir_archivos_servicio
DELIMITER //
CREATE PROCEDURE `subir_archivos_servicio`(IN p_id_alumno INT, IN p_r_aceptacion VARCHAR(100), IN p_r_liberacion VARCHAR(100), IN p_r_reporte1 VARCHAR(100), IN p_r_reporte2 VARCHAR(100), IN p_r_reporte3 VARCHAR(100))
BEGIN
    UPDATE servicio_social 
    SET 
        ruta_carta_aceptacion = COALESCE(p_r_aceptacion, ruta_carta_aceptacion),
        ruta_carta_liberacion = COALESCE(p_r_liberacion, ruta_carta_liberacion),
        ruta_reporte1   = COALESCE(p_r_reporte1, ruta_reporte1),
        ruta_reporte2   = COALESCE(p_r_reporte2, ruta_reporte2),
        ruta_reporte3   = COALESCE(p_r_reporte3, ruta_reporte3)
    WHERE id_alumno = p_id_alumno;
END//
DELIMITER ;

-- Volcando estructura para tabla escolar.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `correo` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla escolar.usuarios: ~7 rows (aproximadamente)
REPLACE INTO `usuarios` (`id_usuario`, `correo`, `password`, `rol`) VALUES
	(1, 'mmtz5818@gmail.com', '1234', 'administrativo'),
	(2, 'abc@gmail.com', '123456', 'docente'),
	(3, 'mmtz5818@gmail.com', '123456789', 'alumno'),
	(6, 'takafallingjin@gmail.com', 'typhlosion', 'alumno'),
	(8, 'A266144@tecsanpedro.edu.mx', '12345678', 'alumno'),
	(9, 'A267109@tecsanpedro.edu.mx', '12345678', 'alumno'),
	(10, 'profe123@gmail.com', 'profe123', 'docente'),
	(11, 'profe1234@gmail.com', '123', 'docente'),
	(12, 'carlos@gmail.com', 'profe123', 'docente');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
