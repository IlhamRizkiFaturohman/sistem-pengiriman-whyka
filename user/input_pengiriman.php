<?php
session_start();
include '../koneksi.php';

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

if($_SESSION['role'] !== 'admin'){
    die("❌ Akses ditolak! Halaman ini hanya untuk admin.");
}

$popup = "";

/* =========================
   PROSES SIMPAN PENGIRIMAN
========================= */
if(isset($_POST['submit'])){

    $id_user = $_POST['id_user'];
    $getUser = mysqli_query($conn, "SELECT username FROM users WHERE id='$id_user'");
    $userData = mysqli_fetch_assoc($getUser);
    $username = $userData['username'];

    $nama_penerima = $_POST['nama_penerima'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $nama_barang = $_POST['nama_barang'];
    $berat = $_POST['berat'];
    $negara = $_POST['negara'];
    $kota = $_POST['kota'];
    $kode_pos = $_POST['kode_pos'];
    $metode = $_POST['metode_pengiriman'];

    $kode_resi = "WHYKA-" . date("YmdHis") . "-" . rand(1000,9999);

    $query = mysqli_query($conn, "
        INSERT INTO pengiriman (
            nama_penerima,
            no_hp,
            alamat,
            nama_barang,
            berat,
            negara,
            kota,
            kode_pos,
            metode_pengiriman,
            kode_resi,
            username,
            id_user,
            status
        ) VALUES (
            '$nama_penerima',
            '$no_hp',
            '$alamat',
            '$nama_barang',
            '$berat',
            '$negara',
            '$kota',
            '$kode_pos',
            '$metode',
            '$kode_resi',
            '$username',
            '$id_user',
            'booking'
        )
    ");

    if($query){
        $popup = $kode_resi;
    } else {
        die("Error: " . mysqli_error($conn));
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Input Pengiriman - WHYKA</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    font-family:Poppins;
    background:linear-gradient(135deg,#0f172a,#111827);
    color:white;
    padding:25px;
}

/* CARD */
.container{
    max-width:850px;
    margin:auto;
    background:rgba(255,255,255,0.06);
    border:1px solid rgba(255,255,255,0.1);
    border-radius:20px;
    padding:25px;
    backdrop-filter:blur(10px);
}

/* HEADER */
h2{
    text-align:center;
    margin-bottom:20px;
}

/* STEP */
.steps{
    display:flex;
    justify-content:center;
    margin-bottom:20px;
}
.step{
    width:30px;
    height:30px;
    border-radius:50%;
    background:#334155;
    margin:0 5px;
    display:flex;
    align-items:center;
    justify-content:center;
}
.step.active{
    background:#3b82f6;
}

/* FORM */
.step-box{display:none;}
.step-box.active{display:block;}

input,select,textarea{
    width:100%;
    padding:12px;
    margin-bottom:10px;
    border-radius:10px;
    border:none;
    outline:none;
    background:#0b1220;
    color:white;
}

button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:10px;
    background:linear-gradient(135deg,#3b82f6,#1d4ed8);
    color:white;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    transform:translateY(-2px);
}

/* POPUP */
.popup{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.7);
    display:flex;
    align-items:center;
    justify-content:center;
}
.popup-box{
    background:white;
    color:black;
    padding:25px;
    border-radius:15px;
    text-align:center;
}
.popup-box h3{
    color:#1d4ed8;
}
</style>
</head>

<body>

<div class="container">
<h2>🚚 Input Pengiriman</h2>

<form method="POST" id="form">

<select name="id_user" required>
    <option value="4">User 4</option>
    <option value="5">User 5</option>
</select>

<div class="steps">
    <div class="step active">1</div>
    <div class="step">2</div>
    <div class="step">3</div>
</div>

<!-- STEP 1 -->
<div class="step-box active">
    <input name="nama_penerima" placeholder="Nama Penerima" required>
    <input name="no_hp" placeholder="No HP" required>
    <input name="alamat" placeholder="Alamat Lengkap" required>
    <button type="button" onclick="next()">Lanjut</button>
</div>

<!-- STEP 2 -->
<div class="step-box">
    <input name="nama_barang" placeholder="Nama Barang" required>
    <input name="berat" type="number" placeholder="Berat (Kg)" required>
    <button type="button" onclick="next()">Lanjut</button>
</div>

<!-- STEP 3 -->
<div class="step-box">
    <input name="negara" placeholder="Negara Tujuan" required>
    <input name="kota" placeholder="Kota Tujuan" required>
    <input name="kode_pos" placeholder="Kode Pos" required>

    <select name="metode_pengiriman">
        <option>Air Freight</option>
        <option>Sea Freight</option>
    </select>

    <button type="submit" name="submit" id="btn">
        Kirim Pengiriman
    </button>
</div>

</form>
</div>

<script>
let step=0;
const box=document.querySelectorAll(".step-box");
const stepUI=document.querySelectorAll(".step");

function show(i){
    box.forEach(b=>b.classList.remove("active"));
    stepUI.forEach(s=>s.classList.remove("active"));

    box[i].classList.add("active");
    stepUI[i].classList.add("active");
}

function next(){
    if(step<2){
        step++;
        show(step);
    }
}

document.getElementById("form").addEventListener("submit",()=>{
    document.getElementById("btn").innerText="Processing...";
});
</script>

<?php if($popup!=""){ ?>
<div class="popup">
    <div class="popup-box">
        <h2>🎉 Berhasil</h2>
        <p>No Resi:</p>
        <h3><?= $popup ?></h3>
        <button onclick="location.href='dashboard.php'">OK</button>
    </div>
</div>
<?php } ?>

</body>
</html>