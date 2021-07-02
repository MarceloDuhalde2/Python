<?php 	include "../base/header.php"; 
		include "../../coingecko_api_library.php"; ?>
<?php
	if (isset($_POST['submit'])) {
		$sql = "SELECT * FROM `positions` WHERE `estado`=1 AND `id`=".$_POST['position_id'].";";
		$pos = $conn->query($sql)->fetch_all(MYSQLI_ASSOC)[0];
		$cantidad_tokens = number_format($_POST["cantidad_tokens"] + $pos['cantidad_tokens'],8,".","");
		$monto_comprado = number_format($_POST["total_comprado"] + $pos['total_comprado'],8,".","");
		$sql2 = "UPDATE `positions` SET `cantidad_tokens`='".$cantidad_tokens."', `total_comprado`='".$monto_comprado."' WHERE `id`= '".$pos["id"]."';";
		if ($conn->query($sql2) != TRUE) {return "error sql: ".$sql2;}
		$sql2 = "INSERT INTO `positions_log` (`id_posicion`, `tipo`, `tokens`, `monto_usd`, `date`)
		VALUES ('".$pos["id"]."', '1', '".$_POST["cantidad_tokens"]."', '".$_POST["total_comprado"]."', '".date("Y-m-d H:i:s")."');";
		if ($conn->query($sql2) != TRUE) {return "error sql: ".$sql2;}
		header('Location: index.php');
	}
	if(isset($_GET["id"])){
		$position_id = $_GET["id"]; 	
		$sql = "SELECT `pos`.* FROM `positions` `pos` WHERE `pos`.`id`=".$position_id.";";
		$position = $conn->query($sql)->fetch_all(MYSQLI_ASSOC)[0];
		$sql = "SELECT * FROM `wallets` `wal`;";
		$wallets = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
		}else{
		echo "NO SE ENCUENTRA LA OPERACION.";
	}
?>
<style>
	td, th {
		padding: 5px;
	}
</style>	
<form class='form' method='post'>
	<h3>Agregar Posicion a: <?php echo $position['token']; ?></h3>
	<table id="table1" class="table table-striped table-bordered" style="width:100%">
		<thead>
			<tr>
				<th>Cantidad de Tokens</th>
				<th>Monto Total</th>
				<th>Av. Price</th>
				<th>Ultimo Precio</th>
				<th>Ganancia %</th>
				<th>Ganancia en Usd</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				$ultimo_precio = get_last_price($position["id_coingecko"]);
				$average_price = number_format($position['total_comprado']/$position['cantidad_tokens'],6);
				if($average_price != 0)
					$percentage = number_format(($ultimo_precio * 100 / $average_price) - 100,2)." %";
				else
					$percentage = "-";
				$win_usd = number_format(($ultimo_precio * $position["cantidad_tokens"] - $position['total_comprado']),2);
				if($win_usd > 0)
					$back = "#e2f7dd";
				if($win_usd < 0)
					$back = "#f59f9f";
				if($win_usd == 0)
					$back = "#e6e36e";				?>	
				<tr style='background-color:<?php echo $back;?>'>
					<td><?php echo number_format($position['cantidad_tokens'],2); ?></td>
					<td><?php echo number_format($position['total_comprado'],2); ?></td>
					<td><?php echo $average_price; ?></td>
					<td><?php echo $ultimo_precio; ?></td>
					<td><?php echo $percentage; ?></td>
					<td><?php echo $win_usd; ?></td>
				</tr>
		</tbody>
	</table>
	<!-- no olvidar agregar datos del proyecto-->
	<input type='hidden' name='position_id' value='<?php echo $position_id;?>' />
	<label for='cantidad_tokens'>Cantidad Tokens:</label><br>
	<input class="form-control" type='text' id='cantidad_tokens' name='cantidad_tokens' value='<?php echo "200";?>'>
	<br>
	<label for='total_comprado'>Total Comprado (USD):</label><br>
	<input class="form-control" type='text' id='total_comprado' name='total_comprado' value='<?php echo $position['total_comprado'];?>'>
	<br>
	<input class='btn btn-info' type='submit' name='submit' value='Agregar' />
</form>	
<?php include "../base/footer.php"; ?>
