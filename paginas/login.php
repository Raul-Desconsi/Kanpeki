<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanpeki Store</title>

    <link href="../ativos/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <link rel="stylesheet" href="css/componentes.css">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

    <main class="login" id="login">
        
        <div class="login-left">
            <h2>Kanpeki</h2>
            <p>Entre para acessar a lojinha</p>

            <input type="text" id="cracha" class="form-control mb-2" placeholder="Crachá">
            <input type="password" id="senha" class="form-control mb-3" placeholder="Senha">

            <button class="btn btn-primary w-100" onclick="login()">Entrar</button>
        </div>

        <div class="login-right">
            <img src="../ativos/imagens/logo.png" alt="Logo Kanpeki" class="logo-right">
            <p>Troque suas ideias por recompensas 🎁</p>
        </div>

    </main>

    <script src="../ativos/plugins/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/login.js"></script> 
</body>
</html>