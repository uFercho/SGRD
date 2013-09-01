<?php
	include_once("../db_sgd/conect.php");
	include_once("../getFunction.php");
	
	$output = msgReturn(false,'Error inesperado.');
	
	if (isset($_FILES)) {
		$dir_temp = 'tmp';
		$temp_file_name = $_FILES["file-txt"]["tmp_name"];
		$original_file_name = $_FILES["file-txt"]["name"];
		
		// se busca la extension del archivo
		$ext = explode ('.', $original_file_name);
		$ext = $ext [count ($ext) - 1];
		
		if (strtolower($ext) == 'txt') {
			$root = $dir_temp.'/'.$original_file_name;
			if (move_uploaded_file($temp_file_name, $root)) {
				//$root = '../../Archivos Pruebas/TXT/Agosto 2009/Grupo A/003001.TXT'; // 3f
				//$root = '../../Archivos Pruebas/TXT/NoName/Junio 2010/Grupo B/028001_C.TXT'; // 2f
				$fp = fopen($root,'r');
				$cadena = fread($fp, filesize($root));					
				fclose($fp);
				unlink($root);
				$arreglo = explode("\n", $cadena);	//se divide el contenido en un array por lineas
				$iniData = 0;				
				foreach($arreglo as $linea)	{
					$partes = explode("\t", $linea);	//se divide las lineas por palabras
					switch(str_replace('"', '', $partes[0])) {
						case 'Serialnumber:':
							$serial = trim($partes[1]);
							break;
						case 'Start:':
							$DateTimeIni = formatDateTime(str_replace('"', '', $partes[1]),str_replace('"', '', $partes[2]));
							break;	
						case 'End:': 
							$DateTimeFin = formatDateTime(str_replace('"', '', $partes[1]),str_replace('"', '', $partes[2]));
							break;
						case 'Storage interval:': 
							$Intervalo = substr(str_replace('"', '', trim($partes[1])),2,-3);
							break;
					}
					if ($partes[0] == 'Date') {	
						$mysqli = newMySQLi(); //$mysqli = new mysqli('localhost', 'my_user', 'my_password', 'my_db');
						// chequeo de coneccion
						if (mysqli_connect_errno()) {
							$output = msgReturn(false,'Conección fallida. '.mysqli_connect_error());
							break;
						}
						// se desabilita el autocommit
						$mysqli->autocommit(FALSE);
						
						$sql = "SELECT cro_id, cro_placa FROM tm_cro_cronograma WHERE cro_serial = '".$serial."' AND cro_colocacion BETWEEN CONCAT(date('".$DateTimeIni."'),' 00:00:00') AND CONCAT(date('".$DateTimeIni."'),' 23:59:59')";
						if ($result = $mysqli->query($sql)) {
							//validar el resultado!
							$row = $result->fetch_row(); 
							// Free result 
							$result->close(); 
						}
						
						if(!$row) {
							$output = msgReturn(false,'Esta medición no esta asignada a una Campaña. Serila: '.$serial.' ini: '.$DateTimeIni);
							break;
						} else {
							$sql = "UPDATE tm_cro_cronograma ";
							$sql.= "SET cro_colocacion = '".$DateTimeIni."', cro_retiro_real = '".$DateTimeFin."', cro_intervalo = '".$Intervalo."' ";
							$sql.= "WHERE cro_id = ".$row[0];
							
							$all_query_ok=true; // variable de control
							$mysqli->query($sql) ? null : $all_query_ok = false;
							
							$sql = "select ut.qtdfases, count(*) as numequip ";
							$sql.= "from utransformadora ut inner join eqtransformador eq on ut.instalacao = eq.instalacao ";
							$sql.= "where ut.instalacao = '".$row[1]."' ";
							$sql.= "group by ut.qtdfases ";
							
							//$sql = "select qtdfases from utransformadora where instalacao='".$row[1]."'";	
							
							$stid = oci_parse(conectar_sgd(), $sql);
							oci_execute($stid);
							$results = oci_fetch_assoc($stid);
							
							switch($results["QTDFASES"]) {
								case '2': $sql = 'INSERT INTO tm_reg_registro2f ('; break;	
								case '3': $sql = 'INSERT INTO tm_reg_registro3f ('; break;
							}									 
							$sql.= 'cro_id,';
							$sql.= 'reg_row,';
							$sql.= 'reg_date_time,';						
							$sql.= headerColum($partes);
							$sql.= ')VALUES';
							
							$iniData = 1;
							$rowNum = 0;
							continue;
						}
					}
					if ((trim($partes[0]) != '')&&($iniData == 1)) {
						$rowNum++;
						$sql.= "(";					
						$sql.= "'".$row[0]."',";
						$sql.= "'".$rowNum."',";
						$sql.= "'".formatDateTime(str_replace('"', '', $partes[0]),str_replace('"', '', $partes[1]))."',";
						for($i=2; $i < count($partes)-1; $i++) {
							$sql.= $partes[$i] != '' ? "'".$partes[$i]."'," : "null,";
						}						
						$sql = substr($sql, 0, -1).'),';
					}	
				}
				
				//echo $sql.PHP_EOL;
				
				if($iniData == 1) {
					$mysqli->query(substr($sql, 0, -1)) ? null : $all_query_ok = false;
					// si los query no dan errores se hace el commit sino se hace el rollback
					if ($all_query_ok) {
						$mysqli->commit();
						$mysqli->close(); 
						
						if(buscaSobreCarga($row[0], $row[1], $results["QTDFASES"], $results["NUMEQUIP"])) $output = msgReturn(true,$_FILES['file-txt']['name']);
						else $output = msgReturn(true,$_FILES['file-txt']['name']." - ERROR - SC");
					} else {
						$mysqli->rollback();
						$mysqli->close(); 
						
						//$output = msgReturn(false,substr($sql, 0, -1));
						$output = msgReturn(false,'No se pudo cargar el archivo, probablemente ya exista en el Servidor. '.$DateTimeIni);
					}
				}
			} else {$output = msgReturn(false,'Hubo un error del servidor al manipular el archivo. Intentelo de nuevo.');}
		} else {$output = msgReturn(false,'La extensión no es ".TXT". El archivo no es valido');}
	} else {$output = msgReturn(false,'El archivo no es valido');}
	
	echo $output;
			
	function headerColum($colums) {
		$fRows = 0;
		$query = '';
		foreach($colums as $colum) {
			switch($colum) {					
				//se agregan los arrays del voltage de las fases
				case '"U1  [V]"': 
					$query.= 'reg_v1, ';
				break;
				case '"U2  [V]"':
					$query.= 'reg_v2, ';
				break;
				case '"U3  [V]"': 
					$query.= 'reg_v3, ';	
				break;
				
				//se agregan los arrays de la corriente de las fases
				case '"I5  [A]"': 
					$query.= 'reg_i1, ';
				break;
				case '"I6  [A]"': 
					$query.= 'reg_i2, ';
				break;
				case '"I7  [A]"': 
					$query.= 'reg_i3, ';
				break;
				case '"I8  [A]"': 
					$query.= 'reg_in, ';
				break;
										
				//se agregan los arrays de la potencia de las fases	
				case '"S1-5  [VA]"': 
					$query.= 'reg_s1, ';
				break;
				case '"S2-6  [VA]"': 
					$query.= 'reg_s2, ';
				break;
				case '"S3-7  [VA]"': 
					$query.= 'reg_s3, ';
				break;
				case '"S1-7  [VA]"': 
					$query.= 'reg_st, ';
				break;
				
				//se agregan los arrays del factor de potencia
				case '"PF1-5  []"': 
					$query.= 'reg_fp1, ';
				break;
				case '"PF2-6  []"': 
					$query.= 'reg_fp2, ';
				break;
				case '"PF3-7  []"': 
					$query.= 'reg_fp3, ';
				break;
				case '"PF1-7  []"': 
					$query.= 'reg_fpt, ';
				break;
									
				//se agregan los arrays de la energia consumida
				case '"Wp1-7  [Wh]"': 
					$query.= 'reg_wt';
				break;
			}
		}
		return $query;
	}
?>
