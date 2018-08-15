<?php session_start(); ?>
<!doctype html>

<?php

if ( !(isset($_SESSION['opcion1'])) ){
	if ( !(isset($_POST['inicio'])) || $_POST['inicio'] == "" || !(isset($_POST['final'])) || $_POST['final'] == ""){
		header("Location:"."/FTMetrics/FTMetrics-comercial/public/");
	}else{
		$_SESSION['opcion1'] = $_POST['seleccion'];
		$_SESSION['fechaI1'] = str_replace("T"," ",$_POST['inicio']).":00.000" ;
		$_SESSION['fechaF1'] = str_replace("T"," ",$_POST['final']).":00.000" ;
	}
} elseif ( isset($_POST['inicio']) && $_POST['inicio'] == "" ) {
	$_SESSION['opcion1'] = $_POST['seleccion'];
	$_SESSION['fechaI1'] = $_POST['inicio'];
	$_SESSION['fechaF1'] = $_POST['final'];
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
			onclick="location='/FTMetrics/FTMetrics-comercial/public/'"> &#10094; back</button>
			<div class="btn-group float-right" role="group" aria-label="Basic example">
				<form action="../connection/csv.php" method="post">
					<input type="hidden" name="opcion" value="<?php echo $_SESSION['opcion1']; ?>">
					<input type="hidden" name="inicio" value="<?php echo $_SESSION['fechaI1']; ?>">
					<input type="hidden" name="final" value="<?php echo $_SESSION['fechaF1']; ?>">
					<button class="btn btn-outline-primary" type="submit">&#8659; csv</button>
				</form>
				<form action="../connection/xls.php" method="post">
					<input type="hidden" name="opcion" value="<?php echo $_SESSION['opcion1']; ?>">
					<input type="hidden" name="inicio" value="<?php echo $_SESSION['fechaI1']; ?>">
					<input type="hidden" name="final" value="<?php echo $_SESSION['fechaF1']; ?>">
					<button class="btn btn-outline-primary" type="submit">&#8659; xls</button>
				</form>
				<form action="../connection/pdf.php" method="post">
					<input type="hidden" name="opcion" value="<?php echo $_SESSION['opcion1']; ?>">
					<input type="hidden" name="inicio" value="<?php echo $_SESSION['fechaI1']; ?>">
					<input type="hidden" name="final" value="<?php echo $_SESSION['fechaF1']; ?>">
					<button class="btn btn-outline-primary" type="submit">&#8659; pdf</button>
				</form>
			</div>
					<h1 class='text-center display-1'><a href="./index.php"> FTMetrics</a></h1>
					<h1 class="text-center display-4">Scrap Management</h1>
					<img src="image.png" style="display:block; margin:auto;">
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
														echo (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.')).substr($val, strrpos ($val, ".") , 3) : $val; ?></td>
											<td><?php $val = total($datos1, 'dTotalParts');
														echo (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.')).substr($val, strrpos ($val, ".") , 3) : $val; ?></td>
											<td><?php $val = total($datos1, 'dScrapParts');
														echo (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.')).substr($val, strrpos ($val, 3)) : $val; ?></td>
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
								<form action="modify.php" method="post">
											<th scope="row"><?php echo $datos1[0]['sShortName'] ?></th>
											<td><?php echo $datos1[0]['sPartId']; ?></td>
											<td><?php echo  substr($datos1[0]['tStart']->date, 0, 19); ?></td>
											<td><?php echo substr(end($datos1)['tEnd']->date, 0, 19); ?></td>
											<td><?php $val = total($datos1, 'dPartCount');
														echo (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.')).substr($val, strrpos ($val, ".") , 3) : $val; ?></td>
											<td><?php $val = total($datos1, 'dTotalParts');
														echo (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.')).substr($val, strrpos ($val, ".") , 3) : $val; ?></td>
											<td><?php $val = total($datos1, 'dScrapParts');
														echo (strpos($val, '.')) ? substr($val, 0 , strpos($val, '.')).substr($val, strrpos ($val, 3)) : $val; ?></td>
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