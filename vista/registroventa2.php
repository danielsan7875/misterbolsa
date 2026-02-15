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
                    <div class="input-group mb-4 sticky-top pt-1 TA6" style="z-index: 10;">
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
              

                        
            <div class="col-lg-4 h-100 pb-7 DivOscuro2">
              
               

                <div class="cart-column DivOscuro2">
                    <form action="?pagina=registroventa" method="POST" autocomplete="off" id="SystemPos">
                  <div class="cart-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fa-solid fa-cart-shopping me-2"></i>Carrito Actual
                             <span id="cart-count-badge" class="badge bg-primary rounded-pill ms-2 fs-6">0</span> </h5>
                                                        

                        <button type="button" id="vaciar-carrito" class="btn btn-sm btn-outline-danger border-0 TA10">
                            <i class="fa-solid fa-trash"></i> Vaciar
                        </button>
                    </div>
                  
                    <!-- Lista de Productos Añadidos (Dinámica) -->
           
                    <div id="lista-carrito" class="cart-items TA9">
                         
                        <!-- Los productos se inyectan aquí -->
                    </div>

                    <!-- Sección Inferior (Método de Pago, Totales, Botón) -->
                    <div class="payment-section DivOscuro2">
                        <!-- Métodos de Pago -->
                       

                        <!-- Totales -->
                        <div class="border-dashed mb-3 pt-3">
                           
                            <?php $precio = $dolar[0]['precios'];  ?>
                            <div class="total-row">
                                <span class="text-muted">Total en Dólares</span>
                                        <span class="fw-medium text-muted TA11" id="tasade"> Tasa de Cambio: Bs <?= $precio ?></span>

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

                        <div id="MetodoPagoDiv">

                        </div>
                    

                        <?php if (is_array($cierre)): ?>
                            <input type="hidden" name="id_cierre" value="<?php echo $cierre['id_cierre']; ?>">
                            <input type="hidden" name="hora" id="hora">
                        <?php endif; ?>

                        <!-- Botón Continuar -->
                        
                        <?php 
                            if (!$cierre) {
                            echo'
                            <button type="button" class="btn-process shadow-sm d-flex justify-content-between align-items-center TA12" disabled>
                                <span>Cierre no activo, no puede hacer venta</span>
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                            ';
                            }else{
                            echo'
                            <button type="button" class="btn-process shadow-sm d-flex justify-content-between align-items-center TA12" data-bs-toggle="modal" data-bs-target="#ModalPago">
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

   
    



    <?php require_once 'complementos/footerpos.php'; ?>   
  
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
                      element: '.TA1',  popover: { title: 'Informacion Personal', 
                          description: 'Actualiza tu nombre, apellido y nombre de usuario para que aparezcan correctamente en tus registros, luego si modificaste un datos haz click en Guarda cambios', side: "top" 
                      } 
                  },
                  { 
                      element: '.TA2', popover: { title: 'cambiar la clave', 
                          description: 'Actualiza tu nombre, apellido y nombre de usuario para que aparezcan correctamente en tus registros. coloca tu clave actual y posteriormente coloca la contraseña nueva de acceso', side: "top" 
                      } 
                  },
                  { 
                      // Usamos el punto (.) para referirnos a la clase
                      element: '.TA3',  popover: { title: 'Informacion Personal', 
                          description: 'Actualiza tu nombre, apellido y nombre de usuario para que aparezcan correctamente en tus registros, luego si modificaste un datos haz click en Guarda cambios', side: "top" 
                      } 
                  },
                  { 
                      element: '.TA4', popover: { title: 'cambiar la clave', 
                          description: 'Actualiza tu nombre, apellido y nombre de usuario para que aparezcan correctamente en tus registros. coloca tu clave actual y posteriormente coloca la contraseña nueva de acceso', side: "top" 
                      } 
                  },
                  { 
                      // Usamos el punto (.) para referirnos a la clase
                      element: '.TA5',  popover: { title: 'Informacion Personal', 
                          description: 'Actualiza tu nombre, apellido y nombre de usuario para que aparezcan correctamente en tus registros, luego si modificaste un datos haz click en Guarda cambios', side: "top" 
                      } 
                  },
                  { 
                      element: '.TA6', popover: { title: 'cambiar la clave', 
                          description: 'Actualiza tu nombre, apellido y nombre de usuario para que aparezcan correctamente en tus registros. coloca tu clave actual y posteriormente coloca la contraseña nueva de acceso', side: "top" 
                      } 
                  },
                  { 
                      // Usamos el punto (.) para referirnos a la clase
                      element: '.TA7',  popover: { title: 'Informacion Personal', 
                          description: 'Actualiza tu nombre, apellido y nombre de usuario para que aparezcan correctamente en tus registros, luego si modificaste un datos haz click en Guarda cambios', side: "top" 
                      } 
                  },
                  { 
                      element: '.TA8', popover: { title: 'cambiar la clave', 
                          description: 'Actualiza tu nombre, apellido y nombre de usuario para que aparezcan correctamente en tus registros. coloca tu clave actual y posteriormente coloca la contraseña nueva de acceso', side: "top" 
                      } 
                  },
                  { 
                      // Usamos el punto (.) para referirnos a la clase
                      element: '.TA9',  popover: { title: 'Informacion Personal', 
                          description: 'Actualiza tu nombre, apellido y nombre de usuario para que aparezcan correctamente en tus registros, luego si modificaste un datos haz click en Guarda cambios', side: "top" 
                      } 
                  },
                  { 
                      element: '.TA10', popover: { title: 'cambiar la clave', 
                          description: 'Actualiza tu nombre, apellido y nombre de usuario para que aparezcan correctamente en tus registros. coloca tu clave actual y posteriormente coloca la contraseña nueva de acceso', side: "top" 
                      } 
                  },
                  { 
                      // Usamos el punto (.) para referirnos a la clase
                      element: '.TA11',  popover: { title: 'Informacion Personal', 
                          description: 'Actualiza tu nombre, apellido y nombre de usuario para que aparezcan correctamente en tus registros, luego si modificaste un datos haz click en Guarda cambios', side: "top" 
                      } 
                  },
                  { 
                      element: '.TA12', popover: { title: 'cambiar la clave', 
                          description: 'Actualiza tu nombre, apellido y nombre de usuario para que aparezcan correctamente en tus registros. coloca tu clave actual y posteriormente coloca la contraseña nueva de acceso', side: "top" 
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