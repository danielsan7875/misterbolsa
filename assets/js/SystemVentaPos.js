// BUCARDAOR DE PRODUCTOS
$(document).ready(function() {
    $("#inputBuscar").on("keyup", function() {
        var valorBusqueda = $(this).val().toLowerCase().trim();
        var productosVisibles = 0;

        $(".producto-item").each(function() {
            // Obtenemos el texto del nombre del producto
            var nombreProducto = $(this).find(".nombre-producto").text().toLowerCase();

            // Verificamos si el valor buscado está incluido en el nombre
            if (nombreProducto.indexOf(valorBusqueda) > -1) {
                $(this).fadeIn(200); // Mostramos con una transición suave
                productosVisibles++;
            } else {
                $(this).fadeOut(100); // Ocultamos
            }
        });

        // Lógica para mostrar el mensaje de "No se encuentra nada"
        if (productosVisibles === 0 && valorBusqueda !== "") {
            $("#mensajeVacio").removeClass("d-none");
        } else {
            $("#mensajeVacio").addClass("d-none");
        }
    });

    // ------------------------------------- CARRITO----------------------
      let carrito = [];

    // AGREGAR AL CARRITO CON VALIDACIÓN DE STOCK
    $(".btn-agregar").on("click", function() {
        const id = $(this).data("id");
        const nombre = $(this).data("nombre");
        const precioDetal = parseFloat($(this).data("precio-detal"));
        const precioMayor = parseFloat($(this).data("precio-mayor"));
        const cantMayor = parseInt($(this).data("cant-mayor"));
        const stockMax = parseInt($(this).data("stock"));

        if (stockMax <= 0) {
              muestraMensaje("error", 1500, "Sin Stock","Este producto no tiene existencias disponibles.");
            return;
        }

        const existe = carrito.find(item => item.id === id);

        if (existe) {
            if (existe.cantidad >= stockMax) {
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Límite alcanzado',
                    text: 'No puedes añadir más de lo que hay en stock (' + stockMax + ').',
                    backdrop: true, 
                     heightAuto: false,
                    confirmButtonColor: '#0d6efd'
                });
                
                
                return;
            }
            existe.cantidad++;
        } else {
            carrito.push({ 
                id, nombre, precioDetal, precioMayor, cantMayor, stockMax,
                cantidad: 1 
            });
        }
        actualizarInterfaz();
    });

    // CONTROLES DEL CARRITO
    $("#lista-carrito").on("click", ".btn-control", function() {
        const id = $(this).data("id");
        const accion = $(this).data("accion");
        const item = carrito.find(p => p.id === id);

        if (accion === 'mas') {
            if (item.cantidad < item.stockMax) {
                item.cantidad++;
            } else {
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Máximo',
                    text: 'Solo hay ' + item.stockMax + ' unidades disponibles.',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                      backdrop: true, 
                     heightAuto: false,
                    timer: 3000
                });
                
              
            }
        } else if (accion === 'menos') {
            if (item.cantidad > 1) item.cantidad--;
            else eliminarDelCarrito(id);
        } else if (accion === 'eliminar') {
            eliminarDelCarrito(id);
        }
        actualizarInterfaz();
    });

    // VACIAR CARRITO CON CONFIRMACIÓN
    $("#vaciar-carrito").on("click", function() {
        if (carrito.length === 0) return;

        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se eliminarán todos los productos del carrito.",
            icon: 'warning',
              backdrop: true, 
             heightAuto: false,
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, vaciar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                carrito = [];
                actualizarInterfaz();
                Swal.fire({
                    title: 'Vaciado',
                    text: 'El carrito ha sido limpiado.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    });

    function eliminarDelCarrito(id) {
        carrito = carrito.filter(item => item.id !== id);
    }

    // INTERFAZ Y LÓGICA DE PRECIOS + INPUTS HIDDEN
    function actualizarInterfaz() {
        const $contenedor = $("#lista-carrito");
        const $inputsContenedor = $("#inputs-ocultos");
        
        $contenedor.empty();
        $inputsContenedor.empty();
        
        let totalGeneral = 0;
        let itemsTotales = 0;

        if (carrito.length === 0) {
            $contenedor.html('<p class="text-center text-muted my-4">El carrito está vacío</p>');
            $("#registrar").prop("disabled", true);
        } else {
            $("#registrar").prop("disabled", false);
        }

        carrito.forEach(item => {
            // Lógica de precio al mayor
            let aplicarMayor = (item.cantMayor > 0 && item.cantidad >= item.cantMayor);
            let precioAplicado = aplicarMayor ? item.precioMayor : item.precioDetal;
            let modoVenta = aplicarMayor ? "Mayor" : "Detal";
            
            const subtotal = precioAplicado * item.cantidad;
            totalGeneral += subtotal;
            itemsTotales += item.cantidad;

            // 1. Renderizar visual en el carrito
            const html = `
                <div class="cart-item d-flex justify-content-between align-items-center border-bottom py-2">
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn btn-link text-muted p-0 btn-control" data-id="${item.id}" data-accion="eliminar">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </button>
                        <div class="bg-light rounded p-2 text-center" style="width: 40px;">
                            <i class="fa-solid fa-boxes-stacked text-secondary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fs-6 fw-medium">${item.nombre}</h6>
                            <small class="${aplicarMayor ? 'text-success fw-bold' : 'text-muted'}">
                                $${precioAplicado.toFixed(2)} ${aplicarMayor ? '(Mayor)' : 'u/d'}
                            </small>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1">
                        <div class="qty-control d-flex align-items-center border rounded">
                            <button type="button" class="btn btn-sm btn-control px-2" data-id="${item.id}" data-accion="menos">-</button>
                            <span class="fw-bold small px-2" style="min-width: 25px; text-align: center;">${item.cantidad}</span>
                            <button type="button" class="btn btn-sm btn-control px-2" data-id="${item.id}" data-accion="mas" ${item.cantidad >= item.stockMax ? 'disabled' : ''}>+</button>
                        </div>
                        <span class="fw-bold text-primary small">$${subtotal.toFixed(2)}</span>
                         <input type="hidden" name="id_producto[]" value="${item.id}">
                            <input type="hidden" name="cantidad[]" value="${item.cantidad}">
                            <input type="hidden" name="precio_unitario[]" value="${precioAplicado}">
                            <input type="hidden" name="maode[]" value="${modoVenta}">
                    </div>
                </div>
            `;
            $contenedor.append(html);

            // 2. Generar inputs ocultos para el formulario (ARREGLOS PARA PHP)
            $inputsContenedor.append(`
                <input type="hidden" name="id_producto[]" value="${item.id}">
                <input type="hidden" name="cantidad[]" value="${item.cantidad}">
                <input type="hidden" name="precio_unitario[]" value="${precioAplicado}">
                <input type="hidden" name="maode[]" value="${modoVenta}">
            `);
        });

        // Actualización de Totales en Dólares
        $("#total-dolares").text(`$${totalGeneral.toFixed(2)}`);
        $("#total_dl").val(`${totalGeneral.toFixed(2)}`);
        $("#monto_dl").val(`${totalGeneral.toFixed(2)}`);

        
        // CÁLCULO DE CAMBIO A BS
        const tasaElemento = document.getElementById('tasadecambio');
        // Validamos si es un input (value) o un elemento de texto (textContent)
        let tasa = tasaElemento ? parseFloat(tasaElemento.value || tasaElemento.textContent) : 0;
        
        if (isNaN(tasa)) tasa = 0;
        console.log(tasa);
        const totalBs = totalGeneral * tasa;
        $(".grand-total").text(`Bs. ${totalBs.toFixed(2)}`);
        $("#total_bs").val(`${totalBs.toFixed(2)}`);
        $("#monto_bs").val(`${totalBs.toFixed(2)}`);



        $("#cart-count-badge").text(itemsTotales);
    }

    //--------------------------------------------- FECHA GUARDAR -----------------

    $('#fecha').val(new Date().toISOString().slice(0, 10));

    //--------------------------------------------- METODO PAGO --------------------

      $('.payment-method-card').on('click', function () {

        // Quitar y agregar clase active
        $('.payment-method-card').removeClass('active');
        $(this).addClass('active');

        // Obtener ID del método
        let metodoId = $(this).data('id');

        // Guardar ID en input hidden
        $('#metodopago_id').val(metodoId);

        // Mostrar campo referencia solo para método 1 o 2
        if (metodoId == 1 || metodoId == 2) {
            $('#contenedorReferencia').removeClass('d-none');
        } else {
            $('#contenedorReferencia').addClass('d-none');
            $('#referencia_visible').val('');
            $('#referencia').val('');
        }
    });

    // Validación referencia (solo números, 4 a 6 dígitos)
    $('#referencia_visible').on('input', function () {
        let valor = $(this).val();

        // Eliminar cualquier carácter que no sea número
        valor = valor.replace(/\D/g, '');

        // Limitar a 6 caracteres
        if (valor.length > 6) {
            valor = valor.substring(0, 6);
        }

        $(this).val(valor);

        // Validar longitud (4 a 6)
        if (valor.length >= 4 && valor.length <= 6) {
            $('#referencia').val(valor);
        } else {
            $('#referencia').val('');
        }
    });

    //----------------------------------------- REGISTRO VENTA AJAX ------------------

    $('#registrar').on("click", function () {
        activarLoaderBoton('#registrar', "Guardando...");

        if (carrito.length === 0) {
            muestraMensaje("error", 1000, "Carrito Vacios", "Seleccionar un producto");
            desactivarLoaderBoton('#registrar');
        } else if (!validarMetodoPago()) {
            muestraMensaje("error", 1500, "Seleccionar un metodo de pago", "");
            desactivarLoaderBoton('#registrar');
        }else {

            Swal.fire({
            title: '¿Deseas Registrar esta Venta?',
            text: "",
            icon: 'question',
              backdrop: true, 
             heightAuto: false,
            showCancelButton: true,
            confirmButtonColor: '#19863e',
            cancelButtonColor: '#d33b3b',
            confirmButtonText: 'Sí, Registrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {

               bloquearPantalla();
                var datos = new FormData($('#SystemPos')[0]);
                datos.append('registrar', 'registrar');
                enviaAjax(datos);
            
            }
            desactivarLoaderBoton('#registrar');
        });
      
            
            
        }
    });

    //---------------------------- FUNCION DESBLOQUEAR PANTALLA -----------------

    desbloquearPantalla();

     //---------------------------- FUNCION VALIDAR PAGO -----------------
    
    function validarMetodoPago() {
        let metodoId = $('#metodopago_id').val();
        let referencia = $('#referencia').val();
        // 1. Método no seleccionado
        if (!metodoId || metodoId === '') {
            return false;
        }
        // 2. Métodos que requieren referencia
        if (metodoId == 1 || metodoId == 2) {

            if (!referencia || referencia === '') {
                muestraMensaje("error", 1500, "La referencia debe tener entre 4 y 6 números", "");
                return false;
            }

            // 3. Validar formato (4 a 6 dígitos)
            let regex = /^[0-9]{4,6}$/;
            if (!regex.test(referencia)) {
                muestraMensaje("error", 1500, "La referencia debe tener entre 4 y 6 números", "");
                return false;
            }
        }
        return true;
    }
});




function activarLoaderBoton(idBoton, texto) {
    const $boton = $(idBoton);
    const textoActual = $boton.html();
    $boton.data('texto-original', textoActual); // Guarda el texto original
    $boton.prop('disabled', true);
    $boton.html(`<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${texto}`);
}

function desactivarLoaderBoton(idBoton) {
    const $boton = $(idBoton);
    const textoOriginal = $boton.data('texto-original');
    $boton.prop('disabled', false);
    $boton.html(textoOriginal);
}


function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
      icon: icono,
      timer: tiempo,
      title: titulo,
      html: mensaje,
      showConfirmButton: false,
    backdrop: true, 
    heightAuto: false,
    });
  }


    function bloquearPantalla() {
        $('#overlay-bloqueo').fadeIn(200);
        $('body').css('overflow', 'hidden'); // bloquear scroll
    }

    function desbloquearPantalla() {
        $('#overlay-bloqueo').fadeOut(200);
        $('body').css('overflow', 'auto');
    }


  
  function enviaAjax(datos) {
    $.ajax({
      async: true,
      url: "",
      type: "POST",
      contentType: false,
      data: datos,
      processData: false,
      cache: false,
      beforeSend: function () { },
      timeout: 10000,
      success: function (respuesta) {
        console.log(respuesta);
        var lee = JSON.parse(respuesta);
        try {
  
          if (lee.accion == 'registrar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1500, "Se ha registrado con éxito", "Su registro se ha completado exitosamente");
              desactivarLoaderBoton('#registrar');
              desbloquearPantalla(); 
              setTimeout(function () {
                location = '?pagina=registroventa';
              }, 1500);
            } else {
              muestraMensaje("error", 2000, "ERROR", "ERROR");
              desbloquearPantalla(); 
              desactivarLoaderBoton('#registrar'); 
            }

          } else if (lee.accion == 'actualizar') { // falta
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Se ha Modificado con éxito", "Su registro se ha Actualizado exitosamente");
              desactivarLoaderBoton('#actualizar');
              setTimeout(function () {
                location = '?pagina=producto';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "Intente Nuevamente O Mas Tarde");
              desactivarLoaderBoton('#actualizar');
            }
          }
  
        } catch (e) {
          alert("Error en JSON " + e.name);
        }
      },
      error: function (request, status, err) {
        Swal.close();
        if (status == "timeout") {
          muestraMensaje("error", 2000, "Error", "Servidor ocupado, intente de nuevo");
             desactivarLoaderBoton('#registrar'); 
        } else {
          muestraMensaje("error", 2000, "Error", "ERROR: <br/>" + request + status + err);
             desactivarLoaderBoton('#registrar'); 
        }
      },
      complete: function () {
      }
    });
  }