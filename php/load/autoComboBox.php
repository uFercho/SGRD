<?php
	header("Content-Type: text/plain");
	
	include_once("../getFunction.php");
		
	$start = isset($_POST['start'])?$_POST['start']:0; //posición a iniciar
	$limit = isset($_POST['limit'])?$_POST['limit']:25; //número de registros a mostrar
	$query = isset($_POST['query'])?$_POST['query']:'CC02'; 
	
	$fields= array(
		array("name" => "root","type"=>"string"),
		array("name" => "placa","type"=>"string"),
		array("name" => "fecha","type"=>"string")
	);
	
	$sql = "SELECT 
				cro_id,
				cro_placa,  
				DATE_FORMAT(cro_colocacion,'%d/%m/%Y') AS fecha_ini 
			FROM 
				tm_cro_cronograma cro INNER JOIN tm_tre_tree_menu tre ON cro.cro_id = tre.tre_id_tabla 
			WHERE
				cro.cro_placa LIKE  CONCAT('".Valida_root($query)."','%')  AND 
				tre.tre_categoria='PUNTO' AND 
				tre.tre_estado='PROC'";
	
	$mysqli = newMySQLi();
	if (mysqli_connect_errno()) {}
	
	if ($result = $mysqli->query($sql)) {
		$rowNum = 0;
		while($row = $result->fetch_object()) {
			$data[$rowNum]['root']  = $row->cro_id;
			$data[$rowNum]['placa'] = $row->cro_placa;
			$data[$rowNum]['fecha'] = $row->fecha_ini;
			$rowNum++;
		} 
		// Free result 
		$result->close(); 
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