<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
$vista="Mi Perfil";

require_once 'modelo/perfil.php';

  $objperfil = new Perfil ();


  if(isset($_POST["actualizar"])){

      $objperfil->set_Id_usuario($_POST['id_usuario']);
      $objperfil->set_Nombres($_POST['nombre']);
      $objperfil->set_Apellidos($_POST['apellido']);
      $objperfil->set_Usuario($_POST['usuario']);

      $result=$objperfil->actualizar();

      if ($result['respuesta'] == 1) {

          $consulta = $objperfil->consulta($_POST['id_usuario']);

         $_SESSION["nombre"]   = $consulta[0]['nombre'];
          $_SESSION["apellido"] = $consulta[0]['apellido'];
          $_SESSION["usuario"]  = $consulta[0]['usuario'];

      }


      echo json_encode($result);
      exit;

  } else if(isset($_POST["clave"])){

      $objperfil->set_Id_usuario($_POST['id_usuario']);
      $objperfil->set_Nombres($_POST['nombres']);
      $objperfil->set_Apellidos($_POST['apellidos']);
      $objperfil->set_Usuario($_POST['usuario']);

      $result=$objperfil->actualizar();
      echo json_encode($result);



  } else{

  } 

require_once 'vista/perfil.php';
?>