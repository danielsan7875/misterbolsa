<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];

require_once 'modelo/home.php';
$objhome = new Home();

$dolar = $objhome->obtenerDolar(); 
$totalProductos = $objhome->contarProductos();
$totalCompra = $objhome->contarCompras();
$totalVenta = $objhome->contarVentas();

 if(isset($_POST['tasadolar'])){
  if(!empty($_POST['id_dolar']) && !empty($_POST['precios'])){

      $objhome->set_Id_dolar($_POST['id_dolar']); 
      $objhome->set_Precios($_POST['precios']); 

      $result = $objhome->tasadolar();
      echo json_encode($result);
  }

}


    require_once 'vista/home.php';
?>