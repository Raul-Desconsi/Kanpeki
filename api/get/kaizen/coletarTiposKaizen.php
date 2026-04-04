<?php

session_start();
include __DIR__ . '/../../config/connect.php'; 

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($conexao)) {
    try {
        $sql = "SELECT * FROM tipo";
        $stmt = $conexao->prepare($sql);
        $stmt->execute();

        $resposta = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tipo = [];

        foreach ($resposta as $row) {
            $tipo[] = $row; 
        }

        http_response_code(200);
        echo json_encode([
            "tipos" => $tipo
        ]);

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