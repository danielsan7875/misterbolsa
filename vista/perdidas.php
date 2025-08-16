<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head.php'; ?>
    <title>Perdidas | Mister Bolsa C.A</title>
    <link rel="stylesheet" href="assets/css/perdida.css" />
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
    <li class="breadcrumb-item"><a href="?pagina=producto">Producto</a></li>
    <li class="breadcrumb-item active color-text1" aria-current="page">Perdidas</li>
  </ol>
</nav>

<div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900"> <i class="fas fa-chart-line  mr-3" style="color: #E63946;"></i>
        Perdidas</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <span class="icon text-white-100">
            <i class="fas fa-file-medical"></i>
            </span>
            <span class="text ext-white-100">Registrar Perdida</span>
      </button>
    
      </div>

      <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-3">
  <a href="?pagina=producto" class="btn btn-info me-md-2" type="button">Ir a Producto</a>
  <button class="btn btn-primary" type="button"><i class="fa-regular fa-file-pdf"></i>Reportes</button>
</div>      
<div class="card shadow mb-4"> <!-- comienzo del Cuadro blanco-->
<div class="table-margen">    <!-- comienzo del Cuadro blanco body-->
      <div class="table-responsive">
  <table id="myTable" class="table table-striped table-hover" style="width:100%">
    <thead>
      <tr class="table-primary">
        <th>#</th>
        <th>FECHA</th>
        <th>PRODUCTO</th>
        <th>CANTIDAD</th>
        <th>NOTA</th>
        <?php if( $tipo_usuario == 1) { ?> 
        <th>ACCION</th>
        <?php } ?>
      </tr>
    </thead>
    <tbody>
       <?php
        foreach ($registro as $dato){
        ?>
                <tr>
                  <td class="text-center"> <?php echo $dato['id_perdida']?> </td>
                  <td class="text-center"> <?php echo $dato['fecha']?> </td>
                  <td> <?php echo $dato['nombre']." - ".$dato['nombre_marca']." - ".$dato['nombre_presentacion'] ?> </td>
                  <td class="text-center"> <?php echo $dato['cantidad']?> </td>
                  <td> <?php echo $dato['nota']?> </td>

                  <?php if( $tipo_usuario == 1) { ?> 
                  <td>
                  <form action="?pagina=perdidas">
                         <button type="button" onclick="eliminar(this)" class="btn btn-danger btn-sm eliminar" value="<?php echo $dato['id_perdida'] ?>"> <i class="fas fa-trash-alt" title="Eliminar"> </i> Eliminar </button>
                    </form>

                  </td>
                  <?php } ?>
                </tr>
 <?php } ?>
    
    </tbody>
    </table>
</div>
</div>
</div>

<!-- |||||||||||||||||||||||||   MODAL DE REGISTRO  |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel" style="color:rgb(255, 255, 255);">
        Registro de Perdida de Producto
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:rgb(255, 255, 255);"></button>
      </div>

      <div class="modal-body">
      <form action="?pagina=perdidas" id="u" autocomplete="off"> <!--comienzo Formulario-->
        
        <div class="row ">
          <div class="col-sm">
             <label class="form-label text-dark">Producto</label>
                <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-boxes-stacked" style="color:#007bff;"></i></span>
                <select class="form-select text-dark" aria-label="Default select example" name="id_producto" id="idproducto" required>
                  <option value="">Seleccione un Producto:</option>
                       <?php foreach($producto as $producto) {?>
                  <option value="<?php echo $producto['id_producto'];?>"> <?php echo $producto['nombre']." - ".$producto['nombre_marca']." - ".$producto['nombre_presentacion']?> </option>
                     <?php } ?>
                </select> 
              </div>
          </div>

        </div>

        <div class="row ">
          <div class="col-sm">
          <label class="form-label text-dark">cantidad</label>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-box" style="color:#007bff;"></i></span>
                <input type="text" class="form-control text-dark" name="cantidad" id="cantidad" placeholder="Ejemplo: 5 "  required>  
              </div>
              <span id="textocantidad" class="vali-span"></span> 
          </div>

          <div class="col-sm">
          <label class="form-label text-dark">Nota</label>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-comment" style="color:#007bff;"></i></span>
                <input type="text" class="form-control text-dark"  name="nota" id="nota" placeholder="Ejemplo: gasto "  required>  
          </div>
          <span id="textonota" class="vali-span"></span>
        </div>

        <input type="hidden" name="fecha" id="fechaperdida">  
<hr>
<div class="button-container">
    <button type="button" class="btn btn-success" id="registrar">Registrar</button>
    <button type="button" class="btn btn-secondary" id="limpiardatos" name="limpiar">Limpiar</button>
</div>
        </form> <!--fin del formulario-->
      </div>
    
      
    </div>
  </div>
</div>           
  

  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/moment.js"></script>
  <script src="assets/js/perdidas.js"></script>
  <?php require_once 'complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||--> 


</body>
</html>