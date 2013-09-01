<?php
	header("Content-Type: text/plain"); 
	
	include_once("../../getFunction.php");

	$root = $_POST['root'];
	$numFase = $_POST['numFase'];
	//$root = 694;
	//$numFase = 2;
	
	$fields=array();
	$fields[] = array("name" => "info","header"=>"");
	$fields[] = array("name" => "fase_1","header"=>"FASE A");
	switch($numFase) {
		case '3': 			
			$fields[] = array("name" => "fase_2","header"=>"FASE B");
			$fields[] = array("name" => "fase_3","header"=>"FASE C");
			$sql = "SELECT 'MIN' AS info, MIN(reg_i1) AS fase_1, MIN(reg_i2) AS fase_2, MIN(reg_i3) AS fase_3, MIN(reg_dv) AS desb  
					FROM tm_reg_registro3f 
					WHERE cro_id = ".$root." UNION ALL
					SELECT 'PRO' AS info, AVG(reg_i1) AS fase_1, AVG(reg_i2) AS fase_2, AVG(reg_i3) AS fase_3, AVG(reg_dv) AS desb  
					FROM tm_reg_registro3f 
					WHERE cro_id = ".$root." UNION ALL
					SELECT 'MAX' AS info, MAX(reg_i1) AS fase_1, MAX(reg_i2) AS fase_2, MAX(reg_i3) AS fase_3, MAX(reg_dv) AS desb  
					FROM tm_reg_registro3f
					WHERE cro_id = ".$root;/*." UNION ALL
					SELECT 'CAL' AS info, 0 AS fase_1, 0 AS fase_2, 0 AS fase_3, 0 AS neutro, 0 AS desb  
					FROM tm_reg_registro3f
					WHERE cro_id = ".$root;  */
			break;
		case '2':
			$fields[] = array("name" => "fase_2","header"=>"FASE B");
			$sql = "SELECT 'MIN' AS info, MIN(reg_i1) AS fase_1, MIN(reg_i2) AS fase_2, MIN(reg_dv) AS desb  
					FROM tm_reg_registro2f 
					WHERE cro_id = ".$root." UNION ALL
					SELECT 'PRO' AS info, AVG(reg_i1) AS fase_1, AVG(reg_i2) AS fase_2, AVG(reg_dv) AS desb  
					FROM tm_reg_registro2f 
					WHERE cro_id = ".$root." UNION ALL
					SELECT 'MAX' AS info, MAX(reg_i1) AS fase_1, MAX(reg_i2) AS fase_2, MAX(reg_dv) AS desb  
					FROM tm_reg_registro2f
					WHERE cro_id = ".$root;/*." UNION ALL
					SELECT 'CAL' AS info, 0 AS fase_1, 0 AS fase_2, 0 AS neutro, 0 AS desb  
					FROM tm_reg_registro2f
					WHERE cro_id = ".$root;  */ 
			break;
	}
	$fields[] = array("name" => "desb","header"=>"Desb(%)");
	
	
	$mysqli = newMySQLi();
	// chequeo de coneccion
	if (mysqli_connect_errno()) {}
	
	if ($result = $mysqli->query($sql)) {
		//validar el resultado!
		$rowNum = 0;
		while($row = $result->fetch_object()) {
			$data[$rowNum]['info']   = $row->info;
			$data[$rowNum]['fase_1'] = round($row->fase_1, 3, PHP_ROUND_HALF_UP);
			if ($numFase!='1') $data[$rowNum]['fase_2'] = round($row->fase_2, 3, PHP_ROUND_HALF_UP);
			if ($numFase=='3') $data[$rowNum]['fase_3'] = round($row->fase_3, 3, PHP_ROUND_HALF_UP);
			$data[$rowNum]['desb']   = round($row->desb, 3, PHP_ROUND_HALF_UP);
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