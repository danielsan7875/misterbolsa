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
    'direccion' => 'Av. Principal, Local 12, Cabudare',
    'telefono' => '0424-5820047'
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
    SELECT dc.id_venta, dc.hora, v.total_dl, v.total_bs
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

  ul {
    margin: 0;
    padding-left: 15px;
}

li {
    margin-bottom: 4px;
}

.sin-puntos {
    list-style: none;
    padding-left: 0;
    margin: 0;
    
}

.sin-puntos li {
    margin-bottom: 4px;
}

.text-left {
    text-align: left !important;
}


.logo {
    max-width: 500px;
}

.local-name {
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 4px;
}

p {
    margin: 2px 0;
}


</style>';


$html .= '
<table width="100%" style="margin-bottom: 15px; background: #b3d9f3; border-radius: 6px; padding:12px;">
    <tr>
        <!-- LOGO -->
        <td width="40%" align="left" valign="middle">
            <img src="' . $logoBase64 . '" class="logo">
        </td>

        <!-- DATOS DEL LOCAL -->
        <td width="60%" align="right" valign="middle">
            <p class="local-name">' . $local['nombre'] . '</p>
            <p><strong>RIF:</strong> ' . $local['rif'] . '</p>
            <p>' . $local['direccion'] . '</p>
            <p><strong>Tel:</strong> ' . $local['telefono'] . '</p>
        </td>
    </tr>
</table>';


$html .= '
<table width="100%" style="margin-bottom: 10px;">
    <tr>
        <td width="33%">
            <strong>N° Cierre:</strong> #' . $id_cierre . '
        </td>
        <td width="33%" align="center">
            <strong>Fecha Apertura:</strong> ' . date("d/m/Y h:i A", strtotime($cierre['fecha_creacion'])) . '
        </td>
        <td width="33%" align="right">
            <strong>Fecha Cierre:</strong> ' . date("d/m/Y h:i A", strtotime($cierre['fecha_cierre'])) . '
        </td>
    </tr>
</table>';

$html .= '
<table width="100%" style="margin-bottom: 15px;">
    <tr>
        <td width="33%">
            <strong>Tasa del Día:</strong> ' . number_format($cierre['tasa'], 2, ',', '.') . '
        </td>
        <td width="33%" align="center">
            <strong>Generado por:</strong> ' . $_SESSION['nombre'] . ' ' . $_SESSION['apellido'] . '
        </td>
        <td width="33%" align="right">
            <strong>Fecha Impresión:</strong> ' . date("d/m/Y h:i A") . '
        </td>
    </tr>
</table>';

$html .= '
<h3 class="section-title">Totales del Día</h3>

<table width="100%" style="margin-top:10px; border-collapse:collapse;">
  <tr>

    <!-- COLUMNA USD -->
    <td width="50%" style="vertical-align:top; padding-right:10px;">
      <div style="border:1px solid #b3d9f3; border-radius:6px; padding:10px;">
        <h4 style="text-align:center; margin:0 0 8px 0; font-size:14px;">Totales en USD</h4>
        <table width="100%" style="border-collapse:collapse;">
          <tr><td>Total Día</td><td><strong>$ ' . number_format($cierre['total_dia_dl'], 2, ',', '.') . '</strong></td></tr>
          <tr><td>Efectivo</td><td>$ ' . number_format($cierre['total_efectivo_dl'], 2, ',', '.') . '</td></tr>
          <tr><td>Punto</td><td>$ ' . number_format($cierre['total_punto_dl'], 2, ',', '.') . '</td></tr>
          <tr><td>Pago Móvil</td><td>$ ' . number_format($cierre['total_pagomovil_dl'], 2, ',', '.') . '</td></tr>
          <tr><td>Divisas</td><td>$ ' . number_format($cierre['total_divisas_dl'], 2, ',', '.') . '</td></tr>
        </table>
      </div>
    </td>

    <!-- COLUMNA BS -->
    <td width="50%" style="vertical-align:top; padding-left:10px;">
      <div style="border:1px solid #b3d9f3; border-radius:6px; padding:10px;">
        <h4 style="text-align:center; margin:0 0 8px 0; font-size:14px;">Totales en Bolívares</h4>
        <table width="100%" style="border-collapse:collapse;">
          <tr><td>Total Día</td><td><strong>' . number_format($cierre['total_dia_bs'], 2, ',', '.') . ' Bs</strong></td></tr>
          <tr><td>Efectivo</td><td>' . number_format($cierre['total_efectivo_bs'], 2, ',', '.') . ' Bs</td></tr>
          <tr><td>Punto</td><td>' . number_format($cierre['total_punto_bs'], 2, ',', '.') . ' Bs</td></tr>
          <tr><td>Pago Móvil</td><td>' . number_format($cierre['total_pagomovil_bs'], 2, ',', '.') . ' Bs</td></tr>
          <tr><td>Divisas</td><td>' . number_format($cierre['total_divisas_bs'], 2, ',', '.') . ' Bs</td></tr>
        </table>
      </div>
    </td>

  </tr>
</table>

<!-- DETALLE DE VENTAS -->
<h3 class="section-title">Detalle de Ventas</h3>

<table class="tabla">
  <thead>
    <tr>
      <th>Nro Venta</th>
      <th>Producto</th>
      <th>Cantidad</th>
      <th>Modo / Precio</th>
      <th>Total ($)</th>
      <th>Hora</th>
    </tr>
  </thead>
  <tbody>';
foreach ($ventas as $v) {

    $stmtProductos->execute([$v['id_venta']]);
    $productos = $stmtProductos->fetchAll(PDO::FETCH_ASSOC);

    $listaProductos = '<ul class="sin-puntos">';
    $listaCantidad  = '<ul class="sin-puntos">';
    $listaModo      = '<ul class="sin-puntos">';

    foreach ($productos as $p) {

        $listaProductos .= '<li>'
            . $p['producto'] . ' - ' . $p['marca'] . ' - ' . $p['presentacion']
            . '</li>';

        $listaCantidad .= '<li>' . $p['cantidad'] . '</li>';

        $listaModo .= '<li>'
            . strtoupper($p['maode']) . ' (' . number_format($p['precio_unitario'], 2, ',', '.') . ' $)'
            . '</li>';
    }

    $listaProductos .= '</ul>';
    $listaCantidad  .= '</ul>';
    $listaModo      .= '</ul>';

    $html .= '
    <tr>
        <td>' . $v['id_venta'] . '</td>
        <td class="text-left">' . $listaProductos . '</td>
        <td>' . $listaCantidad . '</td>
        <td>' . $listaModo . '</td>
        <td>
            <strong>' . number_format($v['total_dl'], 2, ',', '.') . ' $</strong><br>
            <small>Bs. ' . number_format($v['total_bs'], 2, ',', '.') . '</small>
        </td>
       <td>' . date('h:i A', strtotime($v['hora'])) . '</td>
    </tr>';
}

$html .= '</tbody></table>';

$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();

// 4. Agregar numeración de páginas al canvas después del render
$canvas = $dompdf->getCanvas();
$canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
    $text = "Páginas $pageNumber de $pageCount";
    $font = $fontMetrics->getFont('Arial', 'normal');
    $size = 8;
    $width = $fontMetrics->getTextWidth($text, $font, $size);
    $x = $canvas->get_width() - $width - 40;
    $y = $canvas->get_height() - 35;
    $canvas->text($x, $y, $text, $font, $size);
});

$dompdf->stream("CIERRE_DIARO_NRO-$id_cierre.pdf", ["Attachment" => true]);
?>
