<?php
include '../koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['simpan'])) {

    // AUTO RESI
    $resi = "WHYKA-" . date("YmdHis") . "-" . rand(1000,9999);

    // SESSION
    $id_user = $_SESSION['id_user'] ?? 0;
    $username = $_SESSION['username'] ?? '';

    // FORM INPUT
    $barang = $_POST['nama_barang'] ?? '';
    $berat = (float) ($_POST['berat'] ?? 0);
    if ($berat <= 0) {
    die("Berat harus lebih dari 0 kg");
}

    $negara = $_POST['negara'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $kode_pos = $_POST['kode_pos'] ?? '';
    $metode = $_POST['metode_pengiriman'] ?? 'Sea Freight';
    $penerima = $_POST['nama_penerima'] ?? '';
    $hp = $_POST['no_hp'] ?? '';
    if (!preg_match('/^[0-9+\-\s]+$/', $hp)) {
    die("Nomor HP tidak valid");
    }
    $kota = $_POST['kota'] ?? '';

    $negara_asal = $_POST['negara_asal'] ?? 'Indonesia';
    $negara_tujuan = $negara;


    $cargo_details = $_POST['cargo_details'] ?? '';
    $qty = (int) ($_POST['qty'] ?? 1);
    if ($qty < 1) {
    die("Qty minimal 1");
    }
    
    $dimensi = $_POST['dimensi'] ?? '';
    $duty_tax = $_POST['duty_tax'] ?? '0';
    $customs_status = $_POST['customs_status'] ?? 'pending';
    $currency = $_POST['currency'] ?? 'IDR';
    $hs_code = $_POST['hs_code'] ?? '';
    $hs_code = mysqli_real_escape_string($conn, $hs_code);

    if ($metode == 'Air Freight') {
    $price_total = max(500000, $berat * 150000);
    } else {
    $price_total = max(300000, $berat * 50000);
    }

    // SHIPPER / CONSIGNEE FIX
    $shipper = $username;
    $shipper_address = $alamat;

    $consignee = $penerima;
    $consignee_address = $alamat;

    include "../phpqrcode/qrlib.php";

    $tempDir = __DIR__ . "/../assets/qrcode/";
    if (!is_dir($tempDir)) {
    mkdir($tempDir, 0777, true);
    }

   $fileName = $resi . ".png";
    $path = $tempDir . $fileName;
    $url = "http://localhost/whyka-logistik/tracking.php?resi=" . $resi;

    // BUAT QR CODE
    QRcode::png($url, $path, QR_ECLEVEL_L, 5);

    // CEK HASIL QR
    if (!file_exists($path)) {
    die("QR gagal dibuat");
    }

    $airway_bill = 'AWB-' . rand(100000,999999);


    // ESCAPE
$resi = mysqli_real_escape_string($conn, $resi);

$username = $_SESSION['username'] ?? 'ADMIN';
$id_user = $_SESSION['id_user'] ?? 0;

$nama_pengirim = mysqli_real_escape_string($conn, $username);

$penerima = mysqli_real_escape_string($conn, $penerima);
$barang = mysqli_real_escape_string($conn, $barang);
$negara = mysqli_real_escape_string($conn, $negara);
$kota = mysqli_real_escape_string($conn, $kota);
$alamat = mysqli_real_escape_string($conn, $alamat);

$shipper = mysqli_real_escape_string($conn, $shipper);
$shipper_address = mysqli_real_escape_string($conn, $shipper_address);

$consignee = mysqli_real_escape_string($conn, $consignee);
$consignee_address = mysqli_real_escape_string($conn, $consignee_address);

$cargo_details = mysqli_real_escape_string($conn, $cargo_details);

$hs_code = mysqli_real_escape_string($conn, $hs_code);
$airway_bill = mysqli_real_escape_string($conn, $airway_bill);

$jalur_fix = ($metode == 'Air Freight') ? 'bandara' : 'pelabuhan';

// AMBIL ASAL
$q1 = mysqli_query($conn,"
SELECT * FROM lokasi_logistik
WHERE jenis='$jalur_fix'
AND negara='$negara_asal'
LIMIT 1
");

$asal = mysqli_fetch_assoc($q1);

// AMBIL TUJUAN
$q2 = mysqli_query($conn,"
SELECT * FROM lokasi_logistik
WHERE jenis='$jalur_fix'
AND negara='$negara_tujuan'
LIMIT 1
");

$tujuan = mysqli_fetch_assoc($q2);

// FALLBACK
if (!$asal) {
    $asal = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM lokasi_logistik
    WHERE jenis='$jalur_fix'
    LIMIT 1
    "));
}

if (!$tujuan) {
    $tujuan = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT * FROM lokasi_logistik
    WHERE jenis='$jalur_fix'
    LIMIT 1
    "));
}

$port_asal = $asal['nama_lokasi'] ?? '';
$port_tujuan = $tujuan['nama_lokasi'] ?? '';

    // INSERT PENGIRIMAN
    $insert = mysqli_query($conn, "INSERT INTO pengiriman (
        no_resi, nama_pengirim, nama_penerima, berat, status, nama_barang,
        negara, kota, alamat, kode_pos, metode_pengiriman, no_hp, tanggal_masuk,
        shipper, shipper_address, consignee, consignee_address, airway_bill, hs_code,
        negara_asal, negara_tujuan, port_asal, port_tujuan, cargo_details, qty,
        dimensi, duty_tax, customs_status, service_type, currency,
        price_total, username, id_user
    ) 
    VALUES (
        '$resi', '$nama_pengirim', '$penerima', '$berat', 'booking', '$barang',
        '$negara', '$kota', '$alamat', '$kode_pos', '$metode', '$hp', NOW(),
        '$shipper','$shipper_address','$consignee','$consignee_address','$airway_bill','$hs_code',
        '$negara_asal', '$negara_tujuan', '$port_asal', '$port_tujuan', '$cargo_details', '$qty',
        '$dimensi', '$duty_tax', '$customs_status', '$metode', '$fileName', '$currency',
        '$price_total', '$username', '$id_user'
    )");

    if ($insert) {

       $lokasi_awal = $asal['nama_lokasi'] ?? 'Unknown';
        $lat_awal = $asal['lat'] ?? 0;
        $lng_awal = $asal['lng'] ?? 0;

        $lokasi_tujuan = $tujuan['nama_lokasi'] ?? 'Unknown';
        $lat_tujuan = $tujuan['lat'] ?? 0;
        $lng_tujuan = $tujuan['lng'] ?? 0;

        mysqli_query($conn, "
            INSERT INTO tracking_history (
                no_resi, status, lokasi, catatan, lat, lng, jalur
            ) VALUES (
                '$resi',
                'booking',
                '$lokasi_awal → $lokasi_tujuan',
                'Rute pengiriman dari $lokasi_awal ke $lokasi_tujuan',
                '$lat_awal',
                '$lng_awal',
                '$jalur_fix'
            )
        ");

        echo "<script>
            alert('Pengiriman berhasil dibuat! Resi: $resi');
            window.location='pengiriman.php';
        </script>";
        exit();

    } else {
        echo "ERROR INSERT: " . mysqli_error($conn);
    }

} else {
    $resi = 'WHYKA-' . date('YmdHis') . '-' . rand(1000,9999);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Tambah Pengiriman - Whyka Logistics</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* =========================
   GLOBAL RESET
========================= */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    min-height: 100vh;
    background: linear-gradient(135deg, #1a0a0a 0%, #2d1010 50%, #1a0a0a 100%);
    overflow-x: hidden;
    color: white;
}

/* Background glow */
body::before,
body::after {
    content: "";
    position: fixed;
    border-radius: 50%;
    filter: blur(150px);
    z-index: -1;
}

body::before {
    width: 500px;
    height: 500px;
    background: #dc2626;
    opacity: 0.15;
    top: -120px;
    left: -120px;
}

body::after {
    width: 400px;
    height: 400px;
    background: #f87171;
    opacity: 0.1;
    bottom: -120px;
    right: -120px;
}

/* =========================
   SIDEBAR
========================= */
.sidebar {
    width: 260px;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: rgba(30, 10, 10, 0.95);
    backdrop-filter: blur(15px);
    padding: 25px 20px;
    border-right: 1px solid rgba(220, 38, 38, 0.25);
    box-shadow: 5px 0 25px rgba(0,0,0,0.4);
}

.logo-box {
    text-align: center;
    margin-bottom: 25px;
}

.logo-box img {
    width: 70px;
    height: 70px;
    background: white;
    padding: 6px;
    border-radius: 12px;
}

.logo-box h6 {
    color: #fca5a5;
    margin-top: 10px;
    font-weight: 700;
}

.logo-box small {
    color: #f87171;
    letter-spacing: 2px;
}

.sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    margin-bottom: 8px;
    border-radius: 10px;
    text-decoration: none;
    color: #fecaca;
    font-size: 14px;
    transition: 0.25s;
}

.sidebar a:hover {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    transform: translateX(5px);
    color: white;
}

.sidebar a.active {
    background: linear-gradient(135deg, #dc2626, #7f1d1d);
    color: white;
}

/* =========================
   CONTENT
========================= */
.content {
    margin-left: 260px;
    padding: 30px;
}

/* =========================
   MAIN CARD
========================= */
.card-box {
    max-width: 1000px;
    background: rgba(20, 10, 10, 0.85);
    border: 1px solid rgba(220, 38, 38, 0.2);
    border-radius: 18px;
    padding: 30px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.5);
}

/* HEADER */
.header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
}

.header-text h4 {
    font-size: 20px;
    font-weight: 700;
    color: #fca5a5;
}

.header-text p {
    font-size: 13px;
    color: #aaa;
}

.resi-box {
    padding: 10px 15px;
    background: rgba(220,38,38,0.15);
    border: 1px solid rgba(220,38,38,0.3);
    border-radius: 10px;
    font-family: monospace;
    color: #fca5a5;
}

/* =========================
   SECTION CARD
========================= */
.section-card {
    background: rgba(15, 8, 8, 0.7);
    border: 1px solid rgba(220, 38, 38, 0.15);
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 18px;
    transition: 0.3s;
}

.section-card:hover {
    border-color: rgba(220, 38, 38, 0.4);
    transform: translateY(-2px);
}

.section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.section-icon {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dc2626, #7f1d1d);
    border-radius: 10px;
}

.section-title {
    font-size: 14px;
    font-weight: 600;
    color: #fca5a5;
}

/* =========================
   FORM GRID
========================= */
.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

.form-grid.three-col {
    grid-template-columns: repeat(3, 1fr);
}

.full-width {
    grid-column: span 2;
}

/* =========================
   INPUT STYLE (FIXED)
========================= */
input,
select,
textarea {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid rgba(220,38,38,0.25);
    background: rgba(0,0,0,0.35);
    color: white;
    outline: none;
    transition: 0.2s;
}

input:focus,
select:focus,
textarea:focus {
    border-color: #dc2626;
    box-shadow: 0 0 10px rgba(220,38,38,0.3);
}

textarea {
    min-height: 80px;
    resize: vertical;
}

/* =========================
   BUTTON
========================= */
.btn-submit {
    width: 100%;
    padding: 14px;
    margin-top: 15px;
    border: none;
    border-radius: 12px;
    background: linear-gradient(135deg, #dc2626, #7f1d1d);
    color: white;
    font-weight: 700;
    cursor: pointer;
    transition: 0.3s;
    letter-spacing: 1px;
}

.btn-submit:hover {
    transform: translateY(-2px);
    background: linear-gradient(135deg, #ef4444, #b91c1c);
}

/* =========================
   RESPONSIVE
========================= */
@media (max-width: 992px) {
    .sidebar {
        width: 220px;
    }

    .content {
        margin-left: 220px;
    }

    .form-grid,
    .form-grid.three-col {
        grid-template-columns: 1fr;
    }

    .full-width {
        grid-column: span 1;
    }
}

@media (max-width: 768px) {
    .sidebar {
        position: relative;
        width: 100%;
        height: auto;
    }

    .content {
        margin-left: 0;
    }

    .header-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}

</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="logo-box">
        <img src="../assets/img/logo PT.png" alt="Whyka Logo">
        <h6>WHYKA EKSPRES</h6>
        <small>LOGISTICS</small>
    </div>

    <hr>

    <a href="dashboard.php">
        <span class="menu-icon">&#128202;</span> Dashboard
    </a>
    <a href="tambah_pengiriman.php" class="active">
        <span class="menu-icon">&#10133;</span> Tambah Pengiriman
    </a>
    <a href="pengiriman.php">
        <span class="menu-icon">&#128230;</span> Pengiriman
    </a>
    <a href="track_resi.php">
        <span class="menu-icon">&#128269;</span> Tracking
    </a>

    <hr>

    <a href="../logout.php" class="logout">
        <span class="menu-icon">&#128682;</span> Logout
    </a>
</div>

<!-- CONTENT -->
<div class="content">
    <div class="card-box">

        <!-- Header -->
        <div class="header-section">
            <div class="header-text">
                <h4>ADD INTERNATIONAL SHIPMENT</h4>
                <p>Isi formulir untuk membuat pengiriman baru</p>
            </div>
            <div class="resi-box">
                RESI: <?= $resi; ?>
            </div>
        </div>

<form method="POST" action="proses.php" class="form-clean">
<div class="form-card">

    <h3 class="form-title">📦 Input Pengiriman</h3>
    <p class="form-subtitle">Isi data pengiriman dengan lengkap & benar</p>

    <!-- GRID SEMUA SECTION JADI SATU (SIMPEL UX) -->
    <div class="grid">

        <!-- INFORMASI USER -->
        <input type="text" name="nama_penerima" placeholder="Nama Penerima" required>
        <input type="text" name="no_hp" placeholder="No HP Penerima" required>
        <input type="text" name="nama_barang" placeholder="Nama Barang" required>
        <input type="number" name="berat" placeholder="Berat (Kg)" required>
        <input type="hidden" name="negara_asal" value="Indonesia">
        <input type="hidden" name="port_asal" value="">
        <input type="hidden" name="port_tujuan" value="">
        <input type="number"
        name="qty"
        placeholder="Jumlah Barang"
        min="1"
        value="1"
        required>
        <input type="hidden" name="customs_status" value="pending">
        
        <select name="currency">
            <option value="IDR">IDR</option>
            <option value="USD">USD</option>
            <option value="EUR">EUR</option>
        </select>

        <!-- ALAMAT -->
        <select name="negara" required>
        <option value="">Pilih Negara Tujuan</option>
        <option value="China">China</option>
        <option value="Singapore">Singapore</option>
        <option value="Malaysia">Malaysia</option>
        <option value="USA">USA</option>
        <option value="Japan">Japan</option>
        <option value="South Korea">South Korea</option>
        <option value="Australia">Australia</option>
        <option value="UAE">UAE</option>
    </select>

        <input type="text" name="kota" placeholder="Kota Tujuan" required>
        <input type="text" name="kode_pos" placeholder="Kode Pos">

        <textarea name="alamat" placeholder="Alamat Lengkap" required></textarea>

        <!-- SHIPPING -->
        <select name="metode_pengiriman" id="metode">
            <option value="Air Freight">✈ Air Freight</option>
            <option value="Sea Freight">🚢 Sea Freight</option>
        </select>

        

        <!-- OPTIONAL -->
        <input type="text" name="hs_code" placeholder="HS Code (optional)">
        <input type="text" name="duty_tax" placeholder="Duty Tax (optional)">

        <textarea name="cargo_details" placeholder="Detail Cargo (optional)"></textarea>

    </div>

    <button type="submit" name="simpan" class="btn-submit">
        🚀 Kirim Pengiriman
    </button>

</div>

</form>