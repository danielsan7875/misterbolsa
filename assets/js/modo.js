document.addEventListener('DOMContentLoaded', function () {
  const modoKey = 'modoOscuro_' + idUsuario;
  const boton = document.getElementById('toggleModo');
  const modoActivo = localStorage.getItem(modoKey);

  if (modoActivo === 'activo') {
    activarModoOscuro(boton, modoKey);
    document.body.classList.add('modo-oscuro');
  }

  boton.addEventListener('click', function () {
    const activo = document.body.classList.toggle('modo-oscuro');

    if (activo) {
      activarModoOscuro(boton, modoKey);
    } else {
      desactivarModoOscuro(boton, modoKey);
    }
  });
});


function activarModoOscuro(boton, modoKey) {
  boton.classList.add('activeModo');
  boton.innerHTML = '<i class="fa-solid fa-sun"></i>';
  localStorage.setItem(modoKey, 'activo');
}

function desactivarModoOscuro(boton, modoKey) {
  boton.classList.remove('activeModo');
  boton.innerHTML = '<i class="fa-solid fa-moon"></i>';
  localStorage.setItem(modoKey, 'inactivo');
}

