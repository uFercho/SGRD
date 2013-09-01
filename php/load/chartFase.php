<?php
	header("Content-Type: text/plain"); 
	
	include_once("../getFunction.php");
	
	$root = $_POST['root']; 	//$root = 694;
	$nfase = $_POST['numFase'];	//$nfase = 2;
	
	$fase = isset($_POST['Fase'])?$_POST['cFase']:'f1';
	$cV = isset($_POST['cV']) ?$_POST['cV']:1;
	$cI = isset($_POST['cI']) ?$_POST['cI']:1;
	$cS = isset($_POST['cS']) ?$_POST['cS']:1;
	$cPf= isset($_POST['cPf'])?$_POST['cPf']:1;
	
	$fields=array();
	if($cV==1)	$fields[] = array("name" => "cV" ,"header"=>"Voltaje");
	if($cI==1)	$fields[] = array("name" => "cI" ,"header"=>"Corriente");
	if($cS==1)	$fields[] = array("name" => "cS" ,"header"=>"Potencia");
	if($cPf==1)	$fields[] = array("name" => "cPf","header"=>"FPotencia");
	
	switch($nfase) {
		case 1: $tabla = 'tm_reg_registro1f'; break;
		case 2: $tabla = 'tm_reg_registro2f'; break;
		case 3: $tabla = 'tm_reg_registro3f'; break;
	}
	
	$mysqli = newMySQLi();
	
	if (mysqli_connect_errno()) { $output = msgReturn(false,'Conección fallida. '.mysqli_connect_error()); exit; }
	
	switch($fase) {
		case 'f1':
			$sql = "SELECT reg_date_time, reg_v1, reg_i1, reg_s1, reg_fp1 FROM ".$tabla." WHERE cro_id = ".$root;  //echo $sql;
			$row = '';
			if ($result = $mysqli->query($sql)) {
				while($row = $result->fetch_object()){
					if($cV==1) $data[0]['cV'][] = array($row->reg_date_time, $row->reg_v1!=NULL?round((float)$row->reg_v1, 3, PHP_ROUND_HALF_UP):NULL); 
					if($cI==1) $data[0]['cI'][] = array($row->reg_date_time, $row->reg_i1!=NULL?round((float)$row->reg_i1, 3, PHP_ROUND_HALF_UP):NULL);
					if($cS==1) $data[0]['cS'][] = array($row->reg_date_time, $row->reg_s1!=NULL?round((float)$row->reg_s1, 3, PHP_ROUND_HALF_UP):NULL);
					if($cPf==1)$data[0]['cPf'][]= array($row->reg_date_time, $row->reg_fp1!=NULL?round((float)$row->reg_fp1, 3, PHP_ROUND_HALF_UP):NULL);
				}
			}
			break;
		case 'f2':
			$sql = "SELECT reg_date_time, reg_v2, reg_i2, reg_s2, reg_fp2 FROM ".$tabla." WHERE cro_id = ".$root; 
			$row = '';
			if ($result = $mysqli->query($sql)) {
				while($row = $result->fetch_object()){
					if($cV==1) $data[0]['cV'][] = array($row->reg_date_time, $row->reg_v2!=NULL?round((float)$row->reg_v2, 3, PHP_ROUND_HALF_UP):NULL); 
					if($cI==1) $data[0]['cI'][] = array($row->reg_date_time, $row->reg_i2!=NULL?round((float)$row->reg_i2, 3, PHP_ROUND_HALF_UP):NULL);
					if($cS==1) $data[0]['cS'][] = array($row->reg_date_time, $row->reg_s2!=NULL?round((float)$row->reg_s2, 3, PHP_ROUND_HALF_UP):NULL);
					if($cPf==1)$data[0]['cPf'][]= array($row->reg_date_time, $row->reg_fp2!=NULL?round((float)$row->reg_fp2, 3, PHP_ROUND_HALF_UP):NULL);
				}
			}
			break;
		case 'f3':
			$sql = "SELECT reg_date_time, reg_v3, reg_i3, reg_s3, reg_fp3 FROM ".$tabla." WHERE cro_id = ".$root; 
			$row = '';
			if ($result = $mysqli->query($sql)) {
				while($row = $result->fetch_object()){
					if($cV==1) $data[0]['cV'][] = array($row->reg_date_time, $row->reg_v3!=NULL?round((float)$row->reg_v3, 3, PHP_ROUND_HALF_UP):NULL); 
					if($cI==1) $data[0]['cI'][] = array($row->reg_date_time, $row->reg_i3!=NULL?round((float)$row->reg_i3, 3, PHP_ROUND_HALF_UP):NULL);
					if($cS==1) $data[0]['cS'][] = array($row->reg_date_time, $row->reg_s3!=NULL?round((float)$row->reg_s3, 3, PHP_ROUND_HALF_UP):NULL);
					if($cPf==1)$data[0]['cPf'][]= array($row->reg_date_time, $row->reg_fp3!=NULL?round((float)$row->reg_fp3, 3, PHP_ROUND_HALF_UP):NULL);
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