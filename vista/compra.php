<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head2.php'; ?>
    <title>Compra | Mister Bolsa C.A</title>
 

</head>

<body>
    <?php require_once 'complementos/nav2.php'; ?>
      <?php require_once 'complementos/sidebar2.php'; ?>
 
      <div class="row">
          <div class="d-sm-flex align-items-center justify-content-between mb-5">
            <h1 class="h3 mb-0 TextOscuroTitel"> <i class="fa-solid fa-receipt me-2" style="color: #E63946;"></i>
              Compra</h1>
                
            <!-- Button que abre el Modal N1 Registro -->
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
                  <i class="fas fa-file-medical me-2"></i>
                  Registrar Compra
            </button>
          </div>
      </div>

          <div class="transaction-list shadow-sm border-0 DivOscuro1">
            <div class="col-12">
              <p class="text-muted"><i class="fa-solid fa-calendar-days me-2"></i>Consulta de Compras por Fecha</p>
            </div>

          <form method="POST"  action="?pagina=compra" class="row g-2 align-items-end mt-2">

        <!-- Checkbox 15 dias -->
        <div class="col-12 col-md-2">
            <label class="form-label invisible">Filtro</label>

            <input type="checkbox" id="filtro15" name="filtro" value="15" hidden>

            <label for="filtro15" class="filter-card">
                <span class="check-box"></span>
                <span class="filter-text">Últimos 15 días</span>
            </label>
        </div>

         <!-- Checkbox MES -->
        <div class="col-12 col-md-2">
            <label class="form-label invisible">Filtro</label>

            <input type="checkbox" id="filtroMes" name="filtro" value="mes" hidden>

            <label for="filtroMes" class="filter-card">
                <span class="check-box"></span>
                <span class="filter-text">Este mes</span>
            </label>
        </div>

        <!-- Desde -->
        <div class="col-12 col-md-3">
            <label class="form-label"><i class="fa-solid fa-calendar-days me-2"></i>Desde</label>
            <input type="date" id="fecha_desde" name="desde" class="form-control">
        </div>

        <!-- Hasta -->
        <div class="col-12 col-md-3">
            <label class="form-label"><i class="fa-solid fa-calendar-days me-2"></i>Hasta</label>
            <input type="date" id="fecha_hasta" name="hasta" class="form-control">
        </div>

        <!-- Botón -->
        <div class="col-12 col-md-2 d-grid ">
            <button type="submit" name="modificar" value="buscar" class="btn btn-primary" style=" padding: 12px 15px;">
              <i class="fa-solid fa-magnifying-glass me-2"></i>  Buscar
            </button>
        </div>

    </form>


      </div>

    <p class="text-muted">Periodo actual de registros: Últimos 7 días (Hoy y 6 días anteriores)</p>

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

                      foreach ($registro as $dato) {
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
                          <ul class="mb-0 list-unstyled">
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
                          <ul class="mb-0 list-unstyled">
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
                                    <div class="modal-header ">
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
        

<!-- |||||||||||||||||||||||||   Modal   |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-producto">
    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel" style="color:rgb(255, 255, 255);">
        Registro de Compra
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:rgb(255, 255, 255);"></button>
      </div>

      <div class="modal-body div-oscuro-2">
      <form action="?pagina=compra" id="u" autocomplete="off"> <!--comienzo Formulario-->
      
      <div class="seccion-formulario">
        <div class="row ">
          <h6>Seleccionar un producto y la cantidad</h6>
          <div class="col-sm-10">
          <label class="form-label text-dark">Producto</label>
          <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-boxes-stacked" style="color:#007bff;"></i></span>
          <select class="form-select text-dark select-formulario" aria-label="Default select example" name="id_producto" id="idproducto" required >
              <option value="">Seleccione un Producto:</option>
                <?php foreach($producto as $producto) {?>
                <option value="<?php echo $producto['id_producto'];?>"> <?php echo $producto['nombre']." - ".$producto['nombre_marca']." - ".$producto['nombre_presentacion']?> </option>
               <?php } ?>
            </select>
           </div>
           <span class="text-error mt-0" id="textoproducto"></span>
          </div>
          <div class="col-sm-2">
                <label class="form-label text-dark">Cantidad</label> <br>
                <input type="text" class="form-control text-dark mb-2" id="cantidadpro">
                <span class="text-error mt-0" id="textocantidad"></span>
            </div>
          </div>

          <div class="row">
                    <div class="col">
                      <button type="button" class="g btn btn-primary w-100" id="btn-agregar-producto"> + AGREGAR</button>
                    </div>
                  </div>
        </div>

                  

        <div class="seccion-formulario">
              <div class="row ">
              <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0">Lista de Producto a Comprar</h6>
        <button type="button" class="btn btn-secondary btn-sm" id="btn-limpiar-lista">
          <i class="fa-solid fa-trash-can me-1"></i> Limpiar Lista
        </button>
      </div>

         <table id="tabla-productos" class="table table-hover" style="width:100%">
          <thead class="table-color">
              <th>Producto </th>
              <th>Cantidad </th>
              <th>Accion </th>
          </thead>
          <tbody id="tbody-productos" class="text-dark">

          </tbody>
         </table>
              
        
        </div>
   </div>
     
 <input type="hidden" name="fecha" id="fecha">   
<hr>

  <div class="d-flex justify-content-center gap-3 my-4">
             <button type="button" class="btn btn-lg btn-limpiar" id="limpiardatos" name="limpiar">Limpiar</button>
             <button type="button" class="btn btn-lg btn-guardar" id="registrar"><i class="fa-solid fa-receipt me-2"></i> Registrar</button>
      </div>

        </form> <!--fin del formulario-->
      </div>
    
      
    </div>
  </div>
</div>           
 
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/moment.js"></script>
  <script src="assets/js/compra.js"></script>
  <script src="assets/js/ConsultaFecha.js"></script>
  <?php require_once 'complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->
 



 
</body>
</html>