-- Buat tabel users
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nama_lengkap` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
);

-- Masukkan 1 user dummy (password: 123456)
-- Catatan: Di produksi nyata, password wajib di-hash (misal pakai password_hash di PHP)
INSERT INTO `users` (`username`, `password`, `nama_lengkap`) 
VALUES ('pegawai1', 'password123', 'Budi Santoso');

-- Buat tabel presensi
CREATE TABLE `presensi` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `tanggal` DATE NOT NULL,
  `waktu` TIME NOT NULL,
  `foto_wajah` VARCHAR(255) NOT NULL,
  `foto_kegiatan` VARCHAR(255) NOT NULL,
  `surat_tugas` VARCHAR(255) NOT NULL,
  `latitude` VARCHAR(50) NOT NULL,
  `longitude` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);