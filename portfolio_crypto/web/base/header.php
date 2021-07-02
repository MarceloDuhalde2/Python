<!DOCTYPE html>
<html>
	<head>
		<title>Portfolio</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">
		<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
		<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
		<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
		
		<script src="https://getbootstrap.com/docs/3.3/dist/js/bootstrap.min.js"></script>
		<style>
		.btn-warning {
			color: #000;
		}
		</style>	
	</head>	
<?php
include '../base/db_config.php';
$conn = OpenCon();
?>
<body>
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="navbar-header">
				<a class="navbar-brand" href="../posiciones/index.php">Portfolio</a>
			</div>
			<ul class="nav navbar-nav">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Posiciones <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="../posiciones/index.php">Listar Posiciones</a></li>
					<li><a href="../posiciones/new_position.php">Nueva Posicion</a></li>
				</ul>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">Wallets <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="../wallets/index.php">Listar Wallets</a></li>
					<li><a href="../wallets/new_wallet.php">Nueva Wallet</a></li>
				</ul>
			</li>
			<li><a href="../search/search_coin.php">Buscar Tokens</a></li>
		</div>
	</nav>
	<div class="container" style="width:100%">