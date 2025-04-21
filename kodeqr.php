<?php
include "phpqrcode/qrlib.php";

// URL tujuan
$url = "http://psikotesku123.42web.io/daftar.php";

// Lokasi file hasil QR sementara
$tempDir = "temp/";
if (!file_exists($tempDir)) {
    mkdir($tempDir);
}

$fileName = $tempDir . "daftar_qr.png";

// Generate QR Code
QRcode::png($url, $fileName, QR_ECLEVEL_L, 5);

// Tampilkan gambar
echo "<h2>Scan QR Code untuk Mendaftar Psikotes</h2>";
echo "<img src='" . $fileName . "' alt='QR Code'>";
?>
