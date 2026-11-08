<?php
include '../koneksi.php';

$negara = $_GET['negara'] ?? '';
$jenis  = $_GET['jenis'] ?? '';

$q = mysqli_query($conn,"
SELECT *
FROM lokasi_logistik
WHERE negara='$negara'
AND jenis='$jenis'
ORDER BY nama_lokasi
");

echo '<option value="">Pilih Lokasi</option>';

while($row = mysqli_fetch_assoc($q)){
    echo '<option value="'.$row['id'].'">';
    echo $row['nama_lokasi'].' - '.$row['kota'];
    echo '</option>';
}
?>