<?php
	header("Content-Type: text/plain"); 
	
	include_once("../getFunction.php");
	
	$root = $_POST['root'];
	$nfase = $_POST['numFase'];	
	
	$fields=array(
		array("name" => "iniDate","header"=>"Inicio_F"),
		array("name" => "finDate","header"=>"Inicio_H"),
		array("name" => "vProm","header"=>"vProm")	
	);
	
	switch($nfase) {
		case 1: $tabla = 'tm_reg_registro1f'; break;
		case 2: $tabla = 'tm_reg_registro2f'; break;
		case 3: $tabla = 'tm_reg_registro3f'; break;
	}
	
	connectMySQL();
	$sql = "SELECT MIN(reg_date_time) as minimo, MAX(reg_date_time) as maximo FROM ".$tabla." WHERE cro_id = '".$root."'";
	
	$result =  mysql_query($sql);
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	$data[0]['iniDate'] = $row['minimo'];
	$data[0]['finDate'] = $row['maximo'];
	
	$sql = "SELECT reg_date_time, reg_vp FROM ".$tabla." WHERE cro_id = '".$root."'";
	
	$result =  mysql_query($sql);
	
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$data[0]['vProm'][] = array($row['reg_date_time'],$row['reg_vp']!=NULL?round((float)$row['reg_vp'], 3, PHP_ROUND_HALF_UP):NULL);
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