<?php
	//conexion a la base de datos MsSQL

	//Datos de la conexion
	$serverName = "W2K12XPOC";
	$Database = "FTMetrics2" ;
	$UID = "FTMUser" ;
	$PWD = "FTMUser" ;

	//Realizando conexion con la Base de Datos
	$connectionInfo = array( "Database"=>$Database, "UID"=>$UID, "PWD"=>$PWD);
	if (sqlsrv_connect( $serverName, $connectionInfo)){
		$conn = sqlsrv_connect( $serverName, $connectionInfo);
	}

?>