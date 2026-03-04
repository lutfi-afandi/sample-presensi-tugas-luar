<?php
session_start();
require 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$nama = $_SESSION['nama_lengkap'];

// Ambil riwayat absen
$query_absen = mysqli_query($conn, "SELECT * FROM presensi WHERE user_id = '$user_id' ORDER BY tanggal DESC, waktu DESC");

// Cek status hari ini
$hari_ini = date('Y-m-d');
$cek_hari_ini = mysqli_query($conn, "SELECT id FROM presensi WHERE user_id = '$user_id' AND tanggal = '$hari_ini'");
$sudah_absen = mysqli_num_rows($cek_hari_ini) > 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Presensi App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7fa;
            color: #334155;
        }

        .navbar {
            background: white;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-stats {
            border: none;
            border-radius: 16px;
            transition: transform 0.2s;
        }

        .card-stats:hover {
            transform: translateY(-5px);
        }

        .main-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        }

        .table thead {
            background-color: #f8fafc;
        }

        .btn-action {
            border-radius: 10px;
            padding: 6px 12px;
            font-size: 0.85rem;
        }

        .modal-content {
            border: none;
            border-radius: 20px;
        }

        .img-preview {
            width: 100%;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#"><i class="bi bi-geo-alt-fill me-2"></i>MECUT PRESENSI</a>
            <div class="d-flex align-items-center">
                <div class="text-end me-3 d-none d-md-block">
                    <small class="text-muted d-block">Selamat Datang,</small>
                    <span class="fw-bold"><?= $nama; ?></span>
                </div>
                <a href="logout.php" class="btn btn-light text-danger fw-bold rounded-pill shadow-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row g-3 mb-4 text-center">
            <div class="col-md-6">
                <div class="card card-stats p-4 bg-white shadow-sm">
                    <h6 class="text-muted mb-2">Status Hari Ini</h6>
                    <?php if ($sudah_absen): ?>
                        <div class="text-success fw-bold"><i class="bi bi-check-circle-fill"></i> Sudah Presensi</div>
                    <?php else: ?>
                        <div class="text-warning fw-bold"><i class="bi bi-exclamation-circle-fill"></i> Belum Presensi</div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-stats p-4 bg-primary text-white shadow-sm">
                    <h6 class="opacity-75 mb-2">Total Kehadiran</h6>
                    <div class="h4 fw-bold mb-0"><?= mysqli_num_rows($query_absen); ?> Kali</div>
                </div>
            </div>
        </div>

        <div class="text-center mb-5">
            <?php if (!$sudah_absen): ?>
                <a href="absen.php" class="btn btn-primary btn-lg px-5 py-3 rounded-pill shadow fw-bold">
                    <i class="bi bi-camera-fill me-2"></i> AMBIL PRESENSI SEKARANG
                </a>
            <?php endif; ?>
        </div>

        <div class="card main-card bg-white p-4">
            <h5 class="fw-bold mb-4">Riwayat Aktivitas</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="border-0">Tanggal & Waktu</th>
                            <th class="border-0">Lokasi</th>
                            <th class="border-0 text-center">Detail Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($query_absen) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($query_absen)): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= date('d M Y', strtotime($row['tanggal'])); ?></div>
                                        <small class="text-muted"><?= $row['waktu']; ?> WIB</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-light btn-action text-info"
                                            onclick="showMap('<?= $row['latitude']; ?>', '<?= $row['longitude']; ?>')">
                                            <i class="bi bi-geo-alt me-1"></i> Lihat Peta
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-primary btn-action"
                                            onclick="showDetail('<?= $row['foto_wajah']; ?>', '<?= $row['foto_kegiatan']; ?>', '<?= $row['surat_tugas']; ?>')">
                                            <i class="bi bi-eye-fill me-1"></i> Review Bukti
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center py-5 text-muted">Belum ada data terekam.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header border-0">
                    <h5 class="fw-bold"><i class="bi bi-file-earmark-check me-2"></i>Detail Bukti Presensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6 text-center">
                            <label class="d-block small fw-bold mb-2">Verifikasi Wajah</label>
                            <img id="view-wajah" src="" class="img-preview">
                        </div>
                        <div class="col-md-6 text-center">
                            <label class="d-block small fw-bold mb-2">Foto Kegiatan</label>
                            <img id="view-kegiatan" src="" class="img-preview">
                        </div>
                        <div class="col-12 mt-4">
                            <label class="d-block small fw-bold mb-2">Surat Tugas</label>
                            <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center">
                                <span id="view-surat-name" class="text-truncate me-2"></span>
                                <a id="view-surat-link" href="#" target="_blank" class="btn btn-sm btn-dark rounded-pill px-3">Buka File</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMap" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="map-iframe" width="100%" height="400" frameborder="0" style="border:0; border-radius:15px;" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showDetail(wajah, kegiatan, surat) {
            $('#view-wajah').attr('src', 'uploads/wajah/' + wajah);
            $('#view-kegiatan').attr('src', 'uploads/kegiatan/' + kegiatan);
            $('#view-surat-name').text(surat);
            $('#view-surat-link').attr('href', 'uploads/surat/' + surat);
            new bootstrap.Modal(document.getElementById('modalDetail')).show();
        }

        function showMap(lat, lng) {
            // Gunakan OpenStreetMap atau Google Maps Embed
            const mapUrl = `https://maps.google.com/maps?q=${lat},${lng}&z=15&output=embed`;
            $('#map-iframe').attr('src', mapUrl);
            new bootstrap.Modal(document.getElementById('modalMap')).show();
        }
    </script>
</body>

</html>