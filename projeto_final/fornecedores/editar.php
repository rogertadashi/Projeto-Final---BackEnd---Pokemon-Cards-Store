<?php
require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../conectado.php';
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) die('ID inválido');
$sel = mysqli_prepare($connect, "SELECT * FROM fornecedores WHERE id=?");
mysqli_stmt_bind_param($sel, "i", $id);
mysqli_stmt_execute($sel);
$f = mysqli_fetch_assoc(mysqli_stmt_get_result($sel));
if (!$f) die('Fornecedor não encontrado');
$erro = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nome = trim($_POST['nome'] ?? '');
  $contato = trim($_POST['contato'] ?? '');
  $telefone = trim($_POST['telefone'] ?? '');
  $email = trim($_POST['email'] ?? '');
  if ($nome === '') $erro = 'Informe o nome';
  if (!$erro) {
    $up = mysqli_prepare($connect, "UPDATE fornecedores SET nome=?,contato=?,telefone=?,email=? WHERE id=?");
    mysqli_stmt_bind_param($up, "ssssi", $nome, $contato, $telefone, $email, $id);
    mysqli_stmt_execute($up);
    header("Location: listar.php");
    exit;
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <title>Editar Fornecedor</title>
</head>
<style>
        body {
            font-family: system-ui, Arial;
            background: #0b0b0b;
            color: #eaeaea;
            margin: 20px;
        }

        h2 {
            color: #93c5fd;
        }

        form {
            background: #111;
            padding: 20px;
            border-radius: 10px;
            width: 350px;
            box-shadow: 0 0 10px #000;
        }

        input,
        button {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #333;
            background: #1a1a1a;
            color: #eaeaea;
        }

        input:focus {
            border-color: #2563eb;
            outline: none;
        }

        button {
            background: #16a34a;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #15803d;
        }
<body>
  <h1>Editar Fornecedor</h1>
  <?php if ($erro) echo "<p style='color:red'>$erro</p>"; ?>
  <form method="post">
    <label>Nome:</label><input name="nome" value="<?= htmlspecialchars($f['nome']) ?>"><br><br>
    <label>Contato:</label><input name="contato" value="<?= htmlspecialchars($f['contato']) ?>"><br><br>
    <label>Telefone:</label><input name="telefone" value="<?= htmlspecialchars($f['telefone']) ?>"><br><br>
    <label>Email:</label><input name="email" value="<?= htmlspecialchars($f['email']) ?>"><br><br>
    <button>Salvar</button>
    <a href="listar.php">Cancelar</a>
  </form>
</body>

</html>