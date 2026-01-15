<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];
$vista="Perdidas - Inventario"; $activo="5";

if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

require_once 'modelo/perdidas.php';
$objperdidas = new Perdidas();

$registro = $objperdidas->consultar();
$producto = $objperdidas->obtenerProducto();


if (isset($_POST['registrar'])) {
  if (!empty($_POST['id_producto']) && !empty($_POST['cantidad']) && !empty($_POST['fecha']) && !empty($_POST['nota'])) {
      $objperdidas->set_Id_producto($_POST['id_producto']);
      $objperdidas->set_Cantidad($_POST['cantidad']);
      $objperdidas->set_Fecha($_POST['fecha']);
      $objperdidas->set_Nota($_POST['nota']);

      $result = $objperdidas->registrar();
      echo json_encode($result);
      
  }
} else if(isset($_POST['eliminar'])){
  $id_perdida = $_POST['eliminar'];

  $objperdidas->set_Id_perdida($id_perdida); 
  $result = $objperdidas->eliminar();
  echo json_encode($result);

 }else{
   require_once 'vista/perdidas.php';
 }
?>