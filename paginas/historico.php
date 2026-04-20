<?php
session_start();
require_once("../api/phpFunction/verificaLogin.php");
require_once("../api/config/connect.php");

$usuario = $_SESSION['usuario'];

$stmt = $conexao->prepare("
    SELECT ch.*, p.nome 
    FROM compra_historico ch
    JOIN produtos p ON p.id = ch.produto_id
    WHERE ch.funcionario_cracha = ?
    ORDER BY ch.data DESC
");

$stmt->execute([$usuario['cracha']]);
$historico = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Histórico</title>
    <link href="../ativos/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <?php include("menu.php"); ?>

<div class="container mt-4">
    <h2>📦 Histórico de Compras</h2>

    <?php if(empty($historico)): ?>
        <p>Você ainda não fez compras.</p>
    <?php else: ?>

        <table class="table">
            <tr>
                <th>Produto</th>
                <th>Qtd</th>
                <th>Total</th>
                <th>Data</th>
            </tr>

            <?php foreach($historico as $item): ?>

            <tr>
                <td><?php echo $item['nome']; ?></td>
                <td><?php echo $item['quantidade']; ?></td>
                <td><?php echo $item['valor_total']; ?></td>
                <td><?php echo $item['data']; ?></td>
            </tr>

            <?php endforeach; ?>

        </table>

    <?php endif; ?>

</div>

</body>
</html>