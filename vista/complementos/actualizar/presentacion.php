<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head.php'; ?>
    <title>Modificar presentación | Mister Bolsa C.A</title>
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
    <li class="breadcrumb-item"><a href="?pagina=presentacion">Presentación</a></li>
    <li class="breadcrumb-item active color-text1" aria-current="page">Modificar</li>
  </ol>
</nav>

<div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900"> <i class="fa-solid fa-shapes  mr-3" style="color: #E63946;"></i>
       Modificar</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
          <a href="?pagina=presentacion" class="btn btn-primary">
          <span class="icon text-white-100">
            <i class="fas fa-file-medical"></i>
            </span>
            <span class="text ext-white-100">Volver</span>
      </a>
      </div>

    <div class="card shadow mb-4"> <!-- comienzo del Cuadro blanco-->
      <div class="table-margen">    <!-- comienzo del Cuadro blanco body-->
      <form action="?pagina=presentacion" id="m" autocomplete="off"> <!--comienzo Formulario-->
        

        <div class="row">

        <div class="col-sm-2">
          <label class="form-label text-dark">#</label>        
              <input type="text" class="form-control text-dark" value ="<?php echo $modificar ['id_presentacion']?>" disabled>  
              <input type="hidden"  name="id_presentacion" id="id_presentacion" value ="<?php echo $modificar ['id_presentacion']?>">  
          </div>

          <div class="col-sm-6">
          <label class="form-label text-dark">Nombre</label>
          <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-file-lines" style="color:#007bff;"></i></span>
               <input type="text" class="form-control text-dark" name="nombre" id="nombre_prese" value ="<?php echo $modificar ['nombre']?>" required>  
         
          </div>
          <span id="textopresentacion" class="vali-span"></span> <br>
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


<hr>
<div class="button-container">
    <button type="button" class="btn btn-success" id="actualizar">Actualizar</button>
    <button type="reset" class="btn btn-secondary" name="limpiar">Limpiar</button>
</div>
        </form> <!--fin del formulario-->
      </div>
    </div>

  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/presentacion.js"></script>
  <?php require_once 'vista/complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->    
</body>
</html>