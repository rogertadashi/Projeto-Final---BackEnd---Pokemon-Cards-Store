<?php

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$db_name = "pokestore";

$connect = mysqli_connect($servername, $username, $password, $db_name);

if (isset($_POST['btn-entrar'])) {
    $login = mysqli_escape_string($connect, $_POST['login']);
    $senha = mysqli_escape_string($connect, $_POST['senha']);
    if (empty($login) or empty($senha)) {
        echo 'Favor preencher os dados';
    } else {
        $sql = "select login from usuarios where login= '$login'";
        $resultado = mysqli_query($connect, $sql);
        if (mysqli_num_rows($resultado) > 0) {
            $senha = md5($senha);
            $sql = "select * from usuarios where login = '$login' and senha = '$senha'";
            $resultado = mysqli_query($connect, $sql);
            if (mysqli_num_rows($resultado) == 1) {
                $dados = mysqli_fetch_array($resultado);
                $_SESSION['logado'] = true;
                $_SESSION["id_usuario"] = $dados['ID'];

                header('location:conectado.php');

            }
        } else {
            echo "usuario inexistente";
        }
    }
}

if (mysqli_connect_error() == true) {
    echo "Falha na conexao" . mysqli_connect_error();
}
