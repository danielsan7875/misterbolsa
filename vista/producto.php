<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head2.php'; ?>
    <title>Producto | Mister Bolsa C.A</title>
    <!--
    <link rel="stylesheet" href="assets/css/producto.css" />
     <link rel="stylesheet" href="assets/css/for.css" />

     
-->

<style>
    /* Estilo para productos desactivados (Estatus 2) */
    .fila-desactivada {
        background-color: #f2f2f2 !important; /* Gris muy claro */
        color: #888888; /* Texto gris oscuro/opaco */
    }

    /* Opcional: si quieres que se vea un poco transparente */
    .fila-desactivada td {
        border-color: #ddd;
    }
</style>
</head>

<body>
  <?php require_once 'complementos/nav2.php'; ?>
    <?php require_once 'complementos/sidebar2.php'; ?>
      <?php foreach ($dolar as $dolar){  ?>
          <input type="hidden" id="dolar" value="<?php echo $dolar['precios']?>">
      <?php } ?>

    <div class="row">
      <div class="d-sm-flex align-items-center justify-content-between mb-5">
        <h1 class="h3 mb-0 TextOscuroTitel"> <i class="fa-solid fa-boxes-stacked  me-2" style="color: #E63946;"></i>
          Producto</h1>
           
          <!-- Button que abre el Modal N1 Registro -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                <i class="fas fa-file-medical me-2"></i>
                Nuevo Producto
            </button>
      </div>
    </div>

       

          
      <div class="transaction-list shadow-sm border-0 DivOscuro1">

            <div class="row align-items-center g-2 my-3 mt-0">

              <!-- Columna 1: Alerta (70%) -->
              <div class="col-12 col-md-8">
                 <?php if (empty($StockMenor)) : ?>

                <!-- ALERTA VERDE SIN BOTÓN -->
                <div class="alert alert-success m-0" role="alert">
                   <i class="fa-solid fa-circle-check me-2"></i> Niveles de inventario correctos: Todos los productos cuentan con stock suficiente
                </div>

              <?php else : ?>

              <!-- ALERTA ROJA CON BOTÓN DENTRO -->
              <div class="alert alert-warning d-flex justify-content-between align-items-center m-0" role="alert">
                  <span> <i class="fa-solid fa-triangle-exclamation me-2"></i> <strong>Revisión necesaria: </strong> Hay productos con bajo o mínimo stock, </span>
                    <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#Stock">
                        Ver productos
                    </button>
              </div>
               <?php endif; ?>     
              </div>

              <!-- Columna 2: Botón (30%) -->
             <div class="col-12 col-md-4">
                  <div class="row g-2"> <div class="col-6">
                          <form action="?pagina=producto" method="POST" class="h-100">
                              <button type="submit" class="btn btn-primary w-100 h-100" name="pdfcompleto">
                                  Descargar PDF
                              </button>
                          </form>
                      </div>

                      <div class="col-6">
                          <button class="btn btn-secondary w-100 h-100" disabled>
                              Personalizado
                          </button>
                      </div>

                  </div>
              </div>


          </div>

      </div>

    <div class="transaction-list shadow-sm-0 DivOscuro1">

      <div class="table-responsive text-dark TextOscuro" >

        <table id="myTable" class="table table-hover" style="width:100%">
            <thead>
                <tr class="table-color">
              
                    <th>Nombre</th>
                    <th>Marca y Presentación</th>
                    <th>Precio DETAL</th>
                    <th>Precio MAYOR</th>
                    <th>Stock</th>
                    <th>Detalles</th>
                    <?php if( $tipo_usuario == 1) { ?> 
                    <th>Acciones</th>
                    <?php } ?>
                </tr>
            </thead>
            
            <tbody>
                <?php
                $estatus_texto = array( 
                  1 => 'Activo',
                  2 => 'No Activo',
                );

                $estatus_classes = array(
                  1 => 'activo', 
                  2 => 'no-activo', 
                );

                foreach ($registro as $dato){
                ?>
                <?php 
                    // Determinamos si el estatus es 2 para aplicar el estilo
                    $estilo_fila = ($dato['estatus'] == 2) ? 'fila-desactivada' : '';
                ?>
                  <tr class="<?php echo $estilo_fila; ?>" data-precio-unitario="<?php echo $dato['precio_unitario']; ?>" data-precio-mayor="<?php echo $dato['precio_mayor']; ?>" data-stock-minimo="<?php echo $dato['stock_minimo']; ?>">
                      
                        <td class="td-oscuro">
                          <div class="d-flex align-items-center">
                              <div class="me-3">
                                 <?php if($dato['estatus'] == 2) {
                                   echo '<i class="fa-solid fa-boxes-stacked fa-2x" style="color: #707070;"></i>';
                                 } else{
                                   echo '<i class="fa-solid fa-boxes-stacked fa-2x" style="color: #E63946;"></i>';
                                 }
                                  ?>
                                  
                              </div>
                              <div>
                                  <div style="font-size: 0.9 rem;">
                                      <?php echo $dato['nombre']; ?>
                                  </div>
                                  <div class="text-muted small">
                                      # <?php echo $dato['id_producto']; ?> 
                                      <?php if($dato['estatus'] == 2) { ?>
                                         <span class="badge bg-warning text-dark"> Producto desactivado </span>
                                      <?php } ?> 
                                      
                                  </div>
                              </div>
                          </div>
                      </td> 

                        <td class="td-oscuro">    
                          <div class="mt-0">Marca: <?php echo $dato['nombre_marca']; ?> </div>
                          <div class="mt-0">
                             
                                 <?php if($dato['estatus'] == 2) {
                                   echo '<i class="fa-solid fa-shapes" style="color: #707070;"></i>';
                                 } else{
                                   echo ' <i class="fa-solid fa-shapes" style="color: #E63946;"></i> ';
                                 }
                                  ?>
                            <?php echo $dato['nombre_presentacion']; ?> </div>
                        </td>
                          <td class="fw-bold td-oscuro">
                            <div>
                                  <div style="font-size: 0.9 rem;" id="precioBs">
                                     
                                  </div>
                                  <div class="text-muted small">
                                    $ <?php echo $dato['precio_unitario']; ?>
                                  </div>
                              </div> 
                          </td>

                           <td class="td-oscuro"> 
                             <div>
                                  <div style="font-size: 0.9 rem;" id="precioBss">
                                     
                                  </div>
                                  <div class="text-muted small">
                                    $ <?php echo $dato['precio_mayor']; ?>
                                  </div>
                              </div>
                            </td>

                            <td class="text-center td-oscuro">
                               <?php
                                      if ($dato['stock'] == 0) {
                                        echo '<span class="badge bg-danger fs-6">'.$dato['stock'].'</span>';
                                      } else if($dato['stock'] >= $dato['stock_minimo']){
                                        echo '<span class="badge bg-primary fs-6">'.$dato['stock'].'</span>';
                                      } else{
                                         echo '<span class="badge bg-warning fs-6 text-dark">'.$dato['stock'].'</span>';
                                      }
                                    ?></td>

                              <td class="text-center td-oscuro">
                                <!-- Botón de detalle -->
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detalleModal-<?php echo $dato['id_producto']; ?>">
                                  <i class="fas fa-info-circle me-2"></i> Detalle
                                </button>
                              </td>

                              <?php if ($tipo_usuario == 1) { ?> 
                                <td class="td-oscuro">  <!-- ACCION -->
                                  <form method="POST" action="?pagina=producto">
                                    <div class="dropdown">
                                      <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Opciones
                                      </button>

                                      <?php 
                                            // Si el estatus es 2 (desactivado), el botón debe ser para "Activar"
                                            $esDesactivo = ($dato['estatus'] == 2);
                                            $claseBtn = $esDesactivo ? 'btn-success' : 'btn-warning';
                                            $icono = $esDesactivo ? 'fa-check' : 'fa-triangle-exclamation';
                                        ?>

                                        <button type="button" 
                                                onclick="gestionarEstatus(this)" 
                                                class="btn <?php echo $claseBtn; ?>" 
                                                value="<?php echo $dato['id_producto']; ?>"
                                                data-estatus="<?php echo $dato['estatus']; ?>">
                                            <i class="fa-solid <?php echo $icono; ?>"></i>
                                        </button>

                                      <ul class="dropdown-menu DivOscuro2">
                                        <li>
                                          <button name="modificar" class="dropdown-item modificar text-dark texto-secundario" value="<?php echo $dato['id_producto']; ?>">
                                            <i class="fas fa-pencil-alt me-2 text-primary"></i> Editar
                                          </button>
                                        </li>

                                        <li>
                                          <button name="eliminar" class="dropdown-item eliminar text-dark texto-secundario" value="<?php echo $dato['id_producto']; ?>">
                                            <i class="fas fa-trash-alt me-2 text-danger"></i> Eliminar
                                          </button>
                                        </li>

                                      </ul>
                                    </div>

                                    <!-- Hidden inputs outside the dropdown -->
                                    <input type="hidden" name="eliminar" value="<?php echo $dato['id_producto']; ?>">
                                    <input type="hidden" name="stock" value="<?php echo $dato['stock']; ?>">                                     
                                  </form>
                                 
                                </td>
                              <?php } ?>
                  </tr>

                    <!-- Modal de detalle -->
                    <div class="modal fade" id="detalleModal-<?php echo $dato['id_producto']; ?>" tabindex="-1" aria-labelledby="detalleModalLabel-<?php echo $dato['id_producto']; ?>" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content modal-producto">
                          <div class="modal-header">
                            <h5 class="modal-title text-white" id="detalleModalLabel-<?php echo $dato['id_producto']; ?>">Detalles del producto</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                          </div>
                          <div class="modal-body div-oscuro-2">

                            <!-- Primera Card: Nombre, Marca, Presentación -->
                          
                              <div class="seccion-formulario">
                              <h6 ><i class="fas fa-boxes"></i> Nombre del Producto </h6>
                                <div class="row">
                                  <div class="col"> <h5 class="card-title text-dark TextOscuro"><strong><?php echo $dato['nombre']; ?></strong></h5> </div>
                                  <div class="col ">
                                    <h5 class="mt-0 ">Marca: <?php echo $dato['nombre_marca']; ?></h5>
                                    <h5 class="mt-0 ">Presentación: <?php echo $dato['nombre_presentacion']; ?></h5>
                                </div>
                        
                              </div>
                              </div>
                            

                            <!-- Segunda Card: Precio Detal -->
                          
                          <div class="seccion-formulario">
                              <h6><i class="fas fa-boxes"></i> Precio al Detal </h6>
                                <div class="row">
                                  <div class="col"><h5 class=""><strong>$:</strong> <?php echo $dato['precio_unitario']; ?></h5></div>
                                  <div class="col"><h5 class=""><strong>Bs:</strong> <span class="precio-bs-detal" data-precio="<?php echo $dato['precio_unitario']; ?>"></h5></span></div>
                                </div>
                              </div>
                      

                            <!-- Tercera Card: Precio Mayor -->
                          <div class="seccion-formulario">
                              <h6><i class="fas fa-boxes"></i> Precio al Mayor </h6>
                                <div class="row">
                                  <div class="col"><h5 class=""><strong>$:</strong> <?php echo $dato['precio_mayor']; ?></h5></div>
                                  <div class="col"><h5 class=""><strong>Bs:</strong> <span class="precio-bs-mayor" data-precio="<?php echo $dato['precio_mayor']; ?>"></span></h5></div>
                                  <div class="col"><h5 class=""><strong>Cantidad Mayor:</strong> <?php echo $dato['cantidad_mayor']; ?></h5></div>
                                </div>
                              </div>
                          

                            <!-- Cuarta Card: Stock -->
                            <div class="seccion-formulario">
                                  <h6><i class="fas fa-boxes"></i> Otros datos </h6>
                                <div class="row">
                                  <div class="col"> <h5 class=""><strong>Stock mínimo:</strong> <?php echo $dato['stock_minimo']; ?> </h5></div>
                                  <div class="col"> <h5 class=""><strong>Stock Disponible:</strong> <?php echo $dato['stock']; ?></h5> </div>
                                  <div class="col"> <h5 class="">
                                    <strong>Estado:</strong>
                                    <?php
                                      if ($dato['stock'] >= $dato['stock_minimo']) {
                                        echo '<span class="badge bg-success">Perfecto</span>';
                                      } else {
                                        echo '<span class="badge bg-danger">Bajo stock</span>';
                                      }
                                    ?>
                                    </h5>
                                  </div>
                                </div>
                              </div>
                      

                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                          </div>
                        </div>
                      </div>
                    </div>

              <?php  } ?>
          </tbody>

        </table>
       
      </div>

        <?php if ($total_registros > $_SESSION['limite_producto']): ?>
        <form method="POST" action="?pagina=producto">
          <div class="text-center ">
            <button type="submit" name="ver_mas" class="btn btn-primary w-50 mt-3">
                <i class="fas fa-plus-circle"></i> Ver más registros (+100)
            </button>
            </div>
        </form>
        <?php endif; ?>

    </div> <!-- DIV 2 -->
  

    <!-- |||||||||||||||||||||||||   MODAL DE REGISTRO PRODUCTO   |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-producto div-oscuro-2">

    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel"  style="color:rgb(255, 255, 255);">
       <i class="fas fa-boxes" style="color:rgb(255, 255, 255);"></i> Registro de Producto Nuevo
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body div-oscuro-2">
      <form action="?pagina=producto" id="u" autocomplete="off"> <!--comienzo Formulario-->
        <div class="seccion-formulario">
        <div class="row ">
          <h6><i class="fas fa-boxes"></i> Datos del Producto Nuevo </h6>
          <div class="col-sm">
          <label class="form-label text-dark">Nombre</label>
            <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"> <i class="fa-solid fa-bag-shopping" style="color:#FF3C37;"></i> </span>
            <input type="text" class="form-control text-dark" name="nombre" id="nombre" placeholder="Ejemplo: Bister Bolsa "  required>     
          </div>
          <span id="textonombre"  class="text-error mt-0"></span>
          </div>
        </div>

        <div class="row ">
          <div class="col-sm">
          <label class="form-label text-dark">Marca</label>
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-tag " style="color:#FF3C37;"></i></span>
          <select class="form-select text-dark" aria-label="Default select example"  name="marca" id="marcaselect" required>
              <option value="">Seleccione una Marca:</option>
                 <?php foreach($marca as $marca) {?>
                     <option value="<?php echo $marca['id_marca'];?>"> <?php echo $marca['nombre'];?> </option>
               <?php } ?>
           </select> 
           
           </div>
           <span id="textomarca"  class="text-error mt-0"></span> 
          </div>

          <div class="col-sm">
          <label class="form-label text-dark">Presentacion</label>
          <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-shapes" style="color:#FF3C37;"></i></span>
          <select class="form-select text-dark" aria-label="Default select example" name="presentacion" id="presenselect" required>
              <option value="">Seleccione una Presentacion:</option>
                 <?php foreach($presentacion as $presentacion) {?>
                     <option value="<?php echo $presentacion['id_presentacion'];?>"> <?php echo $presentacion['nombre'];?> </option>
               <?php } ?>
           </select> 
       
           </div>  
               <span id="textopresentacion"  class="text-error mt-0"></span> 
          </div>
               </div>                      
        </div>
        
        <div class="seccion-formulario">
        <div class="row ">
          <h6><i class="fas fa-boxes"></i> Precio del Producto en $ </h6>
          <div class="col-sm">
          <label class="form-label text-dark">Precio Al Detal ($)</label>
            <div class="input-group mb-3">
               <span class="input-group-text " id="basic-addon1"><i class="fa-solid fa-dollar-sign" style="color:#FF3C37;"></i></span>
               <input type="text" class="form-control text-dark " name="precio" id="precio" placeholder="Ejemplo: 5.00 "  required>  
            </div>
            <span id="textoprecio"  class="text-error mt-0"></span> 
          </div>

            <div class="col-sm">
          <label class="form-label text-dark">Precio al Mayor($)</label>
            <div class="input-group mb-3">
               <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-dollar-sign" style="color:#FF3C37;"></i></span>
               <input type="text" class="form-control text-dark" name="preciomayor" id="preciomayor" placeholder="Ejemplo: 4.00 " required>  
            </div>
            <span id="textopreciomayor"  class="text-error mt-0"></span> 
          </div>
        </div>
         </div>

            <div class="seccion-formulario">
        <div class="row">
        <h6><i class="fas fa-boxes"></i> Otros datos </h6>
           <div class="col-sm">
              <label class="form-label text-dark">Stock Minimo</label>
              <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-box" style="color:#FF3C37;"></i></span>
                  <input type="text" class="form-control text-dark"  name="stock_min" id="stock" placeholder="Ejemplo: 2 "  required>  
              </div>
             <span id="textostock"  class="text-error mt-0"></span>
          </div>

          <div class="col-sm">
              <label class="form-label text-dark">Cantidad Al Mayor</label>
                <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-box" style="color:#FF3C37;"></i></span>
                    <input type="text" class="form-control text-dark"  name="cantidadmayor" id="cantidadmayor" placeholder="Ejemplo: 2 "  required>  
                </div>
            <span id="textocantidadmayor"  class="text-error mt-0"></span> 
          </div>

      </div> 
      </div>

      <div class="d-flex justify-content-center gap-3 my-4">
              <button type="reset" class="btn btn-lg btn-limpiar" name="limpiar"><i class="fa-solid fa-repeat me-2"></i>Limpiar</button>
              <button type="button" class="btn btn-lg btn-guardar" id="registrar">Registrar Producto</button>
      </div>
     
    
   </div>
   </form> <!--fin del formulario-->
      </div>
    </div>
  </div>




  <div class="modal fade" id="Stock" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-producto div-oscuro-2">

            <div class="modal-header encabezado-modal">
                <h1 class="modal-title fs-5" style="color:#ffffff;">
                    <i class="fas fa-boxes" style="color:#ffffff;"></i> Productos con Stock Bajo
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body div-oscuro-2">

                <table class="table table-hover w-100">
                    <thead class="table-color">
                        <tr>
                            <th>Producto</th>
                            <th>Marca y Presentación</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($StockMenor as $producto) : ?>
                            <tr>
                                <td class="td-oscuro">
                                   <div class="mt-0"> <?= $producto['nombre'] ?> </div>
                                  <div class="mt-0 text-muted"> Cod. <?= $producto['id_producto'] ?> </div>
                                </td>
                                <td class="td-oscuro">
                                  <div class="mt-0">Marca: <?php echo $producto['nombre_marca']; ?> </div>
                                  <div class="mt-0">  <i class="fa-solid fa-shapes" style="color: #E63946;"></i>
                                   <?php echo $producto['nombre_presentacion']; ?> </div>
                                </td>
                                <td class="td-oscuro"> <i class="fa-solid fa-triangle-exclamation me-2 text-warning"></i> <?= $producto['stock'] ?></td>
                           
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




     
    
    

              
 
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  
  <script src="assets/js/producto.js"></script>
  <?php require_once 'complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||--> 
  
  

</body>
</html>
