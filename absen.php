<?php
session_start();
require 'koneksi.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Cek lagi apakah hari ini sudah absen (proteksi ganda)
$user_id = $_SESSION['user_id'];
$hari_ini = date('Y-m-d');
$cek = mysqli_query($conn, "SELECT id FROM presensi WHERE user_id = '$user_id' AND tanggal = '$hari_ini'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Anda sudah absen hari ini!'); window.location='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Presensi - Tugas Luar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        .main-card {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            background: white;
            overflow: hidden;
        }

        .step-indicator {
            background: #f1f5f9;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .video-wrapper {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            background: #000;
            aspect-ratio: 4/3;
        }

        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-capture {
            border-radius: 15px;
            padding: 12px;
            transition: all 0.3s;
            font-weight: 700;
            border: none;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-primary-custom:disabled {
            background: #cbd5e1;
        }

        .preview-img {
            width: 100%;
            border-radius: 15px;
            border: 2px solid #e2e8f0;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="main-card p-4 p-md-5">

                    <div id="camera-section">
                        <div class="step-indicator d-flex justify-content-between">
                            <span id="step-title"><i class="bi bi-person-badge"></i> LANGKAH 1: VERIFIKASI WAJAH</span>
                            <span class="text-primary" id="step-number">1/3</span>
                        </div>

                        <div class="text-center mb-3">
                            <span id="face-status" class="status-badge bg-light text-secondary">Inisialisasi...</span>
                        </div>

                        <div class="video-wrapper mb-4">
                            <video id="video" autoplay muted></video>
                        </div>

                        <button type="button" id="btn-capture-wajah" class="btn btn-primary-custom btn-capture w-100 mb-2" disabled>
                            <i class="bi bi-camera-fill me-2"></i> VERIFIKASI SEKARANG
                        </button>

                        <button type="button" id="btn-capture-kegiatan" class="btn btn-success btn-capture w-100 mb-2" style="display:none;">
                            <i class="bi bi-geo-fill me-2"></i> AMBIL FOTO KEGIATAN
                        </button>

                        <p class="text-center text-muted small mt-2">Pastikan pencahayaan cukup dan wajah terlihat jelas.</p>
                    </div>

                    <div id="final-section" style="display: none;">
                        <div class="step-indicator d-flex justify-content-between bg-primary text-white">
                            <span><i class="bi bi-check-circle"></i> LANGKAH TERAKHIR</span>
                            <span>3/3</span>
                        </div>

                        <div class="row g-2 mb-4">
                            <div class="col-6">
                                <label class="small fw-bold">Foto Wajah</label>
                                <img id="prev-wajah" class="preview-img">
                            </div>
                            <div class="col-6">
                                <label class="small fw-bold">Foto Kegiatan</label>
                                <img id="prev-kegiatan" class="preview-img">
                            </div>
                        </div>

                        <form action="proses_absen.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="image_wajah" id="input_wajah">
                            <input type="hidden" name="image_kegiatan" id="input_kegiatan">
                            <input type="hidden" name="lat" id="lat">
                            <input type="hidden" name="long" id="long">

                            <div class="mb-4">
                                <label class="form-label fw-bold">Upload Surat Tugas</label>
                                <input type="file" name="surat_tugas" class="form-control form-control-lg" style="border-radius: 12px;" required>
                            </div>

                            <button type="submit" class="btn btn-primary-custom btn-capture w-100">
                                KIRIM PRESENSI SEKARANG <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>