<?php
	
	$mysqli = newMySQLi();
	// chequeo de coneccion
	if (mysqli_connect_errno()) {
		$output = msgReturn(false,'Conección fallida. '.mysqli_connect_error());
		exit;
	}
	
	$all_query_ok=true; // variable de control
	
	$mysqli->autocommit(FALSE); // se desabilita el autocommit	
	
	$tablas = '	CREATE TABLE IF NOT EXISTS `tm_cam_campana` (
					`cam_descripcion` varchar(50) NOT NULL,
					`cam_comentarios` varchar(250) DEFAULT NULL,
					`cam_id` bigint(20) NOT NULL AUTO_INCREMENT,
					PRIMARY KEY (`cam_id`),
					UNIQUE KEY `cam_descripcion` (`cam_descripcion`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;';
	$mysqli->query(substr($tablas, 0, -1)) ? null : $all_query_ok = false;
	
	$tablas = '	CREATE TABLE IF NOT EXISTS `tm_cro_cronograma` (
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
					`cro_estado` smallint(6) DEFAULT \'0\',
					`cro_intervalo` int(11) DEFAULT NULL,
					PRIMARY KEY (`cro_id`),
					KEY `fk_cro_cam_id` (`cam_id`),
					KEY `cro_id` (`cro_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=235 ;';
	$mysqli->query(substr($tablas, 0, -1)) ? null : $all_query_ok = false;	
		
	$tablas = '	CREATE TABLE IF NOT EXISTS `tm_reg_registro` (
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
					`reg_in` double DEFAULT NULL,
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
					KEY `FK_tm_reg_registro_tm_cro_cronograma` (`cro_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8064 ;';
	$mysqli->query(substr($tablas, 0, -1)) ? null : $all_query_ok = false;

	$tablas = '	CREATE TABLE IF NOT EXISTS `tm_reg_registro1f` (
					`reg_id` bigint(20) NOT NULL AUTO_INCREMENT,
					`cro_id` bigint(20) NOT NULL,
					`reg_row` bigint(20) DEFAULT NULL,
					`reg_date_time` datetime DEFAULT NULL,
					`reg_v1` double DEFAULT NULL,
					`reg_vp` double DEFAULT NULL,
					`reg_i1` double DEFAULT NULL,
					`reg_in` double DEFAULT NULL,
					`reg_s1` double DEFAULT NULL,
					`reg_st` double DEFAULT NULL,
					`reg_fp1` double DEFAULT NULL,
					`reg_fpt` double DEFAULT NULL,
					`reg_wt` double DEFAULT NULL,
					`reg_dv` double DEFAULT NULL,
					PRIMARY KEY (`reg_id`),
					UNIQUE KEY `cro_id` (`cro_id`,`reg_row`),
					KEY `FK_reg_cro` (`cro_id`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
	$mysqli->query(substr($tablas, 0, -1)) ? null : $all_query_ok = false;	
		
	$tablas = '	CREATE TABLE IF NOT EXISTS `tm_reg_registro2f` (
					`reg_id` bigint(20) NOT NULL AUTO_INCREMENT,
					`cro_id` bigint(20) NOT NULL,
					`reg_row` bigint(20) DEFAULT NULL,
					`reg_date_time` datetime DEFAULT NULL,
					`reg_v1` double DEFAULT NULL,
					`reg_v2` double DEFAULT NULL,
					`reg_vp` double DEFAULT NULL,
					`reg_i1` double DEFAULT NULL,
					`reg_i2` double DEFAULT NULL,
					`reg_in` double DEFAULT NULL,
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
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
	$mysqli->query(substr($tablas, 0, -1)) ? null : $all_query_ok = false;
		
	$tablas = '	CREATE TABLE IF NOT EXISTS `tm_reg_registro3f` (
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
					`reg_in` double DEFAULT NULL,
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
				) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';
	$mysqli->query(substr($tablas, 0, -1)) ? null : $all_query_ok = false;
		
	$tablas = '	CREATE TABLE IF NOT EXISTS `tm_tre_tree_menu` (
					`tre_id` bigint(11) NOT NULL AUTO_INCREMENT,
					`tre_id_parent` bigint(11) DEFAULT NULL,
					`tre_estado` varchar(10) DEFAULT NULL,
					`tre_id_tabla` bigint(11) DEFAULT NULL,
					`tre_categoria` varchar(50) NOT NULL,
					`tre_orden_num` int(11) DEFAULT NULL,
					`tre_texto` varchar(50) NOT NULL,
					PRIMARY KEY (`tre_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=221 ;';
	$mysqli->query(substr($tablas, 0, -1)) ? null : $all_query_ok = false;
		
	$tablas = '	CREATE TABLE IF NOT EXISTS `tm_usu_usuario` (
					`usu_id` bigint(20) NOT NULL AUTO_INCREMENT,
					`usu_login` varchar(25) NOT NULL,
					`usu_pass` varchar(35) NOT NULL,
					`usu_nivel` varchar(25) NOT NULL,
					PRIMARY KEY (`usu_id`),
					UNIQUE KEY `usu_login` (`usu_login`)
				) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;';
	$mysqli->query(substr($tablas, 0, -1)) ? null : $all_query_ok = false;
	
		
	$triggers = '	DROP TRIGGER IF EXISTS `db_sga`.`tai_cam`;
					DELIMITER //
					CREATE TRIGGER `db_sga`.`tai_cam` AFTER INSERT ON `db_sga`.`tm_cam_campana`
					 FOR EACH ROW BEGIN 
					  
					  DECLARE tmpVar FLOAT;
						  
					  
					  INSERT INTO tm_tre_tree_menu (tre_id, tre_id_parent, tre_id_tabla, tre_categoria, tre_texto)
					  VALUES ( NULL, NULL, NEW.cam_id, \'CAMPA\', NEW.cam_descripcion);  
					  
					END
					//
					DELIMITER ;';
	$mysqli->query(substr($triggers, 0, -1)) ? null : $all_query_ok = false;
		
		
	
	$triggers = '	DROP TRIGGER IF EXISTS `db_sga`.`tai_cro`;
		DELIMITER //
		CREATE TRIGGER `db_sga`.`tai_cro` AFTER INSERT ON `db_sga`.`tm_cro_cronograma`
		 FOR EACH ROW BEGIN 
		  
		  DECLARE tmpVar BigInt;
			  
		  SELECT tre_id FROM tm_tre_tree_menu WHERE tre_id_tabla = NEW.cam_id AND tre_categoria = \'CAMPA\' INTO tmpVar;
		  
		  INSERT INTO tm_tre_tree_menu (tre_id, tre_id_parent, tre_id_tabla, tre_categoria, tre_texto)
		  VALUES ( NULL, tmpVar, NEW.cro_id, \'PUNTO\', CONCAT (NEW.cro_placa, \'-\', DATE(NEW.cro_colocacion)) );
		  
		END
		//
		DELIMITER ;';
	$mysqli->query(substr($triggers, 0, -1)) ? null : $all_query_ok = false;		
		
	$triggers = '	DROP TRIGGER IF EXISTS `db_sga`.`tbi_reg`;
		DELIMITER //
		CREATE TRIGGER `db_sga`.`tbi_reg` BEFORE INSERT ON `db_sga`.`tm_reg_registro`
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
		  
		  UPDATE tm_tre_tree_menu SET tre_estado = \'PROC\' WHERE tre_id_tabla = NEW.cro_id AND tre_categoria = \'PUNTO\';
		  
		END
		//
		DELIMITER ;';	
	$mysqli->query(substr($triggers, 0, -1)) ? null : $all_query_ok = false;
		
	$triggers = '	DROP TRIGGER IF EXISTS `db_sga`.`tbi_tre`;
		DELIMITER //
		CREATE TRIGGER `db_sga`.`tbi_tre` BEFORE INSERT ON `db_sga`.`tm_tre_tree_menu`
		 FOR EACH ROW BEGIN
		
		  DECLARE tmpVar INTEGER;
		
		  IF NEW.tre_categoria = \'PUNTO\' THEN
			SET NEW.tre_estado = \'NO_PROC\';      
		  ELSEIF NEW.tre_categoria = \'CAMPA\' THEN
			SET NEW.tre_estado = \'PROC\';
		  END IF;  
		  
		END
		//
		DELIMITER ;';	
	$mysqli->query(substr($triggers, 0, -1)) ? null : $all_query_ok = false;
		


	/*$insert = '
		INSERT INTO `tm_usu_usuario` (`usu_id`, `usu_login`, `usu_pass`, `usu_nivel`) VALUES
		(1, \'test\', \'098f6bcd4621d373cade4e832627b4f6\', \'admin\');';
	
	
	$mysqli->query(substr($insert, 0, -1)) ? null : $all_query_ok = false;*/


	/*$foreign = '
		--
		-- Filtros para la tabla `tm_cro_cronograma`
		--
		ALTER TABLE `tm_cro_cronograma`
		  ADD CONSTRAINT `fk_cro_cam_id` FOREIGN KEY (`cam_id`) REFERENCES `tm_cam_campana` (`cam_id`) ON DELETE CASCADE ON UPDATE NO ACTION;
		
		--
		-- Filtros para la tabla `tm_reg_registro`
		--
		ALTER TABLE `tm_reg_registro`
		  ADD CONSTRAINT `FK_tm_reg_registro_tm_cro_cronograma` FOREIGN KEY (`cro_id`) REFERENCES `tm_cro_cronograma` (`cro_id`) ON DELETE CASCADE ON UPDATE NO ACTION;
		
		--
		-- Filtros para la tabla `tm_reg_registro1f`
		--
		ALTER TABLE `tm_reg_registro1f`
		  ADD CONSTRAINT `FK_reg_cro_1f` FOREIGN KEY (`cro_id`) REFERENCES `tm_cro_cronograma` (`cro_id`) ON DELETE CASCADE ON UPDATE NO ACTION;
		
		--
		-- Filtros para la tabla `tm_reg_registro2f`
		--
		ALTER TABLE `tm_reg_registro2f`
		  ADD CONSTRAINT `FK_reg_cro_2f` FOREIGN KEY (`cro_id`) REFERENCES `tm_cro_cronograma` (`cro_id`) ON DELETE CASCADE ON UPDATE NO ACTION;
		
		--
		-- Filtros para la tabla `tm_reg_registro3f`
		--
		ALTER TABLE `tm_reg_registro3f`
		  ADD CONSTRAINT `FK_reg_cro_3f` FOREIGN KEY (`cro_id`) REFERENCES `tm_cro_cronograma` (`cro_id`) ON DELETE CASCADE ON UPDATE NO ACTION;';
		
	$mysqli->query(substr($foreign, 0, -1)) ? null : $all_query_ok = false;*/

	if ($all_query_ok) {
		$mysqli->commit();
		//$output = msgReturn(true,$_FILES['file-txt']['name']);
		echo 'Exitoso';
	} else {
		$mysqli->rollback();
		//$output = msgReturn(false,'No se pudo cargar el archivo. Probablemente ya exita en el Servidor');
		echo 'Fallido';
	}
	$mysqli->close(); 
    unset($tablas); 
    unset($triggers); 
    unset($insert); 
    unset($foreign); 
    unset($query);

?>