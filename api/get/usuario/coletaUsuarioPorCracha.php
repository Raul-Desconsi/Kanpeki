<?php

session_start();
include __DIR__ . '/../../config/connect.php'; 

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$input = json_decode(file_get_contents('php://input'), true);

$cracha = $input['cracha'] ?? null;
$senha = $input['senha'] ?? null; 



if (!$cracha || !$senha) {
    http_response_code(400);
    echo json_encode([
        "mensagem" => "Crachá e senha são obrigatórios"
    ]);
    exit;
}

if (isset($conexao)) {
    try {
        $sql = "SELECT * FROM usuario WHERE cracha = :cracha AND senha = :senha";

        $stmt = $conexao->prepare($sql);
        
        $stmt->bindParam(':cracha', $cracha, PDO::PARAM_STR);
        $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);

        $stmt->execute();

        $resposta = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resposta) {
            unset($resposta['senha']);
        
            http_response_code(200);
            echo json_encode([
                "mensagem" => "Login realizado com sucesso",
                "usuario" => $resposta 
            ]);
        } else {
            http_response_code(401);
            echo json_encode([
                "mensagem" => "Crachá ou senha inválidos"
            ]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["mensagem" => "Erro no banco", "detalhes" => $e->getMessage()]);
    }
} else {
    http_response_code(500);
    echo json_encode([
        "mensagem" => "Erro na conexão: variável \$conexao não definida"
    ]);
}
?>