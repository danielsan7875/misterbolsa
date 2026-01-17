// CHECKBOX -----------------------------------------------------------
document.querySelectorAll('input[name="filtro"]').forEach((checkbox) => {
    checkbox.addEventListener('change', function () {

        if (this.checked) {
            document.querySelectorAll('input[name="filtro"]').forEach((other) => {
                if (other !== this) {
                    other.checked = false;
                }
            });
        }

    });
});

 
// RANGO DE FECHAS -----------------------------------------
const desde = document.getElementById('fecha_desde');
const hasta = document.getElementById('fecha_hasta');

desde.addEventListener('change', () => {

    if (!desde.value) {
        hasta.disabled = true;
        hasta.value = '';
        hasta.min = '';
        hasta.max = '';
        return;
    }

    // habilitar
    hasta.disabled = false;

    // fechas con moment
    const fechaDesde = moment(desde.value, 'YYYY-MM-DD');
    const fechaMax   = fechaDesde.clone().add(3, 'months');

    // setear límites
    hasta.min = fechaDesde.format('YYYY-MM-DD');
    hasta.max = fechaMax.format('YYYY-MM-DD');

    // validar valor actual
    if (hasta.value) {
        const fechaHasta = moment(hasta.value, 'YYYY-MM-DD');

        if (
            fechaHasta.isBefore(fechaDesde) ||
            fechaHasta.isAfter(fechaMax)
        ) {
            hasta.value = '';
        }
    }
});