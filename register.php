<?php
include 'koneksi.php';

if(isset($_POST['register'])){

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // cek username atau email sudah ada
    $cek = mysqli_query($conn, "SELECT * FROM users 
        WHERE username='$username' OR email='$email'");

    if(mysqli_num_rows($cek) > 0){

        $error = "Username atau Email sudah digunakan!";

    } else {

        mysqli_query($conn, "INSERT INTO users 
        (username, email, no_telp, password, role)
        VALUES 
        ('$username','$email','$no_telp','$password','user')");

        echo "
        <script>
            alert('Registrasi berhasil!');
            window.location='login.php';
        </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Whyka Ekspres Logistics</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #0a0505;
            padding: 20px;
            overflow-x: hidden;
        }

        /* Background Image with Overlay */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: url('assets/img/bg.png') no-repeat center center;
            background-size: cover;
            z-index: -2;
            filter: brightness(0.25);
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, rgba(20, 5, 5, 0.92) 0%, rgba(40, 10, 10, 0.88) 50%, rgba(15, 5, 5, 0.95) 100%);
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
            animation: particleFloat 18s infinite;
        }

        .particle:nth-child(1) { left: 8%; animation-delay: 0s; animation-duration: 22s; }
        .particle:nth-child(2) { left: 18%; animation-delay: 2s; animation-duration: 18s; }
        .particle:nth-child(3) { left: 32%; animation-delay: 4s; animation-duration: 20s; }
        .particle:nth-child(4) { left: 48%; animation-delay: 1s; animation-duration: 24s; }
        .particle:nth-child(5) { left: 62%; animation-delay: 3s; animation-duration: 19s; }
        .particle:nth-child(6) { left: 78%; animation-delay: 5s; animation-duration: 21s; }
        .particle:nth-child(7) { left: 88%; animation-delay: 2s; animation-duration: 17s; }
        .particle:nth-child(8) { left: 95%; animation-delay: 4s; animation-duration: 23s; }

        @keyframes particleFloat {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 0.5; }
            100% { transform: translateY(-100vh) scale(1); opacity: 0; }
        }

        /* Glow Effects */
        .glow-1 {
            position: fixed;
            width: 500px;
            height: 500px;
            background: #dc2626;
            filter: blur(180px);
            opacity: 0.12;
            top: -200px;
            left: -200px;
            z-index: -1;
            animation: glowPulse 5s ease-in-out infinite;
        }

        .glow-2 {
            position: fixed;
            width: 400px;
            height: 400px;
            background: #f87171;
            filter: blur(150px);
            opacity: 0.08;
            bottom: -150px;
            right: -150px;
            z-index: -1;
            animation: glowPulse 6s ease-in-out infinite reverse;
        }

        @keyframes glowPulse {
            0%, 100% { opacity: 0.12; transform: scale(1); }
            50% { opacity: 0.2; transform: scale(1.1); }
        }

        /* Register Box */
        .register-box {
            width: 100%;
            max-width: 440px;
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

        .register-box::before {
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

        /* Logo */
        .logo {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo img {
            width: 75px;
            height: 75px;
            object-fit: contain;
            background: white;
            padding: 10px;
            border-radius: 18px;
            box-shadow: 0 12px 35px rgba(220, 38, 38, 0.4);
            transition: transform 0.3s ease;
        }

        .logo img:hover {
            transform: scale(1.05) rotate(5deg);
        }

        /* Headings */
        h2 {
            text-align: center;
            margin-bottom: 8px;
            font-weight: 700;
            font-size: 1.5rem;
            background: linear-gradient(135deg, #f87171, #fca5a5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.5px;
        }

        .register-box > p {
            text-align: center;
            font-size: 0.9rem;
            color: #71717a;
            margin-bottom: 28px;
        }

        /* Form Inputs */
        .input-group {
            margin-bottom: 18px;
        }

        .input-group label {
            display: block;
            color: #a1a1aa;
            font-size: 0.82rem;
            font-weight: 500;
            margin-bottom: 8px;
            letter-spacing: 0.3px;
        }

        input {
            width: 100%;
            padding: 15px 18px;
            border-radius: 14px;
            border: 2px solid rgba(220, 38, 38, 0.2);
            font-size: 0.95rem;
            background: rgba(15, 10, 10, 0.8);
            color: #fafafa;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        input::placeholder {
            color: #52525b;
        }

        input:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.15), 0 0 25px rgba(220, 38, 38, 0.1);
            outline: none;
            background: rgba(20, 12, 12, 0.9);
        }

        /* Password Wrapper */
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input {
            flex: 1;
            padding-right: 50px;
        }

        .toggle-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.1rem;
            color: #71717a;
            user-select: none;
            transition: all 0.3s ease;
        }

        .toggle-password:hover {
            color: #f87171;
            transform: translateY(-50%) scale(1.15);
        }

        /* Button */
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
            letter-spacing: 1.5px;
            margin-top: 8px;
            box-shadow: 0 10px 30px rgba(220, 38, 38, 0.35);
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
            box-shadow: 0 15px 40px rgba(220, 38, 38, 0.45);
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        button:hover::before {
            left: 100%;
        }

        button:active {
            transform: translateY(-1px);
        }

        /* Error */
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

        /* Login Link */
        .login-section {
            text-align: center;
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px solid rgba(220, 38, 38, 0.15);
        }

        .login-section p {
            color: #71717a;
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        .login-btn {
            display: inline-block;
            padding: 12px 28px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.15), rgba(185, 28, 28, 0.1));
            color: #f87171;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(220, 38, 38, 0.3);
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.35);
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
        @media (max-width: 480px) {
            .register-box {
                padding: 35px 25px;
                margin: 10px;
            }
            
            h2 {
                font-size: 1.3rem;
            }
            
            .logo img {
                width: 65px;
                height: 65px;
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
    <div class="particle"></div>
</div>

<!-- Glow Effects -->
<div class="glow-1"></div>
<div class="glow-2"></div>

<div class="register-box">

    <div class="logo">
        <img src="assets/img/logo PT.png" alt="Whyka Logo">
    </div>

    <h2>WHYKA EKSPRES LOGISTIK</h2>
    <p>Buat akun customer baru</p>

    <?php if(isset($error)){ ?>
        <div class="error"><?= $error; ?></div>
    <?php } ?>

    <form method="POST">

        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required>
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="Masukkan email" required>
        </div>

        <div class="input-group">
            <label>No Telepon</label>
            <input type="text" name="no_telp" placeholder="Masukkan no telepon" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>
        </div>

        <button type="submit" name="register">Daftar Akun</button>

    </form>

    <div class="login-section">
        <p>Sudah punya akun?</p>
        <a href="login.php" class="login-btn">Login Sekarang</a>
    </div>

</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    const icon = pass.parentElement.querySelector(".toggle-password");

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