<?php
	function conectar_sgd()
	{
		/*$CONFIG2 = array();		//xq CONFIG2 si se utiliza CONFIG ??
		
		$CONFIG['SHOST_NAME'] = '10.71.1.33';
		$CONFIG['SDATABASE_SID'] = 'MART';
		$CONFIG['SDB_USERNAME'] = 'seneca_sgd';
		$CONFIG['SDB_PASSWORD'] = 'brutal';	
		
		$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = ".$CONFIG['SHOST_NAME'].")(PORT = 1521)))(CONNECT_DATA=(SID=".$CONFIG['SDATABASE_SID'].")))";
		
		$conn = oci_connect($CONFIG['SDB_USERNAME'],$CONFIG['SDB_PASSWORD'],$db);
		*/
		
		$conn = oci_connect('admin','root','localhost/XE');
		
		/*return $conn;		*/
	}
?>