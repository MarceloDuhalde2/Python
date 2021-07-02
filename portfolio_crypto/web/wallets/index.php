<?php include "../base/header.php"; ?>
<?php
	$sql = "SELECT * FROM `wallets`";
	$wallets = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
?>
<script>
	$(document).ready(function() {
		$('#table1').DataTable( {
			"order": [[0, "asc" ]],
			"pageLength": 50,
		} );
	} );
	
</script>	
<?php if(!empty($wallets)){ ?>
	<div class="row">
		<div class="col-sm">
			<div class="panel panel-default">
				<div class="panel-heading">
					Wallets:
				</div>
				<div class="panel-body">
					<table id="table1" class="table table-striped table-bordered" style="width:100%">
						<thead>
							<tr>
								<th>Id</th>
								<th>Nombre</th>
								<th>Direccion</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($wallets as $key => $wall) {
							?>	
								<tr>
									<td><?php echo $wall['id']; ?></td>
									<td><?php echo $wall['nombre']; ?></td>
									<td><?php echo $wall['direccion']; ?></td>
									<td><?php print_r("
									<a href='/portfolio_crypto/web/wallets/view_wallet.php?id=".$wall['id']."' class='btn btn-info' role='button' target='_blank'>Ver</a>
									<a href='/portfolio_crypto/web/wallets/edit_wallet.php?id=".$wall['id']."' class='btn btn-info' role='button' target='_blank'>Editar</a>
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


