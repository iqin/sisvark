-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Sep 2026 pada 03.12
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
  `tipe_tampilan` enum('gambar','teks','audio','video','interaktif','hybrid') NOT NULL DEFAULT 'teks',
  `gambar_url` varchar(255) DEFAULT NULL,
  `teks_konten` longtext DEFAULT NULL,
  `audio_url` varchar(255) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `interaktif_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `materi_adaptif`
--

INSERT INTO `materi_adaptif` (`id`, `modul_id`, `kode_konten`, `tipe_vark`, `level_zpd`, `judul`, `tipe_tampilan`, `gambar_url`, `teks_konten`, `audio_url`, `video_url`, `interaktif_url`, `created_at`) VALUES
(1, 1, 'M1-01', 'V', 'novice', 'Diagram Sel Berlabel Lengkap', 'gambar', 'assets/images/modul1/1784301548_288002dd0e4094071a76.jpg', 'Diagram besar sel hewan dan tumbuhan dengan label lengkap. Teks penjelasan muncul di samping setiap label. Animasi video berjalan lambat.', '', '', '', NULL),
(2, 1, 'M1-02', 'V', 'apprentice', 'Diagram Sel Standar (Klik Info)', 'video', '/assets/images/modul1/M1-02_diagram_standar.png', 'Diagram standar sel. Label nama ada, tapi penjelasan disembunyikan. Siswa klik ikon untuk membaca informasi.', '', '', '', NULL),
(3, 1, 'M1-03', 'V', 'master', 'Blind Diagram (Peta Buta)', 'gambar', '/assets/images/modul1/M1-03_blind_diagram.png', 'Gambar sel tanpa label sama sekali. Siswa diminta mengidentifikasi bagian berdasarkan bentuk dan posisi.', NULL, NULL, NULL, NULL),
(4, 1, 'M1-04', 'A', 'novice', 'Audio + Transkrip (Karaoke)', 'audio', NULL, 'Audio player dengan transkrip yang berjalan seperti karaoke/subtitle. Guru menjelaskan materi dengan suara.', 'assets/audio/modul1/1784302353_a096d708346e9ca7dec6.mp3', NULL, NULL, NULL),
(5, 1, 'M1-05', 'A', 'apprentice', 'Audio + Key Points', 'audio', NULL, 'Audio player dengan poin-poin penting di layar. Fokus utama pada penjelasan suara.', '/assets/audio/modul1/M1-05_audio.mp3', NULL, NULL, NULL),
(6, 1, 'M1-06', 'A', 'master', 'Audio Case - Tebak Jenis Sel', 'audio', NULL, 'Rekaman suara menjelaskan ciri-ciri sel misterius. Siswa harus menebak jenis sel hanya dari suara.', '/assets/audio/modul1/M1-06_audio_case.mp3', NULL, NULL, NULL),
(7, 1, 'M1-07', 'R', 'novice', 'Artikel Pendek + Glosarium', 'interaktif', NULL, 'Teks dipotong per paragraf. Kata sulit (Prokariotik, Eukariotik) di-bold dan bisa diklik artinya.', NULL, NULL, '/assets/text/modul1/M1-07_artikel_pendek.html', NULL),
(8, 1, 'M1-08', 'R', 'apprentice', 'Artikel Utuh (Standar)', 'interaktif', NULL, 'Teks standar buku paket satu halaman penuh. Tidak ada fitur pop-up otomatis.', NULL, NULL, '/assets/text/modul1/M1-08_artikel_utuh.html', NULL),
(9, 1, 'M1-09', 'R', 'master', 'Jurnal Ilmiah', 'interaktif', NULL, 'Teks akademis/kompleks. Siswa diminta membuat rangkuman/kesimpulan sendiri.', NULL, NULL, '/assets/text/modul1/M1-09_jurnal_ilmiah.html', NULL),
(10, 1, 'M1-10', 'K', 'novice', 'Video Demo Praktikum', 'video', NULL, 'Video tutorial membelah sel bawang merah, diikuti kuis langkah-langkah.', NULL, 'https://www.youtube.com/watch?v=UJcGZtgQq9A', NULL, NULL),
(11, 1, 'M1-11', 'K', 'apprentice', 'Puzzle Sel (Drag-and-Drop)', 'interaktif', NULL, 'Drag-and-drop bagian sel hewan ke tempat yang benar. Ada tombol Hint jika bingung.', NULL, NULL, '/assets/interactive/modul1/M1-11_puzzle_sel.html', NULL),
(12, 1, 'M1-12', 'K', 'master', 'Game Klasifikasi Sel', 'interaktif', NULL, 'Muncul berbagai gambar sel secara cepat. Siswa harus geser Kanan (Hewan) atau Kiri (Tumbuhan).', NULL, NULL, '/assets/interactive/modul1/M1-12_game_klasifikasi.html', NULL),
(13, 2, 'M2-13', 'V', 'novice', 'Flashcard Digital Organel', 'gambar', '/assets/images/modul2/M2-13_flashcard.png', 'Kartu flashcard digital: gambar organel di satu sisi, fungsi di sisi lain. Ada jembatan keledai visual.', NULL, NULL, NULL, NULL),
(14, 2, 'M2-14', 'V', 'apprentice', 'Peta Konsep (Mind Map)', 'gambar', '/assets/images/modul2/M2-14_mind_map.png', 'Bagan hubungan antar organel yang rumpang (kosong sebagian). Siswa melengkapi.', NULL, NULL, NULL, NULL),
(15, 2, 'M2-15', 'V', 'master', 'Mikrograf Asli Organel', 'gambar', '/assets/images/modul2/M2-15_mikrograf.png', 'Foto mikroskop elektron (hitam putih) organel asli. Siswa menganalisis struktur krista/membran.', NULL, NULL, NULL, NULL),
(16, 2, 'M2-16', 'A', 'novice', 'Podcast Narasi \"Perjalanan Protein\"', 'audio', NULL, 'Podcast narasi: \"Kisah perjalanan protein di dalam pabrik sel\". Penjelasan menggunakan analogi pabrik.', '/assets/audio/modul2/M2-16_podcast.mp3', NULL, NULL, NULL),
(17, 2, 'M2-17', 'A', 'apprentice', 'Rekaman Kuliah Organel', 'audio', NULL, 'Penjelasan formal fungsi organel. Siswa mencatat poin penting.', '/assets/audio/modul2/M2-17_kuliah.mp3', NULL, NULL, NULL),
(18, 2, 'M2-18', 'A', 'master', 'Audio Debat Lisosom', 'audio', NULL, 'Rekaman dua orang berdebat tentang fungsi Lisosom. Siswa menentukan siapa yang benar.', '/assets/audio/modul2/M2-18_debat.mp3', NULL, NULL, NULL),
(19, 2, 'M2-19', 'R', 'novice', 'Tabel Rangkuman Organel', 'interaktif', NULL, 'Teks disajikan dalam bentuk tabel (Nama - Gambar - Fungsi) agar mudah dihafal.', NULL, NULL, '/assets/text/modul2/M2-19_tabel_rangkuman.html', NULL),
(20, 2, 'M2-20', 'R', 'apprentice', 'Teks Deskriptif Organel', 'interaktif', NULL, 'Penjelasan paragraf per organel secara detail dan terstruktur.', NULL, NULL, '/assets/text/modul2/M2-20_teks_deskriptif.html', NULL),
(21, 2, 'M2-21', 'R', 'master', 'Analisis Penyakit Organel', 'interaktif', NULL, 'Teks tentang penyakit yang disebabkan kerusakan organel (Tay-Sachs pada Lisosom).', NULL, NULL, '/assets/text/modul2/M2-21_analisis_penyakit.html', NULL),
(22, 2, 'M2-22', 'K', 'novice', 'Virtual Lab 3D (Guided)', 'interaktif', NULL, 'Mengelilingi model sel 3D. Ada pemandu: \"Klik yang berwarna merah, itu Mitokondria\".', NULL, NULL, '/assets/interactive/modul2/M2-22_virtual_lab.html', NULL),
(23, 2, 'M2-23', 'K', 'apprentice', 'Simulasi Kerja Badan Golgi', 'interaktif', NULL, 'Siswa berperan sebagai Badan Golgi, tugasnya mengepak protein ke vesikel yang benar.', NULL, NULL, '/assets/interactive/modul2/M2-23_simulasi_golgi.html', NULL),
(24, 2, 'M2-24', 'K', 'master', 'Simulasi Kerusakan Organel', 'interaktif', NULL, '\"Sel ini tidak menghasilkan energi. Organel mana yang rusak? Perbaiki!\"', NULL, NULL, '/assets/interactive/modul2/M2-24_simulasi_kerusakan.html', NULL),
(25, 3, 'M3-25', 'V', 'novice', 'Animasi Difusi', 'gambar', '/assets/images/modul3/M3-25_animasi_difusi.gif', 'Video kartun partikel bergerak dari padat ke renggang. Ada teks besar \"DIFUSI\".', NULL, NULL, NULL, NULL),
(26, 3, 'M3-26', 'V', 'apprentice', 'Grafik Laju Transpor', 'gambar', '/assets/images/modul3/M3-26_grafik_transpor.png', 'Gambar grafik hubungan konsentrasi vs laju difusi. Siswa membaca grafik.', NULL, NULL, NULL, NULL),
(27, 3, 'M3-27', 'V', 'master', 'Eksperimen Virtual', 'interaktif', NULL, 'Diberi gambar gelas beker dengan konsentrasi berbeda. \"Gambarkan arah panah air!\"', NULL, NULL, '/assets/interactive/modul3/M3-27_eksperimen_virtual.html', NULL),
(28, 3, 'M3-28', 'A', 'novice', 'Audio Glosarium Osmosis', 'audio', NULL, 'Penjelasan lisan istilah \"Hipertonik, Hipotonik, Isotonik\" secara berulang dan pelan.', '/assets/audio/modul3/M3-28_audio_glosarium.mp3', NULL, NULL, NULL),
(29, 3, 'M3-29', 'A', 'apprentice', 'Audio Kuis', 'audio', NULL, 'Suara membacakan soal cerita tentang transpor membran, siswa memilih jawaban.', '/assets/audio/modul3/M3-29_audio_kuis.mp3', NULL, NULL, NULL),
(30, 3, 'M3-30', 'A', 'master', 'Pertanyaan Pemantik (Socratic)', 'audio', NULL, 'Suara mengajukan pertanyaan: \"Mengapa ikan air laut tidak mati dehidrasi?\" tanpa jawaban.', '/assets/audio/modul3/M3-30_socratic.mp3', NULL, NULL, NULL),
(31, 3, 'M3-31', 'R', 'novice', 'Resep Prosedur Transpor', 'interaktif', NULL, 'Teks urutan proses: \"1. Air masuk, 2. Sel menggembung, 3. Sel pecah (Lisis)\".', NULL, NULL, '/assets/text/modul3/M3-31_resep_prosedur.html', NULL),
(32, 3, 'M3-32', 'R', 'apprentice', 'Artikel Mekanisme Transpor', 'interaktif', NULL, 'Menjelaskan perbedaan Difusi Terfasilitasi vs Transpor Aktif.', NULL, NULL, '/assets/text/modul3/M3-32_artikel_mekanisme.html', NULL),
(33, 3, 'M3-33', 'R', 'master', 'Kasus Medis Infus Salah', 'interaktif', NULL, 'Kasus pemberian infus yang salah (Hipotonik) menyebabkan kematian pasien. Analisis penyebabnya.', NULL, NULL, '/assets/text/modul3/M3-33_kasus_medis.html', NULL),
(34, 3, 'M3-34', 'K', 'novice', 'Slider Konsentrasi', 'interaktif', NULL, 'Menggeser slider kadar garam, melihat sel darah merah mengerut/pecah secara instan.', NULL, NULL, '/assets/interactive/modul3/M3-34_slider.html', NULL),
(35, 3, 'M3-35', 'K', 'apprentice', 'SimLab Osmosis Kentang', 'interaktif', NULL, 'Menimbang kentang, merendam virtual, menimbang ulang.', NULL, NULL, '/assets/interactive/modul3/M3-35_simlab_kentang.html', NULL),
(36, 3, 'M3-36', 'K', 'master', 'Design Lab Transpor Aktif', 'interaktif', NULL, '\"Desainlah percobaan untuk membuktikan transpor aktif menggunakan alat ini.\"', NULL, NULL, '/assets/interactive/modul3/M3-36_design_lab.html', NULL);

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
(2, 'Ahmad Sodiqin', 'xiqinx@gmail.com', '$2y$10$qo3Q5uFyA1eRGivJng.fYuDtPVvWiw16DgBOM1Y0XXKGV4Sanpb5q', 'guru', 'SMK Negeri 1 Tanjung Morawa', 'XI IPA', '2026-06-29 07:50:46', '2026-06-29 07:50:46'),
(3, 'UJI COBA KELAS', 'ahmad.sodiqin622@admin.smk.belajar.id', '$2y$10$F9UzsS0MOgDVPDjpougzoObkznW4IluGYVQpU2B8fkDK1Hh3DSmEm', 'siswa', 'SMAN 1 Banda Aceh', 'XI IPA 1', '2026-07-01 09:38:09', '2026-07-01 09:38:09'),
(4, 'pakiqin2', 'gdgtutor@gmail.com', '$2y$10$ayr3ZFaXCLRpRte2aPOoC.ZIWZVX9hgzzfG3gLH76Dv6Tucjj9AW.', 'siswa', 'SMAN 1 Banda Aceh', 'XI MIPA 2', '2026-07-11 16:57:13', '2026-07-11 16:57:13'),
(5, 'siswa 4', 'siswa@gmail.com', '$2y$10$jZN21v0G9E/khV.oX2XvQ.lrqu0B80ZRGr2P4chdvhm0bROkc/i7y', 'siswa', 'SMA Negeri 1 Tanah Jambo Aye', 'XI IPA 1', '2026-08-06 11:30:10', '2026-08-06 11:30:10'),
(6, 'Nama Saya APa', 'brickmonkeynft@gmail.com', '$2y$10$1OPNvm84EdCy9hMaQaBHDO73JDz0soB97pspX6KS8ZuZe5fMwNjmS', 'siswa', 'SMA Nusantara', 'XI', '2026-09-03 15:43:10', '2026-09-03 15:43:10'),
(7, 'test iqin', 'iqingm.gm@gmail.com', '$2y$10$MzR9LJ69FZ6nO3Iyw3/dlug0ctBHs8/pWxs9Gy99kOpuXvhq/lt2.', 'siswa', 'SMA', 'XI IPA', '2026-09-08 18:20:15', '2026-09-08 18:20:15'),
(8, 'Satu Dua', '123@gmail.com', '$2y$10$j6I6TyD.n0BbEFuv6VY.RuSMe046AbRE4SAX85XSimJA88hYRs.fq', 'siswa', 'SMA', 'XI', '2026-09-09 00:56:45', '2026-09-09 00:56:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesan`
--

