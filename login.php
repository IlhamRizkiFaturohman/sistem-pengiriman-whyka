<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $pass = $_POST['password'];

    // ambil data user
    $query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' LIMIT 1");

    if (!$query) {
        die("Query error: " . mysqli_error($conn));
    }

    $data = mysqli_fetch_assoc($query);

    if (!$data) {

        $error = "Username tidak ditemukan!";

    } else {

        // cek password hash
        if (password_verify($pass, $data['password'])) {

            session_regenerate_id(true);

            $_SESSION['id_user'] = $data['id'];
            $_SESSION['username'] = $data['username'];
            $_SESSION['role'] = $data['role'];
            $_SESSION['login'] = true;

            // redirect berdasarkan role
            if ($data['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: user/dashboard.php");
            }
            exit;

        } else {
            $error = "Password salah!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Whyka Ekspres Logistics</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            overflow: hidden;
            background: #0a0505;
        }

        /* BACKGROUND GLOBAL */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: url('assets/img/bg.png') no-repeat center center;
            background-size: cover;
            z-index: -2;
            filter: brightness(0.3);
        }

        /* OVERLAY GELAP DENGAN GRADIENT */
        body::after {
            content: "";
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(20, 5, 5, 0.9) 0%, rgba(40, 10, 10, 0.85) 50%, rgba(15, 5, 5, 0.95) 100%);
            z-index: -1;
        }

        /* Floating Particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(248, 113, 113, 0.5);
            border-radius: 50%;
            animation: particleFloat 20s infinite;
        }

        .particle:nth-child(1) { left: 10%; animation-delay: 0s; }
        .particle:nth-child(2) { left: 20%; animation-delay: 3s; }
        .particle:nth-child(3) { left: 35%; animation-delay: 1s; }
        .particle:nth-child(4) { left: 50%; animation-delay: 4s; }
        .particle:nth-child(5) { left: 65%; animation-delay: 2s; }
        .particle:nth-child(6) { left: 80%; animation-delay: 5s; }
        .particle:nth-child(7) { left: 90%; animation-delay: 1.5s; }

        @keyframes particleFloat {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 0.5; }
            100% { transform: translateY(-100vh) scale(1); opacity: 0; }
        }

        /* LEFT */
        .left {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 40px;
        }

        /* GLOW EFFECT */
        .left::before {
            content: "";
            position: absolute;
            width: 500px;
            height: 500px;
            background: #dc2626;
            filter: blur(180px);
            opacity: 0.15;
            top: -150px;
            left: -150px;
            animation: glowPulse 4s ease-in-out infinite;
        }

        .left::after {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            background: #f87171;
            filter: blur(120px);
            opacity: 0.1;
            bottom: -100px;
            right: 50px;
            animation: glowPulse 5s ease-in-out infinite reverse;
        }

        @keyframes glowPulse {
            0%, 100% { opacity: 0.15; transform: scale(1); }
            50% { opacity: 0.25; transform: scale(1.1); }
        }

        .branding {
            position: relative;
            z-index: 2;
            max-width: 480px;
            color: white;
        }

        .logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 25px;
            background: white;
            padding: 12px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(220, 38, 38, 0.4);
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05) rotate(5deg);
        }

        .left h1 {
            font-size: 2.4rem;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #f87171, #fca5a5, #fecaca);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
            letter-spacing: 1px;
        }

        .left p {
            color: #a1a1aa;
            font-size: 1rem;
            line-height: 1.8;
            max-width: 400px;
        }

        .features {
            margin-top: 35px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 15px;
            color: #d4d4d8;
            font-size: 0.9rem;
        }

        .feature-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.2), rgba(185, 28, 28, 0.15));
            border: 1px solid rgba(220, 38, 38, 0.3);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* RIGHT */
        .right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            position: relative;
        }

        /* LOGIN BOX */
        .login-box {
            width: 100%;
            max-width: 420px;
            padding: 45px 40px;
            border-radius: 28px;
            background: linear-gradient(145deg, rgba(30, 15, 15, 0.95), rgba(20, 10, 10, 0.98));
            backdrop-filter: blur(20px);
            box-shadow: 
                0 30px 60px rgba(0, 0, 0, 0.5),
                0 0 100px rgba(220, 38, 38, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(220, 38, 38, 0.2);
            animation: fadeUp 0.6s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .login-box::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #dc2626, #f87171, #dc2626);
            background-size: 200% 100%;
            animation: gradientSlide 3s ease infinite;
        }

        @keyframes gradientSlide {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 8px;
            font-weight: 700;
            font-size: 1.8rem;
            background: linear-gradient(135deg, #f87171, #fca5a5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-box > p {
            text-align: center;
            font-size: 0.9rem;
            color: #71717a;
            margin-bottom: 30px;
        }

        /* INPUT */
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            color: #a1a1aa;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .input-group input {
            width: 100%;
            padding: 16px 50px 16px 18px;
            border-radius: 14px;
            border: 2px solid rgba(220, 38, 38, 0.2);
            font-size: 0.95rem;
            background: rgba(15, 10, 10, 0.8);
            color: #fafafa;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        .input-group input::placeholder {
            color: #52525b;
        }

        .input-group input:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.15), 0 0 30px rgba(220, 38, 38, 0.1);
            outline: none;
            background: rgba(20, 12, 12, 0.9);
        }

       .input-group span {
        position: absolute;
        right: 18px;
        top: 50%;
        font-size: 1.1rem;
        color: #71717a;
        pointer-events: auto;
        cursor: pointer;
        }

        .input-group:first-of-type span {
            top: calc(50% + 14px);
        }

        /* Toggle Password */
        .toggle-password {
            position: absolute;
            right: 18px;
            top: calc(50% + 14px);
            cursor: pointer;
            font-size: 1.1rem;
            color: #71717a;
            user-select: none;
            transition: all 0.3s ease;
            pointer-events: auto;
        }

        .toggle-password:hover {
            color: #f87171;
            transform: scale(1.1);
        }

        /* BUTTON */
        button {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
            box-shadow: 0 10px 30px rgba(220, 38, 38, 0.3);
            position: relative;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
        }

        button::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(220, 38, 38, 0.4);
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        button:hover::before {
            left: 100%;
        }

        button:active {
            transform: translateY(-1px);
        }

        /* ERROR */
        .error {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.2), rgba(185, 28, 28, 0.15));
            color: #fca5a5;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            text-align: center;
            border: 1px solid rgba(220, 38, 38, 0.3);
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }

        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid rgba(220, 38, 38, 0.15);
            color: #71717a;
            font-size: 0.9rem;
        }

        .register-link a {
            color: #f87171;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: #fca5a5;
            text-decoration: underline;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(20, 10, 10, 0.5);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #dc2626, #991b1b);
            border-radius: 10px;
        }

        /* Responsive */
        @media (max-width: 992px) {
            body {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
            }
            
            .left {
                padding: 60px 30px 40px;
            }
            
            .left h1 {
                font-size: 1.8rem;
            }
            
            .features {
                display: none;
            }
            
            .right {
                padding: 20px 30px 60px;
            }
            
            .login-box {
                padding: 35px 30px;
            }
        }

        @media (max-width: 576px) {
            .left {
                padding: 40px 20px 30px;
            }
            
            .branding {
                text-align: center;
            }
            
            .left h1 {
                font-size: 1.5rem;
            }
            
            .left p {
                font-size: 0.9rem;
            }
            
            .right {
                padding: 15px 20px 40px;
            }
            
            .login-box {
                padding: 30px 25px;
            }
        }
    </style>
