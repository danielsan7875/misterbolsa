<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];
$vista="Tasa de Cambio"; $activo="10";

if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

require_once 'modelo/tasa.php';

$objtasa = new Tasa();
$consulta = $objtasa->obtenerDolar();

if(isset($_POST['tasadolar'])){
    if(!empty($_POST['id_dolar']) && !empty($_POST['precios'])){
      $precioss = $_POST['precios'];
     

        if ($precioss === "0.00"){
           $res = array('respuesta' => 0, 'accion' => 'tasadolar', 'text' => 'Error con la conexion o el valdor es 0.00');
            echo json_encode($res);
            exit;
        }

        $objtasa->set_Id_dolar($_POST['id_dolar']); 
        $objtasa->set_precios($_POST['precios']); 
 
        $result = $objtasa->tasadolar();
        echo json_encode($result);
    }
  } else {
    require_once 'vista/tasa.php';
  }
?>