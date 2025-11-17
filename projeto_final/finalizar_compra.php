<?php
require_once __DIR__ . '/conexao.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Se não estiver logado → volta ao login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit;
}

$idCliente = $_SESSION['id_usuario'];
$cart = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    $_SESSION['flash'] = "Seu carrinho está vazio.";
    header("Location: carrinho.php");
    exit;
}

// MÉTODO DE PAGAMENTO (padrão)
$condicao_pagamento = $_POST['condicao_pagamento'] ?? 'À vista';

// Pagamentos válidos
$pagamentosValidos = [
    'À vista',
    'Pix',
    'Cartão de crédito',
    'Cartão de débito',
    'Parcelado'
];

if (!in_array($condicao_pagamento, $pagamentosValidos, true)) {
    $condicao_pagamento = 'À vista';
}

// Buscar itens no banco
$ids = implode(',', array_keys($cart));
$query = $conn->query("SELECT id, nome, valor, estoque FROM cartas WHERE id IN ($ids)");

$items = [];
$total = 0;

while ($row = $query->fetch_assoc()) {

    $id = $row['id'];
    $qtd = $cart[$id];
    $estoque = $row['estoque'];

    if ($qtd > $estoque) {
        $_SESSION['flash'] = "Estoque insuficiente para o item: {$row['nome']}";
        header("Location: carrinho.php");
        exit;
    }

    $subtotal = $row['valor'] * $qtd;
    $total += $subtotal;

    $items[] = [
        'id' => $id,
        'nome' => $row['nome'],
        'valor' => $row['valor'],
        'qtd' => $qtd,
        'subtotal' => $subtotal
    ];
}

$conn->begin_transaction();

try {

    // 1) Criar venda
    $stmt = $conn->prepare("
        INSERT INTO vendas (cliente_id, valor_total, condicao_pagamento)
        VALUES (?, ?, ?)
    ");

    if (!$stmt) {
        throw new Exception("Erro no prepare vendas: " . $conn->error);
    }

    $stmt->bind_param("ids", $idCliente, $total, $condicao_pagamento);
    $stmt->execute();

    $idVenda = $conn->insert_id;

    // 2) Inserir itens
    $stmtItem = $conn->prepare("
        INSERT INTO itens_venda (venda_id, carta_id, quantidade, valor_unitario)
        VALUES (?, ?, ?, ?)
    ");

    if (!$stmtItem) {
        throw new Exception("Erro no prepare itens: " . $conn->error);
    }

    // 3) Atualizar estoque
    $stmtEstoque = $conn->prepare("
        UPDATE cartas SET estoque = estoque - ? WHERE id = ?
    ");

    foreach ($items as $i) {

        $stmtItem->bind_param(
            "iiid",
            $idVenda,
            $i['id'],
            $i['qtd'],
            $i['valor']
        );
        $stmtItem->execute();

        $stmtEstoque->bind_param("ii", $i['qtd'], $i['id']);
        $stmtEstoque->execute();
    }

    // Finaliza transação
    $conn->commit();

    unset($_SESSION['cart']); // limpa carrinho

} catch (Exception $e) {

    $conn->rollback();
    $_SESSION['flash'] = "Erro ao finalizar compra: " . $e->getMessage();
    header("Location: carrinho.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra finalizada</title>
    <link rel="stylesheet" href="./style_2.css" />
</head>

<body>
    <h1>🎉 Compra Finalizada!</h1>

    <p>Obrigado pela sua compra! Seu pedido foi registrado com sucesso.</p>

    <p><strong>ID da Venda:</strong> <?= $idVenda ?></p>
    <p><strong>Total:</strong> R$ <?= number_format($total, 2, ',', '.') ?></p>
    <p><strong>Pagamento:</strong> <?= htmlspecialchars($condicao_pagamento) ?></p>

    <a class="btn btn-green" href="index.php">Voltar para a Loja</a>
</body>

</html>