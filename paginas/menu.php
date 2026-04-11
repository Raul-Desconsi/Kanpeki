<<<<<<< HEAD
<?php
// Verificar se a função existe antes de chamar
if(function_exists('contarItensCarrinho')) {
    $contadorCarrinho = contarItensCarrinho();
} else {
    // Fallback caso a função não esteja disponível
    $contadorCarrinho = isset($_SESSION['carrinho']) ? array_sum(array_column($_SESSION['carrinho'], 'qtd')) : 0;
}
?>
=======

>>>>>>> abe08d8901d0ac4c02d6ef4751265503ee79ebae

<nav class="navbar navbar-expand-lg white-background shadow-sm border-bottom-pink sticky-top">
  <div class="container-fluid px-4">
    
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center fw-bold dark" href="loja.php">
      <div class=" me-3">
        <img src="../ativos/imagens/Kanpeki-logo-nbg.png" width="50px" alt="logo">
      </div>
      KANPEKI
    </a>

    <!-- Botão mobile -->
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
      <i class="fas fa-bars dark"></i>
    </button>

    <!-- Conteúdo da Navbar -->
    <div class="collapse navbar-collapse" id="navContent">

      <!-- Menu esquerda -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link nav-custom-link dark" href="loja.php"><i class="fas fa-store me-1"></i> Loja</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-custom-link dark" href="criarKaizen.php"><i class="fas fa-lightbulb me-1"></i>Criar Kaizen</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-custom-link dark" href="historicoKaizens.php"><i class="fa-solid fa-clock-rotate-left me-1"></i>Ver meus Kaizens</a>
        </li>
       
       <?php  
    if ($_SESSION['usuario']['permissao'] > 1) {
        echo '<li class="nav-item">
                <a class="nav-link nav-custom-link dark" href="avaliarKaizen.php"><i class="fa-solid fa-scale-balanced me-1"></i> Avaliar Kaizens</a>
              </li>';
    } 
    ?>
      </ul>

      <!-- Menu direita -->
      <div class="d-flex align-items-center gap-3">

        <!-- 🔍 BARRA DE PESQUISA DESKTOP -->
        <form class="d-none d-md-flex" id="form-pesquisa" action="/Kanpeki/paginas/loja.php" method="GET">
            <div class="input-group" style="width: 250px;">
                <input type="text" 
                       class="form-control border-end-0 rounded-pill" 
                       name="search" 
                       id="search-input"
                       placeholder="🔍 Buscar produtos..." 
                       value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                       style="border-radius: 50px 0 0 50px; font-size: 0.9rem;">
                <button class="btn btn-outline-secondary rounded-pill" type="submit" style="border-radius: 0 50px 50px 0;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <!-- 🔍 Botão pesquisa mobile -->
        <button class="btn d-md-none border p-2 rounded-pill hover-soft shadow-sm" id="btn-search-mobile" type="button">
            <i class="fas fa-search dark"></i>
        </button>

        <!-- CARRINHO COM CONTADOR -->
        <a href="carrinho.php" class="btn d-flex align-items-center text-decoration-none border p-2 rounded-pill hover-soft shadow-sm position-relative">
          <div class="avatar-small pink-soft-background d-flex align-items-center justify-content-center rounded-circle me-2">
            <i class="fas fa-shopping-cart pink-normal"></i>
          </div>
          <span class="dark fw-semibold d-none d-sm-inline me-2">Carrinho</span>
          
          <!-- Badge contador -->
          <span id="carrinho-contador" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                style="font-size: 0.7rem; <?php echo $contadorCarrinho <= 0 ? 'display: none;' : ''; ?>">
            <?php echo $contadorCarrinho; ?>
            <span class="visually-hidden">itens no carrinho</span>
          </span>
        </a>

        <!-- Saldo/Pontos do usuário -->
        <div class="points-container pink-soft-background border-pink-normal d-flex align-items-center px-3 py-2 rounded-pill">
          <i class="fas fa-coins me-2 pink-normal"></i>
          <div class="d-flex flex-column text-end">
            <span class="points-title dark" style="font-size: 0.6rem; font-weight: 800; text-transform: uppercase;">Saldo</span>
            <span class="dark fw-bold" id="pontosUsuario" style="line-height: 1;"><?php echo htmlspecialchars($_SESSION['usuario']['pontos']); ?>
          </div>
        </div>

        <!-- Botão Perfil -->
        <a href="perfil.php" class="btn d-flex align-items-center text-decoration-none border p-2 rounded-pill hover-soft shadow-sm">
          <div class="avatar-small dark-background white d-flex align-items-center justify-content-center rounded-circle me-2">
            <i class="fas fa-user"></i>
          </div>
          <span class="dark fw-semibold d-none d-sm-inline me-2"><?php echo htmlspecialchars($_SESSION['usuario']['nome']); ?></span>
        </a>

         <!-- Botão Logout -->
        <a href="login.php" class="btn d-flex align-items-center text-decoration-none border p-2 rounded-pill hover-soft shadow-sm">
          <div class="avatar-small bg-danger white d-flex align-items-center justify-content-center rounded-circle me-2">
            <i class="fa-solid fa-arrow-right-to-bracket"></i>
          </div>
          <span class="danger fw-semibold d-none d-sm-inline me-2">Sair</span>
        </a>

      </div>
    </div>
  </div>
