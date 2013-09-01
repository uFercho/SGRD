<?php
	header("Content-Type: text/plain"); 
	
	include_once("../../getFunction.php");
	
	$idUT = $_POST['idUT'];
	//$root = 604;
	
	$fields= array(
		array("name" => "placa","header"=>"Placa"),
		array("name" => "kvat","header"=>"S Total"),
		array("name" => "kva1","header"=>"S Total F1"),
		array("name" => "kva2","header"=>"S Total F2"),
		array("name" => "kva3","header"=>"S Total F3"),
		array("name" => "metros_rs","header"=>"Red BT"),		
		array("name" => "factor_cg","header"=>"Factor de Carga"),
		array("name" => "total_cli","header"=>"Total clientes")
	);
	
	$mysqliSGD	= newMySQLiSGD();
	if (mysqli_connect_errno()) {exit();}
	
	$sql ="SELECT instalacao AS placa, kvan AS kvat, metros_rs, ROUND(fatorcarga,3) AS factor_cg, cli_total_operacao AS total_cli ";
	$sql.="FROM utransformadora ";
	$sql.="WHERE instalacao='".$idUT."'";
	
	if ($result = $mysqliSGD->query($sql)) { 
		$row = $result->fetch_object();		
		$data[0]['placa']     = $row->placa;
		$data[0]['kvat']      = $row->kvat;
		$data[0]['metros_rs'] = $row->metros_rs;
		$data[0]['factor_cg'] = $row->factor_cg;
		$data[0]['total_cli'] = $row->total_cli;
    }
	
	$result->close(); 
    unset($obj); 
    unset($sql);
	
	/*$stid = oci_parse(connectSGD(), $sql);
	oci_execute($stid);
	
	while ($row = oci_fetch_assoc($stid)) { 
		$data[0]['placa']     = $row["PLACA"];
		$data[0]['kvat']      = $row["KVAT"];
		$data[0]['metros_rs'] = $row["METROS_RS"];
		$data[0]['factor_cg'] = $row["FACTOR_CG"];
		$data[0]['total_cli'] = $row["TOTAL_CLI"];
	}
	
	oci_free_statement($stid);*/
	
	$sql ="SELECT kvan ";
	$sql.="FROM eqtransformador ";
	$sql.="WHERE instalacao='".$idUT."'";
	
	if ($result = $mysqliSGD->query($sql)) {
		$rowNum = 0;
		while($row = $result->fetch_object()) {
			$kvaEq[$rowNum] = $row->kvan;
			$rowNum++;
		}
    }
	
	/*$stid = oci_parse(connectSGD(), $sql);
	oci_execute($stid);
	
	$kvaEq = array();
	while ($row = oci_fetch_assoc($stid)) { 
		$kvaEq[] = $row["KVAN"];		
	}*/
	
	$data[0]['kva1'] = isset($kvaEq[0])?$kvaEq[0]:0;
	$data[0]['kva2'] = isset($kvaEq[1])?$kvaEq[1]:0;
	$data[0]['kva3'] = isset($kvaEq[2])?$kvaEq[2]:0;
	
	/*oci_free_statement($stid);*/
	
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