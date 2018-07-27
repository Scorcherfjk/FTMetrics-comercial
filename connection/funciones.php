<?php

function total($array, $columName) 
{   
    $valor = floatval(array_sum(array_column($array, $columName)));
    return strval($valor);
}

function validationSetNullDual($var1, $var2)
{
    if (isset($var1) && $var1 != "" && isset($var2) && $var2 != ""){
        return true;
    }
    else{
        return false;        
    }
}

function validationSetNullSimple($var1)
{
    if (isset($var1) && $var1 != ""){
        return true;
    }
    else{
        return false;        
    }
}

function validar($array1, $array2)
{

    if ($array1 != []){
        json_encode($array1);
        $Id = $array1[0]['lOEEConfigWorkCellId'] ;
        $shortName = $array1[0]['sShortName'] ;
        $partId = $array1[0]['sPartId'] ;
        $fechaI = substr($array1[0]['tStart']->date, 0, 19);
        $fechaF = substr(end($array1)['tEnd']->date, 0, 19); 
        $dTotalParts = $val = total($array1, 'dTotalParts');
                        (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.'))
                        .substr($val, strrpos ($val, ".") , 3):  $val;
        $dScrapParts = $val = total($array1, 'dScrapParts');
                        (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.'))
                        .substr($val, strrpos ($val, ".") , 3):  $val;
        $dPartCount = $val = total($array1, 'dPartCount');
                        (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.'))
                        .substr($val, strrpos ($val, ".") , 3):  $val;
        $array = array( 'lOEEConfigWorkCellId' => $Id, 
                        'sShortName' => $shortName,
                        'sPartId' =>  $partId,
                        'tStart' => $fechaI,
                        'tEnd' => $fechaF,
                        'dTotalParts' => $dTotalParts,
                        'dScrapParts' =>  $dScrapParts,
                        'dPartCount' =>  $dPartCount
                    );

        return $array;

    } 
    elseif ($array2 != []){

        json_encode($array2);
        $Id = $array2[0]['lOEEConfigWorkCellId'] ;
        $shortName = $array2[0]['sShortName'] ;
        $partId = $array2[0]['sPartId'] ;
        $fechaI = substr($array2[0]['tStart']->date, 0, 19);
        $fechaF = substr(end($array2)['tEnd']->date, 0, 19);
        $dTotalParts = $val = total($array2, 'dTotalParts');
                        (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.'))
                        .substr($val, strrpos ($val, ".") , 3):  $val;
        $dScrapParts = $val = total($array2, 'dScrapParts');
                        (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.'))
                        .substr($val, strrpos ($val, ".") , 3):  $val;
        $dPartCount = $val = total($array2, 'dPartCount');
                        (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.'))
                        .substr($val, strrpos ($val, ".") , 3):  $val;
        $array = array( 'lOEEConfigWorkCellId' => $Id, 
                        'sShortName' => $shortName,
                        'sPartId' =>  $partId,
                        'tStart' => $fechaI,
                        'tEnd' => $fechaF,
                        'dTotalParts' => $dTotalParts,
                        'dScrapParts' =>  $dScrapParts,
                        'dPartCount' =>  $dPartCount
                    );

        return $array;
    }
}
?>