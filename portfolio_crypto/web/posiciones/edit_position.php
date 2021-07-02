<?php 	include "../base/header.php"; 
		include "../../coingecko_api_library.php"; ?>
<?php
	if (isset($_POST['submit'])) {
		$sql2 = "UPDATE `positions` SET `id_wallet`='".$_POST['id_wallet']."', `estado`='".$_POST['estado']."', `cantidad_tokens`='".$_POST['cantidad_tokens']."', `total_comprado`='".$_POST['total_comprado']."', `id_coingecko`='".$_POST['id_coingecko']."'  WHERE `id`= '".$_POST["position_id"]."';";
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
	<h3>Editar Posicion: <?php echo $position['token']; ?></h3>
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
	<input class="form-control" type='text' id='cantidad_tokens' name='cantidad_tokens' value='<?php echo $position['cantidad_tokens'];?>'>
	<br>
	<label>Wallet:</label><br>
	<select class="form-control" id="id_wallet" name="id_wallet">
		<?php 	foreach ($wallets as $key => $wallet) {
					if($wallet["id"] == $position['id_wallet'])
						$selected = "selected";
					else
						$selected = "";
					echo '<option name="'.$wallet["nombre"].'" value="'.$wallet["id"].'" '.$selected.'>'.$wallet["nombre"].'</option>';
				}
		?>
	</select>
	<br>
	<label for='total_comprado'>Total Comprado (USD):</label><br>
	<input class="form-control" type='text' id='total_comprado' name='total_comprado' value='<?php echo $position['total_comprado'];?>'>
	<br>
	<label for='id_coingecko'>Id Coingecko:</label><br>
	<input class="form-control" type='text' id='id_coingecko' name='id_coingecko' value='<?php echo $position['id_coingecko'];?>'>
	<br>
	<input type="hidden" name="estado" value="0" />
	<input class="form-check-input" type="checkbox" name="estado" value="1" <?php if($position['estado']==1) echo 'checked';?>>
	<label class="form-check-label" for="estado">Estado</label> 
	<br>
	<br>
	<input class='btn btn-info' type='submit' name='submit' value='Actualizar' />
</form>	
<?php include "../base/footer.php"; ?>