</nav>

<!-- 🔍 MODAL DE PESQUISA MOBILE -->
<div class="modal fade" id="modal-search" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-4">
                <form action="/Kanpeki/paginas/loja.php" method="GET">
                    <div class="input-group">
                        <input type="text" 
                               class="form-control" 
                               name="search" 
                               placeholder="Buscar produtos..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                               style="border-radius: 50px 0 0 50px;">
                        <button class="btn btn-primary rounded-pill" type="submit" style="border-radius: 0 50px 50px 0;">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animação para o contador do carrinho */
    @keyframes pulse-carrinho {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.2);
        }
        100% {
            transform: scale(1);
        }
    }
    
    .carrinho-pulse {
        animation: pulse-carrinho 0.5s ease-in-out;
    }
    
    /* Badge do carrinho */
    #carrinho-contador {
        font-size: 0.65rem;
        padding: 0.25rem 0.4rem;
        margin-top: -5px;
        margin-right: -5px;
        transition: all 0.3s ease;
    }
    
    /* Estilo da barra de pesquisa */
    #search-input:focus {
        box-shadow: none;
        border-color: #ff6b6b;
    }
    
    .form-control:focus {
        border-color: #ff6b6b;
        box-shadow: 0 0 0 0.2rem rgba(255,107,107,0.25);
    }
    
    /* Botão pesquisa mobile */
    #btn-search-mobile {
        transition: all 0.3s;
    }
    
    #btn-search-mobile:hover {
        transform: scale(1.05);
        background-color: #f8f9fa;
    }
</style>

<!-- jQuery (necessário para AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS -->
<script src="../ativos/plugins/bootstrap/bootstrap.bundle.min.js"></script>

<script>
// Função para atualizar o contador do carrinho via AJAX
function atualizarContadorCarrinho() {
    $.ajax({
        url: '/Kanpeki/api/insert/carrinho/contador.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            if(response.count !== undefined) {
                const badge = $('#carrinho-contador');
                const novoContador = response.count;
                
                if(novoContador > 0) {
                    badge.text(novoContador).fadeIn();
                    badge.addClass('carrinho-pulse');
                    setTimeout(function() {
                        badge.removeClass('carrinho-pulse');
                    }, 500);
                } else {
                    badge.fadeOut();
                }
            }
        },
        error: function() {
            console.log('Erro ao atualizar contador do carrinho');
        }
    });
}

// Modal de pesquisa mobile
$(document).ready(function() {
    // Abrir modal de pesquisa no mobile
    $('#btn-search-mobile').click(function() {
        $('#modal-search').modal('show');
    });
    
    // Fechar modal ao enviar formulário
    $('#modal-search form').submit(function() {
        $('#modal-search').modal('hide');
    });
    
    // Atualizar contador a cada 5 segundos
    setInterval(atualizarContadorCarrinho, 5000);
    
    // Live search (opcional - pesquisa ao digitar)
    let searchTimeout;
    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        const searchValue = $(this).val();
        
        if(searchValue.length >= 2) {
            searchTimeout = setTimeout(function() {
                $('#form-pesquisa').submit();
            }, 800);
        } else if(searchValue.length === 0) {
            searchTimeout = setTimeout(function() {
                $('#form-pesquisa').submit();
            }, 300);
        }
    });
});
</script>