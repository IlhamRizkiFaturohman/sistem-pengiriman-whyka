<?php
session_start();
include '../koneksi.php';

// VALIDASI LOGIN
if(!isset($_SESSION['login']) || $_SESSION['role'] != 'user'){
    header("Location: ../login.php");
    exit;
}

// WAJIB ADA ID USER
if(!isset($_SESSION['id_user'])){
    die("Session user tidak ditemukan!");
}

$id_user = $_SESSION['id_user'];
$username = $_SESSION['username'];

/* STATS */
$total = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM pengiriman WHERE id_user='$id_user'"));
$booking = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM pengiriman WHERE status='booking' AND id_user='$id_user'"));
$transit = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM pengiriman WHERE status='in transit' AND id_user='$id_user'"));
$done = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM pengiriman WHERE status='delivered' AND id_user='$id_user'"));

/* RFQ STATS */
$total_rfq = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM rfq WHERE id_user='$id_user'"));
$rfq_pending = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM rfq WHERE status='pending' AND id_user='$id_user'"));
$rfq_deal = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM rfq WHERE status='deal' AND id_user='$id_user'"));

/* RESI - GANTI 'no_resi' SESUAI NAMA KOLOM LU */
$resi_list = mysqli_query($conn,"
SELECT id_rfq, no_resi, status, kota 
FROM pengiriman 
WHERE id_user='$id_user' 
ORDER BY id DESC
");

/* RFQ - PAKAI NAMA KOLOM STANDAR */
$result_rfq = mysqli_query($conn,
"SELECT id, produk, kota_asal, kota_tujuan, status, resi, tgl_ajukan 
FROM rfq 
WHERE id_user='$id_user' 
ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<title>Dashboard User - WHYKA EKSPRES</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --bg-dark: #0f0f11;
  --card-dark: rgba(23, 23, 26, 0.7);
  --border-dark: rgba(255, 255, 255, 0.08);
  --red-primary: #E11D48; /* Red-600 */
  --red-hover: #BE123C; /* Red-700 */
  --red-glow: rgba(225, 29, 72, 0.4);
  --text-main: #F9FAFB;
  --text-muted: #9CA3AF;
  --green: #22C55E;
  --yellow: #F59E0B;
  --blue: #3B82F6;
  --purple: #8B5CF6;
}

*{box-sizing:border-box;}
body{
    margin:0;
    font-family:'Poppins', sans-serif;
    background-color: var(--bg-dark);
    background-image: radial-gradient(circle at top left, rgba(225, 29, 72, 0.15), transparent 40%),
                      radial-gradient(circle at bottom right, rgba(225, 29, 72, 0.1), transparent 30%);
    color:var(--text-main);
    min-height: 100vh;
}

/* --- SIDEBAR + LOGO BARU --- */
.sidebar{
    width:260px;
    height:100vh;
    position:fixed;
    background:var(--card-dark);
    backdrop-filter: blur(12px);
    border-right: 1px solid var(--border-dark);
    padding:25px;
    display: flex;
    flex-direction: column;
}
.brand{ /* Wrapper Logo + Teks */
    display: flex;
    align-items: center;
    gap: 12px;
    margin:0 0 30px 0;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--border-dark);
}
.brand img{
    width: 42px;
    height: 42px;
    object-fit: contain; /* Biar logo gak gepeng */
    flex-shrink: 0;
}
.brand-text{
    color:var(--text-main);
    font-weight: 700;
    font-size: 18px; /* Gue kecilin dikit biar muat "LOGISTIK" */
    line-height: 1.2;
}
.brand-text span{color:var(--red-primary);}

.sidebar a{
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px 15px;
    color:var(--text-muted);
    text-decoration:none;
    border-radius:10px;
    margin-top:5px;
    font-weight: 500;
    transition: all .2s ease;
}
.sidebar a:hover, .sidebar a.active{
    background:var(--red-primary);
    color:white;
    box-shadow: 0 4px 15px var(--red-glow);
}
.sidebar .logout{
    margin-top:auto; /* Dorong logout ke bawah */
}
.content{
    margin-left:280px;
    padding:30px 40px;
}

