// BUCARDAOR DE PRODUCTOS
$(document).ready(function() {

    // ------------------------------------- CARRITO----------------------
      let carrito = [];

    // AGREGAR AL CARRITO CON VALIDACIÓN DE STOCK
     $(document).on("click", ".btn-agregar", function() {
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
                            <span class="fw-bold small px-2 TextOscuroTitel2" style="min-width: 25px; text-align: center;">${item.cantidad}</span>
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
        $("#summary-total-usd").text(`$ ${totalGeneral.toFixed(2)}`);

        
        // CÁLCULO DE CAMBIO A BS
        const tasaElemento = document.getElementById('tasadecambio');
        // Validamos si es un input (value) o un elemento de texto (textContent)
        let tasa = tasaElemento ? parseFloat(tasaElemento.value || tasaElemento.textContent) : 0;
        
        if (isNaN(tasa)) tasa = 0;
        console.log(tasa);
        const totalBs = totalGeneral * tasa;
        $(".grand-total").text(`Bs. ${totalBs.toFixed(2)}`);
        $("#total_bs").val(`${totalBs.toFixed(2)}`);
        $("#summary-total-bs").text(`Bs. ${totalBs.toFixed(2)}`);



        $("#cart-count-badge").text(itemsTotales);
    }

    //--------------------------------------------- FECHA GUARDAR -----------------

    $('#fecha').val(moment().format('YYYY-MM-DD'));



    //----------------------------------------- REGISTRO VENTA AJAX ------------------

    $('#btn-process').on("click", function () {
        activarLoaderBoton('#btn-process', "Procesando...");

        if (carrito.length === 0) {
            muestraMensaje("error", 1000, "Carrito Vacios", "Seleccionar un producto");
            desactivarLoaderBoton('#registrar');
        } else if (verificarPagosCompletos()) {

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
                desactivarLoaderBoton('#btn-process');
            });
    
        } else {
            muestraMensaje("error", 1000, "Metodo de Pago Vacio", "Seleccionar un metodo de pago");
             desactivarLoaderBoton('#btn-process');
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

    function guardarHoraActual() {
        const ahora = new Date();

        const hora = String(ahora.getHours()).padStart(2, '0');
        const minutos = String(ahora.getMinutes()).padStart(2, '0');

        $('#hora').val(`${hora}:${minutos}`);
    }
    guardarHoraActual();



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


   function bloquearPantalla() {
        $('#overlay-bloqueo').fadeIn(200);
        $('body').css('overflow', 'hidden'); // bloquear scroll
    }

    function desbloquearPantalla() {
        $('#overlay-bloqueo').fadeOut(200);
        $('body').css('overflow', 'auto');
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
        ocultarLoaderBuscador();
          if (lee.accion == 'registrar') {
            if (lee.respuesta == 1) {

              desactivarLoaderBoton('#registrar');
                desbloquearPantalla(); 
                    resetVenta();
            } else {
              desbloquearPantalla();   
                muestraMensaje("error", 3000, "Error", lee.text);
                    desactivarLoaderBoton('#registrar'); 
            }

          } else if (lee.accion == 'buscarProducto')  {
                if(lee.respuesta == 1){
                    muestraMensaje("error", 3000, "Error", lee.text);
                       ocultarLoaderBuscador();
                }else{
                    ocultarLoaderBuscador();
                    renderProductos(lee.respuesta);
                }
              
          }
  
        } catch (e) {
           muestraMensaje("error", 4000, "Error en JSON ", e.name);
        }
      },
      error: function (request, status, err) {
        Swal.close();
        if (status == "timeout") {
          muestraMensaje("error", 2000, "Error", "Servidor ocupado, intente de nuevo");
             desactivarLoaderBoton('#registrar'); 
             ocultarLoaderBuscador();

        } else {
          muestraMensaje("error", 2000, "Error", "ERROR: <br/>" + request + status + err);
             desactivarLoaderBoton('#registrar'); 
             ocultarLoaderBuscador();

        }
      },
      complete: function () {
      }
    });
  }

  // Seleccionamos los elementos
const origen = document.getElementById('tasadecambio'); // El que tiene el valor
const destino = document.getElementById('tasacofing'); // Donde lo quieres pegar

// Escuchamos cuando escribes o pegas algo
origen.addEventListener('input', () => {
    destino.value = origen.value;
});


