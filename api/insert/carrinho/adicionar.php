<?php
session_start();
header('Content-Type: application/json');

require_once($_SERVER['DOCUMENT_ROOT'] . "/Kanpeki/api/config/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Kanpeki/api/phpFunction/verificaLogin.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Kanpeki/api/phpFunction/carrinho.php");

$response = ['success' => false, 'message' => '', 'count' => 0];

$id = $_POST['id_produto'] ?? null;

if(!$id){
    $response['message'] = 'Produto não identificado';
    echo json_encode($response);
    exit;
}

// buscar produto
$stmt = $conexao->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$produto){
    $response['message'] = 'Produto não encontrado';
    echo json_encode($response);
    exit;
}

// adicionar ao carrinho
adicionarCarrinho($produto);

$response['success'] = true;
$response['message'] = 'Produto adicionado ao carrinho!';
$response['count'] = contarItensCarrinho();

echo json_encode($response);
?>