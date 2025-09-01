<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head.php'; ?>
    <title>Modificar Producto | Mister Bolsa C.A</title>
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
    <li class="breadcrumb-item"><a href="?pagina=producto">Producto</a></li>
    <li class="breadcrumb-item active color-text1 texto-secundario" aria-current="page">Modificar</li>
  </ol>
</nav>

<div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900 texto-principal"> <i class="fa-solid fa-boxes-stacked mr-3" style="color: #E63946;"></i>
       Modificar</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
          <a href="?pagina=producto" class="btn btn-primary">
          <span class="icon text-white-100">
          <i class="fa-solid fa-reply me-2"></i>
            </span>
            <span class="text ext-white-100">Volver</span>
      </a>
      </div>

    <div class="card shadow mb-4"> <!-- comienzo del Cuadro blanco-->
      <div class="table-margen">    <!-- comienzo del Cuadro blanco body-->
      <form action="?pagina=producto" id="m" autocomplete="off"> <!--comienzo Formulario-->
         <div class="seccion-formulario">
        <div class="row ">
           <h6><i class="fas fa-boxes"></i> Datos del Producto Nuevo </h6>

          <div class="col-sm">
          <label class="form-label text-dark">Nombre</label>
            <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"> <i class="fa-solid fa-bag-shopping" style="color:#007bff;"></i> </span>
            <input type="text" class="form-control text-dark" name="nombre" id="nombre" value="<?php echo $modificar['nombre']?>"  required>     
          </div>
          <span id="textonombre"  class="text-error mt-0"></span>
          </div>

          <div class="col-sm-2">
               <label class="form-label text-dark">#</label>
              <input type="text" class="form-control text-dark" value ="<?php echo $modificar ['id_producto']?>" disabled>
              <input type="hidden"  value ="<?php echo $modificar ['id_producto']?>" name="id_producto">
            </div>
             

        </div>

        <div class="row ">
          <div class="col-sm">
          <label class="form-label text-dark">Marca</label>
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-tag " style="color:#007bff;"></i></span>
          <select class="form-select text-dark" aria-label="Default select example"  name="marca" id="marcaselect" required>
          <option value="<?php echo $modificar['id_marca']; ?>"><?php echo $modificar['nombre_marca']." - actual"; ?></option>
                 <?php foreach($marca as $marca) {?>
                     <option value="<?php echo $marca['id_marca'];?>"> <?php echo $marca['nombre'];?> </option>
               <?php } ?>
           </select> 
           </div>
            <span id="textomarca"  class="text-error mt-0"></span> 
          </div>

          <div class="col-sm">
          <label class="form-label text-dark">Presentacion</label>
          <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-shapes" style="color:#007bff;"></i></span>
           <select class="form-select text-dark" name="presentacion" id="presenselect" required>
               <option value="<?php echo $modificar['id_presentacion']; ?>"><?php echo $modificar['nombre_presentacion']." - actual"; ?></option>

               <?php foreach($presentacion as $presentacion) {?>
                     <option value="<?php echo $presentacion['id_presentacion'];?>"> <?php echo $presentacion['nombre'];?> </option>
               <?php } ?>
            </select> 
           </div>  
           <span id="textopresentacion"  class="text-error mt-0"></span> 
          </div>
          </div>
        </div>
      
        <div class="seccion-formulario">
        <div class="row ">
            <h6><i class="fas fa-boxes"></i> Precio del Producto en $ </h6>
          <div class="col-sm">
          <label class="form-label text-dark">Precio Al Detal ($)</label>
            <div class="input-group mb-3">
               <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-dollar-sign" style="color:#007bff;"></i></span>
               <input type="text" class="form-control text-dark" name="precio" id="precio" value ="<?php echo $modificar ['precio_unitario']?>"  required>  
            </div>
            <span id="textoprecio"  class="text-error mt-0"></span> 
          </div>

         
          
                  <div class="col-sm">
          <label class="form-label text-dark">Precio al Mayor($)</label>
            <div class="input-group mb-3">
               <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-dollar-sign" style="color:#007bff;"></i></span>
               <input type="text" class="form-control text-dark" name="preciomayor" id="preciomayor" value="<?php echo $modificar['precio_mayor']?>"  required>  
            </div>
            <span id="textopreciomayor"  class="text-error mt-0"></span> 
          </div>
        </div>
        </div>

    <div class="seccion-formulario">
        <div class="row">
            <h6><i class="fas fa-boxes"></i> Otros datos </h6>
           <div class="col-sm">
          <label class="form-label text-dark">Stock Minimo</label> 
            <div class="input-group mb-3">
               <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-box" style="color:#007bff;"></i></span>
                <input type="text" class="form-control text-dark"  name="stock_min" id="stock" value ="<?php echo $modificar ['stock_minimo']?>" required>  
            </div>
        <span id="textostock"  class="text-error mt-0"></span>
          </div>

          <div class="col-sm">
          <label class="form-label text-dark">Cantidad Al Mayor</label>
            <div class="input-group mb-3">
               <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-box" style="color:#007bff;"></i></span>
                <input type="text" class="form-control text-dark"  name="cantidadmayor" id="cantidadmayor" value="<?php echo $modificar['cantidad_mayor']?>" required>  
            </div>
        <span id="textocantidadmayor"  class="text-error mt-0"></span> 
          </div>
             
        </div>
        </div>

<input type="hidden" value="<?php echo $modificar['stock']?>" name="stock">
<input type="hidden" value="<?php echo $modificar['estatus']?>" name="estatus">
<hr>
<div class="button-container">
    <button type="button" class="btn btn-guardar" id="actualizar"> <i class="fa-solid fa-floppy-disk me-2"></i> Actualizar Datos</button>
    <button type="reset" class="btn btn-limpiar" name="limpiar"><i class="fa-solid fa-repeat me-2"></i>Limpiar</button>
</div>
        </form> <!--fin del formulario-->
      </div>
    </div>

  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/producto.js"></script>
  <?php require_once 'vista/complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->    
</body>
</html>