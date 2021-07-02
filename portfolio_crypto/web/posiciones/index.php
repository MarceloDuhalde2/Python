<?php 	include "../base/header.php"; 
		include "../../coingecko_api_library.php"; ?>
<?php
	$sql = "SELECT `pos`.*, `wal`.`nombre` as `nombre_wallet`, `sym`.`last_close_usd` as `last_close_usd` FROM `positions` `pos` inner join `wallets` `wal` on `pos`.`id_wallet` = `wal`.`id` inner join `symbols` `sym` on `pos`.`id_coingecko` = `sym`.`id_coingecko`";
	$open_positions = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>
<script>
	$(document).ready(function() {
		$('#table1').DataTable( {
			"order": [[0, "asc" ]],
			"pageLength": 50,
		} );
	} );
	
</script>	
<?php if(!empty($open_positions)){ ?>
	<div class="row">
		<div class="col-sm">
			<div class="panel panel-default">
				<div class="panel-heading">
					Posiciones:
				</div>
				<div class="panel-body">
					<table id="table1" class="table table-striped table-bordered" style="width:100%">
						<thead>
							<tr>
								<th>Token</th>
								<th>Wallet</th>
								<th>Cantidad tokens</th>
								<th>Av Price</th>
								<th>Actual Price</th>
								<th>Ganancia %</th>
								<th>Ganancia Usd</th>
								<th>Tenencia Total</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($open_positions as $key => $open) {
								$ultimo_precio = $open["last_close_usd"];
								$average_price = number_format($open['total_comprado']/$open['cantidad_tokens'],8);
								if($average_price != 0)
									$percentage = number_format(($ultimo_precio * 100 / $average_price) - 100,2)." %";
								else
									$percentage = "-";	
								$win_usd = number_format(($ultimo_precio * $open["cantidad_tokens"] - $open['total_comprado']),2);
								$tenencia_total = number_format($ultimo_precio * $open["cantidad_tokens"],2);
								if($win_usd > 0)
									$back = "#e2f7dd";
								if($win_usd < 0)
									$back = "#f59f9f";
								if($win_usd == 0)
									$back = "#e6e36e";
							?>	
								<tr style='background-color:<?php echo $back;?>'>
									<td><?php echo strtoupper($open['token']); ?></td>
									<td><?php echo $open['nombre_wallet']; ?></td>
									<td><?php echo $open['cantidad_tokens']; ?></td>
									<td><?php echo $average_price; ?></td>
									<td><?php echo $ultimo_precio; ?></td>
									<td><?php echo $percentage; ?></td>
									<td><?php echo $win_usd; ?></td>
									<td><?php echo $tenencia_total; ?></td>
									<td><?php print_r("
									<a href='https://www.coingecko.com/es/monedas/".$open['id_coingecko']."' class='btn btn-primary' role='button' target='_blank'>Datos</a>
									<a href='https://www.coingecko.com/es/monedas/".$open['id_coingecko']."#markets' class='btn btn-primary' role='button' target='_blank'>Exchanges</a>
									<a href='/portfolio_crypto/web/posiciones/view_position.php?id=".$open['id']."' class='btn btn-info' role='button' target='_blank'>Ver</a>
									<a href='/portfolio_crypto/web/posiciones/edit_position.php?id=".$open['id']."' class='btn btn-info' role='button' target='_blank'>Editar</a>
									<a href='/portfolio_crypto/web/posiciones/add_position.php?id=".$open['id']."' class='btn btn-info' role='button' target='_blank'>Agregar</a>
									<a href='/portfolio_crypto/web/posiciones/close_position.php?id=".$open['id']."' class='btn btn-danger' role='button' target='_blank'>Vender</a> 
									"); ?></td>	
								</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php if(!empty($closed_operations)){ ?>
	<div class="row">
		<div class="col-sm">
			<div class="panel panel-default">
				<div class="panel-heading">
					Cerradas:
				</div>
				<div class="panel-body">
					<table id="table1" class="table table-striped table-bordered" style="width:100%">
						<thead>
							<tr>
								<th>Proyecto</th>
								<th>Tokens</th>
								<th>Compra (USD)</th>
								<th>Venta (USD)</th>
								<th>Ganancia</th>
								<th>Porcentaje</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($closed_operations as $key => $closed) {
								$sql = "SELECT * FROM `operaciones_log` WHERE `id_operacion`='".$closed["id"]."';";
								$operations_log = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
								$monto_compra = 0;
								$monto_venta = 0;
								$cantidad_tokens = 0;
								foreach ($operations_log as $log) {
									if($log["tipo"]==1){
										//compra
										$monto_compra = $monto_compra + $log["monto_usd"];
										$cantidad_tokens = $cantidad_tokens + $log["tokens"];
									}else{
										//venta
										$monto_venta = $monto_venta + $log["monto_usd"];
									}
								}
								$ganancia = number_format($monto_venta - $monto_compra,2);
								$percentage = number_format(($monto_venta * 100 / $monto_compra) - 100,2);
							?>	
								<tr style='background-color:<?php echo $back;?>'>
									<td><?php echo $closed['nombre_proyecto']; ?></td>
									<td><?php echo number_format($cantidad_tokens,2); ?></td>
									<td><?php echo number_format($monto_compra,2); ?></td>
									<td><?php echo number_format($monto_venta,2); ?></td>
									<td><?php echo $ganancia; ?></td>
									<td><?php echo $percentage." %"; ?></td>
									<td><?php print_r("
									<a href='https://www.sesocio.com/projects/".$closed['nombre_categoria']."/".$closed['desc_url']."/news' class='btn btn-primary' role='button' target='_blank'>Noticias</a>
									<a href='/sesocio_tracker/web/operaciones/ver_operacion.php?id=".$closed['id']."' class='btn btn-info' role='button' target='_blank'>Ver</a>
									<a href='/sesocio_tracker/web/operaciones/editar_operacion.php?id=".$closed['id']."' class='btn btn-info' role='button' target='_blank'>Editar</a>
									<a href='/sesocio_tracker/web/operaciones/operar.php?id=".$closed['proyecto_id']."' class='btn btn-info' role='button' target='_blank'>Agregar</a>
									<a href='/sesocio_tracker/web/operaciones/close_operacion.php?id=".$closed['id']."' class='btn btn-danger' role='button' target='_blank'>Vender</a> 
									"); ?></td>	
								</tr>
							<?php
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php include "../base/footer.php"; ?>


