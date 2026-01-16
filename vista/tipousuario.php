<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head2.php'; ?>
    <title>Tipo de Usuario | Mister Bolsa C.A</title>
</head>

<body>
  
  <?php require_once 'complementos/nav2.php'; ?>
  <?php require_once 'complementos/sidebar2.php'; ?>
 
  <div class="row">
      <div class="d-sm-flex align-items-center justify-content-between mb-5">
        <h1 class="h3 mb-0 TextOscuroTitel"> <i class="fa-solid fa-user-tag me-2" style="color: #E63946;"></i>
          Tipo de Usuario</h1>
            
        <!-- Button que abre el Modal N1 Registro -->
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fas fa-file-medical me-2"></i>
            Registrar
        </button>
      </div>
  </div>
    
  <div class="transaction-list shadow-sm border-0 DivOscuro1">
      <div class="table-responsive TextOscuro">
        <table class="table table-hover" style="width:100%">
            <thead class="table-color">
              <tr>
                <th class="text-center">#</th>
                <th>NOMBRE</th>
                <th>NIVEL</th>
                <th>ACCION</th>
              </tr>
            </thead>
            <tbody id="tablatipousuario" class="">
              
            
            </tbody>
            </table>
        </div>

  </div>



<!-- |||||||||||||||||||||||||   Modal   |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content modal-producto">
    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5 text-white" id="exampleModalToggleLabel">
        Registro de Tipo de Usuario
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:rgb(255, 255, 255);"></button>
      </div>

      <div class="modal-body div-oscuro-2">
      <form action="?pagina=tipousuario" id="u" autocomplete="off"> <!--comienzo Formulario-->
        
      <div class="seccion-formulario">
        <div class="row ">
          <h6><i class="fa-solid fa-user"></i> Datos del cargo </h6>
          <div class="col-sm">
          <label class="form-label text-dark">Nombre</label>
          <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user-tag" style="color:#FF3C37;"></i></span>
              <input type="text" class="form-control text-dark" name="nombre" id="nombre" placeholder="Nombre: "  required>  
         
            </div> 
         <span id="textonombre" class="vali-span"></span>
        </div>

          <div class="col-sm">
          <label class="form-label text-dark">Nivel</label>
            <div class="input-group mb-3">
             <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-users" style="color:#FF3C37;"></i></span>  
                <select class="form-select text-dark" aria-label="Default select example" name="nivel" id="selectnivel" required>
                  <option value="">Seleccione un nivel:</option>
                  <option value="1">Nivel 1</option>
                  <option value="2">Nivel 2</option>   
                  <option value="3">Nivel 3</option>          
                </select>
            </div> 
            <span class="text-error mt-0" id="textonivel"> </span>
           </div>
          </div>
        </div>
        <div class="alert alert-info" role="alert">
        Tipo de Usuario <br>
        Nivel 1 : <br>
        Nivel 2 : <br>
        Nivel 3 : Producto, Venta | Sin la funcion de editar o eliminar datos<br>
        </div>  

<hr>
<div class="button-container">
    
    
</div>

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
  <script src="assets/js/tipousuario.js"></script>
  <?php require_once 'complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->    

  </body>
</html>