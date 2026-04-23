<?php
session_start();
require_once(__DIR__ . "/../api/phpFunction/verificaLogin.php");

$historico = $_SESSION['historico_compras'] ?? [];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Compras - Kanpeki</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="../ativos/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include('menu.php'); ?>

<div class="container mt-5 pt-5">
    <h2 class="mb-4"><i class="fas fa-clock-rotate-left"></i> Histórico de Compras</h2>

    <?php if (empty($historico)): ?>
        <div class="alert alert-info">
            Nenhuma compra foi realizada ainda.
        </div>
    <?php else: ?>
        <?php foreach (array_reverse($historico) as $index => $compra): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Compra #<?php echo count($historico) - $index; ?></strong>
                    </div>
                    <small><?php echo htmlspecialchars($compra['data']); ?></small>
                </div>

                <div class="card-body">
                    <p class="mb-2">
                        <strong>Total:</strong>
                        <?php echo number_format($compra['total_geral'], 0, ',', '.'); ?> pts
                    </p>

                    <p class="mb-3">
                        <strong>Itens:</strong>
                        <?php echo (int)$compra['quantidade_itens']; ?>
                    </p>

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                    <th>Preço unitário</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($compra['itens'] as $item): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($item['produto']); ?></td>
                                        <td><?php echo (int)$item['quantidade']; ?></td>
                                        <td><?php echo number_format($item['preco_unitario'], 0, ',', '.'); ?> pts</td>
                                        <td><?php echo number_format($item['total'], 0, ',', '.'); ?> pts</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>