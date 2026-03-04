<?php
// login.php
session_start();
require 'koneksi.php';

// Jika sudah login, lempar ke dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password']; // Di tutorial ini kita pakai plain-text dulu agar mudah

    $query = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' AND password = '$password'");
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        $_SESSION['user_id'] = $data['id'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Presensi Tugas Luar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
        }

        .login-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body class="d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card login-card p-4">
                    <h3 class="text-center mb-4 fw-bold text-primary">Presensi App</h3>
                    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required placeholder="Masukkan username">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>