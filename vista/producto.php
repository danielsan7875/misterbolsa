<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head.php'; ?>
    <title>Producto | Mister Bolsa C.A</title>
    <link rel="stylesheet" href="assets/css/producto.css" />
     <link rel="stylesheet" href="assets/css/for.css" />
</head>

<body class="">
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
    <li class="breadcrumb-item active color-text1 texto-secundario" aria-current="page">Producto</li>
    
  </ol>

   
</nav>

<div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900 texto-principal"> <i class="fa-solid fa-boxes-stacked  mr-3" style="color: #E63946;"></i>
        Producto</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <span class="icon text-dark">
            <i class="fas fa-file-medical me-2"></i>
            </span>
            <span class="text text-dark">Nuevo Producto</span>
        </button>
      </div>

<div>

<div class="d-flex justify-content-between mb-4">
 <a href="assets/pdf/reporteproducto.php" target="_blank" class="btn btn-primary">
  <i class="fa-regular fa-file-pdf"></i> Reportes
</a>


  <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
             Mas Opciones
            </button>
                <ul class="dropdown-menu div-oscuro-1">
                  <li> <a href="?pagina=perdidas" class="dropdown-item text-dark texto-secundario"><i class="fas fa-chart-line m-2" style="color: #E63946;"></i> Ver Perdidas</a></li>
                  <li> <a href="?pagina=detalle" class="dropdown-item text-dark texto-secundario"><i class="fas fa-file-alt m-2" style="color: #E63946;"></i> Ver Detalles</a></li>
                </ul>
</div>

  

<div id="alerta">

</div>
    <?php foreach ($dolar as $dolar){  ?>
        <input type="hidden" id="dolar" value="<?php echo $dolar['precios']?>">
        <?php } ?>
<div class="card shadow mb-4"> <!-- comienzo del Cuadro blanco-->
<div class="table-margen">    <!-- comienzo del Cuadro blanco body-->
<div class="table-responsive text-dark texto-secundario" >
<table id="myTable" class="table table-hover" style="width:100%">
<thead>
      <tr class="table-primary">
        <th>#</th>
        <th>Nombre</th>
        <th>Marca y Presentación</th>
        <th>Precio DETAL</th>
        <th>Precio MAYOR</th>
        <th>Stock</th>
        <th>Detalles</th>
        <?php if( $tipo_usuario == 1) { ?> 
        <th>Acciones</th>
        <?php } ?>
      </tr>
    </thead>
    <tbody>
<?php
$estatus_texto = array( 
  1 => 'Activo',
  2 => 'No Activo',
);

$estatus_classes = array(
  1 => 'activo', 
  2 => 'no-activo', 
);

