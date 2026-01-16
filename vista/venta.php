<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head2.php'; ?>
    <title> Venta | Mister Bolsa C.A</title>
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
              <h1 class="h3 mb-0 TextOscuroTitel"> <i class="fa-solid fa-cash-register mr-3" style="color: #E63946;"></i>
                Venta</h1>
              
          <!-- Button que abre el Modal N1 Registro -->
              <a href="?pagina=registroventa" class="btn btn-success">  <i class="fas fa-file-medical me-2"></i> Registrar Venta (VPOS)</a>
          </div>
      </div>
    </div>

      <div class="transaction-list shadow-sm border-0 DivOscuro1">

      </div>

    <p class="text-muted">Periodo actual de registros: Últimos 7 días (Hoy y 6 días anteriores)</p>

    <div class="transaction-list shadow-sm border-0 DivOscuro1">

      <div class="table-responsive text-dark TextOscuro">
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
                  <td class="text-center td-oscuro"><?php echo $id_venta; ?></td>
                  <td class="text-center td-oscuro"><?php echo date("d/m/Y", strtotime($info['fecha'])); ?></td>

                  <td class="td-oscuro">
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

                  <td class="td-oscuro">
                      <ul> 
                        <?php foreach ($mostrar as $detalle) { ?>
                          <li><?php echo $detalle['cantidad']?></li> 
                          <?php } ?> 
                      </ul>
                  </td>

                  <td class="td-oscuro"> <ul> 
                    <?php foreach ($mostrar as $detalle) { ?>
                      <li><?php echo $detalle['maode']; ?></li> <?php } ?> 
                      </ul>
                    </td>

                  <td class="td-oscuro">
                      <ul class="mb-0">
                          <?php foreach ($mostrar as $detalle): ?>
                              <li><?php echo $detalle['precio_unitario'] . ' $'; ?></li>
                          <?php endforeach; ?>
                      </ul>
                  </td>

                  <td class="text-center td-oscuro"><?php echo $info['total'] . ' $'; ?></td>

                  <?php if ($tipo_usuario == 1): ?>
                  <td class="td-oscuro">
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
        <p class="text-dark TextOscuro">Fecha: <?php echo $info['fecha']; ?></p>
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
        <h4 class="text-dark TextOscuro">Total: <?php echo $info['total'] . ' $'; ?></h4>
      </div>
      <div class="modal-footer div-oscuro-2">
        <button type="button" class="btn btn-info text-dark" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>



  
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/venta.js"></script>
  <?php require_once 'complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->
  

</body>
</html>