-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Jun 2025 pada 13.34
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
-- Database: `reusemart_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `alamat`
--

CREATE TABLE `alamat` (
  `id_alamat` int(11) NOT NULL,
  `id_pembeli` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `nama_jalan` varchar(255) NOT NULL,
  `kode_pos` int(11) NOT NULL,
  `kecamatan` varchar(255) NOT NULL,
  `kelurahan` varchar(255) NOT NULL,
  `status_default` varchar(255) NOT NULL,
  `kabupaten` varchar(255) NOT NULL,
  `deskripsi_alamat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alamat`
--

INSERT INTO `alamat` (`id_alamat`, `id_pembeli`, `id`, `nama_jalan`, `kode_pos`, `kecamatan`, `kelurahan`, `status_default`, `kabupaten`, `deskripsi_alamat`) VALUES
(1, 1, 'ALT01', 'Jalan Kaliurang KM 7', 55281, 'Ngaglik', 'Sinduadi', 'Rumah', 'Kabupaten Sleman', 'Perumahan Griya Yogyakarta Blok B1'),
(2, 2, 'ALT02', 'Jalan Magelang KM 5', 55284, 'Mlati', 'Sendangadi', 'Rumah', 'Kabupaten Sleman', 'Dekat Indogrosir Jogja, blok A2'),
(3, 3, 'ALT03', 'Jalan Wates KM 9', 55652, 'Lendah', 'Ngentakrejo', 'Rumah', 'Kabupaten Kulon Progo', 'Rumah di sebelah Kantor Pos Lendah'),
(4, 4, 'ALT04', 'Jalan Malioboro', 55213, 'Gedongtengen', 'Sosromenduran', 'Gedung', 'Kota Yogyakarta', 'Gedung Ruko Malioboro Plaza Lantai 2'),
(5, 5, 'ALT05', 'Jalan C. Simanjuntak', 55223, 'Gondokusuman', 'Terban', 'Rumah', 'Kota Yogyakarta', 'Belakang kampus UGM Fakultas Teknik'),
(6, 6, 'ALT06', 'Jalan Parangtritis KM 7', 55712, 'Kretek', 'Tirtomulyo', 'Apartemen', 'Kabupaten Bantul', 'Apartemen Parangtritis Residence No. 10'),
(7, 7, 'ALT07', 'Jalan Baron', 55872, 'Wonosari', 'Kepek', 'Rumah', 'Kabupaten Gunungkidul', 'Perumahan Wonosari Permai blok E5'),
(8, 8, 'ALT08', 'Jalan Gejayan', 55281, 'Depok', 'Condongcatur', 'Ruko', 'Kabupaten Sleman', 'Ruko Gejayan Square No. 8'),
(9, 9, 'ALT09', 'Jalan Wonosari KM 10', 55791, 'Piyungan', 'Srimulyo', 'Rumah', 'Kabupaten Bantul', 'Rumah dekat Pasar Piyungan'),
(10, 10, 'ALT10', 'Jalan Affandi', 55281, 'Depok', 'Caturtunggal', 'Toko', 'Kabupaten Sleman', 'Toko sebelah kampus Sanata Dharma, ruko 3B'),
(35, 11, 'ALT12', 'jl. kematiann hehe', 21344, 'Maguwo', 'sleman', 'Toko', 'Sleman', 'ada'),
(43, 11, 'ALT14', 'Jl.Maguwo raya', 22222, 'Maguwo', 'Maguwo', 'Rumah', 'Sleman', 'Rumah Jo anak dari mamah nya dan papa nya'),
(44, 11, 'ALT15', 'jl. kematian', 21344, 'Maguwo', 'sleman', 'Toko', 'Sleman', 'adada'),
(45, 11, 'ALT16', 'jl. hehe adsa', 21322, 'Tanjung sari', 'kemadang', 'Apartemen', 'gunung kidul', 'ada'),
(46, 13, 'ALT17', 'kemana', 55287, 'Tanjung sari', 'kemadang', 'Rumah', 'gunung kidul', 'ada');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `harga_barang` float NOT NULL,
  `deskripsi_barang` varchar(255) NOT NULL,
  `foto_barang` varchar(255) NOT NULL,
  `status_barang` varchar(255) NOT NULL,
  `rating_barang` float NOT NULL,
  `berat_barang` float NOT NULL,
  `garansi_barang` datetime DEFAULT NULL,
  `masa_penitipan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `id`, `id_kategori`, `nama_barang`, `harga_barang`, `deskripsi_barang`, `foto_barang`, `status_barang`, `rating_barang`, `berat_barang`, `garansi_barang`, `masa_penitipan`) VALUES
