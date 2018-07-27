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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">
	<title>FTMetrics</title>	
  </head>
  <body>
    <div class="container">
    	<div class="jumbotron">
			<div class="row">
				<h1 class='text-center display-1 col'>FTMetrics</h1>
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
			//consulta por defecto
			$query = defaultQuery();
			$prep = sqlsrv_prepare($conn,$query);
			
			if ( $resultado = sqlsrv_execute($prep) ) { 
		?>
				<!--inicio de la tabla-->
				<table class="table table-striped">
					<!--encabezados de la pagina-->
					<thead>
						<tr class='text-center' >
						<th scope="col">Machine</th>
						<th scope="col">Part Id</th>
						<th scope="col">Good Parts</th>
						<th scope="col">Total Parts</th>
						<th scope="col">Scrap Parts</th>
						</tr>
					</thead>
					<tbody>
					<!--construccion de las celdas-->
					<?php while ($fila = sqlsrv_fetch_array($prep)){				

						//si la contiene algun valor en la columna de dScrapParts sera resaltada
						if ($fila['dScrapParts'] != 0 || $fila['dTotalParts'] == 0){
							?>
								<!--celda en caso de que ya haya una modificicion del Scrap-->
								<tr class="table-info text-center" >
								<th scope="row"><?php echo $fila['sShortName'] ?></th>
									<td><?php echo $fila['sPartId'] ?></td>
									<td><?php echo (strpos($fila['dPartCount'], '.')) ? 
													substr($fila['dPartCount'], 0 , strpos($fila['dPartCount'], '.'))
													.substr($fila['dPartCount'], strpos($fila['dPartCount'], '.'), 3 ) : 
													substr($fila['dPartCount'], strpos($fila['dPartCount'], '.'), 3 ) ; ?></td>
									<td><?php echo (strpos($fila['dTotalParts'], '.')) ? 
													substr($fila['dTotalParts'], 0 , strpos($fila['dTotalParts'], '.'))
													.substr($fila['dTotalParts'], strpos($fila['dTotalParts'], '.'), 3 ) : 
													substr($fila['dTotalParts'], strpos($fila['dTotalParts'], '.'), 3 ) ; ?></td>
									<td><?php echo (strpos($fila['dScrapParts'], '.')) ? 
													substr($fila['dScrapParts'], 0 , strpos($fila['dScrapParts'], '.'))
													.substr($fila['dScrapParts'], strpos($fila['dScrapParts'], '.'), 3 ) : 
													substr($fila['dScrapParts'], strpos($fila['dScrapParts'], '.'), 3 ) ; ?></td>
								</tr>
							<?php 
							continue;
						} ?>				
						<!--celdas normales-->
						<tr class='text-center'>
							<th scope="row"><?php echo $fila['sShortName'] ?></th>
							<td><?php echo $fila['sPartId'] ?></td>
							<td><?php echo (strpos($fila['dPartCount'], '.')) ? 
											substr($fila['dPartCount'], 0 , strpos($fila['dPartCount'], '.'))
											.substr($fila['dPartCount'], strpos($fila['dPartCount'], '.'), 3 ) : 
											$fila['dPartCount'] ; ?></td>
							<td><?php echo (strpos($fila['dTotalParts'], '.')) ? 
											substr($fila['dTotalParts'], 0 , strpos($fila['dTotalParts'], '.'))
											.substr($fila['dTotalParts'], strpos($fila['dTotalParts'], '.'), 3 ) : 
											$fila['dTotalParts'] ; ?></td>
							<td><?php echo (strpos($fila['dScrapParts'], '.')) ? 
											substr($fila['dScrapParts'], 0 , strpos($fila['dScrapParts'], '.'))
											.substr($fila['dScrapParts'], strpos($fila['dScrapParts'], '.'), 3 ) : 
											$fila['dScrapParts'] ; ?></td>
						</tr>
					<?php
					} 					
					?>
			<?php
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
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.min.js" integrity="sha384-o+RDsa0aLu++PJvFqy8fFScvbHFLtbvScb8AjopnFD+iEQ7wo/CG0xlczd+2O/em" crossorigin="anonymous"></script>
  </body>
</html>