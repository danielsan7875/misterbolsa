<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head2.php'; ?>
    <title> Venta | Mister Bolsa C.A</title>
</head>

<body>
  <?php require_once 'vista/complementos/nav2.php'; ?>
    <?php require_once 'vista/complementos/sidebar2.php'; ?>

    
  <div class="row">
      <div class="col">
        <?php foreach ($dolar as $dolar){  ?>
            <input type="hidden" id="tasadolar" value="<?php echo $dolar['precios']?>">
        <?php } ?>
        <div class="d-sm-flex align-items-center justify-content-between mb-5">
              <h1 class="h3 mb-0 TextOscuroTitel"> <i class="fa-solid fa-cash-register me-2" style="color: #E63946;"></i>
                Venta</h1>
              
          <!-- Button que abre el Modal N1 Registro -->
             <a href="?pagina=venta" class="btn btn-primary"> <i class="fa-solid fa-reply me-2"></i>Volver</a>
          </div>
      </div>
    </div>

    

       <p class="text-muted"> Periodo actual de registros: <strong><?= $periodo ?></strong> </p>

    <div class="transaction-list shadow-sm border-0 DivOscuro1">

      <div class="table-responsive text-dark TextOscuro">
        <table id="myTable" class="table table-hover" style="width:100%">
          <thead class="table-color">
            <tr>
              <th>Nro y Fecha</th>
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
            foreach ($filtradofecha as $dato) {
                $id_venta = $dato['id_venta'];

                if (!isset($ventas[$id_venta])) {
                    $ventas[$id_venta] = [
                        'fecha' => $dato['fecha'],
                        'total' => $dato['total_dl'],
                        'totalBs' => $dato['total_bs'],
                        'detalles' => [],
                        'pagos' => []
                    ];
                }

                // Agregar productos
                $ventas[$id_venta]['detalles'][] = [
                    'nombre' => $dato['nombre_producto'],
                    'cantidad' => $dato['cantidad'],
                    'marca' => $dato['nombre_marca'],
                    'presentacion' => $dato['nombre_presentacion'],
                    'precio_unitario' => $dato['precio_unitario'],
                    'maode' => $dato['maode']
                ];

                // Agregar pagos SIN DUPLICAR
                if ($dato['id_detallepago']) {

                    // Creamos una clave única por id_detallepago
                    $key = $dato['id_detallepago'];

                    if (!isset($ventas[$id_venta]['pagos'][$key])) {
                        $ventas[$id_venta]['pagos'][$key] = [
                            'metodo' => $dato['metodo_pago'],
                            'monto_dolares' => $dato['monto_dolares'],
                            'monto_bs' => $dato['monto_bs'],
                            'referencia' => $dato['referencia']
                        ];
                    }
                }
            }

              ?>
              <?php foreach ($ventas as $id_venta => $info): ?>
              <tr>

                   <td class="td-oscuro">
                  <div class="d-flex align-items-center">
                      <div class="me-3">
                          <i class="fa-solid fa-cash-register fa-2x" style="color: #E63946;"></i>
                      </div>
                      <div>
                          <div style="font-size: 1.1rem;">
                                 Nro: <?php echo $id_venta; ?>
                          </div>
                          <div class="small text-primary">
                            <strong>
                            Fecha: <?php echo date("d/m/Y", strtotime($info['fecha'])); ?>
                            </strong>
                          </div>
                      </div>
                  </div>
              </td> 

                  <td class="td-oscuro">
                      <ul class="mb-0 list-unstyled">
                          <?php 
                          $detalles = $info['detalles'];
                          $mostrar = array_slice($detalles, 0, 2);
                          foreach ($mostrar as $detalle): ?>
                              <li>
                                  <i class="fa-solid fa-boxes-stacked me-2 text-primary"></i>
                                  <?php echo $detalle['nombre'].' - '.$detalle['marca'].' - '.$detalle['presentacion']; ?>
                              </li>
                          <?php endforeach; ?>
                      </ul>
                      <?php if (count($detalles) > 2){ ?>
                          <button type="button" class="btn badge bg-primary mt-2" data-bs-toggle="modal" data-bs-target="#modal-<?php echo $id_venta; ?>">
                              <i class="fa-solid fa-eye me-2"></i> Ver más información
                          </button>
                      <?php } else{  ?>
                          <button type="button" class="btn badge bg-primary mt-2" data-bs-toggle="modal" data-bs-target="#modal-<?php echo $id_venta; ?>">
                              <i class="fa-solid fa-eye me-2"></i> Ver más
                          </button>
                      <?php } ?>
                  </td>

                  <td class="td-oscuro">
                      <ul class="list-unstyled"> 
                        <?php foreach ($mostrar as $detalle) { ?>
                          <li><?php echo $detalle['cantidad']?></li> 
                          <?php } ?> 
                      </ul>
                  </td>

                  <td class="td-oscuro">
                     <ul class="list-unstyled"> 
                    <?php foreach ($mostrar as $detalle) { ?>
                      <li><?php echo $detalle['maode']; ?></li> <?php } ?> 
                      </ul>
                    </td>

                  <td class="td-oscuro">
                      <ul class="mb-0 list-unstyled">
                          <?php foreach ($mostrar as $detalle): ?>
                              <li><?php echo $detalle['precio_unitario'] . ' $'; ?></li>
                          <?php endforeach; ?>
                      </ul>
                  </td>

                  <td class="td-oscuro align-middle">
                    <?php echo  '$ '.$info['total']; ?>
                            <p class="text-muted">Bs. <?php echo $info['totalBs'] ?></p>
                  </td>

                  <?php if ($tipo_usuario == 1): ?>
                  <td class="td-oscuro align-middle">
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
      <div class="modal-body">
      
        <h5 class="text-dark TextOscuro"><i class="fa-solid fa-calendar-days me-2"></i>Fecha: <?php echo date("d/m/Y", strtotime($info['fecha'])); ?></h5>

        <div class="seccion-formulario">
        <?php if (!empty($info['pagos'])): ?>
            <h5 class="text-dark TextOscuro">Métodos de Pago</h5>

            <table class="table table-bordered table-hover mb-4">
                <thead class="table-primary">
                    <tr>
                        <th>Método</th>
                        <th>Monto $</th>
                        <th>Monto Bs</th>
                        <th>Referencia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_dl = 0;
                    $total_bs = 0;

                    foreach ($info['pagos'] as $pago): 
                        $total_dl += $pago['monto_dolares'];
                        $total_bs += $pago['monto_bs'];
                    ?>
                    <tr>
                        <td class="td-oscuro"><?php echo $pago['metodo']; ?></td>
                        <td class="text-center td-oscuro"><?php echo $pago['monto_dolares']; ?></td>
                        <td class="text-center td-oscuro"><?php echo $pago['monto_bs']; ?></td>
                        <td class="text-center td-oscuro"><?php echo $pago['referencia']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
            </div>
            
               <div class="seccion-formulario">
             <h5 class="text-dark TextOscuro">Lista de Productos</h5>
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
                  <td class="td-oscuro"><?php echo $detalle['nombre'].' - '.$detalle['marca'].' - '.$detalle['presentacion']; ?></td>
                  <td class="text-center td-oscuro"><?php echo $detalle['cantidad']; ?></td>
                  <td class="text-center td-oscuro"><?php echo strtoupper($detalle['maode']); ?></td>
                  <td class="text-center td-oscuro"><?php echo $detalle['precio_unitario'] . ' $'; ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            </div>

            <div class="d-flex justify-content-end gap-3 text-dark">
                <h4 class="text-dark TextOscuro">Total: <?php echo '$ '.$info['total']; ?></h4>
                <h4 class="text-dark TextOscuro">| <?php echo 'Bs '.$info['totalBs']; ?></h4>
            </div>
           
          </div>
          <div class="modal-footer div-oscuro-2">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>



  
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/venta.js"></script>
  <?php require_once 'vista/complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->
  

</body>
</html>