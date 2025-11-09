<?php
include("../conexao.php");
$result = mysqli_query($conn, "SELECT * FROM cartas");
?>

<h2>Lista de clientes</h2>
<table border="1">
<tr><th>ID</th><th>Imagem</th><th>Código</th><th>Nome</th><th>Tipo</th><th>Raridade</th><th>Valor</th></tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['imagem'] ?></td>
    <td><?= $row['codigo'] ?></td>
    <td><?= $row['nome'] ?></td>
    <td><?= $row['tipo'] ?></td>
    <td><?= $row['raridade'] ?></td>
    <td><?= $row['valor'] ?></td>
    <td>
        <a href="editar.php?id=<?= $row['id'] ?>">Editar</a> |
        <a href="excluir.php?id=<?= $row['id'] ?>">Excluir</a>
    </td>
</tr>
<?php } ?>
</table>
