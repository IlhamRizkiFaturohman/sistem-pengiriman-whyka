<?php
include '../koneksi.php';
header('Content-Type: application/json');

$resi = $_GET['resi'] ?? '';

if($resi == ''){
    echo json_encode(['status'=>'error','msg'=>'Resi kosong']);
    exit;
}

// 1. AMBIL DATA TERAKHIR BUAT MARKER UTAMA
$stmt = $conn->prepare("SELECT * FROM tracking_history WHERE no_resi = ? ORDER BY created_at DESC LIMIT 1");
$stmt->bind_param("s", $resi);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    // Auto fix lat/lng kalau 0
    $lat = $data['lat'];
    $lng = $data['lng'];
    if(empty($lat) || empty($lng)){
    $lat = 0;
    $lng = 0;
}

    // 2. INI TAMBAHAN BARU - AMBIL SEMUA TITIK JALUR
    $stmt2 = $conn->prepare("
    SELECT
        lokasi,
        lat,
        lng,
        status,
        created_at
    FROM tracking_history
    WHERE no_resi = ?
    AND lat IS NOT NULL
    AND lng IS NOT NULL
    ORDER BY created_at ASC
");

$stmt2->bind_param("s", $resi);
$stmt2->execute();

$route_result = $stmt2->get_result();

$route = [];

while($row = $route_result->fetch_assoc()){

    $route[] = [
        'lat' => (float)$row['lat'],
        'lng' => (float)$row['lng'],
        'nama_tempat' => $row['lokasi'],
        'status' => $row['status']
    ];

}

    // 3. KIRIM DATA + ROUTE SEKALIAN
    echo json_encode([
        'status' => 'success',
        'data' => [
            'resi' => $data['no_resi'],
            'status' => $data['status'],
            'location' => $data['lokasi'],
            'catatan' => $data['catatan'],
            'lat' => (float)$lat,
            'lng' => (float)$lng,
            'jalur' => $data['jalur'] ?? 'laut',
            'updated_at' => $data['created_at']
        ],
        'route' => $route // INI KUNCINYA BUAT GAMBAR GARIS
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Resi tidak ditemukan'
    ]);
}
$stmt->close();

if(isset($stmt2)){
    $stmt2->close();
}

$conn->close();
?>