(1, 'B01', 1, 'Sony WH-1000XM4 Bluetooth Headphone', 1999000, 'Headphone wireless dengan fitur noise cancelling dan daya tahan baterai hingga 30 jam. Cocok untuk bekerja dan bepergian.', '[\"images/headphone_sony.jpg\", \"images/headphone_sony2.jpg\", \"images/headphone_sony3.jpg\", \"images/headphone_sony4.jpg\"]\n', 'di donasikan', 4, 0.25, '2026-12-31 23:59:59', 30),
(2, 'B02', 2, 'Jaket Denim Pria Levi\'s Original', 420000, 'Jaket denim Levi\'s asli, desain klasik dan bahan tebal, ideal untuk musim hujan.', '[\"images/jaket_denim.jpg\"]', 'laku', 4, 0.9, '2027-05-15 12:00:00', 30),
(3, 'B03', 3, 'Set Panci Masak Anti Lengket 5 pcs', 495000, 'Perlengkapan dapur anti lengket dengan 5 variasi ukuran, lapisan keramik tahan panas.', '[\"images/panci_set.jpg\", \"images/panci_set2.jpg\", \"images/panci_set3.jpg\", \"images/panci_set4.jpg\"]\r\n', 'di donasikan', 4, 3, '2026-06-20 09:30:00', 30),
(4, 'B04', 4, 'Paket Peralatan Sekolah Lengkap', 135000, 'Isi 12 item termasuk alat tulis, tempat pensil, dan buku catatan. Cocok untuk siswa SD/SMP.', '[\"images/alat_tulis.jpg\"]', 'laku', 5, 0.5, '2026-11-10 10:00:00', 30),
(5, 'B05', 5, 'Miniatur Gundam RX-78-2 HG Scale 1/144', 680000, 'Model kit Gundam high-grade cocok untuk kolektor, bisa dirakit dan di-pose.', '[\"images/gundam.jpg\", \"images/gundam2.jpg\", \"images/gundam3.jpg\",\"images/gundam4.jpg\"]', 'laku', 5, 0.9, '2026-08-30 18:00:00', 30),
(6, 'B06', 6, 'Stroller Bayi Lipat 3 Posisi', 1325000, 'Stroller ergonomis dengan fitur lipat cepat, kanopi UV, dan sabuk 5 titik.', '[\"images/stroller.jpg\", \"images/stroller2.jpg\", \"images/stroller3.jpg\",\"images/stroller4.jpg\"]', 'laku', 3, 6.5, '2027-01-25 14:00:00', 30),
(7, 'B07', 7, 'Helm Full Face KYT Galaxy', 540000, 'Helm full face KYT, standar SNI, ventilasi optimal dan visor bening anti-embun.', '[\"images/helm.jpg\", \"images/helm2.jpg\", \"images/helm3.jpg\", \"images/helm4.jpg\"]', 'di donasikan', 4, 1.4, '2027-06-10 16:30:00', 30),
(8, 'B08', 8, 'Set Alat Berkebun Mini 6 in 1', 195000, 'Isi sekop, garpu, sprayer, sarung tangan, gunting tanaman, dan keranjang kecil.', '[\"images/alat_kebun.jpg\"]', 'laku', 4, 2.2, '2025-12-31 20:00:00', 30),
(9, 'B09', 9, 'Mesin Laminating A4 Portable', 570000, 'Mesin laminating ringkas dan cepat, cocok untuk dokumen sekolah dan kantor kecil.', '[\"images/laminator.jpg\", \"images/laminator2.jpg\", \"images/laminator3.jpg\",\"images/laminator4.jpg\"]', 'laku', 5, 3.3, '2026-07-15 11:00:00', 30),
(10, 'B10', 10, 'Paket Skincare Basic untuk Kulit Normal', 320000, 'Terdiri dari facial wash, toner, moisturizer dan sunscreen dengan bahan alami.', '[\"images/skincare.jpg\", \"images/skincare2.jpg\", \"images/skincare3.jpg\", \"images/skincare4.jpg\"]', 'di donasikan', 3, 0.7, '2027-12-10 08:00:00', 30),
(11, 'B11', 10, 'Paket Skincare Basic untuk Kulit Normal', 320000, 'Terdiri dari facial wash, toner, moisturizer dan sunscreen dengan bahan alami.', '[\"images/skincare.jpg\", \"images/skincare2.jpg\", \"images/skincare3.jpg\",\"images/skincare4.jpg\"]', 'tidak laku', 3, 0.7, '2027-12-10 08:00:00', 30),
(12, 'B12', 1, 'Kipas', 320000, 'kipas anti sumuk', '[\"images/kipas.jpg\", \"images/kipas2.jpg\", \"images/kipas3.jpg\",\"images/kipas4.jpg\"]', 'tidak laku', 4, 0.8, '2026-12-01 08:00:00', 30),
(19, 'B13', 7, 'foto driver f1', 10000, 'card foto', '[\"images\\/barang_1748520577_0_68384e8143742.jpeg\",\"images\\/barang_1748520577_1_68384e8143eb8.jpeg\",\"images\\/barang_1748520577_2_68384e81444c6.jpeg\"]', 'Sudah diambil', 0, 0.2, NULL, 30),
(20, 'B14', 5, 'test', 1000, 'test', '[\"images\\/barang_1748606906_0_68399fba05d15.png\"]', 'laku', 0, 0.2, NULL, 30),
(21, 'B15', 2, 'sepatu', 500000, 'sepatu', '[\"images\\/barang_1748779989_0_683c43d537812.png\"]', 'laku', 0, 0.5, NULL, 30),
(22, 'B16', 2, 'panci', 200000, 'ada', '[\"images\\/barang_1748790750_0_683c6ddec15f2.jpeg\"]', 'laku', 0, 0.5, NULL, 30),
(23, 'B17', 7, 'hehe', 30000, 'asadfsdf', '[\"images\\/barang_1748791605_0_683c7135073b9.jpeg\",\"images\\/barang_1748791605_1_683c713507838.jpeg\"]', 'laku', 0, 0.5, NULL, 30),
(24, 'B18', 2, 'nike', 400000, 'sepatu', '[\"images\\/barang_1748868110_0_683d9c0e87422.png\"]', 'laku', 0, 0.8, NULL, 30),
(25, 'B19', 5, 'ada', 120000, 'ada', '[\"images\\/barang_1748868506_0_683d9d9aee4b2.png\"]', 'laku', 0, 0.8, NULL, 30),
(26, 'B20', 3, 'io', 800000, 'oio', '[\"images\\/barang_1748868538_0_683d9dba0abcf.png\"]', 'laku', 0, 0.4, NULL, 30),
(27, 'B21', 1, 'io', 800000, 'ada', '[\"images\\/barang_1748908528_0_683e39f067c44.jpeg\"]', 'Sudah diambil', 0, 0.4, NULL, 30),
(28, 'B22', 4, 'pencil', 100000, 'ada', '[\"images\\/barang_1748913502_0_683e4d5ee4eee.jpeg\"]', 'laku', 0, 0.3, NULL, 30),
(29, 'B23', 1, 'Surya', 900000, 'rokok', '[\"images\\/barang_1748915571_0_683e55731b261.jpg\"]', 'laku', 0, 0.6, NULL, 30),
(30, 'B24', 7, 'Kenalpot', 700000, 'bom', '[\"images\\/barang_1748915615_0_683e559f15d9d.png\",\"images\\/barang_1748915615_1_683e559f16457.png\"]', 'laku', 0, 0.3, NULL, 30),
(31, 'B25', 1, 'Baju', 400000, 'jadi', '[\"images\\/barang_1748915772_0_683e563ce4155.png\",\"images\\/barang_1748915772_1_683e563ce4906.jpg\",\"images\\/barang_1748915772_2_683e563ce5251.jpg\"]', 'Sudah diambil', 0, 0.4, NULL, 30),
(32, 'B26', 3, 'Hyundai', 70000, 'mobil', '[\"images\\/barang_1748915806_0_683e565e7dcfd.jpeg\",\"images\\/barang_1748915806_1_683e565e7e3e9.jpeg\",\"images\\/barang_1748915806_2_683e565e7e9e7.jpeg\"]', 'laku', 0, 0.7, NULL, 30),
(33, 'B27', 3, 'Honda', 300000, 'Honda', '[\"images\\/barang_1748922408_0_683e70284c5a8.jpeg\",\"images\\/barang_1748922408_1_683e70284ca16.jpeg\",\"images\\/barang_1748922408_2_683e70284cdce.jpeg\"]', 'laku', 0, 0.8, NULL, 30),
(34, 'B28', 1, 'Masako', 400000, 'Masako', '[\"images\\/barang_1748922455_0_683e70574c5f6.png\",\"images\\/barang_1748922455_1_683e70574c8e1.png\",\"images\\/barang_1748922455_2_683e70574cb2d.png\",\"images\\/barang_1748922455_3_683e70574cd32.jpg\"]', 'Sudah diDonasikan', 0, 0.6, NULL, 30),
(35, 'B29', 4, 'ada', 9000000, 'ada', '[\"images\\/barang_1748924173_0_683e770d8db4f.PNG\",\"images\\/barang_1748924173_1_683e770d8de89.png\"]', 'di donasikan', 0, 0.8, NULL, 30),
(36, 'B30', 4, 'aad', 9000, 'ada', '[\"images\\/barang_1748924300_0_683e778c76acb.jpeg\",\"images\\/barang_1748924300_1_683e778c7700a.jpeg\"]', 'di donasikan', 0, 0.7, NULL, 30),
(37, 'B31', 5, 'koaidjs', 9000, '80000', '[\"images\\/barang_1748924324_0_683e77a4041d1.png\"]', 'laku', 0, 0.8, NULL, 30),
(38, 'B32', 10, 'klsa', 60000, 'ajskdl', '[\"images\\/barang_1748924461_0_683e782d000c9.png\"]', 'di donasikan', 0, 0.8, NULL, 30),
(39, 'B33', 2, 'loh', 700000, 'loh', '[\"images\\/barang_1749042427_0_684044fb41a12.png\",\"images\\/barang_1749042427_1_684044fb42103.png\"]', 'di donasikan', 0, 0.8, NULL, 30),
(40, 'B34', 5, 'ad', 1300000, 'asdasd', '[\"images\\/barang_1749045129_0_68404f893e1f3.PNG\",\"images\\/barang_1749045129_1_68404f893e630.png\",\"images\\/barang_1749045129_2_68404f893e8ff.png\",\"images\\/barang_1749045129_3_68404f893ec3a.png\",\"images\\/barang_1749045129_4_68404f893eef1.jpg\"]', 'di donasikan', 0, 0.7, NULL, 30),
(41, 'B35', 10, 'asdadsa', 8000000, 'adadadads', '[\"images\\/barang_1749045168_0_68404fb02955b.jpg\",\"images\\/barang_1749045168_1_68404fb0298b2.jpeg\",\"images\\/barang_1749045168_2_68404fb029bb0.jpeg\"]', 'laku', 0, 0.8, NULL, 30),
(42, 'B36', 6, 'fef', 1400000, 'asffc', '[\"images\\/barang_1749045432_0_684050b8401ab.png\",\"images\\/barang_1749045432_1_684050b840802.png\",\"images\\/barang_1749045432_2_684050b840c53.jpg\"]', 'laku', 0, 0.8, NULL, 30),
(43, 'B37', 10, 'adacax', 60000, 'asrfdq', '[\"images\\/barang_1749045458_0_684050d2886e7.png\",\"images\\/barang_1749045458_1_684050d288ab9.jpg\",\"images\\/barang_1749045458_2_684050d288e9b.jpeg\"]', 'laku', 0, 0.8, NULL, 30),
(44, 'B38', 3, 'ghj', 300000, 'dgafs', '[\"images\\/barang_1749049498_0_6840609a16599.jpeg\",\"images\\/barang_1749049498_1_6840609a16a67.jpeg\"]', 'avaliable', 0, 0.6, NULL, 30),
(45, 'B39', 4, 'asdads', 800000, 'adadas', '[\"images\\/barang_1749129950_0_68419adea6dd0.jpeg\",\"images\\/barang_1749129950_1_68419adea727c.jpeg\",\"images\\/barang_1749129950_2_68419adea75fe.jpeg\",\"images\\/barang_1749129950_3_68419adea7949.jpeg\"]', 'tidak laku', 0, 0.4, NULL, 30),
(46, 'B40', 1, 'botol', 300000, 'botol', '[\"images\\/barang_1749129984_0_68419b0024710.PNG\",\"images\\/barang_1749129984_1_68419b0024a4c.png\",\"images\\/barang_1749129984_2_68419b0024d0d.png\"]', 'Barang akan diambil kembali', 0, 0.6, NULL, 30),
(47, 'B41', 1, 'panci', 540000, 'panci', '[\"images\\/barang_1749130021_0_68419b25abcc4.jpeg\",\"images\\/barang_1749130021_1_68419b25ac0ad.jpeg\"]', 'tidak laku', 0, 0.8, '2025-06-17 22:28:39', 30),
(48, 'B42', 3, 'hehe', 700000, 'hehe', '[\"images\\/barang_1749130057_0_68419b49b857a.jpg\",\"images\\/barang_1749130057_1_68419b49b8b75.png\",\"images\\/barang_1749130057_2_68419b49b8f68.jpg\"]', 'tidak laku', 0, 0.7, NULL, 30),
(49, 'B43', 1, 'panci', 540000, 'panci', '[\"images\\/barang_1749130021_0_68419b25abcc4.jpeg\",\"images\\/barang_1749130021_1_68419b25ac0ad.jpeg\"]', 'tidak laku', 0, 0.8, '2025-06-17 22:28:39', 30),
(50, 'B44', 3, 'hehe', 700000, 'hehe', '[\"images\\/barang_1749130057_0_68419b49b857a.jpg\",\"images\\/barang_1749130057_1_68419b49b8b75.png\",\"images\\/barang_1749130057_2_68419b49b8f68.jpg\"]', 'laku', 0, 0.7, NULL, 30),
(51, 'B45', 4, 'asdads', 800000, 'adadas', '[\"images\\/barang_1749129950_0_68419adea6dd0.jpeg\",\"images\\/barang_1749129950_1_68419adea727c.jpeg\",\"images\\/barang_1749129950_2_68419adea75fe.jpeg\",\"images\\/barang_1749129950_3_68419adea7949.jpeg\"]', 'laku', 0, 0.4, NULL, 30),
(52, 'B46', 7, 'adas', 20000, 'hai', '[\"images\\/barang_1750938785_0_685d34a1a2482.PNG\",\"images\\/barang_1750938785_1_685d34a1a2a92.png\",\"images\\/barang_1750938785_2_685d34a1a2e56.png\"]', 'tidak laku', 0, 0.8, NULL, 30);

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `claimmerchandise`
--

