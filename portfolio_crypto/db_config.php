<?php

function OpenCon(){
	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "root";
	$db = "portfolio_tracker";

	$conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);
	$conn->query('SET GLOBAL connect_timeout=200000');
	$conn->query('SET GLOBAL wait_timeout=200000');
	$conn->query('SET GLOBAL interactive_timeout=200000');
	return $conn;
}
 
function CloseCon($conn)
 {
 $conn -> close();
 }
   
?>
