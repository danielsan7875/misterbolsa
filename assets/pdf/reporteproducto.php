<?php
require_once 'assets/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); 

$dompdf = new Dompdf($options);

require_once 'config/reporte.php';

// Consulta 1: Productos
$sqlProductos = "
  SELECT pro.*, m.nombre AS nombre_marca, p.nombre AS nombre_presentacion 
  FROM producto pro
  INNER JOIN marca m ON pro.id_marca = m.id_marca
  INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
  WHERE pro.estatus < 5
  ORDER BY pro.id_producto DESC
";
$productos = $pdo->query($sqlProductos)->fetchAll(PDO::FETCH_ASSOC);

// Consulta 2: Dólar
$sqlDolar = "SELECT precios FROM dolar ORDER BY id_dolar DESC LIMIT 1";
$dolar = $pdo->query($sqlDolar)->fetchColumn();

// Manejo del Logo (con verificación)
$logoPath = 'assets/images/mister-logo.png';
$logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

$html = '
<style>
  body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
  .header { width: 100%; background-color: #b3d9f3; padding: 10px; border-radius: 6px; }
  /* Dompdf no soporta bien Flexbox, usamos tablas para el layout del header */
  .header-table { width: 100%; border: none; }
  .logo { width: 150px; }
  .empresa-info { text-align: right; }
  .empresa-info h2 { margin: 0; font-size: 18px; }
  .datos-reporte { margin-top: 10px; width: 100%; }
  .tabla { width: 100%; border-collapse: collapse; margin-top: 15px; }
  .tabla th, .tabla td { border: 1px solid #b3d9f3; padding: 8px; text-align: center; }
  .tabla th { background-color: #b3d9f3; }
</style>

<div class="header">
  <table class="header-table">
    <tr>
      <td style="width: 30%;"><img src="' . $logoBase64 . '" class="logo"></td>
      <td style="width: 70%;" class="empresa-info">
        <h2>MISTER BOLSA, C.A.</h2>
        <p>RIF: J-29979563-1</p>
      </td>
    </tr>
  </table>
</div>

<table class="datos-reporte">
  <tr>
    <td><strong>Fecha:</strong> ' . date("d/m/Y") . '</td>
    <td style="text-align: right;"><strong>Tasa Dólar:</strong> Bs ' . number_format($dolar, 2, ',', '.') . '</td>
  </tr>
</table>

<h3 style="text-align:center; margin-top:20px;">Reporte de Productos</h3>

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
        <td>' . $p['nombre_marca'] . ' - ' . $p['nombre_presentacion'] . '</td>
        <td>' . number_format($precio_detal, 2, ',', '.') . '</td>
        <td>' . number_format($precio_mayor, 2, ',', '.') . '</td>
        <td>' . $p['stock'] . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// 1. Cargar el HTML
$dompdf->loadHtml($html);

// 2. Configurar papel
$dompdf->setPaper('letter', 'portrait');

// 3. RENDERIZAR (Esto es vital antes de usar el canvas)
$dompdf->render();

// 4. Agregar numeración de páginas al canvas después del render
$canvas = $dompdf->getCanvas();
$canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
    $text = "Página $pageNumber de $pageCount";
    $font = $fontMetrics->getFont('Arial', 'normal');
    $size = 10;
    $width = $fontMetrics->getTextWidth($text, $font, $size);
    $x = $canvas->get_width() - $width - 40;
    $y = $canvas->get_height() - 35;
    $canvas->text($x, $y, $text, $font, $size);
});

// 5. Salida del PDF
$dompdf->stream("reporte_productos.pdf", ["Attachment" => true]);
exit;