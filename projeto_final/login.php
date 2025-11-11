<?php
session_start();
include("conexao.php");

if (isset($_POST['btn-entrar'])) {
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);

    if (empty($login) || empty($senha)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        // Procura usuário pelo login
        $sql = "SELECT * FROM usuarios WHERE login = ?";
        $stmt = mysqli_prepare($connect, $sql);
        mysqli_stmt_bind_param($stmt, "s", $login);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $usuario = mysqli_fetch_assoc($result);

            // Verifica senha
            if (password_verify($senha, $usuario['senha']) || $senha === $usuario['senha']) {
                $_SESSION['logado'] = true;
                $_SESSION['id_usuario'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];

                header("Location: index.php");
                exit;
            } else {
                $erro = "Senha incorreta!";
            }
        } else {
            $erro = "Usuário não encontrado!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<link rel="stylesheet" href="./style_2.css"/>
<head>
    <meta charset="UTF-8">
    <title>Login PokéStore</title>
</head>

<body>
    <h1>Login de Acesso</h1>

    <?php if (isset($erro))
        echo "<p style='color:red;'>$erro</p>"; ?>

    <form action="login.php" method="POST">
        <label>Login:</label>
        <input type="text" name="login"><br><br>
        <label>Senha:</label>
        <input type="password" name="senha"><br><br>
        <button type="submit" name="btn-entrar">Entrar</button>
    </form>
</body>

</html>