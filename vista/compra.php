<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head.php'; ?>
    <title>Compra | Mister Bolsa C.A</title>
    <link rel="stylesheet" href="assets/css/compra.css" />
<link rel="stylesheet" href="assets/css/for.css" />
</head>

<body>
  <!-- LOADER-->
  <div id="loaderPagina" class="section_loader">
  <span class="loader"></span>
</div>

                 <style>
.g{
  width: 100%;
}
                  </style>

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
    <li class="breadcrumb-item active color-text1" aria-current="page">Compra</li>
  </ol>
</nav>

<div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900"> <i class="fa-solid fa-receipt mr-3" style="color: #E63946;"></i>
        Compra</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <span class="icon text-dark">
            <i class="fas fa-file-medical me-2"></i>
            </span>
            <span class="text text-dark">Registrar Compra</span>
      </button>
      </div>

<div class="d-grid gap-2 d-md-flex justify-content-md-end">
  <button class="btn btn-primary mb-3" type="button" disabled><i class="fa-regular fa-file-pdf"></i>Reportes</button>
</div>
      <div class="card shadow mb-4"> <!-- comienzo del Cuadro blanco-->
<div class="table-margen">    <!-- comienzo del Cuadro blanco body-->
      <div class="table-responsive text-dark">
      <table id="myTable" class="table table-striped table-hover" style="width:100%">
    <thead class="table-color">
      <tr class="table-primary">
        <th >#</th>
        <th>Fecha</th>
        <th>Producto</th>
        <th>Cantidad</th>
        
        <?php if( $tipo_usuario == 1) { ?> 
        <th>Accion</th>
        <?php } ?>
      </tr>
    </thead>
 <tbody>

  <?php

$compras = [];

foreach ($registro as $dato) {
    $id_compra = $dato['id_compra'];
    if (!isset($compras[$id_compra])) {
        $compras[$id_compra] = [
            'fecha' => $dato['fecha'],
            'detalles' => []
        ];
    }

    $compras[$id_compra]['detalles'][] = [
        'nombre' => $dato['nombre'],
        'marca' => $dato['nombre_marca'],
        'presentacion' => $dato['nombre_presentacion'],
        'cantidad' => $dato['cantidad']
    ];
}
?>

<?php foreach ($compras as $id_compra => $info): ?>
  
<tr>
    <td class="text-center"><?php echo $id_compra; ?></td>
    <td class="text-center"><?php echo $info['fecha']; ?></td>

    <td>
        <ul class="mb-0">
            <?php 
            $detalles = $info['detalles'];
            $mostrar = array_slice($detalles, 0, 2);
            foreach ($mostrar as $detalle): ?>
                <li>
                    <i class="fa-solid fa-boxes-stacked me-2" style="color: #457B9D;"></i>
                    <?php echo $detalle['nombre'].' - '.$detalle['marca'].' - '.$detalle['presentacion']; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if (count($detalles) > 2): ?>
          <button type="button" class="btn badge bg-primary mt-2" data-bs-toggle="modal" data-bs-target="#modal-<?php echo $id_compra; ?>">
            <i class="fa-solid fa-eye me-2"></i> Ver más
          </button>
        <?php endif; ?>
    </td>

    <td>
        <ul class="mb-0">
            <?php foreach ($mostrar as $detalle): ?>
                <li><?php echo $detalle['cantidad']; ?></li>
            <?php endforeach; ?>
        </ul>
    </td>

    <?php if ($tipo_usuario == 1): ?>
    <td class="text-center">
        <form action="?pagina=compra">
            <button type="button" onclick="eliminar(this)" class="btn btn-danger btn-sm eliminar" value="<?php echo $id_compra; ?>">
                <i class="fas fa-trash-alt" title="Eliminar"></i> Eliminar
            </button>
        </form>
    </td>
    <?php endif; ?>
</tr>

<!-- Modal con todos los detalles -->
<?php endforeach; ?>
</tbody>
    </table>
    
