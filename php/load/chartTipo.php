<?php
	header("Content-Type: text/plain"); 
	
	include_once("../getFunction.php");
	
	$root = $_POST['root']; 	//$root = 604;
	$nfase = $_POST['numFase']; //$nfase = 3;
	
	$tipo = isset($_POST['cTipo'])?$_POST['cTipo']:'PF';
	$f1 = isset($_POST['f1'])?$_POST['f1']:1;
	$f2 = isset($_POST['f2'])?$_POST['f2']:1;
	$f3 = isset($_POST['f3'])?$_POST['f3']:1;
	
	$fields=array();
	if($f1==1)	$fields[] = array("name" => "f1","header"=>"Fase 1");
	if($f2==1)	$fields[] = array("name" => "f2","header"=>"Fase 2");
	if(($f3==1)&&($nfase==3))	$fields[] = array("name" => "f3","header"=>"Fase 3");
	
	switch($nfase) {
		case 1: $tabla = 'tm_reg_registro1f'; break;
		case 2: $tabla = 'tm_reg_registro2f'; break;
		case 3: $tabla = 'tm_reg_registro3f'; break;
	}	
	
	$mysqli = newMySQLi();
	
	if (mysqli_connect_errno()) {
		$output = msgReturn(false,'Conección fallida. '.mysqli_connect_error());
		exit;
	}
	switch($tipo) {
		case 'V':
			switch($nfase) {
				case 1: $campos = 'reg_date_time, reg_v1'; break;
				case 2: $campos = 'reg_date_time, reg_v1, reg_v2'; break;
				case 3: $campos = 'reg_date_time, reg_v1, reg_v2, reg_v3'; break;
			}	
			$sql = "SELECT ".$campos." FROM ".$tabla." WHERE cro_id = ".$root; //echo $sql;
			$row = '';
			if ($result = $mysqli->query($sql)) {
				while($row = $result->fetch_object()){
					if($f1==1) $data[0]['f1'][] = array($row->reg_date_time, $row->reg_v1!=NULL?round((float)$row->reg_v1, 3, PHP_ROUND_HALF_UP):NULL); 
					if(($f2==1)&&($nfase!=1)) $data[0]['f2'][] = array($row->reg_date_time, $row->reg_v2!=NULL?round((float)$row->reg_v2, 3, PHP_ROUND_HALF_UP):NULL);
					if(($f3==1)&&($nfase==3)) $data[0]['f3'][] = array($row->reg_date_time, $row->reg_v3!=NULL?round((float)$row->reg_v3, 3, PHP_ROUND_HALF_UP):NULL);
				}
			}
			break;
		case 'I':
			switch($nfase) {
				case 1: $campos = 'reg_date_time, reg_i1'; break;
				case 2: $campos = 'reg_date_time, reg_i1, reg_i2'; break;
				case 3: $campos = 'reg_date_time, reg_i1, reg_i2, reg_i3'; break;
			}
			$sql = "SELECT ".$campos." FROM ".$tabla." WHERE cro_id = ".$root; 
			$row = '';
			if ($result = $mysqli->query($sql)) {
				while($row = $result->fetch_object()){
					if($f1==1) $data[0]['f1'][] = array($row->reg_date_time, $row->reg_i1!=NULL?round((float)$row->reg_i1, 3, PHP_ROUND_HALF_UP):NULL); 
					if(($f2==1)&&($nfase!=1)) $data[0]['f2'][] = array($row->reg_date_time, $row->reg_i2!=NULL?round((float)$row->reg_i2, 3, PHP_ROUND_HALF_UP):NULL);
					if(($f3==1)&&($nfase==3)) $data[0]['f3'][] = array($row->reg_date_time, $row->reg_i3!=NULL?round((float)$row->reg_i3, 3, PHP_ROUND_HALF_UP):NULL);
				}
			}
			break;
		case 'S':
			switch($nfase) {
				case 1: $campos = 'reg_date_time, reg_s1'; break;
				case 2: $campos = 'reg_date_time, reg_s1, reg_s2'; break;
				case 3: $campos = 'reg_date_time, reg_s1, reg_s2, reg_s3'; break;
			}
			$sql = "SELECT ".$campos." FROM ".$tabla." WHERE cro_id = ".$root; 
			$row = '';
			if ($result = $mysqli->query($sql)) {
				while($row = $result->fetch_object()){
					if($f1==1) $data[0]['f1'][] = array($row->reg_date_time, $row->reg_s1!=NULL?round((float)$row->reg_s1, 3, PHP_ROUND_HALF_UP):NULL); 
					if(($f2==1)&&($nfase!=1)) $data[0]['f2'][] = array($row->reg_date_time, $row->reg_s2!=NULL?round((float)$row->reg_s2, 3, PHP_ROUND_HALF_UP):NULL);
					if(($f3==1)&&($nfase==3)) $data[0]['f3'][] = array($row->reg_date_time, $row->reg_s3!=NULL?round((float)$row->reg_s3, 3, PHP_ROUND_HALF_UP):NULL);
				}
			}
			break;
		case 'PF':
			switch($nfase) {
				case 1: $campos = 'reg_date_time, reg_fp1'; break;
				case 2: $campos = 'reg_date_time, reg_fp1, reg_fp2'; break;
				case 3: $campos = 'reg_date_time, reg_fp1, reg_fp2, reg_fp3'; break;
			}
			$sql = "SELECT ".$campos." FROM ".$tabla." WHERE cro_id = ".$root; //echo $sql;
			$row = '';
			if ($result = $mysqli->query($sql)) {
				while($row = $result->fetch_object()){
					if($f1==1) $data[0]['f1'][] = array($row->reg_date_time, $row->reg_fp1!=NULL?round((float)$row->reg_fp1, 3, PHP_ROUND_HALF_UP):NULL); 
					if(($f2==1)&&($nfase!=1)) $data[0]['f2'][] = array($row->reg_date_time, $row->reg_fp2!=NULL?round((float)$row->reg_fp2, 3, PHP_ROUND_HALF_UP):NULL);
					if(($f3==1)&&($nfase==3))$data[0]['f3'][] = array($row->reg_date_time, $row->reg_fp3!=NULL?round((float)$row->reg_fp3, 3, PHP_ROUND_HALF_UP):NULL);
				}
			}
			break;
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