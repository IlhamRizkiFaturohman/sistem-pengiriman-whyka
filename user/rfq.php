<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>RFQ - Whyka Logistik</title>

    <style>
        body{
            font-family: Arial;
            background:#f4f6f9;
            padding:30px;
        }

        .container{
            max-width:700px;
            margin:auto;
            background:white;
            padding:25px;
            border-radius:10px;
            box-shadow:0 5px 15px rgba(0,0,0,0.1);
        }

        h3{
            text-align:center;
            margin-bottom:20px;
        }

        label{
            font-weight:bold;
        }

        input, textarea{
            width:100%;
            padding:10px;
            margin-top:5px;
            margin-bottom:15px;
            border-radius:6px;
            border:1px solid #ccc;
        }

        button{
            width:100%;
            padding:12px;
            background:#2563eb;
            color:white;
            border:none;
            border-radius:6px;
            font-size:16px;
            cursor:pointer;
        }

        button:hover{
            background:#1d4ed8;
        }

        .success{
            background:#16a34a;
            color:white;
            padding:10px;
            margin-bottom:15px;
            border-radius:6px;
        }

        .error{
            background:red;
            color:white;
            padding:10px;
            margin-bottom:15px;
            border-radius:6px;
        }
    </style>

</head>
<body>

<div class="container">

    <h3>📦 REQUEST QUOTE (RFQ)</h3>

    <!-- NOTIF SUCCESS -->
    <?php if(isset($_SESSION['success'])): ?>
        <div class="success">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <!-- NOTIF ERROR -->
    <?php if(isset($_SESSION['error'])): ?>
        <div class="error">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- FORM -->
    <form action="proses_rfq.php" method="POST">

        <label>Nama</label>
        <input type="text" name="nama" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Produk</label>
        <input type="text" name="produk" required>

        <label>Kota Asal</label>
        <input type="text" name="kota_asal" required>

        <label>Kota Tujuan</label>
        <input type="text" name="kota_tujuan" required>

        <label>Incoterms</label>
        <input type="text" name="incoterms" required>

        <label>Catatan</label>
        <textarea name="catatan" required></textarea>

        <button type="submit">🚀 Kirim RFQ</button>

    </form>

</div>

</body>
</html>