<?php
session_start();
if (empty($_SESSION["id"])){
  header("location:?pagina=login");
}
require_once 'vista/complementos/error/404.php';
?>