CREATE TABLE `claimmerchandise` (
  `id_transaksi_claim_merchandise` int(11) NOT NULL,
  `id_pembeli` int(11) NOT NULL,
  `id_merchandise` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `tanggal_claim` datetime NOT NULL,
  `tanggal_pengambilan` datetime DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `claimmerchandise`
--

INSERT INTO `claimmerchandise` (`id_transaksi_claim_merchandise`, `id_pembeli`, `id_merchandise`, `id`, `tanggal_claim`, `tanggal_pengambilan`, `status`) VALUES
(1, 1, 1, 'CM01', '2025-06-01 08:00:00', '2025-06-09 09:22:51', 'sudah_diambil'),
(2, 2, 2, 'CM02', '2025-05-02 09:30:00', '2025-05-03 11:30:00', NULL),
(3, 3, 3, 'CM03', '2025-08-03 10:15:00', '2025-08-04 12:00:00', NULL),
(4, 4, 4, 'CM04', '2025-12-04 11:00:00', '2025-12-05 13:00:00', NULL),
(5, 5, 5, 'CM05', '2025-04-05 12:45:00', '2025-04-06 14:30:00', NULL),
(6, 6, 6, 'CM06', '2025-02-06 13:30:00', '2025-02-07 15:00:00', NULL),
(7, 7, 7, 'CM07', '2025-01-07 14:00:00', '2025-01-08 16:00:00', NULL),
(8, 8, 8, 'CM08', '2025-11-08 15:15:00', '2025-11-09 17:00:00', NULL),
(9, 9, 9, 'CM09', '2025-03-09 16:00:00', '2025-03-10 18:30:00', NULL),
(21, 11, 5, 'CM10', '2025-06-09 11:42:01', '2025-06-09 11:44:22', 'sudah_diambil'),
(22, 11, 9, 'CM11', '2025-06-10 01:26:17', NULL, 'belum_diambil'),
(23, 11, 1, 'CM12', '2025-06-10 02:58:22', NULL, 'belum_diambil'),
(24, 11, 1, 'CM13', '2025-06-10 03:25:51', NULL, 'belum_diambil');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detailtransaksipenitipan`
--

CREATE TABLE `detailtransaksipenitipan` (
  `id_detail_transaksi_penitipan` int(11) NOT NULL,
  `id_transaksi_penitipan` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `status_perpanjangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detailtransaksipenitipan`
--

INSERT INTO `detailtransaksipenitipan` (`id_detail_transaksi_penitipan`, `id_transaksi_penitipan`, `id_barang`, `status_perpanjangan`) VALUES
(2, 2, 2, NULL),
(3, 3, 3, NULL),
(4, 4, 4, NULL),
(5, 5, 5, NULL),
(6, 6, 6, NULL),
(7, 7, 7, NULL),
(8, 8, 8, NULL),
(9, 9, 9, NULL),
(10, 10, 11, '2'),
(11, 11, 12, '1'),
(14, 14, 19, NULL),
(15, 15, 20, NULL),
(16, 16, 21, NULL),
(17, 17, 22, NULL),
(18, 18, 23, NULL),
(19, 19, 24, NULL),
(20, 20, 25, NULL),
(21, 21, 26, NULL),
(22, 22, 27, NULL),
(23, 23, 28, NULL),
(24, 24, 29, NULL),
(25, 25, 30, NULL),
(26, 26, 31, '0'),
(27, 27, 32, NULL),
(28, 28, 33, NULL),
(29, 29, 34, NULL),
(30, 30, 35, NULL),
(31, 31, 36, NULL),
(32, 32, 37, NULL),
(33, 33, 38, NULL),
(34, 34, 39, NULL),
(35, 35, 40, NULL),
(36, 36, 41, NULL),
(37, 37, 42, NULL),
(38, 38, 43, NULL),
(39, 39, 44, NULL),
(40, 40, 45, NULL),
(41, 41, 46, NULL),
(42, 42, 47, NULL),
(43, 43, 48, NULL),
(44, 44, 49, NULL),
(45, 45, 50, NULL),
(46, 46, 51, NULL),
(47, 47, 52, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `detailtransaksipenjualan`
--

CREATE TABLE `detailtransaksipenjualan` (
  `id_detail_transaksi_penjualan` int(11) NOT NULL,
  `id_transaksi_penjualan` int(11) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `total_harga` float NOT NULL,
  `rating_untuk_penitip` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detailtransaksipenjualan`
--

INSERT INTO `detailtransaksipenjualan` (`id_detail_transaksi_penjualan`, `id_transaksi_penjualan`, `id_barang`, `total_harga`, `rating_untuk_penitip`) VALUES
(1, 1, 1, 750000, NULL),
(2, 2, 2, 350000, NULL),
(3, 3, 3, 450000, NULL),
(4, 4, 4, 120000, NULL),
(5, 5, 5, 650000, NULL),
(6, 6, 6, 1250000, NULL),
(7, 7, 7, 520000, NULL),
(8, 8, 8, 180000, NULL),
(9, 9, 9, 550000, NULL),
(10, 10, 10, 300000, NULL),
(11, 15, 12, 20000000, NULL),
(21, 18, 12, 320000, NULL),
(22, 19, 20, 1000, NULL),
(23, 20, 21, 500000, NULL),
(24, 21, 22, 200000, NULL),
(25, 22, 23, 30000, NULL),
(27, 24, 5, 770000, NULL),
(28, 25, 24, 390000, NULL),
(29, 26, 25, 120000, NULL),
(30, 27, 26, 800000, NULL),
(31, 28, 11, 320000, NULL),
(32, 29, 28, 100000, NULL),
(33, 30, 9, 670000, NULL),
(37, 32, 6, 1305370, NULL),
(38, 32, 30, 689630, NULL),
(39, 33, 29, 1000000, NULL),
(40, 34, 3, 495000, 5),
(41, 35, 32, 70000, NULL),
(42, 36, 33, 300000, 4),
(43, 37, 34, 400000, 3),
(44, 38, 35, 9000000, NULL),
(45, 39, 36, 9000, NULL),
(46, 40, 37, 9000, NULL),
(47, 41, 39, 700000, NULL),
(48, 42, 40, 1300000, NULL),
(49, 42, 38, 60000, NULL),
(50, 43, 42, 1400000, NULL),
(51, 43, 43, 60000, NULL),
(52, 44, 41, 8000000, NULL),
(53, 45, 44, 400000, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `diskusi`
--

CREATE TABLE `diskusi` (
  `id_forum_diskusi` int(11) NOT NULL,
  `id_pegawai` int(11) DEFAULT NULL,
  `id_pembeli` int(11) NOT NULL,
  `pesan` varchar(255) NOT NULL,
  `tanggal_diskusi` datetime NOT NULL,
  `id_barang` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `diskusi`
--

INSERT INTO `diskusi` (`id_forum_diskusi`, `id_pegawai`, `id_pembeli`, `pesan`, `tanggal_diskusi`, `id_barang`) VALUES
(13, NULL, 17, 'wah barangnya bagus', '2025-05-18 12:53:51', 1),
(14, NULL, 17, 'wah barangnya bagus', '2025-05-18 12:54:05', 1),
(15, NULL, 17, 'barang bagus nih', '2025-05-18 12:54:15', 1),
(17, NULL, 17, 'bagus ya', '2025-05-18 12:57:05', 1),
(18, NULL, 18, 'apa nih', '2025-05-18 12:58:41', 1),
(19, NULL, 18, 'wah gacor nih', '2025-05-18 13:01:14', 1),
(20, NULL, 17, 'wah bagus nih', '2025-05-18 13:02:31', 1),
(21, NULL, 17, 'tes', '2025-05-18 13:12:42', 1),
(23, NULL, 17, 'ddd', '2025-05-18 13:22:03', 1),
(24, NULL, 17, 'panci mantap', '2025-05-18 13:22:30', 3),
(25, NULL, 17, 'dadad', '2025-05-18 13:31:23', 1),
(26, NULL, 17, 'dadad', '2025-05-18 13:31:25', 1),
(27, NULL, 17, 'dada', '2025-05-18 13:31:33', 1),
(28, NULL, 17, 'dada', '2025-05-18 13:32:28', 1),
(29, NULL, 11, 'hai', '2025-05-18 16:11:53', 3),
(30, NULL, 13, 'hai juga yamal', '2025-05-18 16:19:27', 3),
(31, NULL, 13, 'yamal ganteng banget', '2025-05-19 09:51:02', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `donasi`
--

CREATE TABLE `donasi` (
  `id_barang` int(11) NOT NULL,
  `id_request` int(11) NOT NULL,
  `tanggal_donasi` datetime NOT NULL,
  `nama_penerima` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `donasi`
--

INSERT INTO `donasi` (`id_barang`, `id_request`, `tanggal_donasi`, `nama_penerima`) VALUES
(1, 18, '2025-05-21 00:00:00', 'cole palmer'),
(39, 1, '2025-06-11 00:00:00', 'anjay'),
(34, 3, '2025-06-14 00:00:00', 'alex');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jabatan`
--

CREATE TABLE `jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `nama_jabatan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jabatan`
--

INSERT INTO `jabatan` (`id_jabatan`, `id`, `nama_jabatan`) VALUES
(1, 'J001', 'Admin'),
(2, 'J002', 'Owner'),
(3, 'J003', 'Customer Service'),
(4, 'J004', 'Gudang'),
(5, 'J005', 'Kurir'),
(6, 'J006', 'Hunter');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategoribarang`
--

CREATE TABLE `kategoribarang` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `nama_sub_kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategoribarang`
--

INSERT INTO `kategoribarang` (`id_kategori`, `nama_kategori`, `nama_sub_kategori`) VALUES
(1, 'Elektronik & Gadget', 'Smartphone'),
(2, 'Pakaian & Aksesoris', 'Pakaian Pria'),
(3, 'Perabotan Rumah Tangga', 'Peralatan Dapur'),
(4, 'Buku, Alat Tulis, & Peralatan Sekolah', 'Buku Pelajaran'),
(5, 'Hobi, Mainan, & Koleksi', 'Action Figure'),
(6, 'Perlengkapan Bayi & Anak', 'Popok Bayi'),
(7, 'Otomotif & Aksesoris', 'Aksesoris Mobil'),
(8, 'Perlengkapan Taman & Outdoor', 'Alat Berkebun'),
(9, 'Peralatan Kantor & Industri', 'Printer Kantor'),
(10, 'Kosmetik & Perawata Diri', 'Skincare');

-- --------------------------------------------------------

--
-- Struktur dari tabel `komisi`
--

CREATE TABLE `komisi` (
  `id_komisi` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `id_transaksi_penjualan` int(11) NOT NULL,
  `id_penitip` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `komisi_penitip` float NOT NULL,
  `komisi_reusemart` float NOT NULL,
  `komisi_hunter` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `komisi`
--

INSERT INTO `komisi` (`id_komisi`, `id`, `id_transaksi_penjualan`, `id_penitip`, `id_pegawai`, `komisi_penitip`, `komisi_reusemart`, `komisi_hunter`) VALUES
(2, 'KM02', 2, 2, 6, 150000, 75000, 30000),
(3, 'KM03', 3, 3, 6, 120000, 60000, 24000),
(4, 'KM04', 4, 4, 6, 130000, 65000, 26000),
(5, 'KM05', 5, 5, 6, 110000, 55000, 22000),
(6, 'KM06', 6, 6, 6, 140000, 70000, 28000),
(7, 'KM07', 7, 7, 6, 160000, 80000, 32000),
(8, 'KM08', 8, 8, 6, 170000, 85000, 34000),
(9, 'KM09', 9, 9, 6, 180000, 90000, 36000),
(11, 'KMS0010', 28, 13, 6, 240000, 64000, 16000),
(12, 'KMS0011', 29, 16, 8, 77000, 20000, 5000),
(13, 'KMS0012', 32, 6, 4, 1005130, 261074, 65268.5),
(14, 'KMS0013', 32, 16, 6, 531015, 137926, 34481),
(15, 'KMS0014', 36, 19, 8, 231000, 60000, 15000),
(16, 'KMS0015', 40, 3, 6, 6930, 1800, 450),
(20, 'KMS0020', 2, 2, 6, 150000, 75000, 30);

-- --------------------------------------------------------

--
-- Struktur dari tabel `merchandise`
--

CREATE TABLE `merchandise` (
  `id_merchandise` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `nama_merchandise` varchar(255) NOT NULL,
  `harga_merchandise` int(11) NOT NULL,
  `stok_merchandise` int(11) NOT NULL,
  `foto_merchandise` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `merchandise`
--

INSERT INTO `merchandise` (`id_merchandise`, `id_pegawai`, `id`, `nama_merchandise`, `harga_merchandise`, `stok_merchandise`, `foto_merchandise`) VALUES
(1, 3, 'M01', 'T-shirt Reusemart', 500, 498, 'images/tshirt_reusemart.jpg'),
(2, 3, 'M02', 'Tas Travel Reusemart', 1000, 1000, 'images/tas_reusemart.jpg'),
(3, 3, 'M03', 'Tumblr Reusemart', 500, 500, 'images/botol_air_reusemart.jpg'),
(4, 3, 'M04', 'Payung Reusemart', 1000, 1000, 'images/payung_reusemart.jpg'),
(5, 3, 'M05', 'Stiker Reusemart', 100, 99, 'images/stiker_reusemart.jpg'),
(6, 3, 'M06', 'Mug Reusemart', 250, 250, 'images/mug_reusemart.jpg'),
(7, 3, 'M07', 'Topi Reusemart', 250, 250, 'images/topi_reusemart.jpg'),
(8, 3, 'M08', 'Jam Dinding Reusemart', 500, 500, 'images/jam_reusemart.jpg'),
(9, 3, 'M09', 'Ballpoin Reusemart', 100, 99, 'images/ballpoin_reusemart.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(5, '2025_04_30_120726_create_pegawais_table', 1),
(6, '2025_04_30_121050_create_jabatans_table', 1),
(10, '2025_04_30_120436_create_personal_access_tokens_table', 2),
(18, '0001_01_01_000000_create_users_table', 3),
(19, '0001_01_01_000001_create_cache_table', 3),
(20, '0001_01_01_000002_create_jobs_table', 3),
(21, '2025_05_08_174851_create_password_forgot_table', 3),
(22, '2025_05_08_174905_create_password_forgot_pembeli_table', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `organisasi`
--

CREATE TABLE `organisasi` (
  `id_organisasi` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `nama_organisasi` varchar(255) NOT NULL,
  `alamat_organisasi` varchar(255) NOT NULL,
  `nomor_telepon` varchar(255) NOT NULL,
  `email_organisasi` varchar(255) NOT NULL,
  `password_organisasi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `organisasi`
--

INSERT INTO `organisasi` (`id_organisasi`, `id`, `nama_organisasi`, `alamat_organisasi`, `nomor_telepon`, `email_organisasi`, `password_organisasi`) VALUES
(1, 'OR001', 'Toko Merah Indonesia', 'Jl. Merdeka No. 10, Jakarta', '021-12345678', 'info@toko.id', 'password123'),
(2, 'OR002', 'Panti Jompo Sederhana', 'Jl. Teknologi No. 45, Bandung', '022-87654321', 'contact@Jomposederhana.id', 'securepass456'),
(3, 'OR003', 'Warmindo Eco', 'Jl. Raya No. 12, Surabaya', '023-11223344', 'hello@warmindo.id', 'warmindo@123'),
(4, 'OR004', 'Timezone Indonesia', 'Jl. Pahlawan No. 23, Yogyakarta', '024-44332211', 'support@timezone.id', 'timezoneid@123'),
(5, 'OR005', 'Eco Mart', 'Jl. Alam No. 56, Bali', '025-55667788', 'info@ecomart.com', 'ecomart@123'),
(6, 'OR006', 'Panti Rapih Indonesia', 'Jl. Raya No. 78, Semarang', '026-66778899', 'panti@rapih.id', 'pantirapih!2025'),
(7, 'OR007', 'Bakso Meriam Pak Yadi', 'Jl. Kebangsaan No. 32, Medan', '027-77889900', 'meriam@foods.id', 'meriam@foods123'),
(8, 'OR008', 'PT. Teknologi food', 'Jl. Teknologi No. 15, Makassar', '028-88990011', 'info@techforfood.org', 'tech@food123'),
(9, 'OR009', 'Bakery Ceria', 'Jl. Kebersihan No. 29, Malang', '029-99001122', 'bakery@ceria.org', 'bakeryceria123'),
(10, 'OR010', 'Panti Yatim Berbahagia', 'Jl. Hijau No. 19, Palembang', '030-22334455', 'info@pantibahagia.id', 'bahagia@1988'),
(11, 'OR011', 'hai 11', 'hai', '11111111111', 'hai@gmail.com', '$2y$12$CuCyfJSYmRI9O1Ie2P3F8ujDgGXVe1y12jU2dSc/b8GSFx9xxeQXi'),
(12, 'OR012', 'hai2', 'hai2', '22222222222', 'hai2@gmail.com', '$2y$12$uLSnFerDAsIYVPRYB4vUceyWYgXSvCOABjOWo7Th9cgYO0fG7HR5y'),
(14, 'OR013', 'ytta 123', 'bantul', '07777777777', 'lanamarcel15@gmail.com', '$2y$12$fGe/J997Dps2Xd0wgxLjPu2cEbeIB9HDEILbIUFVWEh6L41ZpSHOi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_forgot`
--

CREATE TABLE `password_forgot` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email_organisasi` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_forgot`
--

INSERT INTO `password_forgot` (`id`, `email_organisasi`, `token`, `created_at`, `updated_at`) VALUES
(1, 'lanamarcel15@gmail.com', 'Zslafc8ocrHogs7RF6eIHsRMh0QkW0x73Nznzz90aci8truNNE8Jk4UpkMfcoN2PS', '2025-05-12 17:33:55', '2025-05-12 17:33:55'),
(2, 'lanamarcel15@gmail.com', '7CnRNxBwVTbNe8g51IeaPTT1cZW1TxwlclgZNApVINXZB5iFu3hZnEvUQsSOLz0A6', '2025-05-12 17:35:55', '2025-05-12 17:35:55'),
(3, 'supersvou2828@gmail.com', 'Sa5rZvJVI0xrWdCn5qSq5hhCKvZhVkfql4xYZYDlvCjfRjLxYJlt1HnevYxQXsDeC', '2025-05-12 17:37:02', '2025-05-12 17:37:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_forgot_pembeli_tabel`
--

CREATE TABLE `password_forgot_pembeli_tabel` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email_pembeli` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pegawai`
--

CREATE TABLE `pegawai` (
  `id_pegawai` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `id_jabatan` int(11) NOT NULL,
  `nama_pegawai` varchar(255) NOT NULL,
  `tanggal_lahir_pegawai` date NOT NULL,
  `nomor_telepon_pegawai` varchar(255) NOT NULL,
  `email_pegawai` varchar(255) NOT NULL,
  `password_pegawai` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pegawai`
--

INSERT INTO `pegawai` (`id_pegawai`, `id`, `id_jabatan`, `nama_pegawai`, `tanggal_lahir_pegawai`, `nomor_telepon_pegawai`, `email_pegawai`, `password_pegawai`) VALUES
(1, 'PG01', 1, 'Andi Pratama 2', '1990-06-15', '081234567890', 'andi.pratama@example.com', 'password123'),
(2, 'PG02', 2, 'Nugi Darmawan', '1985-02-20', '082345678901', 'NugiDmwn@gmail.com', 'nugi12345'),
(3, 'PG03', 3, 'Oseanno', '1992-11-30', '083456789012', 'Osemartondang@gmail.com', 'ose12345'),
(4, 'PG04', 4, 'Jessica', '1988-09-10', '084567890123', 'dewi.sari@example.com', 'password101'),
(5, 'PG05', 5, 'Daniel', '1995-03-25', '085678901234', 'erik.wibowo@example.com', '$2y$12$UEOuIOHIgSecxuanXXWVwug0p4Cq9ZFtdYvzXMWWtU69v6GmffK56'),
(6, 'PG06', 6, 'Adel', '1990-12-05', '086789012345', 'fiona.haryanti@example.com', '$2y$12$lKPjztxpwCL5Cnz6VWOhjOiLUTCUYd2sPzuHOfZDgvELir2xQ.mhK'),
(7, 'PG07', 3, 'Sophia', '1991-07-18', '087890123456', 'gita.nuraeni@example.com', 'password415'),
(8, 'PG08', 4, 'Rendra', '1987-04-02', '088901234567', 'hendra.putra@example.com', '$2y$12$d2ZzxwdhZK3ktrh4BtIMTOQzaodHEWNE2cdWMngS/SX2iko.nza6m'),
(9, 'PG09', 5, 'Juanicho', '1994-08-13', '088901234568', 'ika.nuraini@example.com', 'password718'),
(10, 'PG10', 1, 'Alwan', '1993-01-25', '090123456789', 'joko.suryanto@example.com', 'password192'),
(42, 'PG11', 2, 'Jonatong', '2006-09-21', '08987654321', 'Jo@gmail.com', '$2y$12$oVknvBlTfwFkMf12VXVG9OXz9Yz8DlVPKJSVsi8jrxmQKQrWNMIMq'),
(43, 'PG12', 1, 'alex', '2006-09-21', '08987654321', 'alex@gmail.com', '$2y$12$VRsA7kXyVBXx8ymfrVPBgegBAOv/mwG0UfzRrWVzL1tslVLI1jUYC'),
(44, 'PG13', 3, 'Lana', '2004-03-15', '08987654321', 'lana@gmail.com', '$2y$12$XWfAq5qqc0ASs0oEgIN5AewYlN6yEiLbtnw5gNs.dg4jQcwu5zYWq'),
(50, 'PG14', 1, 'Younkowi', '1987-10-13', '09999998899', 'younkowi@gmail.com', '$2y$12$rL32Qv0qeUnN8EH7.avzh.lwTP5sF6Ov1XG8XVXq/hTuCoKn4jWIS');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembeli`
--

CREATE TABLE `pembeli` (
  `id_pembeli` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `nama_pembeli` varchar(255) NOT NULL,
  `tanggal_lahir` datetime NOT NULL,
  `email_pembeli` varchar(255) NOT NULL,
  `password_pembeli` varchar(255) NOT NULL,
  `nomor_telepon_pembeli` varchar(255) NOT NULL,
  `total_poin` int(11) DEFAULT NULL,
  `foto_pembeli` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembeli`
--

INSERT INTO `pembeli` (`id_pembeli`, `id`, `nama_pembeli`, `tanggal_lahir`, `email_pembeli`, `password_pembeli`, `nomor_telepon_pembeli`, `total_poin`, `foto_pembeli`) VALUES
(1, 'PB001', 'Rina Kurniawati', '1990-04-12 00:00:00', 'rina.kurniawati@example.com', 'password123', '081234567890', 150, ''),
(2, 'PB002', 'Samsul Arifin', '1985-02-20 00:00:00', 'samsul.arifin@example.com', 'password456', '082345678901', 200, ''),
(3, 'PB003', 'Tina Suryani', '1992-06-15 00:00:00', 'tina.suryani@example.com', 'password789', '083456789012', 250, ''),
(4, 'PB004', 'Budi Santoso', '1993-08-05 00:00:00', 'budi.santoso@example.com', 'password101', '084567890123', 100, ''),
(5, 'PB005', 'Andi Pratama', '1991-11-30 00:00:00', 'andi.pratama@example.com', 'password112', '085678901234', 350, ''),
(6, 'PB006', 'Dewi Sari', '1987-03-25 00:00:00', 'dewi.sari@example.com', 'password131', '086789012345', 120, ''),
(7, 'PB007', 'Chandra Wijaya', '1994-09-18 00:00:00', 'chandra.wijaya@example.com', 'password415', '087890123456', 180, ''),
(8, 'PB008', 'Fiona Haryanti', '1989-12-22 00:00:00', 'fiona.haryanti@example.com', 'password161', '088901234567', 270, ''),
(9, 'PB009', 'Hendra Putra', '1995-01-10 00:00:00', 'hendra.putra@example.com', 'password192', '089012345678', 400, ''),
(10, 'PB010', 'Ika Nuraini', '1990-07-30 00:00:00', 'ika.nuraini@example.com', 'password202', '090123456789', 500, ''),
(11, 'PB011', 'Yamal udin susanto', '2019-06-11 00:00:00', 'yamal@gmail.com', '$2y$12$IlWfvIqIx2z2KvtkFk6QDeX8dJdKh4pZpr3BCbnXgQ87nQ52wAaK.', '082345673401', 500, 'uploads/1748862132.png'),
(12, 'PB012', 'pembeli', '2018-10-10 00:00:00', 'pembeli@gmail.com', '$2y$12$8yksK6K62Czvw7v3Y3YX3OPsraChkRLsMSNn4HcDbbfYWwI87BAd.', '09298121113', 0, NULL),
(13, 'PB013', 'susanto', '1999-10-21 00:00:00', 'susanto@gmail.com', '$2y$12$E9bXbO.n8KhITSVA1xe1teN7w.TwyUMk2vy5BrvWU6aT9ZdlaDI3y', '077777777777', 82, 'uploads/1747585369.jpg');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penitip`
--

CREATE TABLE `penitip` (
  `id_penitip` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `nama_penitip` varchar(255) NOT NULL,
  `nomor_ktp` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `email_penitip` varchar(255) NOT NULL,
  `password_penitip` varchar(255) NOT NULL,
  `nomor_telepon_penitip` varchar(255) NOT NULL,
  `saldo_penitip` float DEFAULT NULL,
  `total_poin` int(11) DEFAULT NULL,
  `badge` varchar(255) DEFAULT NULL,
  `jumlah_penjualan` int(11) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `Rating_penitip` float DEFAULT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penitip`
--

INSERT INTO `penitip` (`id_penitip`, `id`, `nama_penitip`, `nomor_ktp`, `tanggal_lahir`, `email_penitip`, `password_penitip`, `nomor_telepon_penitip`, `saldo_penitip`, `total_poin`, `badge`, `jumlah_penjualan`, `foto_profil`, `Rating_penitip`, `foto_ktp`) VALUES
(2, 'P002', 'Budi Santoso', '321987654321', '1985-06-20', 'budi.santoso@example.com', 'password456', '082345678901', 200000, 200, 'no', 70, 'budi_foto.jpg', 4.1, ''),
(3, 'P003', 'Chandra Wijaya', '320123456789', '1990-02-25', 'chandra.wijaya@example.com', 'password789', '083456789012', 306930, 150, 'no', 100, 'chandra_foto.jpg', 5, ''),
(4, 'P004', 'Dewi Sari', '321345678901', '1987-08-10', 'dewi.sari@example.com', 'password101', '084567890123', 50000, 50, 'no', 30, 'dewi_foto.jpg', 4.4, ''),
(5, 'P005', 'Erik Wibowo', '320987654321', '1992-11-05', 'erik.wibowo@example.com', 'password112', '085678901234', 80000, 120, 'no', 80, 'erik_foto.jpg', 3.8, ''),
(6, 'P006', 'Fiona Haryanti', '321123456780', '1995-09-22', 'fiona.haryanti@example.com', 'password131', '086789012345', 1255130, 250, 'no', 60, 'fiona_foto.jpg', 4.5, ''),
(7, 'P007', 'Gita Nuraeni', '321234567890', '1994-05-15', 'gita.nuraeni@example.com', 'password415', '087890123456', 120000, 180, 'no', 90, 'gita_foto.jpg', 4.8, ''),
(8, 'P008', 'Hendra Putra', '320876543210', '1993-03-02', 'hendra.putra@example.com', 'password161', '088901234567', 40000, 75, 'no', 40, 'hendra_foto.jpg', 4.7, ''),
(9, 'P009', 'Ika Nuraini', '320123789456', '1991-07-10', 'ika.nuraini@example.com', 'password192', '089012345678', 350000, 300, 'yes', 110, 'ika_foto.jpg', 3.9, ''),
(13, 'P011', 'Fiko santoso oo', '32114545468', '1998-07-24', 'fiko@gmail.com', '$2y$12$IE/mIHIQBwOC/T7qnVCZFeIp/bbnrcUp5lBJ/euaDAWYJIkRIE7KK', '082345679801', 4000, 20, NULL, 2, NULL, 4.4, ''),
(14, 'PT012', 'ahmad dialo ooo', '321987654398', '2025-05-15', 'dialo@gmail.com', '$2y$12$8zUccD2DAKaKZQemoZ0NlO80X7sfpMgbV5h4zsZx6SLpaTluc9f3u', '08111111111', 800000, NULL, NULL, 0, NULL, 0, NULL),
(15, 'P012', 'ahmad', '3229576343291', '2025-05-15', 'ahmad@gmail.com', '$2y$12$XNZMpcUxaFuhnefWf8RvRenocA4M31Gi2RdfXFCnMIeUBnY8Cxl.a', '08222222222', 0, NULL, NULL, 0, NULL, 0, NULL),
(16, 'P013', 'dialo', '121948754328', '1998-02-18', 'dia12@gmail.com', '$2y$12$ZCXl8O.jkmJdDKHsln0vvuHI0l.3rCD0aniyEn7.4BkZ4BOyf./6a', '0833333333', 608015, 0, NULL, 0, NULL, NULL, NULL),
(17, 'P013', 'messi sui', '321987654398', '2025-05-14', 'messi@gmail.com', '$2y$12$2f9mnhW8A23/t4pBDTNOie3B9lEHx3GLMrTrJZAr8awNuty/SnHdK', '082345679801', 0, 0, NULL, 0, NULL, NULL, NULL),
(19, 'P013', 'Cristian', '11111111111', '2000-02-09', 'cristian12@gmail.com', '$2y$12$1I3QGbri3MfkcmCN4aD3CuhNsFHYx4GzN0bwYnNPZL2Yv75MybAV2', '08222223122', 231000, 0, NULL, 0, NULL, 3.5, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\Pegawai', 42, 'Personal Access Token', '3952875b09e5302297487e53fbca82ada057f3809e2ae19ffdcf8bbb99c3ec6a', '[\"*\"]', NULL, NULL, '2025-04-30 07:36:59', '2025-04-30 07:36:59'),
(2, 'App\\Models\\Pegawai', 42, 'Personal Access Token', '328e60c064e382ff4ca873a8dc24f0ec41488f568d3a955d9d23c9b0b6642633', '[\"*\"]', NULL, NULL, '2025-04-30 08:15:40', '2025-04-30 08:15:40'),
(3, 'App\\Models\\Pegawai', 42, 'Personal Access Token', 'd7ec6578c51df3692ba25ae0cd01c2f84cb8599c866e49565df428ed1cd40e26', '[\"*\"]', '2025-04-30 08:23:25', NULL, '2025-04-30 08:17:37', '2025-04-30 08:23:25'),
(4, 'App\\Models\\Pegawai', 42, 'Personal Access Token', 'eaf87df8f948b8cd8767d1010137e4f685b75c62ad9c6c1f3e931dea1f6bb753', '[\"*\"]', '2025-04-30 08:30:17', NULL, '2025-04-30 08:24:04', '2025-04-30 08:30:17'),
(5, 'App\\Models\\Pegawai', 42, 'Personal Access Token', '72a7b26a5c323f92efe7ba059b84afcadcb628e4d1f796ab78c7a9309be29fec', '[\"*\"]', '2025-04-30 08:30:59', NULL, '2025-04-30 08:30:40', '2025-04-30 08:30:59'),
(6, 'App\\Models\\Pegawai', 42, 'Personal Access Token', '2764f8d759ade57719212911435e7457fd052be8e1d601d0f0d57fa1a21fbf6b', '[\"*\"]', '2025-04-30 08:33:11', NULL, '2025-04-30 08:33:01', '2025-04-30 08:33:11'),
(7, 'App\\Models\\Pegawai', 42, 'Personal Access Token', '351387fee14c5e7284cef56b1ed4abf64f3e3facdcbe890effdc51f0d52eb05d', '[\"*\"]', '2025-04-30 08:42:30', NULL, '2025-04-30 08:42:16', '2025-04-30 08:42:30'),
(8, 'App\\Models\\Pegawai', 43, 'Personal Access Token', '89281ef354e0a9a449e9b6fa04f54b204c6397aca939326eb9bbc1cc1ca1015b', '[\"*\"]', '2025-04-30 08:46:12', NULL, '2025-04-30 08:43:24', '2025-04-30 08:46:12'),
(9, 'App\\Models\\Pegawai', 43, 'Personal Access Token', '990d2435b02cec21abafad463f81c701775d74e847529f0e4e7c2bdfbe438447', '[\"*\"]', '2025-04-30 08:56:50', NULL, '2025-04-30 08:49:09', '2025-04-30 08:56:50'),
(10, 'App\\Models\\Pegawai', 43, 'Personal Access Token', '2dbb925f865b524e7424e9cc94b7ddd861c25fcdb92af383dae8579992e60556', '[\"*\"]', '2025-04-30 08:57:14', NULL, '2025-04-30 08:56:53', '2025-04-30 08:57:14'),
(11, 'App\\Models\\Pegawai', 43, 'Personal Access Token', '1f7dceb904c8c2163432e986ec6bf04f2eef3ffc0577d0918c042eb000ba1e66', '[\"*\"]', '2025-05-01 04:09:10', NULL, '2025-05-01 04:07:10', '2025-05-01 04:09:10'),
(12, 'App\\Models\\Pegawai', 43, 'Personal Access Token', 'ea8d029851ae2cba6465b7778fbc8c90c7e5ca56b1a181c7e59a3d73ea81c808', '[\"*\"]', '2025-05-01 04:14:13', NULL, '2025-05-01 04:13:54', '2025-05-01 04:14:13'),
(13, 'App\\Models\\Pegawai', 43, 'Personal Access Token', 'c22b54fdd3b751c31520c38a9a375fdfac4a63c55235591a510aeb83b1213bfd', '[\"*\"]', NULL, NULL, '2025-05-01 04:19:32', '2025-05-01 04:19:32'),
(14, 'App\\Models\\Pegawai', 43, 'Personal Access Token', '1fc056f0224b068284fc7a58285c3d7dcadb9fb6269cb9c3d5870ae77b443b85', '[\"*\"]', NULL, NULL, '2025-05-01 04:24:03', '2025-05-01 04:24:03'),
(15, 'App\\Models\\Pegawai', 43, 'Personal Access Token', 'd5670cb8292183ac0fe876d2f281e5e86b5688fd55122c0bd3dff7b0a9912eaa', '[\"Admin\"]', '2025-05-01 04:36:36', NULL, '2025-05-01 04:30:56', '2025-05-01 04:36:36'),
(16, 'App\\Models\\Pegawai', 43, 'Personal Access Token', 'a4efb9cea815c42f40a852a8081e416a539b94aba2ff29fca7efcf4eb0d94e44', '[\"Admin\"]', '2025-05-01 06:00:27', NULL, '2025-05-01 05:55:34', '2025-05-01 06:00:27'),
(17, 'App\\Models\\Pegawai', 43, 'Personal Access Token', '8a4ecbd704a45fdfd1f176d2f459c277ad1e8f6802df4df480d8ce56f17d31f5', '[\"Admin\"]', '2025-05-01 06:01:43', NULL, '2025-05-01 06:00:32', '2025-05-01 06:01:43'),
(18, 'App\\Models\\Pegawai', 42, 'Personal Access Token', '759671bba47fd26a1c1f15dc496d1b92b35709a0c5af16b49d5d5358c3d8f820', '[\"Owner\"]', '2025-05-01 06:02:45', NULL, '2025-05-01 06:02:33', '2025-05-01 06:02:45'),
(19, 'App\\Models\\Pegawai', 43, 'Personal Access Token', 'c76cda360d90a00f017606d17504e27171648b2d9dfcc9109cb6ca96d72e5f5d', '[\"Admin\"]', '2025-05-04 04:52:18', NULL, '2025-05-04 04:50:58', '2025-05-04 04:52:18'),
(20, 'App\\Models\\Pegawai', 42, 'Personal Access Token', '5edf9076ea5a03ceb3740ea1dd5848dc2c5017b4ee987cbf603ae38f62bdeaa0', '[\"Owner\"]', '2025-05-04 04:53:00', NULL, '2025-05-04 04:52:46', '2025-05-04 04:53:00'),
(21, 'App\\Models\\Pegawai', 43, 'Personal Access Token', 'e07acee29cb3167c5f07a6451dc8ac61401c01389e32dd12918c26126c0f8760', '[\"Admin\"]', '2025-05-04 04:54:36', NULL, '2025-05-04 04:54:21', '2025-05-04 04:54:36'),
(22, 'App\\Models\\Pegawai', 42, 'Personal Access Token', '476881a894b255a935d2175a29152f87e1e201fd343b1d105dd0f86087eafdf4', '[\"Owner\"]', '2025-05-04 05:28:56', NULL, '2025-05-04 05:28:32', '2025-05-04 05:28:56'),
(23, 'App\\Models\\Pegawai', 44, 'Personal Access Token', '59bb506650acf92bb726945c8d9816b938972bee0ee35626a17801403977af36', '[\"Customer Service\"]', '2025-05-04 05:31:55', NULL, '2025-05-04 05:31:34', '2025-05-04 05:31:55'),
(37, 'App\\Models\\Pegawai', 42, 'Personal Access Token', '0fc00012949b983cd91e63f7383976672a2b3ed22f061acd1723ee3a1ea5c694', '[\"Owner\"]', '2025-05-06 23:55:34', NULL, '2025-05-06 23:51:30', '2025-05-06 23:55:34'),
(68, 'App\\Models\\Pembeli', 11, 'pembeli-token', 'f5094f6c9572c42ace6acb0b67da4cfc86821ce5082be39354d038a7f4cab425', '[\"*\"]', '2025-06-09 20:25:52', NULL, '2025-06-09 20:24:57', '2025-06-09 20:25:52'),
(87, 'App\\Models\\Pegawai', 6, 'pegawai-token', 'f24340eed98f047601cf162a1e579dba19770531a26cf51d14d48ce7e1bc1b8a', '[\"*\"]', '2025-06-18 07:01:14', NULL, '2025-06-18 06:47:51', '2025-06-18 07:01:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `requestdonasi`
--

CREATE TABLE `requestdonasi` (
  `id_request` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `id_organisasi` int(11) NOT NULL,
  `deskripsi_request` varchar(255) NOT NULL,
  `tanggal_request` datetime NOT NULL,
  `status_request` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `requestdonasi`
--

INSERT INTO `requestdonasi` (`id_request`, `id`, `id_organisasi`, `deskripsi_request`, `tanggal_request`, `status_request`) VALUES
(1, 'R001', 1, 'Pengajuan Televisi 30 inch', '2025-04-01 08:00:00', 'approved'),
(2, 'R002', 2, 'Pengajuan 2 buah smartphone untuk program pendidikan lansia', '2025-04-02 09:30:00', 'approved'),
(3, 'R003', 3, 'Permintaan 1 lusin gelas', '2025-04-03 10:15:00', 'approved'),
(4, 'R004', 4, 'Permintaan 1 lusin bola mainan', '2025-04-04 11:00:00', 'approved'),
(5, 'R005', 5, 'Permohonan 5 keranjang bekas', '2025-04-05 12:45:00', 'rejected'),
(6, 'R006', 6, 'Pengajuan kursi dan meja satu set', '2025-04-06 13:30:00', 'pending'),
(7, 'R007', 7, 'Permintaan 1 set wajan', '2025-04-07 14:00:00', 'approved'),
(8, 'R008', 8, 'Pengajuan blender dan pisau', '2025-04-08 15:15:00', 'rejected'),
(9, 'R009', 9, 'Permohonan mixer untuk keperluan adonan', '2025-04-09 16:00:00', 'Tolak'),
(10, 'R010', 10, 'Permintaan 2 buah papan tulis putih untuk pendidikan anak', '2025-04-10 17:30:00', 'approved'),
(13, 'R011', 12, 'barang 2', '2025-05-17 13:54:11', 'pending'),
(18, 'R012', 12, 'kipas 100', '2025-05-17 13:39:18', 'approved');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `topseller`
--

CREATE TABLE `topseller` (
  `id_penitip` int(11) NOT NULL,
  `tanggal_mulai` datetime NOT NULL,
  `tanggal_selesai` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `topseller`
--

INSERT INTO `topseller` (`id_penitip`, `tanggal_mulai`, `tanggal_selesai`) VALUES
(2, '2025-04-02 09:30:00', '2025-05-02 09:30:00'),
(3, '2025-04-03 10:15:00', '2025-05-03 10:15:00'),
(4, '2025-04-04 11:00:00', '2025-05-04 11:00:00'),
(5, '2025-04-05 12:45:00', '2025-05-05 12:45:00'),
(6, '2025-04-06 13:30:00', '2025-05-06 13:30:00'),
(7, '2025-04-07 14:00:00', '2025-05-07 14:00:00'),
(8, '2025-04-08 15:15:00', '2025-05-08 15:15:00'),
(9, '2025-04-09 16:00:00', '2025-05-09 16:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksipenitipan`
--

CREATE TABLE `transaksipenitipan` (
  `id_transaksi_penitipan` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `id_hunter` int(11) DEFAULT NULL,
  `id_penitip` int(11) NOT NULL,
  `tanggal_penitipan` datetime NOT NULL,
  `tanggal_akhir_penitipan` datetime NOT NULL,
  `tanggal_batas_pengambilan` datetime DEFAULT NULL,
  `tanggal_pengambilan_barang` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksipenitipan`
--

INSERT INTO `transaksipenitipan` (`id_transaksi_penitipan`, `id`, `id_pegawai`, `id_hunter`, `id_penitip`, `tanggal_penitipan`, `tanggal_akhir_penitipan`, `tanggal_batas_pengambilan`, `tanggal_pengambilan_barang`) VALUES
(2, 'T002', 4, NULL, 2, '2025-03-03 09:30:00', '2025-03-30 09:30:00', '2025-04-07 09:30:00', '2025-04-06 10:30:00'),
(3, 'T003', 6, NULL, 3, '2025-02-04 10:15:00', '2025-03-05 10:15:00', '2025-03-13 10:15:00', '2025-03-12 12:00:00'),
(4, 'T004', 4, NULL, 4, '2025-05-05 11:00:00', '2025-06-04 11:00:00', '2025-06-11 11:00:00', '2025-06-10 13:00:00'),
(5, 'T005', 6, NULL, 5, '2025-07-06 12:10:00', '2025-08-05 12:10:00', '2025-08-12 12:10:00', '2025-08-11 14:20:00'),
(6, 'T006', 4, NULL, 6, '2025-09-07 13:00:00', '2025-10-06 13:00:00', '2025-10-13 13:00:00', '2025-10-12 15:00:00'),
(7, 'T007', 6, NULL, 7, '2025-10-08 14:20:00', '2025-11-06 14:20:00', '2025-11-13 14:20:00', '2025-11-12 16:00:00'),
(8, 'T008', 4, NULL, 8, '2025-06-09 15:00:00', '2025-07-07 15:00:00', '2025-07-14 15:00:00', '2025-07-13 17:00:00'),
(9, 'T009', 6, NULL, 9, '2025-08-10 16:45:00', '2025-09-08 16:45:00', '2025-09-15 16:45:00', '2025-09-14 18:00:00'),
(10, 'T010', 6, NULL, 13, '2025-05-21 00:00:00', '2025-07-20 00:00:00', '2025-06-27 00:00:00', '2025-06-26 19:22:25'),
(11, 'T011', 6, NULL, 13, '2025-05-21 00:00:00', '2025-06-20 00:00:00', '2025-06-20 00:00:00', '2025-06-26 19:22:25'),
(14, 'T012', 8, NULL, 15, '2025-05-29 00:00:00', '2025-05-30 13:06:55', NULL, '2025-05-30 12:14:06'),
(15, 'T013', 8, NULL, 15, '2025-05-30 00:00:00', '2025-06-29 00:00:00', NULL, NULL),
(16, 'T014', 8, NULL, 14, '2025-06-01 00:00:00', '2025-07-01 00:00:00', NULL, NULL),
(17, 'T015', 8, NULL, 16, '2025-06-01 00:00:00', '2025-07-01 00:00:00', NULL, NULL),
(18, 'T016', 8, NULL, 14, '2025-06-01 00:00:00', '2025-07-01 00:00:00', NULL, NULL),
(19, 'T017', 8, NULL, 16, '2025-06-02 00:00:00', '2025-07-02 00:00:00', NULL, NULL),
(20, 'T018', 8, NULL, 15, '2025-06-02 00:00:00', '2025-07-02 00:00:00', NULL, NULL),
(21, 'T019', 8, NULL, 14, '2025-06-02 00:00:00', '2025-07-02 00:00:00', NULL, NULL),
(22, 'T020', 8, NULL, 15, '2025-06-02 00:00:00', '2025-06-02 23:56:56', NULL, '2025-06-02 23:56:44'),
(23, 'T021', 8, NULL, 16, '2025-06-03 00:00:00', '2025-07-03 00:00:00', NULL, NULL),
(24, 'T022', 8, NULL, 16, '2025-06-03 00:00:00', '2025-07-03 00:00:00', NULL, NULL),
(25, 'T023', 8, NULL, 16, '2025-06-03 00:00:00', '2025-07-03 00:00:00', NULL, NULL),
(26, 'T024', 8, NULL, 19, '2025-06-03 00:00:00', '2025-06-03 02:02:30', NULL, '2025-06-03 02:01:58'),
(27, 'T025', 8, NULL, 19, '2025-06-03 00:00:00', '2025-07-03 00:00:00', NULL, NULL),
(28, 'T026', 8, NULL, 19, '2025-06-03 00:00:00', '2025-07-03 00:00:00', NULL, NULL),
(29, 'T027', 8, NULL, 19, '2025-06-03 00:00:00', '2025-07-03 00:00:00', NULL, NULL),
(30, 'T028', 8, NULL, 2, '2025-06-03 00:00:00', '2025-07-03 00:00:00', NULL, NULL),
(31, 'T029', 8, NULL, 14, '2025-06-03 00:00:00', '2025-07-03 00:00:00', NULL, NULL),
(32, 'T030', 8, NULL, 3, '2025-06-03 00:00:00', '2025-07-03 00:00:00', NULL, NULL),
(33, 'T031', 8, NULL, 4, '2025-06-03 00:00:00', '2025-07-03 00:00:00', NULL, NULL),
(34, 'T032', 8, NULL, 17, '2025-06-04 00:00:00', '2025-07-04 00:00:00', NULL, NULL),
(35, 'T033', 8, NULL, 19, '2025-06-04 00:00:00', '2025-07-04 00:00:00', NULL, NULL),
(36, 'T034', 8, NULL, 2, '2025-06-04 00:00:00', '2025-07-04 00:00:00', NULL, NULL),
(37, 'T035', 8, NULL, 19, '2025-06-04 00:00:00', '2025-07-04 00:00:00', NULL, NULL),
(38, 'T036', 8, NULL, 15, '2025-06-04 00:00:00', '2025-07-04 00:00:00', NULL, NULL),
(39, 'T037', 8, NULL, 13, '2025-06-04 00:00:00', '2025-07-04 00:00:00', NULL, NULL),
(40, 'T038', 8, NULL, 15, '2025-06-05 00:00:00', '2025-07-05 00:00:00', NULL, NULL),
(41, 'T039', 8, NULL, 14, '2025-06-05 00:00:00', '2025-06-05 00:00:00', '2025-06-12 13:36:35', '2025-06-05 13:36:35'),
(42, 'T040', 8, NULL, 2, '2025-06-05 00:00:00', '2025-07-05 00:00:00', NULL, NULL),
(43, 'T041', 8, NULL, 16, '2025-06-05 00:00:00', '2025-07-05 00:00:00', NULL, NULL),
(44, 'T042', 6, NULL, 19, '2025-06-03 00:00:00', '2025-07-03 00:00:00', NULL, NULL),
(45, 'T043', 6, NULL, 19, '2025-06-03 00:00:00', '2025-07-03 00:00:00', NULL, NULL),
(46, 'T044', 6, NULL, 19, '2025-06-03 00:00:00', '2025-07-03 00:00:00', NULL, NULL),
(47, 'T045', 8, 6, 13, '2025-06-26 00:00:00', '2025-07-26 00:00:00', '2025-08-02 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksipenjualan`
--

CREATE TABLE `transaksipenjualan` (
  `id_transaksi_penjualan` int(11) NOT NULL,
  `id` varchar(255) NOT NULL,
  `id_pembeli` int(11) NOT NULL,
  `id_pegawai` int(11) NOT NULL,
  `tanggal_transaksi` datetime NOT NULL,
  `metode_pengantaran` varchar(255) NOT NULL,
  `tanggal_lunas` datetime DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `status_pembayaran` varchar(255) NOT NULL,
  `poin` int(11) NOT NULL,
  `tanggal_kirim` datetime DEFAULT NULL,
  `ongkir` varchar(255) NOT NULL,
  `status_transaksi` varchar(255) NOT NULL,
  `id_kurir` int(11) DEFAULT NULL,
  `tanggal_ambil` datetime DEFAULT NULL,
  `no_nota` varchar(255) DEFAULT NULL,
  `poin_dapat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksipenjualan`
--

INSERT INTO `transaksipenjualan` (`id_transaksi_penjualan`, `id`, `id_pembeli`, `id_pegawai`, `tanggal_transaksi`, `metode_pengantaran`, `tanggal_lunas`, `bukti_pembayaran`, `status_pembayaran`, `poin`, `tanggal_kirim`, `ongkir`, `status_transaksi`, `id_kurir`, `tanggal_ambil`, `no_nota`, `poin_dapat`) VALUES
(1, 'TP01', 1, 5, '2025-03-01 08:00:00', 'Kurir', '2025-03-01 08:11:00', 'bukti.png', 'Lunas', 120, '2025-03-03 10:00:00', '15000', '', NULL, NULL, NULL, NULL),
(2, 'TP02', 2, 4, '2025-03-05 09:12:00', 'Ambil di gudang', '2025-03-05 09:20:00', 'bukti.png', 'Lunas', 180, '2025-03-06 13:45:00', '0', '', NULL, NULL, NULL, NULL),
(3, 'TP03', 3, 5, '2025-02-10 13:30:00', 'Kurir', '2025-02-10 13:35:00', 'valid', 'lunas/sedang disiapkan', 140, '2025-02-12 11:30:00', '20000', '', NULL, NULL, NULL, NULL),
(4, 'TP04', 4, 4, '2025-01-15 07:25:00', 'Ambil di gudang', '2025-01-15 07:40:00', 'bukti.png', 'Lunas', 260, '2025-01-16 14:20:00', '0', '', NULL, NULL, NULL, NULL),
(5, 'TP05', 5, 5, '2025-06-18 10:10:00', 'Kurir', '2025-06-18 10:20:00', 'bukti.png', 'Belum Lunas', 320, '2025-06-19 15:00:00', '25000', '', NULL, NULL, NULL, NULL),
(6, 'TP06', 6, 4, '2025-04-22 11:45:00', 'Ambil di gudang', '2025-04-22 12:00:00', 'bukti.png', 'Lunas', 410, '2025-04-23 09:30:00', '0', '', NULL, NULL, NULL, NULL),
(7, 'TP07', 7, 5, '2025-07-05 14:50:00', 'Kurir', '2025-07-05 15:00:00', 'bukti.png', 'Lunas', 510, '2025-07-06 17:45:00', '18000', '', NULL, NULL, NULL, NULL),
(8, 'TP08', 8, 4, '2025-08-12 16:40:00', 'Ambil di gudang', '2025-08-12 17:00:00', 'bukti.png', 'Belum Lunas', 630, '2025-08-13 14:30:00', '0', '', NULL, NULL, NULL, NULL),
(9, 'TP09', 9, 5, '2025-09-19 18:05:00', 'Kurir', '2025-09-19 18:15:00', 'bukti.png', 'Lunas', 720, '2025-09-20 19:10:00', '20000', '', NULL, NULL, NULL, NULL),
(10, 'TP10', 10, 4, '2025-10-25 19:30:00', 'Ambil di gudang', '2025-10-25 19:40:00', 'bukti.png', 'Belum Lunas', 840, '2025-10-26 20:00:00', '0', '', NULL, NULL, NULL, NULL),
(11, 'TP011', 10, 4, '2025-04-25 00:00:00', 'ambil di gudang', '0000-00-00 00:00:00', 'bukti1.png', 'belum lunas', 100, '0000-00-00 00:00:00', '0', '', NULL, NULL, NULL, NULL),
(12, 'TP12', 11, 4, '2025-05-13 04:33:14', 'Kurir', '2025-05-13 04:33:14', 'bukti.png', 'lunas', 100, '2025-05-13 04:33:14', '100000', '', NULL, NULL, NULL, NULL),
(15, 'TP13', 11, 4, '2025-05-13 04:33:14', 'ambil digudang', '2025-05-13 04:33:14', 'bukti.png', 'belum', 0, '2025-05-13 04:33:14', '0', '', NULL, NULL, NULL, NULL),
(18, 'TP14', 11, 9, '2025-05-31 13:21:40', 'diantar_kurir', '2025-05-31 13:22:24', '1748697741_11_avatars-21.png', 'Lunas', 42, '2025-06-02 09:50:14', '100000', 'dijadwalkan', NULL, NULL, '2025.06.23', NULL),
(19, 'TP15', 11, 4, '2025-05-31 13:34:04', 'ambil_sendiri', '2025-05-31 13:34:24', '1748698464_11_Color5.jpg', 'Lunas', 0, NULL, '0', 'transaksi selesai', NULL, '2025-06-02 00:00:00', '2025.06.21', NULL),
(20, 'TP16', 11, 5, '2025-06-01 12:13:44', 'diantar_kurir', '2025-06-01 12:13:55', '1748780033_11_Color5(1).jpg', 'Lunas', 60, '2025-06-03 01:42:30', '100000', 'dijadwalkan', NULL, NULL, '2025.06.30', NULL),
(21, 'TP17', 11, 4, '2025-06-01 15:12:46', 'ambil_sendiri', '2025-06-01 15:12:55', '1748790775_11_Color5.png', 'Lunas', 20, NULL, '0', 'Di siapkan', NULL, '2025-06-03 00:00:00', '2025.06.22', NULL),
(22, 'TP18', 11, 4, '2025-06-01 15:27:01', 'ambil_sendiri', '2025-06-01 15:27:10', '1748791630_11_k.jpg', 'Lunas', 3, NULL, '0', 'Di siapkan', NULL, '2025-06-01 00:00:00', NULL, NULL),
(23, 'TP19', 11, 5, '2025-06-02 10:23:38', 'diantar_kurir', NULL, NULL, 'dibatalkan', 250, '2025-06-04 10:23:38', '100000', 'dibatalkan', NULL, NULL, NULL, NULL),
(24, 'TP20', 11, 9, '2025-06-02 10:25:22', 'diantar_kurir', '2025-06-02 10:25:37', '1748859935_11_20241119_221908.jpg', 'Lunas', 100, '2025-06-02 10:27:29', '100000', 'dijadwalkan', NULL, NULL, '2025.06.25', NULL),
(25, 'TP21', 11, 4, '2025-06-02 12:42:49', 'ambil_sendiri', '2025-06-02 12:43:00', '1748868180_11_Color5(1).jpg', 'Lunas', 100, NULL, '0', 'transaksi selesai', NULL, '2025-06-03 00:00:00', NULL, NULL),
(26, 'TP22', 13, 4, '2025-06-02 12:50:26', 'ambil_sendiri', '2025-06-02 12:50:38', '1748868638_13_Color5 1.png', 'Lunas', 0, NULL, '0', 'transaksi selesai', NULL, '2025-06-02 00:00:00', NULL, NULL),
(27, 'TP23', 13, 4, '2025-06-02 12:56:31', 'ambil_sendiri', '2025-06-02 12:56:42', '1748869002_13_Color5 2.png', 'Lunas', 0, NULL, '0', 'transaksi selesai', NULL, '2025-06-03 00:00:00', NULL, NULL),
(28, 'TP24', 13, 4, '2025-06-02 20:10:54', 'ambil_sendiri', '2025-06-02 20:11:08', '1748895067_13_Color5.png', 'Lunas', 0, NULL, '0', 'transaksi selesai', NULL, '2025-06-04 00:00:00', NULL, 32),
(29, 'TP25', 13, 4, '2025-06-03 01:19:09', 'ambil_sendiri', '2025-06-03 01:19:23', '1748913563_13_Color5.png', 'Lunas', 0, NULL, '0', 'transaksi selesai', NULL, '2025-06-04 00:00:00', '2025.06.30', 10),
(30, 'TP26', 11, 9, '2025-06-03 01:48:45', 'diantar_kurir', '2025-06-03 01:48:54', '1748915334_11_contoh logo.PNG', 'Lunas', 0, '2025-06-03 01:49:32', '100000', 'dijadwalkan', NULL, NULL, '2025.06.31', 80),
(31, 'TP27', 11, 5, '2025-06-03 02:29:29', 'diantar_kurir', NULL, NULL, 'dibatalkan', 0, '2025-06-05 02:29:29', '0', 'dibatalkan', NULL, NULL, NULL, NULL),
(32, 'TP28', 11, 6, '2025-06-03 02:31:06', 'ambil_sendiri', '2025-06-03 02:31:16', '1748917876_11_3.png', 'Lunas', 300, NULL, '0', 'transaksi selesai', NULL, '2025-06-03 00:00:00', '2025.06.34', 239),
(33, 'TP29', 11, 9, '2025-06-03 02:39:37', 'diantar_kurir', '2025-06-03 02:39:45', '1748918385_11_Color5.png', 'Lunas', 0, '2025-06-03 02:40:42', '100000', 'dijadwalkan', NULL, NULL, '2025.06.34', 120),
(34, 'TP30', 11, 4, '2025-06-05 02:46:32', 'ambil_sendiri', '0000-00-00 00:00:00', '1748918800_11_20241119_221908.jpg', 'Lunas', 0, NULL, '0', 'Hangus', NULL, '2025-06-01 00:00:00', NULL, 49),
(35, 'TP31', 11, 4, '2025-06-03 03:59:49', 'ambil_sendiri', '2025-06-03 04:00:08', '1748923208_11_contoh logo.PNG', 'Lunas', 0, NULL, '0', 'siap diambil', NULL, '2025-06-04 00:00:00', NULL, 7),
(36, 'TP32', 11, 6, '2025-06-03 04:14:05', 'ambil_sendiri', '2025-06-03 04:14:11', '1748924051_11_foto1.jpg', 'Lunas', 0, NULL, '0', 'transaksi selesai', NULL, '2025-06-04 00:00:00', NULL, 30),
(37, 'TP33', 11, 4, '2025-06-03 04:15:31', 'ambil_sendiri', '2025-06-03 04:15:38', '1748924138_11_Frame 1069.png', 'Lunas', 0, NULL, '0', 'Hangus', NULL, '2025-06-01 00:00:00', NULL, 40),
(38, 'TP34', 11, 4, '2025-06-03 04:16:27', 'ambil_sendiri', '2025-06-03 04:16:37', '1748924197_11_2.png', 'Lunas', 0, NULL, '0', 'Hangus', NULL, '2025-06-01 00:00:00', NULL, 1080),
(39, 'TP35', 11, 4, '2025-06-03 04:18:55', 'ambil_sendiri', '2025-06-03 04:19:03', '1748924343_11_Frame 5.png', 'Lunas', 0, NULL, '0', 'Hangus', NULL, '2025-06-01 00:00:00', NULL, 0),
(40, 'TP36', 11, 6, '2025-06-03 04:19:15', 'ambil_sendiri', '2025-06-03 04:19:24', '1748924364_11_k.jpg', 'Lunas', 0, NULL, '0', 'transaksi selesai', NULL, '2025-06-03 00:00:00', NULL, 0),
(41, 'TP37', 13, 4, '2025-06-04 13:08:42', 'ambil_sendiri', '2025-06-04 13:08:51', '1749042529_13_Frame 1069.png', 'Lunas', 0, NULL, '0', 'Hangus', NULL, '2025-06-01 00:00:00', NULL, 84),
(42, 'TP38', 13, 4, '2025-06-04 13:53:55', 'ambil_sendiri', '2025-06-04 13:54:03', '1749045243_13_Desktop - 3.png', 'Lunas', 0, NULL, '0', 'Hangus', NULL, '2025-06-01 00:00:00', NULL, 0),
(43, 'TP39', 13, 4, '2025-06-04 13:58:11', 'ambil_sendiri', '2025-06-04 13:58:17', '1749045497_13_Color5.png', 'Lunas', 0, NULL, '0', 'Di siapkan', NULL, '2025-06-05 00:00:00', NULL, 175),
(44, 'TP40', 13, 5, '2025-06-04 14:48:16', 'diantar_kurir', '2025-06-04 14:48:26', '1749048506_13_foto1.jpg', 'Lunas', 0, '2025-06-05 08:00:00', '0', 'dijadwalkan', NULL, NULL, NULL, 960),
(45, 'TP41', 13, 5, '2025-06-04 15:06:46', 'diantar_kurir', '2025-06-04 15:06:52', '1749049612_13_Color5 2.png', 'Menunggu Konfirmasi', 0, '2025-06-05 08:00:00', '100000', 'sedang disiapkan', NULL, NULL, '2025.06.46', 30);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `alamat`
--
ALTER TABLE `alamat`
  ADD PRIMARY KEY (`id_alamat`),
  ADD KEY `fk_pembeli` (`id_pembeli`);

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `fk_kategori` (`id_kategori`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `claimmerchandise`
--
ALTER TABLE `claimmerchandise`
  ADD PRIMARY KEY (`id_transaksi_claim_merchandise`),
  ADD KEY `pembeli_fk` (`id_pembeli`),
  ADD KEY `merchandise_fk` (`id_merchandise`);

--
-- Indeks untuk tabel `detailtransaksipenitipan`
--
ALTER TABLE `detailtransaksipenitipan`
  ADD PRIMARY KEY (`id_detail_transaksi_penitipan`),
  ADD KEY `barang_fk` (`id_barang`),
  ADD KEY `id_transaksi_penitipan` (`id_transaksi_penitipan`);

--
-- Indeks untuk tabel `detailtransaksipenjualan`
--
ALTER TABLE `detailtransaksipenjualan`
  ADD PRIMARY KEY (`id_detail_transaksi_penjualan`) USING BTREE,
  ADD KEY `fk_barang` (`id_barang`),
  ADD KEY `fk_transaksi_penjualan` (`id_transaksi_penjualan`) USING BTREE;

--
-- Indeks untuk tabel `diskusi`
--
ALTER TABLE `diskusi`
  ADD PRIMARY KEY (`id_forum_diskusi`),
  ADD KEY `id_pegawai` (`id_pegawai`),
  ADD KEY `id_pembeli` (`id_pembeli`),
  ADD KEY `fk_diskusi_barang` (`id_barang`);

--
-- Indeks untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD KEY `fk_request` (`id_request`),
  ADD KEY `foreignKey_barang` (`id_barang`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategoribarang`
--
ALTER TABLE `kategoribarang`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `komisi`
--
ALTER TABLE `komisi`
  ADD PRIMARY KEY (`id_komisi`),
  ADD KEY `fk_penitip` (`id_penitip`),
  ADD KEY `fk_pegawai` (`id_pegawai`),
  ADD KEY `transaksi_penjualan_fk` (`id_transaksi_penjualan`) USING BTREE;

--
-- Indeks untuk tabel `merchandise`
--
ALTER TABLE `merchandise`
  ADD PRIMARY KEY (`id_merchandise`),
  ADD KEY `pegawai_fk` (`id_pegawai`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `organisasi`
--
ALTER TABLE `organisasi`
  ADD PRIMARY KEY (`id_organisasi`);

--
-- Indeks untuk tabel `password_forgot`
--
ALTER TABLE `password_forgot`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_forgot_pembeli_tabel`
--
ALTER TABLE `password_forgot_pembeli_tabel`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`id_pegawai`),
  ADD KEY `fk_jabatan` (`id_jabatan`);

--
-- Indeks untuk tabel `pembeli`
--
ALTER TABLE `pembeli`
  ADD PRIMARY KEY (`id_pembeli`);

--
-- Indeks untuk tabel `penitip`
--
ALTER TABLE `penitip`
  ADD PRIMARY KEY (`id_penitip`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `requestdonasi`
--
ALTER TABLE `requestdonasi`
  ADD PRIMARY KEY (`id_request`),
  ADD KEY `fk_organisasi` (`id_organisasi`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `topseller`
--
ALTER TABLE `topseller`
  ADD KEY `penitip_fk` (`id_penitip`);

--
-- Indeks untuk tabel `transaksipenitipan`
--
ALTER TABLE `transaksipenitipan`
  ADD PRIMARY KEY (`id_transaksi_penitipan`),
  ADD KEY `penitip_fkey` (`id_penitip`),
  ADD KEY `pegawai_fkey` (`id_pegawai`);

--
-- Indeks untuk tabel `transaksipenjualan`
--
ALTER TABLE `transaksipenjualan`
  ADD PRIMARY KEY (`id_transaksi_penjualan`) USING BTREE,
  ADD KEY `pegawai_foreignKey` (`id_pegawai`),
  ADD KEY `pembeli_foreignKey` (`id_pembeli`),
  ADD KEY `kurirPegawai_foreignKey` (`id_kurir`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `alamat`
--
ALTER TABLE `alamat`
  MODIFY `id_alamat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT untuk tabel `claimmerchandise`
--
ALTER TABLE `claimmerchandise`
  MODIFY `id_transaksi_claim_merchandise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `detailtransaksipenitipan`
--
ALTER TABLE `detailtransaksipenitipan`
  MODIFY `id_detail_transaksi_penitipan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `detailtransaksipenjualan`
--
ALTER TABLE `detailtransaksipenjualan`
  MODIFY `id_detail_transaksi_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT untuk tabel `diskusi`
--
ALTER TABLE `diskusi`
  MODIFY `id_forum_diskusi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jabatan`
--
ALTER TABLE `jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategoribarang`
--
ALTER TABLE `kategoribarang`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `komisi`
--
ALTER TABLE `komisi`
  MODIFY `id_komisi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `merchandise`
--
ALTER TABLE `merchandise`
  MODIFY `id_merchandise` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `organisasi`
--
ALTER TABLE `organisasi`
  MODIFY `id_organisasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `password_forgot`
--
ALTER TABLE `password_forgot`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `password_forgot_pembeli_tabel`
--
ALTER TABLE `password_forgot_pembeli_tabel`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  MODIFY `id_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT untuk tabel `pembeli`
--
ALTER TABLE `pembeli`
  MODIFY `id_pembeli` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `penitip`
--
ALTER TABLE `penitip`
  MODIFY `id_penitip` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT untuk tabel `requestdonasi`
--
ALTER TABLE `requestdonasi`
  MODIFY `id_request` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `transaksipenitipan`
--
ALTER TABLE `transaksipenitipan`
  MODIFY `id_transaksi_penitipan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `transaksipenjualan`
--
ALTER TABLE `transaksipenjualan`
  MODIFY `id_transaksi_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `alamat`
--
ALTER TABLE `alamat`
  ADD CONSTRAINT `fk_pembeli` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategoribarang` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `claimmerchandise`
--
ALTER TABLE `claimmerchandise`
  ADD CONSTRAINT `merchandise_fk` FOREIGN KEY (`id_merchandise`) REFERENCES `merchandise` (`id_merchandise`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pembeli_fk` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detailtransaksipenitipan`
--
ALTER TABLE `detailtransaksipenitipan`
  ADD CONSTRAINT `barang_fk` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `fk_transaksi_penitipan` FOREIGN KEY (`id_transaksi_penitipan`) REFERENCES `transaksipenitipan` (`id_transaksi_penitipan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detailtransaksipenjualan`
--
ALTER TABLE `detailtransaksipenjualan`
  ADD CONSTRAINT `fk_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transaksi_pembelian` FOREIGN KEY (`id_transaksi_penjualan`) REFERENCES `transaksipenjualan` (`id_transaksi_penjualan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `donasi`
--
ALTER TABLE `donasi`
  ADD CONSTRAINT `fk_request` FOREIGN KEY (`id_request`) REFERENCES `requestdonasi` (`id_request`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foreignKey_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `komisi`
--
ALTER TABLE `komisi`
  ADD CONSTRAINT `fk_pegawai` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_penitip` FOREIGN KEY (`id_penitip`) REFERENCES `penitip` (`id_penitip`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_pembelian_fk` FOREIGN KEY (`id_transaksi_penjualan`) REFERENCES `transaksipenjualan` (`id_transaksi_penjualan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `merchandise`
--
ALTER TABLE `merchandise`
  ADD CONSTRAINT `pegawai_fk` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `fk_jabatan` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `requestdonasi`
--
ALTER TABLE `requestdonasi`
  ADD CONSTRAINT `fk_organisasi` FOREIGN KEY (`id_organisasi`) REFERENCES `organisasi` (`id_organisasi`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `topseller`
--
ALTER TABLE `topseller`
  ADD CONSTRAINT `penitip_fk` FOREIGN KEY (`id_penitip`) REFERENCES `penitip` (`id_penitip`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksipenitipan`
--
ALTER TABLE `transaksipenitipan`
  ADD CONSTRAINT `pegawai_fkey` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `penitip_fkey` FOREIGN KEY (`id_penitip`) REFERENCES `penitip` (`id_penitip`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksipenjualan`
--
ALTER TABLE `transaksipenjualan`
  ADD CONSTRAINT `kurirPegawai_foreignKey` FOREIGN KEY (`id_kurir`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pegawai_foreignKey` FOREIGN KEY (`id_pegawai`) REFERENCES `pegawai` (`id_pegawai`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pembeli_foreignKey` FOREIGN KEY (`id_pembeli`) REFERENCES `pembeli` (`id_pembeli`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
