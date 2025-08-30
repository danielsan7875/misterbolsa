<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head.php'; ?>
    <title>Modificar Tipo Usuario | Mister Bolsa C.A</title>
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
 
    <div class="body-wrapper body-oscuro">
    <?php require_once 'vista/complementos/nav.php'; ?>
    <div class="container-fluid">
  <!--|||||||||||||||   Fin de Sidebar y Nav  |||||||||||||||||||||||||||-->

  <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="?pagina=home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="?pagina=tipousuario">Tipo Usuario</a></li>
    <li class="breadcrumb-item active color-text1" aria-current="page">Modificar</li>
  </ol>
</nav>

<div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900"> <i class="fa-solid fa-user-tag mr-3" style="color: #E63946;"></i>
       Modificar</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
          <a href="?pagina=tipousuario" class="btn btn-primary">
          <span class="icon text-white-100">
             <i class="fa-solid fa-reply me-2"></i>
            </span>
            <span class="text text-white-100">Volver</span>
      </a>
      </div>

    <div class="card shadow mb-4"> <!-- comienzo del Cuadro blanco-->
      <div class="table-margen">    <!-- comienzo del Cuadro blanco body-->
      <form action="?pagina=tipousuario" id="m" autocomplete="off"> <!--comienzo Formulario-->
        
        <div class="row">
           <div class="col-sm-2">
          <label class="form-label text-dark"># <?php echo $modificar ['id_tipo']?></label>        
             
              <input type="hidden"  name="id_tipo" id="id_tipo" value ="<?php echo $modificar ['id_tipo']?>">  
          </div>
        </div>
        <div class="seccion-formulario">

      
        <div class="row">

          <div class="col-sm-4">
          <label class="form-label text-dark">Nombre</label>
          <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-file-lines" style="color:#007bff;"></i></span>
               <input type="text" class="form-control text-dark" name="nombre" id="nombre" value ="<?php echo $modificar ['nombre']?>" required>  
         
          </div>
            <span id="textonombre" class="text-error mt-0"></span> <br>
          </div>

          <div class="col-sm-4">
          <label class="form-label text-dark">Nivel</label>
          <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-file-lines" style="color:#007bff;"></i></span>
              <select class="form-select text-dark" aria-label="Default select example" name="nivel" id="selectnivel" required>
              <option value="<?php echo $modificar['nivel']; ?>"><?php echo "Nivel ".$modificar['nivel']." - actual"; ?></option>
                  <option value="1">Nivel 1</option>
                  <option value="2">Nivel 2</option>   
                  <option value="3">Nivel 3</option>          
                </select>
          </div>
          <span id="textonivel" class="text-error mt-0"></span> 
          </div>

          <div class="col-sm-4">
          <label class="form-label text-dark">Estatus</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-file-lines" style="color:#007bff;"></i></span>
                   <select class="form-select text-dark" name="estatus" required>
                     <?php
                       $estatus_texto = array(
                           1 => 'Activo',
                           2 => 'No Activo',
                                  );
                      ?>
                     <option value="<?php echo $modificar ['estatus']?>"> <?php echo $estatus_texto[$modificar['estatus']]. ' - Actual';?></option>
                     <option value="1">Activo</option>
                     <option value="2">No Activo</option>
                     </select>
            </div>
          </div>

            </div>
        </div>

       
<hr>
<div class="button-container">
    <button type="button" class="btn btn-guardar" id="actualizar">Actualizar datos</button>
    <button type="reset" class="btn btn-limpiar" name="limpiar">Limpiar</button>
</div>
        </form> <!--fin del formulario-->
      </div>
    </div>

  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/tipousuario.js"></script>
  <?php require_once 'vista/complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->    
</body>
</html>