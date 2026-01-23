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


if (isset($_POST['ajaxProducto'])) {

    $buscar = $_POST['buscarProducto'] ?? '';

    if ($buscar === '') {
        // Últimos 20 registrados
        $producto = $objperdidas->ultimosProductos();
    } else {
        // Búsqueda normal
        $producto = $objperdidas->buscarProducto($buscar);
    }

    echo json_encode($producto);
    exit;

} else if (isset($_POST['registrar'])) {
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

 }else if (isset($_POST['consultafiltro'])) {

    $filtro = $_POST['filtro'] ?? null;
    $desde  = $_POST['desde'] ?? null;
    $hasta  = $_POST['hasta'] ?? null;

    $filtradofecha = $objperdidas->consultarFiltrado($filtro, $desde, $hasta);

      // TEXTO DEL PERIODO
    if ($filtro == "15") {
        $periodo = "Últimos 15 días (Hoy y 14 días anteriores)";
    } elseif ($filtro == "mes") {
        $periodo = "Mes actual (" . date('F Y') . ")";
    } elseif (!empty($desde) && !empty($hasta)) {
        $periodo = "Desde " . date('d/m/Y', strtotime($desde)) .
                   " hasta " . date('d/m/Y', strtotime($hasta));
    } else {
        $periodo = "Todos los registros";
    }

    require_once("vista/complementos/consulta/perdidas.php");
}else{
   require_once 'vista/perdidas.php';
 }
?>