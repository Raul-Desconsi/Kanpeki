<?php
session_start();
require_once(__DIR__ . "/../api/config/connect.php");
require_once(__DIR__ . "/../api/phpFunction/verificaLogin.php");
require_once(__DIR__ . "/../api/phpFunction/carrinho.php");

// Capturar termo de pesquisa
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Contador do carrinho
$totalCarrinho = 0;
if (function_exists('contarItensCarrinho')) {
    $totalCarrinho = contarItensCarrinho();
} elseif (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
    foreach ($_SESSION['carrinho'] as $item) {
        $totalCarrinho += isset($item['quantidade']) ? (int)$item['quantidade'] : 1;
    }
}

// Buscar produtos em promoção - apenas se não houver pesquisa
if (empty($searchTerm)) {
    $stmtPromocao = $conexao->prepare("SELECT * FROM produtos WHERE status = 1 AND desconto > 0 ORDER BY desconto DESC");
    $stmtPromocao->execute();
    $produtosPromocao = $stmtPromocao->fetchAll(PDO::FETCH_ASSOC);
} else {
    $produtosPromocao = [];
}

// Buscar produtos com filtro de pesquisa
if (!empty($searchTerm)) {
    $stmt = $conexao->prepare("
        SELECT * FROM produtos 
        WHERE status = 1 
        AND (nome LIKE :search OR descricao LIKE :search) 
        ORDER BY nome
    ");
    $stmt->execute([':search' => "%$searchTerm%"]);
} else {
    $stmt = $conexao->prepare("SELECT * FROM produtos WHERE status = 1 ORDER BY nome");
    $stmt->execute();
}
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Loja - Kanpeki</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link href="../ativos/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        body {
            background: #f5f6fa;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 300px;
            background: #1f2937;
            color: #fff;
            padding: 24px 18px;
            position: fixed;
            top: 70px; /* 🔥 empurra abaixo do menu */
            left: 0;
            height: calc(100vh - 70px); /* 🔥 ajusta altura */
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0,0,0,0.08);
            z-index: 1000;
        }

        .sidebar h2 {
            font-size: 1.6rem;
            margin-bottom: 25px;
            font-weight: bold;
        }

        .sidebar .logo-icon {
            color: #60a5fa;
            margin-right: 8px;
        }

        .sidebar-card {
            background: rgba(255,255,255,0.06);
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 18px;
        }

        .sidebar-card h5 {
            margin-bottom: 14px;
            font-size: 1rem;
        }

        .search-input {
            border-radius: 10px;
            border: none;
            padding: 10px 12px;
        }

        .btn-search,
        .btn-cart,
        .btn-clear {
            width: 100%;
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
            border: none;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-search {
            background: #2563eb;
            color: #fff;
        }

        .btn-search:hover {
            background: #1d4ed8;
            color: #fff;
        }

        .btn-cart {
            background: #10b981;
            color: #fff;
        }

        .btn-cart:hover {
            background: #059669;
            color: #fff;
        }

        .btn-clear {
            background: #374151;
            color: #fff;
        }

        .btn-clear:hover {
            background: #4b5563;
            color: #fff;
        }

        .cart-badge-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255,255,255,0.08);
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 12px;
        }

        .cart-count {
            background: #ef4444;
            color: #fff;
            min-width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .main-content {
             margin-left: 300px;
             width: calc(100% - 300px);
             padding: 120px 30px 30px 30px;
        }

        .page-header {
            margin-bottom: 28px;
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .page-header p {
            color: #6b7280;
            margin: 0;
        }

        .section-title {
            position: relative;
            margin-bottom: 24px;
            padding-bottom: 10px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 65px;
            height: 3px;
            background: linear-gradient(90deg, #2563eb, #7c3aed);
            border-radius: 4px;
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 24px;
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
        }

        .card-img-top {
            height: 220px;
            object-fit: contain;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .btn-adicionar,
        .btn-adicionar-promo {
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-adicionar:active,
        .btn-adicionar-promo:active {
            transform: scale(0.96);
        }

        .preco-original {
            text-decoration: line-through;
            font-size: 0.85rem;
            color: #999;
        }

        .preco-com-desconto {
            color: #16a34a;
            font-size: 1.1rem;
        }

        .badge-desconto {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 54px;
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            z-index: 1;
        }

        .promo-banner {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            border-radius: 18px;
            padding: 28px;
            margin-bottom: 35px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .promo-banner::before {
            content: "🔥";
            font-size: 140px;
            position: absolute;
            right: -15px;
            bottom: -35px;
            opacity: 0.14;
            transform: rotate(-15deg);
        }

        .promo-countdown {
            background: rgba(255,255,255,0.18);
            border-radius: 10px;
            padding: 10px 18px;
            display: inline-block;
        }

        .promo-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 16px;
            padding: 20px;
            color: white;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            height: 100%;
        }

        .promo-card:hover {
            transform: scale(1.03);
        }

        .promo-card img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 15px;
        }

        .promo-desconto {
            font-size: 1.8rem;
            font-weight: bold;
        }

        .search-active {
            background: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
            border-radius: 16px;
            padding: 22px;
            margin-bottom: 28px;
            color: white;
        }

        .search-results-count {
            font-size: 0.95rem;
            opacity: 0.92;
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.04);
        }

        .highlight {
            background-color: #ffeb3b;
            padding: 0 3px;
            border-radius: 3px;
            color: #000;
        }

        .card-destaque {
            border: 2px solid #ff6b6b;
            box-shadow: 0 5px 20px rgba(255,107,107,0.18);
        }


        .menu-ajuste-loja {
            position: fixed;
            top: 0;
            left: 0;
            width:  100%; 
            z-index: 1100;
            background: #fff;
        }

        @media (max-width: 991px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }

            .layout {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

<div class="menu-ajuste-loja">
    <?php include('menu.php'); ?>
</div>


<div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
    <div id="toast-message" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" style="display: none;">
        <div class="d-flex">
            <div class="toast-body" id="toast-body">Produto adicionado!</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<div class="layout">
    <aside class="sidebar">
        <h2><i class="fas fa-store logo-icon"></i>Kanpeki</h2>

        <div class="sidebar-card">
            <h5><i class="fas fa-search"></i> Pesquisa</h5>
            <form method="GET" action="">
                <div class="mb-3">
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control search-input"
                        placeholder="Buscar produtos..."
                        value="<?php echo htmlspecialchars($searchTerm); ?>"
                    >
                </div>
                <button type="submit" class="btn-search mb-2">
                    <i class="fas fa-search"></i> Pesquisar
                </button>

                <?php if(!empty($searchTerm)): ?>
                    <a href="loja.php" class="btn-clear">
                        <i class="fas fa-times-circle"></i> Limpar busca
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <div class="sidebar-card">
            <h5><i class="fas fa-shopping-cart"></i> Carrinho</h5>
            <div class="cart-badge-box">
                <span>Itens no carrinho</span>
                <span class="cart-count" id="carrinho-contador">
                    <?php echo (int)$totalCarrinho; ?>
                </span>
            </div>

            <a href="carrinho.php" class="btn-cart">
                <i class="fas fa-cart-shopping"></i> Ver carrinho
            </a>
        </div>

         <!-- Histórico -->
            <div class="sidebar-card">
                <h5><i class="fas fa-clock-rotate-left"></i> Histórico</h5>

                <?php if (!empty($_SESSION['historico_compras'])): ?>
                    <?php $ultimaCompra = end($_SESSION['historico_compras']); ?>
                    <p class="mb-2"><strong>Última compra:</strong></p>
                    <small class="d-block mb-2">
                        Data: <?php echo htmlspecialchars($ultimaCompra['data']); ?>
                    </small>
                    <small class="d-block mb-2">
                        Itens: <?php echo (int)$ultimaCompra['quantidade_itens']; ?>
                    </small>
                    <small class="d-block mb-3">
                        Total: <?php echo number_format($ultimaCompra['total_geral'], 0, ',', '.'); ?> pts
                    </small>

                    <a href="historicoCompras.php" class="btn-clear">
                        <i class="fas fa-eye"></i> Ver histórico completo
                    </a>
                <?php else: ?>
                    <p class="mb-0">Nenhuma compra realizada ainda.</p>
                <?php endif; ?>
            </div>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h1>Loja Kanpeki</h1>
            <p>Explore os produtos disponíveis e adicione ao carrinho pela barra lateral.</p>
        </div>

        <?php if(!empty($searchTerm)): ?>
        <div class="search-active">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h4><i class="fas fa-search"></i> Resultados para: "<?php echo htmlspecialchars($searchTerm); ?>"</h4>
                    <p class="search-results-count mb-0">
                        <i class="fas fa-box"></i> <?php echo count($produtos); ?> produto(s) encontrado(s)
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(!empty($searchTerm) && empty($produtos)): ?>
        <div class="no-results">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h4>Nenhum produto encontrado</h4>
            <p class="text-muted">Não encontramos resultados para "<strong><?php echo htmlspecialchars($searchTerm); ?></strong>"</p>
            <p class="text-muted">Tente usar palavras diferentes ou verificar a ortografia.</p>
            <a href="loja.php" class="btn btn-primary mt-2">
                <i class="fas fa-arrow-left"></i> Ver todos os produtos
            </a>
        </div>
        <?php endif; ?>

        <?php if(empty($searchTerm)): ?>
        <div class="promo-banner">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2><i class="fas fa-fire"></i> Ofertas Imperdíveis!</h2>
                    <p>Produtos com descontos especiais por tempo limitado. Aproveite enquanto dura.</p>
                    <div class="promo-countdown">
                        <i class="fas fa-clock"></i> Oferta válida por:
                        <span id="countdown">07d 12h 34m 56s</span>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-tags fa-4x"></i>
                    <h3 class="mt-2">Até 20% OFF</h3>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if(empty($searchTerm) && !empty($produtosPromocao)): ?>
        <div class="mb-5">
            <div class="section-title">
                <h2><i class="fas fa-tag text-danger"></i> Produtos em Promoção</h2>
                <p class="text-muted">Ofertas especiais com desconto garantido.</p>
            </div>

            <div class="row">
                <?php foreach($produtosPromocao as $promocao): 
                    $precoComDesconto = $promocao['preco'] - ($promocao['preco'] * $promocao['desconto'] / 100);
                ?>
                <div class="col-md-3 mb-4">
                    <div class="promo-card" onclick="window.location.href='#produto-<?php echo $promocao['id']; ?>'">
                        <?php if(!empty($promocao['img'])): ?>
                            <img src="<?php echo htmlspecialchars($promocao['img']); ?>" alt="<?php echo htmlspecialchars($promocao['nome']); ?>">
                        <?php else: ?>
                            <i class="fas fa-gift fa-4x"></i>
                        <?php endif; ?>

                        <h5><?php echo htmlspecialchars($promocao['nome']); ?></h5>
                        <div class="promo-desconto">-<?php echo (int)$promocao['desconto']; ?>%</div>
                        <div>
                            <span class="text-decoration-line-through"><?php echo number_format($promocao['preco'], 0, ',', '.'); ?> pts</span>
                            <br>
                            <strong><?php echo number_format($precoComDesconto, 0, ',', '.'); ?> pts</strong>
                        </div>

                        <button type="button" class="btn btn-light btn-sm mt-2 btn-adicionar-promo" data-id="<?php echo (int)$promocao['id']; ?>">
                            <i class="fas fa-cart-plus"></i> Comprar
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if(!empty($produtos)): ?>
        <div class="section-title">
            <h2>
                <i class="fas fa-store"></i>
                <?php echo !empty($searchTerm) ? 'Produtos Encontrados' : 'Todos os Produtos'; ?>
            </h2>
            <p class="text-muted">
                <?php echo !empty($searchTerm) ? 'Veja os produtos que correspondem à sua busca' : 'Confira nosso catálogo completo'; ?>
            </p>
        </div>

        <div class="row">
            <?php foreach($produtos as $produto): 
                $precoComDesconto = $produto['preco'];
                $temDesconto = ($produto['desconto'] ?? 0) > 0;

                if($temDesconto) {
                    $precoComDesconto = $produto['preco'] - ($produto['preco'] * $produto['desconto'] / 100);
                }

                $nomeDestacado = htmlspecialchars($produto['nome']);
                if(!empty($searchTerm)) {
                    $pattern = '/(' . preg_quote($searchTerm, '/') . ')/i';
                    $nomeDestacado = preg_replace($pattern, '<span class="highlight">$1</span>', $nomeDestacado);
                }
            ?>
            <div class="col-lg-4 col-md-6" id="produto-<?php echo (int)$produto['id']; ?>">
                <div class="card h-100 position-relative <?php echo $temDesconto ? 'card-destaque' : ''; ?>">
                    
                    <?php if($temDesconto): ?>
                        <div class="badge-desconto">-<?php echo (int)$produto['desconto']; ?>%</div>
                    <?php endif; ?>

                    <?php if(!empty($produto['img'])): ?>
                        <img src="<?php echo htmlspecialchars($produto['img']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                    <?php else: ?>
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light">
                            <i class="fas fa-image fa-4x text-muted"></i>
                        </div>
                    <?php endif; ?>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <?php echo $nomeDestacado; ?>
                            <?php if($temDesconto): ?>
                                <i class="fas fa-fire text-danger"></i>
                            <?php endif; ?>
                        </h5>

                        <p class="card-text text-muted small">
                            <?php echo htmlspecialchars($produto['descricao'] ?? 'Produto disponível na loja'); ?>
                        </p>

                        <div class="mt-auto">
                            <?php if($temDesconto): ?>
                                <p class="card-text mb-1">
                                    <span class="preco-original"><?php echo number_format($produto['preco'], 0, ',', '.'); ?> pts</span>
                                    <span class="preco-com-desconto fw-bold ms-2">
                                        <?php echo number_format($precoComDesconto, 0, ',', '.'); ?> pts
                                    </span>
                                </p>
                            <?php else: ?>
                                <p class="card-text">
                                    <strong class="text-primary"><?php echo number_format($produto['preco'], 0, ',', '.'); ?> pontos</strong>
                                </p>
                            <?php endif; ?>

                            <?php if((int)$produto['estoque'] <= 0): ?>
                                <button type="button" class="btn btn-secondary w-100 mt-2" disabled>
                                    <i class="fas fa-times"></i> Esgotado
                                </button>
                            <?php else: ?>
                                <button type="button" class="btn btn-primary btn-adicionar w-100 mt-2" data-id="<?php echo (int)$produto['id']; ?>">
                                    <i class="fas fa-cart-plus"></i> Adicionar ao Carrinho
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {

    function showToast(message, type = 'success') {
        const toast = $('#toast-message');
        toast.removeClass('bg-success bg-danger').addClass(type === 'success' ? 'bg-success' : 'bg-danger');
        $('#toast-body').text(message);
        toast.stop(true, true).fadeIn().delay(3000).fadeOut();
    }

    function atualizarContador(contador) {
        $('#carrinho-contador').text(contador);
    }

    function tratarErroAjax(xhr) {
        if (xhr.status === 401) {
            showToast('Sessão expirada. Faça login novamente.', 'error');
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 1200);
            return;
        }

        if (xhr.responseJSON && xhr.responseJSON.message) {
            showToast(xhr.responseJSON.message, 'error');
            return;
        }

        showToast('Erro ao adicionar produto', 'error');
    }

    function adicionarAoCarrinho(idProduto, botaoElement) {
        const botao = $(botaoElement);
        const textoOriginal = botao.html();

        botao.html('<i class="fas fa-spinner fa-spin"></i> Adicionando...').prop('disabled', true);

        $.ajax({
            url: '../api/insert/carrinho/adicionar.php',
            method: 'POST',
            data: { id_produto: idProduto },
            dataType: 'json',
            success: function(response) {
                if (response && response.success) {
                    showToast(response.message || 'Produto adicionado com sucesso!', 'success');

                    if (response.count !== undefined) {
                        atualizarContador(response.count);
                    }

                    botao.html('<i class="fas fa-check"></i> Adicionado!');
                    setTimeout(() => {
                        botao.html(textoOriginal).prop('disabled', false);
                    }, 1500);
                } else {
                    showToast((response && response.message) ? response.message : 'Não foi possível adicionar o produto.', 'error');
                    botao.html(textoOriginal).prop('disabled', false);
                }
            },
            error: function(xhr) {
                tratarErroAjax(xhr);
                botao.html(textoOriginal).prop('disabled', false);
            }
        });
    }

    $('.btn-adicionar').on('click', function() {
        const idProduto = $(this).data('id');
        adicionarAoCarrinho(idProduto, this);
    });

    $('.btn-adicionar-promo').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const idProduto = $(this).data('id');
        adicionarAoCarrinho(idProduto, this);
    });

    if ($('#countdown').length) {
        const targetDate = new Date();
        targetDate.setDate(targetDate.getDate() + 7);
        targetDate.setHours(23, 59, 59, 999);

        function updateCountdown() {
            const now = new Date();
            const diff = targetDate - now;

            if (diff > 0) {
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                $('#countdown').html(`${days}d ${hours}h ${minutes}m ${seconds}s`);
            } else {
                $('#countdown').html('Oferta encerrada!');
            }
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    <?php if(!empty($searchTerm) && count($produtos) > 0): ?>
        showToast('🔍 Encontramos <?php echo count($produtos); ?> produto(s) para sua busca', 'success');
    <?php elseif(!empty($searchTerm) && count($produtos) == 0): ?>
        showToast('😕 Nenhum produto encontrado para "<?php echo htmlspecialchars($searchTerm, ENT_QUOTES); ?>"', 'error');
    <?php endif; ?>
});
</script>
</body>
</html>