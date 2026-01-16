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


        <table class="table table-hover" style="width:100%">
          <thead class="table-color">
            <tr>
              <th>#</th>
              <th>NOMBRE</th>
              <th>ESTATUS</th>
              <th>ACCION</th>
            </tr>
          </thead>

          <tbody id="tablapresentacion">
            
          
          </tbody>
        </table>

    </div>
  
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