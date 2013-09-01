<?php
	header("Content-Type: text/plain"); 
	
	include_once("../../getFunction.php");
	
	$start = isset($_POST['start'])?$_POST['start']:0; //posición a iniciar
	$limit = isset($_POST['limit'])?$_POST['limit']:25; //número de registros a mostrar
	$root = $_POST['root'];
	$filter = isset($_POST['filter'])?$_POST['filter']:'todos';
	$nfase = $_POST['numFase'];
	$voltn = isset($_POST['voltn'])?$_POST['voltn']:120;
	$por_v = isset($_POST['por_v'])?$_POST['por_v']:10;
	
	connectMySQL();
	
	$fields=array();
	$fields[] = array("name" => "reg_date_time","header"=>"FECHA : HORA");
	$fields[] = array("name" => "reg_semana","header"=>"Semana");
	switch($nfase) {
		case 2: 
			array_push($fields,array("name" => "reg_v1","header"=>"V1 [V]"),array("name" => "reg_v2","header"=>"V2 [V]")); 
			$sql = "SELECT DATE_FORMAT(reg_date_time, '%d/%m/%y %T') as reg_date_time, DATE_FORMAT(reg_date_time, '%w') as reg_semana, reg_v1, reg_v2, reg_vp FROM tm_reg_registro2f ";
			break;
		case 3: 
			array_push($fields,array("name" => "reg_v1","header"=>"V1 [V]"),array("name" => "reg_v2","header"=>"V2 [V]"),array("name" => "reg_v3","header"=>"V3 [V]")); 
			$sql = "SELECT DATE_FORMAT(reg_date_time, '%d/%m/%y %T') as reg_date_time, DATE_FORMAT(reg_date_time, '%w') as reg_semana, reg_v1, reg_v2, reg_v3, reg_vp FROM tm_reg_registro3f ";
			break;
	}		
	$fields[] = array("name" => "reg_vp","header"=>"Vp [V]");
	
	switch($filter)
	{
		case 'no_validos':
			$sql.= "WHERE cro_id = ".$root." AND reg_vp <=> NULL "; //reg no_validos
			break;
		case 'validos': 
			$sql.= "WHERE cro_id = ".$root." AND NOT(reg_vp <=> NULL) "; //reg validos
			break;
		case 'reg_pen_bajo': 
			$sql.= "WHERE cro_id = ".$root." AND NOT(reg_vp <=> NULL) AND reg_vp < ".($voltn*((100-$por_v)/100)); //reg_pen_bajo
			break;
		case 'reg_pen_alto': 
			$sql.= "WHERE cro_id = ".$root." AND NOT(reg_vp <=> NULL) AND reg_vp > ".($voltn*((100+$por_v)/100)); //reg_pen_alto
			break;
		case 'reg_penalizados': 
			$sql.= "WHERE cro_id = ".$root." AND NOT(reg_vp <=> NULL) AND reg_vp NOT BETWEEN ".($voltn*((100-$por_v)/100))." AND ".($voltn*((100+$por_v)/100)); //reg_penalizados
			break;
		case 'todos': 
			$sql.= "WHERE cro_id = ".$root; //todos
			break;
		default:
			$sql.= "WHERE cro_id = ".$root; //todos
	}
	
	$result =  mysql_query($sql);
	
	$rowNum = 0;
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$data[$rowNum]['reg_date_time'] = $row['reg_date_time'];
		$data[$rowNum]['reg_semana']   	= intToWeek($row['reg_semana']);
		$data[$rowNum]['reg_v1'] = $row['reg_v1'];//round($row['reg_v1'], 3, PHP_ROUND_HALF_UP);
		if($nfase!=1) $data[$rowNum]['reg_v2'] = $row['reg_v2'];//round($row['reg_v2'], 3, PHP_ROUND_HALF_UP);
		if($nfase==3) $data[$rowNum]['reg_v3'] = $row['reg_v3'];//round($row['reg_v3'], 3, PHP_ROUND_HALF_UP);
		$data[$rowNum]['reg_vp'] = ''.round($row['reg_vp'], 3, PHP_ROUND_HALF_UP);
		$rowNum++;
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