<?php
	function connectMySQL() // Convierte una fecha en formato DateTime
	{
		$localhost  = 'localhost';
		$mysql_db   = 'db_sga';
		$mysql_user = 'root';
		$mysql_pass = ''; 
		//$mysql_pass = 'oracle123*';
		
		$link = mysql_connect($localhost, $mysql_user, $mysql_pass) or die(mysql_error());
		mysql_select_db($mysql_db, $link) or die(mysql_error());
	}
	
	function newMySQLi()
	{
		$localhost  = 'localhost';
		$mysql_db   = 'db_sga';
		$mysql_user = 'root';
		$mysql_pass = ''; 
		//$mysql_pass = 'oracle123*';
		
		/*	   new mysqli('localhost', 'my_user', 'my_password', 'my_db');	*/
		return new mysqli($localhost, $mysql_user, $mysql_pass, $mysql_db);
	}
	
	function newMySQLiSGD()
	{
		$localhost  = 'localhost';
		$mysql_db   = 'db_sgd';
		$mysql_user = 'root';
		$mysql_pass = ''; 
		//$mysql_pass = 'oracle123*';
		
		/*	   new mysqli('localhost', 'my_user', 'my_password', 'my_db');	*/
		return new mysqli($localhost, $mysql_user, $mysql_pass, $mysql_db);
	}
	
	function connectSGD()
	{
		$CONFIG = array();		//xq CONFIG2 si se utiliza CONFIG ??
		
		$CONFIG['SHOST_NAME'] = '10.71.1.33';
		$CONFIG['SDATABASE_SID'] = 'MART';
		$CONFIG['SDB_USERNAME'] = 'seneca_sgd';
		$CONFIG['SDB_PASSWORD'] = 'brutal';	
		
		$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = ".$CONFIG['SHOST_NAME'].")(PORT = 1521)))(CONNECT_DATA=(SID=".$CONFIG['SDATABASE_SID'].")))";
		
		$conn = ocinlogon($CONFIG['SDB_USERNAME'],$CONFIG['SDB_PASSWORD'],$db);
		
		/*$conn = oci_connect('admin','root','localhost/XE');*/
		
		return $conn;		
	}

	function formatDateTime($date, $time) // Convierte una fecha en formato DateTime
	{
		$dateTime = trim($date)!='' ? substr(trim($date),6,4).'-'.substr(trim($date),3,2).'-'.substr(trim($date),0,2) : '';
		$dateTime.= trim($time)!='' ? ' '.trim($time) : '';
		return $dateTime;		
	}
	
	
	function msgReturn($success,$msg)
	{
		if($success) 
			return '{success:true, msg:'.json_encode(utf8_encode($msg)).'}';
		else 
			return '{"success":false, msg:'.json_encode(utf8_encode($msg)).'}';
	}
	
	function Valida_root($root){
		$root = str_replace("-", "", $root);
        $root = str_replace(" ", "", $root);
		return $root;
	}
	
	function Sustituto_Cadena($rb){ 
        ## Sustituyo caracteres en la cadena final
        $rb = str_replace("Ã¡", "á", $rb);
        $rb = str_replace("Ã©", "é", $rb);
        //$rb = str_replace("Â®", "&reg;", $rb);
        $rb = str_replace("Ã­", "í", $rb);
        $rb = str_replace("ï¿½", "Í", $rb);
        $rb = str_replace("Ã³", "ó", $rb);
        $rb = str_replace("Ãº", "ú", $rb);
        //$rb = str_replace("n~", "&ntilde;", $rb);
        //$rb = str_replace("Âº", "&ordm;", $rb);
        //$rb = str_replace("Âª", "&ordf;", $rb);
        $rb = str_replace("ÃƒÂ¡", "Á", $rb);
        $rb = str_replace("Ã±", "ñ", $rb);
        $rb = str_replace("Ã‘", "Ñ", $rb);
        //$rb = str_replace("ÃƒÂ±", "&ntilde;", $rb);
        //$rb = str_replace("n~", "&ntilde;", $rb);
        $rb = str_replace("Ãš", "Ú", $rb);
        return $rb;
	}
	
	function peor_caso($SC_r, $caso){
		if( ($caso == 24) and ($SC_r >= $caso) ) return 24;
		elseif ( ($caso == 8) and ($SC_r >= $caso) ) return 8;
		elseif ( ($caso == 4) and ($SC_r >= $caso) ) return 4;
		elseif ( ($caso == 2) and ($SC_r >= $caso) ) return 2;
		elseif ( ($caso == 1) and ($SC_r >= $caso) ) return 1;
		
	}
	
	function buscaSobreCarga($root, $idUT, $nfase, $numEquip) {
		$addFase = $numEquip == 3 ? ", s2.kvan as kvan_s2, s2.tombamento as patri_s2, s3.kvan as kvan_s3, s3.tombamento as patri_s3" : "";
		$sql ="select st.kvan as kvan_st, s1.kvan as kvan_s1, s1.tombamento as patri_s1".$addFase." from ";
																									   
			$sql.="( select round(kvan*1000,3) as kvan, '1' as id ";
			$sql.="from utransformadora ";
			$sql.="where instalacao='".$idUT."' ) st ";
		
		if($numEquip==3) {
			$sql.="inner join ( select round(kvan*1000,3) as kvan, '1' as id, tombamento ";
			$sql.="from eqtransformador ";
			$sql.="where instalacao='".$idUT."' and faseligsec='A' ) s1 on st.id = s1.id ";
			$sql.="inner join ( select round(kvan*1000,3) as kvan, '1' as id, tombamento ";
			$sql.="from eqtransformador ";
			$sql.="where instalacao='".$idUT."' and faseligsec='B' ) s2 on s1.id = s2.id ";
			$sql.="inner join ( select round(kvan*1000,3) as kvan, '1' as id, tombamento ";
			$sql.="from eqtransformador ";
			$sql.="where instalacao='".$idUT."' and faseligsec='C' ) s3 on s2.id = s3.id";
		}else{
			$sql.="inner join ( select round(kvan*1000,3) as kvan, '1' as id, tombamento ";
			$sql.="from eqtransformador ";
			$sql.="where instalacao='".$idUT."' ) s1 on st.id = s1.id ";
		}
		
		
		$stid = oci_parse(connectSGD(), $sql);
		oci_execute($stid);
		$row = oci_fetch_assoc($stid);
		
		$mysqli = newMySQLi();
		if (mysqli_connect_errno()) {} // chequeo de coneccion
		
		$tabla = $nfase == 3 ? "tm_reg_registro3f" : "tm_reg_registro2f";
		
		$addFase = $numEquip == 3 ? "reg_s1, reg_s2, reg_s3, " : "";
		
		$H_24_r1 = 0; $H_24_r2 = 0; $H_24_r3 = 0; $H_24_rt = 0; 
		$H_08_r1 = 0; $H_08_r2 = 0; $H_08_r3 = 0; $H_08_rt = 0;
		$H_04_r1 = 0; $H_04_r2 = 0; $H_04_r3 = 0; $H_04_rt = 0;
		$H_02_r1 = 0; $H_02_r2 = 0; $H_02_r3 = 0; $H_02_rt = 0;
		$H_01_r1 = 0; $H_01_r2 = 0; $H_01_r3 = 0; $H_01_rt = 0;
		$SC_r1 = 25;  $SC_r2 = 25;  $SC_r3 = 25;  $SC_rt = 25;
		
		$sql = "SELECT ".$addFase."reg_st FROM ".$tabla." WHERE cro_id = ".$root." ORDER BY reg_date_time";
		
		if ($result = $mysqli->query($sql)) {
			while($row1 = $result->fetch_object()){
				
				$H_24_rt = $row1->reg_st > $row["KVAN_ST"]*1.01 ? $H_24_rt+1 : 0;
				$H_08_rt = $row1->reg_st > $row["KVAN_ST"]*1.17 ? $H_08_rt+1 : 0;
				$H_04_rt = $row1->reg_st > $row["KVAN_ST"]*1.33 ? $H_04_rt+1 : 0;
				$H_02_rt = $row1->reg_st > $row["KVAN_ST"]*1.54 ? $H_02_rt+1 : 0;
				$H_01_rt = $row1->reg_st > $row["KVAN_ST"]*1.79 ? $H_01_rt+1 : 0;
				
				if( ($H_24_rt >= 144)and ($SC_rt >= 24)) $SC_rt = 24; 
				if( ($H_08_rt >= 48) and ($SC_rt >= 8) ) $SC_rt = 8;
				if( ($H_04_rt >= 24) and ($SC_rt >= 4) ) $SC_rt = 4;
				if( ($H_02_rt >= 12) and ($SC_rt >= 2) ) $SC_rt = 2;
				if( ($H_01_rt >= 6)  and ($SC_rt >= 1) ) $SC_rt = 1;
				
				if($numEquip == 3){
					
					$H_24_r1 = $row1->reg_s1 > $row["KVAN_S1"]*1.01 ? $H_24_r1+1 : 0;
					$H_08_r1 = $row1->reg_s1 > $row["KVAN_S1"]*1.17 ? $H_08_r1+1 : 0;
					$H_04_r1 = $row1->reg_s1 > $row["KVAN_S1"]*1.33 ? $H_04_r1+1 : 0;
					$H_02_r1 = $row1->reg_s1 > $row["KVAN_S1"]*1.54 ? $H_02_r1+1 : 0;
					$H_01_r1 = $row1->reg_s1 > $row["KVAN_S1"]*1.79 ? $H_01_r1+1 : 0;
					
					if( ($H_24_r1 >= 144)and ($SC_r1 >= 24)) $SC_r1 = 24; 
					if( ($H_08_r1 >= 48) and ($SC_r1 >= 8) ) $SC_r1 = 8;
					if( ($H_04_r1 >= 24) and ($SC_r1 >= 4) ) $SC_r1 = 4;
					if( ($H_02_r1 >= 12) and ($SC_r1 >= 2) ) $SC_r1 = 2;
					if( ($H_01_r1 >= 6)  and ($SC_r1 >= 1) ) $SC_r1 = 1;
					
					$H_24_r2 = $row1->reg_s2 > $row["KVAN_S2"]*1.01 ? $H_24_r2+1 : 0;
					$H_08_r2 = $row1->reg_s2 > $row["KVAN_S2"]*1.17 ? $H_08_r2+1 : 0;
					$H_04_r2 = $row1->reg_s2 > $row["KVAN_S2"]*1.33 ? $H_04_r2+1 : 0;
					$H_02_r2 = $row1->reg_s2 > $row["KVAN_S2"]*1.54 ? $H_02_r2+1 : 0;
					$H_01_r2 = $row1->reg_s2 > $row["KVAN_S2"]*1.79 ? $H_01_r2+1 : 0;
					
					if( ($H_24_r2 >= 144)and ($SC_r2 >= 24)) $SC_r2 = 24; 
					if( ($H_08_r2 >= 48) and ($SC_r2 >= 8) ) $SC_r2 = 8;
					if( ($H_04_r2 >= 24) and ($SC_r2 >= 4) ) $SC_r2 = 4;
					if( ($H_02_r2 >= 12) and ($SC_r2 >= 2) ) $SC_r2 = 2;
					if( ($H_01_r2 >= 6)  and ($SC_r2 >= 1) ) $SC_r2 = 1;
					
					$H_24_r3 = $row1->reg_s3 > $row["KVAN_S3"]*1.01 ? $H_24_r3+1 : 0;
					$H_08_r3 = $row1->reg_s3 > $row["KVAN_S3"]*1.17 ? $H_08_r3+1 : 0;
					$H_04_r3 = $row1->reg_s3 > $row["KVAN_S3"]*1.33 ? $H_04_r3+1 : 0;
					$H_02_r3 = $row1->reg_s3 > $row["KVAN_S3"]*1.54 ? $H_02_r3+1 : 0;
					$H_01_r3 = $row1->reg_s3 > $row["KVAN_S3"]*1.79 ? $H_01_r3+1 : 0;
				
					if( ($H_24_r3 >= 144)and ($SC_r3 >= 24)) $SC_r3 = 24; 
					if( ($H_08_r3 >= 48) and ($SC_r3 >= 8) ) $SC_r3 = 8;
					if( ($H_04_r3 >= 24) and ($SC_r3 >= 4) ) $SC_r3 = 4;
					if( ($H_02_r3 >= 12) and ($SC_r3 >= 2) ) $SC_r3 = 2;
					if( ($H_01_r3 >= 6)  and ($SC_r3 >= 1) ) $SC_r3 = 1;
				}
			}
		}
		
		if($SC_r1 == 25) $SC_r1 = 0;
		if($SC_r2 == 25) $SC_r2 = 0;
		if($SC_r3 == 25) $SC_r3 = 0;
		if($SC_rt == 25) $SC_rt = 0;
		
		$sql = "SELECT
				   fac.fase, fac.reg_date_time, fac.smax, fac.savg, fac.smin, sc.n_sc, sc.h_sc, su.n_su, su.h_su
				FROM (
					SELECT
						'FASE T' AS fase, MAX(reg_st) AS smax, AVG(reg_st) AS savg, MIN(reg_st) AS smin, reg_date_time   
					FROM ".$tabla."   
					WHERE cro_id = ".$root."
				) AS fac INNER JOIN (
					SELECT 
						'FASE T' AS fase, COUNT(*) AS n_sc, ROUND((COUNT(*)*10)/60,3) AS h_sc 
					FROM ".$tabla."   
					WHERE cro_id = ".$root." AND reg_st > (".$row["KVAN_ST"].")  
				) AS sc ON fac.fase = sc.fase INNER JOIN (
					SELECT 
						'FASE T' AS fase, COUNT(*) AS n_su, ROUND((COUNT(*)*10)/60,3) AS h_su 
					FROM ".$tabla." 
					WHERE cro_id = ".$root." AND reg_st < (".($row["KVAN_ST"]*0.4).")
				) AS su ON sc.fase = su.fase "; 
				
		if($numEquip==3) {
			$sql.= "UNION ALL 
					SELECT
					   fac.fase, fac.reg_date_time, fac.smax, fac.savg, fac.smin, sc.n_sc, sc.h_sc, su.n_su, su.h_su
					FROM (               
						SELECT 
						   'FASE A' AS fase, MAX(reg_s1) AS smax, AVG(reg_s1) AS savg, MIN(reg_s1) AS smin, reg_date_time   
						FROM ".$tabla."   
						WHERE cro_id = ".$root." 
					) AS fac INNER JOIN (
						SELECT 
							'FASE A' AS fase, COUNT(*) AS n_sc, ROUND((COUNT(*)*10)/60,3) AS h_sc 
						FROM ".$tabla."   
						WHERE cro_id = ".$root." AND reg_s1 > (".$row["KVAN_S1"].")
					) AS sc ON fac.fase = sc.fase INNER JOIN (
						SELECT 
							'FASE A' AS fase, COUNT(*) AS n_su, ROUND((COUNT(*)*10)/60,3) AS h_su 
						FROM ".$tabla." 
						WHERE cro_id = ".$root." AND reg_s1 < (".($row["KVAN_S1"]*0.4).")
					) AS su on sc.fase = su.fase
					UNION ALL 
					SELECT
					   fac.fase, fac.reg_date_time, fac.smax, fac.savg, fac.smin, sc.n_sc, sc.h_sc, su.n_su, su.h_su
					FROM (               
						SELECT 
						   'FASE B' AS fase, MAX(reg_s2) AS smax, AVG(reg_s2) AS savg, MIN(reg_s2) AS smin, reg_date_time   
						FROM ".$tabla."   
						WHERE cro_id = ".$root." 
					) AS fac INNER JOIN (
						SELECT 
							'FASE B' AS fase, COUNT(*) AS n_sc, ROUND((COUNT(*)*10)/60,3) AS h_sc 
						FROM ".$tabla."   
						WHERE cro_id = ".$root." AND reg_s2 > (".$row["KVAN_S2"].")
					) AS sc ON fac.fase = sc.fase INNER JOIN (
						SELECT 
							'FASE B' AS fase, COUNT(*) AS n_su, ROUND((COUNT(*)*10)/60,3) AS h_su 
						FROM ".$tabla." 
						WHERE cro_id = ".$root." AND reg_s2 < (".($row["KVAN_S2"]*0.4).")
					) AS su on sc.fase = su.fase
					UNION ALL 
					SELECT
					   fac.fase, fac.reg_date_time, fac.smax, fac.savg, fac.smin, sc.n_sc, sc.h_sc, su.n_su, su.h_su
					FROM (               
						SELECT 
						   'FASE C' AS fase, MAX(reg_s3) AS smax, AVG(reg_s3) AS savg, MIN(reg_s3) AS smin, reg_date_time   
						FROM ".$tabla."   
						WHERE cro_id = ".$root." 
					) AS fac INNER JOIN (
						SELECT 
							'FASE C' AS fase, COUNT(*) AS n_sc, ROUND((COUNT(*)*10)/60,3) AS h_sc 
						FROM ".$tabla."   
						WHERE cro_id = ".$root." AND reg_s3 > (".$row["KVAN_S3"].")
					) AS sc ON fac.fase = sc.fase INNER JOIN (
						SELECT 
							'FASE C' AS fase, count(*) AS n_su, ROUND((COUNT(*)*10)/60,3) AS h_su 
						FROM ".$tabla." 
						WHERE cro_id = ".$root." AND reg_s3 < (".($row["KVAN_S3"]*0.4).")
					) AS su on sc.fase = su.fase";
		}
		
		$mysqli = newMySQLi();
		if (mysqli_connect_errno()) {} // chequeo de coneccion
		
		//$muestra = '';
		$i = 0;
		$all_query_ok = true; // variable de control
		if ($result = $mysqli->query($sql)) {
			while($row2 = $result->fetch_object()){
				if ($row2->fase == 'FASE T'){
					$sql = 'INSERT INTO tm_scu_sobrecarga_unidad (';
					$sql.= 'scu_max,';
					$sql.= 'scu_avg,';
					$sql.= 'scu_min,';
					$sql.= 'scu_kvan,';				
					$sql.= 'scu_fecha,';
					$sql.= 'scu_placa,';
					$sql.= 'scu_num_sc,';
					$sql.= 'scu_horas_sc,';
					$sql.= 'scu_num_su,';
					$sql.= 'scu_horas_su';
					$sql.= ')VALUES(';					
					$sql.= "'".$row2->smax."',";	
					$sql.= "'".$row2->savg."',";	
					$sql.= "'".$row2->smin."',";		
					$sql.= "'".$row["KVAN_ST"]."',";
					$sql.= "'".$row2->reg_date_time."',";			
					$sql.= "'".$idUT."',";
					$sql.= "'".$row2->n_sc."',";			
					$sql.= "'".$row2->h_sc."',";
					$sql.= "'".$row2->n_su."',";			
					$sql.= "'".$row2->h_su."')";
					//$muestra.= $sql.PHP_EOL;
					$mysqli->query($sql) ? null : $all_query_ok = false;
					if($numEquip!=3){
						$sql = 'INSERT INTO tm_sce_sobrecarga_equipo (';
						$sql.= 'sce_max,';
						$sql.= 'sce_avg,';
						$sql.= 'sce_min,';
						$sql.= 'sce_kvan,';				
						$sql.= 'sce_fecha,';
						$sql.= 'sce_patrimonio,';
						$sql.= 'scu_placa,';
						$sql.= 'sce_num_sc,';
						$sql.= 'sce_horas_sc,';
						$sql.= 'sce_num_su,';
						$sql.= 'sce_horas_su,';
						$sql.= 'sce_sobrecarga';
						$sql.= ')VALUES(';						
						$sql.= "'".$row2->smax."',";	
						$sql.= "'".$row2->savg."',";	
						$sql.= "'".$row2->smin."',";				
						$sql.= "'".$row["KVAN_ST"]."',";
						$sql.= "'".$row2->reg_date_time."',";
						$sql.= "'".$row["PATRI_S1"]."',";
						$sql.= "'".$idUT."',";
						$sql.= "'".$row2->n_sc."',";			
						$sql.= "'".$row2->h_sc."',";
						$sql.= "'".$row2->n_su."',";			
						$sql.= "'".$row2->h_su."',";			
						$sql.= "'".$SC_rt."')";
						//$muestra.= $sql.PHP_EOL;
						$mysqli->query($sql) ? null : $all_query_ok = false;					
					}
				}else{
					$sql = 'INSERT INTO tm_sce_sobrecarga_equipo (';
					$sql.= 'sce_max,';
					$sql.= 'sce_avg,';
					$sql.= 'sce_min,';
					$sql.= 'sce_kvan,';				
					$sql.= 'sce_fecha,';
					$sql.= 'sce_patrimonio,';
					$sql.= 'scu_placa,';
					$sql.= 'sce_num_sc,';
					$sql.= 'sce_horas_sc,';
					$sql.= 'sce_num_su,';
					$sql.= 'sce_horas_su,';
					$sql.= 'sce_sobrecarga';
					$sql.= ')VALUES(';						
					$sql.= "'".$row2->smax."',";	
					$sql.= "'".$row2->savg."',";	
					$sql.= "'".$row2->smin."',";			
					$sql.= "'".$row["KVAN_S".++$i]."',";
					$sql.= "'".$row2->reg_date_time."',";
					$sql.= "'".$row["PATRI_S".$i]."',";
					$sql.= "'".$idUT."',";
					$sql.= "'".$row2->n_sc."',";			
					$sql.= "'".$row2->h_sc."',";
					$sql.= "'".$row2->n_su."',";			
					$sql.= "'".$row2->h_su."',";
					switch($i) {
						case '1': $sql.= "'".$SC_r1."')"; break;
						case '2': $sql.= "'".$SC_r2."')"; break;
						case '3': $sql.= "'".$SC_r3."')"; break;
					}
					//$muestra.= $sql.PHP_EOL;
					$mysqli->query($sql) ? null : $all_query_ok = false;
				}
			}
		}
		//return $muestra;
		if ($all_query_ok) {
			$mysqli->commit();
			$mysqli->close(); 
			return true;
		} else {
			$mysqli->rollback();
			$mysqli->close(); 
			return false;	
		}
		
	}
	
	function intToWeek($i) // Convierte una fecha en formato DateTime
	{
		switch($i) {
			case '0': return 'Domingo'; break;
			case '1': return 'Lunes'; break;
			case '2': return 'Martes'; break;
			case '3': return 'Miercoles'; break;
			case '4': return 'Jueves'; break;
			case '5': return 'Viernes'; break;
			case '6': return 'Sabado'; break;
		}
	}
?>