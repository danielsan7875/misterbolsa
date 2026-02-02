<!doctype html>
<html lang="en">

<head>
    <?php require_once 'vista/complementos/head2.php'; ?>
    <title>Modificar Producto | Mister Bolsa C.A</title>
</head>

<body>
  
  <?php require_once 'vista/complementos/nav2.php'; ?>
  <?php require_once 'vista/complementos/sidebar2.php'; ?>
 
  <div class="row">
    
      <div class="d-sm-flex align-items-center justify-content-between mb-5">
            <h1 class="h3 mb-0 text-gray-900 texto-principal"> <i class="fa-solid fa-boxes-stacked me-3" style="color: #E63946;"></i>
            Modificar</h1>
                
            <!-- Button que abre el Modal N1 Registro -->
                <a href="?pagina=producto" class="btn btn-primary">
                <span class="icon text-white-100">
                <i class="fa-solid fa-reply me-2"></i>
                  </span>
                  <span class="text ext-white-100">Volver</span>
            </a>
      </div>

  </div>  
    
  <div class="chart-container shadow-sm border-0 DivOscuro1">

      <form action="?pagina=producto" id="m" autocomplete="off"> <!--comienzo Formulario-->
         <div class="seccion-formulario">
        <div class="row ">
           <h6><i class="fas fa-boxes"></i> Datos del Producto </h6>

          <div class="col-sm">
          <label class="form-label text-dark">Nombre</label>
            <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"> <i class="fa-solid fa-bag-shopping" style="color:#FF3C37;"></i> </span>
            <input type="text" class="form-control text-dark" name="nombre" id="nombre" value="<?php echo $modificar['nombre']?>"  required>     
          </div>
          <span id="textonombre"  class="text-error mt-0"></span>
          </div>

          <div class="col-sm-2">
               <label class="form-label text-dark">#</label>
              <input type="text" class="form-control text-dark" value ="<?php echo $modificar ['id_producto']?>" disabled>
              <input type="hidden"  value ="<?php echo $modificar ['id_producto']?>" name="id_producto">
            </div>
             

        </div>

        <div class="row ">
          <div class="col-sm">
          <label class="form-label text-dark">Marca</label>
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-tag " style="color:#FF3C37;"></i></span>
          <select class="form-select text-dark" aria-label="Default select example"  name="marca" id="marcaselect" required>
          <option value="<?php echo $modificar['id_marca']; ?>"><?php echo $modificar['nombre_marca']." - actual"; ?></option>
              
           </select> 
           </div>
            <span id="textomarca"  class="text-error mt-0"></span> 
          </div>

          <div class="col-sm">
          <label class="form-label text-dark">Presentacion</label>
          <div class="input-group mb-3">
          <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-shapes" style="color:#FF3C37;"></i></span>
           <select class="form-select text-dark" name="presentacion" id="presenselect" required>
               <option value="<?php echo $modificar['id_presentacion']; ?>"><?php echo $modificar['nombre_presentacion']." - actual"; ?></option>

            </select> 
           </div>  
           <span id="textopresentacion"  class="text-error mt-0"></span> 
          </div>
          </div>
        </div>
      
        <div class="seccion-formulario">
        <div class="row ">
            <h6><i class="fas fa-boxes"></i> Precio del Producto en $ </h6>
          <div class="col-sm">
          <label class="form-label text-dark">Precio Al Detal ($)</label>
            <div class="input-group mb-3">
               <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-dollar-sign" style="color:#FF3C37;"></i></span>
               <input type="text" class="form-control text-dark" name="precio" id="precio" value ="<?php echo $modificar ['precio_unitario']?>"  required>  
            </div>
            <span id="textoprecio"  class="text-error mt-0"></span> 
          </div>

         
          
                  <div class="col-sm">
          <label class="form-label text-dark">Precio al Mayor($)</label>
            <div class="input-group mb-3">
               <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-dollar-sign" style="color:#FF3C37;"></i></span>
               <input type="text" class="form-control text-dark" name="preciomayor" id="preciomayor" value="<?php echo $modificar['precio_mayor']?>"  required>  
            </div>
            <span id="textopreciomayor"  class="text-error mt-0"></span> 
          </div>
        </div>
        </div>

    <div class="seccion-formulario">
        <div class="row">
            <h6><i class="fas fa-boxes"></i> Otros datos </h6>
           <div class="col-sm">
          <label class="form-label text-dark">Stock Minimo</label> 
            <div class="input-group mb-3">
               <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-box" style="color:#FF3C37;"></i></span>
                <input type="text" class="form-control text-dark"  name="stock_min" id="stock" value ="<?php echo $modificar ['stock_minimo']?>" required>  
            </div>
        <span id="textostock"  class="text-error mt-0"></span>
          </div>

          <div class="col-sm">
          <label class="form-label text-dark">Cantidad Al Mayor</label>
            <div class="input-group mb-3">
               <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-box" style="color:#FF3C37;"></i></span>
                <input type="text" class="form-control text-dark"  name="cantidadmayor" id="cantidadmayor" value="<?php echo $modificar['cantidad_mayor']?>" required>  
            </div>
        <span id="textocantidadmayor"  class="text-error mt-0"></span> 
          </div>
             
        </div>
        </div>

