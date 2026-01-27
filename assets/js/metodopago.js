
function desactivar(boton){
    Swal.fire({
      title: '¿Desea Desactivar?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#14961aff',
      cancelButtonColor: '#EF233C',
      confirmButtonText: ' SI ',
      cancelButtonText: 'NO'
    }).then((result) => {
      if (result.isConfirmed) {
        var datos = new FormData();
        datos.append('desactivar', boton.value);
        enviaAjax(datos);
      }
    });
  }

  function activar(boton){
    Swal.fire({
      title: '¿Desea activar?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#14961aff',
      cancelButtonColor: '#EF233C',
      confirmButtonText: ' SI ',
      cancelButtonText: 'NO'
    }).then((result) => {
      if (result.isConfirmed) {
        var datos = new FormData();
        datos.append('activar', boton.value);
        enviaAjax(datos);
      }
    });
  }


function consulta() {
    var datos = new FormData();
    datos.append('consultar', 'consultar');
    enviaAjax(datos);
  }

$(document).ready(function() {
   // VALIDACION DE DATOS  
    consulta();
    
  });




  function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
      icon: icono,
      timer: tiempo,
      title: titulo,
      html: mensaje,
      showConfirmButton: false,
    });
  }


  function crearConsulta(datos) {
  var tablaBody = $('#tablametodopago');
  tablaBody.empty();

  datos.forEach(function (dato) {
    var estatus_texto = {
      1: 'Activo',
      2: 'No Activo',
      3: 'Inactivo'
    };
    var estatus_classes = {
      1: 'badge rounded-pill bg-success',
      2: 'badge rounded-pill bg-warning text-dark',
      3: 'badge rounded-pill bg-danger'
    };

    // Botones condicionales según estatus
    var botones = '';
    if (parseInt(dato.estatus) === 1)  {
      botones = '<button type="button" onclick="desactivar(this)" class="btn btn-warning btn-sm eliminar" value="' + dato.id_metodopago + '">' +
                  '<i class="fa-solid fa-triangle-exclamation me-2" title="Desactivar"></i> Desactivar' +
                '</button>';
    } else if (parseInt(dato.estatus) === 2 || parseInt(dato.estatus) === 3) {
      botones = '<button type="button" onclick="activar(this)" class="btn btn-success btn-sm activar" value="' + dato.id_metodopago + '">' +
                  '<i class="fa-solid fa-check me-2" title="Activar"></i> Activar' +
                '</button>';
    }

    var fila = '<tr>' +
      '<td class="text-center td-oscuro">' + dato.id_metodopago + '</td>' +
      '<td class="td-oscuro">' + dato.nombre + '</td>' +
      '<td class="td-oscuro"><span class="' + estatus_classes[dato.estatus] + '">' + estatus_texto[dato.estatus] + '</span></td>' +
      '<td class="td-oscuro">' +
        '<form method="POST" action="?pagina=presentacion">' +
          botones +
        '</form>' +
      '</td>' +
      '</tr>';

    tablaBody.append(fila);
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
  
            if (lee.accion == 'consultar') {
                crearConsulta(lee.datos);
            
            }else if (lee.accion == 'activar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1500, "Se ha activado con éxito", "");
                  setTimeout(function () {
                    consulta();
                  }, 2000);
                } else {
                  muestraMensaje("error", 3000, "Error", lee.text);
                
                }
            } else if (lee.accion == 'desactivar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1500, "Se ha Desactivado con éxito", "");
                  setTimeout(function () {
                    consulta();
                  }, 2000);
                } else {
                  muestraMensaje("error", 3000, "Error", lee.text);
                }
            }
  
        } catch (e) {
          muestraMensaje("error", 2000, "Error en JSON",e.name);
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