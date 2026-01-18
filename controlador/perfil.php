<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
 $vista="Mi Perfil";


require_once 'vista/perfil.php';
?>