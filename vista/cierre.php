<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head2.php'; ?>
    <title>Cierre Diario | Mister Bolsa C.A</title>
    <!-- <link rel="stylesheet" href="assets/css/compra.css" />
<link rel="stylesheet" href="assets/css/for.css" /> -->

<style>
.g{
  width: 100%;
}

.boton-rojo-sombra {
  background-color: #d32f2f;
  color: #fff;
  border: none;
  padding: 12px 24px;
  border-radius: 6px;
  box-shadow: 0 4px 6px rgba(0,0,0,0.2);
  transition: background-color 0.3s ease, transform 0.2s ease;
}
.boton-rojo-sombra:hover {
  background-color: #b71c1c;
  transform: scale(1.05);
}

.boton-rojo-sombra:disabled {
  background-color: #f2a5a5;
  color: #fff;
  cursor: not-allowed;
  box-shadow: none;
  opacity: 0.7;
}
                  </style>
</head>

<body> 

  <?php require_once 'complementos/nav2.php'; ?>
  <?php require_once 'complementos/sidebar2.php'; ?>
 
  <div class="row">
    <div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900 texto-principal"> <i class="fa-solid fa-file-invoice-dollar me-2" style="color: #E63946;"></i>
        Cierre Diario</h1>
          <form action="?pagina=cierre" method="POST" autocomplete="off" id="form_cierre">   
          <!-- Button que abre el Modal N1 Registro -->

              <input type="hidden" name="fecha" id="cierrefecha" >
              
              <?php foreach ($dolar as $dolar){  ?>
                  <input type="hidden" name="tasa" id="tasass" value="<?php echo $dolar['precios']?>">
              <?php } ?>

              <button type="button" class="btn btn-success" name="iniciar" id="iniciar">
         
              <i class="fa-solid fa-circle-play me-2"></i>
                Comenzar
              </button>
          </form> 
      </div>
  </div>
    
   <div class="transaction-list shadow-sm border-0">
        <div class="row">
          <div class="col-sm-4">
            <div class="card text-center" style="width: 20rem;" id="card-cierre">
          <div class="card-body" id="card-body-cierre">
            <h5 class="card-title texto-secundario"><strong>Estatus del Cierre</strong></h5>
            <hr>
            <h5 id="estatus-cierre" class="text-dark texto-secundario mb-3">
              Se encuentra: <strong class="badge rounded-pill bg-secondary text-light">No activo</strong>
            </h5>
            <form action="?pagina=cierre" method="POST" id="cierreBD">
                <input type="hidden" id="fechaCierreBd" name="fecha_cierre" value="">
                <input type="hidden" id="id_cierre_hidden" name="id_cierre" value="">
            <button type="button" id="btn-cerrar-cierre" name="cierre" class="boton-rojo-sombra  g">Cerrar Cierre</button>
            </form>
          </div>
        </div>


          </div>
          <div class="col-sm-4">
              <div class="card text-center" style="width: 20rem;">
              
                <div class="card-body">
                  <h5 class="card-title texto-secundario"><strong>Cantidad de Cierre</strong></h5>
                  <hr>
                
                  
                  <p style="font-size:30px;" class="text-dark texto-secundario"> 
                      <span id="cantidad-cierre"></span> / <span>3</span>
                </p>
                  
              </div>
            </div>

          </div>
          <div class="col-sm-4">
              <div class="card text-center" style="width: 20rem;">
              
                <div class="card-body"> 
                  <?php $total = $cantidad['total']; ?>
                  <h5 class="card-title texto-secundario"><strong>Cantidad de venta</strong></h5>
                  <hr>
                    <p style="font-size:30px;" class="text-dark texto-secundario"> <?php echo $total ?> </p>

              </div>
            </div>

          </div>
        </div>
   </div>


<div class="chart-container shadow-sm border-0">

  <div class="table-responsive text-dark texto-secundario">
        <table id="myTable" class="table table-hover" style="width:100%">
          <thead class="table-color">
            <tr class="table-primary">
              <th class="text-center">#</th>
              <th class="text-center">Fecha Inicio</th>
              <th class="text-center">Fecha Cierre</th>
              <th class="text-center">Total $</th>
              <th class="text-center"><i class="fa-solid fa-comments-dollar me-2"></i>Tasa Cambio </th>
              <th class="text-center">Total Bs</th>
              <th class="text-center">Estado</th>
              
              <?php if( $tipo_usuario == 1) { ?> 
              <th class="text-center">Accion</th>
              <?php } ?>
            </tr>
          </thead>
        <tbody>
        <?php
         $estatus_texto = array( // Texto que aparece en la vista
          1 => 'Cierre Activo',
          2 => 'Cierre Finalizado',
        );
  
        $estatus_classes = array(// para el estido CSS
          1=> 'badge rounded-pill bg-warning ',
          2=>'badge rounded-pill bg-success ', 
        );
           foreach ($registro as $dato){
        ?>
          <tr>
              <td class="text-center bg-s"><?php echo $dato['id_cierre']; ?></td>
              <td class="text-center bg-s"><?php echo date("d/m/Y", strtotime($dato['fecha_creacion'])); ?></td>
              <td class="text-center bg-s"> 
              <?php 
                    $fecha = $dato['fecha_cierre'];
                    $mostrar_fecha = (!empty($fecha) && $fecha !== '0000-00-00') ? date("d/m/Y", strtotime($fecha)) : ' - ';
                ?>
                <?php echo $mostrar_fecha; ?>
              </td>
              <td class="text-center bg-s">
              <p> <span>$. </span><?php echo $dato['total_dia_dl']; ?> </p> 
              </td>
              <td class="text-center bg-s">
                <p class="badge rounded-pill bg-secondary"> <span>Bs. </span> <?php echo $dato['tasa']; ?></p>
              </td>
              <td class="text-center bg-s">
                 <p> <span>Bs. </span><?php echo $dato['total_dia_bs']; ?> </p> 
              </td>
              <td class="text-center bg-s">
                    <span class="<?= $estatus_classes[$dato['estatus']] ?>">
                      <b>
                       <?php echo $estatus_texto[$dato['estatus']] ?>
                       </b>
                    </span>
                  </td>
              <td class="text-center bg-s">
                <form action="">
                  <button type="button" class="btn btn-info btn-sm text-dark" disabled><i class="fa-solid fa-file-pdf me-2"></i>Descargar</button>
                </form>
              </td>
              
          </tr>

        <!-- Modal con todos los detalles -->
        <?php } ?>
        </tbody>
        </table>
    
      </div>


</div>


<form action="?pagina=cierre" id="fecha_consulta">
<input type="hidden" name="fecha" id="consulta" >
</form>

<form action="?pagina=cierre" id="consultacierre">
<input type="hidden" name="fecha" id="fechacierre" >
</form>

 
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/moment.js"></script>
  <script src="assets/js/cierre.js"></script>
  <?php require_once 'complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->

 
</body>
</html>