CREATE TABLE `pesan` (
  `id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `guru_id` int(11) NOT NULL,
  `pesan` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `is_from_guru` tinyint(1) NOT NULL DEFAULT 0,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesan`
--

INSERT INTO `pesan` (`id`, `siswa_id`, `guru_id`, `pesan`, `is_read`, `is_from_guru`, `parent_id`, `created_at`) VALUES
(1, 1, 2, 'tes tanya guru', 1, 0, NULL, '2026-08-06 10:26:40'),
(2, 1, 2, 'ok saya kesana saja', 1, 1, 1, '2026-08-06 10:31:48'),
(3, 4, 2, 'bu... mau nanya dong', 1, 0, NULL, '2026-08-06 10:36:38'),
(4, 4, 2, 'saya guru, kamu mau nanya apa siswaku?', 1, 1, 3, '2026-08-06 10:37:19'),
(5, 3, 2, 'saya siswa bu.. mau nanya', 1, 0, NULL, '2026-08-06 10:55:27'),
(6, 3, 2, 'iya...mau nanya apa?', 1, 1, 5, '2026-08-06 10:55:41'),
(7, 3, 2, 'ibu bisa kesini?', 1, 0, NULL, '2026-08-06 10:56:00'),
(8, 3, 2, 'ok saya kesana', 1, 1, 7, '2026-08-06 10:56:16'),
(9, 5, 2, 'saya murid, butuh bantuan', 1, 0, NULL, '2026-08-06 11:35:17'),
(10, 5, 2, 'ok saya kesana', 1, 1, 9, '2026-08-06 11:35:36'),
(11, 3, 2, 'ibuuuuuuu', 1, 0, NULL, '2026-08-06 13:14:15'),
(12, 3, 2, 'halo ibuuuuuu', 1, 0, NULL, '2026-08-06 13:14:47'),
(13, 3, 2, 'ok', 1, 1, NULL, '2026-08-06 13:20:50'),
(14, 3, 2, 'halo lagi bu', 1, 0, NULL, '2026-08-06 13:24:13'),
(15, 3, 2, 'ibuuuu', 1, 0, NULL, '2026-08-06 13:24:32'),
(16, 3, 2, 'halo ibu lagi', 1, 0, NULL, '2026-08-06 13:36:25'),
(17, 3, 2, 'tes lagi', 0, 0, NULL, '2026-08-06 13:44:12'),
(18, 6, 2, '⚠️ **Notifikasi Post Test**\n\nSiswa: Nama Saya APa\nModul: Struktur & Fungsi Sel\nSkor: 25\nLevel Awal: Master (Ahli)\nLevel Akhir: Novice (Pemula)\nStatus: ❌ TIDAK LULUS (Level menurun)\n\nSiswa memerlukan intervensi remedial.', 1, 0, NULL, '2026-09-03 17:30:07'),
(19, 3, 2, '⚠️ **Notifikasi Post Test**\n\nSiswa: UJI COBA KELAS\nModul: Struktur & Fungsi Sel\nSkor: 15\nLevel Awal: Apprentice (Menengah)\nLevel Akhir: Novice (Pemula)\nStatus: ❌ TIDAK LULUS (Level menurun)\n\nSiswa memerlukan intervensi remedial.', 0, 0, NULL, '2026-09-03 17:36:12'),
(20, 6, 2, '⚠️ **Notifikasi Post Test**\n\nSiswa: Nama Saya APa\nModul: Struktur & Fungsi Sel\nSkor: 15\nLevel Awal: Master (Ahli)\nLevel Akhir: Novice (Pemula)\nStatus: ❌ TIDAK LULUS (Level menurun)\n\nSiswa memerlukan intervensi remedial.', 1, 0, NULL, '2026-09-08 16:11:00'),
(21, 6, 2, '⚠️ **Notifikasi Post Test**\n\nSiswa: Nama Saya APa\nModul: Struktur & Fungsi Sel\nSkor: 15\nLevel Awal: Master (Ahli)\nLevel Akhir: Novice (Pemula)\nStatus: ❌ TIDAK LULUS (Level menurun)\n\nSiswa memerlukan intervensi remedial.', 1, 0, NULL, '2026-09-08 16:28:31'),
(22, 6, 2, '⚠️ **Notifikasi Post Test**\n\nSiswa: Nama Saya APa\nModul: Struktur & Fungsi Sel\nSkor: 0\nLevel Awal: Novice (Pemula)\nLevel Akhir: Novice (Pemula)\nStatus: ❌ TIDAK LULUS (Level menurun)\n\nSiswa memerlukan intervensi remedial.', 1, 0, NULL, '2026-09-08 16:40:34'),
(23, 8, 2, '⚠️ **Notifikasi Post Test**\n\nSiswa: Satu Dua\nModul: Organel Sel\nSkor: 10\nLevel Awal: Novice (Pemula)\nLevel Akhir: Novice (Pemula)\nStatus: ❌ TIDAK LULUS (Level menurun)\n\nSiswa memerlukan intervensi remedial.', 0, 0, NULL, '2026-09-09 00:59:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `post_test_hasil`
--

CREATE TABLE `post_test_hasil` (
  `id` int(11) NOT NULL,
  `pengguna_id` int(11) NOT NULL,
  `modul_id` int(11) NOT NULL,
  `skor` int(3) NOT NULL,
  `level_zpd_awal` varchar(20) NOT NULL COMMENT 'novice, apprentice, master',
  `level_zpd_akhir` varchar(20) NOT NULL COMMENT 'novice, apprentice, master',
  `status` enum('lulus','tidak_lulus') NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `bisa_ulang` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `post_test_hasil`
--

INSERT INTO `post_test_hasil` (`id`, `pengguna_id`, `modul_id`, `skor`, `level_zpd_awal`, `level_zpd_akhir`, `status`, `created_at`, `bisa_ulang`) VALUES
(2, 3, 1, 15, 'apprentice', 'novice', 'tidak_lulus', '2026-09-03 17:36:12', 0),
(7, 6, 1, 50, 'novice', 'apprentice', 'lulus', '2026-09-08 16:45:17', 0),
(11, 6, 2, 70, 'apprentice', 'apprentice', 'lulus', '2026-09-08 17:46:08', 0),
(12, 4, 1, 70, 'novice', 'apprentice', 'lulus', '2026-09-08 18:16:30', 0),
(13, 7, 1, 15, 'novice', 'novice', 'lulus', '2026-09-08 18:21:35', 0),
(14, 7, 2, 55, 'novice', 'apprentice', 'lulus', '2026-09-08 18:22:40', 0),
(15, 7, 3, 60, 'apprentice', 'apprentice', 'lulus', '2026-09-08 18:23:24', 0),
(16, 8, 1, 30, 'novice', 'novice', 'lulus', '2026-09-09 00:58:45', 0),
(17, 8, 2, 10, 'novice', 'novice', 'tidak_lulus', '2026-09-09 00:59:28', 0);

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
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `vark_hasil`
--

INSERT INTO `vark_hasil` (`id`, `pengguna_id`, `skor_v`, `skor_a`, `skor_r`, `skor_k`, `selisih`, `kategori_hasil`, `tipe_hasil`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 6, 1, 0, 3, 'Aural', 'A', '2026-07-01 08:46:05', NULL),
(4, 3, 3, 5, 0, 2, 2, 'Multimodal (Campuran)', 'M', '2026-07-03 08:44:33', NULL),
(8, 4, 9, 0, 0, 0, 9, 'Visual', 'V', '2026-07-11 17:51:15', NULL),
(9, 5, 1, 0, 0, 9, 8, 'Kinestetik', 'K', '2026-08-06 11:31:23', NULL),
(10, 6, 9, 0, 1, 0, 8, 'Visual', 'V', '2026-09-03 15:44:11', NULL),
(11, 7, 10, 0, 0, 0, 10, 'Visual', 'V', '2026-09-08 18:20:39', NULL),
(12, 8, 10, 0, 0, 0, 10, 'Visual', 'V', '2026-09-09 00:57:16', NULL);

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
-- Struktur dari tabel `vark_test_session`
--

CREATE TABLE `vark_test_session` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_time` int(11) NOT NULL,
  `question_order` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Dumping data untuk tabel `zpd_hasil`
--

INSERT INTO `zpd_hasil` (`id`, `pengguna_id`, `modul_id`, `total_nilai`, `level_zpd`, `created_at`) VALUES
(3, 3, 1, 70, 'novice', '2026-07-10 12:08:18'),
(8, 1, 1, 15, 'novice', '2026-08-06 09:31:58'),
(10, 6, 1, 70, 'apprentice', '2026-09-03 15:47:49'),
(11, 6, 2, 70, 'apprentice', '2026-09-08 17:46:08'),
(12, 4, 1, 70, 'apprentice', '2026-09-08 18:16:04'),
(13, 7, 1, 60, 'apprentice', '2026-09-08 18:21:10'),
(14, 7, 2, 55, 'apprentice', '2026-09-08 18:22:40'),
(15, 7, 3, 60, 'apprentice', '2026-09-08 18:23:24'),
(16, 8, 1, 10, 'novice', '2026-09-09 00:58:01'),
(17, 8, 2, 10, 'novice', '2026-09-09 00:59:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `zpd_soal`
--

CREATE TABLE `zpd_soal` (
  `id` int(11) NOT NULL,
  `modul_id` int(11) NOT NULL,
  `level_zpd` varchar(20) NOT NULL COMMENT 'notice, apprentice, master',
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
-- Dumping data untuk tabel `zpd_soal`
--

INSERT INTO `zpd_soal` (`id`, `modul_id`, `level_zpd`, `bobot_nilai`, `teks_soal`, `opsi_a`, `opsi_b`, `opsi_c`, `opsi_d`, `jawaban_benar`, `created_at`) VALUES
(1, 1, 'dasar', 5, 'Organel yang berfungsi sebagai pengendali seluruh aktivitas sel adalah …', 'Mitokondria', 'Ribosom', 'Nukleus', 'Lisosom', 'C', NULL),
(2, 1, 'dasar', 5, 'Perbedaan utama antara sel prokariotik dan sel eukariotik terletak pada …', 'Ukuran sel', 'Keberadaan dinding sel', 'Keberadaan inti sel sejati', 'Jenis materi genetik', 'C', NULL),
(3, 1, 'dasar', 5, 'Organel yang hanya dimiliki oleh sel tumbuhan dan berfungsi dalam fotosintesis adalah …', 'Mitokondria', 'Kloroplas', 'Vakuola', 'Badan Golgi', 'B', NULL),
(4, 1, 'menengah', 10, 'Suatu sel memiliki banyak mitokondria. Aktivitas utama yang kemungkinan besar dilakukan sel tersebut adalah …', 'Sintesis protein', 'Pembelahan sel', 'Respirasi sel dan produksi energi', 'Penyimpanan cadangan makanan', 'C', NULL),
(5, 1, 'menengah', 10, 'Jika kloroplas pada sel tumbuhan tidak berfungsi dengan baik, dampak langsung yang paling mungkin terjadi adalah …', 'Terganggunya respirasi sel', 'Terhambatnya sintesis protein', 'Menurunnya kemampuan fotosintesis', 'Rusaknya sistem transport zat', 'C', NULL),
(6, 1, 'menengah', 10, 'Perhatikan pernyataan berikut: \r\n1. Memiliki dinding sel 2. Tidak memiliki nukleus 3. Memiliki ribosom 4. Memiliki mitokondria. \r\nCiri khas sel prokariotik ditunjukkan oleh nomor …', '1, 3, dan 4', '1, 2, dan 3', '2 dan 4', '3 dan 4', 'B', NULL),
(7, 1, 'menengah', 10, 'Mengapa sel hewan tidak memiliki kloroplas?', 'Karena sel hewan melakukan respirasi', 'Karena sel hewan tidak memerlukan energi', 'Karena sel hewan tidak melakukan fotosintesis', 'Karena kloroplas hanya berfungsi pada sel prokariotik', 'C', NULL),
(8, 1, 'lanjut', 15, 'Seorang siswa menyatakan bahwa \"semua sel yang memiliki dinding sel adalah sel tumbuhan\". Evaluasi pernyataan tersebut!', 'Benar, karena hanya sel tumbuhan yang berdinding sel', 'Salah, karena sel hewan juga memiliki dinding sel', 'Salah, karena sel bakteri juga memiliki dinding sel', 'Benar, karena dinding sel hanya dimiliki organisme eukariotik', 'C', NULL),
(9, 1, 'lanjut', 15, 'Jika kamu diminta merancang sel buatan yang mampu menghasilkan energi dalam jumlah besar, organel yang harus diprioritaskan jumlahnya adalah …', 'Ribosom', 'Kloroplas', 'Mitokondria', 'Badan Golgi', 'C', NULL),
(10, 1, 'lanjut', 15, 'Seorang peneliti menemukan sel dengan ciri: Tidak memiliki membran inti, Memiliki ribosom, Ukurannya relatif kecil. Berdasarkan ciri tersebut, sel tersebut paling tepat diklasifikasikan sebagai …', 'Sel hewan', 'Sel tumbuhan', 'Sel eukariotik', 'Sel prokariotik', 'D', NULL),
(11, 2, 'dasar', 5, 'Organel yang berperan sebagai pusat pengendali aktivitas sel karena menyimpan materi genetik adalah …', 'Mitokondria', 'Nukleus', 'Ribosom', 'Lisosom', 'B', NULL),
(12, 2, 'dasar', 5, 'Organel yang berfungsi sebagai tempat sintesis protein adalah …', 'Ribosom', 'Badan Golgi', 'Lisosom', 'Mitokondria', 'A', NULL),
(13, 2, 'dasar', 5, 'Organel yang berperan dalam pencernaan intrasel dan daur ulang komponen sel yang rusak adalah …', 'Retikulum endoplasma', 'Badan Golgi', 'Lisosom', 'Mitokondria', 'C', NULL),
(14, 2, 'menengah', 10, 'Sel yang aktif menghasilkan energi dalam jumlah besar umumnya memiliki …', 'Banyak ribosom', 'Banyak lisosom', 'Banyak mitokondria', 'Banyak badan Golgi', 'C', NULL),
(15, 2, 'menengah', 10, 'Protein yang telah disintesis oleh ribosom akan dimodifikasi dan dikemas oleh …', 'Retikulum endoplasma kasar', 'Lisosom', 'Badan Golgi', 'Mitokondria', 'C', NULL),
(16, 2, 'menengah', 10, 'Perhatikan pernyataan berikut: 1. Memiliki membran ganda 2. Mengandung DNA sendiri 3. Berfungsi sebagai tempat respirasi sel 4. Terlibat langsung dalam sintesis protein. Ciri yang tepat untuk mitokondria ditunjukkan oleh nomor …', '1, 2, dan 3', '1, 3, dan 4', '2 dan 4', '3 dan 4', 'A', NULL),
(17, 2, 'menengah', 10, 'Apa akibat yang paling mungkin terjadi jika fungsi badan Golgi pada sel terganggu?', 'Proses respirasi sel terhambat', 'Protein tidak dapat dimodifikasi dan didistribusikan', 'Sintesis protein tidak terjadi', 'Pencernaan intrasel berhenti', 'B', NULL),
(18, 2, 'lanjut', 15, 'Seorang siswa berpendapat bahwa \"ribosom hanya terdapat pada sel eukariotik\". Pendapat tersebut …', 'A. Benar, karena ribosom terikat membran', 'B. Benar, karena sel prokariotik sederhana', 'C. Salah, karena ribosom juga terdapat pada sel prokariotik', 'D. Salah, karena ribosom hanya terdapat pada sel tumbuhan', 'C', NULL),
(19, 2, 'lanjut', 15, 'Jika suatu sel kehilangan hampir seluruh lisosomnya, fungsi yang paling terdampak adalah …', 'A. Sintesis energi', 'B. Modifikasi protein', 'C. Pencernaan dan daur ulang komponen sel', 'D. Transport zat antarsel', 'C', NULL),
(20, 2, 'lanjut', 15, 'Seorang peneliti menemukan organel bermembran ganda yang memiliki lipatan ke dalam (krista) dan berfungsi menghasilkan ATP. Organel tersebut adalah …', 'A. Nukleus', 'B. Mitokondria', 'C. Retikulum endoplasma', 'D. Badan Golgi', 'B', NULL),
(21, 3, 'dasar', 5, 'Perpindahan zat dari daerah berkonsentrasi tinggi ke daerah berkonsentrasi rendah tanpa menggunakan energi disebut …', 'A. Osmosis', 'B. Difusi', 'C. Transpor aktif', 'D. Endositosis', 'B', NULL),
(22, 3, 'dasar', 5, 'Peristiwa perpindahan air melalui membran semipermeabel disebut …', 'A. Difusi', 'B. Transpor aktif', 'C. Osmosis', 'D. Eksositosis', 'C', NULL),
(23, 3, 'dasar', 5, 'Proses perpindahan molekul yang memerlukan energi (ATP) disebut …', 'A. Difusi', 'B. Osmosis', 'C. Transpor pasif', 'D. Transpor aktif', 'D', NULL),
(24, 3, 'menengah', 10, 'Sel akar tumbuhan menyerap ion mineral dari tanah walaupun konsentrasi ion di dalam sel lebih tinggi dibandingkan tanah. Proses yang terjadi adalah …', 'A. Difusi', 'B. Osmosis', 'C. Transpor aktif', 'D. Difusi terfasilitasi', 'C', NULL),
(25, 3, 'menengah', 10, 'Jika sel darah merah dimasukkan ke dalam larutan hipotonik, maka yang akan terjadi adalah …', 'A. Sel mengerut', 'B. Sel tetap', 'C. Sel pecah (hemolisis)', 'D. Sel mengalami plasmolisis', 'C', NULL),
(26, 3, 'menengah', 10, 'Perhatikan pernyataan berikut: 1. Tidak memerlukan energi 2. Mengikuti gradien konsentrasi 3. Memerlukan protein pembawa 4. Berlangsung melawan gradien konsentrasi. Ciri khas transpor aktif ditunjukkan oleh nomor …', 'A. 1 dan 2', 'B. 2 dan 3', 'C. 3 dan 4', 'D. 1 dan 4', 'C', NULL),
(27, 3, 'menengah', 10, 'Mengapa membran sel bersifat selektif permeabel?', 'A. Agar semua zat dapat keluar-masuk dengan bebas', 'B. Agar sel tidak memerlukan energi', 'C. Agar sel dapat mengatur keluar-masuknya zat tertentu', 'D. Agar sel selalu berada dalam keadaan seimbang', 'C', NULL),
(28, 3, 'lanjut', 15, 'Seorang siswa menyatakan bahwa \"osmosis dapat terjadi tanpa membran sel\". Evaluasi pernyataan tersebut!', 'A. Benar, karena osmosis hanya dipengaruhi air', 'B. Benar, karena membran tidak berpengaruh', 'C. Salah, karena osmosis memerlukan membran semipermeabel', 'D. Salah, karena osmosis hanya terjadi pada sel tumbuhan', 'C', NULL),
(29, 3, 'lanjut', 15, 'Jika suatu sel tidak mampu melakukan transpor aktif, dampak yang paling mungkin terjadi adalah …', 'A. Sel tidak dapat melakukan difusi', 'B. Sel tidak dapat mempertahankan keseimbangan ion', 'C. Sel tidak dapat menyerap air', 'D. Sel tidak dapat melakukan endositosis', 'B', NULL),
(30, 3, 'lanjut', 15, 'Suatu sel memasukkan partikel berukuran besar dengan cara membentuk lekukan membran hingga membungkus partikel tersebut. Proses tersebut disebut …', 'A. Difusi', 'B. Osmosis', 'C. Eksositosis', 'D. Endositosis', 'D', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `zpd_test_session`
--

CREATE TABLE `zpd_test_session` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `start_time` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `zpd_test_session`
--

INSERT INTO `zpd_test_session` (`id`, `user_id`, `module_id`, `start_time`, `created_at`, `updated_at`) VALUES
(9, 5, 2, 1788453924, '2026-09-03 16:45:24', '2026-09-03 16:45:24');

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
-- Indeks untuk tabel `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `siswa_id` (`siswa_id`),
  ADD KEY `guru_id` (`guru_id`);

--
-- Indeks untuk tabel `post_test_hasil`
--
ALTER TABLE `post_test_hasil`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengguna_id` (`pengguna_id`),
  ADD KEY `modul_id` (`modul_id`);

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
-- Indeks untuk tabel `vark_test_session`
--
ALTER TABLE `vark_test_session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- Indeks untuk tabel `zpd_test_session`
--
ALTER TABLE `zpd_test_session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `module_id` (`module_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `materi_adaptif`
--
ALTER TABLE `materi_adaptif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT untuk tabel `modul`
--
ALTER TABLE `modul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `post_test_hasil`
--
ALTER TABLE `post_test_hasil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `vark_hasil`
--
ALTER TABLE `vark_hasil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `vark_soal`
--
ALTER TABLE `vark_soal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `vark_test_session`
--
ALTER TABLE `vark_test_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `zpd_hasil`
--
ALTER TABLE `zpd_hasil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `zpd_soal`
--
ALTER TABLE `zpd_soal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT untuk tabel `zpd_test_session`
--
ALTER TABLE `zpd_test_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `materi_adaptif`
--
ALTER TABLE `materi_adaptif`
  ADD CONSTRAINT `materi_adaptif_ibfk_1` FOREIGN KEY (`modul_id`) REFERENCES `modul` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pesan`
--
ALTER TABLE `pesan`
  ADD CONSTRAINT `pesan_ibfk_1` FOREIGN KEY (`siswa_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pesan_ibfk_2` FOREIGN KEY (`guru_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `post_test_hasil`
--
ALTER TABLE `post_test_hasil`
  ADD CONSTRAINT `post_test_hasil_ibfk_1` FOREIGN KEY (`pengguna_id`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `post_test_hasil_ibfk_2` FOREIGN KEY (`modul_id`) REFERENCES `modul` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
