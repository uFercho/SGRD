-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 31-01-2011 a las 16:54:04
-- Versión del servidor: 5.1.36
-- Versión de PHP: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `db_sga`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_cam_campana`
--

CREATE TABLE IF NOT EXISTS `tm_cam_campana` (
  `cam_descripcion` varchar(50) NOT NULL,
  `cam_comentarios` varchar(250) DEFAULT NULL,
  `cam_id` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`cam_id`),
  UNIQUE KEY `cam_descripcion` (`cam_descripcion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=108 ;

--
-- (Evento) desencadenante `tm_cam_campana`
--
DROP TRIGGER IF EXISTS `db_sga`.`tai_cam`;
DELIMITER //
CREATE TRIGGER `db_sga`.`tai_cam` AFTER INSERT ON `db_sga`.`tm_cam_campana`
 FOR EACH ROW BEGIN 
  
  DECLARE tmpVar FLOAT;
      
  
  INSERT INTO tm_tre_tree_menu (tre_id, tre_id_parent, tre_id_tabla, tre_categoria, tre_texto)
  VALUES ( NULL, NULL, NEW.cam_id, 'CAMPA', NEW.cam_descripcion );  
  
END
//
DELIMITER ;

--
-- Volcar la base de datos para la tabla `tm_cam_campana`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_cro_cronograma`
--

CREATE TABLE IF NOT EXISTS `tm_cro_cronograma` (
  `cro_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cam_id` bigint(20) NOT NULL,
  `cro_row` int(11) DEFAULT NULL,
  `cro_volt_nominal` float DEFAULT NULL,
  `cro_punto` varchar(25) DEFAULT NULL,
  `cro_subestacion` varchar(25) DEFAULT NULL,
  `cro_circuito` varchar(5) DEFAULT NULL,
  `cro_municipio` varchar(25) DEFAULT NULL,
  `cro_tipo` varchar(25) DEFAULT NULL,
  `cro_carga` float DEFAULT NULL,
  `cro_clase` varchar(25) DEFAULT NULL,
  `cro_serial` varchar(25) DEFAULT NULL,
  `cro_placa` varchar(25) DEFAULT NULL,
  `cro_colocacion` datetime DEFAULT NULL,
  `cro_retiro_prev` datetime DEFAULT NULL,
  `cro_retiro_real` datetime DEFAULT NULL,
  `cro_reg_total` bigint(20) DEFAULT NULL,
  `cro_reg_evalu` bigint(20) DEFAULT NULL,
  `cro_reg_malos` bigint(20) DEFAULT NULL,
  `cro_feb` float DEFAULT NULL,
  `cro_comentarios` varchar(250) DEFAULT NULL,
  `cro_nfases` int(11) DEFAULT NULL,
  `cro_estado` smallint(6) DEFAULT '0',
  `cro_intervalo` int(11) DEFAULT NULL,
  PRIMARY KEY (`cro_id`),
  KEY `fk_cro_cam_id` (`cam_id`),
  KEY `cro_id` (`cro_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4438 ;

--
-- (Evento) desencadenante `tm_cro_cronograma`
--
DROP TRIGGER IF EXISTS `db_sga`.`tai_cro`;
DELIMITER //
CREATE TRIGGER `db_sga`.`tai_cro` AFTER INSERT ON `db_sga`.`tm_cro_cronograma`
 FOR EACH ROW BEGIN 
  
  DECLARE tmpVar BigInt;
      
  SELECT tre_id FROM tm_tre_tree_menu WHERE tre_id_tabla = NEW.cam_id AND tre_categoria = 'CAMPA' INTO tmpVar;
  
  INSERT INTO tm_tre_tree_menu (tre_id, tre_id_parent, tre_id_tabla, tre_categoria, tre_texto)
  VALUES ( NULL, tmpVar, NEW.cro_id, 'PUNTO', CONCAT (NEW.cro_row, '. ', NEW.cro_placa, ' - ', DATE_FORMAT(NEW.cro_colocacion, '%d/%m/%y')) );
  
END
//
DELIMITER ;

--
-- Volcar la base de datos para la tabla `tm_cro_cronograma`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_reg_registro2f`
--

CREATE TABLE IF NOT EXISTS `tm_reg_registro2f` (
  `reg_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cro_id` bigint(20) NOT NULL,
  `reg_row` bigint(20) DEFAULT NULL,
  `reg_date_time` datetime DEFAULT NULL,
  `reg_v1` double DEFAULT NULL,
  `reg_v2` double DEFAULT NULL,
  `reg_vp` double DEFAULT NULL,
  `reg_i1` double DEFAULT NULL,
  `reg_i2` double DEFAULT NULL,
  `reg_s1` double DEFAULT NULL,
  `reg_s2` double DEFAULT NULL,
  `reg_st` double DEFAULT NULL,
  `reg_fp1` double DEFAULT NULL,
  `reg_fp2` double DEFAULT NULL,
  `reg_fpt` double DEFAULT NULL,
  `reg_wt` double DEFAULT NULL,
  `reg_dv` double DEFAULT NULL,
  PRIMARY KEY (`reg_id`),
  UNIQUE KEY `cro_id` (`cro_id`,`reg_row`),
  KEY `FK_reg_cro` (`cro_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2048 ;

