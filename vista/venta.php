<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head2.php'; ?>
    <title> Venta | Mister Bolsa C.A</title>
    <!--
    <link rel="stylesheet" href="assets/css/producto.css" /> 
    <link rel="stylesheet" href="assets/css/venta.css" />
      <link rel="stylesheet" href="assets/css/for.css" />
-->
</head>

<body>
  <?php require_once 'complementos/nav2.php'; ?>
    <?php require_once 'complementos/sidebar2.php'; ?>

    
  <div class="row">
      <div class="col">
        <?php foreach ($dolar as $dolar){  ?>
            <input type="hidden" id="tasadolar" value="<?php echo $dolar['precios']?>">
        <?php } ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-5">
              <h1 class="h3 mb-0 text-gray-900 texto-principal"> <i class="fa-solid fa-cash-register mr-3" style="color: #E63946;"></i>
                Venta</h1>
              
          <!-- Button que abre el Modal N1 Registro -->
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fas fa-file-medical me-2"></i>
                <span class="">Registrar Venta</span>
          </button>

          <a href="?pagina=registroventa" class="btn btn-success">Registrar Venta (V2)</a>
          </div>
      </div>
    </div>

    <div class="transaction-list shadow-sm border-0">

      <div class="table-responsive text-dark texto-secundario">
        <table id="myTable" class="table table-hover" style="width:100%">
          <thead class="table-color">
            <tr>
              <th>NRO</th>
              <th>Fecha</th>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>M o D</th>
              <th>Precio Uni. $</th>
              <th>TOTAL $</th>
              <?php if( $tipo_usuario == 1) { ?>
              <th>Accion</th>
              <?php } ?>
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
                            'total' => $dato['total_dl'],
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
                          <button type="button" class="btn badge bg-primary mt-2" data-bs-toggle="modal" data-bs-target="#modal-<?php echo $id_venta; ?>">
                              <i class="fa-solid fa-eye me-2"></i> Ver más
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

                  <?php if ($tipo_usuario == 1): ?>
                  <td class="bg-s">
                      <form action="?pagina=venta" method="POST" >
                          <button type="submit"  class="btn btn-primary btn-sm" name="pdf" value="<?php echo $id_venta; ?>"> <i class="fa-regular fa-file-pdf me-2"></i>Descargar</button>
                      </form>
                  </td>
                  <?php endif; ?>
              </tr>
              <?php endforeach; ?>
            </tbody>
        </table>
      </div>

    </div>

 <!--|||||||||||||||  MODAL INFO VENTA |||||||||||||||||||||||||||-->
 <?php foreach ($ventas as $id_venta => $info): ?>
<div class="modal fade" id="modal-<?php echo $id_venta; ?>" tabindex="-1" aria-labelledby="modalLabel-<?php echo $id_venta; ?>" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content modal-producto">
      <div class="modal-header">
        <h5 class="modal-title text-white" id="modalLabel-<?php echo $id_venta; ?>">Detalles de la venta #<?php echo $id_venta; ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body div-oscuro-2">
        <p class="text-dark texto-secundario">Fecha: <?php echo $info['fecha']; ?></p>
        <table class="table table-bordered table-hover">
          <thead class="table-primary">
            <tr>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Modo</th>
              <th>Precio Unitario</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($info['detalles'] as $detalle): ?>
            <tr>
              <td class="bg-s"><?php echo $detalle['nombre'].' - '.$detalle['marca'].' - '.$detalle['presentacion']; ?></td>
              <td class="text-center bg-s"><?php echo $detalle['cantidad']; ?></td>
              <td class="text-center bg-s"><?php echo strtoupper($detalle['maode']); ?></td>
              <td class="text-center bg-s"><?php echo $detalle['precio_unitario'] . ' $'; ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <h4 class="text-dark texto-secundario">Total: <?php echo $info['total'] . ' $'; ?></h4>
      </div>
      <div class="modal-footer div-oscuro-2">
        <button type="button" class="btn btn-info text-dark" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>


<!-- |||||||||||||||||||||||||   MODAL DE REGISTRO  |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content modal-producto">
    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel"  style="color:rgb(255, 255, 255);">
        <i class="fa-solid fa-cash-register m-2" style="color:rgb(255, 255, 255);"> </i> Registro de Venta
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:rgb(255, 255, 255);"></button>
      </div>

      <div class="modal-body div-oscuro-2">
      <form action="?pagina=venta" autocomplete="off" id="formularioventa"> <!--comienzo Formulario-->
<!-- ||||||||||||||||||  F1||||||||||||||||||||||||-->             
      <div class="row ">

        <div class="col-sm-8">
          <label for="" class="text-dark"> * Producto</label>
          <div class="input-group mb-3">
             <label class="input-group-text" for="inputGroupSelect01"><i class="fa-solid fa-boxes-stacked" style="color: #EF233C;"></i></label>
              <select class="form-select text-dark" aria-label="Default select example" name="selectproducto" id="id_producto">
                <option value="">Seleccione un Producto:</option>
                     <?php foreach($producto as $producto) { ?>
                <option value="<?php echo $producto['id_producto']; ?>" 
                    data-stock="<?php echo $producto['stock']; ?>" 
                    data-precio="<?php echo $producto['precio_unitario']; ?>"
                    data-preciomayor="<?php echo $producto['precio_mayor']; ?>"
                    data-cantidad="<?php echo $producto['cantidad_mayor']; ?>">
                    <?php echo $producto['nombre']." - ".$producto['nombre_marca']." - ".$producto['nombre_presentacion']; ?>
                </option>
                  <?php } ?>
              </select>
          </div>
        </div>

         <div class="col-sm-2">
            <label for="" class="text-dark">* Cantidad</label>
            <input type="text" id="cantidadproducto" class="form-control text-dark border border-secondary" placeholder="Cantidad:"> 
        </div>
        <div class="col">
        <label for="" class="text-dark">Añadir a la venta</label>
          <button type="button" class="btn btn-primary" id="agregar"><i class="fa-solid fa-cart-plus"></i>Agregar</button>
        </div>
      </div>

