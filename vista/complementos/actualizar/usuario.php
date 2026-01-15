<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head2.php'; ?>
    <title>Modificar Usuario | Mister Bolsa C.A</title>

</head>

<body>
 
  <?php require_once 'vista/complementos/nav2.php'; ?>
  <?php require_once 'vista/complementos/sidebar2.php'; ?>
 
  <div class="row">
    
      <div class="d-sm-flex align-items-center justify-content-between mb-5">
            <h1 class="h3 mb-0 texto-principal"> <i class="fa-solid fa-users me-3" style="color: #E63946;"></i>
            Modificar</h1>
                
            <!-- Button que abre el Modal N1 Registro -->
                <a href="?pagina=usuario" class="btn btn-primary">
                <span class="icon text-white-100">
                    <i class="fa-solid fa-reply me-2"></i>
                  </span>
                  <span class="text ext-white-100">Volver</span>
            </a>
       </div>
  </div>
  
  <div class="chart-container shadow-sm border-0">
      <form action="?pagina=usuario" id="m" autocomplete="off"> <!--comienzo Formulario-->
        
        <div class="row ">
          <div class="col-sm-2 ">
            <h5 class="texto-cuarto">Usuario: # <?php echo $modificar['id_usuario']?></h5>
              <input type="hidden"  value ="<?php echo $modificar ['id_usuario']?>" name="id_usuario" id="id_usuario">
            </div>
        </div>

  <div class="seccion-formulario mt-2">
        <div class="row ">
             <h6 class="texto-principal"><i class="fa-solid fa-user"></i> Datos de la Persona </h6>
          <div class="col-sm">
          <label class="form-label text-dark texto-secundario">Nombre</label>
     
            <div class="input-group mb-3">
              <span class="input-group-text div-icono" id="basic-addon1"><i class="fa-solid fa-address-book" style="color:#FF3C37;"></i></span>
              <input type="text" class="form-control text-dark" name="nombres" id="nombre" value="<?php echo $modificar['nombre']?>"  required> 
            </div> 
          <span id="textonombre" class="vali-span"></span> <br>
          </div>
      
          <div class="col-sm">
          <label class="form-label text-dark texto-secundario">Apellido</label>
        
            <div class="input-group mb-3">
              <span class="input-group-text div-icono" id="basic-addon1"><i class="fa-solid fa-address-book" style="color:#FF3C37;"></i></span>
              <input type="text" class="form-control text-dark" name="apellidos" id="apellido" value="<?php echo $modificar['apellido']?>"  required>  
            </div> 
          
          <span id="textoapellido" class="vali-span"></span> <br>
          </div>

          <div class="col-sm">
          <label class="form-label text-dark texto-secundario">Cargo (Tipo de Usuario)</label>
            <div class="input-group mb-3">
              <span class="input-group-text div-icono" id="basic-addon1"><i class="fa-solid fa-user-tag" style="color:#FF3C37;"></i></span>
                <select class="form-select text-dark" aria-label="Default select example" name="tipo_usuario" id="tiposelect" required>
                <option value="<?php echo $modificar['id_tipo']; ?>"><?php echo $modificar['nombre_tipo']." - actual"; ?></option>
                      <?php foreach($tipos as $tipos) {?>
                    <option value="<?php echo $tipos['id_tipo'];?>"> <?php echo $tipos['nombre'].' - Nivel '.$tipos['nivel'];?> </option>
                      <?php } ?>
                </select>
            </div>    
             <span id="textotipo" class="text-error mt-0"> </span> 
          </div>
              </div>
        </div>

      <div class="seccion-formulario">
        <div class="row ">
          <h6 class="texto-principal"><i class="fa-solid fa-user"></i> Datos Para Acceder al Sistema </h6>
          <div class="col-sm">
          <label class="form-label text-dark texto-secundario">Usuario</label>
            <div class="input-group mb-3">
              <span class="input-group-text div-icono" id="basic-addon1"><i class="fa-solid fa-user" style="color:#FF3C37;"></i></span>
              <input type="text" class="form-control text-dark" name="usuario" id="usuario" value="<?php echo $modificar['usuario']?>" required>  
            </div> 
          <span id="textousuario" class="vali-span"></span> <br>
          </div>

          <div class="col-sm">
          <label class="form-label text-dark texto-secundario">Constraseña</label>
            <div class="input-group mb-3">
              <span class="input-group-text div-icono" id="basic-addon1"><i class="fa-solid fa-lock" style="color:#FF3C37;"></i></span>
              <input type="text" class="form-control text-dark"  name="clave" id="clave" value="<?php echo $modificar['clave']?>"required>    
            </div>
          <span id="textoclave" class="vali-span"></span> <br>
          </div>

          <div class="col-sm-3">
          <label class="form-label text-dark texto-secundario">Estatus</label>
            <div class="input-group mb-3">
              <span class="input-group-text div-icono" id="basic-addon1"><i class="fa-regular fa-file-lines" style="color:#FF3C37;"></i></span>
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
  <script src="assets/js/usuario.js"></script>
  <?php require_once 'vista/complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->    
</body>
</html>