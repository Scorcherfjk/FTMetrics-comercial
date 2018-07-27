<?php
	//conexion a la base de datos MsSQL
	$serverName = "W2K12XPOC";
	$connectionInfo = array( "Database"=>"FTMetrics2", "UID"=>"FTMUser", "PWD"=>"FTMUser");
	if (sqlsrv_connect( $serverName, $connectionInfo)){
		$conn = sqlsrv_connect( $serverName, $connectionInfo);
	}
?>