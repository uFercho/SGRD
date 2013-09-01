<?php
	header("Content-Type: text/plain"); 
	
	include_once("../getFunction.php");
	
	$root = $_POST['root']; 	//$root = 235;
	$nfase = $_POST['numFase'];	//$nfase = 3;
	$otros = isset($_POST['Otros'])?$_POST['Otros']:'Vp';
	
	$fields=array(
		array("name" => "Otros","header"=>"Parametro")	
	);
	
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
	switch($otros) {
		case 'Vp':
			$sql = "SELECT reg_date_time, reg_vp FROM ".$tabla." WHERE cro_id = ".$root; 
			$row = '';
			if ($result = $mysqli->query($sql)) {
				while($row = $result->fetch_object()){$data[0]['Otros'][] = array($row->reg_date_time, $row->reg_vp!=NULL?round((float)$row->reg_vp, 3, PHP_ROUND_HALF_UP):NULL);}
			}
			break;
		case 'In':
			$sql = "SELECT reg_date_time, reg_in FROM ".$tabla." WHERE cro_id = ".$root; 
			$row = '';
			if ($result = $mysqli->query($sql)) {
				while($row = $result->fetch_object()){$data[0]['Otros'][] = array($row->reg_date_time, $row->reg_in!=NULL?round((float)$row->reg_in, 3, PHP_ROUND_HALF_UP):NULL);}
			}
			break;
		case 'Db':
			$sql = "SELECT reg_date_time, reg_dv FROM ".$tabla." WHERE cro_id = ".$root; 
			$row = '';
			if ($result = $mysqli->query($sql)) {
				while($row = $result->fetch_object()){$data[0]['Otros'][] = array($row->reg_date_time, $row->reg_dv!=NULL?round((float)$row->reg_dv, 3, PHP_ROUND_HALF_UP):NULL);}
			}
			break;
		case 'St':
			$sql = "SELECT reg_date_time, reg_st FROM ".$tabla." WHERE cro_id = ".$root; 
			$row = '';
			if ($result = $mysqli->query($sql)) {
				while($row = $result->fetch_object()){$data[0]['Otros'][] = array($row->reg_date_time, $row->reg_st!=NULL?round((float)$row->reg_st, 3, PHP_ROUND_HALF_UP):NULL);}
			}
			break;
		case 'PFt':
			$sql = "SELECT reg_date_time, reg_fpt FROM ".$tabla." WHERE cro_id = ".$root; 
			$row = '';
			if ($result = $mysqli->query($sql)) {
				while($row = $result->fetch_object()){$data[0]['Otros'][] = array($row->reg_date_time, $row->reg_fpt!=NULL?round((float)$row->reg_fpt, 3, PHP_ROUND_HALF_UP):NULL);}
			}
			break;
		case 'Wt':
			$sql = "SELECT reg_date_time, reg_wt FROM ".$tabla." WHERE cro_id = ".$root; 
			$row = '';
			if ($result = $mysqli->query($sql)) {
				while($row = $result->fetch_object()){$data[0]['Otros'][] = array($row->reg_date_time, $row->reg_wt!=NULL?round((float)$row->reg_wt, 3, PHP_ROUND_HALF_UP):NULL);}
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