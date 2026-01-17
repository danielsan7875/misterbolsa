<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head2.php'; ?>
    <title>Perdidas | Mister Bolsa C.A</title>
    <link rel="stylesheet" href="assets/css/perdida.css" />
   
</head>

<body>
  
  <?php require_once 'complementos/nav2.php'; ?>
  <?php require_once 'complementos/sidebar2.php'; ?>
 
  <div class="row">
      <div class="d-sm-flex align-items-center justify-content-between mb-5">
          <h1 class="h3 mb-0 TextOscuroTitel"> <i class="fas fa-chart-line me-2" style="color: #E63946;"></i>
            Perdidas</h1>
              
          <!-- Button que abre el Modal N1 Registro -->
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
              <i class="fas fa-file-medical me-2"></i>
              Registrar Perdida
          </button>
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
            <th>FECHA</th>
            <th>PRODUCTO</th>
            <th>CANTIDAD</th>
            <th>NOTA</th>
          </tr>
        </thead>
        <tbody>
          <?php
            foreach ($registro as $dato){
            ?>
                    <tr>
                        <td class="td-oscuro">
                              <div class="d-flex align-items-center">
                                  <div class="me-3">
                                      <i class="fas fa-chart-line fa-2x" style="color: #E63946;"></i>
                                  </div>
                                  <div>
                                      <div>
                                        <?php echo date("d/m/Y", strtotime($dato['fecha']))?> 
                                      </div>
                                      <div class="text-muted small">
                                          # <?php echo $dato['id_perdida']?>
                                      </div>
                                  </div>
                              </div>
                          </td> 
                      <td class="td-oscuro align-middle"> <?php echo $dato['nombre']." - ".$dato['nombre_marca']." - ".$dato['nombre_presentacion'] ?> </td>
                      <td class="text-center td-oscuro align-middle"> <?php echo $dato['cantidad']?> </td>
                      <td class="td-oscuro align-middle"> <?php echo $dato['nota']?> </td>
                    </tr>
    <?php } ?>
        
        </tbody>
        </table>
    </div>

  </div>
    

<!-- |||||||||||||||||||||||||   MODAL DE REGISTRO  |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-producto">
    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel" style="color:rgb(255, 255, 255);">
        Registro de Perdida de Producto
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body div-oscuro-2">
      <form action="?pagina=perdidas" id="u" autocomplete="off"> <!--comienzo Formulario-->
        
      <div class="seccion-formulario">
        <div class="row ">
            <h6><i class="fas fa-chart-line "></i> Selecionar el producto </h6>
          <div class="col-sm">
             <label class="form-label text-dark">Producto</label>
                <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-boxes-stacked" style="color:#007bff;"></i></span>
                <select class="form-select text-dark" aria-label="Default select example" name="id_producto" id="idproducto"   width="10px" required>
                  <option value="">Seleccione un Producto:</option>
                       <?php foreach($producto as $producto) {?>
                  <option value="<?php echo $producto['id_producto'];?>"> <?php echo $producto['nombre']." - ".$producto['nombre_marca']." - ".$producto['nombre_presentacion']?> </option>
                     <?php } ?>
                </select> 
              </div>
                 <span id="textoproducto" class="text-error mt-0"></span>
          </div>
</div>
        </div>

         <div class="seccion-formulario">
        <div class="row ">
          <h6><i class="fas fa-chart-line "></i> Cantidad y Nota </h6>
          <div class="col-sm">
          <label class="form-label text-dark">cantidad</label>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-box" style="color:#007bff;"></i></span>
                <input type="text" class="form-control text-dark" name="cantidad" id="cantidad" placeholder="Ejemplo: 5 "  required>  
              </div>
              <span id="textocantidad" class="text-error mt-0"></span> 
          </div>

          <div class="col-sm">
          <label class="form-label text-dark">Nota</label>
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-comment" style="color:#007bff;"></i></span>
                <input type="text" class="form-control text-dark"  name="nota" id="nota" placeholder="Ejemplo: gasto "  required>  
          </div>
          <span id="textonota" class="text-error mt-0"></span>
          </div>
        </div>

        <input type="hidden" name="fecha" id="fechaperdida">  
    </div>
        <hr>

      <div class="d-flex justify-content-center gap-3 my-4">
           <button type="button" class="btn btn-lg btn-limpiar" id="limpiardatos" name="limpiar">Limpiar</button>
            <button type="button" class="btn btn-lg btn-guardar" id="registrar">Registrar</button>
      </div>
        </form> <!--fin del formulario-->
  
    
    </div>    
    </div>
  </div>
</div>           
  

  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/moment.js"></script>
  <script src="assets/js/perdidas.js"></script>
  <?php require_once 'complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||--> 


</body>
</html>