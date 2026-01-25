<?php

// Conexión PDO
$pdo = new PDO("mysql:host=localhost;dbname=bdbolsa3", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>