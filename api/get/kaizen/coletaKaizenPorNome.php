<?php

session_start();
include __DIR__ . '/../../config/connect.php'; 

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$input = json_decode(file_get_contents('php://input'), true);

$nome   = $input['nome'] ?? null;
$filtro = $input['filtro'] ?? null; 

if (isset($conexao)) {
    try {

        $sql = "
            SELECT 
                FORM.*, 
                TIPO.NOME AS tipo_nome, 
                STR.NOME AS setor_nome,
                USR.NOME AS nome_funcionario
            FROM FORMULARIO FORM 
            INNER JOIN TIPO TIPO ON TIPO.ID = FORM.TIPO_ID
            INNER JOIN SETOR STR ON STR.ID = FORM.SETOR_ID
            INNER JOIN USUARIO USR ON USR.CRACHA = FORM.FUNCIONARIO_CRACHA
            WHERE 1=1
        ";

        if ($filtro === "aprovados") {
            $sql .= " AND FORM.STATUS = 'APROVADO'";
        }

        if ($filtro === "recusados") {
                    $sql .= " AND FORM.STATUS = 'RECUSADO'";
        }

        
        if ($nome) {
            $sql .= " AND FORM.TITULO LIKE :nome";
        }

        $stmt = $conexao->prepare($sql);

        if ($nome) {
            $nomeLike = "%$nome%";
            $stmt->bindParam(':nome', $nomeLike, PDO::PARAM_STR);
        }

        $stmt->execute();

        $kaizens = $stmt->fetchAll(PDO::FETCH_ASSOC);

        http_response_code(200);
        echo json_encode([
            "kaizens" => $kaizens
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
        "mensagem" => "Erro na conexão"
    ]);
}