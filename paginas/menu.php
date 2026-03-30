<?php 
session_start();
?>

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

<!-- Bootstrap JS -->
    <script src="../ativos/plugins/bootstrap/bootstrap.bundle.min.js"></script>
