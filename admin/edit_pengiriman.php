<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM pengiriman WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

if(!$data){
    echo "Data tidak ditemukan";
    exit();
}

if(isset($_POST['update'])){

    // Ambil semua, kalau kosong pakai data lama biar tidak NULL
    function post($key, $old){
        return isset($_POST[$key]) && $_POST[$key] !== '' ? mysqli_real_escape_string($GLOBALS['conn'], $_POST[$key]) : $old;
    }

    $nama_pengirim = post('nama_pengirim', $data['nama_pengirim']);
    $nama_penerima = post('nama_penerima', $data['nama_penerima']);
    $nama_barang = post('nama_barang', $data['nama_barang']);
    $berat = post('berat', $data['berat']);
    $negara = post('negara', $data['negara']);
    $kota = post('kota', $data['kota']);
    $alamat = post('alamat', $data['alamat']);
    $kode_pos = post('kode_pos', $data['kode_pos']);
    $no_hp = post('no_hp', $data['no_hp']);

    $shipper = post('shipper', $data['shipper']);
    $shipper_address = post('shipper_address', $data['shipper_address']);
    $consignee = post('consignee', $data['consignee']);
    $consignee_address = post('consignee_address', $data['consignee_address']);
    $airway_bill = post('airway_bill', $data['airway_bill']);
    $hs_code = post('hs_code', $data['hs_code']);
    $negara_asal = post('negara_asal', $data['negara_asal']);
    $negara_tujuan = post('negara_tujuan', $data['negara_tujuan']);
    $port_asal = post('port_asal', $data['port_asal']);
    $port_tujuan = post('port_tujuan', $data['port_tujuan']);
    $cargo_details = post('cargo_details', $data['cargo_details']);
    $qty = post('qty', $data['qty']);
    $dimensi = post('dimensi', $data['dimensi']);
    $duty_tax = post('duty_tax', $data['duty_tax']);
    $customs_status = post('customs_status', $data['customs_status']);
    $service_type = post('service_type', $data['service_type']);
    $currency = post('currency', $data['currency']);
    $price_total = post('price_total', $data['price_total']);
    $catatan = post('catatan', $data['catatan']);
    $status = post('status', $data['status']);

    $q = "UPDATE pengiriman SET
    nama_pengirim='$nama_pengirim',
    nama_penerima='$nama_penerima',
    nama_barang='$nama_barang',
    berat='$berat',
    negara='$negara',
    kota='$kota',
    alamat='$alamat',
    kode_pos='$kode_pos',
    no_hp='$no_hp',
    shipper='$shipper',
    shipper_address='$shipper_address',
    consignee='$consignee',
    consignee_address='$consignee_address',
    airway_bill='$airway_bill',
    hs_code='$hs_code',
    negara_asal='$negara_asal',
    negara_tujuan='$negara_tujuan',
    port_asal='$port_asal',
    port_tujuan='$port_tujuan',
    cargo_details='$cargo_details',
    qty='$qty',
    dimensi='$dimensi',
    duty_tax='$duty_tax',
    customs_status='$customs_status',
    service_type='$service_type',
    currency='$currency',
    price_total='$price_total',
    catatan='$catatan',
    status='$status'
    WHERE id='$id'";

    if(mysqli_query($conn, $q)){
        // FIX UNTUK tracking_history YANG NULL TADI
        // Sekarang isi pengiriman_id + no_resi dengan benar
        $no_resi = $data['no_resi'];
        // Cek apakah status berubah, kalau iya catat
        if($status != $data['status']){
            mysqli_query($conn, "INSERT INTO tracking_history (pengiriman_id, no_resi, status, jalur, lokasi, catatan, created_at) 
            VALUES ('$id', '$no_resi', '$status', 'udara', '$kota', 'Status diubah admin menjadi $status', NOW())");
        }
        echo "<script>alert('Data berhasil diupdate!'); window.location='pengiriman.php';</script>";
        exit();
    } else {
        echo "Error: ".mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pengiriman - Whyka Ekspres Logistics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Poppins',sans-serif;min-height:100vh;display:flex;justify-content:center;align-items:center;background:linear-gradient(135deg,#1a0a0a 0%,#2d1010 50%,#1a0a0a 100%);padding:40px 20px;overflow-x:hidden}
        .box{width:100%;max-width:750px;padding:45px 40px;border-radius:28px;background:linear-gradient(145deg,rgba(30,15,15,0.95),rgba(20,10,10,0.98));box-shadow:0 30px 60px rgba(0,0,0,0.5);border:1px solid rgba(220,38,38,0.2);position:relative;z-index:1}
        .box::before{content:"";position:absolute;top:0;left:0;right:0;height:4px;background:linear-gradient(90deg,#dc2626,#f87171,#dc2626)}
        .back-link{display:inline-flex;align-items:center;gap:8px;color:#a1a1aa;text-decoration:none;font-size:.9rem;margin-bottom:25px}
        .back-link:hover{color:#f87171}
        .header{display:flex;align-items:center;gap:15px;margin-bottom:30px}
        .header-icon{width:55px;height:55px;background:linear-gradient(135deg,#dc2626,#991b1b);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem}
        .header-text h3{font-size:1.4rem;font-weight:700;color:#fca5a5;margin:0}
        .header-text p{color:#71717a;font-size:.85rem;margin:5px 0 0}
        .resi-badge{background:rgba(220,38,38,0.2);padding:12px 18px;border-radius:12px;margin-bottom:28px;border:1px solid rgba(220,38,38,0.3);display:flex;gap:10px}
        .resi-badge span{color:#71717a;font-size:.85rem}.resi-badge strong{color:#fca5a5;font-family:'Courier New',monospace}
        label{display:block;color:#a1a1aa;font-size:.85rem;font-weight:500;margin-bottom:8px}
        .form-control{width:100%;padding:13px 16px!important;border-radius:12px!important;border:2px solid rgba(220,38,38,0.2)!important;font-size:.95rem;background:rgba(15,10,10,0.8)!important;color:#fafafa!important;margin-bottom:16px!important}
        .form-control:focus{border-color:#dc2626!important;box-shadow:0 0 0 4px rgba(220,38,38,0.15)!important;outline:none}
        select.form-control{cursor:pointer}
        .section-title{color:#f87171;font-weight:600;margin:25px 0 15px;padding-bottom:8px;border-bottom:1px solid rgba(220,38,38,0.3)}
        .btn-submit{width:100%;padding:16px;border:none;border-radius:14px;background:linear-gradient(135deg,#dc2626,#b91c1c);color:#fff;font-weight:600;font-size:1rem;cursor:pointer;text-transform:uppercase;letter-spacing:1.5px;margin-top:10px}
        .row2{display:grid;grid-template-columns:1fr 1fr;gap:15px}
        @media(max-width:576px){.row2{grid-template-columns:1fr}.box{padding:30px 20px}}
    </style>
</head>
<body>
<div class="box">
    <a href="pengiriman.php" class="back-link">← Kembali ke Data Pengiriman</a>
    <div class="header"><div class="header-icon">✎</div><div class="header-text"><h3>Edit Pengiriman</h3><p>Update informasi data pengiriman</p></div></div>
    <div class="resi-badge"><span>No Resi:</span><strong><?= htmlspecialchars($data['no_resi']); ?></strong></div>

    <form method="POST">
        <h5 class="section-title">Data Pengirim / Penerima</h5>
        <div class="row2">
            <div><label>Nama Pengirim</label><input type="text" name="nama_pengirim" value="<?= htmlspecialchars($data['nama_pengirim'] ?? ''); ?>" class="form-control"></div>
            <div><label>Nama Penerima</label><input type="text" name="nama_penerima" value="<?= htmlspecialchars($data['nama_penerima'] ?? ''); ?>" class="form-control"></div>
        </div>
        <label>Shipper</label><input type="text" name="shipper" value="<?= htmlspecialchars($data['shipper'] ?? ''); ?>" class="form-control">
        <label>Shipper Address</label><textarea name="shipper_address" class="form-control"><?= htmlspecialchars($data['shipper_address'] ?? ''); ?></textarea>
        <label>Consignee</label><input type="text" name="consignee" value="<?= htmlspecialchars($data['consignee'] ?? ''); ?>" class="form-control">
        <label>Consignee Address</label><textarea name="consignee_address" class="form-control"><?= htmlspecialchars($data['consignee_address'] ?? ''); ?></textarea>
        <div class="row2">
            <div><label>No HP</label><input type="text" name="no_hp" value="<?= htmlspecialchars($data['no_hp'] ?? ''); ?>" class="form-control"></div>
            <div><label>Kode Pos</label><input type="text" name="kode_pos" value="<?= htmlspecialchars($data['kode_pos'] ?? ''); ?>" class="form-control"></div>
        </div>

        <h5 class="section-title">Data Barang</h5>
        <label>Nama Barang</label><input type="text" name="nama_barang" value="<?= htmlspecialchars($data['nama_barang'] ?? ''); ?>" class="form-control" required>
        <div class="row2">
            <div><label>Berat (Kg)</label><input type="number" step="0.01" name="berat" value="<?= htmlspecialchars($data['berat'] ?? ''); ?>" class="form-control"></div>
            <div><label>Qty</label><input type="number" name="qty" value="<?= htmlspecialchars($data['qty'] ?? ''); ?>" class="form-control"></div>
        </div>
        <label>Dimensi</label><input type="text" name="dimensi" value="<?= htmlspecialchars($data['dimensi'] ?? ''); ?>" class="form-control" placeholder="ex: 50x40x30 cm">
        <label>Cargo Details</label><textarea name="cargo_details" class="form-control"><?= htmlspecialchars($data['cargo_details'] ?? ''); ?></textarea>
        <div class="row2">
            <div><label>Airway Bill</label><input type="text" name="airway_bill" value="<?= htmlspecialchars($data['airway_bill'] ?? ''); ?>" class="form-control"></div>
            <div><label>HS Code</label><input type="text" name="hs_code" value="<?= htmlspecialchars($data['hs_code'] ?? ''); ?>" class="form-control"></div>
        </div>

        <h5 class="section-title">Rute & Negara</h5>
        <div class="row2">
            <div><label>Negara Asal</label><input type="text" name="negara_asal" value="<?= htmlspecialchars($data['negara_asal'] ?? ''); ?>" class="form-control"></div>
            <div><label>Negara Tujuan</label><input type="text" name="negara_tujuan" value="<?= htmlspecialchars($data['negara_tujuan'] ?? ''); ?>" class="form-control"></div>
        </div>
        <div class="row2">
            <div><label>Port Asal</label><input type="text" name="port_asal" value="<?= htmlspecialchars($data['port_asal'] ?? ''); ?>" class="form-control"></div>
            <div><label>Port Tujuan</label><input type="text" name="port_tujuan" value="<?= htmlspecialchars($data['port_tujuan'] ?? ''); ?>" class="form-control"></div>
        </div>
        <label>Negara (Tujuan Akhir)</label><input type="text" name="negara" value="<?= htmlspecialchars($data['negara'] ?? ''); ?>" class="form-control" required>
        <label>Kota</label><input type="text" name="kota" value="<?= htmlspecialchars($data['kota'] ?? ''); ?>" class="form-control" required>
        <label>Alamat Lengkap</label><textarea name="alamat" class="form-control" required><?= htmlspecialchars($data['alamat'] ?? ''); ?></textarea>

        <h5 class="section-title">Biaya & Status</h5>
        <div class="row2">
            <div><label>Service Type</label><select name="service_type" class="form-control"><option value="udara" <?= ($data['service_type']=='udara')?'selected':''; ?>>Udara</option><option value="laut" <?= ($data['service_type']=='laut')?'selected':''; ?>>Laut</option><option value="darat" <?= ($data['service_type']=='darat')?'selected':''; ?>>Darat</option></select></div>
            <div><label>Currency</label><input type="text" name="currency" value="<?= htmlspecialchars($data['currency'] ?? 'IDR'); ?>" class="form-control"></div>
        </div>
        <div class="row2">
            <div><label>Duty Tax</label><input type="number" name="duty_tax" value="<?= htmlspecialchars($data['duty_tax'] ?? ''); ?>" class="form-control"></div>
            <div><label>Price Total</label><input type="number" name="price_total" value="<?= htmlspecialchars($data['price_total'] ?? ''); ?>" class="form-control"></div>
        </div>
        <label>Customs Status</label><input type="text" name="customs_status" value="<?= htmlspecialchars($data['customs_status'] ?? ''); ?>" class="form-control">
        <label>Catatan</label><textarea name="catatan" class="form-control"><?= htmlspecialchars($data['catatan'] ?? ''); ?></textarea>
        <label>Status Pengiriman</label>
        <select name="status" class="form-control">
            <option value="booking" <?= $data['status']=='booking'?'selected':''; ?>>Booking</option>
            <option value="pickup" <?= $data['status']=='pickup'?'selected':''; ?>>Pickup</option>
            <option value="customs" <?= $data['status']=='customs'?'selected':''; ?>>Customs</option>
            <option value="loading" <?= $data['status']=='loading'?'selected':''; ?>>Loading</option>
            <option value="in transit" <?= $data['status']=='in transit'?'selected':''; ?>>In Transit</option>
            <option value="arrived" <?= $data['status']=='arrived'?'selected':''; ?>>Arrived</option>
            <option value="delivered" <?= $data['status']=='delivered'?'selected':''; ?>>Delivered</option>
        </select>

        <button type="submit" name="update" class="btn-submit">💾 Update Data</button>
    </form>
</div>
</body>
</html>