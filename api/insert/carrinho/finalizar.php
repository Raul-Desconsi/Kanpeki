<?php
session_start();

require_once(__DIR__ . "/../../config/connect.php");
require_once(__DIR__ . "/../../phpFunction/verificaLogin.php");
require_once(__DIR__ . "/../../phpFunction/carrinho.php");

$usuario = $_SESSION['usuario'] ?? null;
$total = totalCarrinho();

if (!$usuario) {
    header("Location: ../../../paginas/login.php");
    exit;
}

if ($total <= 0) {
    header("Location: ../../../paginas/carrinho.php?erro=carrinho_vazio");
    exit;
}

// valida saldo
if ((float)$usuario['pontos'] < (float)$total) {
    header("Location: ../../../paginas/carrinho.php?erro=saldo_insuficiente");
    exit;
}

try {
    // desconta pontos
    $stmt = $conexao->prepare("UPDATE usuario SET pontos = pontos - ? WHERE cracha = ?");
    $stmt->execute([$total, $usuario['cracha']]);

    // atualiza sessão
    $_SESSION['usuario']['pontos'] -= $total;

    // salva histórico sem usar banco
    if (!isset($_SESSION['historico_compras']) || !is_array($_SESSION['historico_compras'])) {
        $_SESSION['historico_compras'] = [];
    }

    $itensCompra = [];
    foreach ($_SESSION['carrinho'] as $item) {
        $quantidade = isset($item['qtd']) ? (int)$item['qtd'] : (isset($item['quantidade']) ? (int)$item['quantidade'] : 1);

        $itensCompra[] = [
            'produto' => $item['nome'],
            'quantidade' => $quantidade,
            'preco_unitario' => (float)$item['preco'],
            'total' => (float)$item['preco'] * $quantidade,
            'img' => $item['img'] ?? ''
        ];
    }

    $_SESSION['historico_compras'][] = [
        'data' => date('d/m/Y H:i:s'),
        'total_geral' => $total,
        'quantidade_itens' => count($itensCompra),
        'itens' => $itensCompra
    ];

    // limpa carrinho
    unset($_SESSION['carrinho']);

    header("Location: ../../../paginas/loja.php?msg=compra_realizada");
    exit;
} catch (Exception $e) {
    header("Location: ../../../paginas/carrinho.php?erro=falha_finalizar");
    exit;
}
?>