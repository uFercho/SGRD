<?php

include_once("../getFunction.php");

$root  = isset($_POST['root'])?$_POST['root']:1027;
$nfase  = isset($_POST['numFase'])?$_POST['numFase']:3;

$v1  = isset($_POST['v1'])?$_POST['v1']:0;
$v2  = isset($_POST['v2'])?$_POST['v2']:0;
$v3  = isset($_POST['v3'])?$_POST['v3']:0;
$i1  = isset($_POST['i1'])?$_POST['i1']:0;
$i2  = isset($_POST['i2'])?$_POST['i2']:0;
$i3  = isset($_POST['i3'])?$_POST['i3']:0;
$s1  = isset($_POST['s1'])?$_POST['s1']:0;
$s2  = isset($_POST['s2'])?$_POST['s2']:0;
$s3  = isset($_POST['s3'])?$_POST['s3']:0;
$st  = isset($_POST['st'])?$_POST['st']:0;
$pf1 = isset($_POST['pf1'])?$_POST['pf1']:0;
$pf2 = isset($_POST['pf2'])?$_POST['pf2']:0;
$pf3 = isset($_POST['pf3'])?$_POST['pf3']:0;
$pft = isset($_POST['pft'])?$_POST['pft']:0;
$wt  = isset($_POST['wt'])?$_POST['wt']:0;

$t = "\t";

$mysqli = newMySQLi();

//if (mysqli_connect_errno()) {exit();} // chequeo de coneccion

	$sql = "SELECT 
				cro_serial,
				DATE(cro_colocacion) AS cro_ini_date,
				TIME(cro_colocacion) AS cro_ini_time,
				DATE(cro_retiro_real) AS cro_fin_date,
				TIME(cro_retiro_real) AS cro_fin_time,
				cro_intervalo,
				cro_row
			FROM 
				tm_cro_cronograma 
			WHERE 
				cro_id = ".$root;
	
	if ($result = $mysqli->query($sql)) { //validar el resultado!
		while($row = $result->fetch_object()) {
			$txt = '"UP 2210"'.PHP_EOL;
			$txt.= '"Version"'.$t.'"1.61"'.$t.'"1.61"'.PHP_EOL;
			$txt.= '"Serialnumber:"'.$t.$row->cro_serial.PHP_EOL;
			$txt.= '"Main comment"'.$t.'"Measure 1"'.PHP_EOL;
			$txt.= '"Start:"'.$t.'"'.$row->cro_ini_date.'"'.$t.'"'.$row->cro_ini_time.'"'.PHP_EOL;
			$txt.= '"End:"'.$t.'"'.$row->cro_fin_date.'"'.$t.'"'.$row->cro_fin_time.'"'.PHP_EOL;
			$intervalo = $row->cro_intervalo;
			$txt.= '"Storage interval:"'.$t.'"0:'.$intervalo.':00"'.PHP_EOL;	
			$name = $row->cro_row < 10 ? '00'.$row->cro_row.'001.txt' : '0'.$row->cro_row.'001.txt';
			$filename = fopen($name, "w");
		} 
		$result->close(); // Free result  
	}
	
