<?php
	include_once("../getFunction.php");
		
	$root  = $_POST['root'];
	//$root = 604;
	
	$mysqli = newMySQLi();
	// chequeo de coneccion
	if (mysqli_connect_errno()) {exit();}
	
	$sql = "SELECT cro_placa FROM tm_cro_cronograma WHERE cro_id = ".$root;
	
    if ($result = $mysqli->query($sql)) { 
        $row = $result->fetch_object();
		$placa = $row->cro_placa;
    }
	//$data['placa'] = $placa;
	
    $result->close(); 
    unset($obj); 
    unset($sql);
	
	$mysqliSGD	= newMySQLiSGD();
	if (mysqli_connect_errno()) {exit();}
	
	$sql = "SELECT ut.qtdfases, COUNT(*) AS numequip ";
	$sql.= "FROM utransformadora ut INNER JOIN eqtransformador eq ON ut.instalacao = eq.instalacao ";
	$sql.= "WHERE ut.instalacao = '".$placa."' ";
	$sql.= "GROUP BY ut.qtdfases ";
	
	/*$stid = oci_parse(connectSGD(), $sql);
	oci_execute($stid);
	
	while ($row = oci_fetch_assoc($stid)) { 
		$data['nfases'] = $row["QTDFASES"];
		$data['nequip'] = $row["NUMEQUIP"];
	}*/
	
    if ($result = $mysqliSGD->query($sql)) { 
		$row = $result->fetch_object();
        $nfases = $row->qtdfases;
		$nequip = $row->numequip;
    }
	
	
	echo $placa.';'.$nfases.';'.$nequip;
?>  