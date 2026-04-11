<?php
session_start();

include __DIR__ . '/../../config/connect.php'; 

header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$input = json_decode(file_get_contents('php://input'), true);

$cracha = $_SESSION['usuario']['cracha'] ?? null;
$setor  = $_SESSION['usuario']['setor_id'] ?? null; 

$titulo   = $input['titulo']   ?? null;
$melhoria = $input['melhoria'] ?? null; 
$problema = $input['problema'] ?? null;
$resultado= $input['resultado']?? null; 
$tipo     = $input['tipo']     ?? null;
$urgencia = $input['urgencia'] ?? null; 

if (!$cracha || !$setor) {
    http_response_code(401);
    echo json_encode(["mensagem" => "Usuário não autenticado"]);
    exit;
}

if (!$titulo || !$melhoria || !$problema || !$resultado || !$tipo || !$urgencia) { 
    http_response_code(400);
    echo json_encode(["mensagem" => "Faltam dados"]);
    exit;
}

if (!isset($conexao)) {
    http_response_code(500);
    echo json_encode(["mensagem" => "Erro na conexão"]);
    exit;
}

try {

    $conexao->beginTransaction();

    $sql = "INSERT INTO formulario (
        tipo_id, setor_id, funcionario_cracha, titulo,
        descricao_melhoria, descricao_resultado,
        descricao_problema, urgencia, data
    ) VALUES (
        :tipo_id, :setor_id, :cracha, :titulo,
        :melhoria, :resultado, :problema,
        :urgencia, CURRENT_TIMESTAMP
    )";

    $stmt = $conexao->prepare($sql);

    $stmt->execute([
        ':tipo_id' => $tipo,
        ':setor_id' => $setor,
        ':cracha' => $cracha,
        ':titulo' => $titulo,
        ':melhoria' => $melhoria,
        ':resultado' => $resultado,
        ':problema' => $problema,
        ':urgencia' => $urgencia
    ]);

    $log = $conexao->prepare("
        INSERT INTO logs (funcionario_cracha, acao, data, erro)
        VALUES (:cracha, 'CADASTRAR KAIZEN', CURRENT_TIMESTAMP, 'SUCESSO')
    ");
    $log->execute([':cracha' => $cracha]);

    $conexao->commit();

    http_response_code(201);
    echo json_encode(["mensagem" => "Cadastrado com sucesso!"]);

} catch (PDOException $e) {

    $conexao->rollBack();

    try {
        $logErro = $conexao->prepare("
            INSERT INTO logs (funcionario_cracha, acao, data, erro)
            VALUES (:cracha, 'CADASTRAR KAIZEN', CURRENT_TIMESTAMP, :erro)
        ");

        $logErro->execute([
            ':cracha' => $cracha,
            ':erro' => $e->getMessage()
        ]);

    } catch (PDOException $logError) {
        error_log("Erro ao salvar log: " . $logError->getMessage());
    }

    http_response_code(500);
    echo json_encode([
        "mensagem" => "Erro no servidor",
    ]);
}