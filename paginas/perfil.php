<?php
session_start();
$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../ativos/imagens/Kanpeki-logo-nbg.png" type="image/x-icon">
    <title>Perfil - Kanpeki</title>

    <link href="../ativos/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/componentes.css">
    <link rel="stylesheet" href="css/cores.css">
    <link rel="stylesheet" href="css/menu.css">
    <link rel="stylesheet" href="css/perfil.css">
</head>

<body>

    <?php include("menu.php"); ?>

    <div hidden id="cracha"> <?php echo htmlspecialchars($_SESSION['usuario']['cracha']); ?>
    </div>

    <div class="container mt-4 mb-5">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <h2 class="mb-4">Informações do Perfil</h2>

                <form>
                    <div class="row g-4">

                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-light rounded-4">
                                <div class="card-body text-center">

                                    <img src="../ativos/imagens/user.png" alt="Foto de perfil"
                                        class="img-fluid rounded-circle mb-3"
                                        style="width: 240px; height: 240px; object-fit: cover;">


                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card h-100 border-0 bg-light rounded-4">
                                <div class="card-body">
                                    <h5 class="mb-3">Dados do Usuário</h5>

                                    <div class="mb-3">
                                        <label class="form-label">Crachá</label>
                                        <input type="text" class="form-control" id="crachaLbl" readonly value=""
                                            disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nome</label>
                                        <input type="text" class="form-control" id="nome" readonly name="nome" value="">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Setor</label>
                                        <input type="text" class="form-control" id="setor" readonly name="setor"
                                            value="" readonly>
                                    </div>


                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card border-0 bg-light rounded-4">
                                <div class="card-body">
                                    <h5 class="mb-3">Alterar Senha</h5>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Nova senha</label>
                                            <input type="password" class="form-control" id="nova_senha"
                                                name="nova_senha">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Confirmar nova senha</label>
                                            <input type="password" class="form-control" id="confirmar_senha"
                                                name="confirmar_senha">
                                        </div>
                                    </div>

                                    <button type="button" onclick="salvarsenha()"
                                        class="btn btn-dark rounded-pill px-4">
                                        Salvar alterações
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../ativos/plugins/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="js/perfil.js"></script>

</body>

</html>