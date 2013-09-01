<?php
	header("Content-Type: text/plain"); 
	
	include_once("../../getFunction.php");
	
	$start = isset($_POST['start'])?$_POST['start']:0; //posición a iniciar
	$limit = isset($_POST['limit'])?$_POST['limit']:25; //número de registros a mostrar
	$root = isset($_POST['root'])?$_POST['root']:604;
	$idUT = isset($_POST['idUT'])?$_POST['idUT']:'CC01986';
	$nfase = isset($_POST['numFase'])?$_POST['numFase']:3;
	$reg_s = isset($_POST['reg_s'])?$_POST['reg_s']:'reg_st';
	$statsUE = isset($_POST['statsUE'])?$_POST['statsUE']:'SC';
	
	$mysqliSGD	= newMySQLiSGD();
	if (mysqli_connect_errno()) {exit();}
	
	switch($reg_s) {
		case 'reg_st': $sql = "SELECT ROUND(kvan*1000,3) AS kvan FROM utransformadora WHERE instalacao='".$idUT."' "; break;
		case 'reg_s1': $sql = "SELECT ROUND(kvan*1000,3) AS kvan FROM eqtransformador WHERE instalacao='".$idUT."' AND faseligsec='A' "; break;
		case 'reg_s2': $sql = "SELECT ROUND(kvan*1000,3) AS kvan FROM eqtransformador WHERE instalacao='".$idUT."' AND faseligsec='B' "; break;
		case 'reg_s3': $sql = "SELECT ROUND(kvan*1000,3) AS kvan FROM eqtransformador WHERE instalacao='".$idUT."' AND faseligsec='C' "; break;
	}
	
	/*$stid = oci_parse(connectSGD(), $sql);
	oci_execute($stid);
	$row = oci_fetch_assoc($stid);*/
	//echo "sql1: ".$sql;
	
	if ($result = $mysqliSGD->query($sql)) { 
		$row = $result->fetch_object();		
    }
	
	$fields=array();
	array_push($fields,array("name" => "reg_date_time","header"=>"FECHA : HORA"),array("name" => "reg_semana","header"=>"Semana"),array("name" => "reg_s","header"=>"KVA")); 
	
	switch($nfase) {
		case 2: $sql = "SELECT DATE_FORMAT(reg_date_time, '%d/%m/%y %T') as reg_date_time, DATE_FORMAT(reg_date_time, '%w') as reg_semana,  ".$reg_s." as reg_s FROM tm_reg_registro2f WHERE cro_id = ".$root; break;
		case 3: $sql = "SELECT DATE_FORMAT(reg_date_time, '%d/%m/%y %T') as reg_date_time, DATE_FORMAT(reg_date_time, '%w') as reg_semana, ".$reg_s." as reg_s FROM tm_reg_registro3f WHERE cro_id = ".$root; break;
	}	
	
	switch($statsUE)
	{
		case 'SC': $sql.= " AND ".$reg_s." > (".$row->kvan.")"; break; //sobre cargado
		case 'RG': $sql.= " AND ".$reg_s." BETWEEN (".($row->kvan*0.4).") and (".$row->kvan.")"; break; //normal o regular
		case 'SU': $sql.= " AND ".$reg_s." < (".($row->kvan*0.4).")"; break; //sub utilizado
	}
	
	$mysqli = newMySQLi();
	if (mysqli_connect_errno()) {} // chequeo de coneccion
	//echo "sql2: ".$sql;
	if ($result = $mysqli->query($sql)) { //validar el resultado!
		$rowNum = 0;
		while($row = $result->fetch_object()) {
			$data[$rowNum]['reg_date_time']   = $row->reg_date_time;
			$data[$rowNum]['reg_semana']   = intToWeek($row->reg_semana);
			$data[$rowNum]['reg_s'] = round($row->reg_s/1000, 3, PHP_ROUND_HALF_UP);
			$rowNum++;
		} 
		$result->close(); // Free result  
	}
	if(!isset($data)) $data = array();
	
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
		'data'		=> array_splice($data,$start,$limit)
	);
	
	//echo $sql;
	echo json_encode($paging);
	//print_r($paging);

?>