<?php
require_once("../conexao.php");
require_once("../conectado.php");

// =============================
// Permissões
// =============================
$funcao = $_SESSION['funcao'] ?? 'Cliente';

// só Administrador acessa essa tela
if ($funcao !== 'Administrador') {
    $_SESSION['flash'] = 'Apenas administradores podem acessar a lista de usuários.';
    header('Location: ../index.php');
    exit;
}

$podeEditar  = in_array($funcao, ['Administrador', 'Vendedor'], true);
$podeExcluir = in_array($funcao, ['Administrador', 'Vendedor'], true);

// =============================
// Busca usuários
// =============================
$result = mysqli_query($conn, "SELECT * FROM usuarios ORDER BY nome ASC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
    <style>
        body {
            font-family: system-ui, Arial;
            background: #0b0b0b;
            color: #eaeaea;
            margin: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #111;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            border-bottom: 1px solid #222;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #161616;
        }

        a {
            color: #93c5fd;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .btn-edit,
        .btn-delete,
        .btn-green {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: inline-block;
            font-size: 0.9rem;
        }

        .btn-edit {
            background: #2563eb;
            color: #fff;
        }

        .btn-delete {
            background: #dc2626;
            color: #fff;
        }

        .btn-green {
            background: #16a34a;
            color: #fff;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>

    <h2>Lista de Usuários</h2>

    <p>
        <a href="cadastrar.php" class="btn-green">
            + Cadastrar Usuário
        </a>
    </p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Usuário</th>
                <th>Função</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= (int)$row['id'] ?></td>
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td><?= htmlspecialchars($row['login']) ?></td>
                <td><?= htmlspecialchars($row['funcao']) ?></td>
                <td>
                    <?php if ($podeEditar): ?>
                        <a class="btn-edit" href="editar.php?id=<?= (int)$row['id'] ?>">Editar</a>
                    <?php endif; ?>

                    <?php if ($podeExcluir): ?>
                        &nbsp;
                        <a class="btn-delete"
                           href="excluir.php?id=<?= (int)$row['id'] ?>"
                           onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                            Excluir
                        </a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