$txt.= PHP_EOL;
$txt.= PHP_EOL;
$txt.= PHP_EOL;
$txt.= '"Channel/Method"'.$t.'"Comment"'.$t.'"Range"'.$t.'"Min"'.$t.'"Max"'.PHP_EOL;


	$sql = "SELECT ";
		if($v1==1) $sql.= " ROUND(MIN(reg_v1),3)  AS min_v1,  ROUND(MAX(reg_v1),3)  AS max_v1, ";
		if($v2==1) $sql.= " ROUND(MIN(reg_v2),3)  AS min_v2,  ROUND(MAX(reg_v2),3)  AS max_v2, ";
		if($v3==1) $sql.= " ROUND(MIN(reg_v3),3)  AS min_v3,  ROUND(MAX(reg_v3),3)  AS max_v3, ";
		if($i1==1) $sql.= " ROUND(MIN(reg_i1),3)  AS min_i1,  ROUND(MAX(reg_i1),3)  AS max_i1, ";
		if($i2==1) $sql.= " ROUND(MIN(reg_i2),3)  AS min_i2,  ROUND(MAX(reg_i2),3)  AS max_i2, ";
		if($i3==1) $sql.= " ROUND(MIN(reg_i3),3)  AS min_i3,  ROUND(MAX(reg_i3),3)  AS max_i3, ";
		if($s1==1) $sql.= " ROUND(MIN(reg_s1),3)  AS min_s1,  ROUND(MAX(reg_s1),3)  AS max_s1, ";
		if($s2==1) $sql.= " ROUND(MIN(reg_s2),3)  AS min_s2,  ROUND(MAX(reg_s2),3)  AS max_s2, ";
		if($s3==1) $sql.= " ROUND(MIN(reg_s3),3)  AS min_s3,  ROUND(MAX(reg_s3),3)  AS max_s3, ";
		if($st==1) $sql.= " ROUND(MIN(reg_st),3)  AS min_st,  ROUND(MAX(reg_st),3)  AS max_st, "; 
		if($pf1==1)$sql.= " ROUND(MIN(reg_fp1),3) AS min_fp1, ROUND(MAX(reg_fp1),3) AS max_fp1, ";
		if($pf2==1)$sql.= " ROUND(MIN(reg_fp2),3) AS min_fp2, ROUND(MAX(reg_fp2),3) AS max_fp2, ";
		if($pf3==1)$sql.= " ROUND(MIN(reg_fp3),3) AS min_fp3, ROUND(MAX(reg_fp3),3) AS max_fp3, ";
		if($pft==1)$sql.= " ROUND(MIN(reg_fpt),3) AS min_fpt, ROUND(MAX(reg_fpt),3) AS max_fpt, ";
		if($wt==1) $sql.= " ROUND(MIN(reg_wt),3)  AS min_wt,  ROUND(MAX(reg_wt),3)  AS max_wt  "; 
	$sql.= " FROM ";
		$sql.= $nfase==3 ? "tm_reg_registro3f " : "tm_reg_registro2f ";
	$sql.= " WHERE ";
		$sql.= " cro_id = ".$root;	
	
	if ($result = $mysqli->query($sql)) { //validar el resultado!
		while($row = $result->fetch_object()) {
			if($v1==1) $txt.= '"U1  [V] Voltage"'.$t.'""'.$t.'""'.$t.$row->min_v1.$t.$row->max_v1.PHP_EOL;
			if($v2==1) $txt.= '"U2  [V] Voltage"'.$t.'""'.$t.'""'.$t.$row->min_v2.$t.$row->max_v2.PHP_EOL;
			if($v3==1) $txt.= '"U3  [V] Voltage"'.$t.'""'.$t.'""'.$t.$row->min_v3.$t.$row->max_v3.PHP_EOL;
			if($i1==1) $txt.= '"I5  [A] Current"'.$t.'""'.$t.'""'.$t.$row->min_i1.$t.$row->max_i1.PHP_EOL;
			if($i2==1) $txt.= '"I6  [A] Current"'.$t.'""'.$t.'""'.$t.$row->min_i2.$t.$row->max_i2.PHP_EOL;
			if($i3==1) $txt.= '"I7  [A] Current"'.$t.'""'.$t.'""'.$t.$row->min_i3.$t.$row->max_i3.PHP_EOL;
			if($s1==1) $txt.= '"S1-5  [VA] 1-Wattmeter"'.$t.'""'.$t.'""'.$t.$row->min_s1.$t.$row->max_s1.PHP_EOL;
			if($s2==1) $txt.= '"S2-6  [VA] 1-Wattmeter"'.$t.'""'.$t.'""'.$t.$row->min_s2.$t.$row->max_s2.PHP_EOL;
			if($s3==1) $txt.= '"S3-7  [VA] 1-Wattmeter"'.$t.'""'.$t.'""'.$t.$row->min_s3.$t.$row->max_s3.PHP_EOL;
			if($st==1) $txt.= '"S1-7  [VA] 3-Wattmeter"'.$t.'""'.$t.'""'.$t.$row->min_st.$t.$row->max_st.PHP_EOL;
			if($pf1==1)$txt.= '"PF1-5  [] 1-Wattmeter"'.$t.'""'.$t.'""'.$t.$row->min_fp1.$t.$row->max_fp1.PHP_EOL;
			if($pf2==1)$txt.= '"PF2-6  [] 1-Wattmeter"'.$t.'""'.$t.'""'.$t.$row->min_fp2.$t.$row->max_fp2.PHP_EOL;
			if($pf3==1)$txt.= '"PF3-7  [] 1-Wattmeter"'.$t.'""'.$t.'""'.$t.$row->min_fp3.$t.$row->max_fp3.PHP_EOL;
			if($pft==1)$txt.= '"PF1-7  [] 3-Wattmeter"'.$t.'""	'.$t.'"'.$t.$row->min_fpt.$t.$row->max_fpt.PHP_EOL;
			if($wt==1) $txt.= '"Wp1-7  [Wh] Energy"'.$t.'"'.$intervalo.'"'.$t.'""'.$t.$row->min_wt.$t.$row->max_wt.PHP_EOL;
		} 
		$result->close(); // Free result  
	}
