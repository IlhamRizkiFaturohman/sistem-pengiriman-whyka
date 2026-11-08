<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = (int)$_GET['id'];

$query = mysqli_query($conn,"
SELECT *
FROM pengiriman
WHERE id='$id'
");

$data = mysqli_fetch_assoc($query);
if(!$data){
    die("Data pengiriman tidak ditemukan");
}

if(isset($_POST['update'])){

    $shipper = mysqli_real_escape_string($conn, $_POST['shipper']);
    $shipper_address = mysqli_real_escape_string($conn, $_POST['shipper_address']);

    $consignee = mysqli_real_escape_string($conn, $_POST['consignee']);
    $consignee_address = mysqli_real_escape_string($conn, $_POST['consignee_address']);

    $airway_bill = mysqli_real_escape_string($conn, $_POST['airway_bill']);
    $hs_code = mysqli_real_escape_string($conn, $_POST['hs_code']);

    $negara_asal = mysqli_real_escape_string($conn, $_POST['negara_asal']);
    $negara_tujuan = mysqli_real_escape_string($conn, $_POST['negara_tujuan']);

    $port_asal = mysqli_real_escape_string($conn, $_POST['port_asal']);
    $port_tujuan = mysqli_real_escape_string($conn, $_POST['port_tujuan']);

    $cargo_details = mysqli_real_escape_string($conn, $_POST['cargo_details']);
    $qty = mysqli_real_escape_string($conn, $_POST['qty']);
    $dimensi = mysqli_real_escape_string($conn, $_POST['dimensi']);

    $duty_tax = mysqli_real_escape_string($conn, $_POST['duty_tax']);
    $customs_status = mysqli_real_escape_string($conn, $_POST['customs_status']);

    $service_type = mysqli_real_escape_string($conn, $_POST['service_type']);

    $metode_pengiriman = mysqli_real_escape_string($conn, $_POST['metode_pengiriman']);

    $estimasi = mysqli_real_escape_string($conn, $_POST['estimasi']);

    $currency = mysqli_real_escape_string($conn, $_POST['currency']);
    $price_total = mysqli_real_escape_string($conn, $_POST['price_total']);

    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $kota = mysqli_real_escape_string($conn, $_POST['kota']);
    $kode_pos = mysqli_real_escape_string($conn, $_POST['kode_pos']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);

    $berat = mysqli_real_escape_string($conn, $_POST['berat']);
    $negara = mysqli_real_escape_string($conn, $_POST['negara']);

    $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);

    mysqli_query($conn,"
    UPDATE pengiriman SET
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
    metode_pengiriman='$metode_pengiriman',
    estimasi='$estimasi',
    currency='$currency',
    price_total='$price_total',
    alamat='$alamat',
    kota='$kota',
    kode_pos='$kode_pos',
    no_hp='$no_hp',
    berat='$berat',
    negara='$negara',
    catatan='$catatan'
    WHERE id='$id'
    ");

    echo "
    <script>
    alert('Detail pengiriman berhasil diupdate');
    window.location='pengiriman.php';
    </script>
    ";
}
?>
<!DOCTYPE html>
<html>
<head>
     <title>Detail Pengiriman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

<h3>Detail Pengiriman</h3>

<form method="POST">
    <label>Shipper</label>
    <input type="text" name="shipper" value="<?= $data['shipper']; ?>" class="form-control">

    <label>Shipper Address</label>
    <textarea name="shipper_address" class="form-control"><?= $data['shipper_address']; ?></textarea>

    <label>Consignee</label>
    <input type="text" name="consignee" value="<?= $data['consignee']; ?>" class="form-control">

    <label>Consignee Address</label>
    <textarea name="consignee_address" class="form-control"><?= $data['consignee_address']; ?></textarea>

    <label>Airway Bill</label>
    <input type="text" name="airway_bill" value="<?= $data['airway_bill']; ?>" class="form-control">

    <label>HS Code</label>
    <input type="text" name="hs_code" value="<?= $data['hs_code']; ?>" class="form-control">

<label>Negara Asal</label>
<select name="negara_asal" id="negara_asal" class="form-control">
    <option value="">Pilih Negara</option>
    <?php
    $qNegara = mysqli_query($conn, "
        SELECT DISTINCT negara FROM lokasi_logistik ORDER BY negara
    ");

    while($n = mysqli_fetch_assoc($qNegara)){
    ?>
        <option value="<?= $n['negara']; ?>" 
        <?= ($data['negara_asal']==$n['negara'])?'selected':''; ?>>
            <?= $n['negara']; ?>
        </option>
    <?php } ?>
</select>

<label>Negara Tujuan</label>
<select name="negara_tujuan" id="negara_tujuan" class="form-control">
    <option value="">Pilih Negara</option>
    <?php
    $qNegara = mysqli_query($conn, "
        SELECT DISTINCT negara FROM lokasi_logistik ORDER BY negara
    ");

    while($n = mysqli_fetch_assoc($qNegara)){
    ?>
        <option value="<?= $n['negara']; ?>" 
        <?= ($data['negara_tujuan']==$n['negara'])?'selected':''; ?>>
            <?= $n['negara']; ?>
        </option>
    <?php } ?>
</select>

<label>Port Asal</label>
<select name="port_asal" id="port_asal" class="form-control"></select>

<label>Port Tujuan</label>
<select name="port_tujuan" id="port_tujuan" class="form-control"></select>

    <label>Cargo Details</label>
    <textarea name="cargo_details" class="form-control"><?= $data['cargo_details']; ?></textarea>

    <label>Qty</label>
    <input type="number" name="qty" value="<?= $data['qty']; ?>" class="form-control">

    <label>Dimensi</label>
    <input type="text" name="dimensi" value="<?= $data['dimensi']; ?>" class="form-control">

    <label>Duty Tax</label>
    <input type="text" name="duty_tax" value="<?= $data['duty_tax']; ?>" class="form-control">

    <label>Customs Status</label>
    <input type="text" name="customs_status" value="<?= $data['customs_status']; ?>" class="form-control">

    <label>Service Type</label>
    <input type="text" name="service_type" value="<?= $data['service_type']; ?>" class="form-control">

    <label>Currency</label>
    <input type="text" name="currency" value="<?= $data['currency']; ?>" class="form-control">

    <label>Estimasi</label>
    <input type="text" name="estimasi" value="<?= $data['estimasi']; ?>" class="form-control">

    <label>Total Price</label>
    <input type="number" name="price_total" min="0" value="<?= $data['price_total']; ?>" class="form-control">

    <label>Alamat Tujuan</label>
    <textarea name="alamat" class="form-control"><?= $data['alamat']; ?></textarea>

    <label>Kota</label>
    <input type="text" name="kota" value="<?= $data['kota']; ?>" class="form-control">

    <label>Kode Pos</label>
    <input type="text" name="kode_pos" value="<?= $data['kode_pos']; ?>" class="form-control">

    <label>No HP</label>
    <input type="text" name="no_hp" value="<?= $data['no_hp']; ?>" class="form-control">

    <label>Berat (Kg)</label>
    <input type="number" step="0.1" name="berat" value="<?= $data['berat']; ?>" class="form-control">

    <label>Negara</label>
    <input type="text" name="negara" value="<?= $data['negara']; ?>" class="form-control">

    <label>Metode Pengiriman</label>
    <select name="metode_pengiriman" id="metode" class="form-control">
        <option value="Air Freight" <?= ($data['metode_pengiriman']=='Air Freight') ? 'selected' : ''; ?>>Air Freight</option>
        <option value="Sea Freight" <?= ($data['metode_pengiriman']=='Sea Freight') ? 'selected' : ''; ?>>Sea Freight</option>
    </select>

    <label>Catatan</label>
    <textarea name="catatan" class="form-control"><?= $data['catatan']; ?></textarea>

    <button type="submit" name="update" class="btn btn-primary mt-3">Update Detail</button>
</form>

</div>

<script>
function loadLokasi() {

    let negaraAsal = document.getElementById('negara_asal').value;
    let negaraTujuan = document.getElementById('negara_tujuan').value;
    let metode = document.getElementById('metode').value;

    let jenis = (metode === 'Air Freight') ? 'bandara' : 'pelabuhan';

    // PORT ASAL
    fetch('get_lokasi.php?negara=' + negaraAsal + '&jenis=' + jenis)
        .then(res => res.text())
        .then(data => {
            document.getElementById('port_asal').innerHTML = data;
        });

    // PORT TUJUAN
    fetch('get_lokasi.php?negara=' + negaraTujuan + '&jenis=' + jenis)
        .then(res => res.text())
        .then(data => {
            document.getElementById('port_tujuan').innerHTML = data;
        });
}

// jalan saat pertama buka
window.onload = loadLokasi;

// kalau diubah
document.getElementById('metode').addEventListener('change', loadLokasi);
document.getElementById('negara_asal').addEventListener('change', loadLokasi);
document.getElementById('negara_tujuan').addEventListener('change', loadLokasi);
</script>
</body>
</html>