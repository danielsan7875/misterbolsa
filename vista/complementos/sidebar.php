    <!-- Sidebar Start -->
    <aside class="left-sidebar sidebar">
      <!-- Sidebar scroll-->
      <div class="scroll-sidebar" data-simplebar>
        <div class="d-flex mb-4 align-items-center justify-content-between">
            <a href="?pagina=home" class="text-nowrap logo-img ms-0 ms-md-1">
              <img src="assets/images/mister-logo.png" width="200" alt="">
            </a>
            
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
              <i class="ti ti-x fs-8"></i> 
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <input type="hidden" id="dolares">
          <ul id="sidebarnav" class="mb-4 pb-2">
            
            <li class="nav-small-cap ">
              <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
              <span class="hide-menu color-2"><b>Home</b></span> <!--- titulo-->
            </li>
            <li class="sidebar-item text-principal">
              <a
                class="sidebar-link sidebar-link primary-hover-bg "
                href="?pagina=home"
                aria-expanded="false"
              > <!--- VISTA-->
                <span class="aside-icon p-2 bg-light-primary text-principal rounded-3">
                  <i class="fa-solid fa-house fs-7 text-primary "></i>
               
                </span>
                <span class="hide-menu ms-2 ps-1 texto-cuarto"><b>Inicio</b></span>
              </a>
            </li>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
              <span class="hide-menu color-2"><b>Administrar Producto</b></span>
            </li>
            <li class="sidebar-item">
              <a
                class="sidebar-link sidebar-link primary-hover-bg"
                href="?pagina=producto"
                aria-expanded="false"
              >
                <span class="aside-icon p-2 bg-light-primary rounded-3">
                  <i class="fa-solid fa-boxes-stacked fs-7 text-primary"></i>
                
                </span>
                <span class="hide-menu ms-2 ps-1 texto-cuarto"><b>Producto</b></span> 
              </a>
            </li>
            <?php if( $tipo_usuario == 1 || $tipo_usuario == 2) { ?> 
            <li class="sidebar-item">
              <a
                class="sidebar-link sidebar-link primary-hover-bg"
                href="?pagina=compra"
                aria-expanded="false"
              >
                <span class="aside-icon p-2 bg-light-primary rounded-3">
                  <i class="fa-solid fa-receipt fs-7 text-primary"></i>
                </span>
                <span class="hide-menu ms-2 ps-1 texto-cuarto"><b>Compra </b></span>
              </a>
            </li>

            <?php } ?>
            
            <li class="sidebar-item">
              <a
                class="sidebar-link sidebar-link primary-hover-bg"
                href="?pagina=venta"
                aria-expanded="false"
              >
                <span class="aside-icon p-2 bg-light-primary rounded-3">
                  <i class="fa-solid fa-cash-register fs-7 text-primary"></i>
                 
                </span>
                <span class="hide-menu ms-2 ps-1 texto-cuarto"><b>Venta</b></span>
              </a>
            </li>
           
            <?php if( $tipo_usuario == 1 || $tipo_usuario == 2) { ?> 
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
              <span class="hide-menu color-2"><b>Catalogar</b></span>
            </li>


            <li class="sidebar-item">
              <a
                class="sidebar-link sidebar-link primary-hover-bg"
                href="?pagina=marca"
                aria-expanded="false"
              >
                <span class="aside-icon p-2 bg-light-primary rounded-3">
                  <i class="fa-solid fa-tag fs-7 text-primary"></i>
                </span>
                <span class="hide-menu ms-2 ps-1 texto-cuarto"><b>Marca</b></span>
              </a>
            </li>
            <li class="sidebar-item">
              <a
                class="sidebar-link sidebar-link primary-hover-bg"
                href="?pagina=presentacion"
                aria-expanded="false"
              >
                <span class="aside-icon p-2 bg-light-primary rounded-3">
                  <i class="fa-solid fa-shapes fs-7 text-primary"></i>
                
                </span>
                <span class="hide-menu ms-2 ps-1 texto-cuarto"><b>Presentación</b></span>
              </a>
            </li>
            <?php } ?>

            <?php if( $tipo_usuario == 1) { ?>
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-5"></i>
              <span class="hide-menu color-2"><b>Administrar Usuario</b></span>
            </li>
            <li class="sidebar-item">
              <a
                class="sidebar-link sidebar-link primary-hover-bg"
                href="?pagina=usuario"
                aria-expanded="false"
              >
                <span class="aside-icon p-2 bg-light-primary rounded-3">
                  <i class="fa-solid fa-users fs-7 text-primary"></i>
                  
                </span>
                <span class="hide-menu ms-2 ps-1 texto-cuarto"><b>Usuario</b><span>
              </a>
            </li>
            <li class="sidebar-item">
              <a
                class="sidebar-link sidebar-link primary-hover-bg"
                href="?pagina=tipousuario"
                aria-expanded="false"
              >
                <span class="aside-icon p-2 bg-light-primary rounded-3">
                  <i class="fa-solid fa-user-tag fs-7 text-primary"></i>
                </span>
                <span class="hide-menu ms-2 ps-1 texto-cuarto"><b>Tipo de Usurario</b></span>
              </a>
            </li>
            <?php } ?>
          </ul>
          

          
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
    <!--  Sidebar End -->