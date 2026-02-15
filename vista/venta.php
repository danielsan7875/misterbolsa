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
        <div class="d-sm-flex align-items-center justify-content-between mb-5">
              <h1 class="h3 mb-0 TextOscuroTitel"> <i class="fa-solid fa-cash-register me-2" style="color: #E63946;"></i>
                Venta</h1>
              
          <!-- Button que abre el Modal N1 Registro -->
            <div class="d-flex gap-2"> 
                <a href="?pagina=registroventa" class="btn btn-success TA1">  <i class="fas fa-file-medical me-2"></i> Registrar Venta (V2)</a>
                
                <button type="button" class="btn btn-primary" id="Teayuda">
                  <i class="fa-solid fa-circle-question me-2"></i>
                  Ayuda
                </button>
              </div>
          </div>
      </div>
    </div>

     <div class="transaction-list shadow-sm border-0 DivOscuro1 TA2">
            <div class="col-12">
              <p class="text-muted"><i class="fa-solid fa-calendar-days me-2"></i>Consulta de Ventas por Fecha</p>
            </div>

          <form method="POST" action="?pagina=venta" class="row g-2 align-items-end mt-2">

        <!-- Checkbox 15 dias -->
        <div class="col-12 col-md-2 TA3">
            <label class="form-label invisible">Filtro</label>

            <input type="checkbox" id="filtro15" name="filtro" value="15" hidden>

            <label for="filtro15" class="filter-card">
                <span class="check-box"></span>
                <span class="filter-text">Últimos 15 días</span>
            </label>
        </div>

         <!-- Checkbox MES -->
        <div class="col-12 col-md-2 TA4">
            <label class="form-label invisible">Filtro</label>

            <input type="checkbox" id="filtroMes" name="filtro" value="mes" hidden>

            <label for="filtroMes" class="filter-card">
                <span class="check-box"></span>
                <span class="filter-text">Este mes</span>
            </label>
        </div>

        <!-- Desde -->
        <div class="col-12 col-md-3 TA5">
            <label class="form-label"><i class="fa-solid fa-calendar-days me-2"></i>Desde</label>
            <input type="date" id="fecha_desde" name="desde" class="form-control">
        </div>

        <!-- Hasta -->
        <div class="col-12 col-md-3 TA6">
            <label class="form-label"><i class="fa-solid fa-calendar-days me-2"></i>Hasta</label>
            <input type="date" id="fecha_hasta" name="hasta" class="form-control">
        </div>

        <!-- Botón -->
        <div class="col-12 col-md-2 d-grid ">
            <button type="submit" name="consultafiltro" value="buscar" class="btn btn-primary" style=" padding: 12px 15px;">
              <i class="fa-solid fa-magnifying-glass me-2"></i>  Buscar
            </button>
        </div>

    </form>


    </div>

    <p class="text-muted">Periodo actual de registros: Últimos 7 días (Hoy y 6 días anteriores)</p>

    <div class="transaction-list shadow-sm border-0 DivOscuro1">

      <div class="table-responsive text-dark TextOscuro TA7">
        <table id="myTable" class="table table-hover" style="width:100%">
          <thead class="table-color">
            <tr>
              <th>Nro y Fecha</th>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Modo</th>
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
                        'totalBs' => $dato['total_bs'],
                        'detalles' => [],
                        'pagos' => []
                    ];
                }

                // Agregar productos
                // Agregar productos SIN DUPLICAR
                $productoKey = $dato['id_producto'];

                if (!isset($ventas[$id_venta]['detalles'][$productoKey])) {
                    $ventas[$id_venta]['detalles'][$productoKey] = [
                        'nombre' => $dato['nombre_producto'],
                        'cantidad' => $dato['cantidad'],
                        'marca' => $dato['nombre_marca'],
                        'presentacion' => $dato['nombre_presentacion'],
                        'precio_unitario' => $dato['precio_unitario'],
                        'maode' => $dato['maode']
                    ];
                }

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
                          <button type="button" class="btn badge bg-primary mt-2 TA8" data-bs-toggle="modal" data-bs-target="#modal-<?php echo $id_venta; ?>">
                              <i class="fa-solid fa-eye me-2"></i> Ver más información
                          </button>
                      <?php } else{  ?>
                          <button type="button" class="btn badge bg-primary mt-2 TA8" data-bs-toggle="modal" data-bs-target="#modal-<?php echo $id_venta; ?>">
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
                              <li><?php echo number_format($detalle['precio_unitario'],2,',','.'). ' $'; ?></li>
                          <?php endforeach; ?>
                      </ul>
                  </td>

                  <td class="td-oscuro align-middle">
                    <?php echo  '$ '.number_format($info['total'],2,',','.'); ?>
                            <p class="text-muted">Bs. <?php echo number_format($info['totalBs'], 2, ',', '.');  ?></p>
                  </td>

                  <?php if ($tipo_usuario == 1): ?>
                  <td class="td-oscuro align-middle">
                      <form action="?pagina=venta" method="POST" >
                          <button type="submit"  class="btn btn-primary btn-sm TA9" name="pdf" value="<?php echo $id_venta; ?>"> <i class="fa-regular fa-file-pdf me-2"></i>Descargar</button>
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
                        <td class="text-center td-oscuro"><?php echo number_format($pago['monto_dolares'],2,',','.'); ?></td>
                        <td class="text-center td-oscuro"><?php echo number_format($pago['monto_bs'],2,',','.'); ?></td>
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
                  <td class="text-center td-oscuro"><?php echo number_format($detalle['precio_unitario'],2,',','.') . ' $'; ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            </div>

            <div class="d-flex justify-content-end gap-3 text-dark">
                <h4 class="text-dark TextOscuro">Total: <?php echo '$ '.number_format($info['total'],2,',','.'); ?></h4>
                <h4 class="text-dark TextOscuro">| <?php echo 'Bs '.number_format($info['totalBs'],2,',','.'); ?></h4>
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
    <script src="assets/js/moment.js"></script>
  <?php require_once 'complementos/footer2.php'; ?>  
  <script src="assets/js/ConsultaFecha.js"></script>
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->
  <script>
    const driver = window.driver.js.driver;
        const driverObj = driver({
            showProgress: true,
            // Textos en ESPAÑOL
            nextBtnText: 'Siguiente →',
            prevBtnText: '← Atrás',
            doneBtnText: '¡Entendido!',
            progressText: 'Paso {{current}} de {{total}}',
            steps: [
                { 
                    // Usamos el punto (.) para referirnos a la clase
                    element: '.TA1',  popover: { title: 'Registrar Venta', 
                        description: 'Haz una venta nueva y descuenta productos de tu inventario', side: "top" 
                    } 
                },
                { 
                    element: '.TA2', popover: { title: 'Panel de Consulta por fechas', 
                        description: 'Filtra tus registros por fechas específicas para localizar transacciones pasadas.', side: "top" 
                    } 
                },
                { 
                  element: '.TA3', popover: { title: 'Filtro de ultimos 15 dias', 
                        description: 'Muestra rápidamente la actividad de las últimas dos semanas.', side: "top" 
                  } 
                },
                { 
                  element: '.TA4', popover: { title: 'Filtro de Mes', 
                        description: 'Visualiza todos los registros acumulados desde el primer día de este mes.', side: "top" 
                  } 
                },
                { 
                  element: '.TA5', popover: { title: 'Filtro Rango fecha (inicio)', 
                        description: 'Elige desde qué día quieres empezar a ver tus registros', side: "top" 
                  } 
                },
                { 
                  element: '.TA6', popover: { title: 'Filtro Rango fecha (final)', 
                        description: 'Elige hasta qué día buscar. (Recuerda: puedes consultar periodos de máximo 3 meses)', side: "top" 
                  } 
                },
                { 
                  element: '.TA7', popover: { title: 'Movimientos de los ultimos 7 Dias', 
                        description: 'Resumen rápido de los movimientos y transacciones realizados en los últimos 7 días', side: "top" 
                  } 
                },
                { 
                  element: '.TA8', popover: { title: 'Ver Más / Ver Mas Informacion', 
                        description: 'Consulta el desglose detallado de los productos vendidos y que metodo de pago se utilizo', side: "top" 
                  } 
                },
                { 
                  element: '.TA9', popover: { title: 'Generar PDF', 
                        description: 'Genera un comprobante oficial de la venta en formato PDF.', side: "top" 
                  } 
                }
                
            ]
        });

document.querySelector('#Teayuda').addEventListener('click', () => {
  driverObj.drive();
});  
</script>

</body>
</html>