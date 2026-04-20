<?php

session_start();
include __DIR__ . '/../../config/connect.php';
include __DIR__ . '/../../../api/insert/usuario/alterarpontosDoUsuario.php';
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$input = json_decode(file_get_contents('php://input'), true);

$id = $input['id'] ?? null;
$cracha = $input['cracha'] ?? null;
$avaliacao = $input['avaliacao'] ?? 0;
$status = $input['status'] ?? null;
$observacao = $input['observacao'] ?? null;

if (isset($conexao)) {
    try {

        $conexao->beginTransaction();

        $sqlValor = "
        SELECT 
            IF(
                :status = 'REPROVADO',
                0,
                COALESCE(SUM(TP.valor_base + :avaliacao), 0)
            ) AS valor_avaliado
        FROM tipo TP
        INNER JOIN formulario FRM ON FRM.tipo_id = TP.id
        WHERE FRM.id = :id
        ";

        $stmt = $conexao->prepare($sqlValor);
        $stmt->execute([
            ':id' => $id,
            ':avaliacao' => $avaliacao,
            ':status' => $status
        ]);

        $valor_avaliado = $stmt->fetchColumn();

        $sqlInsert = "
        INSERT INTO formulario_vinculo 
        (funcionario_cracha, id_formulario, valor_avaliado, status_aprovacao, observacao, data)
        VALUES (:cracha, :id, :valor, :status, :observacao, NOW())
        ";

        $stmt = $conexao->prepare($sqlInsert);
        $stmt->execute([
            ':cracha' => $cracha,
            ':id' => $id,
            ':valor' => $valor_avaliado,
            ':status' => $status,
            ':observacao' => $observacao
        ]);

        $sqlUpdate = "UPDATE formulario SET status = :status WHERE id = :id";

        $stmt = $conexao->prepare($sqlUpdate);
        $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);

        $resposta = alterarPontos($conexao, $cracha, $valor_avaliado);

        if ($resposta) {
            $conexao->commit();

            http_response_code(201);
            echo json_encode([
                "mensagem" => "Avaliação cadastrada com sucesso"
            ]);
        } else {
            $conexao->rollBack();

            http_response_code(500);
            echo json_encode([
                "mensagem" => "Erro ao alterar o saldo do usuário"
            ]);
        }

    } catch (PDOException $e) {

        if ($conexao->inTransaction()) {
            $conexao->rollBack();
        }

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
?>```