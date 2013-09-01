<?php
	header("Content-Type: text/plain"); 
	
	include_once("../../getFunction.php");
	
	$idUT = $_POST['idUT'];
	
	$fields= array(
		array("name" => "fases","header"=>"Fases"),
		array("name" => "num_usu","header"=>"Usuarios"),
		array("name" => "kva","header"=>"KVA")
	);
	
	$mysqliSGD	= newMySQLiSGD();
	if (mysqli_connect_errno()) {exit();}
	
	$sql="SELECT fases, COUNT(*) AS num_usu, ROUND(SUM(kva), 3) AS kva ";
	$sql.="FROM consumidor ";
	$sql.="WHERE instalacao='".$idUT."' ";
	$sql.="GROUP BY fases";
	
	if ($result = $mysqliSGD->query($sql)) {
		$rowNum = 0;
		while($row = $result->fetch_object()) {
			$data[$rowNum]['fases']   = $row->fases;
			$data[$rowNum]['num_usu'] = $row->num_usu;
			$data[$rowNum]['kva']     = round(/*(float)*/$row->kva, 3, PHP_ROUND_HALF_UP);
			$rowNum++;
		}
    }
	
	/*$stid = oci_parse(connectSGD(), $sql);
	oci_execute($stid);
	
	$rowNum = 0;
	while ($row = oci_fetch_assoc($stid)) { 
		$data[$rowNum]['fases']   = $row["FASES"];
		$data[$rowNum]['num_usu'] = $row["NUM_USU"];
		$data[$rowNum]['kva']     = round((float)$row["KVA"], 3, PHP_ROUND_HALF_UP);
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