/* --- HEADER --- */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom: 30px;
}
.header h1{margin:0; font-size: 28px;}
.info-card{
    background:var(--card-dark);
    backdrop-filter: blur(12px);
    padding:12px 18px;
    border-radius:12px;
    border:1px solid var(--border-dark);
    font-size:14px;
    color:var(--text-muted);
}
.info-card b{color:var(--text-main);}

/* --- STATS GRID --- */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:20px;
    margin-bottom: 30px;
}
.card{
    background:var(--card-dark);
    backdrop-filter: blur(12px);
    padding:20px;
    border-radius:16px;
    border:1px solid var(--border-dark);
    transition: all .3s ease;
}
.card:hover{
    border-color: var(--red-primary);
    transform: translateY(-5px);
}
.card h3{
    color:var(--text-muted);
    font-size:14px;
    margin:0;
    font-weight: 500;
}
.card .num{
    font-size:32px;
    font-weight:700;
    margin-top:8px;
    background: linear-gradient(90deg, var(--red-primary), #FB7185);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* --- BUTTON --- */
.btn{
    background:var(--red-primary);
    padding:12px 20px;
    border-radius:10px;
    text-decoration:none;
    color:white;
    display:inline-flex;
    align-items:center;
    gap:8px;
    font-weight:600;
    border: none;
    cursor: pointer;
    transition: all .2s ease;
    box-shadow: 0 4px 15px var(--red-glow);
}
.btn:hover{background:var(--red-hover); transform: translateY(-2px);}

/* --- TABLE & RFQ ITEM --- */
.table-wrap{
    background:var(--card-dark);
    backdrop-filter: blur(12px);
    border-radius:16px;
    border:1px solid var(--border-dark);
    padding: 20px;
    margin-top:25px;
    overflow-x:auto;
}
.table-wrap h3{margin-top:0; margin-bottom: 20px;}
table{width:100%;border-collapse:collapse;}
th{
    background:transparent;
    padding:12px;
    text-align:left;
    font-size: 13px;
    color:var(--text-muted);
    text-transform: uppercase;
    font-weight: 600;
    border-bottom: 1px solid var(--border-dark);
}
td{padding:14px 12px;border-bottom:1px solid var(--border-dark); font-size: 14px;}
tr:last-child td{border-bottom:none;}
.badge{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight: 600;
    color: white;
    display: inline-block;
}
.rfq-item{
    background:var(--card-dark);
    backdrop-filter: blur(12px);
    border:1px solid var(--border-dark);
    padding:18px;
    margin-bottom:15px;
    border-radius:16px;
    transition: all .2s ease;
}
.rfq-item:hover{border-color: var(--red-primary);}
.rfq-item b{color: var(--text-muted); font-weight: 500;}
.rfq-item > small{display: block; margin-top: 10px; color: var(--text-muted);}
</style>
</head>
<body>

<div class="sidebar">
    <!-- BAGIAN LOGO YANG BARU -->
    <div class="brand">
        <img src="../assets/img/Logo PT.png" alt="Logo WHYKA">
        <div class="brand-text">
            WHYKA <span>EKSPRES</span><br>
            <span style="font-size: 14px; font-weight: 500; color: var(--text-muted);">LOGISTIK</span>
        </div>
    </div>

    <a href="dashboard.php" class="active">📊 Dashboard</a>
    <a href="track_resi.php">📍 Tracking</a>
    <a href="../logout.php" class="logout">🚪 Logout</a>
</div>

<div class="content">
<div class="header">
    <h1>Dashboard User</h1>
    <div class="info-card">
        👤 <b><?= htmlspecialchars($username) ?></b> | ID: <b><?= htmlspecialchars($id_user) ?></b>
    </div>
</div>

<div class="grid">
<div class="card"><h3>Total RFQ</h3><div class="num"><?= $total_rfq ?></div></div>
<div class="card"><h3>RFQ Pending</h3><div class="num"><?= $rfq_pending ?></div></div>
<div class="card"><h3>RFQ Deal</h3><div class="num"><?= $rfq_deal ?></div></div>
<div class="card"><h3>Total Pengiriman</h3><div class="num"><?= $total ?></div></div>
<div class="card"><h3>Booking</h3><div class="num"><?= $booking ?></div></div>
<div class="card"><h3>Transit</h3><div class="num"><?= $transit ?></div></div>
<div class="card"><h3>Delivered</h3><div class="num"><?= $done ?></div></div>
</div>

<a href="../user/rfq.php" class="btn">+ Ajukan RFQ Baru</a>
<h3 style="margin-top:30px;">Data Request Quote RFQ</h3>

<?php
if($result_rfq && mysqli_num_rows($result_rfq) > 0){
    while($d = mysqli_fetch_assoc($result_rfq)){
        $status = strtolower($d['status']);

        if($status == 'pending'){$badge_color = 'var(--yellow)'; $status_text='Menunggu Review';}
        elseif($status == 'diproses'){$badge_color = 'var(--blue)'; $status_text='Sedang Diproses';}
        elseif($status == 'deal'){$badge_color = 'var(--green)'; $status_text='Disetujui';}
        elseif($status == 'ditolak'){$badge_color = '#6b7280'; $status_text='Ditolak';}
        else{$badge_color = '#6b7280'; $status_text=$d['status'];}
    ?>
    <div class="rfq-item">
        <b>RFQ ID:</b> RFQ-<?= $d['id'] ?><br>
        <b>Produk:</b> <?= htmlspecialchars($d['produk']) ?><br>
        <b>Rute:</b> <?= htmlspecialchars($d['kota_asal']) ?> → <?= htmlspecialchars($d['kota_tujuan']) ?><br><br>
        <b>Status:</b> <span class="badge" style="background:<?= $badge_color ?>;"><?= $status_text ?></span><br><br>
        <b>Resi:</b>
        <?php if(empty($d['resi']) || $d['resi']=='-'){
            echo "<span style='color:var(--text-muted)'>Menunggu Persetujuan Admin</span>";
        }else{
            echo htmlspecialchars($d['resi']);
            echo '<br><br><a href="track_resi.php?resi='.urlencode($d['resi']).'" class="btn" style="background:var(--green); box-shadow: 0 4px 15px rgba(34,197,94,0.4);">📍 Lacak Pengiriman</a>';
        } ?>
        <small>Tanggal: <?= $d['tgl_ajukan'] ?></small>
    </div>
    <?php 
    }
} else {
    echo "<div style='padding:20px; text-align:center; color:var(--text-muted); background:var(--card-dark); border-radius:16px;'>Belum ada data RFQ. Klik tombol di atas buat ajukan.</div>";
}
?>

<div class="table-wrap">
<h3>📦 Resi Kamu</h3>
<table>
<thead>
<tr>
<th>RFQ</th>
<th>Resi</th>
<th>Status</th>
<th>Kota Tujuan</th>
</tr>
</thead>
<tbody>
<?php 
if($resi_list && mysqli_num_rows($resi_list) > 0){
    while($r = mysqli_fetch_assoc($resi_list)){
        $status_pengiriman = strtolower($r['status']);
        if($status_pengiriman=='booking'){$warna='var(--yellow)';}
        elseif($status_pengiriman=='customs'){$warna='var(--blue)';}
        elseif($status_pengiriman=='in transit'){$warna='var(--purple)';}
        elseif($status_pengiriman=='delivered'){$warna='var(--green)';}
        else{$warna='#6b7280';}
?>
<tr>
<td><?= !empty($r['id_rfq']) ? "RFQ-" . $r['id_rfq'] : "Manual" ?></td>
<td><?= htmlspecialchars($r['no_resi']) ?></td>
<td><span class="badge" style="background:<?= $warna ?>;"><?= htmlspecialchars($r['status']) ?></span></td>
<td><?= htmlspecialchars($r['kota']) ?></td>
</tr>
<?php }} else {
    echo '<tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:20px;">Belum ada data pengiriman</td></tr>';
} ?>
</tbody>
</table>
</div>
</div>
</body>
</html>