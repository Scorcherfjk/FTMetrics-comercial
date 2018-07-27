<!doctype html>

<?php

	require('../connection/conexion.php');
	require('../connection/consultas.php');
	require('../connection/funciones.php');
	
	$contador = array();
	$datos1 = array();
	$ff = array();

?>

<html lang="es">
  <head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
    
	<title>FTMetrics</title>
  </head>
  <body>
    <div class="container">
		<?php

		//validando conexion
		if( isset( $conn ) ) {

			//construyendo la consulta de la tabla

			//formateo de las variables para la consulta
			$opcion = "1000025";
			$inicio = "2018-07-01 00:00:00.000";
			$final = "2018-07-02 00:00:00.000";
			
			//consulta al pasar la fecha
			$query = primaryQuery($opcion,$inicio,$final);
			$prep = sqlsrv_prepare($conn,$query);
			
			if ( $resultado = sqlsrv_execute($prep) ) { ?>
				<!--inicio de la tabla-->
				<table class="table table-striped">

					<!--encabezados de la tabla-->
					<thead>
						<tr class='text-center' >
							<th scope="col">Short Name</th>
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
			echo "La conexión no se pudo establecer.<br />";
			die( "<strong>el error ha sido : </strong>".print_r( sqlsrv_errors(), true));

		}
			?>
	</div>
	
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
  </body>
</html>