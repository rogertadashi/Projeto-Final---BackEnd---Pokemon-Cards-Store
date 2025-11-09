<?php
include("../conexao.php");


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM clientes WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $cpf = $_POST['cpf'];

    $sql = "UPDATE clientes 
            SET nome='$nome', email='$email', telefone='$telefone', cpf='$cpf' 
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        header("Location: listar.php");
        exit;
    } else {
        echo "Erro ao atualizar: " . mysqli_error($conn);
    }
}
?>

<form method="POST">
    <input type="text" name="nome" value="<?= $row['nome'] ?>" placeholder="Nome" required><br>
    <input type="text" name="email" value="<?= $row['email'] ?>" placeholder="Email"><br>
    <input type="text" name="telefone" value="<?= $row['telefone'] ?>" placeholder="Telefone"><br>
    <input type="text" name="cpf" value="<?= $row['cpf'] ?>" placeholder="CPF"><br>
    <button type="submit">Salvar Alterações</button>
</form>