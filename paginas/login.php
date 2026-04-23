<?php
session_start();
$_SESSION = array();
session_destroy();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanpeki Store</title>

    <link href="../ativos/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="css/login.css?v=2">
</head>
<body>

<main class="login">
    <div class="login-left">
        <div class="login-box">
            <h2>Kanpeki</h2>
            <p>Entre para acessar a lojinha</p>

            <input type="text" id="cracha" class="form-control mb-2" placeholder="Crachá">
            <input type="password" id="senha" class="form-control mb-3" placeholder="Senha">

            <button class="btn btn-primary w-100" onclick="login()">Entrar</button>
        </div>
    </div>

    <div class="login-right">
        <img src="../ativos/imagens/logo.png" alt="Logo Kanpeki" class="login-right-img">
    </div>
</main>

<script src="../ativos/plugins/bootstrap/bootstrap.bundle.min.js"></script>
<script src="js/login.js"></script>
</body>
</html>