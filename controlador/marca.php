<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$tipo_usuario = $_SESSION['nivel'];
$vista="Marca"; $activo="7";

if ($_SESSION["nivel"] == 3){
  header("location:?pagina=registroventa");
}

require_once 'modelo/marca.php';

$objmarca = new Marca();

$registro = $objmarca->consultar();

if(isset($_POST['registrar'])){
   if( !empty($_POST['nombre'])){ 

       $objmarca->set_Nombre($_POST['nombre']);
   
       $result=$objmarca->registrar();
       echo json_encode($result);
       
   }
}else if(isset($_POST['modificar'])){
   $modificar = $objmarca->buscar($_POST['modificar']);
   require_once ("vista/complementos/actualizar/marca.php");

   }else if(isset($_POST['actualizar'])){
       $id_marca = $_POST['id_marca'];
       $nombre = $_POST['nombre'];
       $estatus = $_POST['estatus'];

       $objmarca->set_Id_marca($id_marca);
       $objmarca->set_Nombre($nombre);
       $objmarca->set_Estatus($estatus);

       $result=$objmarca->actualizar();
       echo json_encode($result);

}else if(isset($_POST['eliminar'])){
   $id_marca = $_POST['eliminar'];
   $objmarca->set_Id_marca($id_marca); 
   $result = $objmarca->eliminar();
   echo json_encode($result);

} else if ($tipo_usuario == 1 || $tipo_usuario == 2) { // Validacion si es administrador entra
    require_once 'vista/marca.php';
} else {
    require_once 'vista/complementos/error/privilegio.php';
}
?>