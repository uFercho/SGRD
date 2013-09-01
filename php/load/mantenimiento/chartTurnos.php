<?php
	header("Content-Type: text/plain"); 
	
	include_once("../../getFunction.php");
	
	$root = isset($_POST['root'])?$_POST['root']:247;
	$idUT = isset($_POST['idUT'])?$_POST['idUT']:'CC02125';
	$nfase = isset($_POST['numFase'])?$_POST['numFase']:2;
	$reg_s = isset($_POST['reg_s'])?$_POST['reg_s']:'reg_st';
	$statsUE = isset($_POST['statsUE'])?$_POST['statsUE']:'SC';
	$h1 = isset($_POST['h1'])?$_POST['h1']:'07';
	$h2 = isset($_POST['h2'])?$_POST['h2']:'15';
	$h3 = isset($_POST['h3'])?$_POST['h3']:'23';
	
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
	
	if ($result = $mysqliSGD->query($sql)) { 
		$row = $result->fetch_object();		
    }
	
	if ((int)$h1 < (int)$h2) {
    	if ((int)$h1 < (int)$h3) // h1 es el menor
			$orden = "IF(TIME(reg_date_time) BETWEEN '".$h1.":00:00' AND TIME(SUBTIME('2010-10-10 ".$h2.":00:00','0:0:1')),'1er Turno',IF(TIME(reg_date_time) BETWEEN '".$h2.":00:00' AND TIME(SUBTIME('2010-10-10 ".$h3.":00:00','0:0:1')),'2do Turno','3er Turno'))";
		else // h3 es el menor
			$orden = "IF(TIME(reg_date_time) BETWEEN '".$h3.":00:00' AND TIME(SUBTIME('2010-10-10 ".$h1.":00:00','0:0:1')),'3er Turno',IF(TIME(reg_date_time) BETWEEN '".$h1.":00:00' AND TIME(SUBTIME('2010-10-10 ".$h2.":00:00','0:0:1')),'1er Turno','2do Turno'))";
	} elseif ((int)$h2 < (int)$h3) { // h2 es el menor
		$orden = "IF(TIME(reg_date_time) BETWEEN '".$h2.":00:00' AND TIME(SUBTIME('2010-10-10 ".$h3.":00:00','0:0:1')),'2do Turno',IF(TIME(reg_date_time) BETWEEN '".$h3.":00:00' AND TIME(SUBTIME('2010-10-10 ".$h1.":00:00','0:0:1')),'3er Turno','1er Turno'))";
	} else 
		$orden = "IF(TIME(reg_date_time) BETWEEN '".$h3.":00:00' AND TIME(SUBTIME('2010-10-10 ".$h1.":00:00','0:0:1')),'3er Turno',IF(TIME(reg_date_time) BETWEEN '".$h1.":00:00' AND TIME(SUBTIME('2010-10-10 ".$h2.":00:00','0:0:1')),'1er Turno','2do Turno'))";
		
	switch($nfase) {
		case 1: $tabla = "tm_reg_registro1f"; break;
		case 2: $tabla = "tm_reg_registro2f"; break;
		case 3: $tabla = "tm_reg_registro3f"; break;
	}
	
	$sql = "SELECT 
				".$orden." AS turno, 
				COUNT(*) AS reg_s
			FROM 
				".$tabla." 
			WHERE 
				cro_id = ".$root;
				
	switch($statsUE) {
		case 'SC': $sql.= " AND ".$reg_s." > (".$row->kvan.")"; break; //sobre cargado
		case 'RG': $sql.= " AND ".$reg_s." BETWEEN (".($row->kvan*0.4).") and (".$row->kvan.")"; break; //normal o regular
		case 'SU': $sql.= " AND ".$reg_s." < (".($row->kvan*0.4).")"; break; //sub utilizado
	}			
	
	$sql.= " GROUP BY turno";	
			
	//echo $sql; exit();
    $mysqli = newMySQLi(); //$mysqli = new mysqli('localhost', 'my_user', 'my_password', 'my_db');
	if (mysqli_connect_errno()) {exit();} // chequeo de coneccion

	if ($result = $mysqli->query($sql)) { //validar el resultado!
		$rowNum = 0;
		while($row = $result->fetch_object()) {
			$turno[] = $row->turno;
			$data[$rowNum]['turno'] = $row->turno;
			$data[$rowNum]['reg_s'] = /*round((*/$row->reg_s/**10)/60)*/;
			$rowNum++;
		} 
		$result->close(); // Free result  
	}
	if(!isset($data)) $data = array();
	if(!isset($turno)) $turno = array();
	if (!in_array ('1er Turno', $turno)) { $data[$rowNum]['turno'] = '1er Turno'; $data[$rowNum]['reg_s'] = 0; $rowNum++; } 
	if (!in_array ('2do Turno', $turno)) { $data[$rowNum]['turno'] = '2do Turno'; $data[$rowNum]['reg_s'] = 0; $rowNum++; } 
	if (!in_array ('3er Turno', $turno)) { $data[$rowNum]['turno'] = '3er Turno'; $data[$rowNum]['reg_s'] = 0; $rowNum++; } 
	
	
	$sql = "SELECT
				COUNT(*) AS total  
			FROM 
				".$tabla."
			WHERE 
			cro_id = ".$root." AND NOT(reg_vp <=> NULL)";
	
	if ($result = $mysqli->query($sql)) { //validar el resultado!
		
		while($row = $result->fetch_object()) {			
			$totalHoras = /*round((*/$row->total/**10)/60)*/;
		} 
		$result->close(); // Free result  
	}
		
	//echo $sql;
    //$result->close(); 
    unset($obj); 
    unset($sql); 
	

	//echo json_encode($paging); 
	echo $data[0]['turno'].",".$data[0]['reg_s']*(10/60).";".$data[1]['turno'].",".$data[1]['reg_s']*(10/60).";".$data[2]['turno'].",".$data[2]['reg_s']*(10/60).";Total,".round($totalHoras*(10/60), 3, PHP_ROUND_HALF_UP);

?>