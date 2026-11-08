<?php
include 'koneksi.php';

$user = mysqli_real_escape_string($conn, $_POST['user']);

$query = mysqli_query($conn,
"SELECT * FROM users WHERE email='$user' OR username='$user'");

$data = mysqli_fetch_assoc($query);

if(!$data){
    echo "User tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>

<style>
body{
    font-family:'Poppins', sans-serif;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background: linear-gradient(135deg, #0a0505, #1a0a0a);
    margin:0;
}

.box{
    width:380px;
    padding:35px;
    border-radius:18px;
    background: rgba(20,10,10,0.95);
    border:1px solid rgba(220,38,38,0.3);
    color:white;
    text-align:center;
}

h3{
    color:#f87171;
}

input{
    width:100%;
    padding:14px;
    margin-top:10px;
    border-radius:12px;
    border:1px solid rgba(220,38,38,0.3);
    background:rgba(15,10,10,0.8);
    color:white;
}

button{
    width:100%;
    padding:14px;
    margin-top:15px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#dc2626,#b91c1c);
    color:white;
    font-weight:bold;
    cursor:pointer;
}
</style>
</head>

<body>

<div class="box">
    <h3>Reset Password</h3>

    <form method="POST" action="update_password.php">
        <input type="hidden" name="id" value="<?= $data['id']; ?>">

        <input type="password" name="password" placeholder="Password Baru" required>
        <input type="password" name="confirm" placeholder="Ulangi Password" required>

        <button type="submit">Update Password</button>
    </form>
</div>

</body>
</html>