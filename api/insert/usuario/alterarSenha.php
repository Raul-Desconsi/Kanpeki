<?php

session_start();
include __DIR__ . '/../../config/connect.php'; 
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$input = json_decode(file_get_contents('php://input'), true);

$cracha = $input['cracha'] ?? null;
$nova_senha = $input['senha_nova'] ?? null;
$confirmar_senha = $input['confirmar_senha'] ?? null;

if ($nova_senha != $confirmar_senha) {
    http_response_code(400);
    echo json_encode([
        "mensagem" => "As senhas não são compatíveis"
    ]);
    exit;
}

if (!$cracha || !$nova_senha) {
    http_response_code(400);
    echo json_encode([
        "mensagem" => "Crachá e nova senha são obrigatórios"
    ]);
    exit;
}

if (isset($conexao)) {
    try {
        $sql = "UPDATE USUARIO SET SENHA = :senha_nova WHERE CRACHA = :cracha";

        $stmt = $conexao->prepare($sql);
        
        $stmt->bindParam(':senha_nova', $nova_senha, PDO::PARAM_STR);
        $stmt->bindParam(':cracha', $cracha, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode([
                "mensagem" => "Senha atualizada com sucesso"
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "mensagem" => "Usuário não encontrado"
            ]);
        }

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "mensagem" => "Erro no banco",
            "detalhes" => $e->getMessage()
        ]);
    }
} else {
    http_response_code(500);
    echo json_encode([
        "mensagem" => "Erro na conexão: variável \$conexao não definida"
    ]);
}
?>