$txt.= PHP_EOL;
$txt.= PHP_EOL;
$txt.= PHP_EOL; //continuar el dinamismo desde el punto de vista de las faces "filtrando la fase 3 en todos los casos"
$txt.= PHP_EOL; 

	
	$txt.= 'Date'.$t;
	$txt.= 'Time'.$t;
	if($v1==1) $txt.= '"U1  [V]"'.$t;
	if($v2==1) $txt.= '"U2  [V]"'.$t;
	if($v3==1) $txt.= '"U3  [V]"'.$t;
	if($i1==1) $txt.= '"I5  [A]"'.$t;
	if($i2==1) $txt.= '"I6  [A]"'.$t;
	if($i3==1) $txt.= '"I7  [A]"'.$t;
	if($s1==1) $txt.= '"S1-5  [VA]"'.$t;
	if($s2==1) $txt.= '"S2-6  [VA]"'.$t;
	if($s3==1) $txt.= '"S3-7  [VA]"'.$t;
	if($st==1) $txt.= '"S1-7  [VA]"'.$t;
	if($pf1==1)$txt.= '"PF1-5  []"'.$t;
	if($pf2==1)$txt.= '"PF2-6  []"'.$t;
	if($pf3==1)$txt.= '"PF3-7  []"'.$t;
	if($pft==1)$txt.= '"PF1-7  []"'.$t;
	if($wt==1) $txt.= '"Wp1-7  [Wh]"'.$t;
	$txt.= PHP_EOL;

	
	$sql = "SELECT ";
		$sql.= "DATE_FORMAT(reg_date_time, '%d/%m/%Y') AS reg_d, ";
		$sql.= "DATE_FORMAT(reg_date_time, '%T') AS reg_h, ";
		if($v1==1) $sql.= "ROUND(reg_v1,3) AS reg_v1, ";
		if($v2==1) $sql.= "ROUND(reg_v2,3) AS reg_v2, "; 
		if($v3==1) $sql.= "ROUND(reg_v3,3) AS reg_v3, ";   
		if($i1==1) $sql.= "ROUND(reg_i1,3) AS reg_i1, ";
		if($i2==1) $sql.= "ROUND(reg_i2,3) AS reg_i2, ";
		if($i3==1) $sql.= "ROUND(reg_i3,3) AS reg_i3, ";   
		if($s1==1) $sql.= "ROUND(reg_s1,3) AS reg_s1, ";
		if($s2==1) $sql.= "ROUND(reg_s2,3) AS reg_s2, ";
		if($s3==1) $sql.= "ROUND(reg_s3,3) AS reg_s3, "; 
		if($st==1) $sql.= "ROUND(reg_st,3) AS reg_st, "; 
		if($pf1==1)$sql.= "ROUND(reg_fp1,3) AS reg_fp1, ";
		if($pf2==1)$sql.= "ROUND(reg_fp2,3) AS reg_fp2, ";
		if($pf3==1)$sql.= "ROUND(reg_fp3,3) AS reg_fp3, ";
		if($pft==1)$sql.= "ROUND(reg_fpt,3) AS reg_fpt, ";
		if($wt==1) $sql.= "ROUND(reg_wt,3) AS reg_wt  ";
	$sql.= "FROM ";
		$sql.= $nfase==3 ? "tm_reg_registro3f " : "tm_reg_registro2f ";
	$sql.= "WHERE "; 
		$sql.= "cro_id = ".$root;
								 
				
	
	if ($result = $mysqli->query($sql)) { //validar el resultado!
		while($row = $result->fetch_object()) {
			$txt.= '"'.$row->reg_d.'"'.$t;
			$txt.= '"'.$row->reg_h.'"'.$t;
			if($v1==1) $txt.= $row->reg_v1.$t;
			if($v2==1) $txt.= $row->reg_v2.$t;
			if($v3==1) $txt.= $row->reg_v3.$t;
			if($i1==1) $txt.= $row->reg_i1.$t;
			if($i2==1) $txt.= $row->reg_i2.$t;
			if($i3==1) $txt.= $row->reg_i3.$t;
			if($s1==1) $txt.= $row->reg_s1.$t;
			if($s2==1) $txt.= $row->reg_s2.$t;
			if($s3==1) $txt.= $row->reg_s3.$t;
			if($st==1) $txt.= $row->reg_st.$t;
			if($pf1==1)$txt.= $row->reg_fp1.$t;
			if($pf2==1)$txt.= $row->reg_fp2.$t;
			if($pf3==1)$txt.= $row->reg_fp3.$t;
			if($pft==1)$txt.= $row->reg_fpt.$t;
			if($wt==1) $txt.= $row->reg_wt.$t;
			$txt.= PHP_EOL;
		} 
		$result->close(); // Free result  
	}

fwrite($filename, $txt);

header("Content-type: application/force-download"); 
header("Content-disposition: attachment; filename=".$name);
header("Content-Transfer-Encoding: binary"); 
header("Content-Length: ".filesize($name));
readfile($name); 
?>