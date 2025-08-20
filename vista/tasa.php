<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head.php'; ?>
    <title>Tasa Dolar | Mister Bolsa C.A</title>
    <link rel="stylesheet" href="assets/css/for.css" />
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

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="?pagina=home">Inicio</a></li>
    <li class="breadcrumb-item active color-text1" aria-current="page">Tasa Dolar</li>
  </ol>
</nav>

  <div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900"> <i class="fa-solid fa-money-bill-trend-up me-2" style="color: #E63946;"></i>
        Configuracion del Tasa del Dolar</h1>
       
  </div>


  <div class="conteiner">
    <div class="row">
      <div class="col">
            <div class="card">
  <div class="card-header text-dark">
    <i class="fa-solid fa-floppy-disk me-2"></i> Tasa del Dolar BCV - Guardada
  </div>
  <div class="card-body">
    <h5 class="card-title">Tasa del Dolar (Guardada) </h5>
      <?php foreach ($consulta as $dolar){  ?>
    <h3 class="text-dark"><b> <?php echo $dolar['precios']?> Bs</b></h3>
      <?php } ?>
    <p class="card-text">Actualmente estás es la tasa de cambio de USD a Bolívares (Bs) guardada en nuestra base de datos.
        puedes modificarla manualmente en cualquier momento según tu preferencia o la tasa vigente.
    </p>

    <form action="?pagina=venta" id="dolarform" autocomplete="off">

      <label class="form-label text-dark"> Modificarla manualmente la tasa del $ a Bs</label>
      <div class="input-group mb-3">
        <input type="text" class="form-control text-dark" placeholder="0.00" id="inputtasadolar" name="precios">
        <button class="btn btn-success text-dark" type="button" id="actualizar">
          <i class="fa-solid fa-money-bill-transfer me-2"></i>Actualizar Tasa</button>
      </div>
      <span id="textotasadolar"  class="text-error mt-0"></span> 
      <input type="hidden" name="id_dolar" value="1">

       
    </form>

  </div>
</div>
      </div>

      <div class="col">
        <div class="card">
          <div class="card-header text-dark">
           <i class="fa-solid fa-wifi me-2"></i> Tasa del Dolar BCV - Internet
          </div>
          <div class="card-body">
            <h5 class="card-title">Tasa del Dolar (Actual - Via Internet) </h5>
            <h3 class="text-dark"><b id="inputdolar"></b></h3>
            <p class="card-text">
              Estás utilizando la tasa de cambio USD a Bs obtenida automáticamente desde internet.
              Si lo prefieres, puedes sincronizar esta tasa y actualizar la que está guardada en la base de datos.
            </p>
         <form action="?pagina=tasa" method="POST" autocomplete="off" id="formdolar">
          <input type="hidden" name="id_dolar" value="1">
          <input type="hidden" name="precios" id="preciobcv">
           <button  class="btn btn-primary" id="sincronizar" type="button"> <i class="fa-solid fa-rotate me-2"></i> Sincronizar y actualizar</button>
        </form>
          </div>
        </div>
      </div>
    </div>
  </div>


        
   <script src="assets/js/modulotasa.js"></script>
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <?php require_once 'vista/complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->    
</body>
</html>