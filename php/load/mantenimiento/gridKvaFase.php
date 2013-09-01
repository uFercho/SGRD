<?php
	header("Content-Type: text/plain"); 
	
	include_once("../../getFunction.php");
	
	$idUT = $_POST['idUT'];
	//$root = 607;
	
	$fields= array(
		array("name" => "kva","header"=>""),
		array("name" => "fase_a","header"=>"Fase A"),
		array("name" => "fase_b","header"=>"Fase B"),
		array("name" => "fase_c","header"=>"Fase C"),
		array("name" => "total","header"=>"Total")
	);
	
	$mysqliSGD	= newMySQLiSGD();
	if (mysqli_connect_errno()) {exit();}
		
	$sql="SELECT 'KVA' AS kva, ROUND(SUM(kva_bt_a+kva_at_a+kva_ip_a), 3) AS fase_a, ROUND(SUM(kva_bt_b+kva_at_b+kva_ip_b), 3) AS fase_b, ROUND(SUM(kva_bt_c+kva_at_c+kva_ip_c), 3) AS fase_c, ROUND(SUM(kva_bt_a+kva_at_a+kva_ip_a+kva_bt_b+kva_at_b+kva_ip_b+kva_bt_c+kva_at_c+kva_ip_c), 3) AS total ";
	$sql.="FROM utransformadora ";
	$sql.="WHERE instalacao='".$idUT."' ";
	
	if ($result = $mysqliSGD->query($sql)) {
		$rowNum = 0;
		while($row = $result->fetch_object()) {
			$data[$rowNum]['kva']    = $row->kva;
			$data[$rowNum]['fase_a'] = $row->fase_a;
			$data[$rowNum]['fase_b'] = $row->fase_b;
			$data[$rowNum]['fase_c'] = $row->fase_c;
			$data[$rowNum]['total']  = $row->total;
			$rowNum++;
		}
    }
	
	/*$stid = oci_parse(connectSGD(), $sql);
	oci_execute($stid);
	
	$rowNum = 0;
	while ($row = oci_fetch_assoc($stid)) { 
		$data[$rowNum]['kva']    = $row["KVA"];
		$data[$rowNum]['fase_a'] = $row["FASE_A"];
		$data[$rowNum]['fase_b'] = $row["FASE_B"];
		$data[$rowNum]['fase_c'] = $row["FASE_C"];
		$data[$rowNum]['total']  = $row["TOTAL"];
		$rowNum++;
	}*/
	
	
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