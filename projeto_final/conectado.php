<?php
require_once 'conexao.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit;
}

$tipo = $_SESSION['tipo_usuario'] ?? '';

if ($tipo === 'usuario') {
    $id = $_SESSION['id_usuario'] ?? null;

    $sql = "SELECT nome, funcao FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $dados = mysqli_fetch_assoc($resultado);
    } else {
        session_destroy();
        header("Location: login.php");
        exit;
    }
} elseif ($tipo === 'cliente') {
    $id = $_SESSION['id_cliente'] ?? null;

    $sql = "SELECT nome, email FROM clientes WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $dados = mysqli_fetch_assoc($resultado);
        $dados['funcao'] = 'Cliente';
    } else {
        session_destroy();
        header("Location: login.php");
        exit;
    }
} else {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Página Restrita</title>
</head>

<body>
    <h1>Bem-vindo(a), <?php echo htmlspecialchars($dados['nome']); ?>!</h1>
    <?php if (isset($dados['funcao']) && ($dados['funcao'] === 'Administrador' || $dados['funcao'] === 'Vendedor')): ?>
        <p>Função: <?= htmlspecialchars($dados['funcao']) ?></p>
    <?php endif; ?>


    <?php if ($tipo === 'cliente'): ?>
        <a href="../index.php">Ir para a loja</a>
    <?php else: ?>
        <a href="../index.php">Painel Administrativo</a>
    <?php endif; ?>

    <br><br>
    <a href="../logout.php">Sair</a>
</body>

</html>