<?php
	include_once("../getFunction.php");

	$login = isset($_POST['login'])?$_POST['login']:'';
	$password = isset($_POST['password'])?$_POST['password']:'';

	$mysqli = newMySQLi(); 
	// chequeo de coneccion
	if (mysqli_connect_errno()) {exit();}
	
	$sql = "SELECT
				COUNT(*) AS usuario
			FROM
				tm_usu_usuario
			WHERE
				usu_login = '".$login."' AND
				usu_pass  = '".$password."'";
				
    if ($result = $mysqli->query($sql)) { 
        $row = $result->fetch_object();
		if($row->usuario == 1)
			$output = msgReturn(true,'Usuario validado.');	
		else
			$output = msgReturn(false,'Usuario no v&aacute;lido.');
    } else {$output = msgReturn(false,'Error de conecci&oacute;n. No se puedo validar el Usuario.');}
	
    $result->close(); 
    unset($obj); 
    unset($sql);
	echo $output;
?>