foreach ($registro as $dato){
?>
  <tr data-precio-unitario="<?php echo $dato['precio_unitario']; ?>" data-precio-mayor="<?php echo $dato['precio_mayor']; ?>" data-stock-minimo="<?php echo $dato['stock_minimo']; ?>">
    <td class="text-center bg-s"> <?php echo $dato['id_producto']; ?></td>

    <td class="bg-s"><?php echo $dato['nombre']; ?></td>

    <td class="bg-s">
      <div class="mt-0">Marca: <?php echo $dato['nombre_marca']; ?> </div>
      <div class="mt-0"> • <?php echo $dato['nombre_presentacion']; ?> </div>
    </td>

    
    <td class="fw-bold bg-s"><div id="precioBs"> </div>  </td>

    <td class="fw-bold bg-s"> <div id="precioBss" > </div>  </td>

    <td class="text-center bg-s"> <strong class="me-2"> <?php echo $dato['stock']; ?> </strong> </td>

    <td class="text-center bg-s">
      <!-- Botón de detalle -->
      <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detalleModal-<?php echo $dato['id_producto']; ?>">
        <i class="fas fa-info-circle me-2"></i> Detalle
      </button>
    </td>


    <?php if ($tipo_usuario == 1) { ?> 
    <td class="bg-s">  <!-- ACCION -->
      <form method="POST" action="?pagina=producto">
  <div class="dropdown">
    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
      Opciones
    </button>
    <ul class="dropdown-menu div-oscuro-1">
      <li>
        <button name="modificar" class="dropdown-item modificar text-dark texto-secundario" value="<?php echo $dato['id_producto']; ?>">
          <i class="fas fa-pencil-alt me-2 text-primary"></i> Editar
        </button>
      </li>
      <li>
        <button name="eliminar" class="dropdown-item eliminar text-dark texto-secundario" value="<?php echo $dato['id_producto']; ?>">
          <i class="fas fa-trash-alt me-2 text-danger"></i> Eliminar
        </button>
      </li>
    </ul>
  </div>

  <!-- Hidden inputs outside the dropdown -->
  <input type="hidden" name="eliminar" value="<?php echo $dato['id_producto']; ?>">
  <input type="hidden" name="stock" value="<?php echo $dato['stock']; ?>">
</form>

    </td>
    <?php } ?>
  </tr>

 <!-- Modal de detalle -->
<div class="modal fade" id="detalleModal-<?php echo $dato['id_producto']; ?>" tabindex="-1" aria-labelledby="detalleModalLabel-<?php echo $dato['id_producto']; ?>" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-producto">
      <div class="modal-header">
        <h5 class="modal-title text-white" id="detalleModalLabel-<?php echo $dato['id_producto']; ?>">Detalles del producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body div-oscuro-2">

        <!-- Primera Card: Nombre, Marca, Presentación -->
       
          <div class="seccion-formulario">
          <h6><i class="fas fa-boxes"></i> Nombre del Producto </h6>
             <div class="row">
              <div class="col"> <h5 class="card-title texto-secundario"><strong><?php echo $dato['nombre']; ?></strong></h5> </div>
              <div class="col ">
                <h5 class="mt-0 texto-secundario">Marca: <?php echo $dato['nombre_marca']; ?></h5>
                <h5 class="mt-0 texto-secundario">Presentación: <?php echo $dato['nombre_presentacion']; ?></h5>
            </div>
    
          </div>
          </div>
        

        <!-- Segunda Card: Precio Detal -->
      
       <div class="seccion-formulario">
           <h6><i class="fas fa-boxes"></i> Precio al Detal </h6>
            <div class="row">
              <div class="col"><h5 class="texto-secundario"><strong>$:</strong> <?php echo $dato['precio_unitario']; ?></h5></div>
              <div class="col"><h5 class="texto-secundario"><strong>Bs:</strong> <span class="precio-bs-detal" data-precio="<?php echo $dato['precio_unitario']; ?>"></h5></span></div>
            </div>
          </div>
  

        <!-- Tercera Card: Precio Mayor -->
       <div class="seccion-formulario">
          <h6><i class="fas fa-boxes"></i> Precio al Mayor </h6>
            <div class="row">
              <div class="col"><h5 class="texto-secundario"><strong>$:</strong> <?php echo $dato['precio_mayor']; ?></h5></div>
              <div class="col"><h5 class="texto-secundario"><strong>Bs:</strong> <span class="precio-bs-mayor" data-precio="<?php echo $dato['precio_mayor']; ?>"></span></h5></div>
              <div class="col"><h5 class="texto-secundario"><strong>Cantidad Mayor:</strong> <?php echo $dato['cantidad_mayor']; ?></h5></div>
            </div>
          </div>
      

        <!-- Cuarta Card: Stock -->
         <div class="seccion-formulario">
              <h6><i class="fas fa-boxes"></i> Otros datos </h6>
            <div class="row">
              <div class="col"> <h5 class="texto-secundario"><strong>Stock mínimo:</strong> <?php echo $dato['stock_minimo']; ?> </h5></div>
              <div class="col"> <h5 class="texto-secundario"><strong>Stock Disponible:</strong> <?php echo $dato['stock']; ?></h5> </div>
              <div class="col"> <h5 class="texto-secundario">
                <strong>Estado:</strong>
                <?php
                  if ($dato['stock'] >= $dato['stock_minimo']) {
                    echo '<span class="badge bg-success">Perfecto</span>';
                  } else {
                    echo '<span class="badge bg-danger text-dark">Bajo stock</span>';
                  }
                ?>
                </h5>
              </div>
            </div>
          </div>
   

      </div>
      <div class="modal-footer div-oscuro-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php
}
?>
</tbody>

  </table>
