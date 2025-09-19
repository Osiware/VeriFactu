<?php
/*
Esto es un ejemplo en PHP de la generación de Facturas con QR.
Plantilla profesional de factura en PDF con QR tributario AEAT usando TCPDF:
Cabecera corporativa con logo + datos de empresa.
Bloque de cliente destacado.
Tabla de líneas con estilos.
Totales resaltados.
QR oficial en el pie, con texto explicativo.
Se require TCPDF,Endroid (PHP)
*/
require __DIR__ . '/vendor/autoload.php';

//use TCPDF; /*si no se usa composer (autoload) quitar de este comentario. (//) */
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;

// ==== DATOS DE EJEMPLO ====
// Empresa
$empresaNombre = "Empresa de Pruebas S.L.";
$empresaNif    = "B00000000";
$empresaDir    = "Calle de Pruebas, num. 16 local";
$empresaTlf    = "+34 999 999 999";
$empresaEmail  = "info@example.com";
$empresaLogo   = __DIR__ . "/logo190.png"; // logo PNG/JPG opcional

// Cliente
$clienteNombre = "Cliente Ejemplo";
$clienteNif    = "X00000000";
$clienteDir    = "Avda. Divinidad 45, 08000 Barcelona";

// Factura
$numFactura    = "F2025-000123";
$fechaFactura  = "20250918"; // AAAAMMDD
$fechaLegible  = "18/09/2025";
$idVerifactu   = "VF-ABC123XYZ";
$moneda        = "EUR";

// Líneas de factura
$lineas = [
    ["Servicios profesionales", 1, 1000.00, 0.21],
    ["Consultoría adicional", 1, 200.00, 0.21],
    ["Material de oficina", 3, 10.00, 0.21],
	["  ", 0, 0, 0],
	[" !!!   !!!   !!!   !!!", 0, 0, 0],
	["AVISO apartir del 1 de Enero obligatorio", 0, 0, 0],
	["para Sociedades y 1 Julio Autonomos", 0, 0, 0],
	["( Factura sin valor real solo es un aviso )", 0, 0, 0],
	["Esto es un ejemplo de la generación", 0,0,0],
	["de una factura con QR de verificación", 0,0,0],
	["hemos realizado un ejemplo en PHP (disponoble)", 0,0,0],
	["que requiere librerias externas y la", 0,0,0],
	["obtención del código VeriFactu de la AEAT", 0,0,0],
	["previo a esta generación.", 0,0,0],
	[" ", 0, 0, 0],
	[" !!! No te olvides !!!", 0, 0, 0]
];

// ==== CÁLCULOS ====
$baseTotal = 0;
$ivaTotal  = 0;
foreach ($lineas as $l) {
    $base = $l[1] * $l[2];
    $iva  = $base * $l[3];
    $baseTotal += $base;
    $ivaTotal  += $iva;
}
$importeTotal = $baseTotal + $ivaTotal;

// ==== URL AEAT PARA QR ====
$baseUrl = "https://www2.agenciatributaria.gob.es/wlpl/OVCF-AUTOFACTU/inicio";
$query = http_build_query([
    'idVerifactu' => $idVerifactu,
    'nif'         => $empresaNif,
    'importe'     => number_format($importeTotal, 2, ".", ""),
    'fecha'       => $fechaFactura,
    'numfactura'  => $numFactura
]);
$urlQR = $baseUrl . "?" . $query;

// ==== GENERAR QR ====
$qr = Builder::create()
    ->writer(new PngWriter())
    ->data($urlQR)
    ->encoding(new Encoding('UTF-8'))
    ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
    ->size(180)
    ->margin(5)
    ->build();
$qrPath = __DIR__ . "/qr_temp.png";
file_put_contents($qrPath, $qr->getString());

// ==== CREAR PDF ====
$pdf = new TCPDF();
$pdf->SetCreator("Factura PHP");
$pdf->SetAuthor($empresaNombre);
$pdf->SetTitle("Factura $numFactura");

// Márgenes
$pdf->SetMargins(15, 20, 15);
$pdf->AddPage();

