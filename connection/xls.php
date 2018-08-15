<?php

require('./conexion.php');
require('./consultas.php');
require('./funciones.php');

header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="report_scrap'.date("Ymd").'.xls"');

$ff = array();
if( isset( $conn ) ) {

    $opcion = $_POST['opcion'];
    $inicio = $_POST['inicio'];
    $final = $_POST['final'];

    $query = primaryQuery($opcion,$inicio,$final);
    $prep = sqlsrv_prepare($conn,$query);

    if (sqlsrv_execute($prep)){
        
        $filename = 'php://output';
        $fp = fopen($filename, 'w');

        while ($fila = sqlsrv_fetch_array($prep)){
            $ff[] = $fila;
        }

        foreach ($ff as $position => $value) {

            $datos1[] = $value;
            if (count($ff)-1 > $position){

                if ($ff[$position]['sPartId'] != $ff[$position+1]['sPartId'] ){
                    
                    json_encode($datos1); 
                    $sShortName = $datos1[0]['sShortName'];
                    $sPartId = $datos1[0]['sPartId'];
                    $tStart =  substr($datos1[0]['tStart']->date, 0, 19);
                    $tEnd = substr(end($datos1)['tEnd']->date, 0, 19);
                    $val = total($datos1, 'dPartCount');
                    $dPartCount = (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.')).substr($val, strrpos ($val, ".") , 3) : $val;
                    $val = total($datos1, 'dTotalParts');
                    $dTotalParts = (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.')).substr($val, strrpos ($val, ".") , 3) : $val;
                    $val = total($datos1, 'dScrapParts');
                    $dScrapParts = (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.')).substr($val, strrpos ($val, 3)) : $val;
                    $datos1 = array();
                    
                    $carga = array($sShortName, $sPartId, $tStart, $tEnd, $dPartCount, $dTotalParts, $dScrapParts);
                    echo implode("\t",$carga)."\n";
                }

            }else{
                json_encode($datos1);  
                $sShortName = $datos1[0]['sShortName'];
                $sPartId = $datos1[0]['sPartId'];
                $tStart = substr($datos1[0]['tStart']->date, 0, 19);
                $tEnd = substr(end($datos1)['tEnd']->date, 0, 19);
                $val = total($datos1, 'dPartCount');
                $dPartCount = (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.')).substr($val, strrpos ($val, ".") , 3) : $val;
                $val = total($datos1, 'dTotalParts');
                $dTotalParts = (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.')).substr($val, strrpos ($val, ".") , 3) : $val;
                $val = total($datos1, 'dScrapParts');
                $dScrapParts = (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.')).substr($val, strrpos ($val, 3)) : $val;
                
                $carga = array($sShortName, $sPartId, $tStart, $tEnd, $dPartCount, $dTotalParts, $dScrapParts);
                echo implode("\t",$carga)."\n";
            }
        }
        exit;
    }
}
?>