<?php session_start(); ?>
<!doctype html>
<html lang="es">
    <head>

    <?php

    if ($_POST['inicio'] == "" || $_POST['final'] == ""){
        header("Location:"."/FTMetrics/FTMetrics-comercial/public/");
    }else{
        $_SESSION['opcion3'] = $_POST['seleccion'];
        $_SESSION['fechaI3'] = $_POST['inicio'];
        $_SESSION['fechaF3'] = $_POST['final'];
    }

    require('../connection/conexion.php');
    require('../connection/consultas.php');
    require('../connection/funciones.php');

    $opcion = $_SESSION['opcion3'];
    $inicio = $_SESSION['fechaI3'];
    $final = $_SESSION['fechaF3'];

    if ( isset($_POST['addScrap']) && $_POST['addScrap'] != "" ){
        header("Location:"."/FTMetrics/FTMetrics-comercial/public/");
    }else{
        $_POST['seleccion'] = $_SESSION['opcion3'];
        $_POST['inicio'] = $_SESSION['fechaI3'];
        $_POST['final'] = $_SESSION['fechaF3'];
    }
    $ff = array();

    ?>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <title>FTMetrics - Modify</title>
    </head>
    <body>
        <div class="container">
            <div class="jumbotron">
            <div class="row">
                <div class="col">
                    <h1 class='text-center display-1'>FTMetrics</h1>
                    <h1 class="text-center display-4">Scrap Management</h1>
                </div>
                <div class="col">
                    <img src="image.png" style="display:block; margin:auto;">
                </div>
		    </div>
            </div>

        <?php

        $query = primaryQuery($opcion,$inicio,$final);
        $prep = sqlsrv_prepare($conn,$query);
        $resultado = sqlsrv_execute($prep);

        while ($fila = sqlsrv_fetch_array($prep)){
            $ff[] = $fila;
        }

        foreach ($ff as $position => $value) {
            $datos1[] = $value;
            if (count($ff)-1 > $position){
                if ($ff[$position]['sPartId'] != $ff[$position+1]['sPartId'] ){
                    $datos1 = array();
                }
            }else{
            json_encode($datos1); ?>

            <h1 class="text-center"> Add Scrap to <cite><?php echo($datos1[0]['sShortName']." - ".$datos1[0]['sPartId']); ?></cite></h1><br>	
            <ul class="nav justify-content-center">
                <li class="nav-item">
                    <h5 class="nav-link"> Good Parts: <code><?php $val1 = total($datos1, 'dPartCount');
                    echo (strpos($val1, '.')) ? substr($val1, 0 , strpos($val1, '.'))
                    .substr($val1, strrpos ($val1, ".") , 3) : $val1; ?></code></h5>
                </li>
                <li class="nav-item">
                    <h5 class="nav-link"> Total Parts: <code><?php $val2 = total($datos1, 'dTotalParts');
                    echo (strpos($val2, '.')) ? substr($val2, 0 , strpos($val2, '.'))
                    .substr($val2, strrpos ($val2, ".") , 3) : $val2; ?></code></h5>
                <li class="nav-item">
                    <h5 class="nav-link"> Scrap Parts: <code><?php $val3 = total($datos1, 'dScrapParts');
                    echo (strpos($val3, '.')) ? substr($val3, 0 , strpos($val3, '.'))
                    .substr($val3, strrpos ($val3, 3)) : $val3; ?></code></h5> 
                </li>
            </ul>
            <br><br>
            <?php if ($val2 != 0){ ?>

            <form class="form-inline justify-content-center" method="POST" action="../connection/update.php">
                <div class="form-group mb-2">
                    <label for="Scrap" class="sr-only">Scrap</label>
                    <input type="text" readonly class="form-control-plaintext" id="Scrap" value="Quantity to add to scrap: ">
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="addScrap" class="sr-only">addScrap</label>
                    <input required type="number" class="form-control" name="addScrap" id="addScrap" placeholder="Scrap">
                    <input type="hidden" name="lOEEWorkCellId" value="<?php  echo end($datos1)['lOEEWorkCellId']; ?>">
                    <input type="hidden" name="dtotalParts" value="<?php  echo $val2 ?>">
                </div>
                <button type="submit" class="btn btn-primary mb-2">Add Scrap</button>
            </form>
            
            <?php }else{ ?>

            <form class="form-inline justify-content-center" method="POST" action="../connection/update.php">
                <div class="form-group mb-2">
                    <label for="Scrap" class="sr-only">Scrap</label>
                    <input type="text" readonly class="form-control-plaintext" id="Scrap" value="Quantity to add to scrap: ">
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label for="addScrap" class="sr-only">addScrap</label>
                    <input readonly required type="number" class="form-control" name="addScrap" id="addScrap" placeholder="Scrap">
                    <input type="hidden" name="lOEEWorkCellId" value="<?php  echo end($datos1)['lOEEWorkCellId']; ?>">
                    <input type="hidden" name="dtotalParts" value="<?php  echo $val2 ?>">
                </div>
                <button disabled type="submit" class="btn btn-primary mb-2">Add Scrap</button>
            </form>

            <div class="alert alert-danger text-center" style="max-width:510px; margin:auto;" role="alert">
            can't modify because there is'n production
            </div>

            <?php
            }
                if ($val3 != 0){ ?>
                    <div class="alert alert-danger text-center" style="max-width:510px; margin:auto;" role="alert">
                        the current machine already has scrap
                    </div>
            <?php   
                }
            }

        }

        ?>

        <br><button type="button" class="btn btn-primary btn-lg btn-block"
        style="max-width:510px; margin:auto;" 
        onclick="location='/FTMetrics/FTMetrics-comercial/public/search.php'">back</button>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="../bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>