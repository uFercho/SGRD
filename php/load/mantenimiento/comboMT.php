<?php
	header("Content-Type: text/plain"); 
	
	include_once("../../getFunction.php");
	
	$numEquip = isset($_POST['numEquip'])?$_POST['numEquip']:1;
	//$root = 604;
	
	$fields= array(
		array("name" => "name","type"=>"string"),
		array("name" => "desc","type"=>"string")
	);
	
	$data = getArrayFields($numEquip);
	
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
	
	function getArrayFields($numEquip) // Convierte una fecha en formato DateTime
	{
		switch($numEquip) {
			case '1':
				$data = array(  
					array("name"=>"ST","desc"=>"reg_st")
    			); 
				break;	
			case '3':
				$data = array(  
					array("name"=>"ST","desc"=>"reg_st"),  
					array("name"=>"S1","desc"=>"reg_s1"),  
					array("name"=>"S2","desc"=>"reg_s2"),   
					array("name"=>"S3","desc"=>"reg_s3")  
    			); 
				break;
		}
		return $data;
	}
	
?>