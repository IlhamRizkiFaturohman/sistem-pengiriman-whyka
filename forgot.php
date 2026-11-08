<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password - WHYKA</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins', sans-serif;
}

body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#0a0505;
    position:relative;
    overflow:hidden;
}

/* background glow */
body::before{
    content:"";
    position:absolute;
    width:500px;
    height:500px;
    background:#dc2626;
    filter:blur(180px);
    opacity:0.15;
    top:-150px;
    left:-150px;
}

body::after{
    content:"";
    position:absolute;
    width:400px;
    height:400px;
    background:#f87171;
    filter:blur(160px);
    opacity:0.1;
    bottom:-120px;
    right:-100px;
}

/* BOX */
.box{
    width:380px;
    padding:35px;
    border-radius:25px;
    background:rgba(20,10,10,0.95);
    border:1px solid rgba(220,38,38,0.2);
    box-shadow:0 30px 60px rgba(0,0,0,0.6);
    position:relative;
    z-index:2;
    animation:fadeUp 0.6s ease;
}

@keyframes fadeUp{
    from{opacity:0; transform:translateY(30px);}
    to{opacity:1; transform:translateY(0);}
}

h3{
    text-align:center;
    color:#f87171;
    margin-bottom:10px;
    font-size:22px;
}

p{
    text-align:center;
    color:#a1a1aa;
    font-size:13px;
    margin-bottom:25px;
}

/* INPUT */
input{
    width:100%;
    padding:14px;
    border-radius:12px;
    border:2px solid rgba(220,38,38,0.2);
    background:rgba(15,10,10,0.8);
    color:white;
    font-size:14px;
    outline:none;
    transition:0.3s;
}

input:focus{
    border-color:#dc2626;
    box-shadow:0 0 0 4px rgba(220,38,38,0.15);
}

/* BUTTON */
button{
    width:100%;
    padding:14px;
    margin-top:18px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#dc2626,#b91c1c);
    color:white;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
    letter-spacing:1px;
}

button:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 30px rgba(220,38,38,0.4);
}

/* LINK BACK */
.back{
    text-align:center;
    margin-top:15px;
    font-size:12px;
}

.back a{
    color:#f87171;
    text-decoration:none;
}

.back a:hover{
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="box">

    <h3>Forgot Password</h3>
    <p>Masukkan username atau email akun kamu</p>

    <form method="POST" action="reset_password.php">

        <input type="text" name="user" placeholder="Email atau Username" required>

        <button type="submit">Cari Akun</button>

    </form>

    <div class="back">
        <a href="login.php">← Kembali ke Login</a>
    </div>

</div>

</body>
</html>