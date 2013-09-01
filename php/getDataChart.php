<?php
	header("Content-Type: text/plain"); 
	
	$params = isset($_POST['params'])?$_POST['params']:''; //parametros para el chart
	$root  = isset($_POST['root'])?$_POST['root']:'';
		
	$archivo = 'files'.$root;
	
	$fp = fopen($archivo,'r');
	$cadena = fread($fp, filesize($archivo));
	
	$arreglo = explode("\n", $cadena);	//se divide el contenido en un array por lineas
	
	$data =	array();
	$data[0]['Vp'] = array();

	$fields=array(
		array("name" => "iniD","header"=>"Inicio_F"),
		array("name" => "finD","header"=>"Inicio_H"),
		array("name" => "Vp","header"=>"vProm")	
	);
	
	$iniData = 0;
	
	$param = explode(",", $params);
	
	foreach($arreglo as $linea)
	{
		$partes = explode("\t", $linea);	//se divide las lineas por palabras
		
		switch(str_replace('"', '', $partes[0]))
		{
			case 'Start:': 
				$dateTime = str_replace('-','/',str_replace('"', '', trim($partes[1])));
				$dateTime = substr($dateTime,3,2).'/'.substr($dateTime,0,2).'/'.substr($dateTime,8,2);
				$dateTime.= ' '.str_replace('"', '', trim($partes[2]));
				$data[0]['iniD'] = $dateTime;
				break;
			case 'End:': 
				$dateTime = str_replace('-','/',str_replace('"', '', trim($partes[1])));
				$dateTime = substr($dateTime,3,2).'/'.substr($dateTime,0,2).'/'.substr($dateTime,8,2);
				$dateTime.= ' '.str_replace('"', '', trim($partes[2]));
				$data[0]['finD']  = $dateTime;
				break;	
		}
		
		if ($partes[0] == 'Date')
		{
			$numFases = 0;
			$tRows = 0;
			foreach($partes as $colum)
			{
				$tRows++;
				switch($colum)
				{
					//se agregan los arrays del voltage de las fases
					case '"U1  [V]"': $numFases++; break;
					case '"U2  [V]"': $numFases++; break;
					case '"U3  [V]"': $numFases++; break;
				}
			}
			$iniData = 1;
			continue;
		}
		
		if ((trim($partes[0]) != '')&&($iniData == 1))
		{
			$dateTime = str_replace('-','/',str_replace('"', '', trim($partes[0])));
			$dateTime = substr($dateTime,3,2).'/'.substr($dateTime,0,2).'/'.substr($dateTime,8,2);
			$dateTime.= ' '.str_replace('"', '', trim($partes[1]));
			
			
			
			if ($numFases == 3)
				$vProm = ((float)$partes[2]+(float)$partes[3]+(float)$partes[4]) / 3;
			elseif ($numFases == 2) 
				$vProm = ((float)$partes[2]+(float)$partes[3]) / 2;
			else
				$vProm = (float)$partes[2];	
			$vProm = round($vProm, 3, PHP_ROUND_HALF_UP);
				
			$data[0]['Vp'][] = array($dateTime,$vProm);
		}
	}
	
	$metadata = array(
		"totalProperty"		=> "total",
		"successProperty"	=> "success",
		"fields"			=> $fields,
		"root"				=> "data"
	);
	
	$paging = array(
		'success'	=>true,
		'metaData'	=> $metadata,
		'total'		=>count($data), //<--- total de registros a paginar
		'data'		=> array_splice($data,0,count($data))
	);
	
	echo json_encode($paging);
	//print_r($paging);
?>
