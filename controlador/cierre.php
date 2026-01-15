<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];
$vista="Cierre Diario"; $activo="11";

if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

require_once 'modelo/cierre.php';
$objcierre = new Cierre();


$registro = $objcierre->consultar();
$dolar = $objcierre->obtenerDolar(); 
$cantidad = $objcierre->contarVentas();

if (isset($_POST['consultar'])) {
    $fecha = $_POST['fecha'];
    $registro = $objcierre->contarCierrePorFecha($fecha);
    echo json_encode($registro);

} else if (isset($_POST['consultarcierre'])) {
    $fecha = $_POST['fecha'];
    $registro = $objcierre->consultarCierreActivo($fecha);
    echo json_encode($registro);

}else if(isset($_POST['iniciar'])){
    $fecha = $_POST['fecha'];
    $tasa = $_POST['tasa'];

    // Validar si ya existe un cierre con estatus 1 en esa fecha
    $validacion = $objcierre->obtenerValidarcierre($fecha);

    if ($validacion['respuesta'] == 1) {
        // Si no existe, proceder con el registro
        $objcierre->set_Fecha($fecha);
        $objcierre->set_Tasa($tasa);

        $resultado = $objcierre->iniciar();
        echo json_encode($resultado); // Solo imprime el resultado del registro
    } else {
        echo json_encode($validacion); // Solo imprime la validación si no se puede registrar
    }

} else if(isset($_POST['cierre'])){
    $fecha_cierre = $_POST['fecha_cierre'];
    $id_cierre = $_POST['id_cierre'];


    $objcierre->set_Fecha($fecha_cierre);
    $resultado = $objcierre->procesarCierreDiario($id_cierre);
    echo json_encode($resultado); 





} else {
  require_once 'vista/cierre.php';
}

?>