document.addEventListener('DOMContentLoaded', function () {

    const modoKey = 'modoOscuro_' + idUsuario;
    const switchModo = document.getElementById('toggleModo');

    // Leer estado guardado
    const modoActivo = localStorage.getItem(modoKey);

    if (modoActivo === 'activo') {
        document.body.classList.add('modo-oscuro');
        switchModo.checked = true;
    }

    // Evento cambio del switch
    switchModo.addEventListener('change', function () {

        if (this.checked) {
            activarModoOscuro(modoKey);
        } else {
            desactivarModoOscuro(modoKey);
        }

    });

});

function activarModoOscuro(modoKey) {
    document.body.classList.add('modo-oscuro');
    localStorage.setItem(modoKey, 'activo');
}

function desactivarModoOscuro(modoKey) {
    document.body.classList.remove('modo-oscuro');
    localStorage.setItem(modoKey, 'inactivo');
}



$(document).ready(function () {

    // 🔹 CARGA INICIAL → últimos 50 productos
    cargarProductosInicial();

    // 🔹 BUSCADOR DINÁMICO
 let temporizadorBusqueda;

$('#inputBuscar').on('input', function () {

    clearTimeout(temporizadorBusqueda);
    let texto = $(this).val();
    texto = texto.replace(/[^a-zA-Z0-9\s\-#]/g, '');
    $(this).val(texto);

    temporizadorBusqueda = setTimeout(function () {
        buscarProductoAjax(texto.trim());
    }, 1500);

});



});


  function mostrarLoaderBuscador() {
    $('#loaderBuscador').removeClass('d-none');
    }

    function ocultarLoaderBuscador() {
        $('#loaderBuscador').addClass('d-none');
    }


/* =====================================================
   🔹 CARGA INICIAL DE PRODUCTOS
===================================================== */

function cargarProductosInicial() {

    const datos = new FormData();
    datos.append('buscarProducto', '');

    enviaAjax(datos);
}


/* =====================================================
   🔹 BUSCAR PRODUCTOS AJAX
===================================================== */

function buscarProductoAjax(texto) {
       mostrarLoaderBuscador();
    const datos = new FormData();
    datos.append('buscarProducto', texto);

    enviaAjax(datos);
}


/* =====================================================
   🔹 MODIFICACIÓN DE TU enviaAjax()
   👉 Solo debes AÑADIR este bloque dentro del success
===================================================== */

// Dentro de tu success(respuesta) agrega:

/*
if (lee.accion == 'buscarProducto') {
    renderProductos(lee.respuesta);
}
*/


/* =====================================================
   🔹 RENDER DE CARDS (RESPETA TU DISEÑO)
===================================================== */

function renderProductos(productos) {

    let html = '';

    $('#contenedorProductos').html('');
    $('#mensajeVacio').addClass('d-none');

    if (!productos || productos.length === 0) {
        $('#mensajeVacio').removeClass('d-none');
        return;
    }

    productos.forEach(p => {

        html += `
        <div class="col producto-item TA7">
            <div class="product-card DivOscuro2">
                <div class="product-img">
                    <i class="fa-solid fa-boxes-stacked TextOscuroTitel2" style="font-size: 28px;"></i>
                </div>

                <div class="p-3 d-flex flex-column flex-grow-1">

                    <h6 class="fw-semibold mb-1 text-truncate nombre-producto">
                        ${p.nombre} | ${p.nombre_marca}
                    </h6>

                    <span class="text-muted mb-1">
                        Presentacion: ${p.nombre_presentacion}
                    </span>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="stock-badge ${p.stock <= 0 ? 'text-danger fw-bold' : ''}">
                            Stock: <span class="num-stock">${p.stock}</span>
                        </span>

                        ${p.cantidad_mayor > 0 ? `<small class="text-muted">Mayor: ${p.cantidad_mayor}u.</small>` : ''}
                    </div>

                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary fs-5">$${parseFloat(p.precio_unitario).toFixed(2)}</span>

                        ${p.precio_mayor > 0 ? `<small class="text-secundary">Mayor: $${parseFloat(p.precio_mayor).toFixed(2)}</small>` : ''}

                        <button class="btn btn-sm btn-outline-primary p-2 btn-agregar TA8"
                            data-id="${p.id_producto}"
                            data-nombre="${p.nombre}"
                            data-precio-detal="${p.precio_unitario}"
                            data-precio-mayor="${p.precio_mayor}"
                            data-cant-mayor="${p.cantidad_mayor}"
                            data-stock="${p.stock}">
                            Añadir <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>

                </div>
            </div>
        </div>`;

    });

    $('#contenedorProductos').html(html);
}
