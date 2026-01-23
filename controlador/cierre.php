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



 // 200 por defecto
    if (!isset($_SESSION['limite_cierre'])) {
        $_SESSION['limite_cierre'] = 200;
    }

    //  "ver más"
    if (isset($_POST['ver_mas'])) {
        $_SESSION['limite_cierre'] += 100; // Aumenta 100 registros
        header("location:?pagina=cierre");
        exit;
    }



$registro = $objcierre->consultar($_SESSION['limite_cierre']);
$total_registros = $objcierre->contarTotal();

$dolar = $objcierre->obtenerDolar(); 
$cantidad = $objcierre->contarVentas();
$cierre = $objcierre->obtenerCierreActivo();

$contarcierre = $objcierre->contarCierrePorFecha();



  if(isset($_POST['iniciar'])){
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

} else if(isset($_POST['pdf'])){

      if (!empty($_POST['pdf'])) {
        $id_cierre = $_POST['pdf'];
     
      if(!$id_cierre){
        header('Location: ?pagina=cierre');
      }
          require_once 'assets/pdf/reportecierre.php';
          echo json_encode($res);
      }

} else {
  require_once 'vista/cierre.php';
}

?>