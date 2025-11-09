<?php
include("../conexao.php");
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM clientes WHERE id=$id");
header("Location: listar.php");
?>
