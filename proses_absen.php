<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $timestamp = time();

    // Fungsi bantu untuk simpan Base64 ke File
    function saveBase64($base64String, $path)
    {
        $data = explode(',', $base64String);
        file_put_contents($path, base64_decode($data[1]));
    }

    // 1. Simpan Foto Wajah
    $file_wajah = "wajah_" . $user_id . "_" . $timestamp . ".jpg";
    saveBase64($_POST['image_wajah'], "uploads/wajah/" . $file_wajah);

    // 2. Simpan Foto Kegiatan
    $file_kegiatan = "kegiatan_" . $user_id . "_" . $timestamp . ".jpg";
    saveBase64($_POST['image_kegiatan'], "uploads/kegiatan/" . $file_kegiatan);

    // 3. Upload Surat Tugas (Masih Manual File)
    $surat_name = "surat_" . $user_id . "_" . $timestamp . "_" . $_FILES['surat_tugas']['name'];
    move_uploaded_file($_FILES['surat_tugas']['tmp_name'], "uploads/surat/" . $surat_name);

    // 4. Insert Database
    $lat = $_POST['lat'];
    $long = $_POST['long'];
    $tgl = date('Y-m-d');
    $jam = date('H:i:s');

    $sql = "INSERT INTO presensi (user_id, tanggal, waktu, foto_wajah, foto_kegiatan, surat_tugas, latitude, longitude) 
            VALUES ('$user_id', '$tgl', '$jam', '$file_wajah', '$file_kegiatan', '$surat_name', '$lat', '$long')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Presensi berhasil dikirim!'); window.location='index.php';</script>";
    }
}
