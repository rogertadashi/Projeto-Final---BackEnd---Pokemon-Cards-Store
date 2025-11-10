<?php
require_once __DIR__ . '/conexao.php';
if (!isset($_SESSION)) session_start();

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

function go($u){ header("Location: $u"); exit; }

if ($_SERVER['REQUEST_METHOD']==='POST'){
  $action = $_POST['action'] ?? '';
  $pid = (int)($_POST['product_id'] ?? 0);
  $qty = max(1, (int)($_POST['quantity'] ?? 1));

  $st = $connect->prepare("SELECT id,nome FROM cartas WHERE id=?");
  $st->bind_param('i',$pid);
  $st->execute();
  $p = $st->get_result()->fetch_assoc();
  if(!$p){ $_SESSION['flash']='Carta inválida.'; go($_SERVER['PHP_SELF']); }

  $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + $qty;

  if($action==='buy_now'){ go('carrinho.php'); }
  $_SESSION['flash'] = "Adicionado ao carrinho: {$p['nome']} (x{$qty}).";
  go($_SERVER['PHP_SELF']);
}

$q = trim($_GET['q'] ?? '');
$page = max(1,(int)($_GET['page'] ?? 1));
$limit=12; $offset=($page-1)*$limit;

if($q!==''){
  $like="%{$q}%";
  $c = $connect->prepare("SELECT COUNT(*) total FROM cartas WHERE (codigo LIKE ? OR nome LIKE ? OR tipo LIKE ? OR raridade LIKE ?)");
  $c->bind_param('ssss',$like,$like,$like,$like);
  $c->execute();
  $total = (int)($c->get_result()->fetch_assoc()['total'] ?? 0);

  $s = $connect->prepare("SELECT id,codigo,nome,tipo,raridade,valor,imagem FROM cartas
                          WHERE (codigo LIKE ? OR nome LIKE ? OR tipo LIKE ? OR raridade LIKE ?)
                          ORDER BY nome LIMIT ? OFFSET ?");
  $s->bind_param('ssssii',$like,$like,$like,$like,$limit,$offset);
}else{
  $total = (int)($connect->query("SELECT COUNT(*) total FROM cartas")->fetch_assoc()['total'] ?? 0);
  $s = $connect->prepare("SELECT id,codigo,nome,tipo,raridade,valor,imagem FROM cartas
                          ORDER BY nome LIMIT ? OFFSET ?");
  $s->bind_param('ii',$limit,$offset);
}
$s->execute(); $res=$s->get_result();
$pages = max(1,(int)ceil($total/$limit));
$cartCount = array_sum($_SESSION['cart']);
?>
<!doctype html><html lang="pt-BR"><head>
<meta charset="utf-8"><title>Loja de Cartas</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<style>
 body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial;margin:20px;background:#0b0b0b;color:#eaeaea}
 .header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
 .header-right a{color:#93c5fd;text-decoration:none;margin-left:10px}
 .topbar{display:flex;gap:12px;align-items:center;margin-bottom:18px}
 input[type=text]{padding:8px 10px;border:1px solid #333;background:#121212;color:#eee;border-radius:10px;min-width:280px}
 .btn{padding:8px 12px;border:0;border-radius:10px;cursor:pointer}
 .btn-cart{background:#1f2937;color:#fff}.btn-buy{background:#16a34a;color:#fff}
 .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:16px}
 .card{background:#111;border:1px solid #1f1f1f;border-radius:16px;padding:14px;display:flex;flex-direction:column;gap:10px}
 .img{width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:12px;border:1px solid #1f1f1f;background:#0e0e0e}
 .muted{color:#bdbdbd;font-size:12px}.pill{display:inline-block;background:#1f2937;padding:2px 8px;border-radius:999px;font-size:12px;margin-right:6px}
 .price{font-weight:700}.row{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
 .qty{width:70px;padding:6px 8px;border-radius:8px;border:1px solid #333;background:#121212;color:#eee}
 .flash{background:#0f172a;border:1px solid #1e293b;padding:10px 12px;border-radius:10px;margin-bottom:14px}
 .pagination{display:flex;gap:6px;margin-top:16px}
 .pagination a,.pagination span{padding:6px 10px;background:#111;border:1px solid #1f1f1f;color:#ddd;border-radius:8px;text-decoration:none}
 .active{background:#2563eb;border-color:#1d4ed8}
</style>
</head><body>
<div class="header">
  <h1>Loja de Cartas</h1>
  <div class="header-right">
    <a href="carrinho.php">🛒 Carrinho (<?= (int)$cartCount ?>)</a>
    <a href="conectado.php">Dashboard</a>
    <a href="logout.php">Sair</a>
  </div>
</div>

<?php if(!empty($_SESSION['flash'])): ?><div class="flash"><?= htmlspecialchars($_SESSION['flash']) ?></div><?php unset($_SESSION['flash']); endif; ?>

<form class="topbar" method="get" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
  <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Buscar por código, nome, tipo ou raridade...">
  <button class="btn btn-cart" type="submit">Buscar</button>
</form>

<div class="grid">
<?php while($row=$res->fetch_assoc()): ?>
  <div class="card">
    <?php if(!empty($row['imagem'])): ?><img class="img" src="<?= htmlspecialchars($row['imagem']) ?>" alt="<?= htmlspecialchars($row['nome']) ?>"><?php endif; ?>
    <div>
      <div class="muted">Código: <?= htmlspecialchars($row['codigo']) ?></div>
      <div style="font-weight:700;font-size:1.05rem;"><?= htmlspecialchars($row['nome']) ?></div>
      <div class="muted"><span class="pill"><?= htmlspecialchars($row['tipo']) ?></span><span class="pill"><?= htmlspecialchars($row['raridade']) ?></span></div>
    </div>
    <div class="price">R$ <?= number_format((float)$row['valor'],2,',','.') ?></div>
    <div class="row">
      <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" style="display:flex;gap:8px;align-items:center">
        <input type="hidden" name="product_id" value="<?= (int)$row['id'] ?>">
        <input class="qty" type="number" min="1" name="quantity" value="1">
        <button class="btn btn-cart" type="submit" name="action" value="add_to_cart">Adicionar</button>
      </form>
      <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
        <input type="hidden" name="product_id" value="<?= (int)$row['id'] ?>">
        <input type="hidden" name="quantity" value="1">
        <button class="btn btn-buy" type="submit" name="action" value="buy_now">Comprar agora</button>
      </form>
    </div>
  </div>
<?php endwhile; ?>
</div>

<?php if($pages>1): ?>
  <div class="pagination">
    <?php for($p=1;$p<=$pages;$p++): ?>
      <?php if($p==$page): ?><span class="active"><?= $p ?></span>
      <?php else: ?><a href="?q=<?= urlencode($q) ?>&page=<?= $p ?>"><?= $p ?></a><?php endif; ?>
    <?php endfor; ?>
  </div>
<?php endif; ?>
</body></html>