</head>

<body>

<!-- Floating Particles -->
<div class="particles">
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
</div>

<!-- LEFT -->
<div class="left">
    <div class="branding">
        <img src="assets/img/logo PT.png" class="logo" alt="Whyka Logo">
        <h1>WHYKA EKSPRES LOGISTICS</h1>
        <p>Sistem Tracking Ekspor & Impor Modern berbasis digital dan real-time monitoring.</p>
        
        <div class="features">
            <div class="feature-item">
                <div class="feature-icon">🚀</div>
                <span>Real-time Tracking System</span>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🌍</div>
                <span>International Shipping Network</span>
            </div>
            <div class="feature-item">
                <div class="feature-icon">🔒</div>
                <span>Secure & Reliable Service</span>
            </div>
        </div>
    </div>
</div>

<!-- RIGHT -->
<div class="right">

    <div class="login-box">

        <h2>Welcome Back</h2>
        <p>Login untuk mengakses dashboard</p>

        <?php if (isset($error)) { ?>
            <div class="error"><?= $error; ?></div>
        <?php } ?>

        <form method="POST">

            <div class="input-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required>
                <span>👤</span>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>

<button type="submit" name="login">Login</button>

<div style="text-align:center; margin-top:15px; font-size:13px;">
    <p class="register-link" style="margin-bottom:5px;">
        Belum punya akun?
        <a href="register.php">Daftar Sekarang</a>
    </p>

    <a href="forgot.php" style="color:#f87171; text-decoration:none;">
        Lupa password?
    </a>
</div>

        </form>

    </div>

</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    const icon = document.querySelector(".toggle-password");

    if (pass.type === "password") {
        pass.type = "text";
        icon.textContent = "🙈";
    } else {
        pass.type = "password";
        icon.textContent = "👁️";
    }
}
</script>

</body>
</html>