<?php
	header("Content-Type: text/plain"); 
	
	include_once("../getFunction.php");

	$start = isset($_POST['start'])?$_POST['start']:0; //posición a iniciar
	$limit = isset($_POST['limit'])?$_POST['limit']:25; //número de registros a mostrar
	$root = $_POST['root'];
	$numFase = $_POST['numFase'];
	
	$fields = getArrayFields($numFase);
	
	connectMySQL();
	
	switch($numFase) {
		case '2': $sql = "SELECT DATE_FORMAT(reg_date_time, '%d/%m/%y %H:%i') as reg_date_time,reg_v1,reg_v2,reg_vp,reg_i1,reg_i2,reg_dv,reg_s1,reg_s2,reg_st,reg_fp1,reg_fp2,reg_fpt,reg_wt FROM tm_reg_registro2f WHERE cro_id = '".$root."'"; break;	
		case '3': $sql = "SELECT DATE_FORMAT(reg_date_time, '%d/%m/%y %H:%i') as reg_date_time,reg_v1,reg_v2,reg_v3,reg_vp,reg_i1,reg_i2,reg_i3,reg_dv,reg_s1,reg_s2,reg_s3,reg_st,reg_fp1,reg_fp2,reg_fp3,reg_fpt,reg_wt FROM tm_reg_registro3f WHERE cro_id = '".$root."'"; break;
	}
	
	$result =  mysql_query($sql);
	
	$rowNum = 0;
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    	$data[$rowNum]['date'] = $row['reg_date_time'];
		
		$data[$rowNum]['V1'] = $row['reg_v1'];
		if (($numFase=='2')||($numFase=='3')) $data[$rowNum]['V2'] = $row['reg_v2'];
		if ($numFase=='3') $data[$rowNum]['V3'] = $row['reg_v3'];
		$data[$rowNum]['Vp'] = $row['reg_vp'] == NULL ? NULL : round($row['reg_vp'], 3, PHP_ROUND_HALF_UP);
		
		$data[$rowNum]['I5'] = $row['reg_i1'];
		if (($numFase=='2')||($numFase=='3')) $data[$rowNum]['I6'] = $row['reg_i2'];
		if ($numFase=='3') $data[$rowNum]['I7'] = $row['reg_i3'];
		$data[$rowNum]['Desb'] = round($row['reg_dv'], 3, PHP_ROUND_HALF_UP);
		
		$data[$rowNum]['S1-5'] = $row['reg_s1'];
		if (($numFase=='2')||($numFase=='3')) $data[$rowNum]['S2-6'] = $row['reg_s2'];
		if ($numFase=='3') $data[$rowNum]['S3-7'] = $row['reg_s3'];
		$data[$rowNum]['S1-7'] = $row['reg_st'];
		
		$data[$rowNum]['PF1-5'] = $row['reg_fp1'];
		if (($numFase=='2')||($numFase=='3')) $data[$rowNum]['PF2-6'] = $row['reg_fp2'];
		if ($numFase=='3') $data[$rowNum]['PF3-7'] = $row['reg_fp3'];
		$data[$rowNum]['PF1-7'] = $row['reg_fpt'];
		
		$data[$rowNum]['WP1-7'] = $row['reg_wt'];
		$rowNum++;
	}
	
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
	
	echo json_encode($paging);
	//print_r($paging);
	
	
	
	function getArrayFields($nFases) // Convierte una fecha en formato DateTime
	{
		switch($nFases) {
			case '2': 
				$fields=array(
					array("name" => "date","header" => "Fecha y Hora"),
					array("name" => "V1","header" => "V1 [V]"),
					array("name" => "V2","header" => "V2 [V]"),
					array("name" => "Vp","header" => "Vp [V]"),
					array("name" => "I5","header" => "I1 [A]"),
					array("name" => "I6","header" => "I2 [A]"),
					array("name" => "Desb","header" => "Desb_I [%]"),
					array("name" => "S1-5","header" => "S1 [VA]"),
					array("name" => "S2-6","header" => "S2 [VA]"),
					array("name" => "S1-7","header" => "ST [VA]"),
					array("name" => "PF1-5","header" => "PF1"),
					array("name" => "PF2-6","header" => "PF2"),
					array("name" => "PF1-7","header" => "PFT"),
					array("name" => "WP1-7","header" => "WT [Wh]")
				);
				break;	
			case '3':
				$fields=array(
					array("name" => "date","header" => "Fecha y Hora"),
					array("name" => "V1","header" => "V1 [V]"),
					array("name" => "V2","header" => "V2 [V]"),
					array("name" => "V3","header" => "V3 [V]"),
					array("name" => "Vp","header" => "Vp [V]"),
					array("name" => "I5","header" => "I1 [A]"),
					array("name" => "I6","header" => "I2 [A]"),
					array("name" => "I7","header" => "I3 [A]"),
					array("name" => "Desb","header" => "Desb_I [%]"),
					array("name" => "S1-5","header" => "S1 [VA]"),
					array("name" => "S2-6","header" => "S2 [VA]"),
					array("name" => "S3-7","header" => "S3 [VA]"),
					array("name" => "S1-7","header" => "ST [VA]"),
					array("name" => "PF1-5","header" => "PF1"),
					array("name" => "PF2-6","header" => "PF2"),
					array("name" => "PF3-7","header" => "PF3"),
					array("name" => "PF1-7","header" => "PFT"),
					array("name" => "WP1-7","header" => "WT [Wh]")
				);
				break;
		}
		return $fields;
	}

?>