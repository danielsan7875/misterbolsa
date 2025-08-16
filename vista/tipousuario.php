<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head.php'; ?>
    <title>Tipo de Usuario | Mister Bolsa C.A</title>
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
    <li class="breadcrumb-item active color-text1" aria-current="page">Tipo de Usuario</li>
  </ol>
</nav>

<div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900"> <i class="fa-solid fa-user-tag mr-3" style="color: #E63946;"></i>
        Tipo de Usuario</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <span class="icon">
            <i class="fas fa-file-medical"></i>
            </span>
            <span class="text">Registrar</span>
      </button>
      </div>


<div class="card shadow mb-4">
<div class="table-margen">   
      <div class="table-responsive">
<table class="table table-striped table-hover" style="width:100%">
    <thead>
      <tr class="table-primary">
        <th class="text-center">#</th>
        <th>NOMBRE</th>
        <th>NIVEL</th>
        <th>ACCION</th>
      </tr>
    </thead>
    <tbody id="tablatipousuario">
      
    
    </tbody>
    </table>
</div>
</div>
</div>


<!-- |||||||||||||||||||||||||   Modal   |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel"  style="color:rgb(255, 255, 255);">
        Registro de Tipo de Usuario
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:rgb(255, 255, 255);"></button>
      </div>

      <div class="modal-body">
      <form action="?pagina=tipousuario" id="u" autocomplete="off"> <!--comienzo Formulario-->
        
      
        <div class="row ">
          <div class="col-sm">
          <label class="form-label text-dark">Nombre</label>
          <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user-tag" style="color:#007bff;"></i></span>
              <input type="text" class="form-control text-dark" name="nombre" id="nombre" placeholder="Nombre: "  required>  
              <span id="textonombre" class="vali-span"></span> <br>
            </div> 
        
        </div>

          <div class="col-sm">
          <label class="form-label text-dark">Nivel</label>
            <div class="input-group mb-3">
             <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-users" style="color:#007bff;"></i></span>  
                <select class="form-select text-dark" aria-label="Default select example" name="nivel" id="selectnivel" required>
                  <option value="">Seleccione un nivel:</option>
                  <option value="1">Nivel 1</option>
                  <option value="2">Nivel 2</option>   
                  <option value="3">Nivel 3</option>          
                </select>
            </div> 
           </div>
          
        </div>
        <div class="alert alert-info" role="alert">
        Tipo de Usuario <br>
        Nivel 1 : <br>
        Nivel 2 : <br>
        Nivel 3 : Producto, Venta | Sin la funcion de editar o eliminar datos<br>
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
  <script src="assets/js/tipousuario.js"></script>
  <?php require_once 'complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->    

  </body>
</html>