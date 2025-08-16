<!doctype html>
<html lang="en">

<head>
    <?php require_once 'complementos/head.php'; ?>
    <title> Venta | Mister Bolsa C.A</title>
    <link rel="stylesheet" href="assets/css/producto.css" /> 
    <link rel="stylesheet" href="assets/css/venta.css" />
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
    <li class="breadcrumb-item active color-text1" aria-current="page">Venta</li>
  </ol>
</nav>

<div class="d-sm-flex align-items-center justify-content-between mb-5">
       <h1 class="h3 mb-0 text-gray-900"> <i class="fa-solid fa-cash-register mr-3" style="color: #E63946;"></i>
        Venta</h1>
           
       <!-- Button que abre el Modal N1 Registro -->
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
          <span class="icon text-white-100">
            <i class="fas fa-file-medical"></i>
            </span>
            <span class="text ext-white-100">Registrar Venta</span>
      </button>
      </div>

      <div>
      <?php foreach ($dolar as $dolar){  ?>
        <input type="hidden" id="dolar" value="<?php echo $dolar['precios']?>">
        <div class="header-container mb-4">
           <div class="left-section">
              <h5><i class="fa-solid fa-money-bill-transfer"></i> Tasa del Dolar (Bs <?php echo $dolar['precios']?> ) </h5>
              <input type="hidden" value="<?php echo $dolar['precios']?>" id="tasadolar">
              <?php } ?>
              <button class="btn btn-info btn-sm" type="button" data-bs-toggle="offcanvas"
               data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="fas fa-pencil-alt m-1"></i> Cambiar</button>
          </div>
          
          <div class="button-group">
              <a href="#" class="btn btn-primary"><i class="fa-regular fa-file-pdf"></i> Reportes</a>
            </div>
        </div>
    

</div>
      <div class="card shadow mb-4"> <!-- comienzo del Cuadro blanco-->
<div class="table-margen">    <!-- comienzo del Cuadro blanco body-->
      <div class="table-responsive">
      <table id="myTable" class="table table-striped table-hover" style="width:100%">
    <thead>
      <tr class="table-primary">
        <th>#</th>
        <th>FECHA</th>
        <th>PRODUCTO</th>
        <th>CANTIDAD</th>
        <th>M o D</th>
        <th>PRECIO UNI. $</th>
        <th>TOTAL $</th>
        <?php if( $tipo_usuario == 1) { ?>
        <th>ACCION</th>
        <?php } ?>
      </tr>
    </thead>
    <tbody>
<?php
// Agrupamos los datos por id_venta
$ventas = [];
foreach ($registro as $dato) {
    $id_venta = $dato['id_venta'];
    if (!isset($ventas[$id_venta])) {
        $ventas[$id_venta] = [
            'fecha' => $dato['fecha'],
            'total' => $dato['total'],
            'detalles' => []
        ];
    }
    // Agregamos los detalles en un formato más estructurado
    $ventas[$id_venta]['detalles'][] = [
        'nombre' => $dato['nombre'],
        'cantidad' => $dato['cantidad'],
        'marca'=>$dato['nombre_marca'],
        'presentacion'=>$dato['nombre_presentacion'],
        'precio_unitario' => $dato['precio_unitario'],
        'maode' =>$dato['maode']
    ];
}

// Ahora mostramos los resultados agrupados
foreach ($ventas as $id_venta => $info) {
?>
<tr>
    <td class="text-center"><?php echo $id_venta; ?></td>
    <td class="text-center"><?php echo $info['fecha']; ?></td>
   
    <td>
        <ul>
            <?php foreach ($info['detalles'] as $detalle) { ?>
                <li><?php echo ' <i class="fa-solid fa-boxes-stacked" style="color: #457B9D;"></i> '
                .$detalle['nombre'].' - '.$detalle['marca'].' - '.$detalle['presentacion']; ?></li>
            <?php } ?>
        </ul>
    </td>
    <td>
        <ul>
            <?php foreach ($info['detalles'] as $detalle) { ?>
                <li><?php echo $detalle['cantidad']?></li>
            <?php } ?>
        </ul>
    </td>
    <td>
        <ul>
            <?php foreach ($info['detalles'] as $detalle) { ?>
                <li><?php echo $detalle['maode']; ?></li>
            <?php } ?>
        </ul>
    </td>
    <td>
        <ul>
            <?php foreach ($info['detalles'] as $detalle) { ?>
                <li><?php echo $detalle['precio_unitario'].' $ '; ?></li>
            <?php } ?>
        </ul>
    </td>
    
    <td class="text-center" ><?php echo $info['total'].' $ '; ?></td>
    <?php if( $tipo_usuario == 1) { ?>
    <td>
        <form action="?pagina=venta">
                <button type="button" onclick="eliminar(this)" class="btn btn-danger btn-sm eliminar" value="<?php echo $id_venta; ?>"> <i class="fas fa-trash-alt" title="Eliminar"> </i> Eliminar </button>
       </form>
    </td>
    <?php } ?>
</tr>
<?php } ?>
</tbody>
    </table>
