<?php

session_start();
include __DIR__ . '/../../config/connect.php'; 

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($conexao)) {
    try {
        $sql = "SHOW COLUMNS FROM formulario LIKE 'urgencia'";
        $stmt = $conexao->prepare($sql);
        $stmt->execute();

        $coluna = $stmt->fetch(PDO::FETCH_ASSOC);

        $urgencias = [];

        if ($coluna) {
            preg_match("/^enum\((.*)\)$/", $coluna['Type'], $matches);

            if (isset($matches[1])) {
                $valores = explode(",", $matches[1]);

                foreach ($valores as $valor) {
                    $urgencias[] = trim($valor, "'");
                }
            }
        }

        http_response_code(200);
        echo json_encode([
            "urgencia" => $urgencias
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