<input type="hidden" value="<?php echo $modificar['stock']?>" name="stock">
<input type="hidden" value="<?php echo $modificar['estatus']?>" name="estatus">
<hr>
<div class="d-flex justify-content-center gap-3 my-4">
   <button type="reset" class="btn btn-lg btn-limpiar" name="limpiar"><i class="fa-solid fa-repeat me-2"></i>Limpiar</button>
    <button type="button" class="btn btn-lg btn-guardar" id="actualizar"> <i class="fa-solid fa-floppy-disk me-2"></i> Actualizar Datos</button>
   
</div>
        </form> <!--fin del formulario-->
      </div>
 
  <!--|||||||||||||||  Footer y JS  |||||||||||||||||||||||||||-->
  <script src="assets/js/producto.js"></script>
  <?php require_once 'vista/complementos/footer2.php'; ?>  

  <!--|||||||||||||||  FIN Footer y JS  |||||||||||||||||||||||||||-->   
  
  
  <script>
    $(document).ready(function() {
    if (!$('#marcaselect').hasClass('select2-hidden-accessible')) {

    $('#marcaselect').select2({
        width: '100%',
        placeholder: 'Seleccione o busque una marca',
        allowClear: true,

        ajax: {
            delay: 300,

            transport: function (params, success, failure) {

                let datos = new FormData();
                datos.append('ajaxMarca', '1');
                datos.append('buscarMarca', params.data.q ? params.data.q : '');

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: datos,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: success,
                    error: failure
                });
            },

            processResults: function (data) {
                return {
                    results: data.map(function (m) {
                        return {
                            id: m.id_marca,
                            text: m.nombre
                        };
                    })
                };
            }
        },

        language: {
            inputTooShort: function () {
                return 'Escriba para buscar marcas';
            },
            searching: function () {
                return 'Buscando marcas…';
            },
            noResults: function () {
                return 'No se encontraron marcas';
            }
        }
    });

    $('#marcaselect').on('select2:open', function () {
        $('input.select2-search__field').attr('placeholder', 'Buscador');
    });

}


 if (!$('#presenselect').hasClass('select2-hidden-accessible')) {

    $('#presenselect').select2({
        width: '100%',
        placeholder: 'Seleccione o busque una presentacion',
        allowClear: true,

        ajax: {
            delay: 300,

            transport: function (params, success, failure) {

                let datos = new FormData();
                datos.append('ajaxPresentacion', '1');
                datos.append('buscarPresentacion', params.data.q ? params.data.q : '');

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: datos,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: success,
                    error: failure
                });
            },

            processResults: function (data) {
                return {
                    results: data.map(function (m) {
                        return {
                            id: m.id_presentacion,
                            text: m.nombre
                        };
                    })
                };
            }
        },

        language: {
            inputTooShort: function () {
                return 'Escriba para buscar presentacion';
            },
            searching: function () {
                return 'Buscando presentacion';
            },
            noResults: function () {
                return 'No se encontraron presentacion';
            }
        }
    });

    $('#presenselect').on('select2:open', function () {
        $('input.select2-search__field').attr('placeholder', 'Buscador');
    });

}





   });
  </script>
</body>
</html>