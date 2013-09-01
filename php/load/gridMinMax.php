<?php
	header("Content-Type: text/plain"); 
	
	include_once("../getFunction.php");
	
	$root = $_POST['root'];
	$numFase = $_POST['numFase'];
	
	$fields = getArrayFields($numFase);
	
	connectMySQL();
	
	switch($numFase) {
		case '1': 
			$sql = "SELECT 'MAX', MAX(reg_v1) as MaxV1,MAX(reg_vp) as MaxVp,MAX(reg_i1) as MaxI1,MAX(reg_s1) as MaxS1,MAX(reg_st) as MaxST,MAX(reg_fp1) as MaxFp1,MAX(reg_fpt) MaxFpt,MAX(reg_wt) as MaxWt FROM tm_reg_registro1f WHERE cro_id = '".$root."' UNION ALL ";
			$sql.= "SELECT 'PRO', AVG(reg_v1) as AvgV1,AVG(reg_vp) as AvgVp,AVG(reg_i1) as AvgI1,AVG(reg_s1) as AvgS1,AVG(reg_st) as AvgST,AVG(reg_fp1) as AvgFp1,AVG(reg_fpt) AvgFpt,AVG(reg_wt) as AvgWt FROM tm_reg_registro1f WHERE cro_id = '".$root."' UNION ALL ";
			$sql.= "SELECT 'MIN', MIN(reg_v1) as MinV1,MIN(reg_vp) as MinVp,MIN(reg_i1) as MinI1,MIN(reg_s1) as MinS1,MIN(reg_st) as MinST,MIN(reg_fp1) as MinFp1,MIN(reg_fpt) MinFpt,MIN(reg_wt) as MinWt FROM tm_reg_registro1f WHERE cro_id = '".$root."'";
			break;
		case '2': 
			$sql = "SELECT 'MAX', MAX(reg_v1) as MaxV1,MAX(reg_v2) as MaxV2,MAX(reg_vp) as MaxVp,MAX(reg_i1) as MaxI1,MAX(reg_i2) as MaxI2,MAX(reg_s1) as MaxS1,MAX(reg_s2) as MaxS2,MAX(reg_st) as MaxST,MAX(reg_fp1) as MaxFp1,MAX(reg_fp2) as MaxFp2,MAX(reg_fpt) MaxFpt,MAX(reg_wt) as MaxWt FROM tm_reg_registro2f WHERE cro_id = '".$root."' UNION ALL ";
			$sql.= "SELECT 'PRO', AVG(reg_v1) as AvgV1,AVG(reg_v2) as AvgV2,AVG(reg_vp) as AvgVp,AVG(reg_i1) as AvgI1,AVG(reg_i2) as AvgI2,AVG(reg_s1) as AvgS1,AVG(reg_s2) as AvgS2,AVG(reg_st) as AvgST,AVG(reg_fp1) as AvgFp1,AVG(reg_fp2) as AvgFp2,AVG(reg_fpt) AvgFpt,AVG(reg_wt) as AvgWt FROM tm_reg_registro2f WHERE cro_id = '".$root."' UNION ALL ";
			$sql.= "SELECT 'MIN', MIN(reg_v1) as MinV1,MIN(reg_v2) as MinV2,MIN(reg_vp) as MinVp,MIN(reg_i1) as MinI1,MIN(reg_i2) as MinI2,MIN(reg_s1) as MinS1,MIN(reg_s2) as MinS2,MIN(reg_st) as MinST,MIN(reg_fp1) as MinFp1,MIN(reg_fp2) as MinFp2,MIN(reg_fpt) MinFpt,MIN(reg_wt) as MinWt FROM tm_reg_registro2f WHERE cro_id = '".$root."'";
			break;
		case '3': 
			$sql = "SELECT 'MAX', MAX(reg_v1) as MaxV1,MAX(reg_v2) as MaxV2,MAX(reg_v3) as MaxV3,MAX(reg_vp) as MaxVp,MAX(reg_i1) as MaxI1,MAX(reg_i2) as MaxI2,MAX(reg_i3) as MaxI3,MAX(reg_s1) as MaxS1,MAX(reg_s2) as MaxS2,MAX(reg_s3) as MaxS3,MAX(reg_st) as MaxST,MAX(reg_fp1) as MaxFp1,MAX(reg_fp2) as MaxFp2,MAX(reg_fp3) as MaxFp3,MAX(reg_fpt) MaxFpt,MAX(reg_wt) as MaxWt FROM tm_reg_registro3f WHERE cro_id = '".$root."' UNION ALL ";
			$sql.= "SELECT 'PRO', AVG(reg_v1) as AvgV1,AVG(reg_v2) as AvgV2,AVG(reg_v3) as AvgV3,AVG(reg_vp) as AvgVp,AVG(reg_i1) as AvgI1,AVG(reg_i2) as AvgI2,AVG(reg_i3) as AvgI3,AVG(reg_s1) as AvgS1,AVG(reg_s2) as AvgS2,AVG(reg_s3) as AvgS3,AVG(reg_st) as AvgST,AVG(reg_fp1) as AvgFp1,AVG(reg_fp2) as AvgFp2,AVG(reg_fp3) as AvgFp3,AVG(reg_fpt) AvgFpt,AVG(reg_wt) as AvgWt FROM tm_reg_registro3f WHERE cro_id = '".$root."' UNION ALL ";
			$sql.= "SELECT 'MIN', MIN(reg_v1) as MinV1,MIN(reg_v2) as MinV2,MIN(reg_v3) as MinV3,MIN(reg_vp) as MinVp,MIN(reg_i1) as MinI1,MIN(reg_i2) as MinI2,MIN(reg_i3) as MinI3,MIN(reg_s1) as MinS1,MIN(reg_s2) as MinS2,MIN(reg_s3) as MinS3,MIN(reg_st) as MinST,MIN(reg_fp1) as MinFp1,MIN(reg_fp2) as MinFp2,MIN(reg_fp3) as MinFp3,MIN(reg_fpt) MinFpt,MIN(reg_wt) as MinWt FROM tm_reg_registro3f WHERE cro_id = '".$root."'";
			break;
	}
	
	$result =  mysql_query($sql);
	
	$rowNum = 0;
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$data[$rowNum]['DATA'] = $row['MAX'];
		
		$data[$rowNum]['V1'] = round($row['MaxV1'], 3, PHP_ROUND_HALF_UP);
		if (($numFase=='2')||($numFase=='3')) $data[$rowNum]['V2'] = round($row['MaxV2'], 3, PHP_ROUND_HALF_UP);
		if ($numFase=='3') $data[$rowNum]['V3'] = round($row['MaxV3'], 3, PHP_ROUND_HALF_UP);
		$data[$rowNum]['Vp'] = round($row['MaxVp'], 3, PHP_ROUND_HALF_UP);
		
		$data[$rowNum]['I5'] = round($row['MaxI1'], 3, PHP_ROUND_HALF_UP);
		if (($numFase=='2')||($numFase=='3')) $data[$rowNum]['I6'] = round($row['MaxI2'], 3, PHP_ROUND_HALF_UP);
		if ($numFase=='3') $data[$rowNum]['I7'] = round($row['MaxI3'], 3, PHP_ROUND_HALF_UP);
		
		$data[$rowNum]['S1-5'] = round($row['MaxS1'], 3, PHP_ROUND_HALF_UP);
		if (($numFase=='2')||($numFase=='3')) $data[$rowNum]['S2-6'] = round($row['MaxS2'], 3, PHP_ROUND_HALF_UP);
		if ($numFase=='3') $data[$rowNum]['S3-7'] = round($row['MaxS3'], 3, PHP_ROUND_HALF_UP);
		$data[$rowNum]['S1-7'] = round($row['MaxST'], 3, PHP_ROUND_HALF_UP);
		
		$data[$rowNum]['PF1-5'] = round($row['MaxFp1'], 3, PHP_ROUND_HALF_UP);
		if (($numFase=='2')||($numFase=='3')) $data[$rowNum]['PF2-6'] = round($row['MaxFp2'], 3, PHP_ROUND_HALF_UP);
		if ($numFase=='3') $data[$rowNum]['PF3-7'] = round($row['MaxFp3'], 3, PHP_ROUND_HALF_UP);
		$data[$rowNum]['PF1-7'] = round($row['MaxFpt'], 3, PHP_ROUND_HALF_UP);
		
		$data[$rowNum]['WP1-7'] = round($row['MaxWt'], 3, PHP_ROUND_HALF_UP);
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
		'data'		=> array_splice($data,0,count($data))
	);
	
	echo json_encode($paging);
	//print_r($paging);
	
	function getArrayFields($nFases) // Convierte una fecha en formato DateTime
	{
		switch($nFases) {
			case '1':
				$fields=array(
					array("name" => "DATA","header" => ""),
					array("name" => "V1","header" => "V1 [V]"),
					array("name" => "Vp","header" => "Vp [V]"),
					array("name" => "I5","header" => "I1 [A]"),
					array("name" => "S1-5","header" => "S1 [VA]"),
					array("name" => "S1-7","header" => "ST [VA]"),
					array("name" => "PF1-5","header" => "PF1"),
					array("name" => "PF1-7","header" => "PFT"),
					array("name" => "WP1-7","header" => "WT [Wh]")
				);
				break;
			case '2': 
				$fields=array(
					array("name" => "DATA","header" => ""),
					array("name" => "V1","header" => "V1 [V]"),
					array("name" => "V2","header" => "V2 [V]"),
					array("name" => "Vp","header" => "Vp [V]"),
					array("name" => "I5","header" => "I1 [A]"),
					array("name" => "I6","header" => "I2 [A]"),
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
					array("name" => "DATA","header" => ""),
					array("name" => "V1","header" => "V1 [V]"),
					array("name" => "V2","header" => "V2 [V]"),
					array("name" => "V3","header" => "V3 [V]"),
					array("name" => "Vp","header" => "Vp [V]"),
					array("name" => "I5","header" => "I1 [A]"),
					array("name" => "I6","header" => "I2 [A]"),
					array("name" => "I7","header" => "I3 [A]"),
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