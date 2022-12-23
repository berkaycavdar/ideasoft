<?php 

$host = "localhost";
$dbname = "ideasoft";
$user_name = "root";
$pass = "";


$DB = new PDO("mysql:host=".$host.";dbname=".$dbname.";charset=UTF8", $user_name, $pass);

if (mysqli_connect_errno()) {
	echo mysqli_connect_error()."not connect";
	die();
}
