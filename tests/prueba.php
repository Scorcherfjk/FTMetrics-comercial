<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css" integrity="sha384-Smlep5jCw/wG7hdkwQ/Z5nLIefveQRIY9nfy6xoR1uRYBtpZgI6339F5dgvm/e9B" crossorigin="anonymous">

<?php

include('./conexion.php');
include('./consultas.php');


$opcion = "1000030";
$inicio = "2015-03-09 10:15:00.000";
$final = "2015-03-10 11:15:00.000";

$query = primaryQuery($opcion,$inicio,$final);
$prep = sqlsrv_prepare($conn,$query);
if ( $resultado = sqlsrv_execute($prep) ) { 
	?>
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

					//pasando las fechas a variables
					json_encode($fila);
					$fechaInicio = substr($fila['tStart']->date, 0, 19);
					$fechaFinal = substr($fila['tEnd']->date, 0, 19);

					//si la contiene algun valor en la columna de dScrapParts sera resaltada
					if ($fila['dScrapParts'] != 0 || $fila['dTotalParts'] == 0){
						?>

							<!--celda en caso de que ya haya una modificicion del Scrap-->
							<tr class="table-danger text-center" >
							<th scope="row"><?php echo $fila['sShortName'] ?></th>
								<td><?php echo $fila['sPartId'] ?></td>
								<td><?php echo $fechaInicio ?></td>
								<td><?php echo $fechaFinal ?></td>
								<td><?php echo $fila['dPartCount'] ?></td>
								<td><?php echo $fila['dTotalParts'] ?></td>
								<td><?php echo $fila['dScrapParts'] ?></td>
								<td>can't modify</td>
							</tr>

						<?php 
						continue;
					} ?>

					<!--celdas normales-->
					<tr class='text-center'>
						<form action="modify.php" method="post">
							<th scope="row"><?php echo $fila['sShortName'] ?></th>
							<td><?php echo $fila['sPartId'] ?></td>
							<td><?php echo $fechaInicio ?></td>
							<td><?php echo $fechaFinal ?></td>
							<td><?php echo $fila['dPartCount'] ?></td>
							<td><?php echo $fila['dTotalParts'] ?></td>
							<td><?php echo $fila['dScrapParts'] ?></td>
							<input type="hidden" id="seleccion" name="seleccion" value="<?php echo $fila['lOEEConfigWorkCellId'] ; ?>">
							<input type="hidden"  id="inicio" name="inicio" value="<?php echo $fechaInicio ; ?>">
							<input type="hidden" id="final" name="final" value="<?php echo $fechaFinal ; ?>">						
							<td>
								<input type="hidden" name="id" value="<?php echo  str_replace(" ","",$fila['sShortName']." _ ".$fila['sPartId']." _ ".$fila['lOEEWorkCellId']) ; ?>">
								<input class="btn btn-dark btn-sm" type="submit" value="modify">
							</td>
						</form>
					</tr>
				<?php
				} 					
				?>
		<?php
		} ?>