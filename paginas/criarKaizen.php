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
    <?php
    include("menu.php");
    ?>

    <div class="container mt-4 pt-2">
        <div class="card border-pink-soft rounded-2 shadow">
            <div class="card-title d-flex align-items-center  justify-content-center ">

                <h3><b><i class="fa-solid fa-clipboard"></i> Criar formulário de melhoria</b></h3>
            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-id-badge"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                                aria-describedby="basic-addon1">
                        </div>
                    </div>
                   <div class="col">
                        <h4>Nome do funcionário</h4>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-id-badge"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Username" aria-label="Username"
                                aria-describedby="basic-addon1">
                        </div>


                </div>
            </div>


            <script src="../ativos/plugins/bootstrap/bootstrap.bundle.min.js"></script>

        </div>
</body>

</html>