</div>
</div>
</div>

<!-- |||||||||||||||||||||||||   TASA DOLAR   |||||||||||||||||||||||||||||||||| -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasRightLabel"><i class="fa-solid fa-money-bill-transfer"></i> Actualizar Tasa del Dolar</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <form action="?pagina=venta" id="t" autocomplete="off">

      <label class="form-label text-dark">Precio $ A Bs</label>
      <input type="text" class="form-control text-dark" name="precios" id="inputtasadolar" placeholder="0.00 "  required>
      <span id="textotasadolar"  class="vali-span"></span> 
      <input type="hidden" name="id_dolar" value="1"> <br>

      <button type="button" class="btn btn-success text-dark" id="tasadolarre">
      <i class="fa-solid fa-money-bill-transfer"></i> Actualizar Tasa</button>
      <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas" aria-label="Close">Cerrar</button>
    </form>
  </div>
</div>


<!-- |||||||||||||||||||||||||   MODAL DE REGISTRO  |||||||||||||||||||||||||||||||||| -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
    <div class="modal-header encabezado-modal">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel"  style="color:rgb(255, 255, 255);">
        <i class="fa-solid fa-cash-register m-2" style="color:rgb(255, 255, 255);"> </i> Registro de Venta
        </h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color:rgb(255, 255, 255);"></button>
      </div>

      <div class="modal-body">
      <form action="?pagina=venta" autocomplete="off" id="formularioventa"> <!--comienzo Formulario-->
<!-- ||||||||||||||||||  F1||||||||||||||||||||||||-->             
      <div class="row ">

        <div class="col-sm-8">
          <label for="" class="text-dark"> * Producto</label>
          <div class="input-group mb-3">
             <label class="input-group-text" for="inputGroupSelect01"><i class="fa-solid fa-boxes-stacked" style="color: #EF233C;"></i></label>
              <select class="form-select text-dark" aria-label="Default select example" name="selectproducto" id="id_producto">
                <option value="">Seleccione un Producto:</option>
                     <?php foreach($producto as $producto) { ?>
                <option value="<?php echo $producto['id_producto']; ?>" 
                    data-stock="<?php echo $producto['stock']; ?>" 
                    data-precio="<?php echo $producto['precio_unitario']; ?>"
                    data-preciomayor="<?php echo $producto['precio_mayor']; ?>"
                    data-cantidad="<?php echo $producto['cantidad_mayor']; ?>">
                    <?php echo $producto['nombre']." - ".$producto['nombre_marca']." - ".$producto['nombre_presentacion']; ?>
                </option>
                  <?php } ?>
              </select>
          </div>
        </div>

         <div class="col-sm-2">
            <label for="" class="text-dark">* Cantidad</label>
            <input type="text" id="cantidadproducto" class="form-control text-dark border border-secondary" placeholder="Cantidad:"> 
        </div>
        <div class="col">
        <label for="" class="text-dark">Añadir a la venta</label>
          <button type="button" class="btn btn-primary" id="agregar"><i class="fa-solid fa-cart-plus"></i>Agregar</button>
        </div>
      </div>

<!-- ||||||||||||||||||  F2 ||||||||||||||||||||||||-->        
<div class="row text-dark">
  <div class="col-sm-2 stock-container">  
    <div class="input-group input-group-sm mb-3">
      <label class="label-letras">Stock:</label>
        <span class="input-group-text" id="inputGroup-sizing-sm label-fondo" ><i class="fa-solid fa-boxes-stacked" style="color: #EF233C;"></i></span>
        <input type="text" id="stock" placeholder="Stock" class="form-control text-dark" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" disabled>
    </div>