--
-- (Evento) desencadenante `tm_reg_registro2f`
--
DROP TRIGGER IF EXISTS `db_sga`.`tbi_reg2f`;
DELIMITER //
CREATE TRIGGER `db_sga`.`tbi_reg2f` BEFORE INSERT ON `db_sga`.`tm_reg_registro2f`
 FOR EACH ROW BEGIN 
  
  DECLARE tmpVar FLOAT;
  
  SET tmpVar = ABS(NEW.reg_i1 - NEW.reg_i2); 
  
  SET NEW.reg_dv = (tmpVar/((NEW.reg_i1+NEW.reg_i2)/2))*100;  
  
  SET NEW.reg_vp = (NEW.reg_v1 + NEW.reg_v2) / 2;   
  
  UPDATE tm_tre_tree_menu SET tre_estado = 'PROC' WHERE tre_id_tabla = NEW.cro_id AND tre_categoria = 'PUNTO';
  
END
//
DELIMITER ;

--
-- Volcar la base de datos para la tabla `tm_reg_registro2f`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_reg_registro3f`
--

CREATE TABLE IF NOT EXISTS `tm_reg_registro3f` (
  `reg_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cro_id` bigint(20) NOT NULL,
  `reg_row` bigint(20) DEFAULT NULL,
  `reg_date_time` datetime DEFAULT NULL,
  `reg_v1` double DEFAULT NULL,
  `reg_v2` double DEFAULT NULL,
  `reg_v3` double DEFAULT NULL,
  `reg_vp` double DEFAULT NULL,
  `reg_i1` double DEFAULT NULL,
  `reg_i2` double DEFAULT NULL,
  `reg_i3` double DEFAULT NULL,
  `reg_s1` double DEFAULT NULL,
  `reg_s2` double DEFAULT NULL,
  `reg_s3` double DEFAULT NULL,
  `reg_st` double DEFAULT NULL,
  `reg_fp1` double DEFAULT NULL,
  `reg_fp2` double DEFAULT NULL,
  `reg_fp3` double DEFAULT NULL,
  `reg_fpt` double DEFAULT NULL,
  `reg_wt` double DEFAULT NULL,
  `reg_dv` double DEFAULT NULL,
  PRIMARY KEY (`reg_id`),
  UNIQUE KEY `cro_id` (`cro_id`,`reg_row`),
  KEY `FK_reg_cro` (`cro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- (Evento) desencadenante `tm_reg_registro3f`
--
DROP TRIGGER IF EXISTS `db_sga`.`tbi_reg3f`;
DELIMITER //
CREATE TRIGGER `db_sga`.`tbi_reg3f` BEFORE INSERT ON `db_sga`.`tm_reg_registro3f`
 FOR EACH ROW BEGIN 
  
  DECLARE tmpVar FLOAT;
    
  
  SET tmpVar = ABS(NEW.reg_i1 - NEW.reg_i2); 
  IF ABS(NEW.reg_i2 - NEW.reg_i3) > tmpVar THEN
    SET tmpVar = ABS(NEW.reg_i2 - NEW.reg_i3);
  ELSEIF ABS(NEW.reg_i3 - NEW.reg_i1) > tmpVar THEN
    SET tmpVar = ABS(NEW.reg_i3 - NEW.reg_i1);
  END IF;
  
  SET NEW.reg_dv = (tmpVar/((NEW.reg_i1+NEW.reg_i2+NEW.reg_i3)/3))*100;  
  
  SET NEW.reg_vp = (NEW.reg_v1 + NEW.reg_v2 + NEW.reg_v3) / 3;   
  
  UPDATE tm_tre_tree_menu SET tre_estado = 'PROC' WHERE tre_id_tabla = NEW.cro_id AND tre_categoria = 'PUNTO';
  
END
//
DELIMITER ;

