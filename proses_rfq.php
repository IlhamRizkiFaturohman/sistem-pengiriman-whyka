<?php
include 'koneksi.php';

$nama = $_POST['nama'];
$email = $_POST['email'];
$produk = $_POST['produk'];
$kota_asal = $_POST['kota_asal'];
$kota_tujuan = $_POST['kota_tujuan'];
$catatan = $_POST['catatan'];

$status = 'Pending';
$resi = NULL;

// 🔥 HANYA 1 INSERT (WAJIB SINKRON DENGAN DB)
mysqli_query($conn, "
INSERT INTO rfq (
    nama,
    email,
    produk,
    kota_asal,
    kota_tujuan,
    catatan,
    status,
    resi
)
VALUES (
    '$nama',
    '$email',
    '$produk',
    '$kota_asal',
    '$kota_tujuan',
    '$catatan',
    '$status',
    '$resi'
)
");

echo "<script>
alert('RFQ berhasil dikirim!');
window.location='index.php';
</script>";
?>