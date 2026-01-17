<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head2.php'; ?>
    <title>Compra | Mister Bolsa C.A</title>
    <link rel="stylesheet" href="assets/css/compra.css" />

</head>

<body>
    <?php require_once 'vista/complementos/nav2.php'; ?>
      <?php require_once 'vista/complementos/sidebar2.php'; ?>
 
      <div class="row">
          <div class="d-sm-flex align-items-center justify-content-between mb-5">
            <h1 class="h3 mb-0 TextOscuroTitel"> <i class="fa-solid fa-receipt me-2" style="color: #E63946;"></i>
              Compra - Filtrado de consulta</h1>
                
            <!-- Button que abre el Modal N1 Registro -->
            <a href="?pagina=compra" class="btn btn-primary"> <i class="fa-solid fa-reply me-2"></i>Volver</a>
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
                    
                    <?php if( $tipo_usuario == 1) { ?>  
                    <th>Accion</th>
                    <?php } ?>
                  </tr>
                </thead>
                
                <tbody>
                  <?php
                      $compras = [];

                      foreach ($filtradofecha as $dato) {
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
    
                       <td class="td-oscuro">
                              <div class="d-flex align-items-center">
                                  <div class="me-3">
                                      <i class="fa-solid fa-receipt fa-2x" style="color: #E63946;"></i>
                                  </div>
                                  <div>
                                      <div>
                                        <?php echo date("d/m/Y", strtotime($info['fecha'])); ?>
                                      </div>
                                      <div class="text-muted small">
                                          Nro: <?php echo $id_compra; ?>
                                      </div>
                                  </div>
                              </div>
                          </td>

                      <td class="td-oscuro">
                          <ul class="mb-0">
                              <?php 
                              $detalles = $info['detalles'];
                              $mostrar = array_slice($detalles, 0, 2);
                              foreach ($mostrar as $detalle): ?>
                                  <li>
                                      <i class="fa-solid fa-boxes-stacked text-primary me-2"></i>
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

                      <td class="td-oscuro">
                          <ul class="mb-0">
                              <?php foreach ($mostrar as $detalle): ?>
                                  <li><?php echo $detalle['cantidad']; ?></li>
                              <?php endforeach; ?>
                          </ul>
                      </td>

                      <?php if ($tipo_usuario == 1): ?>
                      <td class="text-center td-oscuro">
                          <form action="?pagina=compra">
                              <button type="button" class="btn btn-primary btn-sm" disabled>
                                Descargar
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
                                    <div class="modal-body div-oscuro-2">
                                      
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
                                            <td class="td-oscuro"><?php echo $detalle['nombre'].' - '.$detalle['marca'].' - '.$detalle['presentacion']; ?></td>
                                            <td class="text-center td-oscuro"><?php echo $detalle['cantidad']; ?></td>
                                          </tr>
                                          <?php endforeach; ?>
                                        </tbody>
                                      </table>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <?php endforeach; ?>

          </div>
      </div> <!-- DIV 2-->
        

 
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/moment.js"></script>
  <?php require_once 'vista/complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->

 
</body>
</html>