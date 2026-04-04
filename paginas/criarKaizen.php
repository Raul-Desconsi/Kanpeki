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

    <?php include("menu.php"); ?>

    <div class="container border-pink-soft rounded-3 shadow my-5 p-5">
        <div class="card border-0">

            <!-- Cabeçalho -->
            <div class="card-header text-center bg-white border-0 mb-4">
                <h4 class="fw-bold mb-0">
                    <i class="fa-solid fa-clipboard me-2"></i>
                    Criar Formulário de Melhoria
                </h4>
            </div>

            <div class="card-body">

                <!-- Identificação -->
                <div class="text-center mb-4">
                    <h5 class="fw-bold">
                        <i class="fa-solid fa-user me-1"></i> Identificação
                    </h5>
                </div>

                <hr class="mb-5">

                <div class="row g-5 mb-5">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">Crachá</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text pink-soft-background border-0">
                                <i class="fa-solid fa-id-badge"></i>
                            </span>
                            <input type="text" id="crachaInput" disabled
                                value="<?php echo htmlspecialchars($_SESSION['usuario']['cracha']); ?>"
                                class="form-control" placeholder="Digite o crachá">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold mb-2">Nome do Funcionário</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text pink-soft-background border-0">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input type="text" id="nomeInput" disabled readonly
                                value="<?php echo htmlspecialchars($_SESSION['usuario']['nome']); ?>"
                                class="form-control" placeholder="Nome completo">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold mb-2">Setor</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text pink-soft-background border-0">
                                <i class="fa-solid fa-briefcase"></i>
                            </span>
                            <input type="text"  disabled readonly
                                value="<?php echo htmlspecialchars($_SESSION['usuario']['nome_setor']); ?>"
                                class="form-control" placeholder="Setor">
                        </div>
                    </div>
                </div>

                <!-- Dados -->
                <div class="text-center mb-4">
                    <h5 class="fw-bold">
                        <i class="fa-solid fa-lightbulb me-1"></i> Dados da Melhoria
                    </h5>
                </div>

                <hr class="mb-5">

                <!-- Título -->
                <div class="mb-5">
                    <label class="form-label fw-semibold mb-2">Título da Melhoria</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text pink-soft-background border-0">
                            <i class="fa-solid fa-lightbulb"></i>
                        </span>
                        <input type="text" id="tituloMelhoriaId" class="form-control"
                            placeholder="Digite o título da melhoria">
                    </div>
                </div>

                <!-- Textareas -->
                <div class="mb-5">
                    <label class="form-label fw-semibold mb-2">Descrição do problema</label>
                    <textarea id="problemaInput"
                        placeholder="Descreva o problema identificado no seu setor ou posto de trabalho"
                        class="form-control form-control-lg" rows="4"></textarea>
                </div>

                <div class="mb-5">
                    <label class="form-label fw-semibold mb-2">Descrição da Melhoria</label>
                    <textarea id="melhoriaInput" placeholder="Descreva a melhoria proposta para solucionar o problema"
                        class="form-control form-control-lg" rows="4"></textarea>
                </div>

                <div class="mb-5">
                    <label class="form-label fw-semibold mb-2">Resultado Esperado</label>
                    <textarea id="resultadoInput"
                        placeholder="Descreva o resultado esperado e quais benefícios essa melhoria trará"
                        class="form-control form-control-lg" rows="4"></textarea>
                </div>

                <!-- Complementos -->
                <div class="text-center mb-4">
                    <h5 class="fw-bold">
                        <i class="fa-solid fa-plus"></i> Complementos
                    </h5>
                </div>

                <hr class="mb-5">

                <div class="row g-5 mb-5">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold mb-2">Tipo de Melhoria</label>
                        <select id="tipoDropdown" class="form-control form-control-lg">
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold mb-2">Urgência</label>
                        <select id="urgenciaDropdown" class="form-control form-control-lg">
                            <option selected disabled>Selecione o nível de urgência</option>
                        </select>
                    </div>
                </div>

                <hr class="mb-5">

                <!-- Botões -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <button type="button" onclick="criarKaizen()" class="btn btn-outline-success  w-100 py-2">
                            Salvar
                        </button>
                    </div>

                    <div class="col-md-6">
                        <button type="button" class="btn btn-outline-warning  w-100 py-2">
                            Cancelar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script src="../ativos/plugins/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/criarKaizen.js"></script>
</body>

</html>