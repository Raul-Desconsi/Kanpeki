<?php
session_start();
header('Content-Type: application/json');

require_once($_SERVER['DOCUMENT_ROOT'] . "/Kanpeki/api/config/connect.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Kanpeki/api/phpFunction/verificaLogin.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/Kanpeki/api/phpFunction/carrinho.php");

$response = ['success' => false, 'message' => '', 'total' => 0, 'count' => 0];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'] ?? null;
    
    if($id){
        removerItemCarrinho($id);
        
        $response['success'] = true;
        $response['message'] = 'Item removido com sucesso';
        $response['total'] = totalCarrinho();
        $response['count'] = contarItensCarrinho();
    } else {
        $response['message'] = 'ID inválido';
    }
}

echo json_encode($response);
?>