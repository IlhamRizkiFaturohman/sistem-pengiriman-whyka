<?php
include 'koneksi.php';

$id = $_POST['id'];
$pass = $_POST['password'];
$confirm = $_POST['confirm'];

if($pass != $confirm){
    echo "Password tidak sama!";
    exit;
}

$newPass = password_hash($pass, PASSWORD_DEFAULT);

$update = mysqli_query($conn,
"UPDATE users SET password='$newPass' WHERE id='$id'");

if(!$update){
    die("Gagal update password: " . mysqli_error($conn));
}

header("refresh:2;url=login.php");
exit;