<!-- ||||||||||||||||||  F2 ||||||||||||||||||||||||-->        
<div class="row text-dark">
  <div class="col-sm-2 stock-container">  
    <div class="input-group input-group-sm mb-3">
      <label class="label-letras">Stock:</label>
        <span class="input-group-text" id="inputGroup-sizing-sm label-fondo" ><i class="fa-solid fa-boxes-stacked" style="color: #EF233C;"></i></span>
        <input type="text" id="stock" placeholder="Stock" class="form-control text-dark" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" disabled>
    </div>


</div>
  <div class="col-sm-2 precio-container">
  <div class="input-group input-group-sm mb-3">
      <label class="label-letras ">Precio D:</label>
        <span class="input-group-text" id="inputGroup-sizing-sm"><i class="fa-solid fa-dollar-sign"style="color: #EF233C;"></i></span>
        <input type="text" id="precio" placeholder="Precio" class="form-control text-dark" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" disabled>
    </div>
  </div>

  <div class="col-sm-2 precio-container">
  <div class="input-group input-group-sm mb-3">
      <label class="label-letras ">Precio M:</label>
        <span class="input-group-text" id="inputGroup-sizing-sm"><i class="fa-solid fa-dollar-sign"style="color: #EF233C;"></i></span>
        <input type="text" id="preciomayor" placeholder="Precio" class="form-control text-dark" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" disabled>
        <input type="hidden" id="cantidadomayor">
      </div>
  </div>

  
  <div class="col-sm-4 precio-container">
  <div class="input-group input-group-sm mb-3">
        <span class="input-group-text" id="inputGroup-sizing-sm "><i class="fa-solid fa-delete-left"style="color:rgb(0, 0, 0);"></i></span>
        <button type="button" class="btn btn-secondary" id="limpiardatos">Limpiar Los Datos</button>
      </div>
  </div>

</div>

<!-- ||||||||||||||||||  F3 ||||||||||||||||||||||||-->  
<hr>
<div class="row">
    <div class="col">
      <div class="d-grid gap-2 d-md-block">
        <button class="btn btn-outline-danger btn-sm" type="button" id="limpiarregistro" data-swal-toast-template="#my-template">Limpiar Registro</button>
      </div>
    </div>
    <div class="col-sm-4 fecha-container">
        <div class="input-group input-group-sm mb-3">
           <label class="label-letras  text-dark"> * Fecha de la venta:</label>
            <input type="date" id="fechaventa" class="form-control text-dark" aria-label="Sizing example input"
                aria-describedby="inputGroup-sizing-sm" required style="borde:2px" disabled>
            <input type="hidden" name="fecha" id="ventafecha">
        </div>
    </div>
</div>
<!-- ||||||||||||||||||  F4 ||||||||||||||||||||||||-->  
<div class="table-responsive">  
        <table class="table mt-3 table-bordered">
    <thead class="table-info">
        <tr>
            <th>PRODUCTO</th>
            <th>CANTIDAD</th>
            <th>VENTA AL</th>
            <th>MONTO $</th>
            <th>MONTO Bs</th>
            <th>SUB TOTAL $</th>
            <th>SUB TOTAL Bs</th>
            <th>ACCION</th>
        </tr>
    </thead>
    <tbody id="listadeventa" class="table-group-divider">
        <!-- Aquí se agregarán las filas -->
    </tbody>
    </table>
    </div>

<!-- ||||||||||||||||||  F5 ||||||||||||||||||||||||-->  
<div class="row justify-content-end">
    <div class="col-sm-auto">
        <label class="form-label text-dark">Total ($)</label>
        <div class="input-group mb-3">
             <label class="input-group-text bg-primary" for="inputGroupSelect01"><b style="color: #fff;"> $ </b></label disabled>
            <input type="text" class="form-control text-dark" name="totalUSD" id="totalUSD" placeholder="$ 0.00"  style="width: 150px;"disabled>
            <input type="hidden" class="form-control text-dark" name="total" id="totaldolares">
        </div>
      </div>
    <div class="col-sm-auto">
        <label class="form-label text-dark">Total (Bs)</label>
        <div class="input-group mb-3">
             <label class="input-group-text bg-primary" for="inputGroupSelect01"><b style="color: #fff;"> Bs</b></label>
        <input type="text" class="form-control text-dark" name="totalbs" id="totalBs" placeholder="Bs 0.00"  style="width: 150px; "disabled>  
        </div>   
      </div>    
</div>

<!-- ||||||||||||||||||  BUTTOM |||||||||||||||||||||||-->  
<span id="textoListaDeVenta"  class="vali-span"></span>
<hr>
<div class="button-container">
    <button type="button" class="btn btn-success" id="registrarventa"><i class="fa-regular fa-credit-card"></i> Registrar Venta</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
</div>
        </form> <!--fin del formulario-->
      </div>
    
      
    </div>|
  </div>
</div>


   





  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/moment.js"></script>
  <script src="assets/js/venta.js"></script>
  <?php require_once 'complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->
  

</body>
</html>