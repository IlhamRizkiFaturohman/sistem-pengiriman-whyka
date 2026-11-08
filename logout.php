<?php
session_start();

$role = $_SESSION['role'];

session_destroy();

if($role == 'admin'){
    header("Location: login.php");
} else {
    header("Location: index.php");
}

exit;
?>