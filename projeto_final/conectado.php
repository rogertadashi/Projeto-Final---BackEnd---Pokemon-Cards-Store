<?php

require_once 'conexao.php';

if(!isset($_SESSION)){
    session_start();
}

$id=$_SESSION['id_usuario'];
$sql="select *from usuarios where id ='$id'";

$resultado= mysqli_query($connect, $sql);


$dados= mysqli_fetch_array($resultado);

?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>Pagina Restrita </title>
    </head>
    <body>
        <h1>login</h1>
        <h1><?php echo $dados['nome'];?></h1>
        <a href="logout.php">Sair</a>
        
        
    </body>
    
    
</html>