</div>
</div>
</div>




<!-- |||||||||||||||||||||||||   MODAL DE REGISTRO PRODUCTO   |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-producto div-oscuro-2">
    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel"  style="color:rgb(255, 255, 255);">
       <i class="fas fa-boxes" style="color:rgb(255, 255, 255);"></i> Registro de Producto Nuevo
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body div-oscuro-2">
      <form action="?pagina=producto" id="u" autocomplete="off"> <!--comienzo Formulario-->
        <div class="seccion-formulario">
        <div class="row ">
          <h6><i class="fas fa-boxes"></i> Datos del Producto Nuevo </h6>
          <div class="col-sm">
          <label class="form-label text-dark">Nombre</label>
            <div class="input-group mb-3">
            <span class="input-group-text border border-info" id="basic-addon1"> <i class="fa-solid fa-bag-shopping" style="color:#007bff;"></i> </span>
            <input type="text" class="form-control text-dark border border-info" name="nombre" id="nombre" placeholder="Ejemplo: Bister Bolsa "  required>     
          </div>
          <span id="textonombre"  class="text-error mt-0"></span>
          </div>
        </div>

        <div class="row ">
          <div class="col-sm">
          <label class="form-label text-dark">Marca</label>
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-tag " style="color:#007bff;"></i></span>
          <select class="form-select text-dark" aria-label="Default select example"  name="marca" id="marcaselect" required>
              <option value="">Seleccione una Marca:</option>
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
          <select class="form-select text-dark" aria-label="Default select example" name="presentacion" id="presenselect" required>
              <option value="">Seleccione una Presentacion:</option>
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
               <span class="input-group-text border border-info" id="basic-addon1"><i class="fa-solid fa-dollar-sign" style="color:#007bff;"></i></span>
               <input type="text" class="form-control text-dark border border-info " name="precio" id="precio" placeholder="Ejemplo: 5.00 "  required>  
            </div>
            <span id="textoprecio"  class="text-error mt-0"></span> 
          </div>

            <div class="col-sm">
          <label class="form-label text-dark">Precio al Mayor($)</label>
            <div class="input-group mb-3">
               <span class="input-group-text border border-info" id="basic-addon1"><i class="fa-solid fa-dollar-sign" style="color:#007bff;"></i></span>
               <input type="text" class="form-control text-dark border border-info" name="preciomayor" id="preciomayor" placeholder="Ejemplo: 4.00 " required>  
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
                <span class="input-group-text border border-info" id="basic-addon1"><i class="fa-solid fa-box" style="color:#007bff;"></i></span>
                  <input type="text" class="form-control text-dark border border-info"  name="stock_min" id="stock" placeholder="Ejemplo: 2 "  required>  
              </div>
             <span id="textostock"  class="text-error mt-0"></span>
          </div>

          <div class="col-sm">
              <label class="form-label text-dark">Cantidad Al Mayor</label>
                <div class="input-group mb-3">
                  <span class="input-group-text border border-info" id="basic-addon1"><i class="fa-solid fa-box" style="color:#007bff;"></i></span>
                    <input type="text" class="form-control text-dark border border-info"  name="cantidadmayor" id="cantidadmayor" placeholder="Ejemplo: 2 "  required>  
                </div>
            <span id="textocantidadmayor"  class="text-error mt-0"></span> 
          </div>

  </div>
  </div>
  <div class="button-container ">
    <button type="button" class="btn btn-guardar" id="registrar">Registrar Producto</button>
    <button type="reset" class="btn btn-limpiar" name="limpiar">Limpiar Datos</button>
</div>
        </div>


<br>

        </form> <!--fin del formulario-->
      </div>
    
      
    </div>
  </div>
</div>           
 
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  
  <script src="assets/js/producto.js"></script>
  <?php require_once 'complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||--> 
  
  

</body>
</html>
