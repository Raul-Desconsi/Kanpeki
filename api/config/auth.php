<?php 

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
            http_response_code(401);
            echo json_encode(["erro" => "Sessão expirada. Faça login novamente."]);
            exit;
        }


$nivelUsuario = $_SESSION['usuario']['permissao'] ?? 0;