<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Login | Mister Bolsa C.A</title>
  <?php require_once 'complementos/head.php'; ?>
    <!-- Style -->
    <link rel="stylesheet" href="assets/css/style.css">

  </head>
  <body>
    <div class="d-lg-flex half">
    <div class="bg order-1 order-md-2" style="background-image: url('assets/images/fondo1.avif');"></div>
    <div class="contents order-2 order-md-1">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-7 text-dark">
            <h1><strong>Inicio de Sesión</strong></h1>
            <p class="mb-2 text-dark" style="font-size: 18px !important;"><strong class="text-mister">MISTER BOLSA, C.A RIF: J-29979563-1</strong> - Inventario / Venta Productos</p>
            <p class="mb-4 text-dark" style="font-size: 16px !important;">Ingrese su usuario y clave para acceder el sistema</p>
            
            <form action="?pagina=login" method="POST" autocomplete="off" id="credenciales">
              <div class="form-group first mb-3">
                <label for="username" style="font-size: 15px !important;" ><i class="fa-solid fa-user me-2 mb-2"></i>Usuario</label>
                <input type="text" class="form-control text-dark border border-dark" name="usuario" id="usuario" placeholder="Usuario:" />
                <span id="textousuario" class="text-error"></span>
              </div>
            
              <div class="form-group last mb-3">
                <label for="password" style="font-size: 15px !important;"> <i class="fa-solid fa-lock me-2 mb-2"></i> Contraseña</label>
                 <div class="password-input">
                <input type="password" name="clave" id="password"  class="form-control text-dark border border-dark" placeholder="Contraseña:">
                <span id="show-password" class="fa fa-eye"></span>
              </div>
               <span id="textopassword" class="text-error"></span>

               <input type="hidden" name="dolar" id="bcvfor">
              </div>
              <hr>
               <button type="submit" name="ingresar" class="btn btn-primary lg-btn" width="100%" id="ingresar">Iniciar Session</button>
            </form>
              
          </div>
        </div>
      </div>
    </div>

    
  </div>
  <script src="assets/js/sweetalert2.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    
 <!-- MENSAJES Y JS-->    

<script src="assets/js/login.js"></script>
  <?php if(isset($_SESSION['message'])): ?>  <!-- Mensaje de Alertas -->
    <script>
        Swal.fire({      
            title: '<?php echo $_SESSION['message']['title']; ?>',
            text: '<?php echo $_SESSION['message']['text']; ?>',
            icon: '<?php echo $_SESSION['message']['icon']; ?>',
            confirmButtonColor: '#EF233C',
            confirmButtonText: 'Aceptar' 
        });
    </script>
    <?php unset($_SESSION['message']); ?> <!-- Eliminar el mensaje de la sesión -->
<?php endif; ?>


  </body>
</html>


<!--

Dirrecion: calle domingo mendez entre av. Libertador y juan de Dios ponte cabudare

-->