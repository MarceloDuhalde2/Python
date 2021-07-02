<?php include "../base/header.php"; ?>
<?php
	if (isset($_POST['submit'])) {
		//aca tengo que ver si no hay una operacion abierta para el mismo proyecto
		$sql2 = "INSERT INTO `wallets` (`nombre`, `direccion`) VALUES ('".$_POST['nombre']."', '".$_POST['direccion']."');";
		if ($conn->query($sql2) != TRUE) {return "error sql: ".$sql2;}
		header('Location: index.php');
	}
?>
<style>
	td, th {
		padding: 5px;
	}
</style>	
<form class='form' method='post'>
	<!-- no olvidar agregar datos del proyecto-->
	<label for='nombre'>Nombre:</label><br>
	<input class="form-control" type='text' id='nombre' name='nombre'>
	<br>
	<label for='direccion'>Direccion:</label><br>
	<input class="form-control" type='text' id='direccion' name='direccion'>
	<br>
	<input class='btn btn-info' type='submit' name='submit' value='Insertar Wallet' />
</form>	
<?php include "../base/footer.php"; ?>