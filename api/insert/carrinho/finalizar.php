<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT'] . "/Kanpeki/api/config/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Kanpeki/api/phpFunction/verificaLogin.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Kanpeki/api/phpFunction/carrinho.php");

$usuario = $_SESSION['usuario'];
$carrinho = $_SESSION['carrinho'] ?? [];

$total = totalCarrinho();

if($total <= 0){
    header("Location: /Kanpeki/paginas/carrinho.php");
    exit;
}

// valida saldo
if($usuario['pontos'] < $total){
    echo "Saldo insuficiente";
    exit;
}

// desconta pontos
$stmt = $conexao->prepare("UPDATE usuario SET pontos = pontos - ? WHERE cracha = ?");
$stmt->execute([$total, $usuario['cracha']]);

// 🔥 DATA ÚNICA DO PEDIDO (AGRUPAMENTO)
$dataCompra = date("Y-m-d H:i:s");

// 🔥 SALVAR HISTÓRICO
foreach($carrinho as $item){

    $stmt = $conexao->prepare("
        INSERT INTO compra_historico 
        (produto_id, funcionario_cracha, quantidade, valor_total, data)
        VALUES (?, ?, ?, ?, ?)
    ");

    $valorTotalItem = $item['preco'] * $item['qtd'];

    $stmt->execute([
        $item['id'],
        $usuario['cracha'],
        $item['qtd'],
        $valorTotalItem,
        $dataCompra
    ]);
}

// atualiza sessão
$_SESSION['usuario']['pontos'] -= $total;

// limpa carrinho
unset($_SESSION['carrinho']);

header("Location: /Kanpeki/paginas/loja.php?msg=compra_realizada");
exit;
?>