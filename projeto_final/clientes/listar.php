<?php
require_once("../conexao.php");
require_once("../conectado.php");

// =============================
// Permissões
// =============================
$funcao = $_SESSION['funcao'] ?? 'Cliente';

// quem pode editar (Admin e Vendedor)
$podeEditar  = in_array($funcao, ['Administrador', 'Vendedor'], true);
$podeExcluir = in_array($funcao, ['Administrador', 'Vendedor'], true);

// =============================
// Busca clientes
// =============================
$result = mysqli_query($conn, "SELECT * FROM clientes ORDER BY nome ASC");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Clientes</title>
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

        button,
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

    <h2>Lista de Clientes</h2>

    <?php if ($podeEditar): ?>
        <p>
            <a href="cadastrar.php" class="btn-green">
                + Cadastrar Cliente
            </a>
        </p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>CPF</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= (int)$row['id'] ?></td>
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['telefone']) ?></td>
                <td><?= htmlspecialchars($row['cpf']) ?></td>
                <td>
                    <?php if ($podeEditar): ?>
                        <a class="btn-edit" href="editar.php?id=<?= (int)$row['id'] ?>">Editar</a>
                    <?php endif; ?>

                    <?php if ($podeExcluir): ?>
                        <?php if ($podeEditar): ?>&nbsp;<?php endif; ?>
                        <a class="btn-delete"
                           href="excluir.php?id=<?= (int)$row['id'] ?>"
                           onclick="return confirm('Tem certeza que deseja excluir este cliente?');">
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
