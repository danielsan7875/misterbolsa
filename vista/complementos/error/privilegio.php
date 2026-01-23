<!doctype html>
<html lang="en">

<head>
       <?php require_once 'vista/complementos/head2.php'; ?>
    <title>Acceso Denegado | Mister Bolsa C.A</title>
</head>

<body>
    <?php require_once 'vista/complementos/nav2.php'; ?>
<?php require_once 'vista/complementos/sidebar2.php'; ?>

    <div class="transaction-list shadow-sm-0 DivOscuro1">
      <div class="container py-5">
  <div class="row align-items-center text-center text-md-start">
    
    <!-- Texto -->
    <div class="col-md-6 mb-4 mb-md-0">
      <h2 class="fw-bold mb-2">Estimado Usuario:</h2>
      <h2 class="fw-bold text-primary mb-4">
        <?php echo $_SESSION['nombre']." ".$_SESSION['apellido']; ?>
      </h2>

      <h3 class=" text-muted mb-5">
        Acceso denegado. Por favor, contacte al administrador para solicitar los permisos correspondientes.
      </h3>

      <a href="?pagina=home" class="btn btn-lg btn-primary px-5 py-3 shadow">
        <i class="fas fa-home me-2"></i> Volver al Inicio
      </a>
    </div>

    <!-- Imagen -->
    <div class="col-md-6 text-center">
      <img src="assets/images/error/acceso.svg" alt="Acceso denegado" class="img-fluid" style="max-width: 420px;">
    </div>

  </div>
</div>

 </div>       
 
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
<?php require_once 'vista/complementos/footer2.php'; ?>
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->    
</body>
</html>