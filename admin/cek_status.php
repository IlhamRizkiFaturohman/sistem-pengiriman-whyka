<?php
header('Content-Type: application/json');
include '../koneksi.php';

$resi = $_GET['resi'] ?? '';

if($resi == ''){
    echo json_encode(['status'=>'error','msg'=>'Resi kosong']);
    exit;
}

$resi = mysqli_real_escape_string($conn, $resi);

// Ambil data tracking
$query = mysqli_query($conn, "SELECT * FROM tracking_history WHERE no_resi = '$resi' ORDER BY created_at DESC LIMIT 1");

if(!$query){
    echo json_encode(['status'=>'error','msg'=>'Query error: '.mysqli_error($conn)]);
    exit;
}

if(mysqli_num_rows($query) == 0){
    echo json_encode(['status'=>'error','msg'=>'Resi tidak ditemukan']);
    exit;
}

$data = mysqli_fetch_assoc($query);

// AUTO GENERATE LAT/LNG kalau null
$lat = $data['lat'];
$lng = $data['lng'];

if(empty($lat) || empty($lng) || $lat == 0 || $lng == 0){
    // Generate random koordinat Indonesia biar map nya gerak
    $lat = -6.2088 + (rand(-100, 100) / 1000); // Jakarta + random
    $lng = 106.8456 + (rand(-100, 100) / 1000);
    
    // Update ke DB biar next ga generate lagi
    $update = mysqli_query($conn, "UPDATE tracking_history SET lat='$lat', lng='$lng' WHERE id='".$data['id']."'");
}

// Ambil jalur dari DB, kalau kosong default 'laut'
$jalur = $data['jalur'] ?? 'laut';

echo json_encode([
    'status' => 'success',
    'data' => [
        'resi' => $data['no_resi'],
        'lat' => (float)$lat,
        'lng' => (float)$lng,
        'jalur' => $jalur,  // <-- INI YANG DITAMBAHIN
        'status_pengiriman' => $data['status_pengiriman'],
        'update_terakhir' => $data['update_terakhir'],
        'nama_penerima' => $data['nama_penerima'] ?? ''
    ]
]);

// Ambil jalur dari DB, kalau kosong default 'laut'
$jalur = $data['jalur'] ?? 'laut';

echo json_encode([
    'status' => 'success',
    'data' => [
        'resi' => $data['no_resi'],
        'lat' => (float)$lat,
        'lng' => (float)$lng,
        'jalur' => $jalur,  // <-- INI YANG DITAMBAHIN
        'status_pengiriman' => $data['status_pengiriman'],
        'update_terakhir' => $data['update_terakhir'],
        'nama_penerima' => $data['nama_penerima'] ?? ''
    ]
]);
?>