--
-- Volcar la base de datos para la tabla `tm_reg_registro3f`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_sce_sobrecarga_equipo`
--

CREATE TABLE IF NOT EXISTS `tm_sce_sobrecarga_equipo` (
  `sce_id` int(11) NOT NULL AUTO_INCREMENT,
  `sce_max` float NOT NULL,
  `sce_avg` float NOT NULL,
  `sce_kvan` float NOT NULL,
  `sce_fecha` datetime NOT NULL,
  `sce_patrimonio` varchar(15) NOT NULL,
  `sce_num_sc` int(11) NOT NULL,
  `sce_horas_sc` float NOT NULL,
  `sce_remplazado` tinyint(1) NOT NULL,
  `scu_placa` varchar(15) NOT NULL,
  `sce_num_su` int(11) DEFAULT NULL,
  `sce_horas_su` float DEFAULT NULL,
  `sce_min` float DEFAULT NULL,
  `sce_sobrecarga` int(11) NOT NULL,
  PRIMARY KEY (`sce_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `tm_sce_sobrecarga_equipo`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_scu_sobrecarga_unidad`
--

CREATE TABLE IF NOT EXISTS `tm_scu_sobrecarga_unidad` (
  `scu_id` int(11) NOT NULL AUTO_INCREMENT,
  `scu_max` float NOT NULL,
  `scu_avg` float NOT NULL,
  `scu_kvan` float NOT NULL,
  `scu_fecha` datetime NOT NULL,
  `scu_placa` varchar(15) NOT NULL,
  `scu_num_sc` int(11) NOT NULL,
  `scu_horas_sc` float NOT NULL,
  `scu_remplazado` tinyint(1) NOT NULL DEFAULT '0',
  `scu_num_su` int(11) DEFAULT NULL,
  `scu_horas_su` float DEFAULT NULL,
  `scu_min` float DEFAULT NULL,
  PRIMARY KEY (`scu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `tm_scu_sobrecarga_unidad`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_tre_tree_menu`
--

CREATE TABLE IF NOT EXISTS `tm_tre_tree_menu` (
  `tre_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `tre_id_parent` bigint(11) DEFAULT NULL,
  `tre_estado` varchar(10) DEFAULT NULL,
  `tre_id_tabla` bigint(11) DEFAULT NULL,
  `tre_categoria` varchar(50) NOT NULL,
  `tre_orden_num` int(11) DEFAULT NULL,
  `tre_texto` varchar(50) NOT NULL,
  PRIMARY KEY (`tre_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4033 ;

--
-- (Evento) desencadenante `tm_tre_tree_menu`
--
DROP TRIGGER IF EXISTS `db_sga`.`tbi_tre`;
DELIMITER //
CREATE TRIGGER `db_sga`.`tbi_tre` BEFORE INSERT ON `db_sga`.`tm_tre_tree_menu`
 FOR EACH ROW BEGIN

  DECLARE tmpVar INTEGER;

  IF NEW.tre_categoria = 'PUNTO' THEN
    SET NEW.tre_estado = 'NO_PROC';      
  ELSEIF NEW.tre_categoria = 'CAMPA' THEN
    SET NEW.tre_estado = 'PROC';
  END IF;  
  
END
//
DELIMITER ;

--
-- Volcar la base de datos para la tabla `tm_tre_tree_menu`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tm_usu_usuario`
--

CREATE TABLE IF NOT EXISTS `tm_usu_usuario` (
  `usu_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `usu_login` varchar(25) NOT NULL,
  `usu_pass` varchar(35) NOT NULL,
  `usu_nivel` varchar(25) NOT NULL,
  PRIMARY KEY (`usu_id`),
  UNIQUE KEY `usu_login` (`usu_login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcar la base de datos para la tabla `tm_usu_usuario`
--

INSERT INTO `tm_usu_usuario` (`usu_id`, `usu_login`, `usu_pass`, `usu_nivel`) VALUES
(1, 'test', '098f6bcd4621d373cade4e832627b4f6', 'admin');

--
-- Filtros para las tablas descargadas (dump)
--

--
-- Filtros para la tabla `tm_cro_cronograma`
--
ALTER TABLE `tm_cro_cronograma`
  ADD CONSTRAINT `fk_cro_cam_id` FOREIGN KEY (`cam_id`) REFERENCES `tm_cam_campana` (`cam_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tm_reg_registro2f`
--
ALTER TABLE `tm_reg_registro2f`
  ADD CONSTRAINT `FK_reg_cro_2f` FOREIGN KEY (`cro_id`) REFERENCES `tm_cro_cronograma` (`cro_id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Filtros para la tabla `tm_reg_registro3f`
--
ALTER TABLE `tm_reg_registro3f`
  ADD CONSTRAINT `FK_reg_cro_3f` FOREIGN KEY (`cro_id`) REFERENCES `tm_cro_cronograma` (`cro_id`) ON DELETE CASCADE ON UPDATE NO ACTION;
