<?php
include '../koneksi.php';

$id = $_GET['id'] ?? '';

$q = mysqli_query($conn, "SELECT * FROM pengiriman WHERE id='$id'");
$d = mysqli_fetch_assoc($q);

if(!$d){
    die("Data ga ketemu bro");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice - Whyka</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f5f5f5;
            padding:20px;
        }

        .invoice-box{
            max-width:900px;
            margin:auto;
            background:#fff;
            padding:25px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        .header{
            display:flex;
            justify-content:space-between;
            align-items:center;
            border-bottom:2px solid #000;
            padding-bottom:10px;
        }

        .logo-wrap{
            display:flex;
            align-items:center;
            gap:10px;
        }

        .logo{
            font-size:22px;
            font-weight:bold;
        }

        .sub{
            font-size:12px;
            color:#666;
        }

        .box-right{
            text-align:right;
            font-size:13px;
        }

        .row{
            display:flex;
            justify-content:space-between;
            margin-top:20px;
        }

        .col{
            width:48%;
            font-size:13px;
        }

        .section-title{
            margin-top:25px;
            font-weight:bold;
            border-bottom:1px solid #ddd;
            padding-bottom:5px;
        }

        table{
            width:100%;
            margin-top:10px;
            border-collapse:collapse;
        }

        table td{
            padding:8px;
            border-bottom:1px solid #eee;
            font-size:13px;
        }

        table td:first-child{
            width:200px;
            font-weight:bold;
        }

        .footer{
            text-align:center;
            margin-top:30px;
            font-size:12px;
            color:#777;
        }

        .print-btn{
            margin-top:20px;
            text-align:right;
        }

        .btn{
            background:#000;
            color:#fff;
            padding:10px 15px;
            border:none;
            cursor:pointer;
            border-radius:5px;
        }

        @media print{
            body{
                background:#fff;
            }
            .print-btn{
                display:none;
            }
            .invoice-box{
                box-shadow:none;
                border:none;
            }
        }
    </style>
</head>

<body>

<div class="invoice-box">

    <!-- HEADER -->
    <div class="header">

        <div class="logo-wrap">
            <img src="../assets/img/Logo PT.png" style="width:50px; height:50px; object-fit:contain;">
            
            <div>
                <div class="logo">WHYKA EXPRESS</div>
                <div class="sub">PT WHYKA INTERNATIONAL LOGISTICS</div>
            </div>
        </div>

        <div class="box-right">
            <div><b>RESI:</b> <?= $d['no_resi']; ?></div>
            <div><b>STATUS:</b> <?= strtoupper($d['status']); ?></div>
            <div><b>Service:</b> <?= $d['service_type']; ?></div>
        </div>

    </div>

    <!-- SHIPPER / CONSIGNEE -->
    <div class="row">
        <div class="col">
            <b>SHIPPER</b><br>
            <?= $d['shipper'] ?? '-' ?><br>
            <?= $d['shipper_address'] ?? '-' ?>
        </div>

        <div class="col">
            <b>CONSIGNEE</b><br>
            <?= $d['consignee'] ?? '-' ?><br>
            <?= $d['consignee_address'] ?? '-' ?>
        </div>
    </div>

    <!-- SHIPMENT DETAIL -->
    <div class="section-title">SHIPMENT DETAILS</div>

    <table>
        <tr><td>Barang</td><td><?= $d['nama_barang']; ?></td></tr>
        <tr><td>Qty</td><td><?= $d['qty']; ?></td></tr>
        <tr><td>Berat</td><td><?= $d['berat']; ?> KG</td></tr>
        <tr><td>Dimensi</td><td><?= $d['dimensi']; ?></td></tr>
        <tr><td>Route</td><td><?= $d['negara_asal']; ?> → <?= $d['negara_tujuan']; ?></td></tr>
        <tr><td>Port</td><td><?= $d['port_asal']; ?> → <?= $d['port_tujuan']; ?></td></tr>
        <tr><td>Currency</td><td><?= $d['currency']; ?></td></tr>
        <tr><td>Duty Tax</td><td><?= $d['duty_tax']; ?></td></tr>
        <tr><td>Customs</td><td><?= $d['customs_status']; ?></td></tr>
        <tr><td>Service</td><td><?= $d['service_type']; ?></td></tr>
    </table>

    <!-- CARGO -->
    <div class="section-title">CARGO DETAILS</div>
    <div style="border:1px solid #ddd; padding:10px; font-size:13px;">
        <?= nl2br($d['cargo_details'] ?? '-') ?>
    </div>

    <!-- PRINT BUTTON -->
    <div class="print-btn">
        <button onclick="window.print()" class="btn">🖨️ Print Invoice</button>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        WHYKA EXPRESS INTERNATIONAL LOGISTICS
    </div>

</div>

</body>
</html>