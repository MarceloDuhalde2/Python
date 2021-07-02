<?php include "../base/header.php"; ?>
<?php
	if(isset($_GET["id"])){
		$wallet_id = $_GET["id"]; 	
		$sql = "SELECT * FROM `wallets` WHERE `id`=".$wallet_id.";";
		$wallet = $conn->query($sql)->fetch_all(MYSQLI_ASSOC)[0];
	}
?>
<style>
	td, th {
		padding: 5px;
	}
</style>
<div class="panel panel-default">
	<div class="panel-heading">
		Wallet: <?php echo $wallet['nombre']; ?>
	</div>
	<div class="panel-body">
		<table id="table1" class="table table-striped table-bordered" style="width:100%">
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Direccion</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $wallet['nombre']; ?></td>
					<td><?php echo $wallet['direccion']; ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<?php include "../base/footer.php"; ?>
