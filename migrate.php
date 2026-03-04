<?php
// migrate.php
require 'koneksi.php'; // Pastikan file ini berisi variabel $conn (mysqli) atau $pdo

$migrationDir = __DIR__ . '/database/migrations/';
$files = scandir($migrationDir);

echo "--- Starting Migration ---\n";

foreach ($files as $file) {
    // Hanya proses file dengan ekstensi .sql
    if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
        echo "Migrating: $file ... ";

        // Baca isi file SQL
        $sql = file_get_contents($migrationDir . $file);

        // Eksekusi SQL
        // Jika pakai mysqli:
        if (mysqli_multi_query($conn, $sql)) {
            // Pastikan semua query dalam satu file selesai
            do {
                if ($result = mysqli_store_result($conn)) {
                    mysqli_free_result($result);
                }
            } while (mysqli_next_result($conn));

            echo "SUCCESS\n";
        } else {
            echo "FAILED: " . mysqli_error($conn) . "\n";
        }
    }
}

echo "--- Migration Finished ---\n";
