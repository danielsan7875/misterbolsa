<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head2.php'; ?>
    <title>Presentación | Mister Bolsa C.A</title>
    <!-- <link rel="stylesheet" href="assets/css/for.css" /> -->
</head>

<body>

  <?php require_once 'complementos/nav2.php'; ?>
  <?php require_once 'complementos/sidebar2.php'; ?>
 
  <div class="row">
      <div class="d-sm-flex align-items-center justify-content-between mb-5">
          <h1 class="h3 mb-0 TextOscuroTitel"> <i class="fa-solid fa-shapes  mr-3" style="color: #E63946;"></i>
          Presentación</h1>
              
          <!-- Button que abre el Modal N1 Registro -->
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
              <i class="fas fa-file-medical me-2"></i>
               Registrar Presentación
          </button>
      </div>
  </div>
    
  <div class="transaction-list shadow-sm border-0 DivOscuro1">

    <div class="table-responsive TextOscuro">

        <table id="myTable" class="table table-hover" style="width:100%">
          <thead class="table-color">
            <tr>
              <th>NOMBRE</th>
              <th>ESTATUS</th>
              <th>ACCION</th>
            </tr>
          </thead>

          <tbody>
            <?php
            $estatus_texto = array( // Texto que aparece en la vista
                  1 => 'Activo',
                  2 => 'No Activo',
                );
          
                $estatus_classes = array(// para el estido CSS
                  1=> 'badge rounded-pill bg-success ', // Activo
                  2=>'badge rounded-pill bg-warning', // No activo
                  3=> 'badge rounded-pill bg-danger' // Inactivo
                );
            foreach ($registro as $dato){
            ?>
                <tr> 
               <td class="td-oscuro">
                  <div class="d-flex align-items-center">
                      <div class="me-3">
                          <i class="fa-solid fa-shapes fa-2x" style="color: #E63946;"></i>
                      </div>
                      <div>
                          <div class="fw-bold" style="font-size: 1.1rem;">
                              <?php echo $dato['nombre']; ?>
                          </div>
                          <div class="text-muted small">
                              # <?php echo $dato['id_presentacion']; ?>
                          </div>
                      </div>
                  </div>
              </td> 
                <td class="td-oscuro align-middle">
                    <span class="<?= $estatus_classes[$dato['estatus']] ?> fa-1x">
                              <?php echo $estatus_texto[$dato['estatus']] ?>
                    </span>
                </td>
                <td class="td-oscuro align-middle"> 
                <form method="POST" action="?pagina=presentacion"> 
                    <button name="modificar" class="btn btn-primary btn-sm modificar me-3" value="<?php echo $dato['id_presentacion']?> "><i class="fas fa-pencil-alt" title="Editar"></i> Modificar </button> 
                    <button type="button" onclick="eliminar(this)" class="btn btn-danger btn-sm eliminar" value="<?php echo $dato['id_presentacion']?> "><i class="fas fa-trash-alt" title="Eliminar"> </i> Eliminar </button> 
                </form> 
                </td> 
                </tr>

              <?php } ?>
            
          
          </tbody>
        </table>

    </div>

     <?php if ($total_registros > $_SESSION['limite_presentacion']): ?>
        <form method="POST" action="?pagina=presentacion">
          <div class="text-center ">
            <button type="submit" name="ver_mas" class="btn btn-primary w-50 mt-3">
                <i class="fas fa-plus-circle"></i> Ver más registros (+100)
            </button>
            </div>
        </form>
        <?php endif; ?>
  
  </div>


<!-- |||||||||||||||||||||||||   Modal   |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content modal-producto">
    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel" style="color:rgb(255, 255, 255);">
        Registro de Presentación
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
      </div>

      <div class="modal-body div-oscuro-2">
      <form action="?pagina=presentacion" id="u" autocomplete="off"> <!--comienzo Formulario-->
        
<div class="seccion-formulario">
        <div class="row">

          <div class="col-sm">
          <label class="form-label text-dark">Nombre</label>
          <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-file-lines" style="color:#FF3C37;"></i></span>
               <input type="text" class="form-control text-dark" name="nombre" id="nombre_prese" placeholder="Nombre: "  required>  
         
          </div>
          <span id="textopresentacion" class="text-error mt-0"></span> <br>
          </div>

          
        </div>
  </div>

<hr>

      <div class="d-flex justify-content-center gap-3 my-4">
              <button type="reset" class="btn btn-lg btn-limpiar" name="limpiar">Limpiar</button>
            <button type="button" class="btn btn-lg btn-guardar" id="registrar">Registrar</button>
      </div>
        </form> <!--fin del formulario-->
      </div>
    
      
    </div>
  </div>
</div>     
       
 
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->

  <script src="assets/js/presentacion.js"></script>
  <?php require_once 'complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||--> 
  
</body>
</html>