<?php include "../base/header.php"; ?>
<?php
	if (isset($_POST['submit'])) {
		$sql2 = "UPDATE `wallets` SET `nombre`='".$_POST['nombre']."', `direccion`='".$_POST['direccion']."' WHERE `id`= '".$_POST["wallet_id"]."';";
		if ($conn->query($sql2) != TRUE) {return "error sql: ".$sql2;}
		header('Location: index.php');
	}
	if(isset($_GET["id"])){
		$wallet_id = $_GET["id"]; 	
		$sql = "SELECT * FROM `wallets` WHERE `id`=".$wallet_id.";";
		$wallet = $conn->query($sql)->fetch_all(MYSQLI_ASSOC)[0];
}else{
		echo "NO SE ENCUENTRA LA WALLET.";
	}
?>
<style>
	td, th {
		padding: 5px;
	}
</style>	
<form class='form' method='post'>
	<h3>Editar Wallet: <?php echo $wallet['nombre']; ?></h3>
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
	<!-- no olvidar agregar datos del proyecto-->
	<input type='hidden' name='wallet_id' value='<?php echo $wallet_id;?>' />
	<label for='nombre'>Nombre:</label><br>
	<input class="form-control" type='text' id='nombre' name='nombre' value='<?php echo $wallet['nombre'];?>'>
	<br>
	<label for='direccion'>Direccion:</label><br>
	<input class="form-control" type='text' id='direccion' name='direccion' value='<?php echo $wallet['direccion'];?>'>
	<br>
	<input class='btn btn-info' type='submit' name='submit' value='Actualizar Wallet' />
</form>	
<?php include "../base/footer.php"; ?>
