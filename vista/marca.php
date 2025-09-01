<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head.php'; ?>
    <title>Marca | Mister Bolsa C.A</title>
     <link rel="stylesheet" href="assets/css/for.css" />
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
 
    <div class="body-wrapper body-oscuro">
    <?php require_once 'complementos/nav.php'; ?>
    <div class="container-fluid">
  <!--|||||||||||||||   Fin de Sidebar y Nav  |||||||||||||||||||||||||||-->

  <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="?pagina=home">Inicio</a></li>
    <li class="breadcrumb-item active color-text1 texto-secundario" aria-current="page">Marca</li>
  </ol>
</nav>

<div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900 texto-principal"> <i class="fa-solid fa-tag  mr-3" style="color: #E63946;"></i>
        Marca</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
          <button type="button" class="btn btn-success text-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <span class="icon ">
            <i class="fas fa-file-medical me-2"></i>
            </span>
            <span class="text ">Registrar Marca</span>
      </button>
      </div>

<div class="card shadow mb-4"> <!-- comienzo del Cuadro blanco-->
<div class="table-margen">    <!-- comienzo del Cuadro blanco body-->
 <div class="table-responsive">
<table class="table table-hover" style="width:100%">
    <thead>
      <tr class="table-primary">
        <th>#</th>
        <th>NOMBRE</th>
        <th>ESTATUS</th>
        <th>ACCION</th>
      </tr>
    </thead>
    <tbody id="tablamarca">
     
    
    </tbody>
    </table>
</div>
</div>
</div>
<!-- |||||||||||||||||||||||||   Modal   |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-producto">
    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel"  style="color:rgb(255, 255, 255);">
        Registro de Marca
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body div-oscuro-2">
      <form action="?pagina=marca" id="u" autocomplete="off"> <!--comienzo Formulario-->
        
   <div class="seccion-formulario">
        <div class="row">

          <div class="col-sm">
          <label class="form-label text-dark">Nombre</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-file-lines" style="color:#007bff;"></i></span>
              <input type="text" class="form-control text-dark" name="nombre" id="nombre_marca" placeholder="Nombre: "  required>  
            </div>
              <span id="textomarca" class="vali-span"></span> <br>
          </div>

          
        </div>
                </div>


<hr>
<div class="button-container">
    <button type="button" class="btn btn-guardar" id="registrar">Registrar</button>
    <button type="reset" class="btn btn-limpiar" name="limpiar">Limpiar</button>
</div>
        </form> <!--fin del formulario-->
      </div>
    
      
    </div>
  </div>
</div>     
       
        

  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  
  <script src="assets/js/marca.js"></script>
  <?php require_once 'complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||--> 
  

</body>
</html>