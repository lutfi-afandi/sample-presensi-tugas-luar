<?php
session_start();
require 'koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND password = '$password'");
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Kombinasi username atau password salah.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Presensi Sistem</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <style>
        :root {
            --bg-body: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --primary: #0f172a;
            --border-color: #e2e8f0;
            --input-focus: #3b82f6;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.04);
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.02em;
            color: var(--primary);
            margin-bottom: 4px;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 0.875rem;
            margin-bottom: 32px;
        }

        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            margin-bottom: 8px;
            display: block;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        /* Ikon Samping Kiri */
        .input-group-custom i.bi-person,
        .input-group-custom i.bi-shield-lock {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 1.1rem;
            pointer-events: none;
            /* BIAR BISA DI-KLIK TEMBUS KE INPUT */
            z-index: 2;
        }

        .form-control-custom {
            width: 100%;
            height: 50px;
            padding: 10px 45px 10px 45px;
            /* Padding kiri & kanan dilebarin biar gak nabrak ikon */
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: #fff;
            position: relative;
            z-index: 1;
            /* Input di bawah ikon tapi tetep bisa dapet klik karena pointer-events ikon none */
        }

        /* Ikon Mata (Toggle) di Kanan */
        .password-toggle {
            position: absolute;
            right: 14px;
            cursor: pointer;
            color: #94a3b8;
            z-index: 3;
            /* Ini harus tinggi biar bisa diklik */
            padding: 10px;
            /* Area klik diperbesar */
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .btn-submit {
            background: var(--primary);
            color: white;
            border: none;
            height: 50px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            margin-top: 10px;
            cursor: pointer;
            transition: transform 0.1s, opacity 0.2s;
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        .error-msg {
            background: #fff1f2;
            border: 1px solid #ffe4e6;
            color: #be123c;
            padding: 12px;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <div class="login-card">
        <div class="brand-title">Presensi App</div>
        <p class="subtitle">Secure authentication system</p>

        <?php if ($error): ?>
            <div class="error-msg">
                <i class="bi bi-exclamation-circle-fill me-1"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label class="form-label">Username</label>
            <div class="input-group-custom">
                <i class="bi bi-person"></i>
                <input type="text" name="username" class="form-control-custom" placeholder="Your username" required autocomplete="off">
            </div>

            <label class="form-label">Password</label>
            <div class="input-group-custom">
                <i class="bi bi-shield-lock"></i>
                <input type="password" id="passwordField" name="password" class="form-control-custom" placeholder="Your password" required>
                <i class="bi bi-eye password-toggle" id="toggleIcon" onclick="togglePassword()"></i>
            </div>

            <button type="submit" class="btn-submit">Sign In</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('passwordField');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        }
    </script>

</body>

</html>