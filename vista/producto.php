<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head.php'; ?>
    <title>Producto | Mister Bolsa C.A</title>
    <link rel="stylesheet" href="assets/css/producto.css" />
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
 
    <div class="body-wrapper">
    <?php require_once 'complementos/nav.php'; ?>
    <div class="container-fluid">
  <!--|||||||||||||||   Fin de Sidebar y Nav  |||||||||||||||||||||||||||-->

  <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="?pagina=home">Inicio</a></li>
    <li class="breadcrumb-item active color-text1" aria-current="page">Producto</li>
    
  </ol>

   
</nav>

<div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900"> <i class="fa-solid fa-boxes-stacked  mr-3" style="color: #E63946;"></i>
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
 <a href="#" class="btn btn-primary"><i class="fa-regular fa-file-pdf"></i> Reportes</a>

  <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
             Mas Opciones
            </button>
                <ul class="dropdown-menu">
                  <li> <a href="?pagina=perdidas" class="dropdown-item text-dark"><i class="fas fa-chart-line m-2" style="color: #E63946;"></i> Ver Perdidas</a></li>
                  <li> <a href="?pagina=detalle" class="dropdown-item text-dark"><i class="fas fa-file-alt m-2" style="color: #E63946;"></i> Ver Detalles</a></li>
                </ul>
</div>

  

<div id="alerta">

</div>
    <?php foreach ($dolar as $dolar){  ?>
        <input type="hidden" id="dolar" value="<?php echo $dolar['precios']?>">
        <?php } ?>
<div class="card shadow mb-4"> <!-- comienzo del Cuadro blanco-->
<div class="table-margen">    <!-- comienzo del Cuadro blanco body-->
<div class="table-responsive">
<table id="myTable" class="table table-striped table-hover" style="width:100%">
<thead>
      <tr class="table-primary">
        <th>#</th>
        <th>NOMBRE</th>
        <th>MARCA</th>
        <th>PRESENTACION</th>
        <th>PRECIO DETAL BS </th>
        <th>PRECIO MAYOR BS </th>
        <th>STOCK</th>
        <th>DETALLES</th>
        <?php if( $tipo_usuario == 1) { ?> 
        <th>ACCION</th>
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
    <td class="text-center"> <?php echo $dato['id_producto']; ?></td>

    <td><?php echo $dato['nombre']; ?></td>

    <td><?php echo $dato['nombre_marca']; ?></td>

    <td><?php echo $dato['nombre_presentacion']; ?></td>
    
    <td id="precioBs" class="fw-bold">  </td>

    <td id="precioBss" class="fw-bold">  </td>

    <td class="text-center"><i class="fas fa-boxes"></i> <?php echo $dato['stock']; ?></td>

    <td class="text-center">
      <!-- Botón de detalle -->
      <button class="btn btn-info btn-sm text-dark" data-bs-toggle="modal" data-bs-target="#detalleModal-<?php echo $dato['id_producto']; ?>">
        <i class="fas fa-info-circle m-1"></i> Detalle
      </button>
    </td>


    <?php if ($tipo_usuario == 1) { ?> 
    <td>  <!-- ACCION -->
      <form method="POST" action="?pagina=producto">
          <button name="modificar" class="btn btn-primary btn-sm modificar"
          value="<?php echo $dato['id_producto']; ?>"><i class="fas fa-pencil-alt m-1" title="Editar"></i> Editar</button>
       
          <button name="eliminar" class="btn btn-danger btn-sm eliminar"
          value="<?php echo $dato['id_producto']; ?>"><i class="fas fa-trash-alt m-1" title="Eliminar"></i> Eliminar</button>
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
          <h5 class="modal-title"  style="color:rgb(255, 255, 255);" id="detalleModalLabel-<?php echo $dato['id_producto']; ?>">Detalles del producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        
        <div class="seccion-formulario">
         <h4><strong>Precio Detal ($):</strong> <?php echo $dato['precio_unitario']; ?></h4>
          <h4><strong>Precio Mayor ($)</strong> <?php echo $dato['precio_mayor']; ?></4h>
          </div>
        <div class="seccion-formulario">
          <h4><strong>Stock Mínimo:</strong> <?php echo $dato['stock_minimo']; ?></h4>
          <h4><strong>Cantidad Mayor:</strong> <?php echo $dato['cantidad_mayor']; ?></4h>
         
        </div>
        </div>
        <div class="modal-footer">
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

<!-- |||||||||||||||||||||||||   TASA DOLAR   |||||||||||||||||||||||||||||||||| -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasRightLabel"><i class="fa-solid fa-money-bill-transfer"></i> Actualizar Tasa del Dolar</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form action="?pagina=producto" id="t" autocomplete="off">

      <label class="form-label text-dark">Precio $ A Bs</label>
      <input type="text" class="form-control text-dark" name="precios" id="inputtasadolar" placeholder="0.00 "  required>
      <span id="textotasadolar"  class="vali-span"></span> 
      <input type="hidden" name="id_dolar" value="1"> <br>

      <button type="button" class="btn btn-success text-dark" id="tasadolar">
      <i class="fa-solid fa-money-bill-transfer"></i> Actualizar Tasa</button>
      <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas" aria-label="Close">Cerrar</button>
    </form>
  </div>
</div>




<!-- |||||||||||||||||||||||||   MODAL DE REGISTRO PRODUCTO   |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-producto">
    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel"  style="color:rgb(255, 255, 255);">
       <i class="fas fa-boxes" style="color:rgb(255, 255, 255);"></i> Registro de Producto Nuevo
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
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
  <div class="button-container">
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