<?php
include '../koneksi.php';

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM pengiriman WHERE id='$id'");

header("Location: pengiriman.php");
?>