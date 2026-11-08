<?php
include '../koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['username'])){
    header("Location: ../login.php");
    exit;
}
?>

<?php

$result = mysqli_query($conn, "
    SELECT 
        COUNT(*) AS total,

        SUM(status='booking') AS booking,
        SUM(status='customs') AS customs,
        SUM(status='in transit') AS transit,
        SUM(status='delivered') AS delivered

    FROM pengiriman
");

$data = mysqli_fetch_assoc($result);

$total = $data['total'];
$booking = $data['booking'];
$customs = $data['customs'];
$transit = $data['transit'];
$delivered = $data['delivered'];

$totalUser = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
// ================= RFQ =================
$total_rfq = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rfq"));
$pending_rfq = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rfq WHERE status='Pending'"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - Whyka Logistics</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

   <style>
body {
    margin: 0;
    font-family: 'Poppins', 'Segoe UI', sans-serif;

    /* 🔥 BACKGROUND IMAGE */
    background: 
        linear-gradient(rgba(30,10,10,0.9), rgba(20,5,5,0.95)),
        url('../assets/img/bg.png');

    background-size: cover;
    background-position: center;
    background-attachment: fixed;

    min-height: 100vh;
}

/* 🔥 EFFECT GLOW BACKGROUND */
body::before {
    content: "";
    position: fixed;
    width: 500px;
    height: 500px;
    background: #dc2626;
    filter: blur(150px);
    opacity: 0.15;
    top: -100px;
    left: -100px;
}

body::after {
    content: "";
    position: fixed;
    width: 400px;
    height: 400px;
    background: #f87171;
    filter: blur(150px);
    opacity: 0.1;
    bottom: -100px;
    right: -100px;
}

/* SIDEBAR */
.sidebar {
    width: 240px;
    height: 100vh;
    position: fixed;
    background: rgba(30, 10, 10, 0.95);
    backdrop-filter: blur(10px);
    color: white;
    padding: 20px;
    box-shadow: 5px 0 20px rgba(0,0,0,0.4);
    border-right: 1px solid rgba(220, 38, 38, 0.3);
}

/* LOGO */
.logo-box {
    text-align: center;
    margin-bottom: 20px;
}

.logo-box img {
    border-radius: 12px;
    background: white;
    padding: 5px;
}

.logo-box h6 {
    margin-top: 10px;
    font-weight: bold;
    color: #fca5a5;
}

.logo-box small {
    color: #f87171;
}

/* MENU */
.sidebar a {
    display: block;
    color: #fecaca;
    padding: 12px;
    border-radius: 10px;
    text-decoration: none;
    margin-bottom: 10px;
    transition: 0.3s;
    font-size: 14px;
}

.sidebar a.active {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color: white;
}

.sidebar a:hover {
    background: linear-gradient(135deg, #dc2626, #991b1b);
    color: white;
    transform: translateX(5px);
}

.sidebar hr {
    border-color: rgba(220, 38, 38, 0.3);
}

/* CONTENT */
.content {
    margin-left: 260px;
    padding: 30px;
    color: white;
}

/* CARD */
.card-box {
    border-radius: 15px;
    padding: 20px;
    color: white;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(10px);
    transition: 0.3s;
    border: 1px solid rgba(220, 38, 38, 0.2);
}

.card-box:hover {
    transform: translateY(-5px) scale(1.02);
    border-color: rgba(220, 38, 38, 0.5);
}

/* WARNA CARD - TEMA MERAH */
.bg-blue { background: linear-gradient(135deg, #dc2626, #7f1d1d); }
.bg-orange { background: linear-gradient(135deg, #ef4444, #b91c1c); }
.bg-purple { background: linear-gradient(135deg, #f87171, #dc2626); }
.bg-darkblue { background: linear-gradient(135deg, #b91c1c, #450a0a); }
.bg-green { background: linear-gradient(135deg, #991b1b, #7f1d1d); }

/* TABLE CONTAINER */
.table-container {
    background: rgba(30,10,10,0.7);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 20px;
    margin-top: 20px;
    overflow-x: auto;
    border: 1px solid rgba(220, 38, 38, 0.2);
}

/* TABLE */
table {
    color: white;
}

table th {
    background: rgba(220, 38, 38, 0.2);
    color: #fecaca;
}

table tr:hover {
    background: rgba(220, 38, 38, 0.1);
}

/* BADGE */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
}

/* CHART */
canvas {
    background: rgba(220, 38, 38, 0.05);
    border-radius: 15px;
    padding: 10px;
    border: 1px solid rgba(220, 38, 38, 0.1);
}

/* SCROLL */
::-webkit-scrollbar {
    width: 6px;
}
::-webkit-scrollbar-thumb {
    background: #dc2626;
    border-radius: 10px;
}

.live-clock{
    background: rgba(220, 38, 38, 0.15);
    padding: 15px 20px;
    border-radius: 15px;
    backdrop-filter: blur(10px);
    text-align: center;
    min-width: 220px;
    border: 1px solid rgba(220, 38, 38, 0.3);
}

#clock{
    font-size: 28px;
    font-weight: bold;
    color: #f87171;
}

#date{
    color: #fecaca;
    font-size: 14px;
}

/* BUTTON OVERRIDES */
.btn-success {
    background: linear-gradient(135deg, #dc2626, #991b1b) !important;
    border: none !important;
}

.btn-success:hover {
    background: linear-gradient(135deg, #ef4444, #b91c1c) !important;
}

.btn-danger {
    background: linear-gradient(135deg, #7f1d1d, #450a0a) !important;
    border: none !important;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #991b1b, #7f1d1d) !important;
}

/* BADGE OVERRIDES */
.badge.bg-primary {
    background: linear-gradient(135deg, #dc2626, #991b1b) !important;
}

.badge.bg-secondary {
    background: linear-gradient(135deg, #78716c, #57534e) !important;
}

.badge.bg-warning {
    background: linear-gradient(135deg, #f59e0b, #d97706) !important;
}

.badge.bg-info {
    background: linear-gradient(135deg, #f87171, #ef4444) !important;
}

.badge.bg-success {
    background: linear-gradient(135deg, #b91c1c, #7f1d1d) !important;
}

/* H5 TITLES */
.table-container h5 {
    color: #fca5a5;
}

/* ACTIVITY FEED ITEMS */
.table-container div[style*="border-bottom"] {
    border-color: rgba(220, 38, 38, 0.2) !important;
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="logo-box">
        <img src="../assets/img/logo PT.png" width="60">
        <h6>WHYKA EKSPRES</h6>
        <small>LOGISTICS</small>
    </div>

    <hr>

    <a href="dashboard.php">📊 Dashboard</a>
    <a href="tambah_pengiriman.php">➕ Tambah Pengiriman</a>
    <a href="pengiriman.php">📦 Pengiriman</a>
    <a href="track_resi.php">🔍 Tracking</a>

    <hr>

    <a href="../logout.php" class="text-danger">🚪 Logout</a>
</div>

<!-- CONTENT -->
<div class="content">

   <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; margin-bottom:20px;">

    <div>
        <h3>Dashboard</h3>
        <p>Welcome back, <b><?= htmlspecialchars($_SESSION['username']); ?></b></p>
    </div>

    <div class="live-clock">
        <div id="clock"></div>
        <small id="date"></small>
    </div>

</div>

    <!-- CARD -->
    <div class="row g-4">

        <div class="col-lg-3 col-md-6">
            <div class="card-box bg-blue">
                Total Pengiriman
                <h2><?= $total; ?></h2>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card-box bg-orange">
                Booking
                <h2><?= $booking; ?></h2>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card-box bg-purple">
                Customs
                <h2><?= $customs; ?></h2>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card-box bg-darkblue">
                Transit
                <h2><?= $transit; ?></h2>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card-box bg-green">
                Delivered
                <h2><?= $delivered; ?></h2>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
    <div class="card-box bg-darkblue">
        Total User
        <h2><?= $totalUser; ?></h2>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="card-box bg-purple">
        Total RFQ
        <h2><?= $total_rfq; ?></h2>
    </div>
</div>

<div class="col-lg-3 col-md-6">
    <div class="card-box bg-orange">
        RFQ Pending
        <h2><?= $pending_rfq; ?></h2>
    </div>
</div>

</div>

   
 <div class="table-container">
        <div class="mb-3 d-flex gap-2">

    <a href="export_excel.php" class="btn btn-success">
        Export Excel
    </a>


</div>
        <h5>📦 Latest Shipment</h5>

        <table class="table table-hover mt-3">
            <thead>
                <tr>
                    <th>No Resi</th>
                    <th>Barang</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>

            <tbody>
            <?php
            $data = mysqli_query($conn, "SELECT * FROM pengiriman ORDER BY id DESC LIMIT 5");
            while($d = mysqli_fetch_array($data)) {

                $status = $d['status'];

                if ($status == 'booking') $badge = "secondary";
                elseif ($status == 'customs') $badge = "warning";
                elseif ($status == 'loading') $badge = "info";
                elseif ($status == 'in transit') $badge = "primary";
                else $badge = "success";
            ?>
                <tr>
                    <td><?= $d['no_resi']; ?></td>
                    <td><?= $d['nama_barang']; ?></td>
                    <td><span class="badge bg-<?= $badge; ?>"><?= strtoupper($status); ?></span></td>
                    <td><?= $d['tanggal_masuk']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>


    <div class="table-container">

    <h5>📋 RFQ Terbaru</h5>

    <table class="table table-hover mt-3">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Produk</th>
                <th>Asal</th>
                <th>Tujuan</th>
                <th>Status</th>
                <th>Resi</th>
            </tr>
        </thead>

        <tbody>
        <?php
        $rfq = mysqli_query($conn, "SELECT * FROM rfq ORDER BY id DESC LIMIT 5");

        while($r = mysqli_fetch_array($rfq)){

            $status = $r['status'];

            if ($status == 'Pending') $badge = "secondary";
            elseif ($status == 'Diproses') $badge = "warning";
            elseif ($status == 'Dikirim') $badge = "primary";
            else $badge = "success";
        ?>
            <tr>
                <td><?= $r['nama']; ?></td>
                <td><?= $r['produk']; ?></td>
                <td><?= $r['kota_asal']; ?></td>
                <td><?= $r['kota_tujuan']; ?></td>
                <td>
                    <span class="badge bg-<?= $badge; ?>">
                        <?= $status; ?>
                    </span>
                </td>
                <td><?= $r['resi'] ?? '-'; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <a href="admin_rfq.php" class="btn btn-primary">
        Lihat Semua RFQ
    </a>

</div>
    

    <!-- CHART -->
     <div class="table-container">
    <h5>🌍 Grafik Pengiriman per Negara</h5>
    <canvas id="negaraChart" height="100"></canvas>
</div>

    <div class="table-container">
        <h5>📈 Grafik Pengiriman (7 Hari Terakhir)</h5>
        <canvas id="myChart" height="100"></canvas>
    </div>

    <!-- ACTIVITY FEED -->
<div class="table-container">

    <h5>⚡ Activity Feed</h5>

    <?php
    $activity = mysqli_query($conn,
    "SELECT * FROM pengiriman ORDER BY id DESC LIMIT 5");

    while($a = mysqli_fetch_array($activity)){
    ?>

    <div style="
        padding:12px;
        border-bottom:1px solid rgba(220,38,38,0.2);
    ">

        🚚 Resi
        <b><?= $a['no_resi']; ?></b>

        status menjadi

        <span class="badge bg-primary">
            <?= strtoupper($a['status']); ?>
        </span>

    </div>

    <?php } ?>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let chartLine;
let chartBar;

// INIT CHART SEKALI AJA
function initChart() {

    // LINE CHART (7 hari)
    chartLine = new Chart(document.getElementById('myChart'), {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
    label: 'Jumlah Pengiriman',
    data: [],
    borderWidth: 3,
    tension: 0.4,
    fill: true,

    // 🔥 TEMA MERAH
    backgroundColor: 'rgba(220,38,38,0.2)',
    borderColor: '#dc2626',
    pointBackgroundColor: '#dc2626'
}]
        },
        options: {
            animation: {
                duration: 800
            }
        }
    });

    // BAR CHART (negara)
    chartBar = new Chart(document.getElementById('negaraChart'), {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
    label: 'Pengiriman per Negara',
    data: [],
    borderWidth: 2,

    // 🔥 TEMA MERAH
    backgroundColor: 'rgba(248,113,113,0.5)',
    borderColor: '#f87171'
}]
        },
        options: {
            animation: {
                duration: 800
            }
        }
    });
}

// UPDATE DATA TANPA DESTROY
function updateChart() {
    fetch('chart_data.php')
    .then(res => res.json())
    .then(data => {

        // UPDATE LINE
        chartLine.data.labels = data.labels;
        chartLine.data.datasets[0].data = data.values;
        chartLine.update();

        // UPDATE BAR
        chartBar.data.labels = data.negaraLabels;
        chartBar.data.datasets[0].data = data.negaraValues;
        chartBar.update();

    });
}

// INIT
initChart();
updateChart();

// AUTO REFRESH (REAL-TIME)
setInterval(updateChart, 5000);
</script>

<script>

function updateDateTime(){

    const now = new Date();

    const jam = String(now.getHours()).padStart(2,'0');
    const menit = String(now.getMinutes()).padStart(2,'0');
    const detik = String(now.getSeconds()).padStart(2,'0');

    document.getElementById('clock').innerHTML =
    `${jam}:${menit}:${detik}`;

    const hari = [
    "Minggu","Senin","Selasa","Rabu",
    "Kamis","Jumat","Sabtu"
    ];

    const bulan = [
    "Januari","Februari","Maret","April",
    "Mei","Juni","Juli","Agustus",
    "September","Oktober","November","Desember"
    ];

    document.getElementById('date').innerHTML =
    `${hari[now.getDay()]}, ${now.getDate()} ${bulan[now.getMonth()]} ${now.getFullYear()}`;

}

setInterval(updateDateTime,1000);

updateDateTime();

</script>

<script>

let lastNotif = "";

function notifRealtime(){

    fetch('notif.php')
    .then(res => res.text())
    .then(data => {

        if(data != "" && data != lastNotif){

            lastNotif = data;

            const notif = document.createElement("div");

            notif.innerHTML = "🔔 " + data;

            notif.style.position = "fixed";
            notif.style.top = "20px";
            notif.style.right = "20px";
            notif.style.background = "#dc2626";
            notif.style.color = "white";
            notif.style.padding = "15px";
            notif.style.borderRadius = "10px";
            notif.style.zIndex = "9999";
            notif.style.boxShadow = "0 0 15px rgba(220,38,38,0.5)";
            notif.style.transition = "0.3s";

            document.body.appendChild(notif);

            setTimeout(() => {
                notif.remove();
            }, 4000);

        }

    });

}

setInterval(notifRealtime, 5000);

</script>

</body>
</html>