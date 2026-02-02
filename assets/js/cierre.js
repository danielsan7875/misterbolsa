$(document).ready(function(){
   // Formato YYYY-MM-DD HH:mm:ss (Formato de 24 horas)
    var hoy = moment().format('YYYY-MM-DD HH:mm:ss');

    $('#cierrefecha').val(hoy);
    $('#fechaCierreBd').val(hoy);

    $('#iniciar').on("click", function () {
    Swal.fire({
      title: '¿Deseas Iniciar?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#1d994dff',
      cancelButtonColor: '#EF233C',
      confirmButtonText: ' SI ',
      cancelButtonText: 'NO'
    }).then((result) => {
    if (result.isConfirmed) {
       activarLoaderBoton('#iniciar', "Iniciando...");
        var datos = new FormData($('#form_cierre')[0]);
        datos.append('iniciar', 'iniciar');
        enviaAjax(datos);
      }
    });  
});


 $('#btn-cerrar-cierre').on("click", function () {
    Swal.fire({
      title: '¿Deseas Cerrar el Cierre?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#1d994dff',
      cancelButtonColor: '#EF233C',
      confirmButtonText: ' SI ',
      cancelButtonText: 'NO'
    }).then((result) => {
    if (result.isConfirmed) {
       activarLoaderBoton('#btn-cerrar-cierre', "Iniciando...");
        var datos = new FormData($('#cierreBD')[0]);
        datos.append('cierre', 'cierre');
        enviaAjax(datos);
      }
    });  
});


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
           
          if (lee.accion == 'iniciar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1500, "Se ha iniciado con éxito", "Ya puede realizar venta");
                    desactivarLoaderBoton('#iniciar'); 
                      setTimeout(function () {
                        location = '?pagina=cierre';
                      }, 2000);
                } else {
                  muestraMensaje("error", 2000, "Error", lee.text);
                    desactivarLoaderBoton('#iniciar'); 
                }
          } else if (lee.accion == 'cierre') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1500, "Se ha Cerrado con éxito", "Registro del cierre guardada");
                    desactivarLoaderBoton('#btn-cerrar-cierre'); 
                      setTimeout(function () {
                        location = '?pagina=cierre';
                      }, 2000);
                } else {
                  muestraMensaje("error", 2000, "Error", lee.text);
                     desactivarLoaderBoton('#btn-cerrar-cierre'); 
                } 
          }
  
        } catch (e) {
          muestraMensaje("error", 2000, "Error en JSON ", e.name);
        }
      },
      error: function (request, status, err) { 
        Swal.close();
        if (status == "timeout") {
          muestraMensaje("error", 2000, "Error", "Servidor ocupado, intente de nuevo");
           desactivarLoaderBoton('#iniciar'); 
           desactivarLoaderBoton('#btn-cerrar-cierre'); 
        } else {
          muestraMensaje("error", 2000, "Error", "ERROR: <br/>" + request + status + err);
          desactivarLoaderBoton('#iniciar'); 
          desactivarLoaderBoton('#btn-cerrar-cierre'); 
        }
      },
      complete: function () {
      }
    });
  }

