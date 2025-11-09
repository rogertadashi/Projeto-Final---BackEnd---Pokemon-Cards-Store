<?php include("../conexao.php"); ?>

<form method="POST">
    <input type="text" name="nome" placeholder="Nome" required><br>
    <input type="text" name="login" placeholder="Login" required><br>
    <input type="password" name="senha" placeholder="Senha" required><br>
    <select name="funcao">
        <option value="Administrador">Administrador</option>
        <option value="Vendedor">Vendedor</option>
    </select><br>
    <button type="submit">Cadastrar</button>
</form>

<?php
if ($_POST) {
    $nome = $_POST['nome'];
    $login = $_POST['login'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $funcao = $_POST['funcao'];

    $sql = "INSERT INTO usuarios (nome, login, senha, funcao)
            VALUES ('$nome', '$login', '$senha', '$funcao')";

    if (mysqli_query($conn, $sql)) {
        echo "Usuário cadastrado com sucesso!";
    } else {
        echo "Erro: " . mysqli_error($conn);
    }
}
?>
