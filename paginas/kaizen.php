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
</head>

<body>

    <div hidden id="nivelUsuario">
        <?php echo htmlspecialchars($_SESSION['usuario']['permissao']); ?>
    </div>


    <?php include("menu.php"); ?>

    <div class="container my-5">

        <div class="card border-pink-soft rounded-3 shadow p-4">

            <!-- Cabeçalho -->
            <div class="card-header text-center mb-4 bg-white border-0">
                <h4 class="fw-bold mb-0">
                    <i class="fa-solid fa-clipboard me-2"></i>
                    <span id="tituloFormulario"></span>
                </h4>
            </div>

            <div class="card-body">

                <!-- Problema -->
                <div class="mb-5">
                    <h5 class="fw-bold mb-4">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i>
                        Descrição do problema
                    </h5>
                    <div id="descricaoProblema"></div>
                    <hr>
                </div>

                <!-- Melhoria -->
                <div class="mb-5">
                    <h5 class="fw-bold mb-4">
                        <i class="fa-solid fa-lightbulb me-1"></i>
                        Descrição da Melhoria
                    </h5>
                    <div id="descricaoMelhoria"></div>
                    <hr>
                </div>

                <!-- Resultado -->
                <div class="mb-5">
                    <h5 class="fw-bold mb-4">
                        <i class="fa-solid fa-check me-1"></i>
                        Resultado Esperado
                    </h5>
                    <div id="resultadoEsperado"></div>
                    <hr>
                </div>

                <!-- Complementos -->
                <div class="text-center mb-4">
                    <h5 class="fw-bold">
                        <i class="fa-solid fa-plus"></i> Complementos
                    </h5>
                </div>

                <div class="row mb-5 justify-content-center">
                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold mb-2">Tipo de Melhoria</label>
                        <div id="tipoMelhoria"></div>
                    </div>

                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold mb-2">Urgência</label>
                        <div>
                            <span class="badge bg-secondary" id="urgencia"></span>
                        </div>
                    </div>

                    <div class="col-md-4 text-center">
                        <label class="form-label fw-semibold mb-2">Valor Base</label>
                        <div id="valorbase"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Avaliação -->
    <div id="avaliacaoGrid">

        <div class="container my-5">

            <div class="card border-pink-soft rounded-3 shadow p-4">

                <div class="card-header text-center bg-white border-0">
                    <h4 class="fw-bold mb-0">
                        <i class="fa-solid fa-star me-2"></i>
                        Avaliar formulário
                    </h4>
                </div>

                <div class="card-body">
                    <div class="row mb-5 justify-content-center">

                        <div class="col-md-3 text-center">
                            <label class="form-label fw-semibold mb-2">Valor Base</label>
                            <input id="baseValor" disabled type="number" class="form-control" placeholder="0">
                        </div>

                        <div class="col-1 d-flex justify-content-center align-items-center text-center">
                            <i class="fa-solid mt-4 fa-plus"></i>
                        </div>

                        <div class="col-md-3 text-center">
                            <label class="form-label fw-semibold mb-2">Valor da Avaliação</label>
                            <input id="avaliacaoValor" type="number" class="form-control" placeholder="">
                        </div>

                        <div class="col-1 d-flex justify-content-center align-items-center text-center">
                            <i class="fa-solid mt-4 fa-equals"></i>
                        </div>
                        <div class="col-md-3 text-center">
                            <label class="form-label fw-semibold mb-2">Somatória</label>
                            <input id="totalValor" disabled type="number" class="form-control" placeholder="">
                        </div>
                    </div>

                    <div class="row mb-5 justify-content-center">
                        <label class="form-label fw-semibold mb-2">Observação</label>
                        <div class="col-md-12 text-center">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                </div>
                                <textarea id="observacao" class="form-control" aria-label="With textarea"></textarea>
                            </div>
                        </div>
                    </div>

                    <hr class="mb-5">

                    <!-- Botões -->
                    <div class="row d-flex - justify-content-center g-4">
                        <div class="col-md-6">
                            <button type="button" onclick="criarKaizen()" class="btn btn-outline-success  w-100 py-2">
                                Aprovar
                            </button>
                        </div>

                        <div class="col-md-6">
                            <button type="button" onclick="criarKaizen()" class="btn btn-outline-danger  w-100 py-2">
                                Recusar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="../ativos/plugins/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/kaizen.js"></script>

</body>

</html>