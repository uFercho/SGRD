<?php
	header("Content-Type: text/plain"); 
	
	include_once("../../getFunction.php");
	
	$root = $_POST['root'];
	
	$fields=array(
		array("name" => "cro_row","header"=>"Punto"),
		array("name" => "cro_placa","header"=>"Placa"),
		array("name" => "cro_serial","header"=>"Serial"),
		array("name" => "ini_date","header"=>"Fecha Inicio"),
		array("name" => "ini_hour","header"=>"Hora Inicio"),
		array("name" => "fin_date","header"=>"Fecha Inicio"),
		array("name" => "fin_hour","header"=>"Hora Inicio"),
		array("name" => "intervalo","header"=>"Intervalo")
	);
	
	$mysqli = newMySQLi();
	// chequeo de coneccion
	if (mysqli_connect_errno()) {exit();}
	
	$sql = "SELECT 
				cro_row,
				cro_placa, 
				cro_serial, 
				DATE_FORMAT(cro_colocacion,'%d/%m/%Y') AS ini_date, 
				DATE_FORMAT(cro_colocacion,'%T') AS ini_hour, 
				DATE_FORMAT(cro_retiro_real,'%d/%m/%Y') AS fin_date, 
				DATE_FORMAT(cro_retiro_real,'%T') AS fin_hour, 
				cro_intervalo AS intervalo 
			FROM 
				tm_cro_cronograma 
			WHERE 
				cro_id = ".$root;
				
    if ($result = $mysqli->query($sql)) { 
        $row = $result->fetch_object();
		
		$data[0]['cro_row']    = $row->cro_row;
		$data[0]['cro_placa']  = $row->cro_placa;
		$data[0]['cro_serial'] = $row->cro_serial;
		$data[0]['ini_date']   = $row->ini_date;
		$data[0]['ini_hour']   = $row->ini_hour;
		$data[0]['fin_date']   = $row->fin_date;
		$data[0]['fin_hour']   = $row->fin_hour;
		$data[0]['intervalo']  = $row->intervalo;
    } 
    $result->close(); 
    unset($obj); 
    unset($sql); 
	
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