<?php
	header("Content-Type: text/plain"); 
	
	include_once("../../getFunction.php");
	
	$root = $_POST['root'];
	$nfase = $_POST['numFase'];
	$por_v = isset($_POST['por_v'])?$_POST['por_v']:10;
	$voltn = isset($_POST['voltn'])?$_POST['voltn']:120;
	
	$fields=array(
		array("name" => "totales","header"=>"Totales"),
		array("name" => "validos","header"=>"Validos"),
		array("name" => "no_validos","header"=>"No_validos"),
		array("name" => "penalizados","header"=>"Penalizados"),
		array("name" => "pen_bajo","header"=>"Pen_bajo"),
		array("name" => "pen_alto","header"=>"Pen_alto"),
		array("name" => "no_penalizados","header"=>"No_penalizados"),
		array("name" => "FEB","header"=>"F.E.B"),
		array("name" => "TPI","header"=>"T.P.I")
	);
	
	switch($nfase) {
		case 1: $tabla = 'tm_reg_registro1f'; break;
		case 2: $tabla = 'tm_reg_registro2f'; break;
		case 3: $tabla = 'tm_reg_registro3f'; break;
	}
	
	connectMySQL();
	
	$sql = "SELECT (val.validos+nval.no_validos) AS totales, val.validos, nval.no_validos, (btn.pen_bajo+up.pen_alto) AS penalizados, btn.pen_bajo, up.pen_alto,(val.validos-(btn.pen_bajo+up.pen_alto)) as no_penalizados, ((btn.pen_bajo+up.pen_alto)/val.validos)*100 AS FEB, nval.no_validos*10 AS TPI ";
	$sql.= "FROM ";
	$sql.= "(SELECT 1 AS rowNum, COUNT(*) AS no_validos FROM ".$tabla." WHERE cro_id = ".$root." AND reg_vp <=> NULL) AS nval INNER JOIN ";
	$sql.= "(SELECT 1 AS rowNum, COUNT(*) AS validos    FROM ".$tabla." WHERE cro_id = ".$root." AND NOT(reg_vp <=> NULL)) AS val on nval.rowNum = val.rowNum INNER JOIN ";
	$sql.= "(SELECT 1 AS rowNum, COUNT(*) AS pen_bajo  FROM ".$tabla." WHERE cro_id = ".$root." AND NOT(reg_vp <=> NULL) AND reg_vp < ".($voltn*((100-$por_v)/100)).") AS btn ON val.rowNum = btn.rowNum  INNER JOIN ";
	$sql.= "(SELECT 1 AS rowNum, COUNT(*) AS pen_alto FROM ".$tabla." WHERE cro_id = ".$root." AND NOT(reg_vp <=> NULL) AND reg_vp > ".($voltn*((100+$por_v)/100)).") AS up ON btn.rowNum = up.rowNum";
	
	$result =  mysql_query($sql);
	
	$row = mysql_fetch_array($result, MYSQL_ASSOC);
	
	$data[0]['totales'] = $row['totales'];
	$data[0]['validos'] = $row['validos'];
	$data[0]['no_validos'] = $row['no_validos'];
	$data[0]['penalizados'] = $row['penalizados'];
	$data[0]['pen_bajo'] = $row['pen_bajo'];
	$data[0]['pen_alto'] = $row['pen_alto'];
	$data[0]['no_penalizados'] = $row['no_penalizados'];
	$data[0]['FEB'] = $row['FEB'];
	$data[0]['TPI'] = utf8_encode($row['TPI']);
	
	
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

?>