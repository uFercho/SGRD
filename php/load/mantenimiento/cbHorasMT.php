<?php
	header("Content-Type: text/plain"); 
	
	include_once("../../getFunction.php");
	
	$depenH = isset($_POST['depenH'])?$_POST['depenH']:2;
	$restoH = isset($_POST['restoH'])?$_POST['restoH']:0;
	$totalH = 24;
	$maxH = 14;
	
	//$root = 604;
	
	$fields= array(
		array("name" => "name", "type"=>"string"),
		array("name" => "value","type"=>"int")
	);
	
	$data = getArrayFields($totalH,$maxH,$depenH,$restoH);
	
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
	
	function getArrayFields($totalH,$maxH,$depenH,$restoH) // Convierte una fecha en formato DateTime
	{
		$data=array();
		if($totalH-$restoH-$depenH > $maxH){
			for ($i = 1; $i <= $maxH; $i++) { $data[] = array("name" => $i." H", "value" => $i); }
		}else{
			for ($i = 1; $i <= $totalH-$restoH-$depenH; $i++) { $data[] = array("name" => $i." H", "value" => $i); }
		}
		return $data;
	}
?>