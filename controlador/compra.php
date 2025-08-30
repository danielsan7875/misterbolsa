<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];

require_once 'modelo/compra.php';
$objcompra = new Compra();

$registro = $objcompra->consultar();
$producto = $objcompra->obtenerProducto(); 

if(isset($_POST['registrar'])){
  if( !empty($_POST['id_producto'] && !empty($_POST['cantidad']) && !empty($_POST['fecha'])) ){

      $objcompra->set_Fecha($_POST['fecha']);

      $id_producto=$_POST['id_producto'];
      $cantidad=$_POST['cantidad'];

      $result=$objcompra->registrar($id_producto,$cantidad);
      echo json_encode($result);
     
  }

} else if(isset($_POST['eliminar'])){
     $id_compra = $_POST['eliminar'];

      $objcompra->set_Id_compra($id_compra); 
      $result = $objcompra->eliminar();
      echo json_encode($result);
  
} else if ($tipo_usuario == 1 || $tipo_usuario == 2) { // Validacion si es administrador entra
     require_once 'vista/compra.php';
} else {
     require_once 'vista/complementos/error/privilegio.php';
}
?>