<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head.php'; ?>
    <title>Acceso Denegado | Mister Bolsa C.A</title>
</head>

<body>
  <!-- LOADER-->
<div id="loaderPagina" class="section_loader">
<div class="loader-3"></div>
</div>
  <!-- ||||||||||||||  Comienzo de Sidebar y Nav |||||||||||||||||||||||-->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
  <?php require_once 'vista/complementos/sidebar.php'; ?>
 
    <div class="body-wrapper">
    <?php require_once 'vista/complementos/nav.php'; ?>
    <div class="container-fluid">
  <!--|||||||||||||||   Fin de Sidebar y Nav  |||||||||||||||||||||||||||-->


<div class="row m-4">
  <div class="col">
  <h2> <b> Estimado Usuario: </b> </h2>
  <h2> <b> <?php echo $_SESSION['nombre']." ".$_SESSION['apellido']; ?> </b> </h2>
  <br>  <br> <br> 
  <h3 class="mb-5">Acceso denegado. Por favor, contacte al administrador para solicitar los permisos correspondientes.</h3>  
  <br>  <br> <br> 
  <a href="?pagina=home" class="btn btn-primary"> <i class="fas fa-home mr-2"></i> Volver al Inicio</a>
  </div>
  <div class="col">
  <img src="assets/images/error/acceso.svg" alt="" width="90%">
  </div>
</div>
        
 
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <?php require_once 'vista/complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->    
</body>
</html>