<!--  Header Start -->
  <script>
  const idUsuario = <?php echo json_encode($_SESSION['id']); ?>;
</script>

<header class="app-header color-nav  navv">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2 color-1"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="vista/complementos/manueldeusuario.pdf" target="_blank">
              <i class="fa-solid fa-circle-info color-1" title="ayuda - Manual de usuario"></i>
            
                <div class="notification bg-danger rounded-circle color-1"></div>
              </a>
            </li>
             <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="?pagina=registroventa">
              <i class="fa-solid fa-cash-register color-1"></i>
              </a>
              
            </li>
          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
              
            <div class="text-left me-3 tex"> <!-- Cambia text-center a text-left -->
    <h6> <?php 
        echo $_SESSION['nombre']." ".$_SESSION['apellido'];
    ?> </h6>
    <p> <?php 
      echo $_SESSION['nombre_tipo'];
    ?></p>
</div>
              
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="assets/images/profile/user8.png" alt="" width="35" height="35" class="rounded-circle">
                </a>
            
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up div-oscuro-1" aria-labelledby="drop2">
                  <div class="message-body div-oscuro-1">
                    
                    <button type="button" id="toggleModo" class="texto-secondario d-flex align-items-center gap-2 dropdown-item text-dark">
                      <i class="fa-solid fa-moon me-2"></i> 
                      <p class="mb-0 fs-3">Modo Oscuro</p>
                    </button> 
            <?php if($_SESSION['nivel'] < 2){?>
                    <a href="?pagina=tasa" class="texto-secondario d-flex align-items-center gap-2 dropdown-item text-dark">
                    <i class="fa-solid fa-money-bill-trend-up me-2 texto-secundario"></i>
                      <p class="mb-0 fs-3 texto-secundario">Cambiar Tasa Dolar</p>
                    </a>

            <?php } ?>

                    <button type="button" name="cerrar" class="btn btn-outline-danger mx-3 mt-2 d-block shadow-none"
                    data-bs-toggle="modal" data-bs-target="#modalcerrar">Cerrar Session</button>
                  </div>

                </div>
              </li>

            </ul>
          </div>
        </nav>
      </header>
      <!--  Header End -->
   