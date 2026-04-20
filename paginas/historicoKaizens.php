<?php
session_start();
require_once("../api/phpFunction/verificaLogin.php");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../ativos/imagens/Kanpeki-logo-nbg.png" type="image/x-icon">

    <title>Kanpeki</title>

    <link href="../ativos/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="css/componentes.css">
    <link rel="stylesheet" href="css/cores.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/avaliarKaizen.css">

</head>

<body>
    <?php include("menu.php"); ?>

    <div id="cracha" hidden>
    <?php echo htmlspecialchars($_SESSION['usuario']['cracha']); ?>
    </div>

    <div id="conteudo-pagina" class="container-fluid px-md-5 my-5">

        <div class="card card-busca shadow-sm mb-5">
            <div class="input-group input-group-lg">
                <input id="pesquisaInput" type="text" class="form-control" placeholder="Pesquise pelo nome do Kaizen">
        
                <select  id="filtroPesquisa" class="form-control  form-control-lg flex-grow-0 w-auto px-4 text-muted" style="min-width: 200px;">
                    <option value="todos" selected>Mostrar Todos</option>
                    <option value="aprovados">Mostrar Aprovados</option>
                    <option value="recusados">Mostrar Recusados</option>
                    
                </select>


                 <button onclick="insereKaizenNoGrid()" class="btn btn-success px-4" type="button">
                    <i class="fa-solid fa-magnifying-glass me-2"></i> Buscar
                </button>
            </div>
        </div>

        <div class="mb-5">
            <h3 class="fw-bold d-flex align-items-center">
                <i class="fa-solid fa-clipboard-check me-3 pink-normal fa-lg"></i>
                Formulários Kaizen Cadastrado pelo Usuário
            </h3>
            <hr class="pink-normal-backgorund" style="height: 2px; opacity: 0.2;">
        </div>

        <div id="kaizengrid" class="d-flex flex-column gap-4">
        </div>
    </div>

    <script src="../ativos/plugins/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/historicoDeKaizens.js"></script>

</body>

</html>