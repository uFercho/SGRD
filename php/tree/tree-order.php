<?php
 

include_once("../getFunction.php");
// Include the Tree class to generate the TreePanel JSON easily
include("TreeExtJS.class.php"); 

// Make a MySQL Connection and select the database
connectMySQL();

if(isset($_POST["updateOrder"])){
	//update the order
	$nodes = $_POST["nodes"];
	$ids = explode(",",$nodes);
	$idParent = (int)$_POST["parent"];
	for($i=0;$i<count($ids);$i++){
		$id = (int)$ids[$i];
		$query = sprintf("UPDATE tm_tre_tree_menu SET tre_orden_num = %d,tre_id_parent = %d WHERE tre_id = %d",
					$i,$idParent,$id);
		mysql_query($query);
	}
	
}else{

	// Retrieve all the data from the "categories" table
	$result = mysql_query("SELECT * FROM tm_tre_tree_menu WHERE tre_estado = 'PROC' ORDER BY tre_id_parent, tre_id ASC")
	or die(mysql_error());  

	// Create an array of the data
	$data = array();
	while($row = mysql_fetch_array( $result )){
		array_push($data,array(
			"id" => $row["tre_id"],
			"idParent" => $row["tre_id_parent"],
			"text" => utf8_encode($row["tre_texto"]),
			"iconCls" => $row["tre_categoria"]=="CAMPA"?"icon-campana":"icon-muestra",
			"orderNumber" => $row["tre_orden_num"],
			"id-tabla" => $row["tre_id_tabla"]
		));
	}

	

	// Creating the Tree
	$tree = new TreeExtJS();
	for($i=0;$i<count($data);$i++){
		$category = $data[$i];
		$tree->addChild($category,$category["idParent"]);
	}

	echo '[{"text": "SGRD","iconCls": "icon-root","leaf": false,"children": '.$tree->toJson().'}]';//,{"text": "Herramientas","idParent":null,"iconCls": "icon-tools","leaf": true}]';
}
/*mysql_close();*/
?>