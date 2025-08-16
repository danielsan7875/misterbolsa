<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Login | Mister Bolsa C.A</title>
    <link rel="stylesheet" href="assets/css/login.css" />
    <link rel="stylesheet" href="assets/libs/fontawesome/css/all.min.css" />
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/mister-ico.png" />
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/js/sweetalert2.js"></script>
  </head>
  <body>
    <div class="form-section">
      <span class="logo"><i class="fas fa-shopping-bag" style="color: #E63946;"></i></span>
      <h2>Inicio de Sessión</h2>
      <span class="sub-heading">Ingrese su usuario y clave para acceder al sistema</span>
     
      <form action="?pagina=login" method="POST" autocomplete="off">
        
        <label for="name"><i class="fa-solid fa-user"></i> Usuario</label>
        <input class="form-control" type="text" name="usuario" id="usuario" placeholder="Usuario:" />
        <span id="textousuario" class="vali-span"></span>

        <label for="password"><i class="fa-solid fa-lock"></i> Constraseña</label>
        <div class="password-input">
                <input type="password" name="clave" id="password"  class="form-control" placeholder="Contraseña:">
                <span id="show-password" class="fa fa-eye"></span>
         </div>
        <span id="textopassword" class="vali-span"></span>
        

        <button type="submit" name="ingresar" class="btn-primary mt">Iniciar Session</button>
      </form>
      <!--
      <div class="footer-group">
        <span>Already Registered? <a href="">Sign In</a></span>
        <a href="">Forgot password?</a>
      </div>
      -->
    </div>

    <div class="hero-section">
      <h2>
        SISTEMA DE COMPRA Y VENTA
        DE PRODUCTOS 
      </h2>
      <span class="sub-heading">
      <b>MISTER BOLSA, C.A RIF: J-29979563-1</b>
    </span>
      <img src="assets/images/q6.svg" alt="" />
    </div>




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