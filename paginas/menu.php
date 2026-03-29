<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kanpeki</title>

    <!-- Bootstrap 5 -->
    <link href="../ativos/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="../ativos/plugins/bootstrap/bootstrap.bundle.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">

    <link rel="stylesheet" href="css/componentes.css">
    <link rel="stylesheet" href="css/cores.css">
    <link rel="stylesheet" href="css/menu.css">


</head>

<?php
echo <<<HTML
<body>

    <nav class="navbar navbar-expand-lg navbar-light pink-soft-background navbar-custom shadow-sm m-2 rounded-4 px-3">
        <div class="container-fluid">

            <a class="navbar-brand d-flex align-items-center fw-bold" href="#">
                <i class="fas fa-cube me-2"></i>
                Kanpeki
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">

                <ul class="navbar-nav me-auto gap-2">

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2" href="#">
                            <i class="fas fa-gift"></i>
                            Retirar Recompensa
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2" href="#">
                            <i class="fas fa-lightbulb"></i>
                            Preencher Kaizen
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center gap-2" href="#">
                            <i class="fas fa-history"></i>
                            Meus Kaizens
                        </a>
                    </li>

                </ul>

                    <div class="row"> 
                        <div class="col-4 ml-2">
                            <button class="btn btn-light d-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm"
                                type="button" data-bs-toggle="dropdown">

                                <i class="fas fa-user"></i>
                                <span id="idUsuario">João</span>
                            </button>
                        </div>
                        <div class="col-4 mr-2">
                            <button class="btn btn-light d-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm"
                                type="button" data-bs-toggle="dropdown">

                                <i class="fas fa-user"></i>
                                <span id="idUsuario">João</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

</body>
HTML;
?>

</html>