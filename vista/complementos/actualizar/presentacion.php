<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head2.php'; ?>
    <title>Modificar presentación | Mister Bolsa C.A</title>
</head>

<body>
  
  <?php require_once 'vista/complementos/nav2.php'; ?>
  <?php require_once 'vista/complementos/sidebar2.php'; ?>
 
  <div class="row">

     <div class="d-sm-flex align-items-center justify-content-between mb-5">
          <h1 class="h3 mb-0 text-gray-900 texto-principal"> <i class="fa-solid fa-shapes  mr-3" style="color: #E63946;"></i>
          Modificar</h1>
              
          <!-- Button que abre el Modal N1 Registro -->
              <a href="?pagina=presentacion" class="btn btn-primary">
              <span class="icon text-white-100">
              <i class="fa-solid fa-reply me-2"></i>
                </span>
                <span class="text ext-white-100">Volver</span>
          </a>
      </div> 
      
  </div>
   
  <div class="chart-container shadow-sm border-0">
   


      <form action="?pagina=presentacion" id="m" autocomplete="off"> <!--comienzo Formulario-->
        
<div class="seccion-formulario">
        <div class="row">

        <div class="col-sm-2">
          <label class="form-label text-dark">#</label>        
              <input type="text" class="form-control text-dark" value ="<?php echo $modificar ['id_presentacion']?>" disabled>  
              <input type="hidden"  name="id_presentacion" id="id_presentacion" value ="<?php echo $modificar ['id_presentacion']?>">  
          </div>

          <div class="col-sm-6">
          <label class="form-label text-dark">Nombre</label>
          <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-file-lines" style="color:#FF3C37;"></i></span>
               <input type="text" class="form-control text-dark" name="nombre" id="nombre_prese" value ="<?php echo $modificar ['nombre']?>" required>  
         
          </div>
          <span id="textopresentacion" class="text-error mt-0"></span> <br>
          </div>


          <div class="col-sm-4">
          <label class="form-label text-dark">Estatus</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-file-lines" style="color:#FF3C37;"></i></span>
                   <select class="form-select text-dark" name="estatus" required>
                     <?php
                       $estatus_texto = array(
                           1 => 'Activo',
                           2 => 'No Activo',
                                  );
                      ?>
                     <option value="<?php echo $modificar ['estatus']?>"> <?php echo $estatus_texto[$modificar['estatus']]. ' - Actual';?></option>
                     <option value="1">Activo</option>
                     <option value="2">No Activo</option>
                     </select>
            </div>
          </div>
 </div>
          
        </div>


<hr>
<div class="d-flex justify-content-center gap-3 my-4">
   
    <button type="reset" class="btn btn-lg btn-limpiar" name="limpiar"><i class="fa-solid fa-repeat me-2"></i>Limpiar</button>
     <button type="button" class="btn btn-lg btn-guardar" id="actualizar"><i class="fa-solid fa-floppy-disk me-2"></i> Actualizar Datos</button>

</div>
        </form> <!--fin del formulario-->
      </div>


  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/presentacion.js"></script>
  <?php require_once 'vista/complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->    
</body>
</html>