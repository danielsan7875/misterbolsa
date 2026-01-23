<?php
require_once 'assets/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/* ===============================
   CONFIGURACIÓN DOMPDF
================================ */
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

/* ===============================
   CONEXIÓN BD
================================ */
require_once 'config/reporte.php';

/* ===============================
   CONSULTA COMPRA
================================ */
$sqlCompra = "SELECT fecha FROM compra WHERE id_compra = ?";
$stmtCompra = $pdo->prepare($sqlCompra);
$stmtCompra->execute([$id_compra]);
$compra = $stmtCompra->fetch(PDO::FETCH_ASSOC);

/* ===============================
   CONSULTA DETALLES DE COMPRA
================================ */
$sqlDetalles = "
SELECT 
    dc.cantidad,
    pro.nombre AS nombre_producto,
    m.nombre   AS nombre_marca,
    p.nombre   AS nombre_presentacion
FROM detalle_compra dc
INNER JOIN producto pro ON dc.id_producto = pro.id_producto
INNER JOIN marca m ON pro.id_marca = m.id_marca
INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
WHERE dc.id_compra = ?
";

$stmtDetalles = $pdo->prepare($sqlDetalles);
$stmtDetalles->execute([$id_compra]);
$detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

/* ===============================
   VALIDACIÓN
================================ */
if (!$compra || empty($detalles)) {
    die('No se encontraron datos para la compra.');
}

/* ===============================
   LOGO
================================ */
$logoPath = 'assets/images/mister-logo.png';
$logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

/* ===============================
   HTML
================================ */
$html = '
<style>
body { font-family: Arial, sans-serif; font-size: 12px; }

.header { background:#b3d9f3; padding:10px; }
.logo { width:140px; }

.tabla { width:100%; border-collapse:collapse; margin-top:15px; }
.tabla th, .tabla td { border:1px solid #b3d9f3; padding:6px; text-align:center; }
.tabla th { background:#b3d9f3; }

.text-right { text-align:right; }
</style>

<table width="100%" class="header">
<tr>
    <td width="30%">
        <img src="'.$logoBase64.'" class="logo">
    </td>
    <td width="70%" align="right">
        <strong>MISTER BOLSA, C.A.</strong><br>
        RIF: J-29979563-1
    </td>
</tr>
</table>

<p>
<strong>ID Compra:</strong> #'.$id_compra.'<br>
<strong>Fecha:</strong> '.date("d/m/Y", strtotime($compra['fecha'])).'
</p>

<h3 style="text-align:center;">Detalle de Productos Comprados</h3>

<table class="tabla">
<thead>
<tr>
    <th>Producto</th>
    <th>Marca</th>
    <th>Presentación</th>
    <th>Cantidad</th>
</tr>
</thead>
<tbody>';

foreach ($detalles as $d) {
    $html .= '
    <tr>
        <td>'.$d['nombre_producto'].'</td>
        <td>'.$d['nombre_marca'].'</td>
        <td>'.$d['nombre_presentacion'].'</td>
        <td>'.$d['cantidad'].'</td>
    </tr>';
}

$html .= '
</tbody>
</table>';

/* ===============================
   GENERAR PDF
================================ */
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();

/* PAGINACIÓN */
$canvas = $dompdf->getCanvas();
$canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
    $text = "Página $pageNumber de $pageCount";
    $font = $fontMetrics->getFont('Arial', 'normal');
    $size = 10;
    $x = $canvas->get_width() - 100;
    $y = $canvas->get_height() - 30;
    $canvas->text($x, $y, $text, $font, $size);
});

/* DESCARGA */
$dompdf->stream("REPORTE_COMPRA_$id_compra.pdf", ["Attachment" => true]);
