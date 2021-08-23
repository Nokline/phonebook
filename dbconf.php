<?php

$dbserver = "";
$username = "";
$password = "";
$dbname = "";


$connect = mysqli_connect($dbserver, $username, $password, $dbname);

if(!$connect){
	die("Connection failed: ".$connect->connect_error);
}


?>

