<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// panggil function utama
include __DIR__ . '/function.php';

echo "✅ TEST MASUK <br><br>";

// TEST ZONE
$country = "Japan";
$zone = getZone($country);

echo "Negara: $country <br>";
echo "Zone: $zone <br><br>";

// TEST ESTIMASI
$estimasi = getEstimasi($zone);
echo "Estimasi: $estimasi <br><br>";

// TEST HARGA
$berat = 2;
$metode = "Air";

$harga = getPrice($zone, $berat, $metode);
echo "Berat: $berat kg <br>";
echo "Metode: $metode <br>";
echo "Harga: Rp " . number_format($harga,0,',','.') . "<br>";