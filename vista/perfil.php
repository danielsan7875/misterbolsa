<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once 'vista/complementos/head2.php'; ?>
    <title>Mi Perfil | Mister Bolsa C.A</title>
   <style>
       
   </style>
</head>
<body>
    <?php require_once 'vista/complementos/nav2.php'; ?>
<?php require_once 'vista/complementos/sidebar2.php'; ?>


   <div class="transaction-list shadow-sm border-0 DivOscuro1">

<!-- 1. Banner Superior -->
<div class="profile-cover"></div>

<div class="container">
    <!-- 2. Encabezado con Avatar superpuesto -->
    <div class="profile-header-content px-3 px-md-0">
        <div class="avatar-wrapper">
            <!-- Icono de persona en círculo como pidió el diseño -->
            <div class="avatar-icon-placeholder rounded-circle shadow">
                <i class="fa-solid fa-user"></i>
            </div>
        </div>
        <div class="user-info-text ">
            <h2 class="TextOscuro"><?php echo $_SESSION["nombre"].' '.$_SESSION["apellido"];?></h2>
            <p class="TextOscuro"><i class="bi bi-briefcase me-2"></i>Cargo: <?php echo $_SESSION["nombre_tipo"];?></p>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card card1 mb-5 DivOscuro2">
                <div class="card-header card-header1 d-sm-flex align-items-center justify-content-between DivOscuro2">
                     Infomacion Personal  
                    <div class="d-flex gap-2">
                          <button type="button" class="btn btn-primary" id="Teayuda">
                                <i class="fa-solid fa-circle-question me-2"></i>
                                Ayuda
                            </button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted small mb-4">Puede cambiar tu información personal aquí.</p>
                    
                    <form action="?pagina=perfil" method="POST" id="forPerfil" autocomplete="off">
                        <!-- Campos: Nombre, Apellido y Usuario -->
                        <div class="row g-3 TA1">
                          
                            <div class="col-md-4">
                                <label class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $_SESSION["nombre"];?>" placeholder="Tu nombre">
                                   <span id="textonombre" class="text-error mt-0"></span> 
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" value="<?php echo $_SESSION["apellido"];?>" placeholder="Tu apellido">
                                   <span id="textoapellido" class="text-error mt-0"></span> 
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nombre de Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0">@</span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="usuario" name="usuario" value="<?php echo $_SESSION["usuario"];?>" placeholder="usuario">
                                </div>
                                   <span id="textousuario" class="text-error mt-0"></span> 
                            </div>
                        
                        </div>

                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION["id"] ?>">

                          <!-- Botones de Acción -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
                            <button type="button" class="btn btn-success shadow-sm" id="actualizar">Guardar Cambios</button>
                        </div>
</form>
                    <form action="?pagina=perfil" method="POST" id="forClave" autocomplete="off">
                        <!-- 3. Sección de Cambio de Clave -->
                        <div class="password-section DivOscuro2 TA2">
                            <h5 class="fw-bold mb-3"><i class="bi bi-lock-fill me-2 text-primary"></i>Cambiar Contraseña</h5>
                            <div class="row g-3">
                                  
                                <div class="col-12">
                                    <label class="form-label">Contraseña Actual</label>
                                    <input type="text" class="form-control" name="actual" id="actual" placeholder="••••••••">
                                         <span id="textoactual" class="text-error mt-0"></span> 
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nueva Contraseña</label>
                                    <input type="text" class="form-control" name="nueva" id="nueva" placeholder="Nueva clave">
                                         <span id="textonueva" class="text-error mt-0"></span> 
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirmar Contraseña</label>
                                    <input type="text" class="form-control"  name="confirmar" id="confirmar" placeholder="Repite la nueva clave">
                                         <span id="textoconfirmar" class="text-error mt-0"></span> 
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id_usuario" value="<?php echo $_SESSION["id"] ?>">

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="reset" class="btn btn-outline-secondary">Limpiar</button>
                            <button type="button" class="btn btn-success shadow-sm" id="clave">Actulizar clave</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


</div>
<?php require_once 'vista/complementos/footer2.php'; ?>
<script src="assets/js/perfil.js"></script>
  <script>
      const driver = window.driver.js.driver;
          const driverObj = driver({
              showProgress: true,
              // Textos en ESPAÑOL
              nextBtnText: 'Siguiente →',
              prevBtnText: '← Atrás',
              doneBtnText: '¡Entendido!',
              progressText: 'Paso {{current}} de {{total}}',
              steps: [
                  { 
                      // Usamos el punto (.) para referirnos a la clase
                      element: '.TA1',  popover: { title: 'Informacion Personal', 
                          description: 'Actualiza tu nombre, apellido y nombre de usuario para que aparezcan correctamente en tus registros, luego si modificaste un datos haz click en Guarda cambios', side: "top" 
                      } 
                  },
                  { 
                      element: '.TA2', popover: { title: 'cambiar la clave', 
                          description: 'Actualiza tu nombre, apellido y nombre de usuario para que aparezcan correctamente en tus registros. coloca tu clave actual y posteriormente coloca la contraseña nueva de acceso', side: "top" 
                      } 
                  }
                  
              ]
          });

  document.querySelector('#Teayuda').addEventListener('click', () => {
    driverObj.drive();
  });  
  </script>
</body>
</html>