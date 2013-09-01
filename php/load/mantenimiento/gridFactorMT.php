<?php
	header("Content-Type: text/plain"); 
	
	include_once("../../getFunction.php");
	
	$root = isset($_POST['root'])?$_POST['root']:604;
	$idUT = isset($_POST['idUT'])?$_POST['idUT']:'CC01986';
	$nfase = isset($_POST['numFase'])?$_POST['numFase']:3;
	$numEquip = isset($_POST['numEquip'])?$_POST['numEquip']:3;
	
	$mysqliSGD	= newMySQLiSGD();
	if (mysqli_connect_errno()) {exit();}
	
	$addFase = $numEquip == 3 ? ", s1.kvan AS kvan_s1, s2.kvan AS kvan_s2, s3.kvan AS kvan_s3" : "";
	$sql ="SELECT st.kvan AS kvan_st".$addFase." FROM ";
																								   
		$sql.="( SELECT ROUND(kvan*1000,3) AS kvan, '1' AS id ";
		$sql.="FROM utransformadora ";
		$sql.="WHERE instalacao='".$idUT."' ) st ";
	
	if($numEquip==3) {
		$sql.="INNER JOIN ( SELECT ROUND(kvan*1000,3) AS kvan, '1' AS id ";
		$sql.="FROM eqtransformador ";
		$sql.="WHERE instalacao='".$idUT."' AND faseligsec='A' ) s1 ON st.id = s1.id ";
		$sql.="INNER JOIN ( SELECT ROUND(kvan*1000,3) AS kvan, '1' AS id ";
		$sql.="FROM eqtransformador ";
		$sql.="WHERE instalacao='".$idUT."' AND faseligsec='B' ) s2 ON s1.id = s2.id ";
		$sql.="INNER JOIN ( SELECT ROUND(kvan*1000,3) AS kvan, '1' AS id ";
		$sql.="FROM eqtransformador ";
		$sql.="WHERE instalacao='".$idUT."' AND faseligsec='C' ) s3 ON s2.id = s3.id";
	}	
	
	/*$stid = oci_parse(connectSGD(), $sql);
	oci_execute($stid);
	$row = oci_fetch_assoc($stid);*/
	
	if ($result = $mysqliSGD->query($sql)) { 
		$row = $result->fetch_object();		
    }
	
	$tabla = $nfase == 3 ? "tm_reg_registro3f" : "tm_reg_registro2f";
	
	$sql = "SELECT
			   fac.fase, fac.fac_car, fac.fac_uti, sc.n_sc, sc.h_sc, su.n_su, su.h_su
			FROM (
				SELECT
					'FASE T' AS fase, ROUND((MAX(reg_st)/".$row->kvan_st.")*100,3) AS fac_uti, ROUND((AVG(reg_st)/MAX(reg_st))*100,3) AS fac_car   
				FROM ".$tabla."   
				WHERE cro_id = ".$root."
			) AS fac INNER JOIN (
				SELECT 
					'FASE T' AS fase, COUNT(*) AS n_sc, ROUND((COUNT(*)*10)/60,3) AS h_sc 
				FROM ".$tabla."   
				WHERE cro_id = ".$root." AND reg_st > (".$row->kvan_st.")  
			) AS sc ON fac.fase = sc.fase INNER JOIN (
				SELECT 
					'FASE T' AS fase, COUNT(*) AS n_su, ROUND((COUNT(*)*10)/60,3) AS h_su 
				FROM ".$tabla." 
				WHERE cro_id = ".$root." AND reg_st < (".($row->kvan_st*0.4).")
			) AS su ON sc.fase = su.fase "; 
			
	if($numEquip==3) {
		$sql.= "UNION ALL 
				SELECT
				   fac.fase, fac.fac_car, fac.fac_uti, sc.n_sc, sc.h_sc, su.n_su, su.h_su
				FROM (               
					SELECT 
					   'FASE A' AS fase, ROUND((MAX(reg_s1)/".$row->kvan_s1.")*100,3) AS fac_uti, ROUND((AVG(reg_s1)/MAX(reg_s1))*100,3) AS fac_car   
					FROM ".$tabla."   
					WHERE cro_id = ".$root." 
				) AS fac INNER JOIN (
					SELECT 
						'FASE A' AS fase, COUNT(*) AS n_sc, ROUND((COUNT(*)*10)/60,3) AS h_sc 
					FROM ".$tabla."   
					WHERE cro_id = ".$root." AND reg_s1 > (".$row->kvan_s1.")
				) AS sc ON fac.fase = sc.fase INNER JOIN (
					SELECT 
						'FASE A' AS fase, COUNT(*) AS n_su, ROUND((COUNT(*)*10)/60,3) AS h_su 
					FROM ".$tabla." 
					WHERE cro_id = ".$root." AND reg_s1 < (".($row->kvan_s1*0.4).")
				) AS su on sc.fase = su.fase
				UNION ALL 
				SELECT
				   fac.fase, fac.fac_car, fac.fac_uti, sc.n_sc, sc.h_sc, su.n_su, su.h_su
				FROM (               
					SELECT 
					   'FASE B' AS fase, ROUND((MAX(reg_s2)/".$row->kvan_s2.")*100,3) AS fac_uti, ROUND((AVG(reg_s2)/MAX(reg_s2))*100,3) AS fac_car   
					FROM ".$tabla."   
					WHERE cro_id = ".$root." 
				) AS fac INNER JOIN (
					SELECT 
						'FASE B' AS fase, COUNT(*) AS n_sc, ROUND((COUNT(*)*10)/60,3) AS h_sc 
					FROM ".$tabla."   
					WHERE cro_id = ".$root." AND reg_s2 > (".$row->kvan_s2.")
				) AS sc ON fac.fase = sc.fase INNER JOIN (
					SELECT 
						'FASE B' AS fase, COUNT(*) AS n_su, ROUND((COUNT(*)*10)/60,3) AS h_su 
					FROM ".$tabla." 
					WHERE cro_id = ".$root." AND reg_s2 < (".($row->kvan_s2*0.4).")
				) AS su on sc.fase = su.fase
				UNION ALL 
				SELECT
				   fac.fase, fac.fac_car, fac.fac_uti, sc.n_sc, sc.h_sc, su.n_su, su.h_su
				FROM (               
					SELECT 
					   'FASE C' AS fase, ROUND((MAX(reg_s3)/".$row->kvan_s3.")*100,3) AS fac_uti, ROUND((AVG(reg_s3)/MAX(reg_s3))*100,3) AS fac_car   
					FROM ".$tabla."   
					WHERE cro_id = ".$root." 
				) AS fac INNER JOIN (
					SELECT 
						'FASE C' AS fase, COUNT(*) AS n_sc, ROUND((COUNT(*)*10)/60,3) AS h_sc 
					FROM ".$tabla."   
					WHERE cro_id = ".$root." AND reg_s3 > (".$row->kvan_s3.")
				) AS sc ON fac.fase = sc.fase INNER JOIN (
					SELECT 
						'FASE C' AS fase, count(*) AS n_su, ROUND((COUNT(*)*10)/60,3) AS h_su 
					FROM ".$tabla." 
					WHERE cro_id = ".$root." AND reg_s3 < (".($row->kvan_s3*0.4).")
				) AS su on sc.fase = su.fase";
	}
	
	$fields=array(
				array("name" => "fase","header"=>""),
				array("name" => "fac_car","header"=>"FC"),
				array("name" => "fac_uti","header"=>"FU"),
				array("name" => "n_sc","header"=>"Reg. SC"),
				array("name" => "h_sc","header"=>"Horas SC"),
				array("name" => "n_su","header"=>"Reg. SU"),
				array("name" => "h_su","header"=>"Horas SU")
				
	);
	//echo $sql; exit();
	$mysqli = newMySQLi();
	if (mysqli_connect_errno()) {} // chequeo de coneccion
	
	$rowNum = 0;
	$row = '';
	if ($result = $mysqli->query($sql)) {
        while($row = $result->fetch_object()){
            $data[$rowNum]['fase'] = $row->fase;
            $data[$rowNum]['fac_car'] = $row->fac_car;
            $data[$rowNum]['fac_uti'] = $row->fac_uti;
			$data[$rowNum]['n_sc'] = $row->n_sc;
            $data[$rowNum]['h_sc'] = $row->h_sc;
			$data[$rowNum]['n_su'] = $row->n_su;
            $data[$rowNum]['h_su'] = $row->h_su;
			$rowNum++;
        }
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
	
	//echo $sql;
	echo json_encode($paging);
	//print_r($paging);

?>