<?php foreach ($compras as $id_compra => $info): ?>
  <div class="modal fade" id="modal-<?php echo $id_compra; ?>" tabindex="-1" aria-labelledby="modalLabel-<?php echo $id_compra; ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content modal-producto">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white" id="modalLabel-<?php echo $id_compra; ?>">Detalles de la compra #<?php echo $id_compra; ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          
          <p> Fecha de la compra: <b><?php echo $info['fecha']; ?></b></p>
          <table class="table table-hover">
            <thead>
              <tr>
                <th class="bg-secondary text-white">Producto</th>
                <th class="bg-secondary text-white">Cantidad</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($info['detalles'] as $detalle): ?>
              <tr>
                <td><?php echo $detalle['nombre'].' - '.$detalle['marca'].' - '.$detalle['presentacion']; ?></td>
                <td class="text-center"><?php echo $detalle['cantidad']; ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-info text-dark" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>

</div>
</div>
</div>

<!-- |||||||||||||||||||||||||   Modal   |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-producto">
    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel" style="color:rgb(255, 255, 255);">
        Registro de Compra
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:rgb(255, 255, 255);"></button>
      </div>

      <div class="modal-body">
      <form action="?pagina=compra" id="u" autocomplete="off"> <!--comienzo Formulario-->
      
      <div class="seccion-formulario">
        <div class="row ">
          <h6>Seleccionar un producto y la cantidad</h6>
          <div class="col-sm-10">
          <label class="form-label text-dark">Producto</label>
          <div class="input-group mb-3">
          <span class="input-group-text border border-dark" id="basic-addon1"><i class="fa-solid fa-boxes-stacked" style="color:#007bff;"></i></span>
          <select class="form-select text-dark select-formulario border border-dark" aria-label="Default select example" name="id_producto" id="idproducto" required >
              <option value="">Seleccione un Producto:</option>
                <?php foreach($producto as $producto) {?>
                <option value="<?php echo $producto['id_producto'];?>"> <?php echo $producto['nombre']." - ".$producto['nombre_marca']." - ".$producto['nombre_presentacion']?> </option>
               <?php } ?>
            </select>
           </div>
           <span class="text-error mt-0" id="textoproducto"></span>
          </div>
          <div class="col-sm-2">
                <label class="form-label text-dark">Cantidad</label> <br>
                <input type="text" class="form-control text-dark border border-dark mb-2" id="cantidadpro">
                <span class="text-error mt-0" id="textocantidad"></span>
            </div>
          </div>

          <div class="row">
                    <div class="col">
                      <button type="button" class="g btn btn-primary" id="btn-agregar-producto"> + AGREGAR</button>
                    </div>
                  </div>
        </div>

                  

  <div class="seccion-formulario">
        <div class="row ">
         <div class="d-flex justify-content-between align-items-center mb-3">
  <h6 class="mb-0">Lista de Producto a Comprar</h6>
  <button type="button" class="btn btn-info btn-sm text-dark" id="btn-limpiar-lista">
    <i class="fa-solid fa-trash-can me-1"></i> Limpiar Lista
  </button>
</div>

         <table id="tabla-productos" class="table table-hover" style="width:100%">
          <thead class="text-white">
              <th class="bg-secondary text-white" >Producto </th>
              <th class="bg-secondary text-white">Cantidad </th>
              <th class="bg-secondary text-white">Accion </th>
          </thead>
          <tbody id="tbody-productos" class="text-dark">

          </tbody>
         </table>
              
        
        </div>
   </div>
     
 <input type="hidden" name="fecha" id="fecha">   
<hr>
<div class="button-container">
    <button type="button" class="btn btn-guardar" id="registrar"><i class="fa-solid fa-receipt me-2"></i> Registrar</button>
    <button type="button" class="btn btn-limpiar" id="limpiardatos" name="limpiar">Limpiar</button>
</div>

        </form> <!--fin del formulario-->
      </div>
    
      
    </div>
  </div>
</div>           


<style>
#inputCantidad {
  pointer-events: auto;
  user-select: auto;
}

</style>

<!-- Modal para ingresar cantidad -->
<div class="modal fade" id="modalCantidad" tabindex="-1" aria-labelledby="modalCantidadLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content border-dark">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCantidadLabel">Cantidad del Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
      <input type="number" id="inputCantidad" class="form-control" min="1" max="200" placeholder="Ingrese cantidad (1–200)" autocomplete="off">


        
        <span class="text-danger small" id="errorCantidad"></span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary btn-sm" id="btnConfirmarCantidad">Agregar</button>
      </div>
    </div>
  </div>
</div>

 
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/moment.js"></script>
  <script src="assets/js/compra.js"></script>
  <?php require_once 'complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->

 
</body>
</html>