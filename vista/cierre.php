<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head2.php'; ?>
    <title>Cierre Diario | Mister Bolsa C.A</title>
</head>

<body> 

  <?php require_once 'complementos/nav2.php'; ?>
  <?php require_once 'complementos/sidebar2.php'; ?>
 
  <div class="row">
    <div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 TextOscuroTitel"> <i class="fa-solid fa-file-invoice-dollar me-2" style="color: #E63946;"></i>
        Cierre Diario</h1>
          <form action="?pagina=cierre" method="POST" autocomplete="off" id="form_cierre">   
          <!-- Button que abre el Modal N1 Registro -->

              <input type="hidden" name="fecha" id="cierrefecha" >
              
              <?php foreach ($dolar as $dolar){  ?>
                  <input type="hidden" name="tasa" id="tasass" value="<?php echo $dolar['precios']?>">
              <?php } ?>

                <?php if (!$cierre) {
                    echo'
                    <button type="button" class="btn btn-success" name="iniciar" id="iniciar">
                    <i class="fa-solid fa-circle-play me-2"></i> Comenzar
                    </button>
                    '; }else{
                    echo'
                    <button type="button" class="btn btn-success" name="iniciar"  disabled>
                    <i class="fa-solid fa-circle-play me-2"></i> Comenzar
                    </button>
                    '
                ; } ?>            
          </form> 
      </div>
  </div>
    
   <div class="transaction-list shadow-sm border-0 DivOscuro1">
       <div class="row justify-content-center g-4 my-3 mt-0">

    <!-- CARD 1 -->
    <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
        <div class="card text-center DivOscuro2 w-100">
            <div class="card-body" id="card-body-cierre">
                <h5 class="card-title TextOscuroTitel"><strong>Estatus del Cierre</strong></h5>
                <hr>
                 <?php if (!$cierre) {
                     echo'
                      <h5 class="text-dark TextOscuro mb-3">
                        Se encuentra:<strong class="badge rounded-pill bg-secondary text-light">No activo</strong>
                      </h5>
                      '; }else{
                       echo'
                      <h5 id="" class="text-dark TextOscuro mb-3">
                        Se encuentra:  <strong class="badge rounded-pill bg-success">Activo #'.$cierre['id_cierre'].'</strong>
                      </h5>'
                      ;
                  } ?>

                <form action="?pagina=cierre" method="POST" id="cierreBD">
                     <input type="hidden" id="fechaCierreBd" name="fecha_cierre">
                     <input type="hidden" name="id_cierre" value="<?php echo $cierre['id_cierre']?>" >
                   
                    <?php if (!$cierre) {
                        echo'
                        <button type="button" id="btn-cerrar-cierre" class="btn btn-danger w-100" disabled> Cerrar Cierre </button>
                        '; }else{
                         echo'
                         <button type="button" id="btn-cerrar-cierre" class="btn btn-danger w-100"> Cerrar Cierre </button>
                        ';
                    } ?>
                 
                </form>
            </div>
        </div>
    </div>

    <!-- CARD 2 -->
    <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
        <div class="card text-center DivOscuro2 w-100">
            <div class="card-body">
                <h5 class="card-title TextOscuroTitel"><strong>Cantidad de Cierre</strong></h5>
                <hr>
                <p style="font-size:30px;" class="text-dark TextOscuro">
                    <span> <?php echo isset($contarcierre['totalcontar']) ? $contarcierre['totalcontar'] : 0; ?> / 3 </span>
                </p>
            </div>
        </div>
    </div>

    <!-- CARD 3 -->
    <div class="col-12 col-md-6 col-lg-4 d-flex justify-content-center">
        <div class="card text-center DivOscuro2 w-100">
            <div class="card-body">
                <?php $total = $cantidad['total']; ?>
                <h5 class="card-title TextOscuroTitel"><strong>Cantidad de venta</strong></h5>
                <hr>
                <p style="font-size:30px;" class="text-dark TextOscuro">
                    <?php echo $total ?>
                </p>
            </div>
        </div>
    </div>

</div>

   </div>


<div class="chart-container shadow-sm border-0 DivOscuro1">

  <div class="table-responsive text-dark TextOscuro">
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
          1=> 'badge rounded-pill bg-warning text-dark ',
          2=>'badge rounded-pill bg-success ', 
        );
           foreach ($registro as $dato){
        ?>
          <tr>
              <td class="text-center td-oscuro"><?php echo $dato['id_cierre']; ?></td>
              <td class="text-center td-oscuro">
                <?php 
                    $fecha = $dato['fecha_creacion'];
                    $mostrar_fecha1 = (!empty($fecha) && $fecha !== '0000-00-00') ? date("d/m/Y", strtotime($fecha)) : ' - ';
                ?>
                <?php echo $mostrar_fecha1; ?>
              </td>
              <td class="text-center td-oscuro"> 
              <?php 
                    $fecha = $dato['fecha_cierre'];
                    $mostrar_fecha = (!empty($fecha) && $fecha !== '0000-00-00') ? date("d/m/Y", strtotime($fecha)) : ' - ';
                ?>
                <?php echo $mostrar_fecha; ?>
              </td>
              <td class="text-center td-oscuro">
              <p> <span>$. </span><?php echo $dato['total_dia_dl']; ?>  </p> 
              </td>
              <td class="text-center td-oscuro">
                <p class="badge rounded-pill bg-secondary"> <span>Bs. </span> <?php echo $dato['tasa']; ?></p>
              </td>
              <td class="text-center td-oscuro">
                 <p> <span>Bs. </span><?php echo $dato['total_dia_bs']; ?> </p> 
              </td>
              <td class="text-center td-oscuro">
                    <span class="<?= $estatus_classes[$dato['estatus']] ?>">
                      <b>
                       <?php echo $estatus_texto[$dato['estatus']] ?>
                       </b>
                    </span>
                  </td>
              <td class="text-center td-oscuro">
               <?php  if($dato['estatus'] == 1){  ?>
                  <button type="button"  class="btn btn-primary btn-sm" disabled> <i class="fa-regular fa-file-pdf me-2"></i>Descargar</button>
                <?php  } else {  ?>
                  <form action="?pagina=cierre" method="POST" >
                          <button type="submit"  class="btn btn-primary btn-sm" name="pdf" value="<?php echo  $dato['id_cierre']; ?>"> <i class="fa-regular fa-file-pdf me-2"></i>Descargar</button>
                    </form>
                 <?php }  ?>
              </td>
              
          </tr>

        <?php } ?>
        </tbody>
        </table>
    
      </div>

      <?php if ($total_registros > $_SESSION['limite_cierre']): ?>
        <form method="POST" action="?pagina=cierre">
          <div class="text-center ">
            <button type="submit" name="ver_mas" class="btn btn-primary w-50 mt-3">
                <i class="fas fa-plus-circle"></i> Ver más registros (+100)
            </button>
            </div>
        </form>
        <?php endif; ?>



</div>


  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/moment.js"></script>
  <script src="assets/js/cierre.js"></script>
  <?php require_once 'complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->

 
</body>
</html>