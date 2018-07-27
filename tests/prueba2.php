<?php

include('./php/connection/conexion.php');
include('./php/connection/consultas.php');
include('./php/connection/funciones.php');

$i = 0;
$datos16 = array();
$datos12 = array();

$opcion = "1000015";
$inicio = "2015-03-09 10:15:00.000";
$final = "2015-03-10 11:15:00.000";

$query = primaryQuery($opcion,$inicio,$final);
$prep = sqlsrv_prepare($conn,$query);
if ( $resultado = sqlsrv_execute($prep) ) { 

    while ($fila = sqlsrv_fetch_array($prep)){

					//pasando las fechas a variables
					json_encode($fila);
					$fechaInicio = substr($fila['tStart']->date, 0, 19);
                    $fechaFinal = substr($fila['tEnd']->date, 0, 19);

                    if ($fila['sPartId'] == '16 oz'){
                        if ($datos12 != []){ 
                            echo "dTotalParts 12 oz: ".total($datos12, 'dTotalParts');
                            echo("<br/>");
                            echo "dPartCount 12 oz: ".total($datos12, 'dPartCount');
                            echo("<br/>");
                            echo "dScrapParts 12 oz: ".total($datos12, 'dScrapParts');
                            echo("<br/>");
                            echo("<br/>");
                            echo("<br/>");
                        }   
                            $datos16[] = $fila;
                            $datos12 = array();
                    }else{
                        if ($datos16 != []){
                            echo "dTotalParts 16 oz: ".total($datos16, 'dTotalParts');
                            echo("<br/>");
                            echo "dPartCount 16 oz: ".total($datos16, 'dPartCount');
                            echo("<br/>");
                            echo "dScrapParts 16 oz: ".total($datos16, 'dScrapParts');
                            echo("<br/>");
                            echo("<br/>");
                            echo("<br/>");
                        }
                        $datos12[] = $fila;
                        $datos16 = array();
                    }
                }
                    
                    if ($datos12 != []){ 
                        echo "dTotalParts 12 oz: ".total($datos12, 'dTotalParts');
                        echo("<br/>");
                        echo "dPartCount 12 oz: ".total($datos12, 'dPartCount');
                        echo("<br/>");
                        echo "dScrapParts 12 oz: ".total($datos12, 'dScrapParts');
                        echo("<br/>");
                        echo("<br/>");
                        echo("<br/>");
                    } elseif ($datos16 != []){
                        echo "dTotalParts 16 oz: ".total($datos16, 'dTotalParts');
                        echo("<br/>");
                        echo "dPartCount 16 oz: ".total($datos16, 'dPartCount');
                        echo("<br/>");
                        echo "dScrapParts 16 oz: ".total($datos16, 'dScrapParts');
                        echo("<br/>");
                        echo("<br/>");
                        echo("<br/>");
                    }
                }            
?>