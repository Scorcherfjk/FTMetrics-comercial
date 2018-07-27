<?php

    require('./conexion.php');
    require('./consultas.php');
    require('./funciones.php');

    sqlsrv_begin_transaction( $conn );
    $query = updateQuery($_POST['lOEEWorkCellId'], $_POST['addScrap']);
    $tt = sqlsrv_query( $conn, $query );
    if ($tt){
        sqlsrv_commit( $conn );
        sqlsrv_close( $conn ); 
        header("Location:"."/FTMetrics/php/public/searchSpecific.php");
    }else{
        sqlsrv_rollback( $conn );
        sqlsrv_close( $conn ); 
    }
    
?>