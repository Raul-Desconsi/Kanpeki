<?php
session_start();
header('Content-Type: application/json');

require_once(__DIR__ . "/../../config/connect.php");
require_once(__DIR__ . "/../../phpFunction/verificaLogin.php");
require_once(__DIR__ . "/../../phpFunction/carrinho.php");

$response = ['success' => false, 'message' => '', 'total' => 0, 'count' => 0];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'] ?? null;
    $quantidade = $_POST['quantidade'] ?? null;
    
    if($id && $quantidade !== null){
        atualizarQuantidade($id, (int)$quantidade);
        
        $response['success'] = true;
        $response['message'] = 'Carrinho atualizado';
        $response['total'] = totalCarrinho();
        $response['count'] = contarItensCarrinho();
        
        // Calcular subtotal do item
        $carrinho = getCarrinho();
        if(isset($carrinho[$id])){
            $response['subtotal'] = $carrinho[$id]['preco'] * $carrinho[$id]['qtd'];
        }
    } else {
        $response['message'] = 'Dados inválidos';
    }
}

echo json_encode($response);
?>