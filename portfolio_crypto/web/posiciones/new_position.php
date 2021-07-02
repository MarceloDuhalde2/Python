<?php 	include "../base/header.php"; 
		include "../../coingecko_api_library.php"; ?>
<?php
	if (isset($_POST['submit'])) {
		$sql2 = "INSERT INTO `positions` (`token`, `id_wallet`, `cantidad_tokens`, `total_comprado`, `estado`, `id_coingecko`) VALUES ('".$_POST['token']."', '".$_POST['id_wallet']."', '".$_POST['cantidad_tokens']."', '".$_POST['total_comprado']."', '1', '".$_POST['id_coingecko']."');";
		if ($conn->query($sql2) != TRUE) {return "error sql: ".$sql2;}
		$last_id = $conn->insert_id;
		$sql2 = "INSERT INTO `positions_log` (`id_posicion`, `tipo`, `tokens`, `monto_usd`, `date`)
		VALUES ('".$last_id."', '1', '".$_POST["cantidad_tokens"]."', '".$_POST["total_comprado"]."', '".date("Y-m-d H:i:s")."');";
		if ($conn->query($sql2) != TRUE) {return "error sql: ".$sql2;}
		header('Location: index.php');
	}
	if(isset($_GET["coingecko_id"])){
		$sql = "SELECT * FROM `wallets` `wal`;";
		$wallets = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
	}else{
		echo "NO SE ENCUENTRA EL ID COINGECKO.";
	}
?>
<style>
	td, th {
		padding: 5px;
	}
</style>	
<form class='form' method='post'>
	<h3>Nueva Posicion: <?php echo $_GET['symbol']; ?></h3>
	<input type='hidden' name='token' value='<?php echo $_GET["symbol"];?>' />
	<label for='cantidad_tokens'>Cantidad Tokens:</label><br>
	<input class="form-control" type='text' id='cantidad_tokens' name='cantidad_tokens' value='200'>
	<br>
	<label>Wallet:</label><br>
	<select class="form-control" id="id_wallet" name="id_wallet">
		<?php 	foreach ($wallets as $key => $wallet) {
					echo '<option name="'.$wallet["nombre"].'" value="'.$wallet["id"].'">'.$wallet["nombre"].'</option>';
				}
		?>
	</select>
	<br>
	<label for='total_comprado'>Total Comprado (USD):</label><br>
	<input class="form-control" type='text' id='total_comprado' name='total_comprado' value='50'>
	<br>
	<label for='id_coingecko'>Id Coingecko:</label><br>
	<input class="form-control" type='text' id='id_coingecko' name='id_coingecko' value='<?php echo $_GET["coingecko_id"];?>'>
	<br>
	<input class='btn btn-info' type='submit' name='submit' value='Ingresar' />
</form>	
<?php include "../base/footer.php"; ?>
