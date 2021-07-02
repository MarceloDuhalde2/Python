<?php 	include "../base/header.php"; 
		include "../../coingecko_api_library.php"; ?>
<?php
	if(isset($_GET["id"])){
		$position_id = $_GET["id"]; 	
		$sql = "SELECT `pos`.* FROM `positions` `pos` WHERE `pos`.`id`=".$position_id.";";
		$position = $conn->query($sql)->fetch_all(MYSQLI_ASSOC)[0];
		$sql = "SELECT `pos_log`.* FROM `positions_log` `pos_log` WHERE `pos_log`.`id_posicion`=".$position_id." ORDER BY date asc;";
		$logs = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
	}
?>
<style>
	td, th {
		padding: 5px;
	}
</style>
<div class="panel panel-default">
	<div class="panel-heading">
		Token: <?php echo $position['token']; ?>
	</div>
	<div class="panel-body">
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
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		Logs
	</div>
	<div class="panel-body">
		<table id="table1" class="table table-striped table-bordered" style="width:100%">
			<thead>
				<tr>
					<th>Tipo</th>
					<th>Cantidad Tokens</th>
					<th>Monto (USD)</th>
					<th>Av. Price</th>
					<th>Fecha</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					foreach ($logs as $key => $log) {
						$average_price = number_format($log['monto_usd']/$log['tokens'],6);
						if($log["tipo"]==1){
							$tipo = "Compra";
							$back = "#e2f7dd";
						}else{
							$tipo = "Venta";
							$back = "#f59f9f";
						}	
				?>	
					<tr  style='background-color:<?php echo $back;?>'>
						<td><?php echo $tipo; ?></td>
						<td><?php echo number_format($log['tokens'],2); ?></td>
						<td><?php echo number_format($log['monto_usd'],2); ?></td>
						<td><?php echo number_format($average_price,6); ?></td>
						<td><?php echo $log["date"]; ?></td>
					</tr>
					<?php } ?>	
			</tbody>
		</table>
	</div>
</div>	
<?php include "../base/footer.php"; ?>
