<?php
require_once __DIR__ . '/../conexao.php';
require_once __DIR__ . '/../conectado.php';

$id = (int)($_GET['id'] ?? 0);
if ($id<=0) { die("ID inválido"); }

// Buscar venda
$vs = mysqli_prepare($connect, "SELECT id, cliente_id, condicao_pagamento FROM vendas WHERE id=?");
mysqli_stmt_bind_param($vs,"i",$id);
mysqli_stmt_execute($vs);
$r = mysqli_stmt_get_result($vs);
$venda = mysqli_fetch_assoc($r);
if (!$venda) die("Venda não encontrada");

// Listar clientes
$cl = mysqli_query($connect, "SELECT id, nome FROM clientes ORDER BY nome");
$clientes = [];
while($c = mysqli_fetch_assoc($cl)) $clientes[] = $c;

$condicoes = ['À vista','Pix','Cartão de crédito','Cartão de débito','Parcelado'];
$erro=$ok=null;

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $cliente_id = (int)($_POST['cliente_id'] ?? 0);
  $condicao   = $_POST['condicao_pagamento'] ?? 'À vista';
  if (!in_array($condicao,$condicoes,true)) $condicao='À vista';
  if ($cliente_id<=0) $erro = "Selecione um cliente.";
  if (!$erro) {
    $up = mysqli_prepare($connect, "UPDATE vendas SET cliente_id=?, condicao_pagamento=? WHERE id=?");
    mysqli_stmt_bind_param($up,"isi",$cliente_id,$condicao,$id);
    mysqli_stmt_execute($up);
    $ok = "Venda atualizada.";
    // refresh venda data
    $vs = mysqli_prepare($connect, "SELECT id, cliente_id, condicao_pagamento FROM vendas WHERE id=?");
    mysqli_stmt_bind_param($vs,"i",$id);
    mysqli_stmt_execute($vs);
    $venda = mysqli_fetch_assoc(mysqli_stmt_get_result($vs));
  }
}
?>
<!DOCTYPE html><html lang="pt-br"><head><meta charset="utf-8"><title>Editar Venda</title></head><body>
<h1>Editar Venda #<?= (int)$id ?></h1>
<?php if($erro) echo "<p style='color:red'>$erro</p>"; ?>
<?php if($ok) echo "<p style='color:green'>$ok</p>"; ?>
<form method="post">
  <label>Cliente:</label>
  <select name="cliente_id">
    <option value="">-- selecione --</option>
    <?php foreach($clientes as $c): ?>
      <option value="<?= (int)$c['id'] ?>" <?= $c['id']==$venda['cliente_id'] ? 'selected':'' ?>>
        <?= htmlspecialchars($c['nome']) ?>
      </option>
    <?php endforeach; ?>
  </select><br><br>

  <label>Condição de pagamento:</label>
  <select name="condicao_pagamento">
    <?php foreach($condicoes as $c): ?>
      <option <?= $c===$venda['condicao_pagamento'] ? 'selected':'' ?>><?= $c ?></option>
    <?php endforeach; ?>
  </select><br><br>

  <button>Salvar</button>
  <a href="listar.php">Voltar</a>
</form>
</body></html>
