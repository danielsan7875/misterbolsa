<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];
 $vista="Presentacion"; $activo="8";

if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

require_once 'modelo/presentacion.php';

$objpresentacion = new Presentacion();

 $registro = $objpresentacion->consultar();

if(isset($_POST['registrar'])){
        if( !empty($_POST['nombre'])){

            $objpresentacion->set_Nombre($_POST['nombre']);
        
            $result=$objpresentacion->registrar();
            echo json_encode($result);
        }
    }else if(isset($_POST['modificar'])){
        $modificar = $objpresentacion->buscar($_POST['modificar']);
        require_once ("vista/complementos/actualizar/presentacion.php");

        }else if(isset($_POST['actualizar'])){
            $id_presentacion = $_POST['id_presentacion'];
            $nombre = $_POST['nombre'];
            $estatus = $_POST['estatus'];

            $objpresentacion->set_Id_presentacion($id_presentacion);
            $objpresentacion->set_Nombre($nombre);
            $objpresentacion->set_Estatus($estatus);

            $result=$objpresentacion->actualizar();
            echo json_encode($result);
    
    }else if(isset($_POST['eliminar'])){
        $id_presentacion = $_POST['eliminar'];
        $objpresentacion->set_Id_presentacion($id_presentacion); 
        $result = $objpresentacion->eliminar();
        echo json_encode($result);
   
    } else if ($tipo_usuario == 1 || $tipo_usuario == 2) { // Validacion si es administrador entra
        require_once 'vista/presentacion.php';
    } else {
        require_once 'vista/complementos/error/privilegio.php';
    }

?>