
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | MisterBolsa C.A</title>
      <!-- Google Fonts -->
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

      <link rel="shortcut icon" type="image/png" href="assets/images/logos/mister-ico.png" />

      <link rel="stylesheet" href="assets/librerias/bootstrap5/css/bootstrap.min.css" />

      <link rel="stylesheet" href="assets/libs/fontawesome/css/all.min.css" />

      <link rel="stylesheet" href="assets/css/SystemPosLogin.css" />

       <script src="assets/libs/jquery/jquery.min.js"></script>

      <script src="assets/js/loader.js"></script>
    
    <style>
       
    </style>
</head>
<body>
 <!-- LOADER-->
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

    <div class="container-fluid main-container">
        <div class="row login-wrapper">
            
            <!-- Panel Izquierdo: Formulario -->
            <div class="col-lg-6 left-panel">
                <div class="brand-logo">
                    <i class="fa-solid fa-store"></i> MisterBolsa C.A
                </div>

                <div class="login-header">
                    <h2>Bienvenido de nuevo</h2>
                    <p>Por favor, ingresa tus datos para continuar.</p>
                </div>

                <form action="?pagina=login" method="POST" autocomplete="off" id="credenciales">
                    <div class="mb-3">
                        <label for="user" class="form-label"> <i class="fa-solid fa-user me-2"></i>Usuario</label>
                        <input type="text" class="form-control " name="usuario" id="usuario" placeholder="Ejemplo: mister1234" />
                        <span id="textousuario" class="text-error"></span>
                    </div>
                    <div class="mb-3">
                           
                            <label for="password" class="form-label"> <i class="fa-solid fa-lock me-2"></i>Contraseña</label>
                            <div class="password-input">
                               <input type="password" name="clave" id="password" class="form-control" placeholder="••••••••">
                            <span id="show-password" class="fa fa-eye"></span>
                             </div>
                           <span id="textopassword" class="text-error"></span>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="remember" checked>
                            <label class="form-check-label text-secondary" for="remember">
                                Actualizar dolar
                            </label>
                        </div>
                        <a href="#" id="forgotPass" class="text-decoration-none small">¿Olvidaste tu contraseña?</a>
                    </div>
                    
                    <input type="hidden" name="dolar" id="bcvfor">
                    <button type="submit" name="ingresar" class="btn btn-primary w-100 mb-3" id="ingresar">Iniciar Sesión</button>

                   
                </form>

                <p class="text-center mt-4 text-secondary">
                    © 2026 Desarollador Daniel E Sanchez | Mister Bolsa C.A
                </p>
            </div>

            <!-- Panel Derecho: Imagen -->
            <div class="col-lg-6 right-panel">
                <!-- Imagen bonita de arquitectura/paisaje (Placeholder de Unsplash) -->
                <img src="assets/images/portada1.jpg" alt="Imagen Decorativa" class="bg-image">
                
                <div class="overlay-text">
                    <h3>SystemPos</h3>
                    <p>Control de Inventario - Compra y venta de productos</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
 
      <script src="assets/js/sweetalert2.js"></script>
        <script src="assets/librerias/bootstrap5/js/bootstrap.min.js"></script>
        <script src="assets/librerias/bootstrap5/js/bootstrap.bundle.min.js"></script>
      <script src="assets/js/login.js"></script>
      <script>
document.getElementById("forgotPass").addEventListener("click", function(e) {
    e.preventDefault(); // evita que recargue la página

    Swal.fire({
        title: "Recuperar contraseña",
        text: "Comunícate con el administrador para restablecer tu contraseña.",
        icon: "info",
        confirmButtonText: "Entendido",
        confirmButtonColor: "#3085d6"
    });
});
</script>
<script>
document.getElementById('remember').addEventListener('change', function () {
    const hidden = document.getElementById('bcvfor');

    if (this.checked) {
        hidden.value = "1";   // o el valor que quieras enviar
    } else {
        hidden.value = "";    // vacío = no se envía
    }
});
</script>


</body>
</html>