<?php
include("../conexao.php");

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM usuarios WHERE id=$id");
$row = mysqli_fetch_assoc($result);

if ($_POST) {
    $nome = $_POST['nome'];
    $login = $_POST['login'];
    $funcao = $_POST['funcao'];

    $sql = "UPDATE usuarios SET nome='$nome', login='$login', funcao='$funcao' WHERE id=$id";
    mysqli_query($conn, $sql);
    header("Location: listar.php");
}
?>

<form method="POST">
    <input type="text" name="nome" value="<?= $row['nome'] ?>"><br>
    <input type="text" name="login" value="<?= $row['login'] ?>"><br>
    <select name="funcao">
        <option value="Administrador" <?= $row['funcao']=='Administrador'?'selected':'' ?>>Administrador</option>
        <option value="Vendedor" <?= $row['funcao']=='Vendedor'?'selected':'' ?>>Vendedor</option>
    </select><br>
    <button type="submit">Salvar</button>
</form>
