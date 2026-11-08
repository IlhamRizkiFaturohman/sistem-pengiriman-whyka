<?php
include_once __DIR__ . '/koneksi.php'; // penting: sekali saja

function getZone($country) {
    global $conn;

    $q = mysqli_query($conn, "SELECT zone FROM countries WHERE name='$country'");
    $d = mysqli_fetch_array($q);

    return $d['zone'] ?? 'Unknown';
}

function getEstimasi($zone) {
    if ($zone == "Asia") return "2 - 5 Hari";
    if ($zone == "Europe") return "5 - 10 Hari";
    if ($zone == "America") return "7 - 14 Hari";

    return "5 - 12 Hari";
}

function getPrice($zone, $weight, $method) {

    $rate = 0;

    if ($method == "Air Freight") {

        if ($zone == "Asia") $rate = 50000;
        if ($zone == "Europe") $rate = 120000;
        if ($zone == "America") $rate = 150000;

    } else {

        if ($zone == "Asia") $rate = 20000;
        if ($zone == "Europe") $rate = 50000;
        if ($zone == "America") $rate = 70000;
    }

    return $rate * $weight;
}