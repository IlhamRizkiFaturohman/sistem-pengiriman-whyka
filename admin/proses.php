<?php
include '../koneksi.php';
include '../config/negara.php';

if (!isset($_GET['id']) || !isset($_GET['status'])) {
    header("Location: pengiriman.php");
    exit();
}

$id = intval($_GET['id']);
$status = $_GET['status'];

$allowed = ['booking','loading','customs','in transit','arrived','delivered'];

if (!in_array($status, $allowed)) {
    header("Location: pengiriman.php");
    exit();
}

$cek = mysqli_query($conn,"
SELECT *
FROM pengiriman
WHERE id='$id'
");

$data = mysqli_fetch_assoc($cek);

if (!$data) {
    header("Location: pengiriman.php");
    exit();
}

$resi = $data['no_resi'];
$tujuan = trim($data['negara']);

$getCountry = mysqli_query($conn,"
SELECT *
FROM countries
WHERE name='$tujuan'
LIMIT 1
");

$country = mysqli_fetch_assoc($getCountry);

mysqli_query($conn, "
UPDATE pengiriman 
SET status='$status'
WHERE id='$id'
");

/*
|--------------------------------------------------------------------------
| TRACKING GLOBAL AUTO
|--------------------------------------------------------------------------
*/

if($status == 'booking'){

    $lokasi = "Jakarta, Indonesia";
    $catatan = "Shipment booking berhasil dibuat";
    $lat = -6.200000;
    $lng = 106.816666;

}

elseif($status == 'loading'){

    $lokasi = "Singapore";
    $catatan = "Barang sedang dimuat ke cargo";
    $lat = 1.3521;
    $lng = 103.8198;

}

elseif($status == 'customs'){

    $lokasi = "Dubai, UAE";
    $catatan = "Shipment sedang proses customs clearance";
    $lat = 25.2048;
    $lng = 55.2708;

}

elseif($status == 'in transit'){

    if($country){

        $lokasi = $country['name'];
        $lat = $country['latitude'];
        $lng = $country['longitude'];

    }else{

        $lokasi = $tujuan;
        $lat = 0;
        $lng = 0;

    }

    $catatan = "Shipment sedang dalam perjalanan internasional";
}

elseif($status == 'arrived'){

    if($country){

        $lokasi = $country['name'];
        $lat = $country['latitude'];
        $lng = $country['longitude'];

    }else{

        $lokasi = $tujuan;
        $lat = 0;
        $lng = 0;

    }

    $catatan = "Shipment telah tiba di negara tujuan";
}

elseif($status == 'delivered'){

    if($country){

        $lokasi = $country['name'];
        $lat = $country['latitude'];
        $lng = $country['longitude'];

    }else{

        $lokasi = $tujuan;
        $lat = 0;
        $lng = 0;

    }

    $catatan = "Shipment berhasil diterima penerima";
}


mysqli_query($conn, "
INSERT INTO tracking_history (
    pengiriman_id,
    no_resi,
    status,
    lokasi,
    catatan,
    lat,
    lng
)
VALUES (
    '$id',
    '$resi',
    '$status',
    '$lokasi',
    '$catatan',
    '$lat',
    '$lng'
)
");

header("Location: pengiriman.php");
exit();
?>