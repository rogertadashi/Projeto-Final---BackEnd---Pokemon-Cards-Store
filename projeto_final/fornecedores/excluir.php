<?php
require_once("../conexao.php");
require_once("../conectado.php");

$funcao = $_SESSION['funcao'] ?? 'Cliente';
if (!in_array($funcao, ['Administrador', 'Vendedor'], true)) {
    $_SESSION['flash'] = 'Apenas administradores e vendedores podem excluir fornecedores.';
    header('Location: listar.php');
    exit;
}
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$mensagem = '';
$erro = '';

if ($id <= 0) {
    $erro = 'Fornecedor inválido.';
} else {
    $stmt = $conn->prepare("SELECT nome FROM fornecedores WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $fornecedor = $res->fetch_assoc();

    if (!$fornecedor) {
        $erro = 'Fornecedor não encontrado.';
    } else {
        $nomeFornecedor = $fornecedor['nome'];

        $del = $conn->prepare("DELETE FROM fornecedores WHERE id = ?");
        $del->bind_param("i", $id);

        if ($del->execute()) {
            $mensagem = "Fornecedor <strong>" . htmlspecialchars($nomeFornecedor) . "</strong> excluído com sucesso!";
        } else {
            $erro = 'Erro ao excluir fornecedor. Tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Fornecedor</title>
    <style>
        body {
            font-family: system-ui, Arial;
            background: #0b0b0b;
            color: #eaeaea;
            margin: 20px;
        }

        .card {
            max-width: 520px;
            margin-top: 20px;
            padding: 20px 24px;
            background: #111;
            border-radius: 10px;
            box-shadow: 0 0 0 1px #222;
        }

        h2 {
            margin-top: 0;
            margin-bottom: 16px;
        }

        .msg-ok {
            padding: 10px 12px;
            border-radius: 6px;
            background: #022c22;
            border: 1px solid #16a34a;
            color: #bbf7d0;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }

        .msg-erro {
            padding: 10px 12px;
            border-radius: 6px;
            background: #3b0d0d;
            border: 1px solid #f97373;
            color: #fecaca;
            margin-bottom: 12px;
            font-size: 0.95rem;
        }

        .btn {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-back {
            background: #2563eb;
            color: #fff;
        }

        .btn-back:hover {
            background: #1d4ed8;
        }

        a {
            color: #93c5fd;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="card">
        <h2>Excluir Fornecedor</h2>

        <?php if ($mensagem): ?>
            <div class="msg-ok">
                <?= $mensagem ?>
            </div>
        <?php endif; ?>

        <?php if ($erro): ?>
            <div class="msg-erro">
                <?= $erro ?>
            </div>
        <?php endif; ?>

        <p>
            <a href="listar.php" class="btn btn-back">
                ← Voltar para a lista de fornecedores
            </a>
        </p>
    </div>

</body>
</html>
