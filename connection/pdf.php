<?php

require('./conexion.php');
require('./consultas.php');
require('./funciones.php');

require_once('./dompdf/lib/html5lib/Parser.php');
require_once('./dompdf/src/Autoloader.php');
Dompdf\Autoloader::register();

use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

$ff = array();
if( isset( $conn ) ) {

    $opcion = $_POST['opcion'];
    $inicio = $_POST['inicio'];
    $final = $_POST['final'];

    $query = primaryQuery($opcion,$inicio,$final);
    $prep = sqlsrv_prepare($conn,$query);

    if ( $resultado = sqlsrv_execute($prep) ) { 

        $html = '<table width="100%" border="1">
                    <thead>
                        <tr class="text-center">
                            <th scope="col">Machine</th>
                            <th scope="col">Part Id</th>
                            <th scope="col">Start Time</th>
                            <th scope="col">End Time</th>
                            <th scope="col">Good Parts</th>
                            <th scope="col">Total Parts</th>
                            <th scope="col">Scrap Parts</th>
                        </tr>
                    </thead>
                    <tbody>';
            while ($fila = sqlsrv_fetch_array($prep)){
                $ff[] = $fila;
            }

        foreach ($ff as $position => $value) {

            $datos1[] = $value;
            if (count($ff)-1 > $position){

                if ($ff[$position]['sPartId'] != $ff[$position+1]['sPartId'] ){
                    
                    json_encode($datos1);
                    $sShortName = $datos1[0]["sShortName"];
                    $sPartId = $datos1[0]["sPartId"];
                    $tStart = substr($datos1[0]["tStart"]->date, 0, 19) ;
                    $tEnd = substr(end($datos1)["tEnd"]->date, 0, 19) ;
                    $val1 = total($datos1, "dPartCount");
                        $dPartCount = (strpos($val1, ".")) ? substr($val1, 0 , strpos($val1, ".")).substr($val1, strrpos ($val1, ".") , 3) : $val1 ;        
                    $val2 = total($datos1, "dTotalParts");
                        $dTotalParts = (strpos($val2, ".")) ? substr($val2, 0 , strpos($val2, ".").substr($val2, strrpos ($val2, ".") , 3) ): $val2 ;    
                    $val3 = total($datos1, "dScrapParts");
                        $dScrapParts = (strpos($val3, ".")) ? substr($val3, 0 , strpos($val3, ".")).substr($val3, strrpos ($val3, 3)) : $val3 ;
                    
                    $html .='<tr>
                            <th scope="row">'.$sShortName.'</th>
                            <td>'.$sPartId.'</td>
                            <td>'.$tStart.'</td>
                            <td>'.$tEnd.'</td>
                            <td>'.$dPartCount.'</td>
                            <td>'.$dTotalParts.'</td>
                            <td>'.$dScrapParts.'</td>
                        </tr>';
                    $datos1 = array();
                }

            }else{
                json_encode($datos1);
                $sShortName = $datos1[0]["sShortName"];
                $sPartId = $datos1[0]["sPartId"];
                $tStart = substr($datos1[0]["tStart"]->date, 0, 19) ;
                $tEnd = substr(end($datos1)["tEnd"]->date, 0, 19) ;
                $val1 = total($datos1, "dPartCount");
                    $dPartCount = (strpos($val1, ".")) ? substr($val1, 0 , strpos($val1, ".")).substr($val1, strrpos ($val1, ".") , 3) : $val1 ;        
                $val2 = total($datos1, "dTotalParts");
                    $dTotalParts = (strpos($val2, ".")) ? substr($val2, 0 , strpos($val2, ".").substr($val2, strrpos ($val2, ".") , 3) ): $val2 ;    
                $val3 = total($datos1, "dScrapParts");
                    $dScrapParts = (strpos($val3, ".")) ? substr($val3, 0 , strpos($val3, ".")).substr($val3, strrpos ($val3, 3)) : $val3 ;
                
                $html .='<tr>
                        <th scope="row">'.$sShortName.'</th>
                        <td>'.$sPartId.'</td>
                        <td>'.$tStart.'</td>
                        <td>'.$tEnd.'</td>
                        <td>'.$dPartCount.'</td>
                        <td>'.$dTotalParts.'</td>
                        <td>'.$dScrapParts.'</td>
                    </tr>';
            }
					
		}
    $html.= "</tbody></table>";
	} 	
}

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');
ini_set("memory_limit","128M");
// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('report_scrap'.date('Ymd').'.pdf"');

?>