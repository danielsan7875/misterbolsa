<?php
require_once 'assets/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true); // Para cargar imágenes externas si es necesario

$dompdf = new Dompdf($options);

// Conexión PDO
$pdo = new PDO("mysql:host=localhost;dbname=bdbolsa1", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Consulta principal de la venta
$sqlVenta = "SELECT fecha, total FROM venta WHERE id_venta = ?";
$stmtVenta = $pdo->prepare($sqlVenta);
$stmtVenta->execute([$id_venta]);
$venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

// Consulta de los productos vendidos en esa venta
$sqlDetalles = "
    SELECT p.nombre, m.nombre AS marca, pr.nombre AS presentacion,
           dv.cantidad, dv.precio_unitario, dv.maode
    FROM detalle_venta dv
    JOIN producto p ON dv.id_producto = p.id_producto
    JOIN marca m ON p.id_marca = m.id_marca
    JOIN presentacion pr ON p.id_presentacion = pr.id_presentacion
    WHERE dv.id_venta = ?
";
$stmtDetalles = $pdo->prepare($sqlDetalles);
$stmtDetalles->execute([$id_venta]);
$detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);


$logoPath = 'assets/images/mister-logo.png';
$logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
if (!$venta || empty($detalles)) {
    die("No se encontraron datos para la venta.");
}



$html = '
<style>
  body { font-family: Arial, sans-serif; font-size: 12px; }
  .header, .footer { background-color: #b3d9f3; padding: 10px; border-radius: 6px; }
  .logo { width: 160px; }
  .empresa-info { text-align: center; }
  .empresa-info h2 { margin: 0; font-size: 18px; }
  .empresa-info p { margin: 0; font-size: 12px; }
  .datos-reporte { margin-top: 10px; font-size: 11px; display: flex; justify-content: space-between; }
  .tabla { width: 100%; border-collapse: collapse; margin-top: 15px; }
  .tabla th, .tabla td { border: 1px solid #b3d9f3; padding: 6px; text-align: center; }
  .tabla th { background-color: #b3d9f3; }
</style>

<div class="header">
  <img src="' . $logoBase64 . '" class="logo">
  <div class="empresa-info">
    <h2>MISTER BOLSA, C.A.</h2>
    <p>RIF: J-29979563-1</p>
  </div>
</div>

<div class="datos-reporte">
  <div>Fecha de venta: ' . date("d/m/Y", strtotime($venta['fecha'])) . '</div>
  <div>ID Venta: #' . $id_venta . '</div>
</div>

<h3 style="text-align:center; margin-top:20px;">Reporte de Venta</h3>

<table class="tabla">
  <thead>
    <tr>
      <th>Producto</th>
      <th>Cantidad</th>
      <th>Modo</th>
      <th>Precio Unitario ($)</th>
      <th>Subtotal ($)</th>
    </tr>
  </thead>
  <tbody>';
  
foreach ($detalles as $d) {
    $subtotal = $d['cantidad'] * $d['precio_unitario'];
    $html .= '<tr>
        <td>' . $d['nombre'] . ' - ' . $d['marca'] . ' - ' . $d['presentacion'] . '</td>
        <td>' . $d['cantidad'] . '</td>
        <td>' . strtoupper($d['maode']) . '</td>
        <td>' . number_format($d['precio_unitario'], 2, ',', '.') . '</td>
        <td>' . number_format($subtotal, 2, ',', '.') . '</td>
    </tr>';
}

$html .= '
  </tbody>
</table>

<h3 style="text-align:right; margin-top:10px;">Total: $ ' . number_format($venta['total'], 2, ',', '.') . '</h3>
';
$dompdf->loadHtml($html);
$dompdf->render();
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



$dompdf->setPaper('letter', 'portrait');

$dompdf->stream("reporte_venta_$id_venta.pdf", ["Attachment" => true]);

?>
