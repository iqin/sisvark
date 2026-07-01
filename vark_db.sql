-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Jul 2026 pada 08.52
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vark_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `materi_adaptif`
--

CREATE TABLE `materi_adaptif` (
  `id` int(11) NOT NULL,
  `modul_id` int(11) NOT NULL,
  `kode_konten` varchar(10) NOT NULL COMMENT 'M1-01, M1-02, ..., M3-36',
  `tipe_vark` char(1) NOT NULL COMMENT 'V, A, R, K, M',
  `level_zpd` varchar(20) NOT NULL COMMENT 'novice, apprentice, master',
  `judul` varchar(255) NOT NULL,
  `isi_konten` longtext DEFAULT NULL,
  `url_media` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `modul`
--

CREATE TABLE `modul` (
  `id` int(11) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `modul`
--

INSERT INTO `modul` (`id`, `judul`, `deskripsi`, `urutan`, `created_at`) VALUES
(1, 'Struktur & Fungsi Sel', 'Modul 1: Sel Prokariotik vs Eukariotik, Sel Hewan vs Tumbuhan', 1, NULL),
(2, 'Organel Sel', 'Modul 2: Mitokondria, Ribosom, RE, Badan Golgi, Lisosom', 2, NULL),
(3, 'Transpor Membran', 'Modul 3: Difusi, Osmosis, Transpor Aktif, Endositosis', 3, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `kata_sandi` varchar(255) NOT NULL,
  `peran` enum('siswa','guru','admin') NOT NULL DEFAULT 'siswa',
  `sekolah` varchar(100) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `email`, `kata_sandi`, `peran`, `sekolah`, `kelas`, `created_at`, `updated_at`) VALUES
(1, 'Pakiqin', 'pakiqin@gmail.com', '$2y$10$biSP3WobtsojK/38rOtSFuMeoMJm53g2eCXTq9MoILxxRSxDjV32u', 'siswa', '', 'XI IPA', '2026-06-29 07:45:05', '2026-06-29 07:45:05'),
(2, 'Ahmad Sodiqin', 'xiqinx@gmail.com', '$2y$10$qo3Q5uFyA1eRGivJng.fYuDtPVvWiw16DgBOM1Y0XXKGV4Sanpb5q', 'guru', 'SMK Negeri 1 Tanjung Morawa', 'XI IPA', '2026-06-29 07:50:46', '2026-06-29 07:50:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `vark_hasil`
--

CREATE TABLE `vark_hasil` (
  `id` int(11) NOT NULL,
  `pengguna_id` int(11) NOT NULL,
  `skor_v` int(2) NOT NULL DEFAULT 0,
  `skor_a` int(2) NOT NULL DEFAULT 0,
  `skor_r` int(2) NOT NULL DEFAULT 0,
  `skor_k` int(2) NOT NULL DEFAULT 0,
  `selisih` int(2) NOT NULL,
  `kategori_hasil` varchar(50) NOT NULL COMMENT 'Visual, Aural, Read/Write, Kinestetik, Multimodal',
  `tipe_hasil` char(1) NOT NULL COMMENT 'V, A, R, K, M',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `vark_soal`
--

CREATE TABLE `vark_soal` (
  `id` int(11) NOT NULL,
  `nomor` int(2) NOT NULL,
  `teks_soal` text NOT NULL,
  `opsi_v` text NOT NULL,
  `opsi_a` text NOT NULL,
  `opsi_r` text NOT NULL,
  `opsi_k` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `vark_soal`
--

INSERT INTO `vark_soal` (`id`, `nomor`, `teks_soal`, `opsi_v`, `opsi_a`, `opsi_r`, `opsi_k`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ketika guru menjelaskan materi Biologi tentang sistem pernapasan manusia, cara yang paling membantu saya memahami materi tersebut adalah...', 'Melihat gambar, diagram, atau video yang menunjukkan struktur dan proses sistem pernapasan', 'Mendengarkan penjelasan lisan guru dan diskusi di kelas', 'Membaca buku teks atau rangkuman tertulis, kemudian mencatat poin- Instrumen ini merupakan instrumen yang akan dimasukkan kedalam sistem untuk pengklasifikasian VARK siswa  poin penting', 'Mengikuti praktikum, simulasi, atau aktivitas langsung yang melibatkan pernapasan', NULL, '2026-07-01 06:37:38'),
(2, 2, 'Saat saya harus mempersiapkan diri menghadapi ulangan, saya biasanya lebih mudah belajar dengan cara...', 'Membuat atau melihat peta konsep, bagan, atau warna-warna penanda materi pelajaran', 'Mengulang materi dengan mendengarkan penjelasan guru, teman, atau rekaman suara', 'Membaca ulang catatan dan buku pelajaran, lalu menuliskannya kembali dengan bahasa sendiri', 'Belajar sambil mempraktikkan, mencoba contoh soal langsung, atau belajar sambil bergerak', NULL, '2026-07-01 06:37:26'),
(3, 3, 'Guru Biologi sedang menjelaskan cara kerja jantung manusia di depan kelas. Agar kamu paling cepat paham, apa yang sebaiknya guru tersebut lakukan?', 'Menunjukkan poster gambar anatomi jantung atau diagram alur peredaran darah di papan tulis/layar proyektor.', 'Menjelaskan secara lisan  dengan bercerita  atau mengadakan sesi tanya jawab langsung dengan siswa', 'Memberikan lembaran materi (handout) atau menyuruh siswa membaca buku paket yang menjelaskan rincian prosesnya secara tertulis', 'Membawa model patung jantung (torso) ke kelas agar bisa dipegang dan dibongkar-pasang oleh siswa', '2026-06-29 14:03:39', '2026-07-01 06:43:39'),
(4, 4, 'Kelompokmu mendapat tugas membuat proyek mading atau presentasi tentang Sejarah Kemerdekaan. Saat diskusi pembagian tugas, peran apa yang paling ingin kamu ambil?', 'Bagian desain & tata letak (layout): Mengatur posisi gambar, memilih warna, dan membuat grafik/peta konsep agar terlihat menarik.', 'Bagian juru bicara (presenter): Mempresentasikan hasil diskusi di depan kelas atau memimpin diskusi kelompok', 'Bagian penulis konten: Mencari referensi dari buku/internet, lalu menuliskan naskah atau ringkasan materinya', 'Bagian perlengkapan & perakitan: Menggunting, menempel, atau membuat properti fisik/maket yang dibutuhkan', '2026-07-01 06:37:01', '2026-07-01 06:44:30'),
(5, 5, 'Jika saya mengalami kesulitan memahami suatu konsep pelajaran, hal pertama yang biasanya saya lakukan adalah …', 'Mencari gambar, diagram, atau video yang menjelaskan konsep tersebut', 'Bertanya dan mendengarkan penjelasan guru atau teman', 'Membaca kembali buku pelajaran atau catatan hingga saya paham', 'Mencoba mempraktikkan konsep tersebut melalui contoh atau kegiatan nyata', '2026-07-01 06:38:30', '2026-07-01 06:38:30'),
(6, 6, 'Besok ada ujian sekolah dan kamu harus belajar materi yang cukup banyak.\r\nCara belajar seperti apa yang biasanya kamu lakukan di rumah?', 'Membuat peta pikiran (mind map), diagram, atau memberi warna- warni stabilo (highlighter) pada poin-poin penting di catatanmu', 'Belajar sambil berdiskusi dengan teman lewat telepon, atau membaca materi dengan suara keras (didengar sendiri)', 'Membaca  ulang  catatan  buku  tulis  berulang-ulang  atau  menulis ringkasan materi menggunakan kalimat sendiri', 'Mengerjakan latihan soal (try out) atau menirukan/mempraktikkan kejadian (roleplay) dari materi yang dipelajari', '2026-07-01 06:39:09', '2026-07-01 06:39:09'),
(7, 7, 'Ketika mempelajari konsep abstrak yang sulit dibayangkan (misalnya mekanisme kerja organ atau proses biologis), saya paling mudah memahami materi jika …', 'Konsep tersebut disajikan dalam visualisasi bertahap (diagram alur, skema proses, animasi) sehingga saya dapat melihat hubungan antarbagian', 'Guru  menjelaskan  secara  lisan  langkah  demi  langkah,  disertai contoh analogi yang dapat saya dengarkan', 'Saya dapat membaca penjelasan tertulis secara runtut, kemudian menuliskan kembali konsep tersebut dengan kata-kata saya sendiri', 'Saya diberi kesempatan untuk mengaitkan konsep dengan pengalaman nyata atau aktivitas simulatif, sehingga saya dapat “merasakan” prosesnya', '2026-07-01 06:40:06', '2026-07-01 06:45:41'),
(8, 8, 'Kamu mengikuti ekstrakurikuler (misalnya basket, tari, atau musik) dan pelatih mengajarkan teknik gerakan baru. Bagaimana caramu agar bisa cepat menirunya?', 'Melihat gambar sketsa formasi atau diagram langkah kaki yang digambar pelatih di papan strategi', 'Mendengarkan aba-aba dan penjelasan pelatih dengan saksama sebelum mencoba', 'Membaca buku panduan aturan atau catatan instruksi teknik yang diberikan', 'Memperhatikan  contoh  gerakan  pelatih  lalu  langsung  ikut mempraktikkannya berulang-ulang dengan tubuhmu sendiri', '2026-07-01 06:40:45', '2026-07-01 06:40:45'),
(9, 9, 'Ketika harus menerapkan materi pelajaran untuk menyelesaikan soal atau masalah baru, saya biasanya …', 'Membayangkan diagram, skema, atau pola visual yang berkaitan dengan masalah tersebut', 'Mengingat kembali penjelasan lisan atau diskusi yang pernah saya dengar terkait materi itu.', 'Merujuk pada langkah-langkah tertulis atau rumus yang saya baca dan pahami sebelumnya.', 'Memikirkan bagaimana masalah tersebut dapat diselesaikan melalui tindakan atau simulasi nyata', '2026-07-01 06:41:38', '2026-07-01 06:41:38'),
(10, 10, 'Saat  jam  pelajaran  praktikum  di  laboratorium,  guru  meminta  kamu merangkai alat percobaan (misalnya rangkaian listrik atau mencampur larutan kimia). Apa yang pertama kali kamu lakukan?', 'Mengamati gambar diagram alur atau skema rangkaian yang ada di papan tulis', 'Menunggu   instruksi   lisan   guru   atau   bertanya   kepada   teman kelompok: \"Ini yang mana dulu yang dipasang?\"', 'Membaca  langkah-langkah  kerja  (LKS)  atau  modul  penuntun praktikum dengan teliti', 'Langsung  memegang  alat-alatnya  dan  mencoba  merangkainya  sendiri sambil jalan (trial and error)', '2026-07-01 06:42:17', '2026-07-01 06:42:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `zpd_hasil`
--

CREATE TABLE `zpd_hasil` (
  `id` int(11) NOT NULL,
  `pengguna_id` int(11) NOT NULL,
  `modul_id` int(11) NOT NULL,
  `total_nilai` int(3) NOT NULL,
  `level_zpd` varchar(20) NOT NULL COMMENT 'novice, apprentice, master',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `zpd_soal`
--

CREATE TABLE `zpd_soal` (
  `id` int(11) NOT NULL,
  `modul_id` int(11) NOT NULL,
  `level_zpd` varchar(20) NOT NULL COMMENT 'dasar, menengah, lanjut',
  `bobot_nilai` int(2) NOT NULL COMMENT '5, 10, atau 15',
  `teks_soal` text NOT NULL,
  `opsi_a` varchar(255) NOT NULL,
  `opsi_b` varchar(255) NOT NULL,
  `opsi_c` varchar(255) NOT NULL,
  `opsi_d` varchar(255) NOT NULL,
  `jawaban_benar` char(1) NOT NULL COMMENT 'A, B, C, D',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `materi_adaptif`
--
ALTER TABLE `materi_adaptif`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_konten` (`kode_konten`),
  ADD KEY `modul_id` (`modul_id`);

--
-- Indeks untuk tabel `modul`
--
ALTER TABLE `modul`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `vark_hasil`
--
ALTER TABLE `vark_hasil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengguna_id` (`pengguna_id`);

--
-- Indeks untuk tabel `vark_soal`
--
ALTER TABLE `vark_soal`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `zpd_hasil`
--
ALTER TABLE `zpd_hasil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengguna_id` (`pengguna_id`),
  ADD KEY `modul_id` (`modul_id`);

--
-- Indeks untuk tabel `zpd_soal`
--
ALTER TABLE `zpd_soal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `modul_id` (`modul_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `materi_adaptif`
--
ALTER TABLE `materi_adaptif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `modul`
--
ALTER TABLE `modul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `vark_hasil`
--
ALTER TABLE `vark_hasil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `vark_soal`
--
ALTER TABLE `vark_soal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `zpd_hasil`
--
ALTER TABLE `zpd_hasil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `zpd_soal`
--
ALTER TABLE `zpd_soal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `materi_adaptif`
--
ALTER TABLE `materi_adaptif`
  ADD CONSTRAINT `materi_adaptif_ibfk_1` FOREIGN KEY (`modul_id`) REFERENCES `modul` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `vark_hasil`
--
ALTER TABLE `vark_hasil`
  ADD CONSTRAINT `vark_hasil_ibfk_1` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `zpd_hasil`
--
ALTER TABLE `zpd_hasil`
  ADD CONSTRAINT `zpd_hasil_ibfk_1` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `zpd_hasil_ibfk_2` FOREIGN KEY (`modul_id`) REFERENCES `modul` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `zpd_soal`
--
ALTER TABLE `zpd_soal`
  ADD CONSTRAINT `zpd_soal_ibfk_1` FOREIGN KEY (`modul_id`) REFERENCES `modul` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
