 <script>
  const idUsuario = <?php echo json_encode($_SESSION['id']); ?>;
</script>

<div id="loading-screen">
<div id="wifi-loader">
    <svg class="circle-outer" viewBox="0 0 86 86">
        <circle class="back" cx="43" cy="43" r="40"></circle>
        <circle class="front" cx="43" cy="43" r="40"></circle>
        <circle class="new" cx="43" cy="43" r="40"></circle>
    </svg>
    <svg class="circle-middle" viewBox="0 0 60 60">
        <circle class="back" cx="30" cy="30" r="27"></circle>
        <circle class="front" cx="30" cy="30" r="27"></circle>
    </svg>
    <svg class="circle-inner" viewBox="0 0 34 34">
        <circle class="back" cx="17" cy="17" r="14"></circle>
        <circle class="front" cx="17" cy="17" r="14"></circle>
    </svg>
    <div class="text" data-text="Cargando"></div>
</div>
</div>

<!-- OVERLAY PARA CIERRE DE SIDEBAR -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- NAVBAR SUPERIOR -->
    <header class="top-navbar NavOscuro">
        <div class="d-flex align-items-center h-100">
            <!-- Hamburguesa -->
            <button id="hamburgerBtn" class="btn btn-action d-lg-none me-3 btn-hamburger" onclick="toggleSidebar()">
                <i class="fa-solid fa-bars"></i>
            </button>
            
            <!-- Logo Imagen -->
            <div class="logo-area d-flex align-items-center">
                <img src="assets/images/mister-logo.png" alt="NeoPay Logo" class="logo-img">
                <div class="logo-text ms-2 d-none d-sm-block">SystemPos</div>
            </div>

            <!-- Breadcrumb -->
            <div class="breadcrumb-container d-none d-lg-block">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="?pagina=home">Inicio</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $vista ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="d-flex align-items-center">
            <!-- Acciones de Escritorio -->
            <div class="nav-actions d-none d-md-flex me-4">
                <button class="btn-action" title="Ayuda"><i class="fa-solid fa-circle-question"></i></button>
             
                <button id="toggleModo" class="btn-action" title="Modo Oscuro"> <i class="fa-solid fa-moon"></i> </button>
            </div>

            <!-- Perfil Dropdown -->
            <div class="dropdown user-profile-dropdown">
                <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="profile-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="user-info d-none d-sm-block">
                        <span class="user-name TextOscuro">
                             <?php echo $_SESSION['nombre']." ".$_SESSION['apellido']; ?>
                        </span>
                        <span class="user-role">
                            <?php echo $_SESSION['nombre_tipo']; ?>
                        </span>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow DivOscuro2">
                    <!-- Items que aparecen solo en móvil dentro del dropdown -->
                    <li class="mobile-only-item"><a class="dropdown-item" href="#"><i class="fa-solid fa-circle-question me-2"></i> Ayuda</a></li>
                    <li class="mobile-only-item"><a class="dropdown-item" href="#"><i class="fa-solid fa-moon me-2"></i> Modo Oscuro</a></li>
                    <li class="mobile-only-item"><hr class="dropdown-divider"></li>
                    
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-user me-2"></i> Mi Perfil </a></li>
                    <li><a class="dropdown-item" href="#"><i class="fa-solid fa-gear me-2"></i> Configuracion</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#modalcerrar"><i class="fa-solid fa-right-from-bracket me-2"></i> Cerrar Session</a></li>
                </ul>
            </div>
        </div>
    </header>

