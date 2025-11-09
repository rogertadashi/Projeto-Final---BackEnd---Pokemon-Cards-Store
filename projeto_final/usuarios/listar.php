<?php
include("../conexao.php");
$result = mysqli_query($conn, "SELECT * FROM usuarios");
?>

<h2>Lista de Usuários</h2>
<table border="1">
<tr><th>ID</th><th>Nome</th><th>Login</th><th>Função</th><th>Ações</th></tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['nome'] ?></td>
    <td><?= $row['login'] ?></td>
    <td><?= $row['funcao'] ?></td>
    <td>
        <a href="editar.php?id=<?= $row['id'] ?>">Editar</a> |
        <a href="excluir.php?id=<?= $row['id'] ?>">Excluir</a>
    </td>
</tr>
<?php } ?>
</table>
