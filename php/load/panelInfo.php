<?php
	header("Content-Type: text/plain"); 
	
	include_once("../getFunction.php");
	
	$root = $_POST['root'];
	//$root = 3;
	$fields=array(
		array("name" => "serial","header"=>"Serial"),
		array("name" => "placa","header"=>"Placa"),
		array("name" => "colocacion","header"=>"Colocacion"),
		array("name" => "punto","header"=>"Punto")
	);
	
	connectMySQL();
	
	$sql = "SELECT cro_serial, cro_placa, CONCAT(cro_punto, ' - ', cro_municipio) as cro_punto, DATE_FORMAT(cro_colocacion, '%d/%m/%y %H:%i') as cro_colocacion FROM tm_cro_cronograma WHERE cro_id = '".$root."'";
	
	$result =  mysql_query($sql);
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	$data[0]['serial'] = $row['cro_serial'];
	$data[0]['placa'] = $row['cro_placa'];
	$data[0]['colocacion'] = $row['cro_colocacion'];
	$data[0]['punto'] = utf8_encode($row['cro_punto']);
	
	
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