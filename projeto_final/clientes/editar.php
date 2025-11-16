<?php
require_once("../conexao.php");
require_once("../conectado.php");

$funcao = $_SESSION['funcao'] ?? 'Cliente';
if (!in_array($funcao, ['Administrador', 'Vendedor'], true)) {
    $_SESSION['flash'] = 'Você não tem permissão para editar clientes.';
    header('Location: listar.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    $_SESSION['flash'] = 'Cliente inválido.';
    header('Location: listar.php');
    exit;
}

$mensagem = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome']     ?? '');
    $login    = trim($_POST['login']    ?? '');
    $email    = trim($_POST['email']    ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $cpf      = trim($_POST['cpf']      ?? '');
    $senha    = trim($_POST['senha']    ?? ''); 

    if ($nome === '' || $login === '') {
        $mensagem = '<p class="erro">Nome e login são obrigatórios.</p>';
    } else {
        if ($senha !== '') {
            $sql = "UPDATE clientes
                       SET nome = ?, login = ?, email = ?, telefone = ?, cpf = ?, senha = ?
                     WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $nome, $login, $email, $telefone, $cpf, $senha, $id);
        } else {
            $sql = "UPDATE clientes
                       SET nome = ?, login = ?, email = ?, telefone = ?, cpf = ?
                     WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $nome, $login, $email, $telefone, $cpf, $id);
        }

        if ($stmt && $stmt->execute()) {
            $_SESSION['flash'] = 'Cliente atualizado com sucesso.';
            header('Location: listar.php');
            exit;
        } else {
            $mensagem = '<p class="erro">Erro ao atualizar cliente. Tente novamente.</p>';
        }
    }
}

$stmt = $conn->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$cliente = $res->fetch_assoc();

if (!$cliente) {
    $_SESSION['flash'] = 'Cliente não encontrado.';
    header('Location: listar.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <style>
        body {
            font-family: system-ui, Arial;
            background: #0b0b0b;
            color: #eaeaea;
            margin: 20px;
        }

        .container {
            max-width: 480px;
            background: #111;
            padding: 20px 24px;
            border-radius: 10px;
            box-shadow: 0 0 0 1px #222;
        }

        h2 {
            margin-top: 0;
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        input {
            width: 100%;
            padding: 8px 10px;
            margin-bottom: 12px;
            border-radius: 6px;
            border: 1px solid #333;
            background: #0b0b0b;
            color: #eaeaea;
        }

        input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        .btn-primary {
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            background: #16a34a;
            color: #fff;
            font-weight: 500;
        }

        .btn-primary:hover {
            background: #15803d;
        }

        .erro {
            color: #f97373;
            margin-bottom: 10px;
        }

        .voltar {
            display: inline-block;
            margin-bottom: 12px;
            font-size: 0.9rem;
        }

        .voltar a {
            color: #93c5fd;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Editar Cliente</h2>

        <p class="voltar">
            <a href="listar.php">&larr; Voltar para a lista de clientes</a>
        </p>

        <?php if ($mensagem): ?>
            <?= $mensagem ?>
        <?php endif; ?>

        <form method="POST">
            <label for="nome">Nome</label>
            <input
                type="text"
                id="nome"
                name="nome"
                value="<?= htmlspecialchars($cliente['nome']) ?>"
                required
            >

            <label for="login">Login</label>
            <input
                type="text"
                id="login"
                name="login"
                value="<?= htmlspecialchars($cliente['login']) ?>"
                required
            >

            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($cliente['email']) ?>"
            >

            <label for="telefone">Telefone</label>
            <input
                type="text"
                id="telefone"
                name="telefone"
                value="<?= htmlspecialchars($cliente['telefone']) ?>"
            >

            <label for="cpf">CPF</label>
            <input
                type="text"
                id="cpf"
                name="cpf"
                value="<?= htmlspecialchars($cliente['cpf']) ?>"
            >

            <label for="senha">Nova senha (opcional)</label>
            <input
                type="password"
                id="senha"
                name="senha"
                placeholder="Deixe em branco para manter a atual"
            >

            <button type="submit" class="btn-primary">
                 Salvar Alterações
            </button>
        </form>
    </div>

</body>
</html>
