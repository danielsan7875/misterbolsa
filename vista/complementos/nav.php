<!--  Header Start -->
<header class="app-header color-nav">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2 color-1"></i>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-icon-hover" href="vista/complementos/manueldeusuario.pdf" target="_blank">
              <i class="fa-solid fa-circle-info color-1"></i>
            
                <div class="notification bg-danger rounded-circle color-1"></div>
              </a>
            </li>
             <li class="nav-item">
             
              
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
            
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                    <i class="fa-solid fa-lock-open"></i>
                      <p class="mb-0 fs-3">Cambiar Constraseña</p>
                    </a>
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
   