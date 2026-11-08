<?php
include '../koneksi.php';

$data = mysqli_query($conn,
"SELECT * FROM pengiriman ORDER BY id DESC LIMIT 1");

$d = mysqli_fetch_array($data);

echo "Pengiriman baru : ".$d['no_resi'];
?>