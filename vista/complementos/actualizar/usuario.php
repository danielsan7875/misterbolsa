<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head.php'; ?>
    <title>Modificar Usuario | Mister Bolsa C.A</title>
</head>

<body>
  <!-- LOADER-->
<div id="loaderPagina" class="section_loader">
<div class="loader-3"></div>
</div>
  <!-- ||||||||||||||  Comienzo de Sidebar y Nav |||||||||||||||||||||||-->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
  <?php require_once 'vista/complementos/sidebar.php'; ?>
 
    <div class="body-wrapper">
    <?php require_once 'vista/complementos/nav.php'; ?>
    <div class="container-fluid">
  <!--|||||||||||||||   Fin de Sidebar y Nav  |||||||||||||||||||||||||||-->

  <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="?pagina=home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="?pagina=usuario">Usuario</a></li>
    <li class="breadcrumb-item active color-text1" aria-current="page">Modificar</li>
  </ol>
</nav>

<div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900"> <i class="fa-solid fa-users mr-3" style="color: #E63946;"></i>
       Modificar</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
          <a href="?pagina=usuario" class="btn btn-primary">
          <span class="icon text-white-100">
            <i class="fas fa-file-medical"></i>
            </span>
            <span class="text ext-white-100">Volver</span>
      </a>
      </div>

    <div class="card shadow mb-4"> <!-- comienzo del Cuadro blanco-->
      <div class="table-margen">    <!-- comienzo del Cuadro blanco body-->
      <form action="?pagina=usuario" id="m" autocomplete="off"> <!--comienzo Formulario-->
        
        <div class="row ">
            

          <div class="col-sm-2">
            <h5>Usuario: # <?php echo $modificar['id_usuario']?></h5>
              <input type="hidden"  value ="<?php echo $modificar ['id_usuario']?>" name="id_usuario" id="id_usuario">
            </div>
        </div>

        <div class="row ">
          <div class="col-sm">
          <label class="form-label text-dark">Nombre</label>
     
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-address-book" style="color:#007bff;"></i></span>
              <input type="text" class="form-control text-dark" name="nombres" id="nombre" value="<?php echo $modificar['nombre']?>"  required> 
            </div> 
          <span id="textonombre" class="vali-span"></span> <br>
          </div>
      
          <div class="col-sm">
          <label class="form-label text-dark">Apellido</label>
        
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-address-book" style="color:#007bff;"></i></span>
              <input type="text" class="form-control text-dark" name="apellidos" id="apellido" value="<?php echo $modificar['apellido']?>"  required>  
            </div> 
          
          <span id="textoapellido" class="vali-span"></span> <br>
          </div>

          <div class="col-sm">
          <label class="form-label text-dark">Cargo (Tipo de Usuario)</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user-tag" style="color:#007bff;"></i></span>
                <select class="form-select text-dark" aria-label="Default select example" name="tipo_usuario" required>
                <option value="<?php echo $modificar['id_tipo']; ?>"><?php echo $modificar['nombre_tipo']." - actual"; ?></option>
                      <?php foreach($tipos as $tipos) {?>
                    <option value="<?php echo $tipos['id_tipo'];?>"> <?php echo $tipos['nombre'].' - Nivel '.$tipos['nivel'];?> </option>
                      <?php } ?>
                </select>
            </div>    
          </div>
          
        </div>

        <div class="row ">
          <div class="col-sm">
          <label class="form-label text-dark">Usuario</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user" style="color:#007bff;"></i></span>
              <input type="text" class="form-control text-dark" name="usuario" id="usuario" value="<?php echo $modificar['usuario']?>" required>  
            </div> 
          <span id="textousuario" class="vali-span"></span> <br>
          </div>

          <div class="col-sm">
          <label class="form-label text-dark">Constraseña</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock" style="color:#007bff;"></i></span>
              <input type="text" class="form-control text-dark"  name="clave" id="clave" value="<?php echo $modificar['clave']?>"required>    
            </div>
          <span id="textoclave" class="vali-span"></span> <br>
          </div>

          <div class="col-sm-3">
          <label class="form-label text-dark">Estatus</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-regular fa-file-lines" style="color:#007bff;"></i></span>
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
<hr>
<div class="button-container">
    <button type="button" class="btn btn-success" id="actualizar">Actualizar</button>
    <button type="reset" class="btn btn-secondary" name="limpiar">Limpiar</button>
</div>
        </form> <!--fin del formulario-->
      </div>
    </div>

  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/usuario.js"></script>
  <?php require_once 'vista/complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->    
</body>
</html>