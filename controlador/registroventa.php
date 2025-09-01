<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}

$tipo_usuario = $_SESSION['nivel'];
require_once 'modelo/registroventa.php';
$objventa = new Registroventa();


$producto = $objventa->obtenerProducto();
$dolar = $objventa->obtenerDolar();  
if(isset($_POST['registrar'])){
  if( !empty($_POST['fecha']) && !empty($_POST['total'])){

      $objventa->set_Total($_POST['total']);
      $objventa->set_Fecha($_POST['fecha']);

      $id_producto=$_POST['id_producto'];
      $cantidad=$_POST['cantidad'];
      $precio=$_POST['precio'];
      $maode=$_POST['maode'];
 
      $result=$objventa->registrar($id_producto,$cantidad,$precio,$maode);
      echo json_encode($result);
  } else{
    $_SESSION['message'] = array('title' => 'VACIO', 'text' => 'Vacio ¿?', 'icon' => 'info');
    header('Location: ?pagina=venta'); // Te redijire a la vista
}

} else{
  require_once 'vista/registroventa.php';
}


?>