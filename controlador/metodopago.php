<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];
$vista="Metodo de Pago"; $activo="9";

if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

require_once 'modelo/metodopago.php';

$objmetodopago = new Metodopago();

if (isset($_POST['consultar'])) {
    $registro = $objmetodopago->consultar();
    echo json_encode($registro);

} else if(isset($_POST['incluir'])){
    $id_metodo = $_POST['incluir'];
   $objmetodopago->set_Id_metodo($id_metodo); 
   $result = $objmetodopago->activar();
   echo json_encode($result);

}else if(isset($_POST['eliminar'])){
   $id_metodo = $_POST['eliminar'];
   $objmetodopago->set_Id_metodo($id_metodo); 
   $result = $objmetodopago->eliminar();
   echo json_encode($result);

} else if ($tipo_usuario == 1 || $tipo_usuario == 2) { // Validacion si es administrador entra
    require_once 'vista/metodopago.php';
} else {
    require_once 'vista/complementos/error/privilegio.php';
}
?>