<?php
require_once 'assets/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// Conexión
require_once 'config/reporte.php';

// DATOS DEL LOCAL (MANUALES)
$local = [
    'nombre' => 'MISTER BOLSA, C.A.',
    'rif' => 'J-29979563-1',
    'direccion' => 'Av. Principal, Local 12, Caracas',
    'telefono' => '0414-1234567'
];

// CONSULTA 1 — CIERRE DIARIO
$sqlCierre = "
    SELECT *
    FROM cierre_diario
    WHERE id_cierre = ? AND estatus = 2
    LIMIT 1
";
$stmtCierre = $pdo->prepare($sqlCierre);
$stmtCierre->execute([$id_cierre]);
$cierre = $stmtCierre->fetch(PDO::FETCH_ASSOC);

if (!$cierre) {
    die('No existe el cierre o no está en estatus 2.');
}

// CONSULTA 2 — VENTAS DEL CIERRE
$sqlVentas = "
    SELECT dc.id_venta, dc.hora, v.total_dl
    FROM detalle_cierre dc
    JOIN venta v ON dc.id_venta = v.id_venta
    WHERE dc.id_cierre = ?
    ORDER BY dc.hora ASC
";
$stmtVentas = $pdo->prepare($sqlVentas);
$stmtVentas->execute([$id_cierre]);
$ventas = $stmtVentas->fetchAll(PDO::FETCH_ASSOC);

// CONSULTA 3 — PRODUCTOS POR VENTA
$sqlProductos = "
    SELECT 
        p.nombre AS producto,
        m.nombre AS marca,
        pr.nombre AS presentacion,
        dv.cantidad,
        dv.precio_unitario,
        dv.maode
    FROM detalle_venta dv
    JOIN producto p ON dv.id_producto = p.id_producto
    JOIN marca m ON p.id_marca = m.id_marca
    JOIN presentacion pr ON p.id_presentacion = pr.id_presentacion
    WHERE dv.id_venta = ?
";
$stmtProductos = $pdo->prepare($sqlProductos);

// LOGO
$logoPath = 'assets/images/mister-logo.png';
$logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));

// HTML
$html = '
<style>
  body { font-family: Arial, sans-serif; font-size: 12px; }

  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: #b3d9f3;
    border-radius: 6px;
  }

  .header-left {
    display: flex;
    align-items: center;
  }

  .logo {
    width: 110px;
    margin-right: 15px;
  }

  .local-name {
    font-size: 18px;
    font-weight: bold;
    margin: 0;
  }

  .header-right p {
    margin: 2px 0;
    font-size: 12px;
    text-align: right;
  }

  .section-title {
    margin-top: 20px;
    font-size: 16px;
    text-align: center;
  }

  .totales-row {
    width: 100%;
    margin-top: 10px;
  }

  .totales-card {
    width: 48%;
    display: inline-block;
    vertical-align: top;
    border: 1px solid #b3d9f3;
    border-radius: 6px;
    padding: 10px;
    margin-right: 2%;
  }

  .totales-card:last-child {
    margin-right: 0;
  }

  .totales-card h4 {
    margin: 0 0 8px 0;
    font-size: 14px;
    text-align: center;
  }

  .totales-card table {
    width: 100%;
    border-collapse: collapse;
  }

  .totales-card td {
    padding: 4px;
    border-bottom: 1px solid #e3f1fb;
  }

  .totales-card td:last-child {
    font-weight: bold;
  }

  .tabla {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
  }

  .tabla th, .tabla td {
    border: 1px solid #b3d9f3;
    padding: 6px;
    text-align: center;
  }

  .tabla th {
    background-color: #b3d9f3;
  }
</style>



<!-- ENCABEZADO -->
<div class="header">

  <div class="header-left">
    <img src="' . $logoBase64 . '" class="logo">
    <div>
      <p class="local-name">' . $local['nombre'] . '</p>
    </div>
  </div>

  <div class="header-right">
    <p><strong>RIF:</strong> ' . $local['rif'] . '</p>
    <p>' . $local['direccion'] . '</p>
    <p><strong>Tel:</strong> ' . $local['telefono'] . '</p>
  </div>

</div>