// ==== CABECERA ====
if (file_exists($empresaLogo)) {
    $pdf->Image($empresaLogo, 15, 11, 30);
}
$pdf->SetFont("helvetica", "B", 14);
$pdf->SetXY(50, 15);
$pdf->Cell(0, 6, $empresaNombre, 0, 1);
$pdf->SetFont("helvetica", "", 10);
$pdf->SetX(50);
$pdf->MultiCell(0, 5, "NIF: $empresaNif\n$empresaDir\nTel: $empresaTlf\nEmail: $empresaEmail", 0);

// Línea separadora
$pdf->Line(15, 40, 195, 40);

// ==== TÍTULO FACTURA ====
$pdf->SetY(40);
$pdf->SetFont("helvetica", "B", 16);
$pdf->Cell(0, 10, "FACTURA", 0, 1, "C");
$pdf->Ln(2);

// ==== DATOS FACTURA ====
$pdf->SetFont("helvetica", "", 10);
$pdf->Cell(95, 5, "Factura Nº: $numFactura", 0, 0, "L");
$pdf->Cell(95, 5, "Fecha: $fechaLegible", 0, 1, "R");
$pdf->Ln(3);

// ==== DATOS CLIENTE ====
$pdf->SetFont("helvetica", "B", 11);
$pdf->Cell(0, 5, "Cliente:", 0, 1);
$pdf->SetFont("helvetica", "", 10);
$pdf->MultiCell(0, 5, "$clienteNombre\nNIF: $clienteNif\n$clienteDir", 0, "L");
$pdf->Ln(5);

// ==== TABLA DE CONCEPTOS ====
$pdf->SetFont("helvetica", "B", 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->Cell(80, 7, "Concepto", 1, 0, "C", true);
$pdf->Cell(20, 7, "Cant.", 1, 0, "C", true);
$pdf->Cell(30, 7, "Precio", 1, 0, "C", true);
$pdf->Cell(30, 7, "IVA", 1, 0, "C", true);
$pdf->Cell(30, 7, "Total", 1, 1, "C", true);

$pdf->SetFont("helvetica", "", 9);
foreach ($lineas as $l) {
    $base = $l[1] * $l[2];
    $iva  = $base * $l[3];
    $totalLinea = $base + $iva;

    $pdf->Cell(80, 8, $l[0], 1);
    $pdf->Cell(20, 8, $l[1], 1, 0, "C");
    $pdf->Cell(30, 8, number_format($l[2], 2, ",", ".") . " €", 1, 0, "R");
    $pdf->Cell(30, 8, ($l[3]*100) . "%", 1, 0, "C");
    $pdf->Cell(30, 8, number_format($totalLinea, 2, ",", ".") . " €", 1, 1, "R");
}

// ==== TOTALES ====
$pdf->SetFont("helvetica", "B", 11);
$pdf->Cell(160, 8, "Base imponible:", 1, 0, "R");
$pdf->Cell(30, 8, number_format($baseTotal, 2, ",", ".") . " €", 1, 1, "R");
$pdf->Cell(160, 8, "IVA:", 1, 0, "R");
$pdf->Cell(30, 8, number_format($ivaTotal, 2, ",", ".") . " €", 1, 1, "R");
$pdf->Cell(160, 8, "TOTAL:", 1, 0, "R", true);
$pdf->Cell(30, 8, number_format($importeTotal, 2, ",", ".") . " €", 1, 1, "R", true);

// ==== QR + TEXTO PIE ====
$pdf->Image($qrPath, 165, 246, 30, 30, "PNG");
$pdf->SetFont("helvetica", "", 8);
$pdf->SetXY(15, 260);
$pdf->MultiCell(140, 5, "Este código QR permite verificar la validez de la factura en la sede electrónica de la AEAT (Agencia Tributaria).", 0, "L");

// ==== EXPORTAR ====
$filename = __DIR__ . "/factura_$numFactura.pdf";
$pdf->Output($filename, "F");

echo "Factura generada: $filename\n";
unlink($qrPath);
