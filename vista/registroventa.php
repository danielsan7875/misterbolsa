<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head.php'; ?>
    <title>Resgistro de Venta | Mister Bolsa C.A</title>
     <link rel="stylesheet" href="assets/css/for.css" />
</head>

<body>
  <!-- LOADER-->
<div id="loaderPagina" class="section_loader">
<div class="loader-3"></div>
</div>
  <!-- ||||||||||||||  Comienzo de Sidebar y Nav |||||||||||||||||||||||-->

    <div class="body-wrapper body-oscuro">
    <?php require_once 'vista/complementos/nav.php'; ?>
    <div class="container-fluid">
  <!--|||||||||||||||   Fin de Sidebar y Nav  |||||||||||||||||||||||||||-->

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
       <h1 class="h3 mb-0 texto-principal"> <i class="fa-solid fa-cash-register me-2" style="color: #E63946;"></i>
        Registro de Venta</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
          <button type="button" class="btn btn-info text-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
           <i class="fa-solid fa-folder me-2"></i> Consulta de Venta Hoy
          </button>

          <a href="?pagina=venta" class="btn btn-primary">
          <span class="icon ">
            <i class="fa-solid fa-reply me-2"></i>
            </span>
            <span class="text ">Salir de Venta</span>
        </a>
      
      </div>

  
    <div class="row">
      <!-- Sección Ventas -->
      <div class="col-md-6">
        <h5 class="section-title texto-secundario">Informacion de la Venta</h5>
        <div class="card p-3">
    
        <form action="?pagina=registroventa" method="POST" autocomplete="off" id="formularioventa">
          <table class="table table-bordered" id="listadeventa">
            <thead class="table-primary">
              <tr>
                <th class="text-center">-</th>
                <th class="text-center">Producto</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Precio</th>
                <th class="text-center">Tipo</th>
                <th class="text-center">Subtotal</th>
              </tr>
            </thead>
            <tbody id="bodyventa">
               <tr class="fila-vacia">
                <td colspan="6" class="text-center bg-s text-dark texto-secundario">No hay productos seleccionados</td>
              </tr>
            </tbody>
          </table>

         
          <div class="row mt-2 text-center mb-2">
            
            <div class="col-md-4 text-dark texto-secundario">Total $: <br>
             <h5 class="text-dark texto-secundario " ><strong> 0.00 $</strong></h5> </div>
             <div class="col-md-4 text-dark texto-secundario">Tasa de Cambio: <br>
             <?php foreach ($dolar as $dolar){  ?>
               <h5 class="text-dark texto-secundario badge rounded-pill bg-warning text-dark" > <strong><?php echo $dolar['precios']?> Bs</strong> </h5></div>
                <input type="hidden" id="tasadolar" value="<?php echo $dolar['precios']?>">
                <?php } ?>
             
            <div class="col-md-4 text-dark texto-secundario">Total Bs: <br>
              <h5 class="text-dark texto-secundario" > <strong>0.00 Bs</strong> </h5> </div>

                <input type="hidden" name="total" id="totaldolaresfor">
                <input type="hidden" name="fecha" id="ventafecha">

          </div>
          
          <div class="mt-3">
            
            <div class="form-check form-switch ">
             
              <input class="form-check-input" type="checkbox" role="switch" id="switchCheckChecked">
              <label class="form-check-label text-dark texto-secundario" for="switchCheckChecked">Click para confirmar la venta <span class="text-danger">*</span></label>
            </div>
          </div>

          <div class="mt-3 text-center">
            
            <button type="button" class="btn btn-guardar text-dark" name="registrar" id="registrarventa">Registrar venta</button>
          
          </div>
          </form>
        </div>
      </div>


      <!-- Sección Inventario -->
      <div class="col-md-6">
        <h5 class="section-title">Agrega un artículo</h5>
        <input type="text" id="buscador" class="form-control text-dark mb-2 border border-info" placeholder="Buscar productos...">

        <table id="tabla-inventario" class="table table-bordered">
          <thead class="table-info">
            <tr>
              <th class="text-center">+</th>
              <th class="text-center">Nombre del Producto</th>
              <th class="text-center">Stock</th>
              <th class="text-center">Precio Bs.D</th>
            </tr>
          </thead>
     
          <tbody>
              <?php
              foreach ($producto as $producto){
              ?>
            <tr>
              <td class="bg-s">
                <button class="btn btn-primary btn-sm btn-agregar"
                data-id="<?php echo $producto['id_producto']?>"
                data-text="<?php echo $producto['nombre'].' - '.$producto['nombre_marca'].' - '.$producto['nombre_presentacion']?>"
                data-precio-detal="<?php echo $producto['precio_unitario']?>"
                data-precio-mayor="<?php echo $producto['precio_mayor']?>"
                data-cantidad-mayor="<?php echo $producto['cantidad_mayor']?>"
                data-stock="<?php echo $producto['stock']?>">Añadir</button>

              </td>
              <td class="bg-s"><?php echo $producto['nombre'].' - '.$producto['nombre_marca'].' - '.$producto['nombre_presentacion']?></td>
                   <?php
                  $stock = $producto['stock'];
                  $stockMinimo = $producto['stock_minimo'];

                  if ($stock == 0) {
                    $claseStock = 'text-bg-danger';
                  } elseif ($stock < $stockMinimo) {
                    $claseStock = 'text-bg-warning';
                  } else {
                    $claseStock = 'text-bg-primary';
                  }
                ?>

                <td class="text-center bg-s">
                  <span class="badge <?php echo $claseStock; ?>"><?php echo $stock; ?></span>
                </td>

              <td class="text-center bg-s"> 
               <b> D: <span class="preciobsdetal" data-usd="<?php echo $producto['precio_unitario'] ?>"></span> </b> <br>
                M: <span class="preciobsmayor" data-usd="<?php echo $producto['precio_mayor'] ?>"></span>

                <input type="hidden" id="preciodetals" value="<?php echo $producto['precio_unitario'] ?>">
                <input type="hidden" id="preciomayors" value="<?php echo $producto['precio_mayor'] ?>">
              </td>

            </tr>
      
            <!-- Agrega más filas según tu inventario -->
               <?php } ?>
          </tbody>
        </table>
          <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-end" id="paginacion-productos">
            <li class="page-item disabled ">
              <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Atras</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
              <a class="page-link" href="#">Siguiente</a>
            </li>
          </ul>
        </nav>
      </div>
    </div>




    <!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content modal-producto">
      <div class="modal-header">
        <h1 class="modal-title fs-5 text-white" id="staticBackdropLabel">Consulta de Venta (HOY)</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body div-oscuro-2">
                      <div class="card shadow mb-4"> <!-- comienzo del Cuadro blanco-->
