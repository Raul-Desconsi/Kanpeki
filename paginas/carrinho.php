<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/Kanpeki/api/phpFunction/verificaLogin.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Kanpeki/api/phpFunction/carrinho.php");

$carrinho = getCarrinho();
$total = totalCarrinho();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carrinho - Kanpeki</title>
    <link href="/Kanpeki/ativos/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .quantidade-btn {
            cursor: pointer;
            transition: all 0.3s;
        }
        .quantidade-btn:hover {
            transform: scale(1.1);
        }
        .produto-imagem {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }
    </style>
</head>
<body>

<?php include('menu.php'); ?>

<div class="container mt-4">
    <h2>🛒 Meu Carrinho</h2>

    <?php if(empty($carrinho)): ?>
        <div class="alert alert-info">
            Seu carrinho está vazio
            <a href="/Kanpeki/paginas/loja.php" class="alert-link">Continuar comprando</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Produto</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Subtotal</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($carrinho as $id => $item): ?>
                    <tr id="item-<?php echo $id; ?>">
                        <td>
                            <?php if(!empty($item['img'])): ?>
                                <img src="<?php echo $item['img']; ?>" class="produto-imagem me-2">
                            <?php else: ?>
                                <i class="fas fa-box me-2"></i>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($item['nome']); ?>
                        </td>
                        <td><?php echo number_format($item['preco'], 0, ',', '.'); ?> pts</td>
                        <td>
                            <div class="input-group" style="width: 120px;">
                                <button class="btn btn-outline-secondary btn-diminuir" data-id="<?php echo $id; ?>" type="button">-</button>
                                <input type="number" class="form-control text-center qtd-input" data-id="<?php echo $id; ?>" value="<?php echo $item['qtd']; ?>" min="1" style="max-width: 50px;">
                                <button class="btn btn-outline-secondary btn-aumentar" data-id="<?php echo $id; ?>" type="button">+</button>
                            </div>
                        </td>
                        <td class="subtotal-<?php echo $id; ?>"><?php echo number_format($item['preco'] * $item['qtd'], 0, ',', '.'); ?> pts</td>
                        <td>
                            <button class="btn btn-sm btn-danger btn-remover" data-id="<?php echo $id; ?>">
                                <i class="fas fa-trash"></i> Remover
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td colspan="2">
                            <strong id="total-carrinho"><?php echo number_format($total, 0, ',', '.'); ?></strong> pts
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-3">
            <a href="/Kanpeki/paginas/loja.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Continuar Comprando
            </a>
            <form method="POST" action="/Kanpeki/api/insert/carrinho/finalizar.php">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Finalizar Compra
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    
    function atualizarCarrinho(id, novaQuantidade) {
        $.ajax({
            url: '/Kanpeki/api/insert/carrinho/atualizar.php',
            method: 'POST',
            data: { id: id, quantidade: novaQuantidade },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    $('.subtotal-' + id).text(formatNumber(response.subtotal) + ' pts');
                    $('#total-carrinho').text(formatNumber(response.total));
                    
                    if(novaQuantidade === 0) {
                        $('#item-' + id).fadeOut(300, function() {
                            $(this).remove();
                            if($('#tabela-carrinho tbody tr').length === 0) {
                                location.reload();
                            }
                        });
                    }
                }
            }
        });
    }
    
    $('.btn-aumentar').click(function() {
        const id = $(this).data('id');
        const input = $('.qtd-input[data-id="' + id + '"]');
        let qtd = parseInt(input.val());
        qtd++;
        input.val(qtd);
        atualizarCarrinho(id, qtd);
    });
    
    $('.btn-diminuir').click(function() {
        const id = $(this).data('id');
        const input = $('.qtd-input[data-id="' + id + '"]');
        let qtd = parseInt(input.val());
        if(qtd > 1) {
            qtd--;
            input.val(qtd);
            atualizarCarrinho(id, qtd);
        } else {
            if(confirm('Deseja remover este item do carrinho?')) {
                atualizarCarrinho(id, 0);
            }
        }
    });
    
    $('.qtd-input').change(function() {
        const id = $(this).data('id');
        let qtd = parseInt($(this).val());
        if(isNaN(qtd) || qtd < 1) {
            qtd = 1;
            $(this).val(1);
        }
        atualizarCarrinho(id, qtd);
    });
    
    $('.btn-remover').click(function() {
        const id = $(this).data('id');
        if(confirm('Tem certeza que deseja remover este item?')) {
            $.ajax({
                url: '/Kanpeki/api/insert/carrinho/remover.php',
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        $('#item-' + id).fadeOut(300, function() {
                            $(this).remove();
                            $('#total-carrinho').text(formatNumber(response.total));
                            if($('#tabela-carrinho tbody tr').length === 0) {
                                location.reload();
                            }
                        });
                    }
                }
            });
        }
    });
    
    function formatNumber(number) {
        return new Intl.NumberFormat('pt-BR').format(number);
    }
});
</script>
</body>
</html>