<?php
require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../conectado.php';

$q = trim($_GET['q'] ?? '');
$where = "";
$params = [];
if ($q !== '') {
  $where = "WHERE c.nome LIKE ? OR u.nome LIKE ? OR v.condicao_pagamento LIKE ?";
  $like = "%$q%";
  $params = [$like,$like,$like];
}

$sql = "SELECT v.id, v.data_venda, v.valor_total, v.condicao_pagamento,
               c.nome AS cliente, u.nome AS vendedor
        FROM vendas v
        JOIN clientes c ON c.id = v.cliente_id
        JOIN usuarios u ON u.id = v.usuario_id
        " . $where . "
        ORDER BY v.data_venda DESC";

$stmt = mysqli_prepare($connect, $sql);
if ($where) { mysqli_stmt_bind_param($stmt, "sss", ...$params); }
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html><html lang="pt-br"><head><meta charset="utf-8"><title>Vendas - Listar</title>
<style>body{font-family:system-ui;margin:20px}table{border-collapse:collapse;width:100%}th,td{border:1px solid #ddd;padding:8px}</style>
</head><body>
<h1>Vendas</h1>
<form method="get" style="margin-bottom:12px">
  <input type="text" name="q" placeholder="Cliente, vendedor ou condição" value="<?= htmlspecialchars($q) ?>">
  <button>Filtrar</button>
  <a href="../index.php">Voltar</a>
</form>
<table>
  <thead><tr>
    <th>#</th><th>Data</th><th>Cliente</th><th>Vendedor</th><th>Condição</th><th>Total</th><th>Ações</th>
  </tr></thead>
  <tbody>
  <?php while($row = mysqli_fetch_assoc($res)): ?>
    <tr>
      <td><?= (int)$row['id'] ?></td>
      <td><?= htmlspecialchars($row['data_venda']) ?></td>
      <td><?= htmlspecialchars($row['cliente']) ?></td>
      <td><?= htmlspecialchars($row['vendedor']) ?></td>
      <td><?= htmlspecialchars($row['condicao_pagamento']) ?></td>
      <td>R$ <?= number_format((float)$row['valor_total'],2,',','.') ?></td>
      <td>
        <a href="visualizar.php?id=<?= (int)$row['id'] ?>">Ver</a> |
        <a href="editar.php?id=<?= (int)$row['id'] ?>">Editar</a> |
        <a href="excluir.php?id=<?= (int)$row['id'] ?>" onclick="return confirm('Excluir esta venda? Isso apagará os itens.');">Excluir</a>
      </td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
</body></html>
