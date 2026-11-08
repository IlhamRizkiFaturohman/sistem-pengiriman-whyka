<?php
include '../koneksi.php';

if(isset($_POST['update'])){
    $resi = $_POST['no_resi'];
    $status = $_POST['status'];
    $catatan = $_POST['catatan'];
    $lokasi_id = $_POST['lokasi_id'];

    $qLokasi = mysqli_query($conn,"
    SELECT *
    FROM lokasi_logistik
    WHERE id='$lokasi_id'
    ");

    $dataLokasi = mysqli_fetch_assoc($qLokasi);

    $lokasi = $dataLokasi['nama_lokasi'];
    $negara = $dataLokasi['negara'];
    $lat = $dataLokasi['lat'];
    $lng = $dataLokasi['lng'];
    if($dataLokasi['jenis'] == 'bandara'){
    $jalur = 'udara';
}else{
    $jalur = 'laut';
}

    // 1. SIMPAN HISTORI TRACKING BARU
$stmt = $conn->prepare("
INSERT INTO tracking_history
(no_resi, status, lokasi, catatan, lat, lng, jalur)
VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssdds",
    $resi,
    $status,
    $lokasi,
    $catatan,
    $lat,
    $lng,
    $jalur
);

$stmt->execute();


// 2. SIMPAN TITIK RUTE UNTUK GARIS MAP
$stmt2 = $conn->prepare("
INSERT INTO route_points
(no_resi, lat, lng, nama_tempat, negara)
VALUES (?, ?, ?, ?, ?)
");

$stmt2->bind_param(
    "sddss",
    $resi,
    $lat,
    $lng,
    $lokasi,
    $negara
);

$stmt2->execute();

/*
|--------------------------------------------------------------------------
| UPDATE STATUS PENGIRIMAN
|--------------------------------------------------------------------------
*/

mysqli_query($conn,"
UPDATE pengiriman
SET status='$status'
WHERE no_resi='$resi'
");

/*
|--------------------------------------------------------------------------
| UPDATE CUSTOMS STATUS JIKA CUSTOMS
|--------------------------------------------------------------------------
*/

if($status == 'customs'){

    mysqli_query($conn,"
    UPDATE pengiriman
    SET customs_status='clearance'
    WHERE no_resi='$resi'
    ");

}

if($status == 'delivered'){

    mysqli_query($conn,"
    UPDATE pengiriman
    SET customs_status='completed'
    WHERE no_resi='$resi'
    ");

}

echo "
<script>
alert('Sukses! Tracking dan titik rute berhasil disimpan');
window.location='track_resi.php';
</script>
";

exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Lokasi - Whyka</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>body{background:#1a0a0a;color:white;padding:30px}#map{height:400px;border-radius:15px;margin:15px 0}</style>
</head>
<body>
<?php

// ambil resi

$id = $_GET['id'] ?? '';

if($id == ''){
    die("ID kosong bro");
}

$q = mysqli_query($conn,"
SELECT no_resi, negara_asal, negara_tujuan, metode_pengiriman
FROM pengiriman
WHERE id='$id'
LIMIT 1
");

$data = mysqli_fetch_assoc($q);

if(!$data){
    die("Data ga ketemu bro");
}

$resi = $data['no_resi'];


// tentukan jenis lokasi
if($data['metode_pengiriman'] == 'Air Freight'){
    $jenis = 'bandara';
}else{
    $jenis = 'pelabuhan';
}

// ambil lokasi sesuai negara + jenis
$lokasi_q = mysqli_query($conn,"
SELECT *
FROM lokasi_logistik
WHERE jenis='$jenis'
AND (
    negara='".$data['negara_asal']."'
    OR negara='".$data['negara_tujuan']."'
)
ORDER BY negara, nama_lokasi
");
?>

<form method="POST">

<input type="text" value="<?= $resi; ?>" class="form-control mb-2" readonly>
<input type="hidden" name="no_resi" value="<?= $resi; ?>">

<select name="status" class="form-control mb-2">
    <option value="booking">Booking</option>
    <option value="pickup">Pickup</option>
    <option value="in transit">In Transit</option>
    <option value="customs">Customs Clearance</option>
    <option value="arrived">Arrived</option>
    <option value="delivered">Delivered</option>
</select>

<select name="lokasi_id" class="form-control mb-2" required>
    <option value="">Pilih Lokasi</option>

    <?php while($r = mysqli_fetch_assoc($lokasi_q)){ ?>
        <option value="<?= $r['id']; ?>">
            <?= $r['nama_lokasi']; ?> - <?= $r['negara']; ?>
        </option>
    <?php } ?>

</select>

<input name="catatan" placeholder="Catatan" class="form-control mb-2">

<button name="update" class="btn btn-danger w-100">
    UPDATE & AUTO SIMPAN JALUR
</button>

</form>


</body>
</html>