<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once 'complementos/head.php'; ?>
    <title>Resgistro Venta | Mister Bolsa C.A</title>
</head>
<body>
    
<div id="overlay-bloqueo">
    <div class="spinner"></div>
    <p>Procesando Venta...</p>
</div>

    <?php require_once 'complementos/pos2.php'; ?>    

    <!-- Contenedor Principal (Grid) -->
    <div class="container-fluid main-content">
        <div class="row h-100 g-3">
            
            <!-- 2. Columna Izquierda: Lista de Productos -->
            <div class="col-lg-8 h-100">
                <div class="products-column">
                    <!-- Barra de Búsqueda -->
                    <div class="input-group mb-4 sticky-top pt-1" style="z-index: 10;">
                        <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-3">
                           
                            <i class="fa-solid fa-magnifying-glass text-muted" style="width: 18px;"></i>
                        </span>
                        <input type="text" id="inputBuscar" class="form-control border-start-0 rounded-end-pill py-2" placeholder="Buscar producto por nombre">
                      
                         <!-- Loader -->
                        <div id="loaderBuscador" class="text-center my-2 col-12">
                            <div class="d-flex justify-content-center align-items-center gap-2">
                                       
                                <div class="spinner-border spinner-border-sm text-primary"></div>
                                <span class="fw-semibold text-primary">Buscando productos...</span>
                                   
                            </div>
                        </div>

                         <!-- Mensaje vacío -->
                        <div id="mensajeVacio" class="col-12 my-5  d-none">
                            <div class="alert alert-warning text-center">
                                <i class="fa-solid fa-circle-exclamation me-2"></i>
                                No se encontraron productos.
                            </div>
                        </div>

                    </div>

                    <!-- Grid de Cards -->
                    <div class="row row-cols-1 row-cols-md-3 g-4"  id="contenedorProductos"> 

                       
                    </div>
                </div>
            </div>

            <!-- 3. Columna Derecha: Detalle de Venta -->
              

                        
            <div class="col-lg-4 h-100 pb-3 DivOscuro2">
              
               

                <div class="cart-column DivOscuro2">
                    <form action="?pagina=registroventa" method="POST" autocomplete="off" id="SystemPos">
                  <div class="cart-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fa-solid fa-cart-shopping me-2"></i>Carrito Actual
                             <span id="cart-count-badge" class="badge bg-primary rounded-pill ms-2 fs-6">0</span> </h5>
                                                        

                        <button type="button" id="vaciar-carrito" class="btn btn-sm btn-outline-danger border-0">
                            <i class="fa-solid fa-trash"></i> Vaciar
                        </button>
                    </div>
                  
                    <!-- Lista de Productos Añadidos (Dinámica) -->
           
                    <div id="lista-carrito" class="cart-items">
                         
                        <!-- Los productos se inyectan aquí -->
                    </div>

                    <!-- Sección Inferior (Método de Pago, Totales, Botón) -->
                    <div class="payment-section DivOscuro2">
                        <!-- Métodos de Pago -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label text-muted small fw-bold text-uppercase m-0">
                                Métodos de Pagos
                            </label>

                            <button type="button" class="btn btn-primary btn-sm">
                               <i class="fa-solid fa-circle-plus me-2"></i>  Pagos Multiples
                            </button>
                        </div>

                        <div class="row g-2 mb-4">
                          <?php if (!empty($metodo)): ?>
                            <?php foreach ($metodo as $metodo): ?>
                                <div class="col-3">
                                   <div class="payment-method-card TextOscuroTitel2" data-id="<?php echo $metodo['id_metodopago']; ?>">
                                        <?php
                                            // Asignamos el icono según el ID de la base de datos
                                            $icono = "fa-money-bill"; // Icono por defecto
                                            switch ($metodo['id_metodopago']) {
                                                case 1: $icono = "fa-mobile"; break;         // Pago Móvil
                                                case 2: $icono = "fa-credit-card"; break;    // Tarjeta
                                                case 3: $icono = "fa-money-bill"; break;     // Efectivo Bs
                                                case 4: $icono = "fa-money-bill-wave"; break; // Divisas $
                                            }
                                        ?>
                                        <i class="fa-solid <?php echo $icono; ?> mb-1"></i>
                                        <div class="small fw-medium TextOscuroTitel2">
                                            <?php echo htmlspecialchars($metodo['nombre']); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <p class="text-muted">No hay métodos de pago configurados.</p>
                            </div>
                        <?php endif; ?>
                        </div>
                          

                          <div class="mt-3 d-none" id="contenedorReferencia">
                                <label class="form-label">Referencia</label>
                                <input type="text" class="form-control" id="referencia_visible"
                                    placeholder="Ingrese referencia (4 a 6 dígitos)">
                            </div>


                        <!-- Totales -->
                        <div class="border-top border-dashed mb-3 pt-3">
                           
                            <?php $precio = $dolar[0]['precios'];  ?>
                            <div class="total-row">
                                <span class="text-muted">Total en Dólares</span>
                                        <span class="fw-medium text-muted" id="tasade"> Tasa de Cambio: Bs <?= $precio ?></span>

                                <strong><span id="total-dolares" class="fw-bold text-dark fs-5 TextOscuro">$0.00</span></strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fs-5 fw-bold text-dark TextOscuro">Total a Pagar</span>

                                <strong><span class="grand-total text-primary">Bs. 0.00</span></strong>
                            </div>
                        </div>


                       <input type="hidden" id="tasadecambio" value="<?= $precio ?>">
                         
                        <input type="hidden" name="fecha" id="fecha">
                        <input type="hidden" name="total_dl" id="total_dl">
                        <input type="hidden" name="total_bs"id="total_bs">

                        <input type="hidden" name="monto_dolares[]" id="monto_dl">
                        <input type="hidden" name="monto_bs[]" id="monto_bs">
                        

                        <input type="hidden" name="id_metodopago[]" id="metodopago_id">
                        <input type="hidden" name="referencias[]" id="referencia">

                        <?php if (is_array($cierre)): ?>
                            <input type="hidden" name="id_cierre" value="<?php echo $cierre['id_cierre']; ?>">
                            <input type="hidden" name="hora" id="hora">
                        <?php endif; ?>

                        <!-- Botón Continuar -->
                        
                        <?php 
                            if (!$cierre) {
                            echo'
                            <button type="button" class="btn-process shadow-sm d-flex justify-content-between align-items-center" disabled>
                                <span>Cierro no activo, no puede hacer venta</span>
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            ';
                            }else{
                            echo'
                            <button type="button" class="btn-process shadow-sm d-flex justify-content-between align-items-center" id="registrar">
                                <span>Continuar Venta</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                            ';
                            }
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/moment.js"></script>

    <script src="assets/js/datetable/data.js"></script>

    <script src="assets/js/sweetalert2.js"></script>
    
    <script src="assets/librerias/bootstrap5/js/bootstrap.min.js"></script>
    
    <script src="assets/librerias/bootstrap5/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/SystemVentaPos.js"></script>

    <!-- |||||||||||||||| MODALES ||||||||||||||||| -->

    <!-- Modal CONFIGURACION -->
    <div class="modal fade" id="modalconfiguracion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modal-producto">
            <div class="modal-header" >
                <h5 class="modal-title" id="staticBackdropLabel">Configuración</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                        
                <div class="transaction-list shadow-sm border-0 mt-3 DivOscuro2">
                    <div class="row">
                        <div class="col">
                            <h5>Modo Oscuro</h5>
                        </div>
                        <div class="col">
                            <div class="form-check form-switch">
                                <input class="form-check-input" id="toggleModo" type="checkbox" id="flexSwitchCheckDefault">
                               
                            </div>
                        </div>
                    </div>
                    
                </div>
                  <div class="transaction-list shadow-sm border-0 DivOscuro2">
                    <h5>Estado del Cierre Diario: 
                         <?php 
                        if (!$cierre) {
                        echo'<span class="text-danger"> No activo </span>';
                        }else{
                           echo'<span class="text-success"> Activo Nro. '.$cierre["id_cierre"].' </span>';
                        }
                        ?>
                    </h5>
                    </div>
                     <div class="transaction-list shadow-sm border-0 DivOscuro2">
                         <h5>Tasa de Cambio (Guardada en el Sistema)
                            <span class="text-success" id="tasacofing">  </span>
                         </h5>
                    </div>
            </div>
            <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>


      <!-- Modal HISTORIAL -->
    <div class="modal fade" id="modalhistorial"  data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content modal-producto">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"> <i class="fa-solid fa-clock-rotate-left me-2"></i> Historial de Venta de Hoy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                

                             <div class="transaction-list shadow-sm border-0 DivOscuro2">

      <div class="table-responsive text-dark TextOscuro">
        <table id="myTable" class="table table-hover" style="width:100%">
          <thead class="table-color">
            <tr>
              <th>Nro y Fecha</th>
              <th>Producto</th>
         
              <th>Cant. / Modo</th>
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
                $ventas[$id_venta]['detalles'][] = [
                    'nombre' => $dato['nombre_producto'],
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
                            <?php echo date("d/m/Y", strtotime($info['fecha'])); ?>
                            </strong>
                          </div>
                      </div>
                  </div>
              </td> 

               <td class="td-oscuro">
                    <ul class="mb-0 list-unstyled">
                        <?php foreach ($info['detalles'] as $detalle): ?>
                            <li>
                                <i class="fa-solid fa-boxes-stacked me-2 text-primary"></i>
                                <?php echo $detalle['nombre'].' - '.$detalle['marca'].' - '.$detalle['presentacion']; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </td>

               <td class="td-oscuro">
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($info['detalles'] as $detalle): ?>
                            <li>
                                <?php echo $detalle['cantidad'] . ' - ' . strtoupper($detalle['maode']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </td>

                  <td class="td-oscuro">
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($info['detalles'] as $detalle): ?>
                                <li><?php echo number_format($detalle['precio_unitario'], 2, ',', '.') . ' $'; ?></li>
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




            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal CERRAR SESSION -->
    <div class="modal fade" id="modalcerrar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

        <div class="modal-body text-center p-4">

            <i class="fa-solid fa-circle-question text-dark mb-4"
              style="font-size: 7rem;"></i>

            <h4 class="fw-bold mb-4">
                ¿Confirmas que deseas cerrar sesión?
            </h4>

            <div class="d-flex justify-content-center gap-3">

                <form action="?pagina=login" method="POST" autocomplete="off">
                    <button type="submit" class="btn btn-success btn-lg px-4" name="cerrar">
                        Sí
                    </button>
                </form>

                <button type="button" class="btn btn-outline-secondary btn-lg px-4" data-bs-dismiss="modal">
                    No
                </button>
            </div>
        </div>
      
    </div>
  </div>
</div>







<script>
    

  

</script>








</body>
</html>