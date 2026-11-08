<?php
$conn = new mysqli("localhost","root","","whyka_logistik");

if($conn->connect_error){
    die("Koneksi gagal: " . $conn->connect_error);
}