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
                       
                    </div>

                    <!-- Grid de Cards -->
                    <div class="row row-cols-1 row-cols-md-3 g-4"  id="contenedorProductos"> 
                        <?php if (!empty($producto)): ?>
                            <?php foreach ($producto as $productos): ?>
                                <div class="col producto-item">
                                    <div class="product-card">
                                        <div class="product-img">
                                            <i class="fa-solid fa-boxes-stacked" style="font-size: 28px;"></i>
                                        </div>
                                        <div class="p-3 d-flex flex-column flex-grow-1">
                                            <h6 class="fw-semibold mb-1 text-truncate nombre-producto">
                                                <?php echo htmlspecialchars($productos['nombre']).' | '.htmlspecialchars($productos['nombre_marca']); ?>
                                            </h6>
                                            <span class="text-muted mb-1">Presentacion: <?php echo htmlspecialchars($productos['nombre_presentacion']) ?></span>

                                            
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                   <span class="stock-badge <?php echo ($productos['stock'] <= 0) ? 'text-danger fw-bold' : ''; ?>">
                                                        Stock: <span class="num-stock"><?php echo $productos['stock']; ?></span>
                                                    </span>
                                                    <?php if($productos['cantidad_mayor'] > 0): ?>
                                                        <small class="text-muted">Mayor: <?php echo $productos['cantidad_mayor']; ?>u.</small>
                                                    <?php endif; ?>
                                            </div>
                                            
                                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                               <span class="fw-bold text-primary fs-5">
                                                    $<?php echo number_format($productos['precio_unitario'], 2); ?>
                                                </span>
                                                <?php if($productos['precio_mayor'] > 0): ?>
                                                    <small class="text-secundary" style="font-size: 1rem;">Mayor: $<?php echo number_format($productos['precio_mayor'], 2); ?></small>
                                                <?php endif; ?>
                                                
                                                <button class="btn btn-sm btn-outline-primary p-2 btn-agregar" 
                                                        data-id="<?php echo $productos['id_producto']; ?>"
                                                        data-nombre="<?php echo htmlspecialchars($productos['nombre']); ?>"
                                                        data-precio-detal="<?php echo $productos['precio_unitario']; ?>"
                                                        data-precio-mayor="<?php echo $productos['precio_mayor']; ?>"
                                                        data-cant-mayor="<?php echo $productos['cantidad_mayor']; ?>"
                                                        data-stock="<?php echo $productos['stock']; ?>">
                                                    Añadir <i class="fa-solid fa-plus" style="width: 16px; height: 16px;"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                             <!-- Mensaje de "No encontrado" oculto por defecto -->
                            <div id="mensajeVacio" class="col-12 w-100 d-none">
                                <div class="alert alert-warning text-center">
                                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                                    No se encontraron productos que coincidan con tu búsqueda.
                                </div>
                            </div>

                        <?php else: ?>
                            <div class="col" style="width:100%;">
                                 <div class="alert alert-dark" role="alert" style="width:100%;">
                                    <h2>No se encontraron productos registrados.</h2> 
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- 3. Columna Derecha: Detalle de Venta -->
              

            <div class="col-lg-4 h-100 pb-3">
              
               

                <div class="cart-column">
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
                    <div class="payment-section">
                        <!-- Métodos de Pago -->
                        <label class="form-label text-muted small fw-bold text-uppercase mb-2">Método de Pago</label>
                        <div class="row g-2 mb-4">
                          <?php if (!empty($metodo)): ?>
                            <?php foreach ($metodo as $metodo): ?>
                                <div class="col-3">
                                   <div class="payment-method-card" data-id="<?php echo $metodo['id_metodopago']; ?>">
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
                                        <div class="small fw-medium">
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
                          <div class="row g-2 mb-4">
                            <Button type="button" class="btn btn-primary">Pagos Multiples</Button>
                          </div>

                          <div class="mt-3 d-none" id="contenedorReferencia">
                                <label class="form-label">Referencia</label>
                                <input type="text" class="form-control" id="referencia_visible"
                                    placeholder="Ingrese referencia (4 a 6 dígitos)">
                            </div>


                        <!-- Totales -->
                        <div class="border-top border-dashed mb-3 pt-3">
                            <div class="total-row">
                                <span>Tasa de Cambio</span>
                                    <?php foreach ($dolar as $dolar){  ?>
                                        <span class="fw-medium text-dark " id="tasade">Bs <?php echo $dolar['precios']?></span>
                                         <input type="hidden" id="tasadecambio"  value="<?php echo $dolar['precios']?>">
                                    <?php } ?> 
                                   
                            </div>
                            <div class="total-row">
                                <span class="text-muted">Total en Dólares</span>
                                <strong><span id="total-dolares" class="fw-bold text-dark fs-5">$0.00</span></strong>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fs-5 fw-bold text-dark">Total a Pagar</span>
                            
                                <strong><span class="grand-total text-primary">Bs. 0.00</span></strong>
                            </div>
                        </div>
                     
                         
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
    <script src="assets/js/datetable/data.js"></script>

    <script src="assets/js/sweetalert2.js"></script>
    
    <script src="assets/librerias/bootstrap5/js/bootstrap.min.js"></script>
    
    <script src="assets/librerias/bootstrap5/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/SystemVentaPos.js"></script>

    <!-- |||||||||||||||| MODALES ||||||||||||||||| -->

    <!-- Modal CONFIGURACION -->
    <div class="modal fade" id="modalconfiguracion" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title " id="staticBackdropLabel">Configuración</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                        
                <div class="transaction-list shadow-sm border-0 mt-3">
                    <h5>Modo escuro</h5>
                </div>
                  <div class="transaction-list shadow-sm border-0">
                    <h5>Estado del Cierre Diario: 
                         <?php 
                        if (!$cierre) {
                        echo'<span class="text-danger"> No activo </span>';
                        }else{
                           echo'<span class="text-success"> Activo </span>';
                        }
                        ?>
                    </h5>
                    </div>
                     <div class="transaction-list shadow-sm border-0">
                         <h5>Tasa de Cambio (Guardada en el Sistema)
                            <span class="text-success" id="tasacofing">  </span>
                         </h5>
                    </div>
            </div>
            <div class="modal-footer">
                
            </div>
            </div>
        </div>
    </div>


      <!-- Modal HISTORIAL -->
    <div class="modal fade" id="modalhistorial" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Historial de Venta de Hoy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                
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

</body>
</html>