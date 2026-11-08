<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "whyka_logistik";

$conn = mysqli_connect($host, $user, $pass, $db);

// cek koneksi gagal
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// set timezone WIB
date_default_timezone_set('Asia/Jakarta');

// FUNCTION AUTO RESI WHYKA-YYYYMMDD-XXXX
if (!function_exists('generateResi')) {
    function generateResi($conn) {
        $date = date("Ymd");

        $query = mysqli_query($conn, "SELECT MAX(id) as max_id FROM pengiriman");
        
        if (!$query) {
            return "WHYKA-" . $date . "-0001";
        }
        
        $data = mysqli_fetch_assoc($query);
        $urutan = (int)($data['max_id'] ?? 0) + 1;

        return "WHYKA-" . $date . "-" . str_pad($urutan, 4, "0", STR_PAD_LEFT);
    }
}
?>