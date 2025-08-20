<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];

require_once 'modelo/tasa.php';

$objtasa = new Tasa();
$consulta = $objtasa->obtenerDolar();

if(isset($_POST['tasadolar'])){
    if(!empty($_POST['id_dolar']) && !empty($_POST['precios'])){

        $objtasa->set_Id_dolar($_POST['id_dolar']); 
        $objtasa->set_precios($_POST['precios']); 
 
        $result = $objtasa->tasadolar();
        echo json_encode($result);
    }
  } else {
    require_once 'vista/tasa.php';
  }
?>