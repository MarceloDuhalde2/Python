<?php 	include "../base/header.php"; 
		include "../../coingecko_api_library.php";
?>
<?php
	if (isset($_POST['submit'])) {
		//aca tengo que ver si no hay una operacion abierta para el mismo proyecto
		$name = $_POST["nombre"];
		$coins = search_coins_id_by_name($name);
	}
?>
<style>
	td, th {
		padding: 5px;
	}
</style>
<script>
	$(document).ready(function() {
		$('#table1').DataTable( {
			"order": [[0, "asc" ]],
			"pageLength": 50,
		} );
	} );
	
</script>		
<form class='form' method='post'>
	<!-- no olvidar agregar datos del proyecto-->
	<label for='nombre'>Nombre:</label><br>
	<input class="form-control" type='text' id='nombre' name='nombre'>
	<br>
	<input class='btn btn-info' type='submit' name='submit' value='Buscar' />
</form>
<br>	
<?php if(!empty($coins)){ ?>
	<table id="table1" class="table table-striped table-bordered" style="width:100%">
		<thead>
			<tr>
				<th>Id</th>
				<th>Symbol</th>
				<th>Name</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($coins as $key => $coin) {?>	
				<tr>
					<td><?php echo $coin->id; ?></td>
					<td><?php echo $coin->symbol; ?></td>
					<td><?php echo $coin->name; ?></td>
					<td><?php print_r("
						<a href='/portfolio_crypto/web/posiciones/new_position.php?coingecko_id=".$coin->id."&symbol=".$coin->symbol."' class='btn btn-info' role='button' target='_blank'>Nueva Posicion</a>
						"); ?></td>
				</tr>	
			<?php } ?>
		</tbody>
	</table>
<?php } ?>
<?php include "../base/footer.php"; ?>