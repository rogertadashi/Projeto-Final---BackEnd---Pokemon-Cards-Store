<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$password = "";
$db_name = "pokestore";

$connect = mysqli_connect($servername, $username, $password, $db_name);

if (!$connect) {
    die("Falha na conexão: " . mysqli_connect_error());
}
?>
