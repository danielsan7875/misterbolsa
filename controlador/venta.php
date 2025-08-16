<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];
require_once 'modelo/venta.php';
$objventa = new Venta();

$registro = $objventa->consultar();
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

  
} else if(isset($_POST['eliminar'])){
  $id_venta = $_POST['eliminar'];

  $objventa->set_Id_venta($id_venta); 
  $result = $objventa->eliminar();
  echo json_encode($result);

}else if(isset($_POST['tasadolar'])){
  if(!empty($_POST['id_dolar']) && !empty($_POST['precios'])){

      $objventa->set_Id_dolar($_POST['id_dolar']); 
      $objventa->set_precios($_POST['precios']); 

      $result = $objventa->tasadolar();
      echo json_encode($result);
  }else{
    $result = array('respuesta' => 0, 'accion' => 'tasadolar');
    echo json_encode($result);
  }

} else {
  require_once 'vista/venta.php';
}



?>