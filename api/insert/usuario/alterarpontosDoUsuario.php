<?php

function alterarPontos($conexao, $cracha, $valor)
{
    if ($cracha === null || $valor === null) {
        return false;
    }

    if (isset($conexao)) {

        $sql = "
        UPDATE usuario
        SET pontos = pontos + :valor
        WHERE cracha = :cracha
        ";

        $stmt = $conexao->prepare($sql);

        $stmt->bindParam(':cracha', $cracha, PDO::PARAM_STR);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_INT);

        return $stmt->execute();
    }

    return false;
}