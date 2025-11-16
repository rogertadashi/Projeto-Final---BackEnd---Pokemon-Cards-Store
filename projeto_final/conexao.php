<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "pokestore";
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
    die(" Falha na conexão com o banco de dados: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");
$connect = $conn;