<div class="table-margen">    <!-- comienzo del Cuadro blanco body-->
      <div class="table-responsive text-dark texto-secundario">
      <table id="myTable" class="table table-hover" style="width:100%">
    <thead>
      <tr class="table-primary">
        <th>#</th>
        <th>Fecha</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>M o D</th>
        <th>Precio Uni. $</th>
        <th>TOTAL $</th>
  
        <th>Accion</th>
  
      </tr>
    </thead>
    <tbody>
      <?php

$ventas = [];
foreach ($registro as $dato) {
    $id_venta = $dato['id_venta'];
    if (!isset($ventas[$id_venta])) {
        $ventas[$id_venta] = [
            'fecha' => $dato['fecha'],
            'total' => $dato['total'],
            'detalles' => []
        ];
    }
    $ventas[$id_venta]['detalles'][] = [
        'nombre' => $dato['nombre'],
        'cantidad' => $dato['cantidad'],
        'marca' => $dato['nombre_marca'],
        'presentacion' => $dato['nombre_presentacion'],
        'precio_unitario' => $dato['precio_unitario'],
        'maode' => $dato['maode']
    ];
}


?>
<?php foreach ($ventas as $id_venta => $info): ?>
<tr>
    <td class="text-center bg-s"><?php echo $id_venta; ?></td>
    <td class="text-center bg-s"><?php echo date("d/m/Y", strtotime($info['fecha'])); ?></td>

    <td class="bg-s">
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
            <button type="button" class="btn badge bg-primary mt-2">
                <i class="fa-solid fa-eye me-2"></i> Descargar el PDF para ver detallado
            </button>
        <?php endif; ?>
    </td>

    <td class="bg-s">
        <ul> 
          <?php foreach ($mostrar as $detalle) { ?>
             <li><?php echo $detalle['cantidad']?></li> 
             <?php } ?> 
        </ul>
    </td>

    <td class="bg-s"> <ul> 
      <?php foreach ($mostrar as $detalle) { ?>
         <li><?php echo $detalle['maode']; ?></li> <?php } ?> 
        </ul>
       </td>

    <td class="bg-s">
        <ul class="mb-0">
            <?php foreach ($mostrar as $detalle): ?>
                <li><?php echo $detalle['precio_unitario'] . ' $'; ?></li>
            <?php endforeach; ?>
        </ul>
    </td>

    <td class="text-center bg-s"><?php echo $info['total'] . ' $'; ?></td>

    
    <td class="bg-s">
        <form action="?pagina=venta" method="POST" >

            <button type="submit"  class="btn btn-primary btn-sm" name="pdf" value="<?php echo $id_venta; ?>"> <i class="fa-regular fa-file-pdf me-2"></i>Descargar</button>
        </form>
    </td>
  
</tr>
<?php endforeach; ?>
</tbody>

    </table>
</div>
</div>
</div>



      </div>
      <div class="modal-footer div-oscuro-2">
        <button type="button" class="btn btn-limpiar" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
   <script src="assets/js/moment.js"></script>
  <script src="assets/js/registroventa.js"></script>
  <?php require_once 'vista/complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->    
</body>
</html>