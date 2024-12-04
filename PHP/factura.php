<?php

require 'conexion.php';
require '../fpdf/fpdf.php';
require '../helpers/NumeroALetras.php';

define('MONEDA', '$');
define('MONEDA_LETRA', 'pesos');
define('MONEDA_DECIMAL', 'centavos');

$idVenta = isset($_GET['idVenta']) ? $conexion->real_escape_string($_GET['idVenta']) : 1;

if (filter_var($idVenta, FILTER_VALIDATE_INT) === false) {
    $idVenta = 1;
}

$sqlVenta = "SELECT idVenta, nombreCliente, fechaVenta FROM ventas WHERE idVenta = $idVenta";
$resultado = $conexion->query($sqlVenta);

$numeroFilas = $resultado->num_rows;
if ($numeroFilas == 0) {
    echo 'No hay datos que coincidan con la consulta';
    exit;
}

$row_venta = $resultado->fetch_assoc();

$sqlVentaDetalles = "SELECT nombreProducto, cantidadVendida, precioUnitario, ventaTotal FROM ventas WHERE idVenta = $idVenta";
$resultadoDetalles = $conexion->query($sqlVentaDetalles);

$total = 0;


$pdf = new FPDF('P', 'mm', array(80, 200));
$pdf->AddPage();
$pdf->SetMargins(5, 5, 5);
$pdf->SetFont('Arial', 'B', 9);

// $pdf->Image('images/logo.png', 15, 2, 45);

$pdf->Ln(7);

$pdf->MultiCell(70, 5, 'Factura de tienda', 0, 'C');

$pdf->Ln(1);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(17, 5, mb_convert_encoding('Núm ticket: ', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(53, 5, $row_venta['idVenta'] . '      ' . 'Nombre del cliente: ' . $row_venta['nombreCliente'], 0, 1, 'L');
// $pdf->Cell(53, 5, $row_venta['nombreCliente'], 0, 1, 'R');

$pdf->Cell(70, 2, '-------------------------------------------------------------------------', 0, 1, 'L');

$pdf->Cell(10, 4, 'Cant.', 0, 0, 'L');
$pdf->Cell(30, 4, mb_convert_encoding('Descripción', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
$pdf->Cell(15, 4, 'Precio', 0, 0, 'C');
$pdf->Cell(15, 4, 'Importe', 0, 1, 'C');

$pdf->Cell(70, 2, '-------------------------------------------------------------------------', 0, 1, 'L');

$totalProductos = 0;
$pdf->SetFont('Arial', '', 7);

while ($row_detalle = $resultadoDetalles->fetch_assoc()) {
    $importe = number_format($row_detalle['ventaTotal'], 2, '.', ',');
    $totalProductos += $row_detalle['cantidadVendida'];
    $total += number_format($row_detalle['ventaTotal'], 2, '.', ',');


    $pdf->Cell(10, 4, $row_detalle['cantidadVendida'], 0, 0, 'L');

    $yInicio = $pdf->GetY();
    $pdf->MultiCell(30, 4, mb_convert_encoding($row_detalle['nombreProducto'], 'ISO-8859-1', 'UTF-8'), 0, 'L');
    $yFin = $pdf->GetY();

    $pdf->SetXY(45, $yInicio);

    $pdf->Cell(15, 4, MONEDA . ' ' . number_format($row_detalle['precioUnitario'], 2, '.', ','), 0, 0, 'C');

    $pdf->SetXY(60, $yInicio);
    $pdf->Cell(15, 4, MONEDA . ' ' . $importe, 0, 1, 'R');
    $pdf->SetY($yFin);
}

$resultado->close();

$pdf->Ln();

$pdf->Cell(70, 4, mb_convert_encoding('Número de articulos:  ' . $totalProductos, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(70, 5, sprintf('Total: %s  %s', MONEDA, number_format($total, 2, '.', ',')), 0, 1, 'R');

$pdf->Ln(2);

$pdf->SetFont('Arial', '', 8);
// $pdf->MultiCell(70, 4, 'Son ' . strtolower(NumeroALetras::convertir($total, MONEDA_LETRA, MONEDA_DECIMAL)), 0, 'L', 0);

$pdf->Ln();

$pdf->Cell(35, 5, 'Fecha: ' . $row_venta['fechaVenta'], 0, 0, 'C');
// $pdf->Cell(35, 5, 'Hora: ' . $row_venta['hora'], 0, 1, 'C');

$pdf->Ln();

$pdf->MultiCell(70, 5, 'AGRADECEMOS SU PREFERENCIA VUELVA PRONTO!!!', 0, 'C');

// $resultado->close();
$conexion->close();

$pdf->Output();
?>