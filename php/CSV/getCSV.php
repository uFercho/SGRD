<?php
	include_once("../getFunction.php");
	
	$campana  = isset($_POST['txt-campana'])?$_POST['txt-campana']:'Campana Agosto 2009';
	$comenta  = isset($_POST['txt-comenta'])?$_POST['txt-comenta']:'';
	
	$campana = Sustituto_Cadena($campana);
	$comenta = Sustituto_Cadena($comenta);
	
	$muestra = 0;
	
	$output = msgReturn(false,'Error inesperado.');
	
	if (isset($_FILES))
	{
		$dir_temp = 'tmp';
		$temp_file_name = $_FILES["file-csv"]["tmp_name"];
		$original_file_name = $_FILES["file-csv"]["name"];
		
		// Find file extention
		$ext = explode ('.', $original_file_name);
		$ext = $ext [count ($ext) - 1];
		
		if (strtolower($ext) == 'csv') 
		{
			$root = $dir_temp.'/'.$original_file_name;
			if (move_uploaded_file($temp_file_name, $root))
			{
				//$root = '../../Archivos Pruebas/CSV/Cal_Ago_2009.csv';
				$fp = fopen($root,'r');
				$cadena = fread($fp, filesize($root));					
				fclose($fp);
				unlink($root);
				
				$arreglo = explode("\n", $cadena);	//se divide el contenido en un array por lineas
				
				connectMySQL();
				
				$sql = "INSERT INTO tm_cam_campana (cam_descripcion ,cam_comentarios) VALUES ('".$campana."', '".$comenta."');";
				
				if ( mysql_query($sql) )
				{					
					$sql = 'SELECT MAX( cam_id ) as ID FROM tm_cam_campana'; //$sql = 'SELECT LAST_INSERT_ID()';
					$result =  mysql_query($sql);
					$row = mysql_fetch_array($result, MYSQL_ASSOC);
					
					$cam_id = $row['ID'];
					
					$sql = 'INSERT INTO tm_cro_cronograma (';
					$sql.= 'cam_id, ';
					$sql.= 'cro_row, ';
					$sql.= 'cro_volt_nominal, ';
					$sql.= 'cro_punto, ';
					$sql.= 'cro_subestacion, ';
					$sql.= 'cro_circuito, ';
					$sql.= 'cro_municipio, ';
					$sql.= 'cro_tipo, ';
					$sql.= 'cro_carga, ';
					$sql.= 'cro_clase, ';
					$sql.= 'cro_serial, ';
					$sql.= 'cro_placa, ';
					$sql.= 'cro_colocacion, ';
					$sql.= 'cro_retiro_prev, ';
					$sql.= 'cro_retiro_real, ';
					$sql.= 'cro_reg_total, ';
					$sql.= 'cro_reg_evalu, ';
					$sql.= 'cro_reg_malos, ';
					$sql.= 'cro_feb, ';
					$sql.= 'cro_comentarios';
					$sql.= ') VALUES ';
						
					foreach($arreglo as $linea)
					{
						$muestra++;
						$partes = explode(";", $linea);	//se divide las lineas por palabras
							
						//se capturan los datas de todas las tuplas
						if ( ((int)$partes[0] >= 1) && ((int)$partes[0] <= 54) ) //if ((int)$partes[0] == 1) 
						{							
							$sql.= "(";					
							$sql.= "'".$cam_id."', ";	//cam_id int
							$sql.= "'".$partes[0]."', ";
							$sql.= "'".$partes[1]."', ";
							$sql.= "'".$partes[2]."', ";
							$sql.= "'".$partes[3]."', ";
							$sql.= "'".$partes[4]."', ";
							$sql.= "'".$partes[5]."', ";
							$sql.= "'".$partes[6]."', ";
							$sql.= "'".$partes[13]."', ";
							$sql.= "'".$partes[14]."', ";
							$sql.= "'".$partes[15]."', ";
							$sql.= "'".Valida_root($partes[16])."', ";
							$sql.= "'".formatDateTime($partes[17],$partes[18]).":00', ";	
							$sql.= "'".formatDateTime($partes[19],$partes[20]).":00', ";	//se modifico el resultado de la funcion
							$sql.= "'".formatDateTime($partes[21],$partes[22]).":00', ";	
							$sql.= "'".$partes[23]."', ";
							$sql.= "'".$partes[24]."', ";
							$sql.= "'".$partes[25]."', ";
							$sql.= "'".$partes[26]."', ";
							$sql.= "'".$partes[27]."'),"; //nFases
						}	
					}
					
					if ( mysql_query( substr($sql, 0, -1).';' ) ){$output = msgReturn(true,$_FILES['file-csv']['name']);}
					else
					{
						$sql = "DELETE FROM tm_cam_campana WHERE cam_id = '".$cam_id."'";
						mysql_query($sql);
						$output = msgReturn(false,'lineas: '.count($arreglo).' - No se pudo crear la Campaña____2. Probablemente ya exita en el Servidor');
					}
				}else{$output = msgReturn(false,'No se pudo crear la Campaña. Probablemente ya exita en el Servidor');}
			}else{$output = msgReturn(false,'Hubo un error del servidor al manipular el archivo. Intentelo de nuevo.');}
		}else{$output = msgReturn(false,'La extensión no es ".CSV". El archivo no es valido');}
	}else{$output = msgReturn(false,'El archivo no es valido');}
	echo $output;
?>
