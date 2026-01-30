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
   CONSULTA VENTA
================================ */
$sqlVenta = "SELECT fecha, total_dl, total_bs FROM venta WHERE id_venta = ?";
$stmtVenta = $pdo->prepare($sqlVenta);
$stmtVenta->execute([$id_venta]);
$venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);

/* ===============================
   CONSULTA SOLO PRODUCTOS
================================ */
$sqlProductos = "
SELECT 
    dv.cantidad,
    dv.precio_unitario,
    dv.maode,

    pro.nombre AS nombre_producto,
    m.nombre   AS nombre_marca,
    p.nombre   AS nombre_presentacion

FROM detalle_venta dv
INNER JOIN producto pro ON dv.id_producto = pro.id_producto
INNER JOIN marca m ON pro.id_marca = m.id_marca
INNER JOIN presentacion p ON pro.id_presentacion = p.id_presentacion
WHERE dv.id_venta = ?
";

$stmtProductos = $pdo->prepare($sqlProductos);
$stmtProductos->execute([$id_venta]);
$productos = $stmtProductos->fetchAll(PDO::FETCH_ASSOC);

/* ===============================
   CONSULTA SOLO PAGOS
================================ */
$sqlPagos = "
SELECT 
    dp.id_detallepago,
    dp.monto_dolares,
    dp.monto_bs,
    dp.referencia,
    mp.nombre AS metodo_pago
FROM detalle_pago dp
INNER JOIN metodopago mp ON dp.id_metodopago = mp.id_metodopago
WHERE dp.id_venta = ?
";

$stmtPagos = $pdo->prepare($sqlPagos);
$stmtPagos->execute([$id_venta]);
$pagos = $stmtPagos->fetchAll(PDO::FETCH_ASSOC);

/* ===============================
   VALIDACIÓN
================================ */
if (!$venta || empty($productos)) {
    die('No se encontraron datos para la venta.');
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
<strong>ID Venta:</strong> #'.$id_venta.'<br>
<strong>Fecha:</strong> '.date("d/m/Y", strtotime($venta['fecha'])).'
</p>';

/* ===============================
   TABLA MÉTODOS DE PAGO
================================ */
if (!empty($pagos)) {

    $html .= '
    <h3 style="margin-top:20px;">Métodos de Pago</h3>

    <table class="tabla">
    <thead>
    <tr>
        <th>Método</th>
        <th>Monto ($)</th>
        <th>Monto (Bs)</th>
        <th>Referencia</th>
    </tr>
    </thead>
    <tbody>';

    foreach ($pagos as $p) {
        $html .= '
        <tr>
            <td>'.$p['metodo_pago'].'</td>
            <td>'.number_format($p['monto_dolares'], 2, ',', '.').'</td>
            <td>'.number_format($p['monto_bs'], 2, ',', '.').'</td>
            <td>'.($p['referencia'] ?: '-').'</td>
        </tr>';
    }

    $html .= '
    </tbody>
    </table>';
}


$html .='

<h3 style="text-align:center; margin-top:20px;">Detalle de Productos</h3>

<table class="tabla">
<thead>
<tr>
    <th>Producto</th>
    <th>Cant. / Modo</th>
    <th>Precio Unitario ($)</th>
    <th>Subtotal ($)</th>
</tr>
</thead>
<tbody>';

foreach ($productos as $d) {
    $subtotal = $d['cantidad'] * $d['precio_unitario'];

    $html .= '
    <tr>
        <td>'.$d['nombre_producto'].' - '.$d['nombre_marca'].' - '.$d['nombre_presentacion'].'</td>
        <td>'.$d['cantidad'].' - '.strtoupper($d['maode']).'</td>
        <td>'.number_format($d['precio_unitario'], 2, ',', '.').'</td>
        <td>'.number_format($subtotal, 2, ',', '.').'</td>
    </tr>';
}

$html .= '
</tbody>
</table>';


/* ===============================
   TOTALES
================================ */
$html .= '
<h3 class="text-right">
Total: $ '.number_format($venta['total_dl'], 2, ',', '.').'<br>
Bs. '.number_format($venta['total_bs'], 2, ',', '.').'
</h3>';

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
$dompdf->stream("REPORTE_VENTA_$id_venta.pdf", ["Attachment" => true]);
