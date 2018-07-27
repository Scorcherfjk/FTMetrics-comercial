<?php

    require('./conexion.php');
    require('./consultas.php');
    require('./funciones.php');

    sqlsrv_begin_transaction( $conn );
    $query = updateQuery($_POST['lOEEWorkCellId'], $_POST['addScrap']);
    if ( $_POST['addScrap'] <= $_POST['dtotalParts'] ){
        $tt = sqlsrv_query( $conn, $query );
    }
    if ($tt){
        sqlsrv_commit( $conn );
        sqlsrv_close( $conn ); 
        header("Location:"."/FTMetrics/FTMetrics-comercial/public/search.php");
    }else{
        sqlsrv_rollback( $conn );
        sqlsrv_close( $conn );
        echo "ha ocurrido un error. volver al inicio";
        echo "<a href='".header("Location:"."/FTMetrics/FTMetrics-comercial/public/search.php")."'></a>";
    }
    
?>