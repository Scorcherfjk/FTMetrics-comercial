<?php if (session_start()){session_destroy();} ?>
<!doctype html>

<?php

	require('../connection/conexion.php');
	require('../connection/consultas.php');

?>

<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
	<title>FTMetrics</title>	
  </head>
  <body>
    <div class="container">
    	<div class="jumbotron">
				<h1 class='text-center display-1'>FTMetrics</h1>
				<h1 class="text-center display-4">Scrap Management</h1>
				<img src="image.png" style="display:block; margin:auto;">
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
		<?php
		//validando conexion
		if( !(isset( $conn )) ) 
		{
			//en caso de la conexion falle
			echo "Conexión no se pudo establecer.<br />";
			die( "<strong>el error ha sido : </strong><br/>".print_r( sqlsrv_errors(), true));
		}
			?>
	</div>
	
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="../bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>