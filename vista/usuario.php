<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head2.php'; ?>
    <title>Usuario | Mister Bolsa C.A</title>
   
</head>

<body>

  <?php require_once 'complementos/nav2.php'; ?>
  <?php require_once 'complementos/sidebar2.php'; ?>
 
  <div class="row">
    <div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 TextOscuroTitel"> <i class="fa-solid fa-users me-2" style="color: #E63946;"></i>
        Usuario</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <i class="fas fa-file-medical me-2"></i>
            Registrar Usuario
      </button>
    </div>

  </div>

  <div class="transaction-list shadow-sm border-0 DivOscuro1">

    <div class="table-responsive TextOscuro">
        <table id="myTable" class="table table-m table-hover" style="width:100%">
            <thead class="table-color">
              <tr>
                <th class="text-center">#</th>
                <th class="text-center">NOMBRE Y APELLIDO</th>
            
                <th class="text-center">USUARIO</th>
                <th class="text-center">CLAVE</th>
                <th class="text-center">ESTATUS</th>
                <th class="text-center">TIPO</th>
                <th class="text-center">ACCION</th>
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
                          <td class="text-center td-oscuro"> <?php echo $dato['id_usuario']?> </td>
                          <td class="text-center td-oscuro"> <?php echo $dato['nombre']." ".$dato['apellido']?> </td>
                        
                          <td class="text-center td-oscuro"> <?php echo $dato['usuario']?> </td>
                          <td class="text-center td-oscuro"> <?php echo $dato['clave']?> </td>
                          <td class="text-center td-oscuro">
                            <span class="<?= $estatus_classes[$dato['estatus']] ?>">
                              <b>
                              <?php echo $estatus_texto[$dato['estatus']] ?>
                              </b>
                            </span>
                          </td>
                          <td class="text-center td-oscuro"> <?php echo $dato['nombre_tipo']?> </td>
              
                          <td class="td-oscuro">
                              <form action="?pagina=usuario" method="POST" >
                                <button name="modificar" class="btn btn-primary btn-sm modificar"
                                value="<?php echo $dato['id_usuario'] ?>"><i class="fas fa-pencil-alt" title="Editar"></i> Editar</button>
                            
                                <button type="button" onclick="eliminar(this)" class="btn btn-danger btn-sm eliminar" value="<?php echo $dato['id_usuario'] ?>"> <i class="fas fa-trash-alt" title="Eliminar"> </i> Eliminar </button>
                              </form>
                              
                                
                        

                          </td>
                        </tr>
        <?php } ?>
            
            </tbody>
            </table>
        </div>

  </div>

        
<!-- |||||||||||||||||||||||||   Modal   |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content modal-producto">
    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel" style="color:rgb(255, 255, 255);">
        Registro de Usuario
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:rgb(255, 255, 255);"></button>
      </div>

      <div class="modal-body div-oscuro-2 ">
    

      <form action="?pagina=usuario" id="u" autocomplete="off"> <!--comienzo Formulario-->
         <div class="seccion-formulario">
        <div class="row ">
          <h6 class=""><i class="fa-solid fa-user"></i> Datos de la Persona </h6>
          <div class="col-sm">
          <label class="form-label text-dark">Nombre</label>
     
            <div class="input-group mb-3">
              <span class="input-group-text div-icono" id="basic-addon1"><i class="fa-solid fa-address-book" style="color:#FF3C37;"></i></span>
              <input type="text" class="form-control text-dark" name="nombres" id="nombre" placeholder="Ejemplo: Pedro "  required> 
            </div> 
          <span id="textonombre" class="text-error mt-0"></span> <br>
          </div>
      
          <div class="col-sm">
          <label class="form-label text-dark">Apellido</label>
        
            <div class="input-group mb-3">
              <span class="input-group-text div-icono" id="basic-addon1"><i class="fa-solid fa-address-book" style="color:#FF3C37;"></i></span>
              <input type="text" class="form-control text-dark" name="apellidos" id="apellido" placeholder="Ejemplo: Perez "  required>  
            </div> 
          
          <span id="textoapellido" class="text-error mt-0"></span> <br>
          </div>

          <div class="col-sm">
          <label class="form-label text-dark">Cargo (Tipo de Usuario)</label>
            <div class="input-group mb-3">
              <span class="input-group-text div-icono" id="basic-addon1"><i class="fa-solid fa-user-tag" style="color:#FF3C37;"></i></span>
                <select class="form-select text-dark" aria-label="Default select example" name="tipo_usuario" id="tiposelect" required>
                   <option value="">Seleccione una Tipo de Usuario:</option>
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
          <h6 class="texto-principal"><i class="fa-solid fa-user"></i> Datos Para acceder al sistema</h6>
          <div class="col-sm">
          <label class="form-label text-dark texto-secundario">Usuario</label>
            <div class="input-group mb-3">
              <span class="input-group-text div-icono"  id="basic-addon1"><i class="fa-solid fa-user" style="color:#FF3C37;"></i></span>
              <input type="text" class="form-control text-dark" name="usuario" id="usuario" placeholder="Ejemplo: misterbolsa123 "  required>  
            </div> 
          <span id="textousuario" class="text-error mt-0"></span> <br>
          </div>

          <div class="col-sm">
          <label class="form-label text-dark texto-secundario">Constraseña</label>
            <div class="input-group mb-3">
              <span class="input-group-text div-icono" id="basic-addon1"><i class="fa-solid fa-lock" style="color:#FF3C37;"></i></span>
              <input type="text" class="form-control text-dark"  name="clave" id="clave" placeholder="Ejemplo: misterbolsa2024 "  required>    
            </div>
          <span id="textoclave" class="text-error mt-0"></span> <br>
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
  <script src="assets/js/usuario.js"></script>
  <?php require_once 'complementos/footer2.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->
  
</body>
</html>