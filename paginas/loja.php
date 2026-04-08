<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/Kanpeki/api/config/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Kanpeki/api/phpFunction/verificaLogin.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Kanpeki/api/phpFunction/carrinho.php");

// Capturar termo de pesquisa
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

// Buscar produtos em promoção (com desconto > 0) - apenas se não houver pesquisa
if(empty($searchTerm)) {
    $stmtPromocao = $conexao->prepare("SELECT * FROM produtos WHERE status = 1 AND desconto > 0 ORDER BY desconto DESC");
    $stmtPromocao->execute();
    $produtosPromocao = $stmtPromocao->fetchAll(PDO::FETCH_ASSOC);
} else {
    $produtosPromocao = []; // Esconde promoções durante pesquisa
}

// Buscar produtos com filtro de pesquisa
if(!empty($searchTerm)) {
    $stmt = $conexao->prepare("SELECT * FROM produtos WHERE status = 1 AND (nome LIKE :search OR descricao LIKE :search) ORDER BY nome");
    $stmt->execute([':search' => "%$searchTerm%"]);
} else {
    $stmt = $conexao->prepare("SELECT * FROM produtos WHERE status = 1 ORDER BY nome");
    $stmt->execute();
}
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Loja - Kanpeki</title>
    <link href="/Kanpeki/ativos/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .card {
            transition: transform 0.3s;
            margin-bottom: 20px;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .btn-adicionar {
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-adicionar:active {
            transform: scale(0.95);
        }
        .card-img-top {
            height: 200px;
            object-fit: contain;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .preco-original {
            text-decoration: line-through;
            font-size: 0.8rem;
            color: #999;
        }
        .preco-com-desconto {
            color: #28a745;
            font-size: 1.1rem;
        }
        .badge-desconto {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            z-index: 1;
        }
        
        /* Card de Promoção Banner */
        .promo-banner {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 40px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .promo-banner::before {
            content: "🔥";
            font-size: 150px;
            position: absolute;
            right: -20px;
            bottom: -40px;
            opacity: 0.2;
            transform: rotate(-15deg);
        }
        .promo-banner h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .promo-banner p {
            font-size: 1.1rem;
            margin-bottom: 20px;
            opacity: 0.9;
        }
        .promo-countdown {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            padding: 10px 20px;
            display: inline-block;
        }
        
        /* Card de Produto em Destaque */
        .card-destaque {
            border: 2px solid #ff6b6b;
            box-shadow: 0 5px 20px rgba(255,107,107,0.2);
        }
        .card-destaque .card-title {
            color: #ff6b6b;
        }
        
        /* Carrossel de Promoções */
        .promo-carousel {
            margin-bottom: 40px;
        }
        .promo-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border-radius: 15px;
            padding: 20px;
            color: white;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            height: 100%;
        }
        .promo-card:hover {
            transform: scale(1.05);
        }
        .promo-card img {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 15px;
        }
        .promo-desconto {
            font-size: 2rem;
            font-weight: bold;
        }
        .section-title {
            position: relative;
            margin-bottom: 30px;
            padding-bottom: 10px;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        /* Estilos para resultado da pesquisa */
        .search-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            color: white;
        }
        .search-results-count {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .clear-search {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 50px;
            background: rgba(255,255,255,0.2);
            transition: all 0.3s;
        }
        .clear-search:hover {
            background: rgba(255,255,255,0.3);
            color: white;
            text-decoration: none;
        }
        .no-results {
            text-align: center;
            padding: 60px 20px;
            background: #f8f9fa;
            border-radius: 15px;
        }
        .highlight {
            background-color: #ffeb3b;
            padding: 0 3px;
            border-radius: 3px;
        }
    </style>
</head>
<body>

<!-- Toast de notificação -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
    <div id="toast-message" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" style="display: none;">
        <div class="d-flex">
            <div class="toast-body" id="toast-body">
                Produto adicionado!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<?php include('menu.php'); ?>

<div class="container mt-4">
    
    <!-- RESULTADO DA PESQUISA -->
    <?php if(!empty($searchTerm)): ?>
    <div class="search-active">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h4><i class="fas fa-search"></i> Resultados para: "<?php echo htmlspecialchars($searchTerm); ?>"</h4>
                <p class="search-results-count mb-0">
                    <i class="fas fa-box"></i> <?php echo count($produtos); ?> produto(s) encontrado(s)
                </p>
            </div>
            <div class="mt-2 mt-sm-0">
                <a href="/Kanpeki/paginas/loja.php" class="clear-search">
                    <i class="fas fa-times-circle"></i> Limpar busca
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- MENSAGEM QUANDO NÃO ENCONTRA NADA -->
    <?php if(!empty($searchTerm) && empty($produtos)): ?>
    <div class="no-results">
        <i class="fas fa-search fa-4x text-muted mb-3"></i>
        <h4>Nenhum produto encontrado</h4>
        <p class="text-muted">Não encontramos resultados para "<strong><?php echo htmlspecialchars($searchTerm); ?></strong>"</p>
        <p class="text-muted">Tente usar palavras diferentes ou verificar a ortografia.</p>
        <a href="/Kanpeki/paginas/loja.php" class="btn btn-primary mt-2">
            <i class="fas fa-arrow-left"></i> Ver todos os produtos
        </a>
    </div>
    <?php endif; ?>

    <!-- BANNER DE PROMOÇÃO PRINCIPAL (só mostra se não tiver pesquisa) -->
    <?php if(empty($searchTerm)): ?>
    <div class="promo-banner">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2><i class="fas fa-fire"></i> Ofertas Imperdíveis!</h2>
                <p>Produtos com descontos especiais por tempo limitado. Aproveite enquanto dura!</p>
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

    <!-- SEÇÃO DE PRODUTOS EM PROMOÇÃO (só mostra se não tiver pesquisa) -->
    <?php if(empty($searchTerm) && !empty($produtosPromocao)): ?>
    <div class="promo-carousel">
        <div class="section-title">
            <h2><i class="fas fa-tag text-danger"></i> Produtos em Promoção</h2>
            <p class="text-muted">Ofertas especiais com desconto garantido!</p>
        </div>
        <div class="row">
            <?php foreach($produtosPromocao as $promocao): 
                $precoComDesconto = $promocao['preco'] - ($promocao['preco'] * $promocao['desconto'] / 100);
            ?>
            <div class="col-md-3">
                <div class="promo-card" onclick="window.location.href='#produto-<?php echo $promocao['id']; ?>'">
                    <?php if(!empty($promocao['img'])): ?>
                        <img src="<?php echo $promocao['img']; ?>" alt="<?php echo $promocao['nome']; ?>">
                    <?php else: ?>
                        <i class="fas fa-gift fa-4x"></i>
                    <?php endif; ?>
                    <h5><?php echo htmlspecialchars($promocao['nome']); ?></h5>
                    <div class="promo-desconto">-<?php echo $promocao['desconto']; ?>%</div>
                    <div>
                        <span class="text-decoration-line-through"><?php echo number_format($promocao['preco'], 0, ',', '.'); ?> pts</span>
                        <br>
                        <strong><?php echo number_format($precoComDesconto, 0, ',', '.'); ?> pts</strong>
                    </div>
                    <button class="btn btn-light btn-sm mt-2 btn-adicionar-promo" data-id="<?php echo $promocao['id']; ?>">
                        <i class="fas fa-cart-plus"></i> Comprar
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- TODOS OS PRODUTOS -->
    <?php if(!empty($produtos)): ?>
    <div class="section-title">
        <h2><i class="fas fa-store"></i> <?php echo !empty($searchTerm) ? 'Produtos Encontrados' : 'Todos os Produtos'; ?></h2>
        <p class="text-muted"><?php echo !empty($searchTerm) ? 'Veja os produtos que correspondem à sua busca' : 'Confira nosso catálogo completo'; ?></p>
    </div>
    
    <div class="row">
        <?php foreach($produtos as $produto): 
            // Calcular preço com desconto
            $precoComDesconto = $produto['preco'];
            $temDesconto = ($produto['desconto'] ?? 0) > 0;
            if($temDesconto) {
                $precoComDesconto = $produto['preco'] - ($produto['preco'] * $produto['desconto'] / 100);
            }
            
            // Destacar termo de busca no nome (se houver pesquisa)
            $nomeDestacado = htmlspecialchars($produto['nome']);
            if(!empty($searchTerm)) {
                $pattern = '/(' . preg_quote($searchTerm, '/') . ')/i';
                $nomeDestacado = preg_replace($pattern, '<span class="highlight">$1</span>', $nomeDestacado);
            }
        ?>
        <div class="col-md-4" id="produto-<?php echo $produto['id']; ?>">
            <div class="card h-100 position-relative <?php echo $temDesconto ? 'card-destaque' : ''; ?>">
                <!-- Badge de desconto -->
                <?php if($temDesconto): ?>
                    <div class="badge-desconto">
                        -<?php echo $produto['desconto']; ?>%
                    </div>
                <?php endif; ?>
                
                <!-- Imagem do produto -->
                <?php if(!empty($produto['img'])): ?>
                    <img src="<?php echo $produto['img']; ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($produto['nome']); ?>">
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
                    <p class="card-text text-muted small"><?php echo htmlspecialchars($produto['descricao'] ?? 'Produto disponível na loja'); ?></p>
                    
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
                        
                        <?php if($produto['estoque'] <= 0): ?>
                            <button class="btn btn-secondary w-100 mt-2" disabled>
                                <i class="fas fa-times"></i> Esgotado
                            </button>
                        <?php else: ?>
                            <button class="btn btn-primary btn-adicionar w-100 mt-2" data-id="<?php echo $produto['id']; ?>">
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
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    
    // Função para mostrar toast
    function showToast(message, type = 'success') {
        const toast = $('#toast-message');
        toast.removeClass('bg-success bg-danger').addClass(type === 'success' ? 'bg-success' : 'bg-danger');
        $('#toast-body').text(message);
        toast.fadeIn().delay(3000).fadeOut();
    }
    
    // Função para atualizar contador no menu
    function atualizarContador(contador) {
        const badge = $('#carrinho-contador');
        if(contador > 0) {
            badge.text(contador).fadeIn();
            badge.addClass('carrinho-pulse');
            setTimeout(() => badge.removeClass('carrinho-pulse'), 500);
        } else {
            badge.fadeOut();
        }
    }
    
    // Função genérica para adicionar ao carrinho
    function adicionarAoCarrinho(idProduto, botaoElement) {
        const botao = $(botaoElement);
        const textoOriginal = botao.html();
        
        botao.html('<i class="fas fa-spinner fa-spin"></i> Adicionando...').prop('disabled', true);
        
        $.ajax({
            url: '/Kanpeki/api/insert/carrinho/adicionar.php',
            method: 'POST',
            data: { id_produto: idProduto },
            dataType: 'json',
            success: function(response) {
                if(response.success) {
                    showToast(response.message, 'success');
                    if(response.count !== undefined) {
                        atualizarContador(response.count);
                    }
                    
                    botao.html('<i class="fas fa-check"></i> Adicionado!');
                    setTimeout(() => {
                        botao.html(textoOriginal).prop('disabled', false);
                    }, 1500);
                } else {
                    showToast(response.message, 'error');
                    botao.html(textoOriginal).prop('disabled', false);
                }
            },
            error: function() {
                showToast('Erro ao adicionar produto', 'error');
                botao.html(textoOriginal).prop('disabled', false);
            }
        });
    }
    
    // Adicionar ao carrinho (produtos normais)
    $('.btn-adicionar').click(function() {
        const idProduto = $(this).data('id');
        adicionarAoCarrinho(idProduto, this);
    });
    
    // Adicionar ao carrinho (produtos em promoção)
    $('.btn-adicionar-promo').click(function(e) {
        e.stopPropagation(); // Evitar que o clique no botão ative o clique no card
        const idProduto = $(this).data('id');
        adicionarAoCarrinho(idProduto, this);
    });
    
    // Countdown timer (só executa se o elemento existir)
    if($('#countdown').length) {
        function updateCountdown() {
            const targetDate = new Date();
            targetDate.setDate(targetDate.getDate() + 7);
            targetDate.setHours(23, 59, 59, 999);
            
            const now = new Date();
            const diff = targetDate - now;
            
            if(diff > 0) {
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (86400000)) / (3600000));
                const minutes = Math.floor((diff % 3600000) / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);
                
                $('#countdown').html(`${days}d ${hours}h ${minutes}m ${seconds}s`);
            } else {
                $('#countdown').html('Oferta encerrada!');
            }
        }
        
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
    
    // Mostrar toast se houver pesquisa com resultados
    <?php if(!empty($searchTerm) && count($produtos) > 0): ?>
    showToast('🔍 Encontramos <?php echo count($produtos); ?> produto(s) para sua busca', 'success');
    <?php elseif(!empty($searchTerm) && count($produtos) == 0): ?>
    showToast('😕 Nenhum produto encontrado para "<?php echo htmlspecialchars($searchTerm); ?>"', 'error');
    <?php endif; ?>
});
</script>
</body>
</html>