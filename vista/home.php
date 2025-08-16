<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head.php'; ?>
    <title>Inicio | Mister Bolsa C.A</title>
    <link rel="stylesheet" href="assets/css/home.css">
</head> 

<body>
<!-- LOADER-->
<div id="loaderPagina" class="section_loader">
  <span class="loader"></span>
</div>

  <!-- ||||||||||||||  Comienzo de Sidebar y Nav |||||||||||||||||||||||-->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
  <?php require_once 'complementos/sidebar.php'; ?>
 
    <div class="body-wrapper">
    <?php require_once 'complementos/nav.php'; ?>
    <div class="container-fluid">
  <!--|||||||||||||||   Fin de Sidebar y Nav  |||||||||||||||||||||||||||-->
  <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item active color-text1" aria-current="page">Bienvenido@ </li>
    <li class="breadcrumb-item"><a href="?pagina=home">Inicio</a></li>
  </ol>
</nav>

<div class="row">
  
  <div class="carousel">
<div class="wrap">
<img src="assets/images/carrusel/C01.png" alt="" />
<img src="assets/images/carrusel/C02.png" alt="" />
<img src="assets/images/carrusel/C03.png" alt="" />
<img src="assets/images/carrusel/C07.png" alt="" />
<img src="assets/images/carrusel/C04.png" alt="" />
<img src="assets/images/carrusel/C05.png" alt="" />
<img src="assets/images/carrusel/C06.png" alt="" />

<img src="assets/images/carrusel/C01.png" alt="" />
<img src="assets/images/carrusel/C02.png" alt="" />
<img src="assets/images/carrusel/C03.png" alt="" />
<img src="assets/images/carrusel/C07.png" alt="" />
<img src="assets/images/carrusel/C04.png" alt="" />
<img src="assets/images/carrusel/C05.png" alt="" />
<img src="assets/images/carrusel/C06.png" alt="" />
 
</div>
</div>
  
</div>
 <br>
<div class="row">
  <h5>Acceso Directo</h5>
</div>

<div class="row">
  
    <?php if( $tipo_usuario == 1 || $tipo_usuario == 2) { ?> 
<div class="col">
  <div class="card card-home c1" style="width: 14rem;">
    <div class="card-body card-body-home  text-center">
    <i class="fa-solid fa-receipt"></i>
      <h4 class="mb-3">Compras</h4> 
      <?php 
      $totalc = $totalCompra['total'];
      ?>
      Total de Registros
      <h5 class=" mb-2"><b> <?php echo $totalc; ?></b> </h5>
      <a href="?pagina=compra" class="btn btn-light btn-home">Ir a Compra</a>
    </div>
  </div>
</div>
<?php } ?>

  <div class="col">
  <div class="card card-home c2" style="width: 14rem;">
    <div class="card-body card-body-home  text-center">
      <i class="fa-solid fa-boxes-stacked"></i> 
      <h4 class="mb-3">Producto</h4>
      <?php 
      $total = $totalProductos['total']; 
      ?>
      Total de Registros
      <h5 class="mb-2"> <b><?php echo $total; ?></b> </h5>
      <a href="?pagina=producto" class="btn btn-light btn-home">Ir a Producto</a>
    </div>
  </div>
</div>


  <div class="col">
  <div class="card card-home c1" style="width: 14rem;">
    <div class="card-body card-body-home  text-center">
    <i class="fa-solid fa-cash-register"> </i>
      <h4 class="mb-3">Ventas</h4>
      <?php 
      $totalv = $totalVenta['total']; 
      ?>
      Total de Registros
      <h5 class=" mb-2"><b> <?php echo $totalv; ?></b> </h5>
      <a href="?pagina=venta" class="btn btn-light btn-home">Ir a Venta</a>
    </div>
  </div>
</div>

  <div class="col">
  <div class="card card-home c2" style="width: 14rem;">
    <div class="card-body card-body-home  text-center">
    <i class="fa-solid fa-money-bill-transfer"></i>
      <h4 class="mb-3">Precio del Dolar</h4>
      <?php foreach ($dolar as $dolar){  ?>
    Tasa de cambio
      <h5 class="mb-2"><b>Bs <?php echo $dolar['precios']?></b> </h5>
      <?php } ?>
      <a href="#" class="btn btn-light btn-home" type="button" data-bs-toggle="offcanvas"
      data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Cambiar Tasa</a>
    </div>
  </div>
</div>
</div>
 

<!-- |||||||||||||||||||||||||   TASA DOLAR   |||||||||||||||||||||||||||||||||| -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasRightLabel"><i class="fa-solid fa-money-bill-transfer"></i> Actualizar Tasa del Dolar</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
  <form action="?pagina=home" id="t" autocomplete="off">

<label class="form-label text-dark">Precio $ A Bs</label>
<input type="text" class="form-control text-dark" name="precios" id="inputtasadolar" placeholder="0.00 "  required>
<span id="textotasadolar"  class="vali-span"></span> 
<input type="hidden" name="id_dolar" value="1"> <br>

<button type="button" class="btn btn-success text-dark" id="tasadolarre">
<i class="fa-solid fa-money-bill-transfer"></i> Actualizar Tasa</button>
<button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas" aria-label="Close">Cerrar</button>
</form>
  </div>
</div>

<!--|||||||||||||||| MODAL DE AVISO ||||||||||||||||||||| -->
<div class="modal fade" id="miModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
     
      <div class="modal-body">
        <div class="d-flex justify-content-center">
     
       <img src="assets/images/aviso.png" alt="" width="100%">

       </div>
       <div class="d-flex justify-content-center">
       <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
       </div>
      </div>
      
 
    </div>
  </div>
</div>



  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <?php require_once 'complementos/footer.php'; ?>
  <script src="assets/js/home.js"></script>

  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||--> 
  
  <?php if(isset($_SESSION['message'])): ?> <!-- Mensaje de Registro y Modificacion y eliminacion --> 
    <script>
        Swal.fire({
            title:'<?php echo $_SESSION['message']['title']; ?>',
            text: '<?php echo $_SESSION['message']['text']; ?>',
            icon: '<?php echo $_SESSION['message']['icon']; ?>',
            confirmButtonColor: '#457B9D',
            confirmButtonText: 'Aceptar'    
        });
    </script>
    <?php unset($_SESSION['message']); ?> <!-- Eliminar el mensajes de la sesion -->
<?php endif; ?> 
</body>
</html>