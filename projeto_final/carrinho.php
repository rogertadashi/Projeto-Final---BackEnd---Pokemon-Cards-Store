<?php
require_once __DIR__ . '/conexao.php';
if (!isset($_SESSION)) session_start();

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = []; // [carta_id => qtd]

function back() { header("Location: carrinho.php"); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';
  if ($action === 'update') {
    $id  = (int)($_POST['id'] ?? 0);
    $qty = (int)($_POST['qty'] ?? 1);
    if ($id > 0) {
      if ($qty <= 0) unset($_SESSION['cart'][$id]);
      else $_SESSION['cart'][$id] = $qty;
    }
    back();
  }
  if ($action === 'remove') {
    $id = (int)($_POST['id'] ?? 0);
    unset($_SESSION['cart'][$id]);
    back();
  }
  if ($action === 'clear') {
    $_SESSION['cart'] = [];
    back();
  }
  if ($action === 'checkout') {
    header("Location: vendas/cadastrar.php");
    exit;
  }
}

// Monta lista de produtos do carrinho
$items = $_SESSION['cart'];
$ids = array_keys($items);
$rows = [];
$total = 0.0;

if ($ids) {
  $ids = array_map('intval', $ids);
  $in  = implode(',', $ids);
  $sql = "SELECT id, codigo, nome, valor FROM cartas WHERE id IN ($in)";
  $res = $connect->query($sql);
  while ($r = $res->fetch_assoc()) {
    $qtd = (int)($items[$r['id']] ?? 0);
    if ($qtd <= 0) continue;
    $linha = $qtd * (float)$r['valor'];
    $total += $linha;
    $rows[] = [
      'id' => (int)$r['id'],
      'codigo' => $r['codigo'],
      'nome' => $r['nome'],
      'valor' => (float)$r['valor'],
      'qtd' => $qtd,
      'linha' => $linha
    ];
  }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Carrinho</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
  body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;margin:20px;background:#0b0b0b;color:#eaeaea}
  table{width:100%;border-collapse:collapse;background:#111;border:1px solid #1f1f1f;border-radius:12px;overflow:hidden}
  th,td{padding:10px;border-bottom:1px solid #1f1f1f}
  th{text-align:left;background:#0f0f0f}
  .actions{display:flex;gap:8px;align-items:center}
  .qty{width:70px;padding:6px 8px;border-radius:8px;border:1px solid #333;background:#121212;color:#eee}
  .row{display:flex;gap:8px;margin-top:14px}
  .btn{padding:8px 12px;border:0;border-radius:10px;cursor:pointer}
  .btn-gray{background:#1f2937;color:#fff}
  .btn-red{background:#ef4444;color:#fff}
  .btn-green{background:#16a34a;color:#fff}
  .right{display:flex;justify-content:flex-end}
</style>
</head>
<body>
  <h1>Carrinho</h1>
  <p><a style="color:#93c5fd" href="index.php">← Continuar comprando</a></p>

  <?php if (!$rows): ?>
    <p>Seu carrinho está vazio.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Código</th>
          <th>Nome</th>
          <th>Preço</th>
          <th>Qtd</th>
          <th>Subtotal</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($rows as $it): ?>
        <tr>
          <td><?= htmlspecialchars($it['codigo']) ?></td>
          <td><?= htmlspecialchars($it['nome']) ?></td>
          <td>R$ <?= number_format($it['valor'], 2, ',', '.') ?></td>
          <td>
            <form method="post" class="actions" action="carrinho.php">
              <input type="hidden" name="id" value="<?= $it['id'] ?>">
              <input class="qty" type="number" name="qty" min="0" value="<?= $it['qtd'] ?>">
              <button class="btn btn-gray" type="submit" name="action" value="update">Atualizar</button>
            </form>
          </td>
          <td>R$ <?= number_format($it['linha'], 2, ',', '.') ?></td>
          <td>
            <form method="post" action="carrinho.php">
              <input type="hidden" name="id" value="<?= $it['id'] ?>">
              <button class="btn btn-red" type="submit" name="action" value="remove">Remover</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>

    <div class="right" style="margin-top:12px;">
      <div style="font-size:1.1rem;font-weight:700;">Total: R$ <?= number_format($total, 2, ',', '.') ?></div>
    </div>

    <div class="row">
      <form method="post" action="carrinho.php">
        <button class="btn btn-red" type="submit" name="action" value="clear">Limpar carrinho</button>
      </form>
      <form method="post" action="carrinho.php" style="margin-left:auto;">
        <button class="btn btn-green" type="submit" name="action" value="checkout">Finalizar compra</button>
      </form>
    </div>
  <?php endif; ?>
</body>
</html>
