<?php
include("../conexao.php");
$result = mysqli_query($conn, "SELECT * FROM clientes");
?>

<h2>Lista de clientes</h2>
<table border="1">
<tr><th>ID</th><th>Nome</th><th>Email</th><th>Telefone</th><th>CPF</th></tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['nome'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['telefone'] ?></td>
    <td><?= $row['cpf'] ?></td>
    <td>
        <a href="editar.php?id=<?= $row['id'] ?>">Editar</a> |
        <a href="excluir.php?id=<?= $row['id'] ?>">Excluir</a>
    </td>
</tr>
<?php } ?>
</table>