<!-- FECHAS -->
<p><strong>Fecha de Apertura:</strong> ' . date("d/m/Y H:i", strtotime($cierre['fecha_creacion'])) . '</p>
<p><strong>Fecha de Cierre:</strong> ' . date("d/m/Y H:i", strtotime($cierre['fecha_cierre'])) . '</p>
<p><strong>N° Cierre:</strong> #' . $id_cierre . '</p>

<h3 class="section-title">Totales del Día</h3>

<table width="100%" style="margin-top:10px; border-collapse:collapse;">
  <tr>

    <!-- COLUMNA USD -->
    <td width="50%" style="vertical-align:top; padding-right:10px;">
      <div style="border:1px solid #b3d9f3; border-radius:6px; padding:10px;">
        <h4 style="text-align:center; margin:0 0 8px 0; font-size:14px;">Totales en USD</h4>
        <table width="100%" style="border-collapse:collapse;">
          <tr><td>Total Día</td><td><strong>$ ' . number_format($cierre['total_dia_dl'], 2, ',', '.') . '</strong></td></tr>
          <tr><td>Efectivo</td><td><strong>$ ' . number_format($cierre['total_efectivo_dl'], 2, ',', '.') . '</strong></td></tr>
          <tr><td>Punto</td><td><strong>$ ' . number_format($cierre['total_punto_dl'], 2, ',', '.') . '</strong></td></tr>
          <tr><td>Pago Móvil</td><td><strong>$ ' . number_format($cierre['total_pagomovil_dl'], 2, ',', '.') . '</strong></td></tr>
          <tr><td>Divisas</td><td><strong>$ ' . number_format($cierre['total_divisas_dl'], 2, ',', '.') . '</strong></td></tr>
        </table>
      </div>
    </td>

    <!-- COLUMNA BS -->
    <td width="50%" style="vertical-align:top; padding-left:10px;">
      <div style="border:1px solid #b3d9f3; border-radius:6px; padding:10px;">
        <h4 style="text-align:center; margin:0 0 8px 0; font-size:14px;">Totales en Bolívares</h4>
        <table width="100%" style="border-collapse:collapse;">
          <tr><td>Total Día</td><td><strong>' . number_format($cierre['total_dia_bs'], 2, ',', '.') . ' Bs</strong></td></tr>
          <tr><td>Efectivo</td><td><strong>' . number_format($cierre['total_efectivo_bs'], 2, ',', '.') . ' Bs</strong></td></tr>
          <tr><td>Punto</td><td><strong>' . number_format($cierre['total_punto_bs'], 2, ',', '.') . ' Bs</strong></td></tr>
          <tr><td>Pago Móvil</td><td><strong>' . number_format($cierre['total_pagomovil_bs'], 2, ',', '.') . ' Bs</strong></td></tr>
          <tr><td>Divisas</td><td><strong>' . number_format($cierre['total_divisas_bs'], 2, ',', '.') . ' Bs</strong></td></tr>
        </table>
      </div>
    </td>

  </tr>
</table>

<p><strong>Tasa del Día:</strong> ' . number_format($cierre['tasa'], 2, ',', '.') . '</p>


<!-- DETALLE DE VENTAS -->
<h3 class="section-title">Detalle de Ventas</h3>

<table class="tabla">
  <thead>
    <tr>
      <th>#</th>
      <th>Producto</th>
      <th>Cantidad</th>
      <th>Modo</th>
      <th>Total ($)</th>
      <th>Hora</th>
    </tr>
  </thead>
  <tbody>';

$contador = 1;

foreach ($ventas as $v) {

    $stmtProductos->execute([$v['id_venta']]);
    $productos = $stmtProductos->fetchAll(PDO::FETCH_ASSOC);

    foreach ($productos as $p) {

        $subtotal = $p['cantidad'] * $p['precio_unitario'];

        $html .= '
        <tr>
            <td>' . $contador . '</td>
            <td>' . $p['producto'] . ' - ' . $p['marca'] . ' - ' . $p['presentacion'] . '</td>
            <td>' . $p['cantidad'] . '</td>
            <td>' . strtoupper($p['maode']) . '</td>
            <td>' . number_format($subtotal, 2, ',', '.') . '</td>
            <td>' . $v['hora'] . '</td>
        </tr>';

        $contador++;
    }
}

$html .= '
  </tbody>
</table>
';

$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();
$dompdf->stream("cierre_diario_$id_cierre.pdf", ["Attachment" => true]);
?>
