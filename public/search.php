<?php session_start(); ?>
<!doctype html>

<?php

if ( !(isset($_SESSION['opcion1'])) ){
	if ( !(isset($_POST['inicio'])) || $_POST['inicio'] == "" || !(isset($_POST['final'])) || $_POST['final'] == ""){
		header("Location:"."/FTMetrics/php/public/");
	}else{
		$_SESSION['opcion1'] = $_POST['seleccion'];
		$_SESSION['fechaI1'] = str_replace("T"," ",$_POST['inicio']).":00.000" ;
		$_SESSION['fechaF1'] = str_replace("T"," ",$_POST['final']).":00.000" ;
	}
}

	require('../connection/conexion.php');
	require('../connection/consultas.php');
	require('../connection/funciones.php');
	$ff = array();

?>

<html lang="es">
  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

	<title>FTMetrics - Search</title>
  </head>
  <body>
    <div class="container">
	
    	<div class="jumbotron">
		<button type="button" class="btn btn-primary" 
			onclick="location='/FTMetrics/FTMetrics-comercial/public/'">back</button>
			<div class="row">
				
				<h1 class='text-center display-1 col'><a href="./index.php"> FTMetrics</a></h1>

				<!--inicio del formulario-->
				<form name="form1" method="post" action="search.php" style="max-width:300px; margin:auto;" class="col">

					<!--ventana de seleccion de opciones-->
					<div class="form-group">
						<label for="seleccion">Machine</label>
						<input type="hidden" name="seleccion" id="seleccion" value="1000025">
						<input class="form-control" value="Entubadora" type="text" readonly>
					</div>

					<!--fecha inicial-->
					<div class="form-group">
						<label for="inicio">Start Time</label>
						<input require id="inicio" name="inicio" class="form-control" type="datetime-local" autofocus>
					</div>

					<!--fecha final-->
					<div class="form-group">
						<label for="final">End Time</label>
						<input require id="final" name="final" class="form-control" type="datetime-local">
					</div>

					<!--botones del formulario-->
					<button class="btn btn-primary" type="submit" value="Submit" name="button" id="button">Search</button>
					<button class="btn btn-primary" type="reset" value="reset" name="button2" id="button2">Reset</button>
				</form>
			</div>
    	</div>
		<?php

		//validando conexion
		if( isset( $conn ) ) {

			//construyendo la consulta de la tabla

			//formateo de las variables para la consulta
			$opcion = $_SESSION['opcion1'];
			$inicio = $_SESSION['fechaI1'];
			$final = $_SESSION['fechaF1'];
			
			//consulta al pasar la fecha
			$query = primaryQuery($opcion,$inicio,$final);
			$prep = sqlsrv_prepare($conn,$query);
			
			if ( $resultado = sqlsrv_execute($prep) ) { 
		?>
				<!--inicio de la tabla-->
				<table class="table table-striped">

					<!--encabezados de la tabla-->
					<thead>
						<tr class='text-center' >
							<th scope="col">Machine</th>
							<th scope="col">Part Id</th>
							<th scope="col">Start Time</th>
							<th scope="col">End Time</th>
							<th scope="col">Good Parts</th>
							<th scope="col">Total Parts</th>
							<th scope="col">Scrap Parts</th>
							<th scope="col">Modify</th>
						</tr>
					</thead>
					<tbody>
						
					<!--construccion de las celdas-->
					<?php while ($fila = sqlsrv_fetch_array($prep)){
						$ff[] = $fila;
					}

				foreach ($ff as $position => $value) {

					$datos1[] = $value;
					if (count($ff)-1 > $position){

						if ($ff[$position]['sPartId'] != $ff[$position+1]['sPartId'] ){
							
							json_encode($datos1); ?>
									<tr class='text-right'>
										<form action="modify.php" method="post">
											<th scope="row"><?php echo $datos1[0]['sShortName'] ?></th>
											<td><?php echo $datos1[0]['sPartId']; ?></td>
											<td><?php echo  substr($datos1[0]['tStart']->date, 0, 19); ?></td>
											<td><?php echo substr(end($datos1)['tEnd']->date, 0, 19); ?></td>
											<td><?php $val = total($datos1, 'dPartCount');
														echo (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.'))
															.substr($val, strrpos ($val, ".") , 3) : $val; ?></td>
											<td><?php $val = total($datos1, 'dTotalParts');
														echo (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.'))
															.substr($val, strrpos ($val, ".") , 3) : $val; ?></td>
											<td><?php $val = total($datos1, 'dScrapParts');
														echo (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.'))
															.substr($val, strrpos ($val, 3)) : $val; ?></td>
											<input type="hidden" id="seleccion" name="seleccion" value="<?php echo $datos1[0]['lOEEConfigWorkCellId']; ?>">
											<input type="hidden"  id="inicio" name="inicio" value="<?php echo substr($datos1[0]['tStart']->date, 0, 19); ?>">
											<input type="hidden" id="final" name="final" value="<?php echo substr(end($datos1)['tEnd']->date, 0, 19); ?>">						
											<td>
												<input class="btn btn-dark btn-sm" type="submit" value="modify">
											</td>
										</form>
									</tr>

								<?php
							$datos1 = array();
						}

					}else{
						json_encode($datos1); ?>
									<tr class='text-right'>
										<form action="searchSpecific.php" method="post">
											<th scope="row"><?php echo $datos1[0]['sShortName'] ?></th>
											<td><?php echo $datos1[0]['sPartId']; ?></td>
											<td><?php echo  substr($datos1[0]['tStart']->date, 0, 19); ?></td>
											<td><?php echo substr(end($datos1)['tEnd']->date, 0, 19); ?></td>
											<td><?php $val = total($datos1, 'dPartCount');
														echo (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.'))
															.substr($val, strrpos ($val, ".") , 3) : $val; ?></td>
											<td><?php $val = total($datos1, 'dTotalParts');
														echo (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.'))
															.substr($val, strrpos ($val, ".") , 3) : $val; ?></td>
											<td><?php $val = total($datos1, 'dScrapParts');
														echo (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.'))
															.substr($val, strrpos ($val, 3)) : $val; ?></td>
											<input type="hidden" id="seleccion" name="seleccion" value="<?php echo $datos1[0]['lOEEConfigWorkCellId']; ?>">
											<input type="hidden"  id="inicio" name="inicio" value="<?php echo substr($datos1[0]['tStart']->date, 0, 19); ?>">
											<input type="hidden" id="final" name="final" value="<?php echo substr(end($datos1)['tEnd']->date, 0, 19); ?>">						
											<td>
												<input class="btn btn-dark btn-sm" type="submit" value="modify">
											</td>
										</form>
									</tr> <?php
					}
					
				}

			} 	

		}else{

			//en caso de la conexion falle
			echo "Conexión no se pudo establecer.<br />";
			die( "<strong>el error ha sido : </strong>".print_r( sqlsrv_errors(), true));

		}
			?>
	</div>
	
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="../bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>