<?php include("../conexao.php"); ?>

<form method="POST">
    <input type="text" name="codigo" placeholder="Código" required><br>
    <input type="text" name="nome" placeholder="Nome da Carta" required><br>
    <select name="tipo">
        <option>Fogo</option>
        <option>Água</option>
        <option>Planta</option>
        <option>Elétrico</option>
        <option>Psíquico</option>
        <option>Outros</option>
    </select><br>
    <select name="raridade">
        <option>Comum</option>
        <option>Rara</option>
        <option>Ultra Rara</option>
        <option>Lendária</option>
    </select><br>
    <input type="text" name="valor" placeholder="Valor" required><br>
    <button type="submit">Cadastrar</button>
</form>

<?php
if ($_POST) {
    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $raridade = $_POST['raridade'];
    $valor = $_POST['valor'];

    $sql = "INSERT INTO cartas (codigo, nome, tipo, raridade, valor)
            VALUES ('$codigo', '$nome', '$tipo', '$raridade', '$valor')";
    if (mysqli_query($conn, $sql)) {
        echo "Carta cadastrada com sucesso!";
    } else {
        echo "Erro: " . mysqli_error($conn);
    }
}
?>
