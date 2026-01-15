<?php
require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // Para cargar imágenes externas si es necesario

$dompdf = new Dompdf($options);

require_once '../../config/reporte.php';

// Consulta 1: Productos
$sqlProductos = "
    SELECT p.id_producto, p.nombre, m.nombre AS marca, pr.nombre AS presentacion,
           p.precio_unitario, p.precio_mayor, p.stock
    FROM producto p
    JOIN marca m ON p.id_marca = m.id_marca
    JOIN presentacion pr ON p.id_presentacion = pr.id_presentacion
    WHERE p.estatus = 1
";
$productos = $pdo->query($sqlProductos)->fetchAll(PDO::FETCH_ASSOC);

// Consulta 2: Dólar (última tasa)
$sqlDolar = "SELECT precios FROM dolar ORDER BY id_dolar DESC LIMIT 1";
$dolar = $pdo->query($sqlDolar)->fetchColumn();

$logoPath = '../images/mister-logo.png';
$logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

$html = '
<style>
  body {
    font-family: Arial, sans-serif;
    font-size: 12px;
    background-color: #ffffffff;
    color: #000000ff;
  }
  .header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #b3d9f3ff;
    color: #000000ff;
    padding: 10px 20px;
    border-radius: 6px;
  }
  .logo {
    width: 160px;
  }
  .empresa-info {
    flex-grow: 1;
    text-align: center;
  }
  .empresa-info h2 {
    margin: 0;
    font-size: 18px;
    color: #000000ff;
  }
  .empresa-info p {
    margin: 0;
    font-size: 12px;
    color: #1f1f1fff;
  }
  .datos-reporte {
    margin-top: 10px;
    display: flex;
    justify-content: space-between;
    font-size: 11px;
  }
  .tabla {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
  }
  .tabla th, .tabla td {
    border: 1px solid #b3d9f3ff;
    padding: 6px;
    text-align: center;
  }
  .tabla th {
    background-color: #b3d9f3ff;
    color: #000000ff;
  }
  .footer {
    position: fixed;
    bottom: -30px;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 10px;
    color: #020202ff;
  }
</style>

<div class="header">
<img src="' . $logoBase64 . '" class="logo">

  <div class="empresa-info">
    <h2> MISTER BOLSA, C.A.</h2>
    <p>RIF:  J-29979563-1 </p>
  </div>
</div>

<div class="datos-reporte">
  <div> Fecha: ' . date("d/m/Y") . '</div>
  <div> Tasa Dólar: Bs ' . number_format($dolar, 2, ',', '.') . '</div>
</div>

<h3 style="text-align:center; margin-top:20px;"> Reporte de Productos</h3>

<table class="tabla">
  <thead>
    <tr>
  
      <th>Nombre</th>
      <th>Marca y Presentación</th>
      <th>Precio DETAL (Bs)</th>
      <th>Precio MAYOR (Bs)</th>
      <th>Stock</th>
    </tr>
  </thead>
  <tbody>';

foreach ($productos as $p) {
    $precio_detal = $p['precio_unitario'] * $dolar;
    $precio_mayor = $p['precio_mayor'] * $dolar;
    $html .= '<tr>
  
        <td>' . $p['nombre'] . '</td>
        <td>' . $p['marca'] . ' - ' . $p['presentacion'] . '</td>
        <td>' . number_format($precio_detal, 2, ',', '.') . '</td>
        <td>' . number_format($precio_mayor, 2, ',', '.') . '</td>
        <td>' . $p['stock'] . '</td>
    </tr>';
}

$canvas = $dompdf->getCanvas();
$canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
    $text = "Página $pageNumber de $pageCount";
    $font = $fontMetrics->getFont('Arial', 'normal');
    $size = 10;
    $width = $fontMetrics->getTextWidth($text, $font, $size);
    $x = $canvas->get_width() - $width - 40;
    $y = $canvas->get_height() - 30;
    $canvas->text($x, $y, $text, $font, $size);
});


$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();
$dompdf->stream("reporte_productos.pdf", ["Attachment" => false]);
?>
