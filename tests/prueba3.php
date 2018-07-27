<?php

include('./conexion.php');
include('./consultas.php');
include('./funciones.php');

$i = 0;
$datos16 = array();
$datos12 = array();

$opcion = "1000015";
$inicio = "2010-03-09 10:15:00.000";
$final = "2015-03-15 11:15:00.000";

$query = primaryQuery($opcion,$inicio,$final);
$prep = sqlsrv_prepare($conn,$query);
if ( $resultado = sqlsrv_execute($prep) ) { 

    while ($fila = sqlsrv_fetch_array($prep)){

					//pasando las fechas a variables
					json_encode($fila);

                    if ($fila['sPartId'] == '16 oz'){
                        if ($datos12 != []){
                            echo $datos12[0]['sShortName'];
                            echo("<br/>");
                            echo $datos12[0]['sPartId'];
                            echo("<br/>");
                            echo $datos12[0]['tStart']->date;
                            echo("<br/>");
                            echo end($datos12)['tEnd']->date;
                            echo("<br/>");                             
                            echo total($datos12, 'dTotalParts');
                            echo("<br/>");
                            echo total($datos12, 'dPartCount');
                            echo("<br/>");
                            echo total($datos12, 'dScrapParts');
                            echo("<br/>");
                            echo("<br/>");
                            echo("<br/>");
                        }   
                            $datos16[] = $fila;
                            $datos12 = array();
                    }else{
                        if ($datos16 != []){
                            echo $datos16[0]['lOEEConfigWorkCellId'];
                            echo("<br/>");
                            echo $datos16[0]['sPartId'];
                            echo("<br/>");
                            echo $datos16[0]['tStart']->date;
                            echo("<br/>");
                            echo end($datos16)['tEnd']->date;
                            echo("<br/>"); 
                            echo total($datos16, 'dTotalParts');
                            echo("<br/>");
                            echo total($datos16, 'dPartCount');
                            echo("<br/>");
                            echo total($datos16, 'dScrapParts');
                            echo("<br/>");
                            echo("<br/>");
                            echo("<br/>");
                        }
                        $datos12[] = $fila;
                        $datos16 = array();
                    }
                }
                    
                $array = validar($datos12, $datos16);
                print_r($array);
                    
                }    
?>