<?php

session_start();
include __DIR__ . '/../../config/connect.php';

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$input = json_decode(file_get_contents('php://input'), true);

$id = $input['id'] ?? null;

if (isset($conexao)) {
    try {
        $sql = "select f.*, t.nome as tipo_nome, t.valor_base  
                from formulario f 
                inner join tipo t on t.id  = f.tipo_id
                where f.id =:id";
                
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $resposta = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resposta) {
           
            http_response_code(200);
            echo json_encode([
                "kaizen" => $resposta
            ]);
        } else {
            http_response_code(404);
            echo json_encode([
                "mensagem" => "Não encontrado"
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