</div>
  <div class="col-sm-2 precio-container">
  <div class="input-group input-group-sm mb-3">
      <label class="label-letras ">Precio D:</label>
        <span class="input-group-text" id="inputGroup-sizing-sm"><i class="fa-solid fa-dollar-sign"style="color: #EF233C;"></i></span>
        <input type="text" id="precio" placeholder="Precio" class="form-control text-dark" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" disabled>
    </div>
  </div>

  <div class="col-sm-2 precio-container">
  <div class="input-group input-group-sm mb-3">
      <label class="label-letras ">Precio M:</label>
        <span class="input-group-text" id="inputGroup-sizing-sm"><i class="fa-solid fa-dollar-sign"style="color: #EF233C;"></i></span>
        <input type="text" id="preciomayor" placeholder="Precio" class="form-control text-dark" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" disabled>
        <input type="hidden" id="cantidadomayor">
      </div>
  </div>

  
  <div class="col-sm-4 precio-container">
  <div class="input-group input-group-sm mb-3">
        <span class="input-group-text" id="inputGroup-sizing-sm "><i class="fa-solid fa-delete-left"style="color:rgb(0, 0, 0);"></i></span>
        <button type="button" class="btn btn-outline-secondary" id="limpiardatos">Limpiar Los Datos</button>
      </div>
  </div>

</div>

<!-- ||||||||||||||||||  F3 ||||||||||||||||||||||||-->  
<hr>
<div class="row">
    <div class="col">
      <div class="d-grid gap-2 d-md-block">
        <button class="btn btn-outline-danger btn-sm" type="button" id="limpiarregistro" data-swal-toast-template="#my-template">Limpiar Registro</button>
      </div>
    </div>
    <div class="col-sm-4 fecha-container">
        <div class="input-group input-group-sm mb-3">
           <label class="label-letras  text-dark"> * Fecha de la venta:</label>
            <input type="date" id="fechaventa" class="form-control text-dark" aria-label="Sizing example input"
                aria-describedby="inputGroup-sizing-sm" required style="borde:2px" disabled>
            <input type="hidden" name="fecha" id="ventafecha">
        </div>
    </div>
</div>
<!-- ||||||||||||||||||  F4 ||||||||||||||||||||||||-->  
<div class="table-responsive">  
        <table class="table mt-3 table-bordered">
    <thead class="table-info">
        <tr>
            <th>PRODUCTO</th>
            <th>CANTIDAD</th>
            <th>VENTA AL</th>
            <th>MONTO $</th>
            <th>MONTO Bs</th>
            <th>SUB TOTAL $</th>
            <th>SUB TOTAL Bs</th>
            <th>ACCION</th>
        </tr>
    </thead>
    <tbody id="listadeventa" class="table-group-divider">
        <!-- Aquí se agregarán las filas -->
    </tbody>
    </table>
    </div>

<!-- ||||||||||||||||||  F5 ||||||||||||||||||||||||-->  
<div class="row justify-content-end">
    <div class="col-sm-auto">
        <label class="form-label text-dark">Total ($)</label>
        <div class="input-group mb-3">
             <label class="input-group-text bg-primary" for="inputGroupSelect01"><b style="color: #fff;"> $ </b></label disabled>
            <input type="text" class="form-control text-dark" name="totalUSD" id="totalUSD" placeholder="$ 0.00"  style="width: 150px;"disabled>
            <input type="hidden" class="form-control text-dark" name="total" id="totaldolares">
        </div>
      </div>
    <div class="col-sm-auto">
        <label class="form-label text-dark">Total (Bs)</label>
        <div class="input-group mb-3">
             <label class="input-group-text bg-primary" for="inputGroupSelect01"><b style="color: #fff;"> Bs</b></label>
        <input type="text" class="form-control text-dark" name="totalbs" id="totalBs" placeholder="Bs 0.00"  style="width: 150px; "disabled>  
        </div>   
      </div>    
</div>

<!-- ||||||||||||||||||  BUTTOM |||||||||||||||||||||||-->  
<span id="textoListaDeVenta"  class="vali-span"></span>
<hr>
<div class="button-container">
    <button type="button" class="btn btn-success" id="registrarventa"><i class="fa-regular fa-credit-card"></i> Registrar Venta</button>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
</div>
        </form> <!--fin del formulario-->
      </div>
    
      
    </div>|
  </div>
</div>



  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/moment.js"></script>
  <script src="assets/js/venta.js"></script>
  <?php require_once 'complementos/footer.php'; ?>  
  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->
  

</body>
</html>