<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head.php'; ?>
    <title>Presentación | Mister Bolsa C.A</title>
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
    <li class="breadcrumb-item"><a href="?pagina=home">Inicio</a></li>
    <li class="breadcrumb-item active color-text1" aria-current="page">Presentación</li>
  </ol>
</nav>

<div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900"> <i class="fa-solid fa-shapes  mr-3" style="color: #E63946;"></i>
       Presentación</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <span class="icon text-white-100">
            <i class="fas fa-file-medical"></i>
            </span>
            <span class="text ext-white-100">Registrar Presentación</span>
      </button>
      </div>


<div class="card shadow mb-4"> <!-- comienzo del Cuadro blanco-->
<div class="table-margen">    <!-- comienzo del Cuadro blanco body-->
<div class="table-responsive">
<table class="table table-striped table-hover" style="width:100%">
    <thead class="table-color">
      <tr class="table-primary">
        <th>#</th>
        <th>NOMBRE</th>
        <th>ESTATUS</th>
        <th>ACCION</th>
      </tr>
    </thead>
    <tbody id="tablapresentacion">
      
    
    </tbody>
    </table>
</div>
</div>
</div>

<!-- |||||||||||||||||||||||||   Modal   |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel" style="color:rgb(255, 255, 255);">
        Registro de Presentación
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:rgb(255, 255, 255);"></button>
      </div>

      <div class="modal-body">
      <form action="?pagina=presentacion" id="u" autocomplete="off"> <!--comienzo Formulario-->
        

        <div class="row">

          <div class="col-sm">
          <label class="form-label text-dark">Nombre</label>
          <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-file-lines" style="color:#007bff;"></i></span>
               <input type="text" class="form-control text-dark" name="nombre" id="nombre_prese" placeholder="Nombre: "  required>  
         
          </div>
          <span id="textopresentacion" class="vali-span"></span> <br>
          </div>

          
        </div>


<hr>
<div class="button-container">
    <button type="button" class="btn btn-success" id="registrar">Registrar</button>
    <button type="reset" class="btn btn-secondary" name="limpiar">Limpiar</button>
</div>
        </form> <!--fin del formulario-->
      </div>
    
      
    </div>
  </div>
</div>     
       
 
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->

  <script src="assets/js/presentacion.js"></script>
  <?php require_once 'complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||--> 
  


   
</body>
</html>