<?php
	header("Content-Type: text/plain"); 
	
	include_once("../getFunction.php");
	
	$start = isset($_POST['start'])?$_POST['start']:0; //posición a iniciar
	$limit = isset($_POST['limit'])?$_POST['limit']:25; //número de registros a mostrar
	$add = $_POST['records'];
	
	$mysqli = newMySQLi();
	if (mysqli_connect_errno()) {} // chequeo de coneccion

	if(isset($add)){ //if there are records to insert/update
		$records = json_decode(stripslashes($add)); //parse the string to PHP objects
		$ids = array();
		foreach($records as $record){
			if(isset($record->newRecordId)){ //records to insert
				/*$id = count($data['data']);
				$info = array(
					'id'=> id,
					'name'=> $record->name,
					'age'=> $record->age,
					'country'=> $record->country
				);

				array_push($data['data'],$info); //add the new record to session
				array_push($ids,array('oldId'=>$record->newRecordId,'id'=>$id));//new id*/
			}else{ //records to update
				$sql = "UPDATE tm_sce_sobrecarga_equipo SET  sce_remplazado =  1 WHERE sce_id = '".$record->sce_id."'";
				$mysqli->query($sql);
			}
		}
	}
	
	$sql = "SELECT 
					sce_id,
					DATE_FORMAT(sce_fecha, '%d/%m/%y') as sce_fecha,  
  					sce_sobrecarga, 
					(sce_avg/sce_max)*100 AS sce_fc,      
					(sce_max/sce_kvan) AS sce_fu,
					sce_patrimonio,  
					sce_num_sc, 
					sce_horas_sc,
					sce_num_su, 
					sce_horas_su, 
					scu_placa,  
					sce_max/1000 AS sce_max,  
					sce_avg/1000 AS sce_avg, 
					sce_min/1000 AS sce_min,
					sce_kvan/1000 AS sce_kvan, 
					sce_remplazado 
			FROM 
					tm_sce_sobrecarga_equipo
			WHERE
					sce_remplazado = 0 AND sce_sobrecarga != 0"; 
	
	//echo $sql;
	if ($result = $mysqli->query($sql)) { //validar el resultado!
		$rowNum = 0;
		while($row = $result->fetch_object()) {
			$data[$rowNum]['sce_id']   		 = $row->sce_id;
			$data[$rowNum]['sce_fecha']   	 = $row->sce_fecha;
			$data[$rowNum]['sce_sobrecarga'] = msj_sc($row->sce_sobrecarga);
			$data[$rowNum]['sce_fc']   		 = round($row->sce_fc, 3, PHP_ROUND_HALF_UP);
			$data[$rowNum]['sce_fu']   		 = round($row->sce_fu, 3, PHP_ROUND_HALF_UP);
			$data[$rowNum]['sce_patrimonio'] = $row->sce_patrimonio;
			$data[$rowNum]['sce_num_sc']   	 = $row->sce_num_sc;
			$data[$rowNum]['sce_horas_sc']   = round($row->sce_horas_sc, 3, PHP_ROUND_HALF_UP);
			$data[$rowNum]['sce_num_su']   	 = $row->sce_num_su;
			$data[$rowNum]['sce_horas_su']   = round($row->sce_horas_su, 3, PHP_ROUND_HALF_UP);
			$data[$rowNum]['scu_placa']   	 = $row->scu_placa;
			$data[$rowNum]['sce_max']   	 = round($row->sce_max, 3, PHP_ROUND_HALF_UP);
			$data[$rowNum]['sce_avg']   	 = round($row->sce_avg, 3, PHP_ROUND_HALF_UP);
			$data[$rowNum]['sce_min']   	 = round($row->sce_min, 3, PHP_ROUND_HALF_UP);
			$data[$rowNum]['sce_kvan']   	 = round($row->sce_kvan, 3, PHP_ROUND_HALF_UP);
			$data[$rowNum]['sce_remplazado'] = $row->sce_remplazado==0?'No':'Si';
			$rowNum++;
		} 
		$result->close(); // Free result  
	}
	
	if(!isset($data)) $data = array();
	
	$fields= array(
		array("name" => "sce_id","header"=>"id"),
		array("name" => "scu_placa","header"=>"Placa"),
		array("name" => "sce_fecha","header"=>"Fecha"),
		array("name" => "sce_sobrecarga","header"=>"Estado"),
		array("name" => "sce_fc","header"=>"FC"),
		array("name" => "sce_fu","header"=>"FU"),
		array("name" => "sce_patrimonio","header"=>"Patrimonio"),
		array("name" => "sce_num_sc","header"=>"Reg_sc"),
		array("name" => "sce_horas_sc","header"=>"Hora_sc"),
		array("name" => "sce_num_su","header"=>"Reg_su"),
		array("name" => "sce_horas_su","header"=>"Hora_su"),
		array("name" => "scu_placa","header"=>"Placa"),
		array("name" => "sce_max","header"=>"MAX"),
		array("name" => "sce_avg","header"=>"PRO"),
		array("name" => "sce_min","header"=>"MIN"),
		array("name" => "sce_kvan","header"=>"KVAN"),
		array("name" => "sce_remplazado","header"=>"Listo")
	);
	
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
	
	function msj_sc($caso){
		switch($caso) {
			case 1:  return 'SC > 179% [1H]'; break;
			case 2:  return '154% < SC <= 179% [2H]'; break;
			case 4:  return '133% < SC <= 154% [4H]'; break;
			case 8:  return '117% < SC <= 133% [8H]'; break;
			case 24: return '101% < SC <= 117% [24H]'; break;
		}		
	}

?>