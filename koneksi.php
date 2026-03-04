<?php
// koneksi.php
$host = "localhost";
$user = "root"; // Sesuaikan jika user database kamu berbeda
$pass = "";     // Sesuaikan jika ada password database
$db   = "coba_absensi";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
