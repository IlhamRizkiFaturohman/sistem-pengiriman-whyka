<?php
session_start();
include '../koneksi.php';

// CEK LOGIN
if(!isset($_SESSION['id_user'])){
    die("User belum login!");
}

// AMBIL ID USER
$id_user = $_SESSION['id_user'];

// AMBIL INPUT
$nama = trim($_POST['nama']);
$email = trim($_POST['email']);
$produk = trim($_POST['produk']);
$kota_asal = trim($_POST['kota_asal']);
$kota_tujuan = trim($_POST['kota_tujuan']);
$incoterms = trim($_POST['incoterms']);
$catatan = trim($_POST['catatan']);

// VALIDASI
if(
    empty($nama) ||
    empty($email) ||
    empty($produk) ||
    empty($kota_asal) ||
    empty($kota_tujuan) ||
    empty($incoterms) ||
    empty($catatan)
){
    $_SESSION['error'] = "Semua data wajib diisi!";
    header("Location: ../user/rfq.php");
    exit;
}

// INSERT KE RFQ
mysqli_query($conn, "
INSERT INTO rfq (
    id_user, nama, email, produk, kota_asal, kota_tujuan,
    incoterms, catatan, pesan, status, resi, tgl_ajukan
) VALUES (
    '$id_user','$nama','$email','$produk','$kota_asal','$kota_tujuan',
    '$incoterms','$catatan','$catatan',
    'pending','-',NOW()
)
");

// SUCCESS
$_SESSION['success'] = "RFQ berhasil dikirim!";
header("Location: ../user/rfq.php");
exit;