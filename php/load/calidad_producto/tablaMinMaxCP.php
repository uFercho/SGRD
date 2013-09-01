<?php
	header("Content-Type: text/plain"); 
	
	include_once("../../getFunction.php");
	
	$root = $_POST['root'];
	$nfase = $_POST['numFase'];
	
	$mysqli = newMySQLi();
	// chequeo de coneccion
	if (mysqli_connect_errno()) {
		$output = msgReturn(false,'Conección fallida. '.mysqli_connect_error());
		exit;
	}
	// se desabilita el autocommit
	$mysqli->autocommit(FALSE);
	
	
	
	$fields=array();
	$fields[] = array("name" => "col_1","header"=>"");
	switch($nfase) {
		case 2: 
			array_push($fields,array("name" => "col_2","header"=>"FASE 1"),array("name" => "col_3","header"=>"FASE 2")); 
			$sql = "SELECT 'MAX' AS col_1, MAX(reg_v1) AS col_2, MAX(reg_v2) AS col_3, (MAX(reg_v1)+MAX(reg_v2))/2 AS col_5
				FROM tm_reg_registro2f 
				WHERE cro_id = ".$root." UNION ALL
				
				SELECT 'PRO' AS col_1, AVG(reg_v1) AS col_2, AVG(reg_v2) AS col_3, (AVG(reg_v1)+AVG(reg_v2))/2 AS col_5
				FROM tm_reg_registro2f 
				WHERE cro_id = ".$root." UNION ALL 
				
				SELECT 'MIN' AS col_1, MIN(reg_v1) AS col_2, MIN(reg_v2) AS col_3, (MIN(reg_v1)+MIN(reg_v2))/2 AS col_5
				FROM tm_reg_registro2f 
				WHERE cro_id = ".$root;  
			break;
		case 3: 
			array_push($fields,array("name" => "col_2","header"=>"FASE 1"),array("name" => "col_3","header"=>"FASE 2"),array("name" => "col_4","header"=>"FASE 3")); 
			$sql = "SELECT 'MAX' AS col_1, MAX(reg_v1) AS col_2, MAX(reg_v2) AS col_3, MAX(reg_v3) AS col_4, (MAX(reg_v1)+MAX(reg_v2)+MAX(reg_v3))/3 AS col_5
				FROM tm_reg_registro3f 
				WHERE cro_id = ".$root." UNION ALL
				
				SELECT 'PRO' AS col_1, AVG(reg_v1) AS col_2, AVG(reg_v2) AS col_3, AVG(reg_v3) AS col_4, (AVG(reg_v1)+AVG(reg_v2)+AVG(reg_v3))/3 AS col_5
				FROM tm_reg_registro3f 
				WHERE cro_id = ".$root." UNION ALL 
				
				SELECT 'MIN' AS col_1, MIN(reg_v1) AS col_2, MIN(reg_v2) AS col_3, MIN(reg_v3) AS col_4, (MIN(reg_v1)+MIN(reg_v2)+MIN(reg_v3))/3 AS col_5
				FROM tm_reg_registro3f 
				WHERE cro_id = ".$root;  
			break;
	}
	$fields[] = array("name" => "col_5","header"=>"vPROM ");
	
	$rowNum = 0;
	$row = '';
	if ($result = $mysqli->query($sql)) {
        while($row = $result->fetch_object()){
            $data[$rowNum]['col_1'] = $row->col_1;
            $data[$rowNum]['col_2'] = round($row->col_2, 3, PHP_ROUND_HALF_UP);
            if($nfase!=1) $data[$rowNum]['col_3'] = round($row->col_3, 3, PHP_ROUND_HALF_UP);
			if($nfase==3) $data[$rowNum]['col_4'] = round($row->col_4, 3, PHP_ROUND_HALF_UP);
            $data[$rowNum]['col_5'] = round($row->col_5, 3, PHP_ROUND_HALF_UP);
			$rowNum++;
        }
    }
	
    $result->close(); 
    unset($row); 
    unset($sql); 
    unset($query);
	
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