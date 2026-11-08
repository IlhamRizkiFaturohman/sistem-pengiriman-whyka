<?php
include 'koneksi.php';

$data = null;

if (isset($_POST['cek'])) {
    $resi = mysqli_real_escape_string($conn, $_POST['no_resi']);

    $query = mysqli_query($conn, "SELECT * FROM pengiriman WHERE no_resi='$resi'");
    $data = mysqli_fetch_assoc($query);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cek Resi - Whyka Logistics</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            text-align: center;
        }
        .box {
            background: white;
            width: 400px;
            margin: 100px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input {
            width: 80%;
            padding: 10px;
            margin: 10px;
        }
        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }
        .result {
            margin-top: 20px;
            text-align: left;
            background: #fafafa;
            padding: 10px;
            border-radius: 5px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Cek Resi</h2>

    <form method="POST">
        <input type="text" name="no_resi" placeholder="Masukkan No Resi" required>
        <br>
        <button name="cek">Cek</button>
    </form>

    <?php if ($data) { ?>
        <div class="result">
            <h3>Hasil Tracking</h3>
            <p><b>No Resi:</b> <?= $data['no_resi']; ?></p>
            <p><b>Nama Barang:</b> <?= $data['nama_barang']; ?></p>
            <p><b>Tujuan:</b> <?= $data['tujuan']; ?></p>
            <p><b>Status:</b> <?= !empty($data['status']) ? $data['status'] : 'Belum diproses'; ?></p>
        </div>
    <?php } elseif (isset($_POST['cek'])) { ?>
        <p class="error">Resi tidak ditemukan!</p>
    <?php } ?>

</div>

</body>
</html>