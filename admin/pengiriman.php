<?php
session_start();
include '../koneksi.php';
require_once '../function.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$data = mysqli_query($conn, "SELECT * FROM pengiriman ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Pengiriman - Whyka Ekspres Logistics</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1a0a0a 0%, #2d1010 50%, #1a0a0a 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background */
        body::before {
            content: "";
            position: fixed;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(220, 38, 38, 0.12) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(248, 113, 113, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(185, 28, 28, 0.05) 0%, transparent 40%);
            animation: bgPulse 15s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes bgPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.8; transform: scale(1.1); }
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            background: linear-gradient(180deg, rgba(30, 10, 10, 0.98), rgba(20, 5, 5, 0.99));
            backdrop-filter: blur(20px);
            color: white;
            padding: 25px 20px;
            border-right: 1px solid rgba(220, 38, 38, 0.2);
            box-shadow: 5px 0 30px rgba(0, 0, 0, 0.3);
            z-index: 100;
            overflow-y: auto;
        }

        .logo-box {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 25px;
            border-bottom: 1px solid rgba(220, 38, 38, 0.2);
        }

        .logo-box img {
            width: 70px;
            height: 70px;
            object-fit: contain;
            background: white;
            border-radius: 15px;
            padding: 8px;
            box-shadow: 0 10px 30px rgba(220, 38, 38, 0.3);
            transition: transform 0.3s ease;
        }

        .logo-box img:hover {
            transform: scale(1.05) rotate(5deg);
        }

        .logo-box h6 {
            margin-top: 15px;
            font-weight: 700;
            font-size: 1rem;
            background: linear-gradient(135deg, #f87171, #fca5a5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 1px;
        }

        .logo-box small {
            color: #a1a1aa;
            font-size: 0.75rem;
            letter-spacing: 3px;
        }

        .sidebar hr {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(220, 38, 38, 0.3), transparent);
            margin: 20px 0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #d4d4d8;
            padding: 14px 18px;
            margin-bottom: 8px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
        }

        .sidebar a::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 3px;
            height: 100%;
            background: linear-gradient(180deg, #dc2626, #f87171);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar a:hover {
            background: rgba(220, 38, 38, 0.15);
            color: #fca5a5;
            transform: translateX(5px);
        }

        .sidebar a:hover::before {
            transform: scaleY(1);
        }

        .sidebar a.active {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.3), rgba(185, 28, 28, 0.2));
            color: #fca5a5;
        }

        .sidebar a.active::before {
            transform: scaleY(1);
        }

        /* CONTENT */
        .content {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .page-title {
            font-weight: 700;
            font-size: 1.6rem;
            background: linear-gradient(135deg, #f87171, #fca5a5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-title-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #dc2626, #991b1b);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
        }

        .btn-add {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            border-radius: 12px;
            padding: 12px 24px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-add:hover {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(220, 38, 38, 0.4);
        }

        /* CARD TABLE */
        .card {
            border: none;
            border-radius: 20px;
            background: linear-gradient(145deg, rgba(30, 15, 15, 0.95), rgba(20, 10, 10, 0.98));
            backdrop-filter: blur(20px);
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.4),
                0 0 100px rgba(220, 38, 38, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(220, 38, 38, 0.15);
            overflow: hidden;
        }

        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #dc2626, #f87171, #dc2626);
            background-size: 200% 100%;
            animation: gradientSlide 3s ease infinite;
        }

        @keyframes gradientSlide {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .card-body {
            padding: 25px;
            position: relative;
        }

        /* TABLE */
        .table {
            color: #e4e4e7;
            margin-bottom: 0;
        }

        .table thead {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.3), rgba(185, 28, 28, 0.2));
        }

        .table thead th {
            color: #fca5a5;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 16px 12px;
            border: none;
            white-space: nowrap;
        }

        .table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(220, 38, 38, 0.1);
        }

        .table tbody tr:hover {
            background: rgba(220, 38, 38, 0.08);
        }

        .table td {
            vertical-align: middle !important;
            font-size: 0.85rem;
            padding: 16px 12px;
            border: none;
            color: #d4d4d8;
        }

        .table td b {
            color: #fca5a5;
        }

        /* Resi Number */
        .resi-number {
            font-family: 'Courier New', monospace;
            font-weight: 700;
            color: #f87171 !important;
            font-size: 0.85rem;
            background: rgba(220, 38, 38, 0.1);
            padding: 6px 10px;
            border-radius: 8px;
            border: 1px solid rgba(220, 38, 38, 0.2);
        }

        /* Alamat Box */
        .alamat-box {
            font-size: 0.78rem;
            line-height: 1.6;
            color: #a1a1aa;
            max-width: 200px;
        }

        .alamat-box b {
            color: #fca5a5 !important;
            font-weight: 600;
        }

        .alamat-box .icon-info {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 4px;
            color: #71717a;
        }

        /* BADGE STATUS */
        .badge {
            padding: 8px 14px;
            border-radius: 25px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .badge.bg-secondary {
            background: linear-gradient(135deg, #52525b, #3f3f46) !important;
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706) !important;
            color: #1a1a1a !important;
        }

        .badge.bg-info {
            background: linear-gradient(135deg, #f87171, #ef4444) !important;
        }

        .badge.bg-primary {
            background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
        }

        .badge.bg-dark {
            background: linear-gradient(135deg, #27272a, #18181b) !important;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #991b1b, #7f1d1d) !important;
        }

        /* Price Box */
        .price-box {
            background: rgba(220, 38, 38, 0.1);
            padding: 8px 12px;
            border-radius: 10px;
            border: 1px solid rgba(220, 38, 38, 0.2);
        }

        .price-box b {
            color: #f87171 !important;
            font-size: 0.9rem;
        }

        /* Currency */
        .currency-badge {
            background: rgba(248, 113, 113, 0.15);
            color: #fca5a5;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* ACTION BUTTONS */
        .aksi-box {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            max-width: 280px;
        }

        .aksi-box a {
            font-size: 0.75rem;
            padding: 8px 10px;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            border: none;
        }

        .aksi-box a:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .aksi-box .btn-warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #1a1a1a;
        }

        .aksi-box .btn-info {
            background: linear-gradient(135deg, #f87171, #ef4444);
            color: white;
        }

        .aksi-box .btn-primary {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
        }

        .aksi-box .btn-dark {
            background: linear-gradient(135deg, #3f3f46, #27272a);
            color: white;
        }

        .aksi-box .btn-success {
            background: linear-gradient(135deg, #991b1b, #7f1d1d);
            color: white;
        }

        .aksi-box .btn-secondary {
            background: linear-gradient(135deg, #52525b, #3f3f46);
            color: white;
        }

        .aksi-box .btn-danger {
            background: linear-gradient(135deg, #450a0a, #7f1d1d);
            color: white;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(20, 10, 10, 0.5);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #dc2626, #991b1b);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #ef4444, #dc2626);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 220px;
            }
            .content {
                margin-left: 220px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .content {
                margin-left: 0;
            }
            .top-bar {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Table Responsive Fix */
        .table-responsive {
            border-radius: 15px;
            overflow-x: auto;
        }

        .table-responsive::-webkit-scrollbar {
            height: 6px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #71717a;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        /* Animation */
        .card {
            animation: fadeInUp 0.5s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Tooltip custom */
        .action-tooltip {
            position: relative;
        }

        .action-tooltip::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .action-tooltip:hover::after {
            opacity: 1;
            visibility: visible;
            bottom: calc(100% + 5px);
        }
    </style>
</head>

<body>

<div class="sidebar">
    <div class="logo-box">
        <img src="../assets/img/logo PT.png" alt="Whyka Logo">
        <h6>WHYKA EKSPRES</h6>
        <small>LOGISTICS</small>
    </div>

    <hr>

    <a href="dashboard.php">
        <span>📊</span> Dashboard
    </a>
    <a href="tambah_pengiriman.php">
        <span>➕</span> Tambah Pengiriman
    </a>
    <a href="pengiriman.php" class="active">
        <span>📦</span> Pengiriman
    </a>
    <a href="track_resi.php">
        <span>🔍</span> Tracking
    </a>

    <hr>

    <a href="../logout.php">
        <span>🚪</span> Logout
    </a>
</div>

<div class="content">

    <div class="top-bar">
        <div class="d-flex align-items-center gap-3">
            <div class="page-title-icon">📦</div>
            <div>
                <h4 class="page-title mb-0">Data Pengiriman</h4>
                <small style="color: #71717a;">Export - Import Management</small>
            </div>
        </div>
        <a class="btn-add" href="tambah_pengiriman.php">
            <span>+</span> Tambah Pengiriman
        </a>
    </div>

    <div class="card">
        <div class="card-body table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>No Resi</th>
                        <th>Barang</th>
                        <th>Tujuan</th>
                        <th>Estimasi</th>
                        <th>Status</th>
                        <th>Harga</th>
                        <th>Currency</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                <?php while($d = mysqli_fetch_array($data)) {

                    $status = $d['status'];

                    $status = strtolower($d['status']);

                        if ($status == 'booking') $color = "secondary";
                        elseif ($status == 'customs') $color = "warning";
                        elseif ($status == 'loading') $color = "info";
                        elseif ($status == 'in transit') $color = "primary";
                        elseif ($status == 'arrived') $color = "dark";
                        else $color = "success";

                   $zone = isset($d['negara']) ? getZone($d['negara']) : 'default';
                   $berat = $d['berat'] ?? 1;
                   $metode = $d['metode_pengiriman'] ?? 'standard';
                    
                ?>

                <tr>
                    <td>
                        <span class="resi-number"><?= $d['no_resi']; ?></span>
                    </td>
                    <td><?= $d['nama_barang']; ?></td>

                    <td>
                        <div class="alamat-box">
                            <b><?= $d['nama_penerima']; ?></b><br>
                            <?= $d['alamat']; ?><br>
                            <?= $d['port_tujuan']; ?><br>
                            <?= $d['negara_tujuan']; ?><br>
                            <span class="icon-info">📦 <?= $d['kode_pos']; ?></span><br>
                            <span class="icon-info">📞 <?= $d['no_hp']; ?></span>
                        </div>
                    </td>

                    <td><?= $d['estimasi']; ?></td>

                    <td>
                        <span class="badge bg-<?= $color; ?>">
                            <?= strtoupper($status); ?>
                        </span>
                    </td>

                   <td>
                        <div class="price-box">
                        Rp <?= number_format($d['price_total']); ?>
                        </div>
                    </td>

                    <td>
                        <span class="currency-badge">
                            <?= $d['currency'] ?? 'IDR'; ?>
                        </span>
                    </td>

                    <td>
                        <div class="aksi-box">
                            <a href="update_lokasi.php?id=<?= $d['id']; ?>" 
                            class="btn btn-primary btn-sm action-tooltip"
                            data-tooltip="Update Tracking">
                            📍 Update
                            </a>

                            <a href="edit_pengiriman.php?id=<?= $d['id']; ?>" class="btn btn-secondary btn-sm action-tooltip" data-tooltip="Edit">✏️</a>

                            <a href="hapus_pengiriman.php?id=<?= $d['id']; ?>" class="btn btn-danger btn-sm action-tooltip" data-tooltip="Hapus"
                               onclick="return confirm('Yakin ingin hapus data ini?')">🗑️</a>

                            <a href="detail_pengiriman.php?id=<?= $d['id']; ?>"
                                class="btn btn-info btn-sm">Detail</a>

                            <a href="invoice.php?id=<?= $d['id']; ?>" class="btn btn-success btn-sm action-tooltip" data-tooltip="Invoice">🧾</a>

                        </div>
                    </td>

                </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>
    </div>

</div>

</body>
</html>