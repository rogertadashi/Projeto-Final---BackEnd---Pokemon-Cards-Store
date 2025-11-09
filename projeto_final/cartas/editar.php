<?php
include("../conexao.php");

// Verifica se foi passado o ID
if (!isset($_GET['id'])) {
    die("ID da carta não informado.");
}

$id = $_GET['id'];

// Busca os dados da carta
$result = mysqli_query($conn, "SELECT * FROM cartas WHERE id = $id");
if (!$result || mysqli_num_rows($result) == 0) {
    die("Carta não encontrada.");
}

$row = mysqli_fetch_assoc($result);

// Se o formulário for enviado, atualiza os dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $tipo = mysqli_real_escape_string($conn, $_POST['tipo']);
    $raridade = mysqli_real_escape_string($conn, $_POST['raridade']);
    $preco = mysqli_real_escape_string($conn, $_POST['preco']);
    $estoque = mysqli_real_escape_string($conn, $_POST['estoque']);

    $sql = "UPDATE cartas 
            SET nome='$nome', tipo='$tipo', raridade='$raridade', preco='$preco', estoque='$estoque'
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: listar.php");
        exit;
    } else {
        echo "Erro ao atualizar: " . mysqli_error($conn);
    }
}
?>

<h2>Editar Carta Pokémon</h2>

<form method="POST">
    <label>Nome:</label><br>
    <input type="text" name="nome" value="<?= htmlspecialchars($row['nome']) ?>" required><br><br>

    <label>Tipo:</label><br>
    <input type="text" name="tipo" value="<?= htmlspecialchars($row['tipo']) ?>"><br><br>

    <label>Raridade:</label><br>
    <input type="text" name="raridade" value="<?= htmlspecialchars($row['raridade']) ?>"><br><br>

    <label>Preço:</label><br>
    <input type="number" step="0.01" name="preco" value="<?= htmlspecialchars($row['preco']) ?>"><br><br>

    <label>Estoque:</label><br>
    <input type="number" name="estoque" value="<?= htmlspecialchars($row['estoque']) ?>"><br><br>

    <button type="submit">Salvar Alterações</button>
</form>

<p><a href="listar.php">← Voltar à lista de cartas</a></p>
