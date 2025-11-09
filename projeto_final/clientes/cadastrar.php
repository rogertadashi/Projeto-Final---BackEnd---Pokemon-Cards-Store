<?php include("../conexao.php"); ?>

<form method="POST">
    <input type="text" name="nome" placeholder="Nome" required><br>
    <input type="text" name="email" placeholder="Email"><br>
    <input type="text" name="telefone" placeholder="Telefone"><br>
    <input type="text" name="cpf" placeholder="CPF"><br>
    <button type="submit">Cadastrar</button>
</form>

<?php
if ($_POST) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];

    $sql = "INSERT INTO clientes (nome, email, telefone, cpf)
            VALUES ('$nome', '$email', '$telefone', '$cpf')";

    if (mysqli_query($conn, $sql)) {
        echo "Cliente cadastrado com sucesso!";
    } else {
        echo "Erro: " . mysqli_error($conn);
    }
}
?>
