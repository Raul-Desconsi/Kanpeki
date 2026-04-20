<?php


// 🔥 GARANTE QUE A FUNÇÃO EXISTA
if(function_exists('contarItensCarrinho')) {
    $contadorCarrinho = contarItensCarrinho();
} else {
    $contadorCarrinho = isset($_SESSION['carrinho']) 
        ? array_sum(array_column($_SESSION['carrinho'], 'qtd')) 
        : 0;
}
?>

<nav class="navbar navbar-expand-lg white-background shadow-sm border-bottom-pink sticky-top">
  <div class="container-fluid px-4">
    
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center fw-bold dark" href="loja.php">
      <div class="me-3">
        <img src="../ativos/imagens/Kanpeki-logo-nbg.png" width="50px" alt="logo">
      </div>
      KANPEKI
    </a>

    <!-- Botão mobile -->
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
      <i class="fas fa-bars dark"></i>
    </button>

    <div class="collapse navbar-collapse" id="navContent">

      <!-- MENU ESQUERDA -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link nav-custom-link dark" href="loja.php">
            <i class="fas fa-store me-1"></i> Loja
          </a>
        </li>

        <!-- 🔥 NOVO: HISTÓRICO -->
        <li class="nav-item">
          <a class="nav-link nav-custom-link dark" href="historico.php">
            <i class="fas fa-box me-1"></i> Histórico
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link nav-custom-link dark" href="criarKaizen.php">
            <i class="fas fa-lightbulb me-1"></i> Criar Kaizen
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link nav-custom-link dark" href="historicoKaizens.php">
            <i class="fa-solid fa-clock-rotate-left me-1"></i> Ver meus Kaizens
          </a>
        </li>

        <?php  
        if ($_SESSION['usuario']['permissao'] > 1) {
            echo '<li class="nav-item">
                    <a class="nav-link nav-custom-link dark" href="avaliarKaizen.php">
                        <i class="fa-solid fa-scale-balanced me-1"></i> Avaliar Kaizens
                    </a>
                  </li>';
        } 
        ?>
      </ul>

      <!-- MENU DIREITA -->
      <div class="d-flex align-items-center gap-3">

        <!-- BUSCA -->
        <form class="d-none d-md-flex" id="form-pesquisa" action="/Kanpeki/paginas/loja.php" method="GET">
            <div class="input-group" style="width: 250px;">
                <input type="text" 
                       class="form-control border-end-0 rounded-pill" 
                       name="search" 
                       id="search-input"
                       placeholder="🔍 Buscar produtos..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button class="btn btn-outline-secondary rounded-pill" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <!-- BOTÃO MOBILE -->
        <button class="btn d-md-none border p-2 rounded-pill" id="btn-search-mobile" type="button">
            <i class="fas fa-search dark"></i>
        </button>

        <!-- 🛒 CARRINHO -->
        <a href="carrinho.php" class="btn d-flex align-items-center border p-2 rounded-pill position-relative">
          
          <div class="avatar-small pink-soft-background d-flex align-items-center justify-content-center rounded-circle me-2">
            <i class="fas fa-shopping-cart pink-normal"></i>
          </div>

          <span class="dark fw-semibold d-none d-sm-inline me-2">Carrinho</span>

          <span id="carrinho-contador" 
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                style="<?php echo $contadorCarrinho <= 0 ? 'display:none;' : ''; ?>">
            <?php echo $contadorCarrinho; ?>
          </span>
        </a>

        <!-- 💰 SALDO -->
        <div class="points-container d-flex align-items-center px-3 py-2 rounded-pill">
          <i class="fas fa-coins me-2"></i>
          <span id="pontosUsuario">
            <?php echo htmlspecialchars($_SESSION['usuario']['pontos']); ?> pts
          </span>
        </div>

        <!-- 👤 PERFIL -->
        <a href="perfil.php" class="btn d-flex align-items-center border p-2 rounded-pill">
          <i class="fas fa-user me-2"></i>
          <?php echo htmlspecialchars($_SESSION['usuario']['nome']); ?>
        </a>

        <!-- 🚪 LOGOUT -->
        <a href="login.php" class="btn border p-2 rounded-pill">
          Sair
        </a>

      </div>
    </div>
  </div>
</nav>

<!-- MODAL MOBILE -->
<div class="modal fade" id="modal-search">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <form action="/Kanpeki/paginas/loja.php" method="GET">
                <input type="text" name="search" class="form-control" placeholder="Buscar...">
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../ativos/plugins/bootstrap/bootstrap.bundle.min.js"></script>

<script>
// 🔥 AJAX CONTADOR
function atualizarContadorCarrinho() {
    $.get('/Kanpeki/api/insert/carrinho/contador.php', function(response){
        const badge = $('#carrinho-contador');

        if(response.count > 0){
            badge.text(response.count).fadeIn().addClass('carrinho-pulse');
            setTimeout(() => badge.removeClass('carrinho-pulse'), 500);
        } else {
            badge.fadeOut();
        }
    });
}

$(document).ready(function(){

    $('#btn-search-mobile').click(() => $('#modal-search').modal('show'));

    setInterval(atualizarContadorCarrinho, 5000);

});
</script>