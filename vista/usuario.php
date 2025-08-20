<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head.php'; ?>
    <title>Usuario | Mister Bolsa C.A</title>
     <link rel="stylesheet" href="assets/css/for.css" />
</head>

<body>
  <!-- LOADER-->
  <div id="loaderPagina" class="section_loader">
  <span class="loader"></span>
</div>
  <!-- ||||||||||||||  Comienzo de Sidebar y Nav |||||||||||||||||||||||-->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
  <?php require_once 'complementos/sidebar.php'; ?>
 
    <div class="body-wrapper">
    <?php require_once 'complementos/nav.php'; ?>
    <div class="container-fluid">
  <!--|||||||||||||||   Fin de Sidebar y Nav  |||||||||||||||||||||||||||-->

  <nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="?pagina=home">Inicio</a></li>
    <li class="breadcrumb-item active color-text1" aria-current="page">Usuario</li>
  </ol>
</nav>

<div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900"> <i class="fa-solid fa-users mr-3" style="color: #E63946;"></i>
        Usuario</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
          <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <span class="icon text-dark">
            <i class="fas fa-file-medical me-2"></i>
            </span>
            <span class="text text-dark">Registrar Usuario</span>
      </button>
      </div>


<div class="card shadow mb-4"> <!-- comienzo del Cuadro blanco-->
<div class="table-margen">    <!-- comienzo del Cuadro blanco body-->
<div class="table-responsive">
<table id="myTable" class="table table-striped table-hover" style="width:100%">
    <thead>
      <tr class="table-primary">
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
          1=> 'badge rounded-pill bg-success text-dark', // Activo
          2=>'badge rounded-pill bg-warning text-dark', // No activo
          3=> 'badge rounded-pill bg-danger' // Inactivo
        );
        foreach ($registro as $dato){
        ?>
                <tr>
                  <td class="text-center"> <?php echo $dato['id_usuario']?> </td>
                  <td class="text-center"> <?php echo $dato['nombre']." ".$dato['apellido']?> </td>
                 
                  <td class="text-center"> <?php echo $dato['usuario']?> </td>
                  <td class="text-center"> <?php echo $dato['clave']?> </td>
                  <td class="text-center">
                    <span class="<?= $estatus_classes[$dato['estatus']] ?>">
                      <b>
                       <?php echo $estatus_texto[$dato['estatus']] ?>
                       </b>
                    </span>
                  </td>
                  <td class="text-center"> <?php echo $dato['nombre_tipo']?> </td>
      
                  <td>
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

      <div class="modal-body">
      <div class="alert alert-info" role="alert">
        Tipo de Usuario <br>
        Nivel 1 : Acceso completo al sistema <br>
        Nivel 2 : Producto, Compra, Venta, Marca, Presentación | Sin la funcion de Editar o Eliminar datos <br>
        Nivel 3 : Producto, Venta | Sin la funcion de editar o eliminar datos<br>
        </div>

      <form action="?pagina=usuario" id="u" autocomplete="off"> <!--comienzo Formulario-->
         <div class="seccion-formulario">
        <div class="row ">
          <h6><i class="fa-solid fa-user"></i> Datos de la Persona </h6>
          <div class="col-sm">
          <label class="form-label text-dark">Nombre</label>
     
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-address-book" style="color:#007bff;"></i></span>
              <input type="text" class="form-control text-dark" name="nombres" id="nombre" placeholder="Ejemplo: Pedro "  required> 
            </div> 
          <span id="textonombre" class="text-error mt-0"></span> <br>
          </div>
      
          <div class="col-sm">
          <label class="form-label text-dark">Apellido</label>
        
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-address-book" style="color:#007bff;"></i></span>
              <input type="text" class="form-control text-dark" name="apellidos" id="apellido" placeholder="Ejemplo: Perez "  required>  
            </div> 
          
          <span id="textoapellido" class="text-error mt-0"></span> <br>
          </div>

          <div class="col-sm">
          <label class="form-label text-dark">Cargo (Tipo de Usuario)</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user-tag" style="color:#007bff;"></i></span>
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
          <h6><i class="fa-solid fa-user"></i> Datos Para acceder al sistema</h6>
          <div class="col-sm">
          <label class="form-label text-dark">Usuario</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-user" style="color:#007bff;"></i></span>
              <input type="text" class="form-control text-dark" name="usuario" id="usuario" placeholder="Ejemplo: misterbolsa123 "  required>  
            </div> 
          <span id="textousuario" class="text-error mt-0"></span> <br>
          </div>

          <div class="col-sm">
          <label class="form-label text-dark">Constraseña</label>
            <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-lock" style="color:#007bff;"></i></span>
              <input type="text" class="form-control text-dark"  name="clave" id="clave" placeholder="Ejemplo: misterbolsa2024 "  required>    
            </div>
          <span id="textoclave" class="text-error mt-0"></span> <br>
          </div>
           </div>
        </div>
        
<hr>
<div class="button-container">
    <button type="button" class="btn btn-guardar" id="registrar">Registrar</button>
    <button type="reset" class="btn btn-limpiar" name="limpiar">Limpiar</button>
</div>
        </form> <!--fin del formulario-->
      </div>
    
      
    </div>
  </div>
</div>     



  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/usuario.js"></script>
  <?php require_once 'complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->
  
</body>
</html>