async function obtenerTasaDolarApi() {
    try {
        const respuesta = await fetch('https://ve.dolarapi.com/v1/dolares/oficial');
        if (!respuesta.ok) {
            throw new Error(`Error HTTP: ${respuesta.status}`);
        }

        const datos = await respuesta.json();
        const tasaBCV = datos.promedio.toFixed(2); // Redondea la tasa a 2 decimales

        
       
        document.getElementById("inputdolar").textContent = tasaBCV + " Bs";
        document.getElementById("preciobcv").value = tasaBCV;
    } catch (error) {
         document.getElementById("inputdolar").textContent = "0.00";
         document.getElementById("preciobcv").value = "0.00";
    }
}

document.addEventListener("DOMContentLoaded", obtenerTasaDolarApi);




function formatearDecimalAutomatico(input) {
    input.on("keyup", function () {
        let valor = $(this).val().replace(/\D/g, ""); // Elimina todo lo que no sea dígito

        if (valor.length === 0) {
            $(this).val("");
            return;
        }

        // Asegura al menos dos dígitos para los decimales
        while (valor.length < 3) {
            valor = "0" + valor;
        }

        const parteEntera = valor.slice(0, valor.length - 2);
        const parteDecimal = valor.slice(-2);

        const resultado = `${parseInt(parteEntera)}.${parteDecimal}`;
        $(this).val(resultado);
    });
}

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

function validarCampo(campo, regex, textoError, mensaje) {
  const valor = campo.val();

  if (campo.is("select")) {
    if (valor === "") {
      campo.removeClass("is-validd").addClass("is-invalidd");
      textoError.text(mensaje);
      return false;
    } else {
      campo.removeClass("is-invalidd").addClass("is-validd");
      textoError.text("");
      return true;
    }
  } else {
    if (regex.test(valor)) {
      campo.removeClass("is-invalidd").addClass("is-validd");
      textoError.text("");
      return true;
    } else {
      campo.removeClass("is-validd").addClass("is-invalidd");
      textoError.text(mensaje);
      return false;
    }
  }
}

//Función para validar por Keypress
function validarkeypress(er,e){
	key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if(!a){
		e.preventDefault();
    }
}

$(document).ready(function() {
        
   formatearDecimalAutomatico($("#inputtasadolar"));
    $("#inputtasadolar").on("keypress", function(e) {
            validarkeypress(/^[0-9\.\b]*$/, e);
        });
      
      
        $("#inputtasadolar").on("keyup", function() {
            validarCampo($(this),/^[0-9\.]{4,7}$/, 
            $("#textotasadolar"), "Debe haber como mínimo 0.01");
        });

        

     $('#sincronizar').on("click", function () {
        activarLoaderBoton('#sincronizar', "Guardando...");
        var datos = new FormData($('#formdolar')[0]);
        datos.append('tasadolar', 'tasadolar');
        enviaAjax(datos);
      });

      $('#actualizar').on("click", function () {
        if (!validarFormulario()) {
                    muestraMensaje("error", 2000, "Campos inválidos O Vacios", "Por favor, corrige los errores antes de continuar.");
                    return;
                }
            Swal.fire({
                title: '¿Desea Cambiar la Tasa de Cambio?',
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
                activarLoaderBoton('#actualizar', "Guardando...");
                var datos = new FormData($('#dolarform')[0]);
                datos.append('tasadolar', 'tasadolar');
                enviaAjax(datos);
                }
            });
    });

});

function validarFormulario() {
    const resultados = [
        validarCampo($("#inputtasadolar"), /^[0-9\.]{4,7}$/, $("#textotasadolar"), "Debe haber como mínimo 0.01") 
    ];

    return resultados.every(r => r === true);
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
  
          if (lee.accion == 'registrar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Se ha registrado con éxito", "Su registro se ha completado exitosamente");
              desactivarLoaderBoton('#registrar'); 
              setTimeout(function () {
                location = '?pagina=tasa';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", "ERROR");
              desactivarLoaderBoton('#registrar'); 
            }
          } else  if (lee.accion == 'tasadolar') {
            if (lee.respuesta == 1) {
              muestraMensaje("success", 1000, "Tasa Actualiza", "");
              desactivarLoaderBoton('#sincronizar'); 
              desactivarLoaderBoton('#actualizar'); 
              setTimeout(function () {
                location = '?pagina=tasa';
              }, 1000);
            } else {
              muestraMensaje("error", 2000, "ERROR", lee.text);
              desactivarLoaderBoton('#sincronizar'); 
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
        } else {
          muestraMensaje("error", 2000, "Error", "ERROR: <br/>" + request + status + err);
        }
      },
      complete: function () {
      }
    });
  }





