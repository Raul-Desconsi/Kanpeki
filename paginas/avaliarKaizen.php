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
                Formulários Kaizen
            </h3>
            <hr class="pink-normal-backgorund" style="height: 2px; opacity: 0.2;">
        </div>

        <div id="kaizengrid" class="d-flex flex-column gap-4">

            <div class="card kaizen-card status-recusado shadow-sm">
                <div class="row align-items-center">

                    <div class="col-md-9">
                        <div class="row mb-3">
                            <div class="col-12">
                                <span class="label-destaque">Título do Projeto</span>
                                <h4 class="titulo-kaizen fw-bold">Otimização do Fluxo de Carga e Descarga - Setor Norte
                                </h4>
                            </div>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-3 border-meio">
                                <span class="label-destaque">Responsável</span>
                                <span class="valor-destaque text-muted">Carlos Eduardo Alberto</span>
                            </div>

                            <div class="col-md-3 border-meio">
                                <span class="label-destaque">Departamento / Setor</span>
                                <span class="valor-destaque text-muted">Logística Operacional</span>
                            </div>

                            <div class="col-md-3 border-meio text-md-center">
                                <span class="label-destaque">Nível de Urgência</span>
                                <span class="badge bg-warning text-dark badge-custom shadow-sm">
                                    <i class="fa-solid fa-triangle-exclamation me-1"></i> Urgência Alta
                                </span>
                            </div>

                            <div class="col-md-3 text-md-center">
                                <span class="label-destaque">Status</span>
                                <span class="badge bg-status-recusado text-danger badge-custom shadow-sm">
                                    RECUSADO
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 text-md-end mt-4 mt-md-0">
                        <button type="button" class="btn btn-outline-success btn-abrir w-100 shadow-sm"
                            onclick="abrirKaizen()">
                            Visualizar Kaizen <i class="fa-solid fa-external-link ms-2"></i>
                        </button>
                    </div>

                </div>
            </div>


            
        </div>
    </div>

    <script src="../ativos/plugins/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/avaliarKaizen.js"></script>

</body>

</html>