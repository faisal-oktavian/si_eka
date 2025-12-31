-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table budgeting.akun_belanja
CREATE TABLE IF NOT EXISTS `akun_belanja` (
  `idakun_belanja` int NOT NULL AUTO_INCREMENT,
  `no_rekening_akunbelanja` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nama_akun_belanja` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idakun_belanja`) USING BTREE,
  KEY `is_active` (`is_active`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `no_rekening_urusan` (`no_rekening_akunbelanja`) USING BTREE,
  KEY `nama_urusan` (`nama_akun_belanja`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.akun_belanja: ~5 rows (approximately)
INSERT INTO `akun_belanja` (`idakun_belanja`, `no_rekening_akunbelanja`, `nama_akun_belanja`, `is_active`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(6, '5.1.02.99.99.9999', 'Belanja Barang dan Jasa BLUD', 1, '2025-06-17 10:18:05', 'BucinBGMID', '2025-06-17 10:18:06', 'BucinBGMID', 1),
	(7, '5.2.02.99.99.9999', 'Belanja Modal Peralatan dan Mesin BLUD', 1, '2025-06-17 10:19:41', 'BucinBGMID', '2025-06-17 10:19:41', 'BucinBGMID', 1),
	(8, '5.2.04.99.99.9999', 'Belanja Modal Jalan, Jaringan, dan Irigasi BLUD', 1, '2025-06-17 10:21:23', 'BucinBGMID', '2025-06-17 10:24:33', 'BucinBGMID', 1),
	(9, '5.2.03.99.99.9999', 'Belanja Modal Gedung dan Bangunan BLUD', 1, '2025-06-17 10:22:41', 'BucinBGMID', '2025-07-22 19:13:35', 'superadmin', 1),
	(10, '5.2.03.99.99.9999xxxx', 'Belanja Modal Gedung dan Bangunan BLUD 25', 1, '2025-06-23 15:03:56', 'BucinBGMID', '2025-06-23 15:04:14', 'BucinBGMID', 0);

-- Dumping structure for table budgeting.bidang_urusan
CREATE TABLE IF NOT EXISTS `bidang_urusan` (
  `idbidang_urusan` int NOT NULL AUTO_INCREMENT,
  `no_rekening_bidang_urusan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nama_bidang_urusan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `idurusan_pemerintah` int DEFAULT NULL,
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idbidang_urusan`) USING BTREE,
  KEY `is_active` (`is_active`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `no_rekening_urusan` (`no_rekening_bidang_urusan`) USING BTREE,
  KEY `nama_urusan` (`nama_bidang_urusan`) USING BTREE,
  KEY `tahun_anggaran_urusan` (`idurusan_pemerintah`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.bidang_urusan: ~5 rows (approximately)
INSERT INTO `bidang_urusan` (`idbidang_urusan`, `no_rekening_bidang_urusan`, `nama_bidang_urusan`, `idurusan_pemerintah`, `is_active`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(1, '02', 'URUSAN PEMERINTAHAN BIDANG KESEHATAN', 1, 1, '2025-06-05 15:19:46', 'BucinBGMID', '2025-07-22 17:32:12', 'superadmin', 1),
	(2, '01', 'URUSAN PEMERINTAHAN BIDANG KESEHATAN', 2, 1, '2025-06-16 11:25:15', 'BucinBGMID', '2025-06-17 08:51:18', 'BucinBGMID', 1),
	(3, '02', 'URUSAN PEMERINTAHAN BIDANG KESEHATAN', 2, 0, '2025-06-16 11:29:51', 'BucinBGMID', '2025-06-17 08:51:27', 'BucinBGMID', 1),
	(4, '001', 'bidang ok', 6, 1, '2025-06-23 13:53:59', 'BucinBGMID', '2025-06-23 13:57:36', 'BucinBGMID', 0),
	(5, '0233333', 'URUSAN PEMERINTAHAN BIDANG KESEHATAN 25', 1, 1, '2025-06-23 14:14:56', 'BucinBGMID', '2025-06-23 14:21:00', 'BucinBGMID', 0);

-- Dumping structure for table budgeting.config
CREATE TABLE IF NOT EXISTS `config` (
  `idconfig` int NOT NULL AUTO_INCREMENT,
  `key` varchar(200) DEFAULT NULL,
  `value` text,
  `type` varchar(50) DEFAULT 'text',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idconfig`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table budgeting.config: ~5 rows (approximately)
INSERT INTO `config` (`idconfig`, `key`, `value`, `type`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(1, 'app_name', 'NoVaL', 'text', '2022-04-04 02:02:19', NULL, '2022-04-04 02:02:19', NULL, 1),
	(2, 'PPTK', 'dr. LUCKY MURNIASIH FITRI IKA MUHERI', 'text', NULL, NULL, NULL, NULL, 1),
	(3, 'nomor_DPA', 'DPA/A.1/1.02.0.00.0.00.01.0000/001/2025', 'text', NULL, NULL, NULL, NULL, 1),
	(4, 'tahun_anggaran', '2025', 'text', NULL, NULL, NULL, NULL, 1),
	(5, 'kode_surat_NPD', '900', 'text', NULL, NULL, NULL, NULL, 1);

-- Dumping structure for table budgeting.config_app
CREATE TABLE IF NOT EXISTS `config_app` (
  `idconfig_app` int NOT NULL AUTO_INCREMENT,
  `key` varchar(200) DEFAULT NULL,
  `value` varchar(400) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idconfig_app`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table budgeting.config_app: ~0 rows (approximately)
INSERT INTO `config_app` (`idconfig_app`, `key`, `value`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(1, 'disk_space', '20000', '2018-08-29 09:51:31', NULL, '2018-08-29 09:51:31', NULL, 1);

-- Dumping structure for table budgeting.kategori
CREATE TABLE IF NOT EXISTS `kategori` (
  `idkategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idkategori`) USING BTREE,
  KEY `is_active` (`is_active`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `nama_urusan` (`nama_kategori`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.kategori: ~25 rows (approximately)
INSERT INTO `kategori` (`idkategori`, `nama_kategori`, `is_active`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(10, 'Honorarium Dewan Pengawas BLUD : ', 1, '2025-06-19 10:19:08', 'BucinBGMID', '2025-06-19 10:19:09', 'BucinBGMID', 1),
	(11, 'Honor Pegawai BLUD (Tenaga Kesehatan) : ', 1, '2025-06-23 11:37:09', 'BucinBGMID', '2025-06-23 11:37:09', 'BucinBGMID', 1),
	(12, 'Honor tambahan bagi Pegawai BLUD : ', 1, '2025-06-23 11:37:22', 'BucinBGMID', '2025-06-23 11:37:22', 'BucinBGMID', 1),
	(13, 'Honor tambahan : ', 1, '2025-06-23 11:37:35', 'BucinBGMID', '2025-06-23 11:37:36', 'BucinBGMID', 1),
	(14, 'Honor Pegawai BLUD (Tenaga Administrasi) : ', 1, '2025-06-23 11:37:49', 'BucinBGMID', '2025-06-23 15:07:07', 'BucinBGMID', 1),
	(15, 'Honor Pegawai BLUD (Tenaga Administrasi) : ok', 1, '2025-06-23 15:08:10', 'BucinBGMID', '2025-06-23 15:08:13', 'BucinBGMID', 0),
	(16, 'RAPAT EVALUASI DAN LAYANAN :', 1, '2025-06-24 09:00:57', 'BucinBGMID', '2025-06-24 09:00:57', 'BucinBGMID', 1),
	(17, 'RAPAT SPI :', 1, '2025-06-24 09:01:15', 'BucinBGMID', '2025-06-24 09:01:16', 'BucinBGMID', 1),
	(18, 'AUDIT INTERNAL SPI :', 1, '2025-06-24 09:01:26', 'BucinBGMID', '2025-06-24 09:01:26', 'BucinBGMID', 1),
	(19, 'RAPAT INOVASI PELAYANAN RS', 1, '2025-06-24 09:01:38', 'BucinBGMID', '2025-06-24 09:01:38', 'BucinBGMID', 1),
	(20, 'RAPAT INTERNAL UKM', 1, '2025-06-24 09:01:51', 'BucinBGMID', '2025-06-24 09:01:51', 'BucinBGMID', 1),
	(21, 'RAPAT KOMITE MEDIS :', 1, '2025-06-24 09:01:59', 'BucinBGMID', '2025-06-24 09:01:59', 'BucinBGMID', 1),
	(22, 'RAPAT KOMITE KEPERAWATAN :', 1, '2025-06-24 09:02:11', 'BucinBGMID', '2025-06-24 09:02:12', 'BucinBGMID', 1),
	(23, 'RAPAT KOMITE FARMASI  DAN TERAPI', 1, '2025-06-24 09:02:19', 'BucinBGMID', '2025-06-24 09:02:19', 'BucinBGMID', 1),
	(24, 'RAPAT KOMITE PPI :', 1, '2025-06-24 09:02:41', 'BucinBGMID', '2025-06-24 09:02:41', 'BucinBGMID', 1),
	(25, 'RAPAT EVALUASI PENUNJANG NON MEDIK', 1, '2025-06-24 09:02:50', 'BucinBGMID', '2025-06-24 09:02:51', 'BucinBGMID', 1),
	(26, 'RAPAT-RAPAT PERENCANAAN, PENGANGGARAN DAN EVALUASI KINERJA PD :', 1, '2025-06-24 09:03:15', 'BucinBGMID', '2025-06-24 09:03:15', 'BucinBGMID', 1),
	(27, 'KONSUMSI KEBUGARAN PEGAWAI :', 1, '2025-06-24 09:03:24', 'BucinBGMID', '2025-06-24 09:03:25', 'BucinBGMID', 1),
	(28, 'RAPAT KOMITE KOMITE NAKES LAIN:', 1, '2025-06-24 09:03:38', 'BucinBGMID', '2025-06-24 09:03:38', 'BucinBGMID', 1),
	(29, 'Hari Besar Kesehatan', 1, '2025-06-24 10:43:32', 'BucinBGMID', '2025-06-24 10:43:32', 'BucinBGMID', 1),
	(30, 'Hari Ulang Tahun RS', 1, '2025-06-24 10:43:55', 'BucinBGMID', '2025-06-24 10:43:55', 'BucinBGMID', 1),
	(31, 'Tsyakuran HUT RSUD Sumberglagah', 1, '2025-06-24 10:44:03', 'BucinBGMID', '2025-06-24 10:44:04', 'BucinBGMID', 1),
	(32, 'Persiapan', 1, '2025-06-24 10:46:30', 'BucinBGMID', '2025-06-24 10:46:31', 'BucinBGMID', 1),
	(33, 'Pelaksanaan', 1, '2025-06-24 10:46:47', 'BucinBGMID', '2025-06-24 10:46:47', 'BucinBGMID', 1),
	(34, 'Pelaksanaan Upacara 17 Agustus', 1, '2025-06-24 10:46:56', 'BucinBGMID', '2025-06-25 22:01:45', 'BucinBGMID', 1),
	(35, 'Pemeliharaan arsitektur gedung dan bangunan pelayanan :', 1, '2025-07-10 10:21:46', 'administrator', '2025-07-10 10:21:47', 'administrator', 1),
	(36, 'Pembuatan Pondasi Penahan Tanah Depan Gedung UKM :', 1, '2025-07-19 15:51:24', 'superadmin', '2025-07-22 19:19:22', 'superadmin', 1),
	(37, 'Rehab Ruang Rapat Poli Lantai II Menjadi Ruang Komite :', 1, '2025-08-05 21:48:36', 'superadmin', '2025-08-05 21:48:37', 'superadmin', 1);

-- Dumping structure for table budgeting.kegiatan
CREATE TABLE IF NOT EXISTS `kegiatan` (
  `idkegiatan` int NOT NULL AUTO_INCREMENT,
  `no_rekening_kegiatan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nama_kegiatan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `idprogram` int DEFAULT NULL,
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idkegiatan`) USING BTREE,
  KEY `is_active` (`is_active`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `no_rekening_urusan` (`no_rekening_kegiatan`) USING BTREE,
  KEY `nama_urusan` (`nama_kegiatan`) USING BTREE,
  KEY `tahun_anggaran_urusan` (`idprogram`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.kegiatan: ~4 rows (approximately)
INSERT INTO `kegiatan` (`idkegiatan`, `no_rekening_kegiatan`, `nama_kegiatan`, `idprogram`, `is_active`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(2, '1.10', 'Peningkatan Pelayanan BLUD', 1, 1, '2025-06-16 14:49:54', 'BucinBGMID', '2025-06-17 08:52:35', 'BucinBGMID', 1),
	(3, '1.10', 'Peningkatan Pelayanan BLUD', 2, 1, '2025-06-16 15:59:17', 'BucinBGMID', '2025-07-22 17:32:22', 'superadmin', 1),
	(4, '000', 'okoko', 5, 1, '2025-06-23 14:29:40', 'BucinBGMID', '2025-06-23 14:29:52', 'BucinBGMID', 0),
	(5, '1.10', 'Peningkatan Pelayanan BLUD 2025', 2, 1, '2025-06-23 14:45:51', 'BucinBGMID', '2025-06-23 14:47:20', 'BucinBGMID', 0);

-- Dumping structure for table budgeting.kendaraan
CREATE TABLE IF NOT EXISTS `kendaraan` (
  `idkendaraan` int NOT NULL AUTO_INCREMENT,
  `nama_kendaraan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idkendaraan`) USING BTREE,
  KEY `is_active` (`is_active`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `nama_urusan` (`nama_kendaraan`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.kendaraan: ~6 rows (approximately)
INSERT INTO `kendaraan` (`idkendaraan`, `nama_kendaraan`, `is_active`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(47, 'Ambulance Hyundai merah', 1, '2025-07-08 18:00:56', 'administrator', '2025-07-08 18:00:56', 'administrator', 1),
	(48, 'Ambulance Hyundai biru', 1, '2025-07-08 18:01:02', 'administrator', '2025-07-08 18:01:03', 'administrator', 1),
	(49, 'Kijang LSX', 1, '2025-07-08 18:01:10', 'administrator', '2025-07-08 18:01:11', 'administrator', 1),
	(50, 'Panther', 1, '2025-07-08 18:02:35', 'administrator', '2025-07-08 18:02:35', 'administrator', 1),
	(51, 'Inova Lama', 1, '2025-07-08 18:02:45', 'administrator', '2025-07-08 18:02:46', 'administrator', 1),
	(52, 'Inova Reborn', 1, '2025-07-08 18:03:03', 'administrator', '2025-07-22 19:34:03', 'superadmin', 1);

-- Dumping structure for table budgeting.npd
CREATE TABLE IF NOT EXISTS `npd` (
  `idnpd` int NOT NULL AUTO_INCREMENT,
  `npd_date_created` datetime DEFAULT NULL COMMENT 'tanggal input npd',
  `confirm_payment_date` datetime DEFAULT NULL COMMENT 'tanggal pembayaran',
  `npd_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'nomor dokumen',
  `no_urut` varchar(50) DEFAULT NULL COMMENT 'no urut data',
  `npd_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'DRAFT' COMMENT 'INPUT DATA 		-> data diinputkan dimenu realisasi anggaran (oleh user realisasi); \r\nMENUNGGU VERIFIKASI 	-> data diinputkan di menu verifikasi dokumen (oleh user realisasi); \r\nSUDAH DIVERIFIKASI 	-> data sudah diverifikasi (oleh user verifikator); \r\nINPUT NPD		-> data diinputkan di menu npd (oleh user npd);\r\nMENUNGGU PEMBAYARAN	-> data sudah dikirim ke bendahara (oleh user npd);\r\nSUDAH DIBAYAR BENDAHARA -> data sudah dibayar bendahara (oleh user bendahara);',
  `iduser_created` bigint DEFAULT NULL COMMENT 'user yang membuat dokumen',
  `iduser_payment` bigint DEFAULT NULL COMMENT 'user yang membayar anggaran',
  `payment_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `total_anggaran` double DEFAULT NULL,
  `total_cash` double DEFAULT NULL,
  `total_debet` double DEFAULT NULL,
  `total_credit` double DEFAULT NULL,
  `total_transfer` double DEFAULT NULL,
  `debet_number` double DEFAULT NULL,
  `credit_number` double DEFAULT NULL,
  `transfer_number` double DEFAULT NULL,
  `total_pay` double DEFAULT NULL,
  `total_debt` double DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idnpd`),
  KEY `npd_date_created` (`npd_date_created`),
  KEY `confirm_payment_date` (`confirm_payment_date`),
  KEY `npd_code` (`npd_code`),
  KEY `npd_status` (`npd_status`),
  KEY `iduser_created` (`iduser_created`),
  KEY `iduser_payment` (`iduser_payment`),
  KEY `total_anggaran` (`total_anggaran`),
  KEY `total_cash` (`total_cash`),
  KEY `total_debet` (`total_debet`),
  KEY `total_credit` (`total_credit`),
  KEY `total_transfer` (`total_transfer`),
  KEY `debet_number` (`debet_number`),
  KEY `credit_number` (`credit_number`),
  KEY `transfer_number` (`transfer_number`),
  KEY `total_pay` (`total_pay`),
  KEY `total_debt` (`total_debt`),
  KEY `status` (`status`),
  KEY `no_urut` (`no_urut`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.npd: ~10 rows (approximately)
INSERT INTO `npd` (`idnpd`, `npd_date_created`, `confirm_payment_date`, `npd_code`, `no_urut`, `npd_status`, `iduser_created`, `iduser_payment`, `payment_description`, `total_anggaran`, `total_cash`, `total_debet`, `total_credit`, `total_transfer`, `debet_number`, `credit_number`, `transfer_number`, `total_pay`, `total_debt`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(8, '2025-08-15 11:19:47', NULL, '900/08.01/PPTK/2025', '1', 'DRAFT', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-15 11:19:47', 'npd', '2025-08-15 11:19:47', 'npd', 1),
	(9, '2025-08-15 11:19:52', NULL, '900/08.02/PPTK/2025', '2', 'DRAFT', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-15 11:19:52', 'npd', '2025-08-15 11:19:53', 'npd', 1),
	(10, '2025-08-15 11:21:02', NULL, '900/08.03/PPTK/2025', '3', 'INPUT NPD', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-15 11:21:14', 'npd', '2025-08-15 14:38:30', 'npd', 1),
	(11, '2025-08-15 13:39:06', NULL, '900/08.04/PPTK/2025', '4', 'DRAFT', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-15 13:39:06', 'npd', '2025-08-15 13:39:07', 'npd', 1),
	(12, '2025-08-15 13:39:07', NULL, '900/08.05/PPTK/2025', '5', 'DRAFT', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-15 13:39:07', 'npd', '2025-08-15 13:39:08', 'npd', 1),
	(13, '2025-08-15 13:39:12', NULL, '900/08.06/PPTK/2025', '6', 'DRAFT', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-15 13:39:12', 'npd', '2025-08-15 13:39:13', 'npd', 1),
	(15, '2025-08-15 14:38:46', NULL, '900/08.07/PPTK/2025', '7', 'INPUT NPD', 12, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-15 14:38:51', 'npd', '2025-08-15 14:38:52', 'npd', 1),
	(16, '2025-08-15 14:40:00', '2025-08-15 14:44:30', '900/08.08/PPTK/2025', '8', 'SUDAH DIBAYAR BENDAHARA', 12, 9, 'lunas', 809343590, 0, 0, 0, 809343590, NULL, NULL, NULL, 809343590, 0, '2025-08-15 14:40:04', 'npd', '2025-08-15 14:44:37', 'bendahara', 1),
	(17, '2025-08-15 14:40:23', '2025-08-15 14:44:02', '900/08.09/PPTK/2025', '9', 'SUDAH DIBAYAR BENDAHARA', 12, 9, 'langsung lunas', 1375829926, 0, 0, 0, 1375829926, NULL, NULL, NULL, 1375829926, 0, '2025-08-15 14:40:26', 'npd', '2025-08-15 14:44:04', 'bendahara', 1),
	(18, '2025-08-15 14:41:31', NULL, '900/08.10/PPTK/2025', '10', 'MENUNGGU PEMBAYARAN', 12, NULL, NULL, 597792882, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-15 14:41:34', 'npd', '2025-08-15 14:42:49', 'npd', 1);

-- Dumping structure for table budgeting.npd_detail
CREATE TABLE IF NOT EXISTS `npd_detail` (
  `idnpd_detail` bigint NOT NULL AUTO_INCREMENT,
  `idnpd` int NOT NULL,
  `idverification` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idnpd_detail`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `idverification` (`idverification`) USING BTREE,
  KEY `idnpd` (`idnpd`),
  CONSTRAINT `npd_detail_ibfk_1` FOREIGN KEY (`idnpd`) REFERENCES `verification` (`idverification`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.npd_detail: ~9 rows (approximately)
INSERT INTO `npd_detail` (`idnpd_detail`, `idnpd`, `idverification`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(12, 10, 20, '2025-08-15 11:21:14', 'npd', '2025-08-15 11:21:14', 'npd', 1),
	(13, 11, 26, '2025-08-15 13:39:06', 'superadmin', '2025-08-15 13:39:07', 'superadmin', 1),
	(14, 12, 26, '2025-08-15 13:39:07', 'superadmin', '2025-08-15 13:39:08', 'superadmin', 1),
	(15, 13, 26, '2025-08-15 13:39:12', 'superadmin', '2025-08-15 13:39:13', 'superadmin', 1),
	(16, 14, 26, '2025-08-15 13:39:28', 'superadmin', '2025-08-15 13:39:29', 'superadmin', 1),
	(17, 15, 15, '2025-08-15 14:38:51', 'superadmin', '2025-08-15 14:38:51', 'superadmin', 1),
	(18, 16, 17, '2025-08-15 14:40:04', 'superadmin', '2025-08-15 14:40:04', 'superadmin', 1),
	(19, 17, 12, '2025-08-15 14:40:26', 'superadmin', '2025-08-15 14:40:27', 'superadmin', 1),
	(20, 18, 16, '2025-08-15 14:41:34', 'superadmin', '2025-08-15 14:41:34', 'superadmin', 1);

-- Dumping structure for table budgeting.paket_belanja
CREATE TABLE IF NOT EXISTS `paket_belanja` (
  `idpaket_belanja` int NOT NULL AUTO_INCREMENT,
  `nama_paket_belanja` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nilai_anggaran` double DEFAULT NULL,
  `status_paket_belanja` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'DRAFT' COMMENT 'DRAFT / OK; draft : transaksi belum disimpan (trx baru), OK : transaksi pernah disimpan',
  `idsub_kegiatan` int DEFAULT NULL,
  `idkegiatan` int DEFAULT NULL,
  `idprogram` int DEFAULT NULL,
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idpaket_belanja`) USING BTREE,
  KEY `is_active` (`is_active`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `nama_urusan` (`nama_paket_belanja`) USING BTREE,
  KEY `tahun_anggaran_urusan` (`idsub_kegiatan`) USING BTREE,
  KEY `idprogram` (`idprogram`),
  KEY `idkategori` (`idkegiatan`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.paket_belanja: ~59 rows (approximately)
INSERT INTO `paket_belanja` (`idpaket_belanja`, `nama_paket_belanja`, `nilai_anggaran`, `status_paket_belanja`, `idsub_kegiatan`, `idkegiatan`, `idprogram`, `is_active`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(18, NULL, NULL, NULL, 2, 2, 1, 1, '2025-06-19 07:53:37', 'superadmin', '2025-06-19 07:53:38', 'superadmin', 1),
	(19, 'Honorarium Tim Pengadaan, Pengelola Keuangan, Barang Daerah, dan Sistem Informasi ASN', 51327000, 'OK', 2, 2, 1, 1, '2025-06-19 07:59:02', 'superadmin', '2025-08-01 08:56:55', 'superadmin', 1),
	(20, 'Pembinaan Dewan Pengawas pada BLUD', 66680000, 'OK', 2, 2, 1, 1, '2025-06-19 08:44:05', 'superadmin', '2025-08-01 09:00:58', 'superadmin', 1),
	(21, 'Penyediaan Jasa Surat Menyurat', 40600000, 'OK', 2, 2, 1, 1, '2025-06-19 08:48:44', 'superadmin', '2025-08-01 09:04:16', 'superadmin', 1),
	(22, 'Aksesibilitas penderita kusta dan disabilitas', 18000000, 'OK', 3, 3, 2, 1, '2025-06-19 08:49:22', 'superadmin', '2025-08-01 09:08:35', 'superadmin', 1),
	(23, 'Belanja Jasa Pegawai Tidak Tetap BLUD', 4252228200, 'OK', 2, 2, 1, 1, '2025-06-19 08:50:16', 'superadmin', '2025-08-01 09:29:41', 'superadmin', 1),
	(24, 'Fasilitasi Kunjungan Tamu', 33500000, 'OK', 2, 2, 1, 1, '2025-06-19 08:51:16', 'superadmin', '2025-08-01 09:33:17', 'superadmin', 1),
	(25, 'Kegiatan PKRS', 103576000, 'OK', 2, 2, 1, 1, '2025-06-19 09:42:24', 'superadmin', '2025-08-05 17:01:26', 'superadmin', 1),
	(26, 'Outsoursing Cleaning Service', 2422572000, 'OK', 2, 2, 1, 1, '2025-06-19 09:43:04', 'superadmin', '2025-08-05 17:05:24', 'superadmin', 1),
	(27, 'Outsoursing Satpam', 873408000, 'OK', 2, 2, 1, 1, '2025-06-19 09:43:34', 'superadmin', '2025-08-05 17:08:33', 'superadmin', 1),
	(28, 'Pelaksanaan Rapat-rapat Internal, evaluasi, layanan dan Inovasi Pelayanan Publik Rumah Sakit', 152235000, 'OK', 2, 2, 1, 1, '2025-06-19 09:44:10', 'superadmin', '2025-08-05 17:24:39', 'superadmin', 1),
	(29, 'Pembiayaan Listrik, Air, Telepon RS', 1609095500, 'OK', 2, 2, 1, 1, '2025-06-19 09:44:45', 'superadmin', '2025-08-05 17:30:01', 'superadmin', 1),
	(30, 'Peringatan Hari Besar', 100025000, 'OK', 2, 2, 1, 1, '2025-06-19 09:45:16', 'superadmin', '2025-08-05 17:34:57', 'superadmin', 1),
	(31, 'Pemeliharaan dan atau penyediaan suku cadang alat - alat kesehatan RS', 2171625000, 'OK', 2, 2, 1, 1, '2025-06-19 09:45:52', 'superadmin', '2025-08-05 19:09:00', 'superadmin', 1),
	(32, 'Pemeliharaan dan atau penyediaan suku cadang peralatan RS', 848900000, 'OK', 2, 2, 1, 1, '2025-06-19 09:46:22', 'superadmin', '2025-08-05 20:34:17', 'superadmin', 1),
	(33, 'Pendampingan dan Evaluasi Sistem Manajemen Rumah Sakit', 76371000, 'OK', 2, 2, 1, 1, '2025-06-19 09:46:55', 'superadmin', '2025-06-19 09:46:57', 'superadmin', 1),
	(34, 'Pendidikan dan Pelatihan Pegawai', 0, 'OK', 2, 2, 1, 1, '2025-06-19 09:47:32', 'superadmin', '2025-06-25 08:56:05', 'superadmin', 1),
	(35, 'Pengadaan Peralatan / Perlengkapan rumah tangga RS', 0, 'OK', 2, 2, 1, 1, '2025-06-19 09:48:10', 'superadmin', '2025-06-19 09:48:27', 'superadmin', 1),
	(36, 'Pertemuan Sosialisasi Program RS dengan FKTP', 10100000, 'OK', 2, 2, 1, 1, '2025-06-19 09:48:50', 'superadmin', '2025-08-05 20:42:59', 'superadmin', 1),
	(37, 'Penyediaan Bahan Logistik Kantor', 0, 'OK', 2, 2, 1, 1, '2025-06-19 09:49:22', 'superadmin', '2025-06-19 09:49:30', 'superadmin', 1),
	(38, 'Penyediaan operasional pelayanan rehabilitasi dan keterapian fisik', 0, 'OK', 2, 2, 1, 1, '2025-06-19 09:50:12', 'superadmin', '2025-06-19 09:50:15', 'superadmin', 1),
	(39, 'Penyediaan barang cetakan, penggandaan dan dokumentasi publikasi', 0, 'OK', 2, 2, 1, 1, '2025-06-19 09:50:47', 'superadmin', '2025-06-19 09:50:56', 'superadmin', 1),
	(40, 'Penyediaan biaya jasa administrasi, perijinan dan sertifikasi', 0, 'OK', 2, 2, 1, 1, '2025-06-19 09:51:20', 'superadmin', '2025-06-19 09:51:31', 'superadmin', 1),
	(41, 'Penyediaan biaya operasional kendaraan', 0, 'OK', 2, 2, 1, 1, '2025-06-19 09:51:59', 'superadmin', '2025-06-19 09:52:02', 'superadmin', 1),
	(42, 'Jasa Pelayanan Rumah Sakit', 24914240000, 'OK', 2, 2, 1, 1, '2025-06-19 09:52:32', 'superadmin', '2025-08-05 20:47:46', 'superadmin', 1),
	(43, 'Penyediaan jasa operasional', 0, 'OK', 2, 2, 1, 1, '2025-06-19 09:53:07', 'superadmin', '2025-06-19 09:53:13', 'superadmin', 1),
	(44, 'Penyediaan Operasional RS', 0, 'OK', 2, 2, 1, 1, '2025-06-19 09:53:38', 'superadmin', '2025-06-19 09:53:45', 'superadmin', 1),
	(45, 'Penyediaan makan minum harian pasien', 2677680000, 'OK', 2, 2, 1, 1, '2025-06-19 09:54:12', 'superadmin', '2025-08-05 20:51:30', 'superadmin', 1),
	(46, 'Penyelenggaraan Rapat Koordinasi dan Konsultasi', 0, 'OK', 2, 2, 1, 1, '2025-06-19 09:54:38', 'superadmin', '2025-06-19 09:54:42', 'superadmin', 1),
	(47, 'Penyusunan, Pengembangan, Pemeliharaan dan Pelaksanaan Sistem Informasi Kesehatan Rumah Sakit', 0, 'OK', 2, 2, 1, 1, '2025-06-19 09:55:12', 'superadmin', '2025-06-19 09:55:35', 'superadmin', 1),
	(48, 'Perawatan Jenazah', 0, 'OK', 2, 2, 1, 1, '2025-06-19 09:56:12', 'superadmin', '2025-06-19 09:56:16', 'superadmin', 1),
	(49, 'Pemasaran RS', 0, 'OK', 2, 2, 1, 1, '2025-06-19 09:56:39', 'superadmin', '2025-06-19 09:56:49', 'superadmin', 1),
	(50, 'Khitan Masal', 0, 'OK', 2, 2, 1, 1, '2025-06-19 09:57:15', 'superadmin', '2025-06-19 09:57:22', 'superadmin', 1),
	(51, 'Inhouse training dalam rangka peningkatan kompetensi pegawai', 27500000, 'OK', 2, 2, 1, 1, '2025-06-19 09:57:53', 'superadmin', '2025-08-05 21:15:06', 'superadmin', 1),
	(52, 'Sosialisasi Kepegawaian', 60750000, 'OK', 2, 2, 1, 1, '2025-06-19 10:00:15', 'superadmin', '2025-08-05 21:19:11', 'superadmin', 1),
	(53, 'Gelar Pameran', 62520000, 'OK', 2, 2, 1, 1, '2025-06-19 10:01:19', 'superadmin', '2025-08-05 21:24:01', 'superadmin', 1),
	(54, 'Sediaan Farmasi, Alat Kesehatan dan Bahan Medis Habis Pakai', 12830342854, 'OK', 2, 2, 1, 1, '2025-06-19 10:01:55', 'superadmin', '2025-08-05 21:29:50', 'superadmin', 1),
	(55, 'Pengadaan alat kesehatan rumah sakit', 0, 'OK', 2, 2, 1, 1, '2025-06-19 10:04:18', 'superadmin', '2025-06-19 10:04:32', 'superadmin', 1),
	(56, 'Pengadaan Pakaian Dinas beserta Atribut Kelengkapannya', 320400000, 'OK', 2, 2, 1, 1, '2025-06-19 10:05:11', 'superadmin', '2025-08-05 21:31:24', 'superadmin', 1),
	(57, 'Pemasangan Kembali Membrane Lama Area Pakir Poli Rawat Jalan', 58000000, 'OK', 2, 2, 1, 1, '2025-06-19 10:05:36', 'superadmin', '2025-08-05 21:33:28', 'superadmin', 1),
	(58, 'Penyusunan Dokumen Studi Kelayakan Pengembangan Pelayanan', 100000000, 'OK', 2, 2, 1, 1, '2025-06-19 10:06:08', 'superadmin', '2025-08-05 22:02:23', 'superadmin', 1),
	(59, 'Pembinaan, Koordinasi dan evaluasi BLUD', 79200000, 'OK', 2, 2, 1, 1, '2025-06-19 10:06:46', 'superadmin', '2025-08-05 21:35:57', 'superadmin', 1),
	(60, 'Perubahan Dinding Partisi Ruang Fisotherapi', 2500000, 'OK', 2, 2, 1, 1, '2025-06-19 10:07:17', 'superadmin', '2025-07-29 13:47:29', 'superadmin', 1),
	(61, 'Kegiatan RS Pendidikan', 15000000, 'OK', 2, 2, 1, 1, '2025-06-19 10:07:45', 'superadmin', '2025-08-05 21:40:40', 'superadmin', 1),
	(62, 'Audit Umum atas Laporan Keuangan RSUD Sumberglagah Tahun 2024', 85000000, 'OK', 2, 2, 1, 1, '2025-06-19 10:08:16', 'superadmin', '2025-08-05 21:45:41', 'superadmin', 1),
	(63, 'Sosialisasi Perpajakan (SPT Pajak Tahunan)', 13250000, 'OK', 2, 2, 1, 1, '2025-06-19 10:08:42', 'superadmin', '2025-08-05 21:47:29', 'superadmin', 1),
	(64, 'Rehab Ruang Rapat Poli Lantai II Menjadi Ruang Komite', 103299000, 'OK', 2, 2, 1, 1, '2025-06-19 10:09:17', 'superadmin', '2025-08-05 21:49:52', 'superadmin', 1),
	(65, 'Pembuatan Pondasi Penahan Tanah Depan Gedung UKM', 119129000, 'OK', 2, 2, 1, 1, '2025-06-19 10:09:57', 'superadmin', '2025-08-05 21:51:21', 'superadmin', 1),
	(66, 'Pelatihan Manajemen SPI Rumah Sakit', 10000000, 'OK', 2, 2, 1, 1, '2025-06-19 10:10:21', 'superadmin', '2025-07-19 15:50:51', 'superadmin', 1),
	(67, 'Rehap Gedung Rawat Inap Anggrek Tahap II', 0, 'OK', 2, 2, 1, 1, '2025-06-19 10:10:46', 'superadmin', '2025-06-19 10:11:03', 'superadmin', 1),
	(68, 'Pemeliharaan Sipil, Arsitektur Gedung dan Bangunan Pelayanan', 237800000, 'OK', 2, 2, 1, 1, '2025-06-19 10:11:34', 'superadmin', '2025-08-05 21:52:39', 'superadmin', 1),
	(69, 'Pemeliharaan arsitektur gedung dan bangunan pelayanan', 4099440000, 'OK', 2, 2, 1, 1, '2025-06-19 10:12:32', 'superadmin', '2025-08-05 21:53:28', 'superadmin', 1),
	(70, NULL, 0, 'DRAFT', NULL, NULL, NULL, 1, '2025-07-01 18:51:29', 'superadmin', '2025-07-01 18:51:30', 'superadmin', 1),
	(71, NULL, 0, 'DRAFT', NULL, NULL, NULL, 1, '2025-07-08 20:08:09', 'superadmin', '2025-07-08 20:08:09', 'superadmin', 1);

-- Dumping structure for table budgeting.paket_belanja_detail
CREATE TABLE IF NOT EXISTS `paket_belanja_detail` (
  `idpaket_belanja_detail` int NOT NULL AUTO_INCREMENT,
  `idakun_belanja` int DEFAULT NULL,
  `idpaket_belanja` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idpaket_belanja_detail`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `tahun_anggaran_urusan` (`idpaket_belanja`) USING BTREE,
  KEY `nama_urusan` (`idakun_belanja`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.paket_belanja_detail: ~69 rows (approximately)
INSERT INTO `paket_belanja_detail` (`idpaket_belanja_detail`, `idakun_belanja`, `idpaket_belanja`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(19, 6, 18, '2025-06-19 07:53:37', 'superadmin', '2025-06-19 07:53:38', 'superadmin', 1),
	(20, 6, 19, '2025-06-19 07:59:02', 'superadmin', '2025-07-05 11:34:18', 'superadmin', 1),
	(21, 9, 19, '2025-06-19 07:59:13', 'superadmin', '2025-06-19 08:01:06', 'superadmin', 0),
	(22, 6, 20, '2025-06-19 08:44:05', 'superadmin', '2025-06-19 08:44:06', 'superadmin', 1),
	(23, 6, 20, '2025-06-19 08:44:09', 'superadmin', '2025-06-19 08:44:10', 'superadmin', 1),
	(24, 6, 21, '2025-06-19 08:48:44', 'superadmin', '2025-06-19 08:48:44', 'superadmin', 1),
	(25, 6, 22, '2025-06-19 08:49:22', 'superadmin', '2025-06-19 08:49:22', 'superadmin', 1),
	(26, 6, 23, '2025-06-19 08:50:16', 'superadmin', '2025-06-19 08:50:16', 'superadmin', 1),
	(27, 6, 24, '2025-06-19 08:51:16', 'superadmin', '2025-06-19 08:51:17', 'superadmin', 1),
	(28, 7, 24, '2025-06-19 08:51:21', 'superadmin', '2025-06-19 09:41:38', 'superadmin', 0),
	(29, 6, 25, '2025-06-19 09:42:24', 'superadmin', '2025-06-19 09:42:25', 'superadmin', 1),
	(30, 7, 25, '2025-06-19 09:42:30', 'superadmin', '2025-06-19 09:42:30', 'superadmin', 1),
	(31, 6, 26, '2025-06-19 09:43:04', 'superadmin', '2025-06-19 09:43:04', 'superadmin', 1),
	(32, 6, 27, '2025-06-19 09:43:34', 'superadmin', '2025-06-19 09:43:35', 'superadmin', 1),
	(33, 6, 28, '2025-06-19 09:44:10', 'superadmin', '2025-06-19 09:44:11', 'superadmin', 1),
	(34, 6, 29, '2025-06-19 09:44:45', 'superadmin', '2025-06-19 09:44:45', 'superadmin', 1),
	(35, 6, 30, '2025-06-19 09:45:16', 'superadmin', '2025-06-19 09:45:16', 'superadmin', 1),
	(36, 6, 31, '2025-06-19 09:45:52', 'superadmin', '2025-06-19 09:45:52', 'superadmin', 1),
	(37, 6, 32, '2025-06-19 09:46:22', 'superadmin', '2025-06-19 09:46:22', 'superadmin', 1),
	(38, 6, 33, '2025-06-19 09:46:55', 'superadmin', '2025-06-19 09:46:56', 'superadmin', 1),
	(39, 6, 34, '2025-06-19 09:47:32', 'superadmin', '2025-06-25 08:56:05', 'superadmin', 1),
	(40, 6, 35, '2025-06-19 09:48:10', 'superadmin', '2025-06-19 09:48:10', 'superadmin', 1),
	(41, 7, 35, '2025-06-19 09:48:20', 'superadmin', '2025-06-19 09:48:21', 'superadmin', 1),
	(42, 6, 36, '2025-06-19 09:48:50', 'superadmin', '2025-06-19 09:48:50', 'superadmin', 1),
	(43, 6, 37, '2025-06-19 09:49:22', 'superadmin', '2025-06-19 09:49:23', 'superadmin', 1),
	(44, 6, 38, '2025-06-19 09:50:12', 'superadmin', '2025-06-19 09:50:12', 'superadmin', 1),
	(45, 6, 39, '2025-06-19 09:50:47', 'superadmin', '2025-06-19 09:50:47', 'superadmin', 1),
	(46, 6, 40, '2025-06-19 09:51:20', 'superadmin', '2025-06-19 09:51:21', 'superadmin', 1),
	(47, 6, 41, '2025-06-19 09:51:59', 'superadmin', '2025-06-19 09:52:00', 'superadmin', 1),
	(48, 6, 42, '2025-06-19 09:52:32', 'superadmin', '2025-06-19 09:52:32', 'superadmin', 1),
	(49, 6, 43, '2025-06-19 09:53:07', 'superadmin', '2025-06-19 09:53:08', 'superadmin', 1),
	(50, 6, 44, '2025-06-19 09:53:38', 'superadmin', '2025-06-19 09:53:38', 'superadmin', 1),
	(51, 6, 45, '2025-06-19 09:54:12', 'superadmin', '2025-06-19 09:54:13', 'superadmin', 1),
	(52, 6, 46, '2025-06-19 09:54:38', 'superadmin', '2025-06-19 09:54:38', 'superadmin', 1),
	(53, 6, 47, '2025-06-19 09:55:12', 'superadmin', '2025-06-19 09:55:12', 'superadmin', 1),
	(54, 7, 47, '2025-06-19 09:55:21', 'superadmin', '2025-06-19 09:55:21', 'superadmin', 1),
	(55, 8, 47, '2025-06-19 09:55:31', 'superadmin', '2025-06-19 09:55:31', 'superadmin', 1),
	(56, 6, 48, '2025-06-19 09:56:12', 'superadmin', '2025-06-19 09:56:13', 'superadmin', 1),
	(57, 6, 49, '2025-06-19 09:56:39', 'superadmin', '2025-06-19 09:56:40', 'superadmin', 1),
	(58, 7, 49, '2025-06-19 09:56:46', 'superadmin', '2025-06-19 09:56:47', 'superadmin', 1),
	(59, 6, 50, '2025-06-19 09:57:15', 'superadmin', '2025-06-19 09:57:15', 'superadmin', 1),
	(60, 6, 51, '2025-06-19 09:57:53', 'superadmin', '2025-06-19 09:57:53', 'superadmin', 1),
	(61, 6, 52, '2025-06-19 10:00:15', 'superadmin', '2025-06-19 10:00:15', 'superadmin', 1),
	(62, 6, 53, '2025-06-19 10:01:19', 'superadmin', '2025-06-19 10:01:20', 'superadmin', 1),
	(63, 6, 54, '2025-06-19 10:01:55', 'superadmin', '2025-06-19 10:01:55', 'superadmin', 1),
	(64, 6, 55, '2025-06-19 10:04:18', 'superadmin', '2025-06-19 10:04:19', 'superadmin', 1),
	(65, 7, 55, '2025-06-19 10:04:26', 'superadmin', '2025-06-19 10:04:27', 'superadmin', 1),
	(66, 6, 56, '2025-06-19 10:05:11', 'superadmin', '2025-06-19 10:05:12', 'superadmin', 1),
	(67, 6, 57, '2025-06-19 10:05:36', 'superadmin', '2025-06-19 10:05:37', 'superadmin', 1),
	(68, 6, 58, '2025-06-19 10:06:08', 'superadmin', '2025-06-19 10:06:09', 'superadmin', 1),
	(69, 6, 59, '2025-06-19 10:06:46', 'superadmin', '2025-06-19 10:06:47', 'superadmin', 1),
	(70, 6, 60, '2025-06-19 10:07:17', 'superadmin', '2025-06-19 10:07:18', 'superadmin', 1),
	(71, 6, 61, '2025-06-19 10:07:45', 'superadmin', '2025-06-19 10:07:46', 'superadmin', 1),
	(72, 7, 61, '2025-06-19 10:07:51', 'superadmin', '2025-06-19 10:07:51', 'superadmin', 1),
	(73, 6, 62, '2025-06-19 10:08:16', 'superadmin', '2025-06-19 10:08:17', 'superadmin', 1),
	(74, 6, 63, '2025-06-19 10:08:42', 'superadmin', '2025-06-19 10:08:42', 'superadmin', 1),
	(75, 6, 64, '2025-06-19 10:09:17', 'superadmin', '2025-06-19 10:09:17', 'superadmin', 1),
	(76, 8, 65, '2025-06-19 10:09:57', 'superadmin', '2025-06-19 10:09:58', 'superadmin', 1),
	(77, 6, 66, '2025-06-19 10:10:21', 'superadmin', '2025-06-19 10:10:22', 'superadmin', 1),
	(78, 6, 67, '2025-06-19 10:10:46', 'superadmin', '2025-06-19 10:10:47', 'superadmin', 1),
	(79, 9, 67, '2025-06-19 10:10:52', 'superadmin', '2025-06-19 10:10:52', 'superadmin', 1),
	(80, 6, 68, '2025-06-19 10:11:34', 'superadmin', '2025-06-19 10:11:34', 'superadmin', 1),
	(81, 6, 69, '2025-06-19 10:12:32', 'superadmin', '2025-06-19 10:12:32', 'superadmin', 1),
	(82, 7, 20, '2025-06-26 11:36:14', 'superadmin', '2025-07-08 20:16:44', 'superadmin', 0),
	(83, 6, 19, '2025-07-05 11:19:57', 'superadmin', '2025-07-05 11:20:04', 'superadmin', 0),
	(84, 6, 69, '2025-07-10 10:18:47', 'superadmin', '2025-07-10 10:21:24', 'superadmin', 0);

-- Dumping structure for table budgeting.paket_belanja_detail_sub
CREATE TABLE IF NOT EXISTS `paket_belanja_detail_sub` (
  `idpaket_belanja_detail_sub` int NOT NULL AUTO_INCREMENT,
  `idkategori` int DEFAULT NULL,
  `idsub_kategori` int DEFAULT NULL,
  `idpaket_belanja_detail` int DEFAULT NULL COMMENT 'turunan dari tabel paket_belanja detail berdasarkan idpaket_belanja_detail',
  `is_idpaket_belanja_detail_sub` int DEFAULT NULL COMMENT 'terisi jika data ini turunan dari kategori, mengacu pada kolom idpaket_belanja_detail_sub',
  `volume` double DEFAULT NULL,
  `idsatuan` int DEFAULT NULL,
  `harga_satuan` double DEFAULT NULL,
  `jumlah` double DEFAULT NULL,
  `is_kategori` int DEFAULT NULL COMMENT 'bernilai 1 jika termasuk kategori',
  `is_subkategori` int DEFAULT NULL COMMENT 'bernilai 1 jika termasuk sub kategori',
  `rak_volume_januari` double DEFAULT NULL,
  `rak_jumlah_januari` double DEFAULT NULL,
  `rak_volume_februari` double DEFAULT NULL,
  `rak_jumlah_februari` double DEFAULT NULL,
  `rak_volume_maret` double DEFAULT NULL,
  `rak_jumlah_maret` double DEFAULT NULL,
  `rak_volume_april` double DEFAULT NULL,
  `rak_jumlah_april` double DEFAULT NULL,
  `rak_volume_mei` double DEFAULT NULL,
  `rak_jumlah_mei` double DEFAULT NULL,
  `rak_volume_juni` double DEFAULT NULL,
  `rak_jumlah_juni` double DEFAULT NULL,
  `rak_volume_juli` double DEFAULT NULL,
  `rak_jumlah_juli` double DEFAULT NULL,
  `rak_volume_agustus` double DEFAULT NULL,
  `rak_jumlah_agustus` double DEFAULT NULL,
  `rak_volume_september` double DEFAULT NULL,
  `rak_jumlah_september` double DEFAULT NULL,
  `rak_volume_oktober` double DEFAULT NULL,
  `rak_jumlah_oktober` double unsigned DEFAULT NULL,
  `rak_volume_november` double DEFAULT NULL,
  `rak_jumlah_november` double DEFAULT NULL,
  `rak_volume_desember` double DEFAULT NULL,
  `rak_jumlah_desember` double DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idpaket_belanja_detail_sub`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `nama_urusan` (`idkategori`) USING BTREE,
  KEY `is_kategori` (`is_kategori`),
  KEY `is_subkategori` (`is_subkategori`),
  KEY `idsub_kategori` (`idsub_kategori`),
  KEY `idakun_belanja` (`idpaket_belanja_detail`) USING BTREE,
  KEY `volume` (`volume`),
  KEY `idsatuan` (`idsatuan`),
  KEY `harga_satuan` (`harga_satuan`),
  KEY `jumlah` (`jumlah`),
  KEY `is_idpaket_belanja_detail_sub` (`is_idpaket_belanja_detail_sub`)
) ENGINE=InnoDB AUTO_INCREMENT=356 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.paket_belanja_detail_sub: ~234 rows (approximately)
INSERT INTO `paket_belanja_detail_sub` (`idpaket_belanja_detail_sub`, `idkategori`, `idsub_kategori`, `idpaket_belanja_detail`, `is_idpaket_belanja_detail_sub`, `volume`, `idsatuan`, `harga_satuan`, `jumlah`, `is_kategori`, `is_subkategori`, `rak_volume_januari`, `rak_jumlah_januari`, `rak_volume_februari`, `rak_jumlah_februari`, `rak_volume_maret`, `rak_jumlah_maret`, `rak_volume_april`, `rak_jumlah_april`, `rak_volume_mei`, `rak_jumlah_mei`, `rak_volume_juni`, `rak_jumlah_juni`, `rak_volume_juli`, `rak_jumlah_juli`, `rak_volume_agustus`, `rak_jumlah_agustus`, `rak_volume_september`, `rak_jumlah_september`, `rak_volume_oktober`, `rak_jumlah_oktober`, `rak_volume_november`, `rak_jumlah_november`, `rak_volume_desember`, `rak_jumlah_desember`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(86, NULL, 16, 20, NULL, 24, 29, 495000, 11880000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 3960000, 4, 1980000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 09:19:39', 'superadmin', '2025-08-01 08:56:17', 'superadmin', 1),
	(87, NULL, 17, 20, NULL, 12, 29, 1547250, 18567000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 6189000, 2, 3094500, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 09:26:06', 'superadmin', '2025-08-01 08:56:25', 'superadmin', 1),
	(88, NULL, 18, 20, NULL, 12, 29, 1090000, 13080000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 4360000, 2, 2180000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 09:26:32', 'superadmin', '2025-08-01 08:56:32', 'superadmin', 1),
	(89, NULL, 19, 20, NULL, 24, 29, 125000, 3000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 1000000, 4, 500000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 09:27:03', 'superadmin', '2025-08-01 08:56:41', 'superadmin', 1),
	(90, NULL, 20, 20, NULL, 24, 29, 100000, 2400000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 800000, 4, 400000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 09:27:30', 'superadmin', '2025-08-01 08:56:49', 'superadmin', 1),
	(91, NULL, 21, 20, NULL, 24, 29, 100000, 2400000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 800000, 4, 400000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 09:27:54', 'superadmin', '2025-08-01 08:56:55', 'superadmin', 1),
	(92, 10, NULL, 22, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 10:04:32', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(94, NULL, 22, NULL, 92, 8, 29, 2300000, 18400000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 2300000, 1, 2300000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 10:51:25', 'superadmin', '2025-08-01 09:00:18', 'superadmin', 1),
	(95, NULL, 23, NULL, 92, 8, 29, 125000, 1000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 125000, 1, 125000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:06:02', 'superadmin', '2025-08-01 09:00:27', 'superadmin', 1),
	(96, NULL, 24, NULL, 92, 16, 29, 2000000, 32000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 4000000, 2, 4000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:06:26', 'superadmin', '2025-08-01 09:00:35', 'superadmin', 1),
	(97, NULL, 25, 23, NULL, 160, 30, 40000, 6400000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 17, 680000, 17, 680000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:06:54', 'superadmin', '2025-08-01 09:00:47', 'superadmin', 1),
	(98, NULL, 26, 23, NULL, 160, 30, 20000, 3200000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 17, 340000, 17, 340000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:07:24', 'superadmin', '2025-08-01 09:00:58', 'superadmin', 1),
	(99, NULL, 27, 23, NULL, 8, 30, 410000, 3280000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:07:46', 'superadmin', '2025-07-08 20:18:47', 'superadmin', 1),
	(100, NULL, 28, 23, NULL, 8, 30, 300000, 2400000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:08:07', 'superadmin', '2025-07-08 20:19:19', 'superadmin', 1),
	(101, NULL, 29, 24, NULL, 1, 31, 1000000, 1000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:35:19', 'superadmin', '2025-08-01 09:04:13', 'superadmin', 1),
	(102, NULL, 30, 24, NULL, 2640, 32, 15000, 39600000, 0, 1, 61, 915000, 151, 2265000, 169, 2535000, 259, 3885000, 286, 4290000, 260, 3900000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:35:42', 'superadmin', '2025-08-01 09:03:47', 'superadmin', 1),
	(103, 11, NULL, 26, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:38:32', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(104, 12, NULL, 26, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:38:45', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(105, 13, NULL, 26, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:38:56', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(106, 14, NULL, 26, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:39:07', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(107, 12, NULL, 26, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:39:18', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(108, 13, NULL, 26, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:39:28', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(109, NULL, 31, NULL, 103, 96, 29, 3046200, 292435200, 0, 1, 9, 27415800, 9, 27415800, 9, 27415800, 9, 27415800, 9, 27415800, 9, 27415800, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:42:19', 'superadmin', '2025-08-01 09:20:18', 'superadmin', 1),
	(110, NULL, 32, NULL, 103, 156, 29, 3809500, 594282000, 0, 1, 12, 45714000, 12, 45714000, 12, 45714000, 12, 45714000, 12, 45714000, 12, 45714000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:43:32', 'superadmin', '2025-08-01 09:13:59', 'superadmin', 1),
	(111, NULL, 33, NULL, 103, 24, 29, 3186400, 76473600, 0, 1, 2, 6372800, 2, 6372800, 2, 6372800, 2, 6372800, 2, 6372800, 2, 6372800, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:44:06', 'superadmin', '2025-08-01 09:14:06', 'superadmin', 1),
	(112, NULL, 34, NULL, 104, 8, 29, 2386200, 19089600, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 8, 19089600, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:44:43', 'superadmin', '2025-08-01 09:22:27', 'superadmin', 1),
	(113, NULL, 35, NULL, 104, 13, 29, 3149500, 40943500, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, 37794000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:45:20', 'superadmin', '2025-08-01 09:23:03', 'superadmin', 1),
	(114, NULL, 36, NULL, 104, 2, 29, 2526400, 5052800, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 5052800, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:45:50', 'superadmin', '2025-08-01 09:23:21', 'superadmin', 1),
	(115, NULL, 37, NULL, 105, 23, 33, 1200000, 27600000, 0, 1, NULL, NULL, NULL, NULL, 23, 27600000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 11:46:17', 'superadmin', '2025-08-01 09:16:05', 'superadmin', 1),
	(116, NULL, 38, NULL, 106, 528, 29, 3209500, 1694616000, 0, 1, 49, 157265500, 49, 157265500, 49, 157265500, 49, 157265500, 49, 157265500, 44, 141218000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 13:21:08', 'superadmin', '2025-08-01 09:25:22', 'superadmin', 1),
	(117, NULL, 39, NULL, 106, 48, 29, 3046200, 146217600, 0, 1, 4, 12184800, 4, 12184800, 4, 12184800, 4, 12184800, 4, 12184800, 4, 12184800, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 13:21:51', 'superadmin', '2025-08-01 09:25:31', 'superadmin', 1),
	(118, NULL, 32, NULL, 106, 156, 29, 3809500, 594282000, 0, 1, 8, 30476000, 8, 30476000, 8, 30476000, 8, 30476000, 8, 30476000, 13, 49523500, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 13:25:13', 'superadmin', '2025-08-01 09:26:13', 'superadmin', 1),
	(119, NULL, 41, NULL, 107, 44, 29, 2549500, 112178000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 44, 112178000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 13:27:01', 'superadmin', '2025-08-01 09:26:26', 'superadmin', 1),
	(120, NULL, 42, NULL, 107, 4, 29, 2386200, 9544800, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 9544800, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 13:27:36', 'superadmin', '2025-08-01 09:26:40', 'superadmin', 1),
	(121, NULL, 35, NULL, 107, 13, 29, 3149500, 40943500, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 13, 40943500, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 13:28:09', 'superadmin', '2025-08-01 09:26:52', 'superadmin', 1),
	(122, NULL, 43, NULL, 108, 61, 33, 1200000, 73200000, 0, 1, NULL, NULL, NULL, NULL, 61, 73200000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 13:29:11', 'superadmin', '2025-08-01 09:17:48', 'superadmin', 1),
	(123, NULL, 44, 26, NULL, 1008, 29, 12300, 12398400, 0, 1, 84, 1033200, 84, 1033200, 84, 1033200, 84, 1033200, 84, 1033200, 83, 1020900, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 13:31:22', 'superadmin', '2025-08-01 09:28:49', 'superadmin', 1),
	(124, NULL, 45, 26, NULL, 1008, 29, 15300, 15422400, 0, 1, 84, 1285200, 84, 1285200, 84, 1285200, 84, 1285200, 84, 1285200, 83, 1269900, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 13:31:50', 'superadmin', '2025-08-01 09:29:06', 'superadmin', 1),
	(125, NULL, 46, 26, NULL, 1008, 29, 203500, 205128000, 0, 1, 84, 17094000, 84, 17094000, 84, 17094000, 84, 17094000, 84, 17094000, 83, 16890500, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 13:32:16', 'superadmin', '2025-08-01 09:29:18', 'superadmin', 1),
	(126, NULL, 47, 26, NULL, 1008, 29, 101800, 102614400, 0, 1, 84, 8551200, 84, 8551200, 84, 8551200, 84, 8551200, 84, 8551200, 83, 8449400, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 13:32:41', 'superadmin', '2025-08-01 09:29:33', 'superadmin', 1),
	(127, NULL, 48, 26, NULL, 1008, 29, 188300, 189806400, 0, 1, 84, 15817200, 84, 15817200, 84, 15817200, 84, 15817200, 84, 15817200, 83, 15628900, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 13:33:07', 'superadmin', '2025-08-01 09:29:41', 'superadmin', 1),
	(128, NULL, 49, 27, NULL, 500, 30, 44000, 22000000, 0, 1, 10, 440000, 90, 3960000, 10, 440000, 15, 660000, 19, 836000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 13:35:16', 'superadmin', '2025-08-01 09:31:44', 'superadmin', 1),
	(129, NULL, 50, 27, NULL, 500, 30, 23000, 11500000, 0, 1, 10, 230000, 98, 2254000, 10, 230000, 15, 345000, 19, 437000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 13:35:54', 'superadmin', '2025-08-01 09:33:17', 'superadmin', 1),
	(130, NULL, 52, 29, NULL, 960, 30, 15000, 14400000, 0, 1, 50, 750000, 40, 600000, 80, 1200000, 50, 750000, 50, 750000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:47:50', 'superadmin', '2025-08-05 16:56:53', 'superadmin', 1),
	(131, NULL, 53, 29, NULL, 30, 30, 35000, 1050000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:48:08', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(132, NULL, 54, 29, NULL, 50, 34, 30000, 1500000, 0, 1, 1, 30000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 30000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:48:24', 'superadmin', '2025-08-05 16:57:20', 'superadmin', 1),
	(133, NULL, 55, 29, NULL, 1000, 34, 7000, 7000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:48:44', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(134, NULL, 56, 29, NULL, 50, 34, 200000, 10000000, 0, 1, 1, 200000, 1, 200000, 1, 200000, 1, 200000, 1, 200000, 1, 200000, 1, 200000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:48:59', 'superadmin', '2025-08-05 17:00:01', 'superadmin', 1),
	(135, NULL, 57, 29, NULL, 6, 34, 20000, 120000, 0, 1, 6, 120000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:49:15', 'superadmin', '2025-08-05 16:55:23', 'superadmin', 1),
	(136, NULL, 58, 29, NULL, 8, 35, 25000, 200000, 0, 1, 8, 200000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:49:33', 'superadmin', '2025-08-05 16:55:48', 'superadmin', 1),
	(137, NULL, 59, 29, NULL, 1, 36, 2761000, 2761000, 0, 1, NULL, NULL, 1, 2761000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:49:54', 'superadmin', '2025-08-05 16:55:55', 'superadmin', 1),
	(138, NULL, 60, 29, NULL, 5, 35, 70000, 350000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:50:08', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(139, NULL, 61, 29, NULL, 2, 34, 150000, 300000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 300000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:50:23', 'superadmin', '2025-08-05 16:58:38', 'superadmin', 1),
	(140, NULL, 62, 29, NULL, 1, 34, 795000, 795000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:50:39', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(141, NULL, 63, 29, NULL, 0, 36, 2300000, 0, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:51:18', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(142, NULL, 64, 29, NULL, 2, 36, 6800000, 13600000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:51:45', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(143, NULL, 65, 29, NULL, 5, 36, 1700000, 8500000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1700000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:52:06', 'superadmin', '2025-08-05 16:59:13', 'superadmin', 1),
	(144, NULL, 66, 29, NULL, 20, 37, 100000, 2000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:52:27', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(145, NULL, 67, 30, NULL, 20, 34, 650000, 13000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:52:46', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(146, NULL, 68, 30, NULL, 1, 38, 26000000, 26000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:53:05', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(147, NULL, 69, 30, NULL, 1, 34, 2000000, 2000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-23 15:53:18', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(148, NULL, 70, 31, NULL, 24, 29, 5570000, 133680000, 0, 1, 2, 11140000, 2, 11140000, 2, 11140000, 2, 11140000, 2, 11140000, 2, 11140000, 2, 11140000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 08:54:39', 'superadmin', '2025-08-05 17:03:17', 'superadmin', 1),
	(149, NULL, 71, 31, NULL, 396, 29, 5477000, 2168892000, 0, 1, 33, 180741000, 33, 180741000, 33, 180741000, 33, 180741000, 33, 180741000, 33, 180741000, 33, 180741000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 08:55:36', 'superadmin', '2025-08-05 17:04:15', 'superadmin', 1),
	(150, NULL, 72, 31, NULL, 1, 31, 100000000, 100000000, 0, 1, NULL, NULL, 1, 100000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 08:55:51', 'superadmin', '2025-08-05 17:05:08', 'superadmin', 1),
	(151, NULL, 73, 31, NULL, 1, 31, 20000000, 20000000, 0, 1, NULL, NULL, 1, 20000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 08:56:08', 'superadmin', '2025-08-05 17:05:24', 'superadmin', 1),
	(152, NULL, 74, 32, NULL, 144, 29, 5971000, 859824000, 0, 1, 12, 71652000, 12, 71652000, 12, 71652000, 12, 71652000, 12, 71652000, 12, 71652000, 12, 71652000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 08:59:24', 'superadmin', '2025-08-05 17:07:34', 'superadmin', 1),
	(153, NULL, 75, 32, NULL, 12, 39, 1007000, 12084000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 12, 12084000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 08:59:41', 'superadmin', '2025-08-05 17:08:22', 'superadmin', 1),
	(154, NULL, 76, 32, NULL, 5, 34, 300000, 1500000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 5, 1500000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 08:59:57', 'superadmin', '2025-08-05 17:08:31', 'superadmin', 1),
	(155, 16, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:19:38', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(156, NULL, 77, NULL, 155, 1160, 30, 15000, 17400000, 0, 1, 53, 795000, 184, 2760000, 59, 885000, 181, 2715000, 309, 4635000, 39, 585000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:19:53', 'superadmin', '2025-08-05 17:17:58', 'superadmin', 1),
	(157, 17, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:20:03', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(158, NULL, 78, NULL, 157, 240, 30, 35000, 8400000, 0, 1, NULL, NULL, 20, 700000, NULL, NULL, 30, 1050000, 106, 3710000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:20:20', 'superadmin', '2025-08-05 17:18:39', 'superadmin', 1),
	(159, NULL, 79, NULL, 157, 240, 30, 15000, 3600000, 0, 1, 20, 300000, 20, 300000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:20:38', 'superadmin', '2025-08-05 17:10:29', 'superadmin', 1),
	(160, 18, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:20:48', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(161, NULL, 80, NULL, 160, 50, 30, 35000, 1750000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:21:03', 'superadmin', '2025-06-24 09:21:03', 'superadmin', 1),
	(162, NULL, 81, NULL, 160, 50, 30, 15000, 750000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:21:17', 'superadmin', '2025-06-24 09:21:18', 'superadmin', 1),
	(163, 19, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:21:31', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(164, NULL, 82, NULL, 163, 90, 30, 35000, 3150000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:21:50', 'superadmin', '2025-06-24 09:21:50', 'superadmin', 1),
	(165, NULL, 83, NULL, 163, 90, 30, 15000, 1350000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:22:05', 'superadmin', '2025-06-24 09:22:05', 'superadmin', 1),
	(166, 20, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:22:29', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(167, NULL, 84, NULL, 166, 150, 30, 35000, 5250000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:22:48', 'superadmin', '2025-06-24 09:22:48', 'superadmin', 1),
	(168, NULL, 85, NULL, 166, 150, 30, 15000, 2250000, 0, 1, NULL, NULL, 20, 300000, 9, 135000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:23:04', 'superadmin', '2025-08-05 17:11:23', 'superadmin', 1),
	(169, 21, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:23:15', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(170, NULL, 78, NULL, 169, 240, 30, 35000, 8400000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:23:31', 'superadmin', '2025-06-24 09:23:32', 'superadmin', 1),
	(171, NULL, 79, NULL, 169, 240, 30, 15000, 3600000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:23:48', 'superadmin', '2025-06-24 09:23:49', 'superadmin', 1),
	(172, 22, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:23:59', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(173, NULL, 86, NULL, 172, 216, 30, 35000, 7560000, 0, 1, NULL, NULL, 36, 1260000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:27:05', 'superadmin', '2025-08-05 17:11:52', 'superadmin', 1),
	(174, NULL, 87, NULL, 172, 216, 30, 15000, 3240000, 0, 1, NULL, NULL, 36, 540000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:27:23', 'superadmin', '2025-08-05 17:12:01', 'superadmin', 1),
	(175, 23, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:47:14', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(176, NULL, 88, NULL, 175, 40, 30, 35000, 1400000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:47:34', 'superadmin', '2025-06-24 09:47:35', 'superadmin', 1),
	(177, 24, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:47:46', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(178, NULL, 89, NULL, 177, 100, 30, 35000, 3500000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:48:03', 'superadmin', '2025-06-24 09:48:04', 'superadmin', 1),
	(179, NULL, 90, NULL, 177, 100, 30, 15000, 1500000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 09:48:22', 'superadmin', '2025-06-24 09:48:23', 'superadmin', 1),
	(180, 25, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:37:58', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(181, NULL, 91, NULL, 180, 204, 30, 15000, 3060000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:38:13', 'superadmin', '2025-06-24 10:38:14', 'superadmin', 1),
	(182, NULL, 92, NULL, 180, 105, 30, 15000, 1575000, 0, 1, NULL, NULL, 7, 105000, 20, 300000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:38:33', 'superadmin', '2025-08-05 17:12:37', 'superadmin', 1),
	(183, 26, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:38:43', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(184, NULL, 174, NULL, 183, 400, 30, 15000, 6000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, 8, 120000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:39:08', 'superadmin', '2025-08-05 17:22:21', 'superadmin', 1),
	(185, 27, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:39:20', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(186, NULL, 94, NULL, 185, 2400, 30, 12000, 28800000, 0, 1, 250, 3000000, 200, 2400000, 350, 4200000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:39:36', 'superadmin', '2025-08-05 17:24:03', 'superadmin', 1),
	(187, NULL, 95, 33, NULL, 480, 40, 40000, 19200000, 0, 1, NULL, NULL, 10, 400000, 40, 1600000, 40, 1600000, 30, 1200000, 40, 1600000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:39:56', 'superadmin', '2025-08-05 17:24:17', 'superadmin', 1),
	(188, 28, NULL, 33, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:40:04', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(189, NULL, 96, NULL, 188, 200, 30, 35000, 7000000, 0, 1, NULL, NULL, 47, 1645000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:40:22', 'superadmin', '2025-08-05 17:13:55', 'superadmin', 1),
	(190, NULL, 97, NULL, 188, 375, 30, 15000, 5625000, 0, 1, NULL, NULL, 47, 705000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:40:41', 'superadmin', '2025-08-05 17:14:01', 'superadmin', 1),
	(191, 29, NULL, 35, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:47:08', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(192, 30, NULL, 35, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:47:16', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(193, 31, NULL, 35, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:47:28', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(194, 32, NULL, 35, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:47:36', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(195, 33, NULL, 35, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:47:43', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(196, 34, NULL, 35, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:47:53', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(197, NULL, 98, NULL, 191, 30, 30, 100000, 3000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:49:52', 'superadmin', '2025-06-24 10:49:52', 'superadmin', 1),
	(198, NULL, 99, NULL, 191, 200, 41, 30000, 6000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:50:13', 'superadmin', '2025-06-24 10:50:14', 'superadmin', 1),
	(199, NULL, 100, NULL, 191, 200, 30, 15000, 3000000, 0, 1, 50, 750000, NULL, NULL, NULL, NULL, NULL, NULL, 50, 750000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:50:30', 'superadmin', '2025-08-05 17:32:43', 'superadmin', 1),
	(200, NULL, 101, NULL, 191, 200, 30, 35000, 7000000, 0, 1, 50, 1750000, NULL, NULL, NULL, NULL, NULL, NULL, 50, 1750000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:50:46', 'superadmin', '2025-08-05 17:32:50', 'superadmin', 1),
	(201, NULL, 102, NULL, 191, 10, 42, 150000, 1500000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:51:04', 'superadmin', '2025-06-24 10:51:05', 'superadmin', 1),
	(202, NULL, 103, NULL, 191, 10, 42, 150000, 1500000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:51:20', 'superadmin', '2025-06-24 10:51:21', 'superadmin', 1),
	(203, NULL, 99, NULL, 193, 60, 30, 200000, 12000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:52:24', 'superadmin', '2025-06-24 10:52:25', 'superadmin', 1),
	(204, NULL, 104, NULL, 193, 240, 30, 15000, 3600000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:52:56', 'superadmin', '2025-06-24 10:52:56', 'superadmin', 1),
	(205, NULL, 105, NULL, 194, 60, 30, 15000, 900000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:54:52', 'superadmin', '2025-06-24 10:54:53', 'superadmin', 1),
	(206, NULL, 106, NULL, 194, 60, 30, 35000, 2100000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:55:12', 'superadmin', '2025-06-24 10:55:13', 'superadmin', 1),
	(207, NULL, 107, NULL, 195, 100, 34, 22000, 2200000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:57:06', 'superadmin', '2025-06-24 10:57:06', 'superadmin', 1),
	(208, NULL, 108, NULL, 195, 225, 41, 110000, 24750000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:57:25', 'superadmin', '2025-06-24 10:57:26', 'superadmin', 1),
	(209, NULL, 109, NULL, 195, 15, 30, 700000, 10500000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 10:57:58', 'superadmin', '2025-06-24 10:57:58', 'superadmin', 1),
	(210, NULL, 56, NULL, 195, 2, 34, 200000, 400000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:00:57', 'superadmin', '2025-06-24 11:00:57', 'superadmin', 1),
	(211, NULL, 110, NULL, 195, 2, 39, 1675000, 3350000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:01:12', 'superadmin', '2025-06-24 11:01:13', 'superadmin', 1),
	(212, NULL, 111, NULL, 195, 2, 39, 2000000, 4000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:01:29', 'superadmin', '2025-06-24 11:01:30', 'superadmin', 1),
	(213, NULL, 112, NULL, 195, 1, 39, 6000000, 6000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:01:51', 'superadmin', '2025-06-24 11:01:51', 'superadmin', 1),
	(214, NULL, 113, NULL, 195, 20, 43, 100000, 2000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:02:07', 'superadmin', '2025-06-24 11:02:07', 'superadmin', 1),
	(215, NULL, 114, NULL, 196, 415, 30, 15000, 6225000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:02:40', 'superadmin', '2025-06-24 11:02:41', 'superadmin', 1),
	(216, NULL, 115, 36, NULL, 3, 38, 50000000, 150000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 50000000, NULL, NULL, 2, 100000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:27:10', 'superadmin', '2025-08-05 19:09:00', 'superadmin', 1),
	(217, NULL, 116, 36, NULL, 1, 38, 100000000, 100000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:27:25', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(218, NULL, 117, 36, NULL, 2, 38, 35000000, 70000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:27:43', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(219, NULL, 118, 36, NULL, 10, 38, 15000000, 150000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:27:59', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(220, NULL, 119, 36, NULL, 1, 38, 60000000, 60000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:28:15', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(221, NULL, 120, 36, NULL, 2, 38, 25000000, 50000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:28:34', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(222, NULL, 121, 36, NULL, 4, 38, 30000000, 120000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:28:49', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(223, NULL, 122, 36, NULL, 3, 38, 50000000, 150000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:29:06', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(224, NULL, 123, 36, NULL, 3, 38, 20000000, 60000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:29:30', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(225, NULL, 124, 36, NULL, 5, 38, 5000000, 25000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:29:50', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(226, NULL, 125, 36, NULL, 3, 38, 20000000, 60000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:30:09', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(227, NULL, 126, 36, NULL, 2, 38, 20000000, 40000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:30:27', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(228, NULL, 127, 36, NULL, 1, 38, 20000000, 20000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:30:45', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(229, NULL, 128, 36, NULL, 1, 38, 41000000, 41000000, 0, 1, NULL, NULL, 1, 41000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:30:58', 'superadmin', '2025-08-05 17:37:15', 'superadmin', 1),
	(230, NULL, 129, 36, NULL, 1, 31, 190625000, 190625000, 0, 1, NULL, NULL, NULL, NULL, 1, 190625000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:31:17', 'superadmin', '2025-08-05 17:37:39', 'superadmin', 1),
	(231, NULL, 130, 36, NULL, 1, 38, 75850000, 75850000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:31:37', 'superadmin', '2025-08-05 17:38:08', 'superadmin', 1),
	(232, NULL, 131, 36, NULL, 1, 38, 15000000, 15000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:31:52', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(233, NULL, 132, 36, NULL, 1, 38, 40000000, 40000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 40000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:32:17', 'superadmin', '2025-08-05 17:42:09', 'superadmin', 1),
	(234, NULL, 133, 36, NULL, 2, 38, 150000000, 300000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:32:38', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(235, NULL, 134, 36, NULL, 1, 38, 300000000, 300000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 300000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:32:54', 'superadmin', '2025-08-05 17:42:16', 'superadmin', 1),
	(236, NULL, 135, 36, NULL, 10, 38, 3000000, 30000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:33:10', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(237, NULL, 136, 36, NULL, 1, 38, 50000000, 50000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:33:32', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(238, NULL, 137, 37, NULL, 10, 38, 600000, 6000000, 0, 1, NULL, NULL, 2, 1200000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:38:13', 'superadmin', '2025-08-05 20:25:15', 'superadmin', 1),
	(239, NULL, 138, 37, NULL, 32, 38, 400000, 12800000, 0, 1, NULL, NULL, NULL, NULL, 8, 3200000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:38:35', 'superadmin', '2025-08-05 20:25:27', 'superadmin', 1),
	(240, NULL, 139, 37, NULL, 40, 38, 400000, 16000000, 0, 1, NULL, NULL, NULL, NULL, 10, 4000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:38:55', 'superadmin', '2025-08-05 20:25:34', 'superadmin', 1),
	(241, NULL, 140, 37, NULL, 434, 38, 110000, 47740000, 0, 1, NULL, NULL, 47, 5170000, 7, 770000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:39:13', 'superadmin', '2025-08-05 20:25:57', 'superadmin', 1),
	(242, NULL, 141, 37, NULL, 2, 36, 6250000, 12500000, 0, 1, NULL, NULL, NULL, NULL, 2, 12500000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:39:31', 'superadmin', '2025-08-05 20:26:28', 'superadmin', 1),
	(243, NULL, 142, 37, NULL, 2, 36, 7500000, 15000000, 0, 1, NULL, NULL, NULL, NULL, 2, 15000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:39:48', 'superadmin', '2025-08-05 20:26:34', 'superadmin', 1),
	(244, NULL, 143, 37, NULL, 2, 36, 10000000, 20000000, 0, 1, NULL, NULL, NULL, NULL, 2, 20000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:40:14', 'superadmin', '2025-08-05 20:26:44', 'superadmin', 1),
	(245, NULL, 144, 37, NULL, 12, 36, 2500000, 30000000, 0, 1, NULL, NULL, 1, 2500000, NULL, NULL, NULL, NULL, NULL, NULL, 8, 20000000, 3, 7500000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:40:28', 'superadmin', '2025-08-05 20:30:07', 'superadmin', 1),
	(246, NULL, 145, 37, NULL, 2, 36, 4000000, 8000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:40:42', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(247, NULL, 146, 37, NULL, 1, 36, 30000000, 30000000, 0, 1, NULL, NULL, NULL, NULL, 1, 30000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:40:57', 'superadmin', '2025-08-05 20:27:34', 'superadmin', 1),
	(248, NULL, 147, 37, NULL, 1, 38, 50000000, 50000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:41:17', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(249, NULL, 148, 37, NULL, 20, 38, 400000, 8000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:41:33', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(250, NULL, 149, 37, NULL, 20, 38, 350000, 7000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:43:36', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(251, NULL, 150, 37, NULL, 1, 36, 60000000, 60000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:43:51', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(252, NULL, 151, 37, NULL, 4, 38, 130000000, 520000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:44:12', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(253, NULL, 152, 37, NULL, 1, 38, 3000000, 3000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:44:27', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(254, NULL, 153, 37, NULL, 1, 36, 2860000, 2860000, 0, 1, NULL, NULL, 1, 2860000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 11:44:45', 'superadmin', '2025-08-05 20:27:59', 'superadmin', 1),
	(255, NULL, 154, 38, NULL, 165, 37, 35000, 5775000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 13:52:04', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(256, NULL, 155, 38, NULL, 330, 37, 15000, 4950000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 13:52:17', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(257, NULL, 156, 38, NULL, 1, 34, 450000, 450000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 13:52:34', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(258, NULL, 157, 38, NULL, 55, 34, 8000, 440000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 13:52:49', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(259, NULL, 158, 38, NULL, 27, 44, 1700000, 45900000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 13:53:08', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(260, NULL, 159, 38, NULL, 4, 37, 664000, 2656000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 13:53:27', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(261, NULL, 160, 38, NULL, 1, 30, 800000, 800000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 13:53:43', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(262, NULL, 161, 38, NULL, 1, 30, 400000, 400000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 13:53:56', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(263, NULL, 162, 38, NULL, 1, 45, 15000000, 15000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 13:54:12', 'superadmin', '2025-06-25 08:42:04', 'superadmin', 1),
	(267, 14, NULL, 82, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-26 11:36:38', 'superadmin', '2025-07-08 20:16:42', 'superadmin', 0),
	(268, NULL, 24, NULL, 267, 2, 33, 30000, 60000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-26 11:37:38', 'superadmin', '2025-07-08 20:16:39', 'superadmin', 0),
	(273, NULL, 37, 24, NULL, 8, 42, 100000, 800000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-01 18:58:04', 'superadmin', '2025-07-01 18:58:31', 'superadmin', 0),
	(274, NULL, 37, 24, NULL, 8, 42, 100000, 800000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-01 18:59:13', 'superadmin', '2025-07-01 18:59:18', 'superadmin', 0),
	(275, NULL, 163, 69, NULL, 60, 44, 1000000, 60000000, 0, 1, NULL, NULL, 1, 1000000, NULL, NULL, NULL, NULL, NULL, NULL, 8, 8000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-09 19:38:53', 'superadmin', '2025-08-05 21:35:54', 'superadmin', 1),
	(276, NULL, 164, 69, NULL, 480, 30, 20000, 9600000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-09 19:39:56', 'superadmin', '2025-07-09 19:39:56', 'superadmin', 1),
	(277, NULL, 165, 69, NULL, 240, 30, 40000, 9600000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-09 19:40:18', 'superadmin', '2025-07-09 19:40:19', 'superadmin', 1),
	(278, 35, NULL, 81, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-10 10:23:36', 'superadmin', '2025-07-10 10:23:36', 'superadmin', 1),
	(279, NULL, 166, NULL, 278, 18, 31, 50344000, 906192000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 50344000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-10 10:26:01', 'superadmin', '2025-08-05 21:53:27', 'superadmin', 1),
	(280, NULL, 166, NULL, 278, 1, 31, 50344000, 50344000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-10 10:26:40', 'superadmin', '2025-07-10 10:27:07', 'superadmin', 0),
	(281, NULL, 166, NULL, 278, 1, 31, 50344000, 50344000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-10 10:26:47', 'superadmin', '2025-07-10 10:27:09', 'superadmin', 0),
	(282, NULL, 167, NULL, 278, 8, 31, 39556000, 316448000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-10 10:27:34', 'superadmin', '2025-07-10 10:40:24', 'superadmin', 1),
	(283, NULL, 168, NULL, 278, 8, 31, 359600000, 2876800000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-10 10:27:57', 'superadmin', '2025-07-10 10:41:05', 'superadmin', 1),
	(284, NULL, 166, 80, NULL, 1, 31, 26100000, 26100000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 26100000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-19 15:46:50', 'superadmin', '2025-08-05 21:52:37', 'superadmin', 1),
	(285, NULL, 167, 80, NULL, 1, 31, 20300000, 20300000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-19 15:47:08', 'superadmin', '2025-07-19 15:47:09', 'superadmin', 1),
	(286, NULL, 168, 80, NULL, 1, 31, 191400000, 191400000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-19 15:47:28', 'superadmin', '2025-07-19 15:47:29', 'superadmin', 1),
	(287, NULL, 169, 77, NULL, 5, 44, 1700000, 8500000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-19 15:49:54', 'superadmin', '2025-07-19 15:49:55', 'superadmin', 1),
	(288, NULL, 170, 77, NULL, 30, 30, 15000, 450000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-19 15:50:21', 'superadmin', '2025-07-19 15:50:21', 'superadmin', 1),
	(289, NULL, 171, 77, NULL, 30, 30, 35000, 1050000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-19 15:50:46', 'superadmin', '2025-07-19 15:50:47', 'superadmin', 1),
	(290, 36, NULL, 76, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-19 15:51:35', 'superadmin', '2025-07-19 15:51:36', 'superadmin', 1),
	(291, NULL, 166, NULL, 290, 1, 31, 16245000, 16245000, 0, 1, NULL, NULL, NULL, NULL, 1, 16245000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-19 15:52:42', 'superadmin', '2025-08-05 21:51:20', 'superadmin', 1),
	(292, NULL, 167, NULL, 290, 1, 31, 12635000, 12635000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-19 15:53:08', 'superadmin', '2025-07-19 15:53:09', 'superadmin', 1),
	(293, NULL, 168, NULL, 290, 1, 31, 90249000, 90249000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-19 15:53:25', 'superadmin', '2025-07-19 15:53:26', 'superadmin', 1),
	(294, NULL, 37, 70, NULL, 8, 34, 20000000, 160000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-23 19:55:02', 'superadmin', '2025-07-23 21:57:46', 'superadmin', 0),
	(295, NULL, 137, 70, NULL, 10, 38, 250000, 2500000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-29 13:47:23', 'superadmin', '2025-07-29 13:47:23', 'superadmin', 1),
	(296, NULL, 172, 25, NULL, 24, 30, 300000, 7200000, 0, 1, NULL, NULL, 2, 600000, 2, 600000, 4, 1200000, 4, 1200000, 4, 1200000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-01 09:07:04', 'superadmin', '2025-08-01 09:07:04', 'superadmin', 1),
	(297, NULL, 173, 25, NULL, 24, 30, 450000, 10800000, 0, 1, NULL, NULL, 1, 450000, 1, 450000, 2, 900000, 2, 900000, 2, 900000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-01 09:08:13', 'superadmin', '2025-08-01 09:08:14', 'superadmin', 1),
	(298, NULL, 175, NULL, 183, 225, 30, 35000, 7875000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 17:23:08', 'superadmin', '2025-08-05 17:23:09', 'superadmin', 1),
	(299, NULL, 176, 34, NULL, 1, 31, 60000000, 60000000, 0, 1, 1, 60000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 17:28:15', 'superadmin', '2025-08-05 17:28:16', 'superadmin', 1),
	(300, NULL, 177, 34, NULL, 1, 31, 42000000, 42000000, 0, 1, 1, 42000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 17:28:40', 'superadmin', '2025-08-05 17:28:40', 'superadmin', 1),
	(301, NULL, 178, 34, NULL, 1, 31, 987095500, 987095500, 0, 1, 1, 987095500, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 17:29:05', 'superadmin', '2025-08-05 17:29:06', 'superadmin', 1),
	(302, NULL, 179, 34, NULL, 1, 31, 430000000, 430000000, 0, 1, 1, 430000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 17:29:25', 'superadmin', '2025-08-05 17:29:25', 'superadmin', 1),
	(303, NULL, 180, 34, NULL, 1, 31, 90000000, 90000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 17:29:56', 'superadmin', '2025-08-05 17:29:57', 'superadmin', 1),
	(304, NULL, 181, 36, NULL, 1, 38, 30650000, 30650000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 17:40:37', 'superadmin', '2025-08-05 17:40:38', 'superadmin', 1),
	(305, NULL, 115, 36, NULL, 2, 38, 21750000, 43500000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 17:40:56', 'superadmin', '2025-08-05 17:40:57', 'superadmin', 1),
	(306, NULL, 183, 42, NULL, 50, 30, 100000, 5000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 39, 3900000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 20:41:39', 'superadmin', '2025-08-05 20:41:40', 'superadmin', 1),
	(307, NULL, 184, 42, NULL, 70, 30, 20000, 1400000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 20:42:04', 'superadmin', '2025-08-05 20:42:04', 'superadmin', 1),
	(308, NULL, 185, 42, NULL, 70, 30, 40000, 2800000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 20:42:20', 'superadmin', '2025-08-05 20:42:20', 'superadmin', 1),
	(309, NULL, 186, 42, NULL, 2, 30, 450000, 900000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, 900000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 20:42:53', 'superadmin', '2025-08-05 20:42:53', 'superadmin', 1),
	(310, NULL, 187, 48, NULL, 1, 31, 12914240000, 12914240000, 0, 1, NULL, NULL, NULL, NULL, 1, 12914240000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 20:47:19', 'superadmin', '2025-08-05 20:47:20', 'superadmin', 1),
	(311, NULL, 188, 48, NULL, 1, 31, 12000000000, 12000000000, 0, 1, 1, 12000000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 20:47:42', 'superadmin', '2025-08-05 20:47:42', 'superadmin', 1),
	(312, NULL, 189, 51, NULL, 33471, 47, 80000, 2677680000, 0, 1, 9214, 737120000, 7865, 629200000, 7199, 575920000, 8505, 680400000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 20:51:29', 'superadmin', '2025-08-05 20:51:30', 'superadmin', 1),
	(313, NULL, 190, 60, NULL, 24, 37, 450000, 10800000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:14:03', 'superadmin', '2025-08-05 21:14:04', 'superadmin', 1),
	(314, NULL, 191, 60, NULL, 800, 37, 15000, 12000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 60, 900000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:14:23', 'superadmin', '2025-08-05 21:14:23', 'superadmin', 1),
	(315, NULL, 192, 60, NULL, 100, 37, 35000, 3500000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:14:44', 'superadmin', '2025-08-05 21:14:45', 'superadmin', 1),
	(316, NULL, 99, 60, NULL, 6, 43, 200000, 1200000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:15:06', 'superadmin', '2025-08-05 21:15:06', 'superadmin', 1),
	(317, NULL, 193, 61, NULL, 50, 44, 900000, 45000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:18:17', 'superadmin', '2025-08-05 21:18:17', 'superadmin', 1),
	(318, NULL, 194, 61, NULL, 595, 30, 15000, 8925000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 180, 2700000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:18:42', 'superadmin', '2025-08-05 21:18:43', 'superadmin', 1),
	(319, NULL, 195, 61, NULL, 195, 30, 35000, 6825000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 110, 3850000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:19:11', 'superadmin', '2025-08-05 21:19:11', 'superadmin', 1),
	(320, NULL, 196, 62, NULL, 1, 43, 41820000, 41820000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:22:01', 'superadmin', '2025-08-05 21:22:02', 'superadmin', 1),
	(321, NULL, 197, 62, NULL, 18, 37, 300000, 5400000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:22:20', 'superadmin', '2025-08-05 21:22:20', 'superadmin', 1),
	(322, NULL, 198, 62, NULL, 6, 37, 350000, 2100000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:22:47', 'superadmin', '2025-08-05 21:22:47', 'superadmin', 1),
	(323, NULL, 199, 62, NULL, 12, 34, 600000, 7200000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:23:04', 'superadmin', '2025-08-05 21:23:04', 'superadmin', 1),
	(324, NULL, 99, 62, NULL, 200, 34, 30000, 6000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:24:00', 'superadmin', '2025-08-05 21:24:01', 'superadmin', 1),
	(325, NULL, 201, 63, NULL, 1, 31, 6860036854, 6860036854, 0, 1, 1, 6860036854, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:26:50', 'superadmin', '2025-08-05 21:26:51', 'superadmin', 1),
	(326, NULL, 202, 63, NULL, 1, 31, 2249051000, 2249051000, 0, 1, NULL, NULL, NULL, NULL, 1, 2249051000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:27:19', 'superadmin', '2025-08-05 21:27:20', 'superadmin', 1),
	(327, NULL, 203, 63, NULL, 1, 31, 2795048000, 2795048000, 0, 1, NULL, NULL, 1, 2795048000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:27:50', 'superadmin', '2025-08-05 21:27:51', 'superadmin', 1),
	(328, NULL, 204, 63, NULL, 1, 31, 763707000, 763707000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, 1, 763707000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:28:25', 'superadmin', '2025-08-05 21:28:25', 'superadmin', 1),
	(329, NULL, 205, 63, NULL, 130, 36, 1250000, 162500000, 0, 1, NULL, NULL, 1, 1250000, NULL, NULL, 1, 1250000, 1, 1250000, 1, 1250000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:29:36', 'superadmin', '2025-08-05 21:29:37', 'superadmin', 1),
	(330, NULL, 206, 66, NULL, 445, 48, 720000, 320400000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:31:17', 'superadmin', '2025-08-05 21:31:18', 'superadmin', 1),
	(331, NULL, 207, 67, NULL, 1, 31, 58000000, 58000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 58000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:33:25', 'superadmin', '2025-08-05 21:33:25', 'superadmin', 1),
	(332, NULL, 208, 68, NULL, 1, 49, 100000000, 100000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:34:35', 'superadmin', '2025-08-05 22:02:23', 'superadmin', 1),
	(333, NULL, 209, 71, NULL, 20, 49, 15000, 300000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:37:42', 'superadmin', '2025-08-05 21:37:42', 'superadmin', 1),
	(334, NULL, 210, 71, NULL, 20, 49, 35000, 700000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:37:58', 'superadmin', '2025-08-05 21:37:59', 'superadmin', 1),
	(335, NULL, 211, 72, NULL, 4, 38, 3500000, 14000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 4, 14000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:39:45', 'superadmin', '2025-08-05 21:39:45', 'superadmin', 1),
	(336, NULL, 212, 72, NULL, 1, 50, 40000000, 40000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 40000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:40:34', 'superadmin', '2025-08-05 21:40:34', 'superadmin', 1),
	(337, NULL, 213, 73, NULL, 1, 36, 70000000, 70000000, 0, 1, NULL, NULL, NULL, NULL, 1, 70000000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:42:45', 'superadmin', '2025-08-05 21:42:45', 'superadmin', 1),
	(338, NULL, 170, 73, NULL, 300, 30, 15000, 4500000, 0, 1, 73, 1095000, 32, 480000, 105, 1575000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:43:21', 'superadmin', '2025-08-05 21:45:39', 'superadmin', 1),
	(339, NULL, 171, 73, NULL, 300, 30, 35000, 10500000, 0, 1, 73, 2555000, 32, 1120000, 105, 3675000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:43:58', 'superadmin', '2025-08-05 21:43:59', 'superadmin', 1),
	(340, NULL, 214, 74, NULL, 10, 44, 900000, 9000000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:46:37', 'superadmin', '2025-08-05 21:46:37', 'superadmin', 1),
	(341, NULL, 170, 74, NULL, 85, 30, 15000, 1275000, 0, 1, NULL, NULL, 85, 1275000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:47:05', 'superadmin', '2025-08-05 21:47:05', 'superadmin', 1),
	(342, NULL, 171, 74, NULL, 85, 30, 35000, 2975000, 0, 1, NULL, NULL, 85, 2975000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:47:26', 'superadmin', '2025-08-05 21:47:26', 'superadmin', 1),
	(343, 37, NULL, 75, NULL, NULL, NULL, NULL, NULL, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:48:43', 'superadmin', '2025-08-05 21:48:44', 'superadmin', 1),
	(344, NULL, 166, NULL, 343, 1, 31, 11570000, 11570000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 11570000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:49:03', 'superadmin', '2025-08-05 21:49:04', 'superadmin', 1),
	(345, NULL, 167, NULL, 343, 1, 31, 9091000, 9091000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:49:29', 'superadmin', '2025-08-05 21:49:30', 'superadmin', 1),
	(346, NULL, 168, NULL, 343, 1, 31, 82638000, 82638000, 0, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-05 21:49:49', 'superadmin', '2025-08-05 21:49:49', 'superadmin', 1);

-- Dumping structure for table budgeting.program
CREATE TABLE IF NOT EXISTS `program` (
  `idprogram` int NOT NULL AUTO_INCREMENT,
  `no_rekening_program` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nama_program` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `idbidang_urusan` int DEFAULT NULL,
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idprogram`) USING BTREE,
  KEY `is_active` (`is_active`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `no_rekening_urusan` (`no_rekening_program`) USING BTREE,
  KEY `nama_urusan` (`nama_program`) USING BTREE,
  KEY `tahun_anggaran_urusan` (`idbidang_urusan`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.program: ~5 rows (approximately)
INSERT INTO `program` (`idprogram`, `no_rekening_program`, `nama_program`, `idbidang_urusan`, `is_active`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(1, '01', 'PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI', 1, 1, '2025-06-16 10:34:13', 'BucinBGMID', '2025-06-17 08:52:18', 'BucinBGMID', 1),
	(2, '02', 'PROGRAM PEMENUHAN UPAYA KESEHATAN PERORANGAN DAN UPAYA KESEHATAN MASYARAKAT', 1, 1, '2025-06-16 15:58:30', 'BucinBGMID', '2025-07-22 17:32:16', 'superadmin', 1),
	(3, '03', 'PROGRAM PENUNJANG URUSAN PEMERINTAHAN DAERAH PROVINSI TAHUN 2025', 1, 1, '2025-06-17 07:50:44', 'BucinBGMID', '2025-06-17 07:52:00', 'BucinBGMID', 0),
	(4, '012021', 'okok', 5, 1, '2025-06-23 14:18:01', 'BucinBGMID', '2025-06-23 14:20:55', 'BucinBGMID', 0),
	(5, '02', 'PROGRAM PEMENUHAN UPAYA KESEHATAN PERORANGAN DAN UPAYA KESEHATAN MASYARAKAT 25', 1, 1, '2025-06-23 14:29:05', 'BucinBGMID', '2025-06-23 14:29:56', 'BucinBGMID', 0);

-- Dumping structure for table budgeting.role
CREATE TABLE IF NOT EXISTS `role` (
  `idrole` int NOT NULL AUTO_INCREMENT,
  `parent` int DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idrole`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table budgeting.role: ~6 rows (approximately)
INSERT INTO `role` (`idrole`, `parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(1, 0, 'administrator', 'Superadmin', 'Superadmin', '2022-04-04 12:00:00', 'superadmin', '2022-04-04 12:00:00', 'superadmin', 1),
	(4, 0, 'verifikator', 'Verifikator', 'Verifikator', '2022-04-04 12:00:00', 'superadmin', '2022-04-04 12:00:00', 'superadmin', 1),
	(5, 0, 'bendahara', 'Bendahara', 'Bendahara', '2022-04-04 12:00:00', 'superadmin', '2022-04-04 12:00:00', 'superadmin', 1),
	(6, 0, 'admin', 'Admin', 'Admin', '2022-04-04 12:00:00', 'superadmin', '2022-04-04 12:00:00', 'superadmin', 1),
	(7, 0, 'direktur', 'Direktur', 'Direktur', '2022-04-04 12:00:00', 'superadmin', '2022-04-04 12:00:00', 'superadmin', 1),
	(8, 0, 'npd', 'NPD', 'NPD', '2022-04-04 12:00:00', 'superadmin', '2022-04-04 12:00:00', 'superadmin', 1);

-- Dumping structure for table budgeting.ruang
CREATE TABLE IF NOT EXISTS `ruang` (
  `idruang` int NOT NULL AUTO_INCREMENT,
  `nama_ruang` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idruang`) USING BTREE,
  KEY `is_active` (`is_active`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `nama_urusan` (`nama_ruang`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.ruang: ~8 rows (approximately)
INSERT INTO `ruang` (`idruang`, `nama_ruang`, `is_active`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(56, 'Ortotik Prostetik', 1, '2025-07-08 17:58:26', 'administrator', '2025-07-08 17:58:27', 'administrator', 1),
	(57, '3D Printing', 1, '2025-07-08 17:58:34', 'administrator', '2025-07-08 17:58:34', 'administrator', 1),
	(58, 'Ruang USG', 1, '2025-07-08 17:58:42', 'administrator', '2025-07-08 17:58:42', 'administrator', 1),
	(59, 'Ruang Alat Panometic', 1, '2025-07-08 17:58:49', 'administrator', '2025-07-08 17:58:49', 'administrator', 1),
	(60, 'Operator radiologi', 1, '2025-07-08 17:58:55', 'administrator', '2025-07-08 17:58:56', 'administrator', 1),
	(61, 'Gudang', 1, '2025-07-08 17:59:04', 'administrator', '2025-07-08 17:59:04', 'administrator', 1),
	(62, 'Ruang x-ray 1', 1, '2025-07-08 17:59:11', 'administrator', '2025-07-08 17:59:11', 'administrator', 1),
	(63, 'Ruang x-ray 2', 1, '2025-07-08 17:59:19', 'administrator', '2025-07-08 17:59:19', 'administrator', 1),
	(64, 'Ruang IT', 1, '2025-07-08 17:59:28', 'administrator', '2025-07-08 17:59:29', 'administrator', 1),
	(65, 'Ruang Server', 1, '2025-07-08 17:59:42', 'administrator', '2025-07-22 19:43:46', 'superadmin', 1);

-- Dumping structure for table budgeting.satuan
CREATE TABLE IF NOT EXISTS `satuan` (
  `idsatuan` int NOT NULL AUTO_INCREMENT,
  `nama_satuan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idsatuan`) USING BTREE,
  KEY `is_active` (`is_active`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `nama_urusan` (`nama_satuan`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.satuan: ~22 rows (approximately)
INSERT INTO `satuan` (`idsatuan`, `nama_satuan`, `is_active`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(29, 'ob', 1, '2025-06-23 08:37:45', 'BucinBGMID', '2025-06-23 08:37:46', 'BucinBGMID', 1),
	(30, 'ok', 1, '2025-06-23 08:37:55', 'BucinBGMID', '2025-06-23 08:37:56', 'BucinBGMID', 1),
	(31, 'LS', 1, '2025-06-23 08:38:03', 'BucinBGMID', '2025-06-23 08:38:03', 'BucinBGMID', 1),
	(32, 'pasien', 1, '2025-06-23 08:38:09', 'BucinBGMID', '2025-06-23 08:38:09', 'BucinBGMID', 1),
	(33, 'org', 1, '2025-06-23 08:38:18', 'BucinBGMID', '2025-06-23 08:38:18', 'BucinBGMID', 1),
	(34, 'bh', 1, '2025-06-23 08:39:03', 'BucinBGMID', '2025-06-23 08:39:03', 'BucinBGMID', 1),
	(35, 'pak', 1, '2025-06-23 08:39:10', 'BucinBGMID', '2025-06-23 08:39:10', 'BucinBGMID', 1),
	(36, 'kali', 1, '2025-06-23 08:39:14', 'BucinBGMID', '2025-06-23 08:39:14', 'BucinBGMID', 1),
	(37, 'oh', 1, '2025-06-23 08:39:34', 'BucinBGMID', '2025-06-23 08:39:34', 'BucinBGMID', 1),
	(38, 'unit', 1, '2025-06-23 08:39:54', 'BucinBGMID', '2025-06-23 08:39:55', 'BucinBGMID', 1),
	(39, 'set', 1, '2025-06-23 08:40:19', 'BucinBGMID', '2025-06-23 08:40:20', 'BucinBGMID', 1),
	(40, 'dus', 1, '2025-06-23 08:41:01', 'BucinBGMID', '2025-06-23 08:41:02', 'BucinBGMID', 1),
	(41, 'paket', 1, '2025-06-23 08:41:23', 'BucinBGMID', '2025-06-23 08:41:24', 'BucinBGMID', 1),
	(42, 'box', 1, '2025-06-23 08:41:27', 'BucinBGMID', '2025-06-23 08:41:28', 'BucinBGMID', 1),
	(43, 'Pt', 1, '2025-06-23 08:41:48', 'BucinBGMID', '2025-06-23 08:41:56', 'BucinBGMID', 1),
	(44, 'oj', 1, '2025-06-23 08:58:51', 'BucinBGMID', '2025-06-23 08:58:51', 'BucinBGMID', 1),
	(45, 'keg', 1, '2025-06-23 08:59:07', 'BucinBGMID', '2025-07-22 19:46:13', 'superadmin', 1),
	(46, 'kegxxx', 1, '2025-06-23 15:20:02', 'BucinBGMID', '2025-06-23 15:20:05', 'BucinBGMID', 0),
	(47, 'hari perawatan', 1, '2025-08-05 20:49:14', 'superadmin', '2025-08-05 20:49:15', 'superadmin', 1),
	(48, 'stel', 1, '2025-08-05 21:31:05', 'superadmin', '2025-08-05 21:31:05', 'superadmin', 1),
	(49, 'dokumen', 1, '2025-08-05 21:34:17', 'superadmin', '2025-08-05 21:34:18', 'superadmin', 1),
	(50, 'pcs', 1, '2025-08-05 21:40:14', 'superadmin', '2025-08-05 21:40:14', 'superadmin', 1);

-- Dumping structure for table budgeting.sub_kategori
CREATE TABLE IF NOT EXISTS `sub_kategori` (
  `idsub_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_sub_kategori` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_gender` varchar(200) DEFAULT NULL COMMENT '0 : tidak wajib isi gender; 1: wajib isi gender',
  `is_description` varchar(200) DEFAULT NULL COMMENT '0 : tidak wajib isi keterangan; 1: wajib isi keterangan',
  `is_room` varchar(200) DEFAULT NULL COMMENT '0 : tidak wajib isi ruang; 1: wajib isi ruang',
  `is_name_training` varchar(200) DEFAULT NULL COMMENT '0 : tidak wajib isi nama diklat; 1: wajib isi nama diklat',
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idsub_kategori`) USING BTREE,
  KEY `is_active` (`is_active`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `nama_urusan` (`nama_sub_kategori`) USING BTREE,
  KEY `is_gender` (`is_gender`)
) ENGINE=InnoDB AUTO_INCREMENT=215 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.sub_kategori: ~198 rows (approximately)
INSERT INTO `sub_kategori` (`idsub_kategori`, `nama_sub_kategori`, `is_gender`, `is_description`, `is_room`, `is_name_training`, `is_active`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(16, 'Pejabat Pengadaan (2 orang x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-19 10:16:45', 'BucinBGMID', '2025-08-05 22:07:31', 'superadmin', 1),
	(17, 'Kuasa Pengguna Anggaran (1 orang x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-19 10:17:02', 'BucinBGMID', '2025-08-05 22:17:34', 'superadmin', 1),
	(18, 'Pejabat Pembuat Komitmen (1 orang x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-19 10:17:13', 'BucinBGMID', '2025-08-05 22:17:40', 'superadmin', 1),
	(19, 'Ketua Tim Teknis (2 orang x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-19 10:17:31', 'BucinBGMID', '2025-08-05 22:17:46', 'superadmin', 1),
	(20, 'Sekretaris Tim Teknis (2 orang x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-19 10:17:42', 'BucinBGMID', '2025-08-05 22:17:52', 'superadmin', 1),
	(21, 'Anggota Tim Teknis (2 orang x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-19 10:17:53', 'BucinBGMID', '2025-08-05 22:17:58', 'superadmin', 1),
	(22, 'Ketua (1 org x 8 bulan)', '1', '0', '0', '0', 1, '2025-06-19 10:19:26', 'BucinBGMID', '2025-08-06 15:19:22', 'superadmin', 1),
	(23, 'Seketaris (1 org x 8 bulan)', '1', '0', '0', '0', 1, '2025-06-19 10:19:35', 'BucinBGMID', '2025-08-06 15:19:33', 'superadmin', 1),
	(24, 'Anggota (2 org x 8 bulan)', '1', '0', '0', '0', 1, '2025-06-19 10:19:42', 'BucinBGMID', '2025-08-06 15:19:43', 'superadmin', 1),
	(25, 'Nasi Kotak (20 org x 8 kl)', '1', '0', '0', '0', 1, '2025-06-19 10:20:05', 'BucinBGMID', '2025-08-06 15:26:30', 'superadmin', 1),
	(26, 'Kue Kotak (20 org x 8 kl)', '1', '0', '0', '0', 1, '2025-06-19 10:20:12', 'BucinBGMID', '2025-08-06 15:26:44', 'superadmin', 1),
	(27, 'Uang harian Dewan Pengawas BLUD (1 org x 8 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-19 10:20:27', 'BucinBGMID', '2025-06-19 10:20:27', 'BucinBGMID', 1),
	(28, 'Biaya Transport Dewan Pengawas BLUD (1 org x 8 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-19 10:20:41', 'BucinBGMID', '2025-06-19 10:20:41', 'BucinBGMID', 1),
	(29, 'Paket Pengiriman', NULL, NULL, NULL, NULL, 1, '2025-06-23 11:34:49', 'BucinBGMID', '2025-06-23 11:34:50', 'BucinBGMID', 1),
	(30, 'Pengantaran Obat Pasien (10 pasien x 22 hari x 12 bln)', '1', NULL, NULL, NULL, 1, '2025-06-23 11:35:02', 'BucinBGMID', '2025-07-19 10:41:49', 'superadmin', 1),
	(31, 'Golongan DIII (8 Org x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-23 11:39:46', 'BucinBGMID', '2025-08-06 15:55:58', 'superadmin', 1),
	(32, 'Golongan DIV / S 1  (13 Org x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-23 11:40:01', 'BucinBGMID', '2025-08-06 15:56:21', 'superadmin', 1),
	(33, 'Golongan DIV / S 2  (2 Org x 12 bulan) ', '1', '0', '0', '0', 1, '2025-06-23 11:40:14', 'BucinBGMID', '2025-08-06 15:56:41', 'superadmin', 1),
	(34, 'Golongan DIII (8 Org x 1 bulan)', '1', '0', '0', '0', 1, '2025-06-23 11:40:30', 'BucinBGMID', '2025-08-06 17:09:19', 'superadmin', 1),
	(35, 'Golongan DIV / S 1  (13 Org x 1 bulan)', '1', '0', '0', '0', 1, '2025-06-23 11:40:48', 'BucinBGMID', '2025-08-06 17:45:30', 'superadmin', 1),
	(36, 'Golongan DIV / S 2  (2 Org x 1 bulan)', '1', '0', '0', '0', 1, '2025-06-23 11:41:18', 'BucinBGMID', '2025-08-06 17:10:33', 'superadmin', 1),
	(37, '23 orang x Rp. 1.200.000', '1', '0', '0', '0', 1, '2025-06-23 11:41:31', 'BucinBGMID', '2025-08-06 16:14:07', 'superadmin', 1),
	(38, 'Golongan SMA (44 Org x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-23 13:19:07', 'BucinBGMID', '2025-08-06 15:57:22', 'superadmin', 1),
	(39, 'Golongan DIII (4 Org x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-23 13:19:24', 'BucinBGMID', '2025-08-06 15:57:46', 'superadmin', 1),
	(40, 'Golongan DIV / S 1  (13 Org x 12 bulan)', NULL, NULL, NULL, NULL, 1, '2025-06-23 13:19:43', 'BucinBGMID', '2025-06-23 13:22:28', 'BucinBGMID', 0),
	(41, 'Golongan SMA (44 Org x 1 bulan)', '1', '0', '0', '0', 1, '2025-06-23 13:25:53', 'BucinBGMID', '2025-08-06 17:43:41', 'superadmin', 1),
	(42, 'Golongan DIII (4 Org x 1 bulan)', '1', '0', '0', '0', 1, '2025-06-23 13:26:13', 'BucinBGMID', '2025-08-06 17:44:21', 'superadmin', 1),
	(43, '61 orang x Rp. 1.200.000', '1', '0', '0', '0', 1, '2025-06-23 13:28:49', 'BucinBGMID', '2025-08-06 16:16:56', 'superadmin', 1),
	(44, 'Iuran BPJS  ketenagakerjaan (JKK) untuk PTT-PK (84 org x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-23 13:29:47', 'BucinBGMID', '2025-08-06 15:58:47', 'superadmin', 1),
	(45, 'Iuran BPJS  Ketenagakerjaan (JKM) untuk PTT-PK (84 org x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-23 13:30:09', 'BucinBGMID', '2025-08-06 15:59:02', 'superadmin', 1),
	(46, 'Iuran BPJS Kesehatan untuk PTT-PK (84 org x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-23 13:30:21', 'BucinBGMID', '2025-08-06 15:59:18', 'superadmin', 1),
	(47, 'Iuran BPJS  Ketenagakerjaan (JP) untuk PTT-PK (84 org x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-23 13:30:32', 'BucinBGMID', '2025-08-06 15:59:38', 'superadmin', 1),
	(48, 'Iuran BPJS Ketenagakerjaan (JHT) untuk PTT-PK (84 org x 12 bulan)', '1', '0', '0', '0', 1, '2025-06-23 13:30:49', 'BucinBGMID', '2025-08-06 15:59:55', 'superadmin', 1),
	(49, 'Nasi Kotak Kunjungan Tamu Kedinasan (10 org x 1 hari x 50 kali)', '1', '0', '0', '0', 1, '2025-06-23 13:34:46', 'BucinBGMID', '2025-08-06 19:23:44', 'superadmin', 1),
	(50, 'Kue Kotak Kunjungan Tamu Kedinasan (10 org x 1 hari x 50 kali)', '1', '0', '0', '0', 1, '2025-06-23 13:34:57', 'BucinBGMID', '2025-08-06 19:24:06', 'superadmin', 1),
	(51, 'Kue Kotak Kunjungan Tamu Kedinasan (10 org x 1 hari x 50 kali)2222', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:16:46', 'BucinBGMID', '2025-06-23 15:16:55', 'BucinBGMID', 0),
	(52, 'Kue kotak u/ peserta, panitia & penyuluh pkrs (40 org x 24 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:43:47', 'BucinBGMID', '2025-06-23 15:43:47', 'BucinBGMID', 1),
	(53, 'Nasi kotak u/ rapat evaluasi (15 org x 2 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:44:17', 'BucinBGMID', '2025-06-23 15:44:17', 'BucinBGMID', 1),
	(54, 'Poster', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:44:26', 'BucinBGMID', '2025-06-23 15:44:27', 'BucinBGMID', 1),
	(55, 'Leaflet', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:44:38', 'BucinBGMID', '2025-06-23 15:44:38', 'BucinBGMID', 1),
	(56, 'Banner', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:44:50', 'BucinBGMID', '2025-06-23 15:44:51', 'BucinBGMID', 1),
	(57, 'Tali Tampar', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:45:00', 'BucinBGMID', '2025-06-23 15:45:01', 'BucinBGMID', 1),
	(58, 'Kabel Ties', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:45:12', 'BucinBGMID', '2025-06-23 15:45:12', 'BucinBGMID', 1),
	(59, 'Canva Pro Berlangganan', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:45:21', 'BucinBGMID', '2025-06-23 15:45:21', 'BucinBGMID', 1),
	(60, 'Kartu nama customer care', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:45:30', 'BucinBGMID', '2025-06-23 15:45:31', 'BucinBGMID', 1),
	(61, 'Flasdisk 32gb', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:45:43', 'BucinBGMID', '2025-06-23 15:45:44', 'BucinBGMID', 1),
	(62, 'Breket TV', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:45:55', 'BucinBGMID', '2025-06-23 15:45:56', 'BucinBGMID', 1),
	(63, 'Iklan Media Cetak 1/4 Halaman', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:46:04', 'BucinBGMID', '2025-06-23 15:46:04', 'BucinBGMID', 1),
	(64, 'Iklan Media Cetak 1/2 Halaman', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:46:12', 'BucinBGMID', '2025-06-23 15:46:12', 'BucinBGMID', 1),
	(65, 'Adlibs Trafific Hour', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:46:20', 'BucinBGMID', '2025-06-23 15:46:20', 'BucinBGMID', 1),
	(66, 'Uang Transport Perjalanan dinas di dalam kota yang kurang dari 8 (delapan) jam', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:46:32', 'BucinBGMID', '2025-06-23 15:46:32', 'BucinBGMID', 1),
	(67, 'Papan Akrilik', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:46:50', 'BucinBGMID', '2025-06-23 15:46:50', 'BucinBGMID', 1),
	(68, 'TV 85 Inch', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:46:58', 'BucinBGMID', '2025-06-23 15:46:59', 'BucinBGMID', 1),
	(69, 'Standing Breket TV', NULL, NULL, NULL, NULL, 1, '2025-06-23 15:47:06', 'BucinBGMID', '2025-06-23 15:47:06', 'BucinBGMID', 1),
	(70, 'Gaji SPV (2 org x 12 bln)', '1', NULL, NULL, NULL, 1, '2025-06-24 08:53:44', 'BucinBGMID', '2025-06-30 15:46:57', 'administrator', 1),
	(71, 'Gaji tenaga pelaksana (33 org x 12 bln)', '1', NULL, NULL, NULL, 1, '2025-06-24 08:53:53', 'BucinBGMID', '2025-06-30 15:47:08', 'administrator', 1),
	(72, 'Bahan pakai habis', '0', NULL, NULL, NULL, 1, '2025-06-24 08:54:02', 'BucinBGMID', '2025-06-30 15:47:19', 'administrator', 1),
	(73, 'Sewa Peralatan Outsoursing Cleaning Service', '0', NULL, NULL, NULL, 1, '2025-06-24 08:54:11', 'BucinBGMID', '2025-06-30 15:47:28', 'administrator', 1),
	(74, 'Gaji Satpam (12 org x 12 bln)', '1', NULL, NULL, NULL, 1, '2025-06-24 08:57:55', 'BucinBGMID', '2025-06-30 15:50:43', 'administrator', 1),
	(75, 'Seragam PDH Satpam (Paket)', '1', '0', '0', '0', 1, '2025-06-24 08:58:12', 'BucinBGMID', '2025-08-06 19:55:17', 'superadmin', 1),
	(76, 'Jas hujan security satpam setelan', '1', '0', '0', '0', 1, '2025-06-24 08:58:21', 'BucinBGMID', '2025-08-06 19:56:04', 'superadmin', 1),
	(77, 'Kue Kotak (20 org x 58 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:04:04', 'BucinBGMID', '2025-06-24 09:04:04', 'BucinBGMID', 1),
	(78, 'Nasi Kotak (20 org x 12 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:04:15', 'BucinBGMID', '2025-06-24 09:04:16', 'BucinBGMID', 1),
	(79, 'Kue Kotak (20 org x 12 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:04:24', 'BucinBGMID', '2025-06-24 09:04:24', 'BucinBGMID', 1),
	(80, 'Nasi Kotak (10 org x 5 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:04:35', 'BucinBGMID', '2025-06-24 09:04:36', 'BucinBGMID', 1),
	(81, 'Kue Kotak (10 org x 5 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:04:45', 'BucinBGMID', '2025-06-24 09:04:45', 'BucinBGMID', 1),
	(82, 'Nasi Kotak (30 org x 3 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:05:00', 'BucinBGMID', '2025-06-24 09:05:01', 'BucinBGMID', 1),
	(83, 'Kue Kotak (30 org x 3 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:05:09', 'BucinBGMID', '2025-06-24 09:05:09', 'BucinBGMID', 1),
	(84, 'Nasi Kotak (30 org x 5 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:05:21', 'BucinBGMID', '2025-06-24 09:05:21', 'BucinBGMID', 1),
	(85, 'Kue Kotak (30 org x 5 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:05:29', 'BucinBGMID', '2025-06-24 09:05:30', 'BucinBGMID', 1),
	(86, 'Nasi Kotak (36 org x 6 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:15:47', 'BucinBGMID', '2025-06-24 09:15:48', 'BucinBGMID', 1),
	(87, 'Kue Kotak (36 org x 6 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:15:55', 'BucinBGMID', '2025-06-24 09:15:55', 'BucinBGMID', 1),
	(88, 'Nasi Kotak (40 orang x 1 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:16:07', 'BucinBGMID', '2025-06-24 09:16:08', 'BucinBGMID', 1),
	(89, 'Nasi Kotak (25 org x 4 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:16:16', 'BucinBGMID', '2025-06-24 09:16:16', 'BucinBGMID', 1),
	(90, 'Kue Kotak (25 org x 4 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:16:37', 'BucinBGMID', '2025-06-24 09:16:37', 'BucinBGMID', 1),
	(91, 'Kue Kotak (17 orang x 12 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:16:47', 'BucinBGMID', '2025-06-24 09:16:47', 'BucinBGMID', 1),
	(92, 'Kue Kotak (35 orang x 3 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:17:02', 'BucinBGMID', '2025-06-24 09:17:03', 'BucinBGMID', 1),
	(93, 'Kue Kotak (20 orang x 15 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:18:45', 'BucinBGMID', '2025-06-24 09:18:46', 'BucinBGMID', 1),
	(94, 'Snak u/ senam kebugaran (50 org x 4 minggu x 12 bln)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:18:53', 'BucinBGMID', '2025-06-24 09:18:53', 'BucinBGMID', 1),
	(95, 'Minuman Showcase u/ Pengunjung Rawat Jalan', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:19:01', 'BucinBGMID', '2025-06-24 09:19:01', 'BucinBGMID', 1),
	(96, 'Nasi Kotak (25 org x 8 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:19:15', 'BucinBGMID', '2025-06-24 09:19:16', 'BucinBGMID', 1),
	(97, 'Kue Kotak (25 org x 15 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 09:19:23', 'BucinBGMID', '2025-06-24 09:19:24', 'BucinBGMID', 1),
	(98, 'Transport lokal (30 org x 1 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:48:20', 'BucinBGMID', '2025-06-24 10:48:20', 'BucinBGMID', 1),
	(99, 'Paket souvenir', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:48:44', 'BucinBGMID', '2025-06-24 10:48:45', 'BucinBGMID', 1),
	(100, 'Kue Kotak  (50 org x 4 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:48:58', 'BucinBGMID', '2025-06-24 10:48:59', 'BucinBGMID', 1),
	(101, 'Nasi Kotak  (50 org x 4 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:49:06', 'BucinBGMID', '2025-06-24 10:49:06', 'BucinBGMID', 1),
	(102, 'Strip GDA', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:49:14', 'BucinBGMID', '2025-06-24 10:49:15', 'BucinBGMID', 1),
	(103, 'Strip Asam Urat', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:49:22', 'BucinBGMID', '2025-06-24 10:49:22', 'BucinBGMID', 1),
	(104, 'Kue Kotak   (240 org x 1 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:52:39', 'BucinBGMID', '2025-06-24 10:52:40', 'BucinBGMID', 1),
	(105, 'Kue Kotak  (30 org x 2 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:54:28', 'BucinBGMID', '2025-06-24 10:54:29', 'BucinBGMID', 1),
	(106, 'Nasi Kotak  (30 org x 2 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:54:36', 'BucinBGMID', '2025-06-24 10:54:36', 'BucinBGMID', 1),
	(107, 'Tote Bag Kanvas', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:55:36', 'BucinBGMID', '2025-06-24 10:55:37', 'BucinBGMID', 1),
	(108, 'Paket sembako', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:55:46', 'BucinBGMID', '2025-06-24 10:55:46', 'BucinBGMID', 1),
	(109, 'Tumpeng Besar', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:55:54', 'BucinBGMID', '2025-06-24 10:55:54', 'BucinBGMID', 1),
	(110, 'Sewa Sound system', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:56:15', 'BucinBGMID', '2025-06-24 10:56:15', 'BucinBGMID', 1),
	(111, 'Sewa Panggung', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:56:24', 'BucinBGMID', '2025-06-24 10:56:25', 'BucinBGMID', 1),
	(112, 'Sewa Tenda dan kursi', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:56:39', 'BucinBGMID', '2025-06-24 10:56:39', 'BucinBGMID', 1),
	(113, 'Biaya Transport Lokal', NULL, NULL, NULL, NULL, 1, '2025-06-24 10:56:46', 'BucinBGMID', '2025-06-24 10:56:47', 'BucinBGMID', 1),
	(114, 'Kue Kotak  (415 org x 1 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:02:26', 'BucinBGMID', '2025-06-24 11:02:26', 'BucinBGMID', 1),
	(115, 'Pemeliharaan Ventilator', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:15:16', 'BucinBGMID', '2025-06-24 11:15:17', 'BucinBGMID', 1),
	(116, 'Pemeliharaan C-ARM', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:15:24', 'BucinBGMID', '2025-06-24 11:15:24', 'BucinBGMID', 1),
	(117, 'Pemeliharaan USG', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:15:33', 'BucinBGMID', '2025-06-24 11:15:34', 'BucinBGMID', 1),
	(118, 'Pemeliharaan Pasien Monitor', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:15:43', 'BucinBGMID', '2025-06-24 11:15:43', 'BucinBGMID', 1),
	(119, 'Pemeliharaan Autoclave', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:15:52', 'BucinBGMID', '2025-06-24 11:15:52', 'BucinBGMID', 1),
	(120, 'Pemeliharaan CPAP', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:16:05', 'BucinBGMID', '2025-06-24 11:16:05', 'BucinBGMID', 1),
	(121, 'Pemeliharaan Defibrilator', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:16:11', 'BucinBGMID', '2025-06-24 11:16:12', 'BucinBGMID', 1),
	(122, 'Pemeliharaan ECG', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:16:19', 'BucinBGMID', '2025-06-24 11:16:20', 'BucinBGMID', 1),
	(123, 'Pemeliharaan Infant Warmer', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:16:26', 'BucinBGMID', '2025-06-24 11:16:27', 'BucinBGMID', 1),
	(124, 'Pemeliharaan infuspump', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:16:34', 'BucinBGMID', '2025-06-24 11:16:35', 'BucinBGMID', 1),
	(125, 'Pemeliharaan Foto terapi', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:16:44', 'BucinBGMID', '2025-06-24 11:16:44', 'BucinBGMID', 1),
	(126, 'Pemeliharaan Tens', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:16:55', 'BucinBGMID', '2025-06-24 11:16:56', 'BucinBGMID', 1),
	(127, 'Pemeliharaan Ultrasound terapi', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:17:03', 'BucinBGMID', '2025-06-24 11:17:03', 'BucinBGMID', 1),
	(128, 'Pemeliharaan General X-ray', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:17:10', 'BucinBGMID', '2025-06-24 11:17:11', 'BucinBGMID', 1),
	(129, 'Pemeliharaan Gas Medis', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:17:20', 'BucinBGMID', '2025-06-24 11:17:20', 'BucinBGMID', 1),
	(130, 'Pemeliharaan X Ray Mobile', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:17:31', 'BucinBGMID', '2025-06-24 11:17:31', 'BucinBGMID', 1),
	(131, 'Pemeliharaan Slit Lamp', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:17:41', 'BucinBGMID', '2025-06-24 11:17:41', 'BucinBGMID', 1),
	(132, 'Pemeliharaan Meja Operasi', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:17:49', 'BucinBGMID', '2025-06-24 11:17:49', 'BucinBGMID', 1),
	(133, 'Pemeliharaan anestesi', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:17:56', 'BucinBGMID', '2025-06-24 11:17:57', 'BucinBGMID', 1),
	(134, 'Pemeliharaan USG Obgyn', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:18:03', 'BucinBGMID', '2025-06-24 11:18:03', 'BucinBGMID', 1),
	(135, 'Vital Sign Monitor', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:18:10', 'BucinBGMID', '2025-06-24 11:18:11', 'BucinBGMID', 1),
	(136, 'Pemeliharaan CTG', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:18:22', 'BucinBGMID', '2025-06-24 11:18:23', 'BucinBGMID', 1),
	(137, 'Bongkar pasang AC', '0', '0', '1', '0', 1, '2025-06-24 11:35:32', 'BucinBGMID', '2025-07-25 13:46:39', 'superadmin', 1),
	(138, 'Pemeliharaan AC cassete', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:35:44', 'BucinBGMID', '2025-06-24 11:35:44', 'BucinBGMID', 1),
	(139, 'Pemeliharaan AC floor standing', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:35:51', 'BucinBGMID', '2025-06-24 11:35:51', 'BucinBGMID', 1),
	(140, 'Pemeliharaan AC split', '0', '0', '1', '0', 1, '2025-06-24 11:35:58', 'BucinBGMID', '2025-07-25 13:46:22', 'superadmin', 1),
	(141, 'Pemeliharaan AVR 120', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:36:08', 'BucinBGMID', '2025-06-24 11:36:08', 'BucinBGMID', 1),
	(142, 'Pemeliharaan AVR 150', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:36:16', 'BucinBGMID', '2025-06-24 11:36:16', 'BucinBGMID', 1),
	(143, 'Pemeliharaan AVR 250', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:36:26', 'BucinBGMID', '2025-06-24 11:36:26', 'BucinBGMID', 1),
	(144, 'Pemeliharaan genset', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:36:32', 'BucinBGMID', '2025-06-24 11:36:32', 'BucinBGMID', 1),
	(145, 'Pemeliharaan mesin cuci piring', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:36:39', 'BucinBGMID', '2025-06-24 11:36:40', 'BucinBGMID', 1),
	(146, 'Pemeliharaan Sumur Bor', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:36:46', 'BucinBGMID', '2025-06-24 11:36:47', 'BucinBGMID', 1),
	(147, 'Pemeliharaan Washer', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:36:53', 'BucinBGMID', '2025-06-24 11:36:54', 'BucinBGMID', 1),
	(148, 'Pengisian freon AC', '0', '0', '1', '0', 1, '2025-06-24 11:37:02', 'BucinBGMID', '2025-07-25 13:46:03', 'superadmin', 1),
	(149, 'Perbaikan AC', '0', '0', '1', '0', 1, '2025-06-24 11:37:16', 'BucinBGMID', '2025-07-25 13:45:50', 'superadmin', 1),
	(150, 'Perbaikan dan Pembuatan Taman IPAL', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:37:23', 'BucinBGMID', '2025-06-24 11:37:24', 'BucinBGMID', 1),
	(151, 'Pemeliharaan AC Central Kamar Operasi', '0', '0', '1', '0', 1, '2025-06-24 11:37:30', 'BucinBGMID', '2025-07-25 13:45:33', 'superadmin', 1),
	(152, 'Servis Sliding Automatic Door Non Tormax', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:37:36', 'BucinBGMID', '2025-06-24 11:37:37', 'BucinBGMID', 1),
	(153, 'Perbaikan Panel ATS/AMF dan Perbaikan Kabel Genset', NULL, NULL, NULL, NULL, 1, '2025-06-24 11:37:48', 'BucinBGMID', '2025-06-24 11:37:49', 'BucinBGMID', 1),
	(154, 'Nasi Kotak (55 org x 3 hr x 1 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 13:50:41', 'BucinBGMID', '2025-06-24 13:50:41', 'BucinBGMID', 1),
	(155, 'Kue Kotak (55 org x 3 hr x 2 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 13:50:49', 'BucinBGMID', '2025-06-24 13:50:49', 'BucinBGMID', 1),
	(156, 'Cendera Mata', NULL, NULL, NULL, NULL, 1, '2025-06-24 13:50:56', 'BucinBGMID', '2025-06-24 13:50:57', 'BucinBGMID', 1),
	(157, 'Cetak Sertifikat ', NULL, NULL, NULL, NULL, 1, '2025-06-24 13:51:03', 'BucinBGMID', '2025-06-24 13:51:04', 'BucinBGMID', 1),
	(158, 'Jasa Narasumber Tenaga Ahli 1 org x 9 jam x 3 hari)', NULL, NULL, NULL, NULL, 1, '2025-06-24 13:51:10', 'BucinBGMID', '2025-06-24 13:51:11', 'BucinBGMID', 1),
	(159, 'Penginapan (1 org x 4 hr)', NULL, NULL, NULL, NULL, 1, '2025-06-24 13:51:17', 'BucinBGMID', '2025-06-24 13:51:17', 'BucinBGMID', 1),
	(160, 'Tiket Kereta Api PP (1 org x 1 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 13:51:24', 'BucinBGMID', '2025-06-24 13:51:25', 'BucinBGMID', 1),
	(161, 'Biaya Transport PP (1 org x 1 kali)', NULL, NULL, NULL, NULL, 1, '2025-06-24 13:51:37', 'BucinBGMID', '2025-06-24 13:51:37', 'BucinBGMID', 1),
	(162, 'Dokumentasi kegiatan', NULL, NULL, NULL, NULL, 1, '2025-06-24 13:51:43', 'BucinBGMID', '2025-06-25 21:43:22', 'BucinBGMID', 1),
	(163, 'Honorarium Narasumber atau Pembahas (Pejabat Eselon II ke bawah/yang disetarakan)  1 org x 5 jam x 12 keg', '1', NULL, NULL, NULL, 1, '2025-07-09 19:36:48', 'administrator', '2025-07-09 19:36:49', 'administrator', 1),
	(164, 'Kue Kotak (20 org x 2 kali x 12 keg)', '1', NULL, NULL, NULL, 1, '2025-07-09 19:37:03', 'administrator', '2025-07-09 19:37:03', 'administrator', 1),
	(165, 'Nasi Kotak (20 org x 1 kali x 12 keg)', '1', NULL, NULL, NULL, 1, '2025-07-09 19:37:17', 'administrator', '2025-07-09 19:37:17', 'administrator', 1),
	(166, 'Perencanaan', '0', NULL, NULL, NULL, 1, '2025-07-10 10:24:43', 'administrator', '2025-07-10 10:24:44', 'administrator', 1),
	(167, 'Pengawasan', '0', NULL, NULL, NULL, 1, '2025-07-10 10:25:02', 'administrator', '2025-07-10 10:25:02', 'administrator', 1),
	(168, 'Konstruksi (Fisik)', '0', NULL, NULL, NULL, 1, '2025-07-10 10:25:14', 'administrator', '2025-07-10 10:25:14', 'administrator', 1),
	(169, 'Jasa Narasumber Tenaga Ahli', '0', NULL, NULL, NULL, 1, '2025-07-19 15:49:40', 'superadmin', '2025-07-19 15:49:40', 'superadmin', 1),
	(170, 'Kue Kotak', '0', NULL, NULL, NULL, 1, '2025-07-19 15:50:08', 'superadmin', '2025-07-19 15:50:09', 'superadmin', 1),
	(171, 'Nasi Kotak', '0', NULL, NULL, NULL, 1, '2025-07-19 15:50:34', 'superadmin', '2025-07-22 19:25:40', 'superadmin', 1),
	(172, 'Uang harian (2 org x 1 hari x 12 lokasi)', '1', '0', '0', '0', 1, '2025-08-01 09:05:20', 'superadmin', '2025-08-01 09:05:21', 'superadmin', 1),
	(173, 'Biaya transport (2 orang x 12 lokasi)', '0', '0', '0', '0', 1, '2025-08-01 09:05:46', 'superadmin', '2025-08-01 09:05:47', 'superadmin', 1),
	(174, 'Kue Kotak (20 orang x 20 kali)', '1', '0', '0', '0', 1, '2025-08-05 17:21:49', 'superadmin', '2025-08-05 17:21:50', 'superadmin', 1),
	(175, 'Nasi Kotak (25 orang x 9 kali)', '1', '0', '0', '0', 1, '2025-08-05 17:22:50', 'superadmin', '2025-08-05 17:22:50', 'superadmin', 1),
	(176, 'Tagihan telepon', '0', '0', '0', '0', 1, '2025-08-05 17:25:57', 'superadmin', '2025-08-05 17:25:58', 'superadmin', 1),
	(177, 'Tagihan air', '0', '0', '0', '0', 1, '2025-08-05 17:26:16', 'superadmin', '2025-08-05 17:26:17', 'superadmin', 1),
	(178, 'Tagihan listrik', '0', '0', '0', '0', 1, '2025-08-05 17:26:34', 'superadmin', '2025-08-05 17:26:35', 'superadmin', 1),
	(179, 'Tagihan Internet', '0', '0', '0', '0', 1, '2025-08-05 17:26:51', 'superadmin', '2025-08-05 17:26:51', 'superadmin', 1),
	(180, 'Tagihan Backup Cloud', '0', '0', '0', '0', 1, '2025-08-05 17:27:05', 'superadmin', '2025-08-05 17:27:06', 'superadmin', 1),
	(181, 'Pemeliharaan Cobas C311', '0', '0', '0', '0', 1, '2025-08-05 17:39:54', 'superadmin', '2025-08-05 17:39:55', 'superadmin', 1),
	(182, 'Pemeliharaan Ventilator', '0', '0', '0', '0', 1, '2025-08-05 17:40:08', 'superadmin', '2025-08-05 17:40:09', 'superadmin', 1),
	(183, 'Transport (50 org x 1 kali)', '1', '0', '0', '0', 1, '2025-08-05 20:38:56', 'superadmin', '2025-08-05 20:40:08', 'superadmin', 1),
	(184, 'Kue Kotak  Persiapan (70 org x 1 kali)', '1', '0', '0', '0', 1, '2025-08-05 20:39:54', 'superadmin', '2025-08-05 20:39:54', 'superadmin', 1),
	(185, 'Nasi Kotak Persiapan (70 org x 1 kali)', '1', '0', '0', '0', 1, '2025-08-05 20:40:26', 'superadmin', '2025-08-05 20:40:27', 'superadmin', 1),
	(186, 'Honor Narasumber Kegiatan FKTP', '1', '0', '0', '0', 1, '2025-08-05 20:40:45', 'superadmin', '2025-08-05 20:40:45', 'superadmin', 1),
	(187, 'Jasa Pelayanan', '1', '0', '0', '0', 1, '2025-08-05 20:46:31', 'superadmin', '2025-08-05 20:46:31', 'superadmin', 1),
	(188, 'Jasa Medis dr. Spesialis', '1', '0', '0', '0', 1, '2025-08-05 20:46:50', 'superadmin', '2025-08-05 20:46:51', 'superadmin', 1),
	(189, 'Makan pasien dengan BOR (Bed Occupancy Rate) 70% (33.471 hari perawatan / (131 bed x 365 hari)', '0', '0', '0', '0', 1, '2025-08-05 20:48:47', 'superadmin', '2025-08-05 20:48:47', 'superadmin', 1),
	(190, 'Honor Narasumber (6 orang x 4 hari)', '1', '0', '0', '0', 1, '2025-08-05 21:12:46', 'superadmin', '2025-08-05 21:12:46', 'superadmin', 1),
	(191, 'Kue Kotak (200 orang x 4 hari)', '1', '0', '0', '0', 1, '2025-08-05 21:12:59', 'superadmin', '2025-08-05 21:12:59', 'superadmin', 1),
	(192, 'Nasi Kotak  (25 orang x 4 hari)', '1', '0', '0', '0', 1, '2025-08-05 21:13:12', 'superadmin', '2025-08-05 21:13:13', 'superadmin', 1),
	(193, 'Honor Narasumber (10 orang x 5 jam)', '1', '0', '0', '0', 1, '2025-08-05 21:17:21', 'superadmin', '2025-08-05 21:17:22', 'superadmin', 1),
	(194, 'Kue Kotak (85 orang x 1 hari x 7 kali)', '1', '0', '0', '0', 1, '2025-08-05 21:17:38', 'superadmin', '2025-08-05 21:17:38', 'superadmin', 1),
	(195, 'Nasi Kotak  (65 orang x 1 hari x 3 kali)', '1', '0', '0', '0', 1, '2025-08-05 21:17:53', 'superadmin', '2025-08-05 21:17:54', 'superadmin', 1),
	(196, 'Desain Booth Pameran (EO)', '0', '0', '0', '0', 1, '2025-08-05 21:20:44', 'superadmin', '2025-08-05 21:20:44', 'superadmin', 1),
	(197, 'Uang harian (6 Orang x 3 hari x 1 kali)', '0', '0', '0', '0', 1, '2025-08-05 21:20:58', 'superadmin', '2025-08-05 21:20:59', 'superadmin', 1),
	(198, 'Biaya transport (6 Orang x 1 kali)', '0', '0', '0', '0', 1, '2025-08-05 21:21:13', 'superadmin', '2025-08-05 21:21:13', 'superadmin', 1),
	(199, 'Penginapan (6 org x 2 hari x 1 kali)', '0', '0', '0', '0', 1, '2025-08-05 21:21:29', 'superadmin', '2025-08-05 21:21:29', 'superadmin', 1),
	(201, 'obat', '0', '0', '0', '0', 1, '2025-08-05 21:25:33', 'superadmin', '2025-08-05 21:25:33', 'superadmin', 1),
	(202, 'reagen', '0', '0', '0', '0', 1, '2025-08-05 21:25:46', 'superadmin', '2025-08-05 21:25:47', 'superadmin', 1),
	(203, 'alkes bhp', '0', '0', '0', '0', 1, '2025-08-05 21:25:59', 'superadmin', '2025-08-05 21:26:00', 'superadmin', 1),
	(204, 'gas', '0', '0', '0', '0', 1, '2025-08-05 21:26:10', 'superadmin', '2025-08-05 21:26:10', 'superadmin', 1),
	(205, 'Alkes Pakai Habis Mata (Katarak set) ', '0', '0', '0', '0', 1, '2025-08-05 21:26:26', 'superadmin', '2025-08-05 21:26:27', 'superadmin', 1),
	(206, 'Seragam Pelayanan', '0', '0', '0', '0', 1, '2025-08-05 21:30:42', 'superadmin', '2025-08-05 21:30:43', 'superadmin', 1),
	(207, 'Pemasangan Kembali Membrane Lama Area Pakir Poli Rawat Jalan', '0', '0', '0', '0', 1, '2025-08-05 21:32:46', 'superadmin', '2025-08-05 21:32:47', 'superadmin', 1),
	(208, 'Penyusunan Dokumen Studi Kelayakan Pengembangan Pelayanan (Feasibility Study) Rawat Jalan', '0', '0', '0', '0', 1, '2025-08-05 21:34:02', 'superadmin', '2025-08-05 21:34:02', 'superadmin', 1),
	(209, 'Kue kotak (20 org x 1 kali)', '1', '0', '0', '0', 1, '2025-08-05 21:36:41', 'superadmin', '2025-08-05 21:36:41', 'superadmin', 1),
	(210, 'Nasi kotak  (20 org x 1 kali)', '1', '0', '0', '0', 1, '2025-08-05 21:36:57', 'superadmin', '2025-08-05 21:36:58', 'superadmin', 1),
	(211, 'Lemari Arsip Pintu Kaca', '0', '0', '0', '0', 1, '2025-08-05 21:38:16', 'superadmin', '2025-08-05 21:38:16', 'superadmin', 1),
	(212, 'Phantom Manekin Model CPR Dewasa', '0', '0', '0', '0', 1, '2025-08-05 21:38:39', 'superadmin', '2025-08-05 21:38:39', 'superadmin', 1),
	(213, 'Honor Auditor Independen (KAP)', '0', '0', '0', '0', 1, '2025-08-05 21:41:57', 'superadmin', '2025-08-05 21:41:58', 'superadmin', 1),
	(214, 'Honorarium Narasumber Setara Eselon III', '1', '0', '0', '0', 1, '2025-08-05 21:46:20', 'superadmin', '2025-08-05 21:46:21', 'superadmin', 1);

-- Dumping structure for table budgeting.sub_kegiatan
CREATE TABLE IF NOT EXISTS `sub_kegiatan` (
  `idsub_kegiatan` int NOT NULL AUTO_INCREMENT,
  `no_rekening_subkegiatan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `nama_subkegiatan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `idkegiatan` int DEFAULT NULL,
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idsub_kegiatan`) USING BTREE,
  KEY `is_active` (`is_active`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `no_rekening_urusan` (`no_rekening_subkegiatan`) USING BTREE,
  KEY `nama_urusan` (`nama_subkegiatan`) USING BTREE,
  KEY `tahun_anggaran_urusan` (`idkegiatan`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.sub_kegiatan: ~3 rows (approximately)
INSERT INTO `sub_kegiatan` (`idsub_kegiatan`, `no_rekening_subkegiatan`, `nama_subkegiatan`, `idkegiatan`, `is_active`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(2, '01', 'Pelayanan dan Penunjang Pelayanan BLUD', 2, 1, '2025-06-16 15:24:30', 'BucinBGMID', '2025-06-17 08:53:35', 'BucinBGMID', 1),
	(3, '01', 'Pelayanan dan Penunjang Pelayanan BLUD', 3, 1, '2025-06-17 08:53:19', 'BucinBGMID', '2025-07-22 17:32:31', 'superadmin', 1),
	(4, '0200', 'okokoko', 5, 1, '2025-06-23 14:46:53', 'BucinBGMID', '2025-06-23 14:47:13', 'BucinBGMID', 0),
	(5, '01000000', 'Pelayanan dan Penunjang Pelayanan BLUD 21212', 3, 1, '2025-06-23 14:57:43', 'BucinBGMID', '2025-06-26 07:52:17', 'administrator', 0);

-- Dumping structure for table budgeting.transaction
CREATE TABLE IF NOT EXISTS `transaction` (
  `idtransaction` int NOT NULL AUTO_INCREMENT,
  `transaction_date` datetime DEFAULT NULL COMMENT 'tanggal realisasi',
  `transaction_code` varchar(50) DEFAULT NULL COMMENT 'nomor invoice',
  `transaction_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'DRAFT' COMMENT 'INPUT DATA 		-> data diinputkan dimenu realisasi anggaran (oleh user realisasi); \r\nMENUNGGU VERIFIKASI 	-> data diinputkan di menu verifikasi dokumen (oleh user realisasi); \r\nSUDAH DIVERIFIKASI 	-> data sudah diverifikasi (oleh user verifikator); \r\nINPUT NPD		-> data diinputkan di menu npd (oleh user npd);\r\nMENUNGGU PEMBAYARAN	-> data sudah dikirim ke bendahara (oleh user npd);\r\nSUDAH DIBAYAR BENDAHARA -> data sudah dibayar bendahara (oleh user bendahara);',
  `updated_status` datetime DEFAULT NULL,
  `total_realisasi` double DEFAULT NULL COMMENT 'total dalam 1 invoice',
  `iduser_created` bigint DEFAULT NULL COMMENT 'user yang membuat invoice',
  `iduser_verification` bigint DEFAULT NULL COMMENT 'user yang memverifikasi',
  `iduser_treasurer` bigint DEFAULT NULL COMMENT 'user bendahara',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idtransaction`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `transaction_status` (`transaction_status`),
  KEY `total_realisasi` (`total_realisasi`),
  KEY `iduser_created` (`iduser_created`),
  KEY `iduser_verification` (`iduser_verification`),
  KEY `iduser_treasurer` (`iduser_treasurer`),
  KEY `transaction_date` (`transaction_date`) USING BTREE,
  KEY `transaction_code` (`transaction_code`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.transaction: ~77 rows (approximately)
INSERT INTO `transaction` (`idtransaction`, `transaction_date`, `transaction_code`, `transaction_status`, `updated_status`, `total_realisasi`, `iduser_created`, `iduser_verification`, `iduser_treasurer`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(45, '2025-07-01 14:40:48', 'ON202508140001', 'INPUT DATA', NULL, 4935000, 10, NULL, NULL, '2025-08-14 14:43:42', 'realisasi', '2025-08-14 14:43:44', 'realisasi', 1),
	(46, '2025-07-01 14:43:55', 'ON202508140002', 'INPUT DATA', NULL, 1222980, 10, NULL, NULL, '2025-08-14 14:44:27', 'realisasi', '2025-08-14 14:44:28', 'realisasi', 1),
	(47, '2025-07-01 14:44:38', 'ON202508140003', 'MENUNGGU PEMBAYARAN', '2025-08-15 14:42:49', 214030882, 10, NULL, NULL, '2025-08-14 14:45:19', 'realisasi', '2025-08-14 14:46:14', 'realisasi', 1),
	(48, '2025-07-01 14:46:21', 'ON202508140004', 'SUDAH DIVERIFIKASI', '2025-08-15 11:04:34', 68802036, 10, NULL, NULL, '2025-08-14 14:46:48', 'realisasi', '2025-08-14 14:46:53', 'realisasi', 1),
	(49, '2025-07-03 14:47:25', 'ON202508140005', 'INPUT DATA', NULL, 1472000, 10, NULL, NULL, '2025-08-14 14:47:53', 'realisasi', '2025-08-14 14:47:55', 'realisasi', 1),
	(50, '2025-07-02 14:48:10', 'ON202508140006', 'SUDAH DIVERIFIKASI', '2025-08-15 11:10:40', 69185496, 10, NULL, NULL, '2025-08-14 14:48:41', 'realisasi', '2025-08-14 14:48:43', 'realisasi', 1),
	(51, '2025-07-01 14:48:54', 'ON202508140007', 'SUDAH DIVERIFIKASI', '2025-08-20 17:48:04', 10500000, 10, NULL, NULL, '2025-08-14 14:49:27', 'realisasi', '2025-08-20 17:47:25', 'superadmin', 1),
	(52, '2025-06-30 14:51:56', 'ON202508140008', 'MENUNGGU VERIFIKASI', '2025-08-15 10:17:03', 7998000, 10, NULL, NULL, '2025-08-14 14:52:31', 'realisasi', '2025-08-14 14:54:29', 'realisasi', 1),
	(53, '2025-06-25 14:54:45', 'ON202508140009', 'INPUT DATA', NULL, 6902500, 10, NULL, NULL, '2025-08-14 14:55:24', 'realisasi', '2025-08-15 08:11:07', 'realisasi', 1),
	(54, '2025-06-20 14:57:09', 'ON202508140010', 'INPUT DATA', NULL, 3901100, 10, NULL, NULL, '2025-08-14 14:57:45', 'realisasi', '2025-08-14 14:58:12', 'realisasi', 1),
	(55, '2025-06-01 14:58:18', 'ON202508140011', 'MENUNGGU VERIFIKASI', '2025-08-15 10:12:02', 1724000, 10, NULL, NULL, '2025-08-14 14:58:44', 'realisasi', '2025-08-14 14:59:09', 'realisasi', 1),
	(56, '2025-07-01 14:59:28', 'ON202508140012', 'INPUT DATA', NULL, 70739601, 10, NULL, NULL, '2025-08-14 15:02:07', 'realisasi', '2025-08-15 08:52:45', 'realisasi', 0),
	(57, '2025-01-31 15:43:04', 'ON202508140013', 'INPUT DATA', NULL, 915000, 10, NULL, NULL, '2025-08-14 15:43:40', 'realisasi', '2025-08-14 15:43:43', 'realisasi', 1),
	(58, '2025-01-01 15:43:52', 'ON202508140014', 'SUDAH DIBAYAR BENDAHARA', '2025-08-15 14:44:03', 290998003, 10, NULL, NULL, '2025-08-14 15:44:34', 'realisasi', '2025-08-14 15:50:34', 'realisasi', 1),
	(59, '2025-05-20 07:54:43', 'ON202508150001', 'MENUNGGU VERIFIKASI', '2025-08-15 10:17:03', 15996000, 10, NULL, NULL, '2025-08-15 07:55:06', 'realisasi', '2025-08-15 08:02:53', 'realisasi', 1),
	(60, '2025-05-09 08:04:40', 'ON202508150002', 'INPUT DATA', NULL, 6880900, 10, NULL, NULL, '2025-08-15 08:05:33', 'realisasi', '2025-08-15 08:07:59', 'realisasi', 1),
	(61, '2025-02-19 08:12:06', 'ON202508150003', 'INPUT DATA', NULL, 2265000, 10, NULL, NULL, '2025-08-15 08:12:26', 'realisasi', '2025-08-15 08:12:28', 'realisasi', 1),
	(62, '2025-03-01 08:12:43', 'ON202508150004', 'INPUT DATA', NULL, 2535000, 10, NULL, NULL, '2025-08-15 08:13:02', 'realisasi', '2025-08-15 08:13:04', 'realisasi', 1),
	(63, '2025-04-01 08:13:23', 'ON202508150005', 'INPUT DATA', NULL, 3885000, 10, NULL, NULL, '2025-08-15 08:13:58', 'realisasi', '2025-08-15 08:14:01', 'realisasi', 1),
	(64, '2025-05-01 08:14:12', 'ON202508150006', 'INPUT DATA', NULL, 4290000, 10, NULL, NULL, '2025-08-15 08:14:35', 'realisasi', '2025-08-15 08:14:38', 'realisasi', 1),
	(65, '2025-02-01 08:15:37', 'ON202508150007', 'MENUNGGU VERIFIKASI', '2025-08-15 10:12:02', 918000, 10, NULL, NULL, '2025-08-15 08:16:08', 'realisasi', '2025-08-15 08:16:33', 'realisasi', 1),
	(66, '2025-03-01 08:16:42', 'ON202508150008', 'MENUNGGU VERIFIKASI', '2025-08-15 10:12:02', 714600, 10, NULL, NULL, '2025-08-15 08:16:57', 'realisasi', '2025-08-15 08:17:20', 'realisasi', 1),
	(67, '2025-04-01 08:17:35', 'ON202508150009', 'MENUNGGU VERIFIKASI', '2025-08-15 10:12:02', 1762500, 10, NULL, NULL, '2025-08-15 08:17:48', 'realisasi', '2025-08-15 08:18:08', 'realisasi', 1),
	(68, '2025-05-01 08:18:18', 'ON202508150010', 'MENUNGGU VERIFIKASI', '2025-08-15 10:12:02', 1762500, 10, NULL, NULL, '2025-08-15 08:18:37', 'realisasi', '2025-08-15 08:18:56', 'realisasi', 1),
	(69, '2025-02-01 08:21:34', 'ON202508150011', 'SUDAH DIBAYAR BENDAHARA', '2025-08-15 14:44:03', 291477998, 10, NULL, NULL, '2025-08-15 08:22:01', 'realisasi', '2025-08-15 08:25:31', 'realisasi', 1),
	(70, '2025-03-01 08:25:50', 'ON202508150012', 'SUDAH DIBAYAR BENDAHARA', '2025-08-15 14:44:03', 390827936, 10, NULL, NULL, '2025-08-15 08:26:41', 'realisasi', '2025-08-15 08:31:33', 'realisasi', 1),
	(71, '2025-04-01 08:31:50', 'ON202508150013', 'SUDAH DIBAYAR BENDAHARA', '2025-08-15 14:44:03', 290466965, 10, NULL, NULL, '2025-08-15 08:32:19', 'realisasi', '2025-08-15 08:35:55', 'realisasi', 1),
	(72, '2025-05-01 08:36:15', 'ON202508150014', 'SUDAH DIBAYAR BENDAHARA', '2025-08-15 14:44:03', 112059024, 10, NULL, NULL, '2025-08-15 08:36:38', 'realisasi', '2025-08-15 08:39:37', 'realisasi', 1),
	(73, '2025-06-01 08:40:06', 'ON202508150015', 'INPUT DATA', NULL, 484688953, 10, NULL, NULL, '2025-08-15 08:40:37', 'realisasi', '2025-08-15 08:48:57', 'realisasi', 1),
	(74, '2025-01-10 08:53:40', 'ON202508150016', 'INPUT DATA', NULL, 550000, 10, NULL, NULL, '2025-08-15 08:54:08', 'realisasi', '2025-08-15 08:54:27', 'realisasi', 1),
	(75, '2025-02-01 08:54:36', 'ON202508150017', 'DITOLAK VERIFIKATOR', '2025-08-20 17:38:03', 5526600, 10, NULL, NULL, '2025-08-15 08:54:59', 'realisasi', '2025-08-15 08:55:20', 'realisasi', 1),
	(76, '2025-03-01 09:01:36', 'ON202508150018', 'INPUT DATA', NULL, 550000, 10, NULL, NULL, '2025-08-15 09:02:03', 'realisasi', '2025-08-15 09:02:23', 'realisasi', 1),
	(77, '2025-04-01 09:02:33', 'ON202508150019', 'INPUT DATA', NULL, 750000, 10, NULL, NULL, '2025-08-15 09:02:50', 'realisasi', '2025-08-15 09:03:05', 'realisasi', 1),
	(78, '2025-05-01 09:03:13', 'ON202508150020', 'DITOLAK VERIFIKATOR', '2025-08-20 17:38:03', 1425000, 10, NULL, NULL, '2025-08-15 09:03:38', 'realisasi', '2025-08-15 09:03:55', 'realisasi', 1),
	(79, '2025-01-01 09:05:28', 'ON202508150021', 'INPUT DATA', NULL, 1913000, 10, NULL, NULL, '2025-08-15 09:05:57', 'realisasi', '2025-08-15 09:07:42', 'realisasi', 1),
	(80, '2025-02-27 09:08:06', 'ON202508150022', 'INPUT DATA', NULL, 2882900, 10, NULL, NULL, '2025-08-15 09:08:32', 'realisasi', '2025-08-15 09:09:36', 'realisasi', 1),
	(81, '2025-03-13 09:09:47', 'ON202508150023', 'INPUT DATA', NULL, 2000500, 10, NULL, NULL, '2025-08-15 09:10:08', 'realisasi', '2025-08-15 09:10:41', 'realisasi', 1),
	(82, '2025-04-24 09:10:49', 'ON202508150024', 'INPUT DATA', NULL, 9505000, 10, NULL, NULL, '2025-08-15 09:11:08', 'realisasi', '2025-08-15 09:12:10', 'realisasi', 1),
	(83, '2025-05-05 09:13:22', 'ON202508150025', 'INPUT DATA', NULL, 1149500, 10, NULL, NULL, '2025-08-15 09:13:39', 'realisasi', '2025-08-15 09:14:02', 'realisasi', 1),
	(84, '2025-06-05 09:14:22', 'ON202508150026', 'INPUT DATA', NULL, 1358500, 10, NULL, NULL, '2025-08-15 09:14:42', 'realisasi', '2025-08-15 09:15:30', 'realisasi', 1),
	(85, '2025-01-01 09:16:56', 'ON202508150027', 'SUDAH DIBAYAR BENDAHARA', '2025-08-15 14:44:37', 191881000, 10, NULL, NULL, '2025-08-15 09:17:20', 'realisasi', '2025-08-15 09:17:58', 'realisasi', 1),
	(86, '2025-02-13 09:18:06', 'ON202508150028', 'SUDAH DIBAYAR BENDAHARA', '2025-08-15 14:44:37', 233700590, 10, NULL, NULL, '2025-08-15 09:18:35', 'realisasi', '2025-08-15 09:19:33', 'realisasi', 1),
	(87, '2025-03-01 09:19:53', 'ON202508150029', 'SUDAH DIBAYAR BENDAHARA', '2025-08-15 14:44:37', 191881000, 10, NULL, NULL, '2025-08-15 09:20:12', 'realisasi', '2025-08-15 09:20:30', 'realisasi', 1),
	(88, '2025-04-01 09:20:43', 'ON202508150030', 'SUDAH DIBAYAR BENDAHARA', '2025-08-15 14:44:37', 191881000, 10, NULL, NULL, '2025-08-15 09:21:01', 'realisasi', '2025-08-15 09:21:20', 'realisasi', 1),
	(89, '2025-05-01 09:21:33', 'ON202508150031', 'MENUNGGU PEMBAYARAN', '2025-08-15 14:42:49', 191881000, 10, NULL, NULL, '2025-08-15 09:21:50', 'realisasi', '2025-08-15 09:22:07', 'realisasi', 1),
	(90, '2025-06-01 09:22:18', 'ON202508150032', 'MENUNGGU PEMBAYARAN', '2025-08-15 14:42:49', 191881000, 10, NULL, NULL, '2025-08-15 09:22:34', 'realisasi', '2025-08-15 09:22:50', 'realisasi', 1),
	(91, '2025-01-01 09:28:57', 'ON202508150033', 'INPUT NPD', '2025-08-15 14:38:30', 68802036, 10, NULL, NULL, '2025-08-15 09:29:38', 'realisasi', '2025-08-15 09:29:40', 'realisasi', 1),
	(92, '2025-02-01 09:29:45', 'ON202508150034', 'INPUT NPD', '2025-08-15 14:38:30', 68802036, 10, NULL, NULL, '2025-08-15 09:30:11', 'realisasi', '2025-08-15 09:30:13', 'realisasi', 1),
	(93, '2025-03-01 09:30:22', 'ON202508150035', 'INPUT NPD', '2025-08-15 14:38:30', 68802036, 10, NULL, NULL, '2025-08-15 09:30:40', 'realisasi', '2025-08-15 09:30:42', 'realisasi', 1),
	(94, '2025-04-01 09:30:50', 'ON202508150036', 'SUDAH DIVERIFIKASI', '2025-08-15 11:04:43', 68802036, 10, NULL, NULL, '2025-08-15 09:31:13', 'realisasi', '2025-08-15 09:31:15', 'realisasi', 1),
	(95, '2025-05-01 09:31:25', 'ON202508150037', 'SUDAH DIVERIFIKASI', '2025-08-15 11:04:43', 82052036, 10, NULL, NULL, '2025-08-15 09:31:54', 'realisasi', '2025-08-15 09:32:28', 'realisasi', 1),
	(96, '2025-06-01 09:32:39', 'ON202508150038', 'SUDAH DIVERIFIKASI', '2025-08-15 11:04:34', 68802036, 10, NULL, NULL, '2025-08-15 09:32:59', 'realisasi', '2025-08-15 09:33:08', 'realisasi', 1),
	(97, '2025-01-12 09:34:10', 'ON202508150039', 'MENUNGGU VERIFIKASI', '2025-08-15 10:22:36', 72190840, 10, NULL, NULL, '2025-08-15 09:34:34', 'realisasi', '2025-08-15 09:36:05', 'realisasi', 1),
	(98, '2025-06-18 09:36:08', 'ON202508150040', 'INPUT DATA', NULL, 4800000, 10, NULL, NULL, '2025-08-15 09:39:02', 'realisasi', '2025-08-15 09:39:42', 'realisasi', 1),
	(99, '2025-01-01 09:41:44', 'ON202508150041', 'INPUT NPD', '2025-08-15 14:38:51', 60000000, 10, NULL, NULL, '2025-08-15 09:42:20', 'realisasi', '2025-08-15 09:42:24', 'realisasi', 1),
	(100, '2025-03-20 09:42:34', 'ON202508150042', 'INPUT NPD', '2025-08-15 14:38:51', 1494520004, 10, NULL, NULL, '2025-08-15 09:43:01', 'realisasi', '2025-08-15 09:43:04', 'realisasi', 1),
	(101, '2025-01-01 09:43:43', 'ON202508150043', 'INPUT DATA', NULL, 231907166, 10, NULL, NULL, '2025-08-15 09:44:09', 'realisasi', '2025-08-15 09:44:10', 'realisasi', 1),
	(102, '2025-02-01 09:44:16', 'ON202508150044', 'INPUT DATA', NULL, 197419365, 10, NULL, NULL, '2025-08-15 09:44:36', 'realisasi', '2025-08-15 09:44:38', 'realisasi', 1),
	(103, '2025-03-01 09:44:44', 'ON202508150045', 'INPUT DATA', NULL, 182307476, 10, NULL, NULL, '2025-08-15 09:45:02', 'realisasi', '2025-08-15 09:45:05', 'realisasi', 1),
	(104, '2025-04-01 09:48:01', 'ON202508150046', 'INPUT DATA', NULL, 214326000, 10, NULL, NULL, '2025-08-15 09:48:19', 'realisasi', '2025-08-15 09:48:21', 'realisasi', 1),
	(105, '2025-05-16 09:50:27', 'ON202508150047', 'INPUT DATA', NULL, 900000, 10, NULL, NULL, '2025-08-15 09:50:53', 'realisasi', '2025-08-15 09:50:57', 'realisasi', 1),
	(106, '2025-06-02 09:51:21', 'ON202508150048', 'INPUT DATA', '2025-08-15 14:36:03', 6550000, 10, NULL, NULL, '2025-08-15 09:51:49', 'realisasi', '2025-08-15 09:52:08', 'realisasi', 1),
	(107, '2025-01-01 09:52:50', 'ON202508150049', 'INPUT DATA', NULL, 12200000, 10, NULL, NULL, '2025-08-15 09:53:10', 'realisasi', '2025-08-15 09:53:13', 'realisasi', 1),
	(108, '2025-02-15 09:53:24', 'ON202508150050', 'INPUT DATA', NULL, 439955964, 10, NULL, NULL, '2025-08-15 09:53:57', 'realisasi', '2025-08-15 09:54:18', 'realisasi', 1),
	(109, '2025-03-01 09:54:31', 'ON202508150051', 'INPUT DATA', NULL, 113708800, 10, NULL, NULL, '2025-08-15 09:54:52', 'realisasi', '2025-08-15 09:54:55', 'realisasi', 1),
	(110, '2025-04-01 09:55:04', 'ON202508150052', 'INPUT DATA', NULL, 40814895, 10, NULL, NULL, '2025-08-15 09:55:25', 'realisasi', '2025-08-15 09:55:43', 'realisasi', 1),
	(111, '2025-05-01 09:55:51', 'ON202508150053', 'INPUT DATA', NULL, 36720000, 10, NULL, NULL, '2025-08-15 09:56:10', 'realisasi', '2025-08-15 09:56:12', 'realisasi', 1),
	(112, '2025-06-04 09:56:20', 'ON202508150054', 'INPUT DATA', NULL, 13860000, 10, NULL, NULL, '2025-08-15 09:56:40', 'realisasi', '2025-08-15 09:56:41', 'realisasi', 1),
	(113, '2025-07-01 09:56:47', 'ON202508150055', 'INPUT DATA', NULL, 15120000, 10, NULL, NULL, '2025-08-15 09:57:05', 'realisasi', '2025-08-15 09:57:07', 'realisasi', 1),
	(114, '2025-01-30 09:57:45', 'ON202508150056', 'MENUNGGU VERIFIKASI', '2025-08-15 10:13:01', 3650000, 10, NULL, NULL, '2025-08-15 09:58:04', 'realisasi', '2025-08-15 09:58:19', 'realisasi', 1),
	(115, '2025-02-25 09:58:34', 'ON202508150057', 'MENUNGGU VERIFIKASI', '2025-08-15 10:13:01', 1600000, 10, NULL, NULL, '2025-08-15 09:58:54', 'realisasi', '2025-08-15 09:59:08', 'realisasi', 1),
	(116, '2025-03-25 09:59:21', 'ON202508150058', 'INPUT DATA', NULL, 67800000, 10, NULL, NULL, '2025-08-15 09:59:41', 'realisasi', '2025-08-15 10:00:24', 'realisasi', 1),
	(117, '2025-02-12 10:00:58', 'ON202508150059', 'INPUT DATA', NULL, 4250000, 10, NULL, NULL, '2025-08-15 10:01:27', 'realisasi', '2025-08-15 10:01:44', 'realisasi', 1),
	(118, '2025-05-01 10:02:11', 'ON202508150060', 'INPUT DATA', NULL, 11460894, 10, NULL, NULL, '2025-08-15 10:02:35', 'realisasi', '2025-08-15 10:02:37', 'realisasi', 1),
	(119, '2025-03-01 10:03:06', 'ON202508150061', 'INPUT DATA', NULL, 15423299, 10, NULL, NULL, '2025-08-15 10:03:28', 'realisasi', '2025-08-15 10:03:31', 'realisasi', 1),
	(120, '2025-07-01 10:04:04', 'ON202508150062', 'MENUNGGU VERIFIKASI', '2025-08-15 10:24:27', 25008300, 10, NULL, NULL, '2025-08-15 10:04:29', 'realisasi', '2025-08-15 10:04:32', 'realisasi', 1),
	(121, '2025-05-01 10:04:56', 'ON202508150063', 'SUDAH DIVERIFIKASI', '2025-08-15 10:59:22', 50000172, 10, NULL, NULL, '2025-08-15 10:05:21', 'realisasi', '2025-08-15 10:05:23', 'realisasi', 1);

-- Dumping structure for table budgeting.transaction_detail
CREATE TABLE IF NOT EXISTS `transaction_detail` (
  `idtransaction_detail` bigint NOT NULL AUTO_INCREMENT,
  `idtransaction` int NOT NULL,
  `idpaket_belanja` int DEFAULT NULL,
  `penyedia` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `iduraian` int DEFAULT NULL,
  `idruang` int DEFAULT NULL,
  `name_training` varchar(200) DEFAULT NULL,
  `volume` double DEFAULT NULL,
  `laki` double DEFAULT NULL,
  `perempuan` double DEFAULT NULL,
  `harga_satuan` double DEFAULT NULL,
  `ppn` double DEFAULT NULL,
  `pph` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `transaction_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idtransaction_detail`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `idtransaction` (`idtransaction`) USING BTREE,
  KEY `idpaket_belanja` (`idpaket_belanja`) USING BTREE,
  KEY `iduraian` (`iduraian`) USING BTREE,
  KEY `volume` (`volume`) USING BTREE,
  KEY `harga_satuan` (`harga_satuan`) USING BTREE,
  KEY `total` (`total`) USING BTREE,
  KEY `penyedia` (`penyedia`) USING BTREE,
  KEY `idruang` (`idruang`),
  KEY `name_training` (`name_training`),
  KEY `laki` (`laki`),
  KEY `perempuan` (`perempuan`),
  KEY `ppn` (`ppn`),
  KEY `pph` (`pph`),
  CONSTRAINT `transaction_detail_ibfk_1` FOREIGN KEY (`idtransaction`) REFERENCES `transaction` (`idtransaction`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=382 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.transaction_detail: ~195 rows (approximately)
INSERT INTO `transaction_detail` (`idtransaction_detail`, `idtransaction`, `idpaket_belanja`, `penyedia`, `iduraian`, `idruang`, `name_training`, `volume`, `laki`, `perempuan`, `harga_satuan`, `ppn`, `pph`, `total`, `transaction_description`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(173, 45, 21, '-', 30, NULL, '', 329, 109, 220, 15000, 0, 0, 4935000, '', '2025-08-14 14:43:42', 'realisasi', '2025-08-14 14:43:42', 'realisasi', 1),
	(174, 46, 25, 'MUSA GRAFIKA', 56, NULL, '', 1, 0, 0, 1222980, 0, 0, 1222980, '', '2025-08-14 14:44:27', 'realisasi', '2025-08-14 14:44:27', 'realisasi', 1),
	(175, 47, 26, '-', 70, NULL, '', 2, 1, 1, 5570000, 0, 0, 11140000, '', '2025-08-14 14:45:19', 'realisasi', '2025-08-14 14:45:20', 'realisasi', 1),
	(176, 47, 26, '-', 71, NULL, '', 33, 26, 7, 5477000, 0, 0, 180741000, '', '2025-08-14 14:45:50', 'realisasi', '2025-08-14 14:45:50', 'realisasi', 1),
	(177, 47, 26, '-', 72, NULL, '', 1, 0, 0, 22149882, 0, 0, 22149882, '', '2025-08-14 14:46:09', 'realisasi', '2025-08-14 14:46:10', 'realisasi', 1),
	(178, 48, 27, '-', 74, NULL, '', 12, 12, 0, 5733503, 0, 0, 68802036, '', '2025-08-14 14:46:48', 'realisasi', '2025-08-14 14:46:49', 'realisasi', 1),
	(179, 49, 28, 'UD ESTU AGUNG', 95, NULL, '', 46, 0, 0, 32000, 0, 0, 1472000, '', '2025-08-14 14:47:53', 'realisasi', '2025-08-14 14:47:54', 'realisasi', 1),
	(180, 50, 31, 'PT. Rizia Mitra Sejati', 115, NULL, '', 2, 0, 0, 34592748, 0, 0, 69185496, '', '2025-08-14 14:48:41', 'realisasi', '2025-08-14 14:48:41', 'realisasi', 1),
	(181, 51, 32, '-', 144, NULL, '', 5, 0, 0, 2100000, 0, 0, 10500000, '', '2025-08-14 14:49:27', 'realisasi', '2025-08-20 17:46:37', 'superadmin', 1),
	(182, 52, 19, '-', 16, NULL, '', 4, 4, 0, 495000, 0, 0, 1980000, '', '2025-08-14 14:52:31', 'realisasi', '2025-08-14 14:52:31', 'realisasi', 1),
	(183, 52, 19, '-', 17, NULL, '', 2, 0, 2, 1269000, 0, 0, 2538000, '', '2025-08-14 14:52:47', 'realisasi', '2025-08-14 14:52:48', 'realisasi', 1),
	(184, 52, 19, '-', 18, NULL, '', 2, 2, 0, 1090000, 0, 0, 2180000, '', '2025-08-14 14:53:11', 'realisasi', '2025-08-14 14:53:11', 'realisasi', 1),
	(185, 52, 19, '-', 19, NULL, '', 4, 0, 4, 125000, 0, 0, 500000, '', '2025-08-14 14:53:47', 'realisasi', '2025-08-14 14:53:48', 'realisasi', 1),
	(186, 52, 19, '-', 20, NULL, '', 4, 2, 2, 100000, 0, 0, 400000, '', '2025-08-14 14:54:09', 'realisasi', '2025-08-14 14:54:09', 'realisasi', 1),
	(187, 52, 19, '-', 21, NULL, '', 4, 0, 4, 100000, 0, 0, 400000, '', '2025-08-14 14:54:25', 'realisasi', '2025-08-14 14:54:25', 'realisasi', 1),
	(188, 53, 20, '-', 22, NULL, '', 1, 1, 0, 2185000, 0, 115000, 2070000, '', '2025-08-14 14:55:24', 'realisasi', '2025-08-15 08:10:45', 'realisasi', 1),
	(189, 53, 20, '-', 23, NULL, '', 1, 1, 0, 118750, 0, 6250, 112500, '', '2025-08-14 14:55:42', 'realisasi', '2025-08-15 08:10:53', 'realisasi', 1),
	(190, 53, 20, '-', 24, NULL, '', 2, 2, 0, 1900000, 0, 100000, 3700000, '', '2025-08-14 14:56:02', 'realisasi', '2025-08-15 08:10:59', 'realisasi', 1),
	(191, 53, 20, '-', 25, NULL, '', 17, 12, 5, 40000, 0, 0, 680000, '', '2025-08-14 14:56:23', 'realisasi', '2025-08-14 14:56:23', 'realisasi', 1),
	(192, 53, 20, '-', 26, NULL, '', 17, 12, 5, 20000, 0, 0, 340000, '', '2025-08-14 14:56:43', 'realisasi', '2025-08-14 14:56:44', 'realisasi', 1),
	(193, 54, 21, 'J&T', 29, NULL, '', 1, 0, 0, 31100, 0, 0, 31100, '', '2025-08-14 14:57:45', 'realisasi', '2025-08-14 14:57:46', 'realisasi', 1),
	(194, 54, 21, '-', 30, NULL, '', 258, 68, 190, 15000, 0, 0, 3870000, '', '2025-08-14 14:58:06', 'realisasi', '2025-08-14 14:58:07', 'realisasi', 1),
	(195, 55, 22, '-', 172, NULL, '', 4, 3, 1, 200000, 0, 0, 800000, '', '2025-08-14 14:58:44', 'realisasi', '2025-08-14 14:58:45', 'realisasi', 1),
	(196, 55, 22, '-', 173, NULL, '', 2, 0, 0, 462000, 0, 0, 924000, '', '2025-08-14 14:59:07', 'realisasi', '2025-08-14 14:59:07', 'realisasi', 1),
	(197, 56, 23, '-', 31, NULL, '', 9, 2, 7, 2842053, 0, 0, 25578477, '', '2025-08-14 15:02:07', 'realisasi', '2025-08-14 15:02:08', 'realisasi', 1),
	(198, 56, 23, '-', 32, NULL, '', 12, 6, 6, 3266407, 0, 0, 39196884, '', '2025-08-14 15:02:24', 'realisasi', '2025-08-14 15:02:25', 'realisasi', 1),
	(199, 56, 23, '-', 33, NULL, '', 2, 2, 0, 2982120, 0, 0, 5964240, '', '2025-08-14 15:02:54', 'realisasi', '2025-08-14 15:02:54', 'realisasi', 1),
	(200, 57, 21, 'koperasi', 30, NULL, '', 61, 29, 32, 15000, 0, 0, 915000, '', '2025-08-14 15:43:40', 'realisasi', '2025-08-14 15:43:41', 'realisasi', 1),
	(201, 58, 23, '-', 31, NULL, '', 9, 2, 7, 2835387, 0, 0, 25518483, '', '2025-08-14 15:44:34', 'realisasi', '2025-08-14 15:44:35', 'realisasi', 1),
	(202, 58, 23, '-', 32, NULL, '', 12, 6, 6, 3331407, 0, 0, 39976884, '', '2025-08-14 15:45:13', 'realisasi', '2025-08-14 15:45:14', 'realisasi', 1),
	(203, 58, 23, '-', 33, NULL, '', 2, 2, 0, 2922120, 0, 0, 5844240, '', '2025-08-14 15:45:37', 'realisasi', '2025-08-14 15:45:38', 'realisasi', 1),
	(204, 58, 23, '-', 38, NULL, '', 49, 43, 6, 2859264, 0, 0, 140103936, '', '2025-08-14 15:46:26', 'realisasi', '2025-08-14 15:46:26', 'realisasi', 1),
	(205, 58, 23, '-', 39, NULL, '', 4, 4, 0, 3014300, 0, 0, 12057200, '', '2025-08-14 15:47:11', 'realisasi', '2025-08-14 15:47:11', 'realisasi', 1),
	(206, 58, 23, '-', 32, NULL, '', 8, 1, 7, 3215980, 0, 0, 25727840, '', '2025-08-14 15:47:53', 'realisasi', '2025-08-14 15:47:53', 'realisasi', 1),
	(207, 58, 23, '-', 44, NULL, '', 84, 58, 26, 11654, 0, 0, 978936, '', '2025-08-14 15:48:24', 'realisasi', '2025-08-14 15:48:24', 'realisasi', 1),
	(208, 58, 23, '-', 45, NULL, '', 84, 58, 26, 14568, 0, 0, 1223712, '', '2025-08-14 15:48:49', 'realisasi', '2025-08-14 15:48:49', 'realisasi', 1),
	(209, 58, 23, '-', 46, NULL, '', 84, 58, 26, 194241, 0, 0, 16316244, '', '2025-08-14 15:49:18', 'realisasi', '2025-08-14 15:49:19', 'realisasi', 1),
	(210, 58, 23, '-', 47, NULL, '', 84, 58, 26, 97120, 0, 0, 8158080, '', '2025-08-14 15:49:42', 'realisasi', '2025-08-14 15:49:43', 'realisasi', 1),
	(211, 58, 23, '-', 48, NULL, '', 84, 58, 26, 179672, 0, 0, 15092448, '', '2025-08-14 15:50:29', 'realisasi', '2025-08-14 15:50:29', 'realisasi', 1),
	(212, 59, 19, '-', 16, NULL, '', 8, 8, 0, 495000, 0, 0, 3960000, '', '2025-08-15 07:55:06', 'realisasi', '2025-08-15 07:55:06', 'realisasi', 1),
	(213, 59, 19, '-', 17, NULL, '', 4, 0, 4, 1269000, 0, 0, 5076000, '', '2025-08-15 07:55:21', 'realisasi', '2025-08-15 07:55:22', 'realisasi', 1),
	(214, 59, 19, '-', 18, NULL, '', 4, 4, 0, 1090000, 0, 0, 4360000, '', '2025-08-15 07:55:40', 'realisasi', '2025-08-15 07:55:40', 'realisasi', 1),
	(215, 59, 19, '-', 19, NULL, '', 8, 0, 8, 125000, 0, 0, 1000000, '', '2025-08-15 08:02:19', 'realisasi', '2025-08-15 08:02:19', 'realisasi', 1),
	(216, 59, 19, '-', 20, NULL, '', 8, 4, 4, 100000, 0, 0, 800000, '', '2025-08-15 08:02:36', 'realisasi', '2025-08-15 08:02:36', 'realisasi', 1),
	(217, 59, 19, '-', 21, NULL, '', 8, 0, 8, 100000, 0, 0, 800000, '', '2025-08-15 08:02:51', 'realisasi', '2025-08-15 08:02:51', 'realisasi', 1),
	(218, 60, 20, 'Siti Munawaroh', 22, NULL, '', 1, 0, 1, 2185000, 0, 115000, 2070000, '', '2025-08-15 08:05:33', 'realisasi', '2025-08-15 08:06:08', 'realisasi', 1),
	(219, 60, 20, 'Siti Munawaroh', 23, NULL, '', 1, 0, 1, 118750, 0, 6250, 112500, '', '2025-08-15 08:05:56', 'realisasi', '2025-08-15 08:06:15', 'realisasi', 1),
	(220, 60, 20, 'Siti Munawaroh', 24, NULL, '', 2, 2, 0, 1900000, 0, 100000, 3700000, '', '2025-08-15 08:06:38', 'realisasi', '2025-08-15 08:06:38', 'realisasi', 1),
	(221, 60, 20, 'NIKI ECHO', 25, NULL, '', 17, 12, 5, 39200, 0, 800, 665600, '', '2025-08-15 08:07:07', 'realisasi', '2025-08-15 08:07:19', 'realisasi', 1),
	(222, 60, 20, 'NIKI ECHO', 26, NULL, '', 17, 12, 5, 19600, 0, 400, 332800, '', '2025-08-15 08:07:50', 'realisasi', '2025-08-15 08:07:51', 'realisasi', 1),
	(223, 61, 21, 'koperasi', 30, NULL, '', 151, 78, 73, 15000, 0, 0, 2265000, '', '2025-08-15 08:12:26', 'realisasi', '2025-08-15 08:12:27', 'realisasi', 1),
	(224, 62, 21, '-', 30, NULL, '', 169, 61, 108, 15000, 0, 0, 2535000, '', '2025-08-15 08:13:02', 'realisasi', '2025-08-15 08:13:02', 'realisasi', 1),
	(225, 63, 21, '-', 30, NULL, '', 259, 79, 180, 15000, 0, 0, 3885000, '', '2025-08-15 08:13:58', 'realisasi', '2025-08-15 08:13:58', 'realisasi', 1),
	(226, 64, 21, '-', 30, NULL, '', 286, 130, 156, 15000, 0, 0, 4290000, '', '2025-08-15 08:14:35', 'realisasi', '2025-08-15 08:14:36', 'realisasi', 1),
	(227, 65, 22, '-', 172, NULL, '', 2, 2, 0, 200000, 0, 0, 400000, '', '2025-08-15 08:16:08', 'realisasi', '2025-08-15 08:16:08', 'realisasi', 1),
	(228, 65, 22, '-', 173, NULL, '', 1, 0, 0, 518000, 0, 0, 518000, '', '2025-08-15 08:16:30', 'realisasi', '2025-08-15 08:16:31', 'realisasi', 1),
	(229, 66, 22, '-', 172, NULL, '', 2, 1, 1, 150000, 0, 0, 300000, '', '2025-08-15 08:16:57', 'realisasi', '2025-08-15 08:16:57', 'realisasi', 1),
	(230, 66, 22, '-', 173, NULL, '', 1, 0, 0, 414600, 0, 0, 414600, '', '2025-08-15 08:17:13', 'realisasi', '2025-08-15 08:17:13', 'realisasi', 1),
	(231, 67, 22, '-', 172, NULL, '', 4, 4, 0, 200000, 0, 0, 800000, '', '2025-08-15 08:17:48', 'realisasi', '2025-08-15 08:17:49', 'realisasi', 1),
	(232, 67, 22, '-', 173, NULL, '', 2, 0, 0, 481250, 0, 0, 962500, '', '2025-08-15 08:18:05', 'realisasi', '2025-08-15 08:18:06', 'realisasi', 1),
	(233, 68, 22, '-', 172, NULL, '', 4, 3, 1, 200000, 0, 0, 800000, '', '2025-08-15 08:18:37', 'realisasi', '2025-08-15 08:18:38', 'realisasi', 1),
	(234, 68, 22, '-', 173, NULL, '', 2, 0, 0, 481250, 0, 0, 962500, '', '2025-08-15 08:18:53', 'realisasi', '2025-08-15 08:18:54', 'realisasi', 1),
	(235, 69, 23, '-', 31, NULL, '', 9, 2, 7, 2858720, 0, 0, 25728480, '', '2025-08-15 08:22:01', 'realisasi', '2025-08-15 08:22:01', 'realisasi', 1),
	(236, 69, 23, '-', 32, NULL, '', 12, 6, 6, 3348907, 0, 0, 40186884, '', '2025-08-15 08:22:16', 'realisasi', '2025-08-15 08:22:16', 'realisasi', 1),
	(237, 69, 23, '-', 33, NULL, '', 2, 2, 0, 3072120, 0, 0, 6144240, '', '2025-08-15 08:22:32', 'realisasi', '2025-08-15 08:22:33', 'realisasi', 1),
	(238, 69, 23, '-', 38, NULL, '', 49, 43, 6, 2854366, 0, 0, 139863934, '', '2025-08-15 08:22:58', 'realisasi', '2025-08-15 08:22:59', 'realisasi', 1),
	(239, 69, 23, '-', 39, NULL, '', 4, 4, 0, 2999300, 0, 0, 11997200, '', '2025-08-15 08:23:19', 'realisasi', '2025-08-15 08:23:19', 'realisasi', 1),
	(240, 69, 23, '-', 32, NULL, '', 8, 1, 7, 3223480, 0, 0, 25787840, '', '2025-08-15 08:23:48', 'realisasi', '2025-08-15 08:23:48', 'realisasi', 1),
	(241, 69, 23, '-', 44, NULL, '', 84, 58, 26, 11654, 0, 0, 978936, '', '2025-08-15 08:24:12', 'realisasi', '2025-08-15 08:24:12', 'realisasi', 1),
	(242, 69, 23, '-', 45, NULL, '', 84, 58, 26, 14568, 0, 0, 1223712, '', '2025-08-15 08:24:30', 'realisasi', '2025-08-15 08:24:30', 'realisasi', 1),
	(243, 69, 23, '-', 46, NULL, '', 84, 58, 26, 194241, 0, 0, 16316244, '', '2025-08-15 08:24:52', 'realisasi', '2025-08-15 08:24:53', 'realisasi', 1),
	(244, 69, 23, '-', 47, NULL, '', 84, 58, 26, 97120, 0, 0, 8158080, '', '2025-08-15 08:25:12', 'realisasi', '2025-08-15 08:25:13', 'realisasi', 1),
	(245, 69, 23, '-', 48, NULL, '', 84, 58, 26, 179672, 0, 0, 15092448, '', '2025-08-15 08:25:28', 'realisasi', '2025-08-15 08:25:28', 'realisasi', 1),
	(246, 70, 23, '-', 31, NULL, '', 9, 2, 7, 2818720, 0, 0, 25368480, '', '2025-08-15 08:26:41', 'realisasi', '2025-08-15 08:26:41', 'realisasi', 1),
	(247, 70, 23, '-', 32, NULL, '', 12, 6, 6, 3296407, 0, 0, 39556884, '', '2025-08-15 08:27:01', 'realisasi', '2025-08-15 08:27:02', 'realisasi', 1),
	(248, 70, 23, '-', 33, NULL, '', 2, 2, 0, 3027120, 0, 0, 6054240, '', '2025-08-15 08:27:18', 'realisasi', '2025-08-15 08:27:18', 'realisasi', 1),
	(249, 70, 23, '-', 37, NULL, '', 23, 10, 13, 1191304, 0, 0, 27399992, '', '2025-08-15 08:27:43', 'realisasi', '2025-08-15 08:27:44', 'realisasi', 1),
	(250, 70, 23, '-', 38, NULL, '', 49, 43, 6, 2858651, 0, 0, 140073899, '', '2025-08-15 08:28:09', 'realisasi', '2025-08-15 08:28:10', 'realisasi', 1),
	(251, 70, 23, '-', 39, NULL, '', 4, 4, 0, 2946800, 0, 0, 11787200, '', '2025-08-15 08:28:30', 'realisasi', '2025-08-15 08:28:30', 'realisasi', 1),
	(252, 70, 23, '-', 32, NULL, '', 8, 1, 7, 3227230, 0, 0, 25817840, '', '2025-08-15 08:28:49', 'realisasi', '2025-08-15 08:28:49', 'realisasi', 1),
	(253, 70, 23, '-', 43, NULL, '', 61, 48, 13, 1196721, 0, 0, 72999981, '', '2025-08-15 08:29:36', 'realisasi', '2025-08-15 08:29:37', 'realisasi', 1),
	(254, 70, 23, '-', 44, NULL, '', 84, 58, 26, 11654, 0, 0, 978936, '', '2025-08-15 08:29:58', 'realisasi', '2025-08-15 08:29:58', 'realisasi', 1),
	(255, 70, 23, '-', 45, NULL, '', 84, 58, 26, 14568, 0, 0, 1223712, '', '2025-08-15 08:30:14', 'realisasi', '2025-08-15 08:30:14', 'realisasi', 1),
	(256, 70, 23, '-', 46, NULL, '', 84, 58, 26, 194241, 0, 0, 16316244, '', '2025-08-15 08:30:27', 'realisasi', '2025-08-15 08:30:28', 'realisasi', 1),
	(257, 70, 23, '-', 47, NULL, '', 84, 58, 26, 97120, 0, 0, 8158080, '', '2025-08-15 08:30:43', 'realisasi', '2025-08-15 08:30:43', 'realisasi', 1),
	(258, 70, 23, '-', 48, NULL, '', 84, 58, 26, 179672, 0, 0, 15092448, '', '2025-08-15 08:31:19', 'realisasi', '2025-08-15 08:31:19', 'realisasi', 1),
	(259, 71, 23, '-', 31, NULL, '', 9, 2, 7, 2846387, 0, 0, 25617483, '', '2025-08-15 08:32:19', 'realisasi', '2025-08-15 08:32:19', 'realisasi', 1),
	(260, 71, 23, '-', 32, NULL, '', 12, 6, 6, 3261407, 0, 0, 39136884, '', '2025-08-15 08:32:34', 'realisasi', '2025-08-15 08:32:35', 'realisasi', 1),
	(261, 71, 23, '-', 33, NULL, '', 2, 2, 0, 3027120, 0, 0, 6054240, '', '2025-08-15 08:32:51', 'realisasi', '2025-08-15 08:32:52', 'realisasi', 1),
	(262, 71, 23, '-', 38, NULL, '', 49, 43, 6, 2856202, 0, 0, 139953898, '', '2025-08-15 08:33:30', 'realisasi', '2025-08-15 08:33:30', 'realisasi', 1),
	(263, 71, 23, '-', 39, NULL, '', 4, 4, 0, 3014300, 0, 0, 12057200, '', '2025-08-15 08:33:44', 'realisasi', '2025-08-15 08:33:44', 'realisasi', 1),
	(264, 71, 23, '-', 32, NULL, '', 8, 1, 7, 3234730, 0, 0, 25877840, '', '2025-08-15 08:33:59', 'realisasi', '2025-08-15 08:34:00', 'realisasi', 1),
	(265, 71, 23, '-', 44, NULL, '', 84, 58, 26, 11654, 0, 0, 978936, '', '2025-08-15 08:34:21', 'realisasi', '2025-08-15 08:34:22', 'realisasi', 1),
	(266, 71, 23, '-', 45, NULL, '', 84, 58, 26, 14568, 0, 0, 1223712, '', '2025-08-15 08:34:42', 'realisasi', '2025-08-15 08:34:43', 'realisasi', 1),
	(267, 71, 23, '-', 46, NULL, '', 84, 58, 26, 194241, 0, 0, 16316244, '', '2025-08-15 08:35:02', 'realisasi', '2025-08-15 08:35:03', 'realisasi', 1),
	(268, 71, 23, '-', 47, NULL, '', 84, 58, 26, 97120, 0, 0, 8158080, '', '2025-08-15 08:35:26', 'realisasi', '2025-08-15 08:35:27', 'realisasi', 1),
	(269, 71, 23, '-', 48, NULL, '', 84, 58, 26, 179672, 0, 0, 15092448, '', '2025-08-15 08:35:52', 'realisasi', '2025-08-15 08:35:52', 'realisasi', 1),
	(270, 72, 23, '-', 31, NULL, '', 9, 2, 7, 2838720, 0, 0, 25548480, '', '2025-08-15 08:36:38', 'realisasi', '2025-08-15 08:36:38', 'realisasi', 1),
	(271, 72, 23, '-', 32, NULL, '', 12, 6, 6, 3248907, 0, 0, 38986884, '', '2025-08-15 08:36:55', 'realisasi', '2025-08-15 08:36:56', 'realisasi', 1),
	(272, 72, 23, '-', 33, NULL, '', 2, 2, 0, 2877120, 0, 0, 5754240, '', '2025-08-15 08:37:11', 'realisasi', '2025-08-15 08:37:11', 'realisasi', 1),
	(273, 72, 23, '-', 44, NULL, '', 84, 58, 26, 11654, 0, 0, 978936, '', '2025-08-15 08:38:07', 'realisasi', '2025-08-15 08:38:07', 'realisasi', 1),
	(274, 72, 23, '-', 45, NULL, '', 84, 58, 26, 14568, 0, 0, 1223712, '', '2025-08-15 08:38:23', 'realisasi', '2025-08-15 08:38:23', 'realisasi', 1),
	(275, 72, 23, '-', 46, NULL, '', 84, 58, 26, 194241, 0, 0, 16316244, '', '2025-08-15 08:38:39', 'realisasi', '2025-08-15 08:38:40', 'realisasi', 1),
	(276, 72, 23, '-', 47, NULL, '', 84, 58, 26, 97120, 0, 0, 8158080, '', '2025-08-15 08:39:09', 'realisasi', '2025-08-15 08:39:10', 'realisasi', 1),
	(277, 72, 23, '-', 48, NULL, '', 84, 58, 26, 179672, 0, 0, 15092448, '', '2025-08-15 08:39:28', 'realisasi', '2025-08-15 08:39:28', 'realisasi', 1),
	(278, 73, 23, '-', 31, NULL, '', 9, 2, 7, 2842053, 0, 0, 25578477, '', '2025-08-15 08:40:37', 'realisasi', '2025-08-15 08:40:38', 'realisasi', 1),
	(279, 73, 23, '-', 32, NULL, '', 12, 6, 6, 3266407, 0, 0, 39196884, '', '2025-08-15 08:41:03', 'realisasi', '2025-08-15 08:41:04', 'realisasi', 1),
	(280, 73, 23, '-', 33, NULL, '', 2, 2, 0, 2982120, 0, 0, 5964240, '', '2025-08-15 08:41:18', 'realisasi', '2025-08-15 08:41:19', 'realisasi', 1),
	(281, 73, 23, '-', 34, NULL, '', 8, 1, 7, 2225387, 0, 0, 17803096, '', '2025-08-15 08:41:50', 'realisasi', '2025-08-15 08:41:51', 'realisasi', 1),
	(282, 73, 23, '-', 35, NULL, '', 12, 6, 6, 2713907, 0, 0, 32566884, '', '2025-08-15 08:42:18', 'realisasi', '2025-08-15 08:42:19', 'realisasi', 1),
	(283, 73, 23, '-', 36, NULL, '', 2, 2, 0, 2517120, 0, 0, 5034240, '', '2025-08-15 08:42:46', 'realisasi', '2025-08-15 08:42:47', 'realisasi', 1),
	(284, 73, 23, '-', 38, NULL, '', 44, 41, 3, 2783449, 0, 0, 122471756, '', '2025-08-15 08:43:30', 'realisasi', '2025-08-15 08:43:30', 'realisasi', 1),
	(285, 73, 23, '-', 39, NULL, '', 4, 4, 0, 2946800, 0, 0, 11787200, '', '2025-08-15 08:43:48', 'realisasi', '2025-08-15 08:43:48', 'realisasi', 1),
	(286, 73, 23, '-', 32, NULL, '', 13, 3, 10, 3258080, 0, 0, 42355040, '', '2025-08-15 08:44:04', 'realisasi', '2025-08-15 08:44:05', 'realisasi', 1),
	(287, 73, 23, '-', 41, NULL, '', 44, 41, 3, 2188904, 0, 0, 96311776, '', '2025-08-15 08:44:24', 'realisasi', '2025-08-15 08:44:25', 'realisasi', 1),
	(288, 73, 23, '-', 42, NULL, '', 4, 4, 0, 2361800, 0, 0, 9447200, '', '2025-08-15 08:44:47', 'realisasi', '2025-08-15 08:44:47', 'realisasi', 1),
	(289, 73, 23, '-', 35, NULL, '', 13, 3, 10, 2684615, 0, 0, 34899995, '', '2025-08-15 08:45:05', 'realisasi', '2025-08-15 08:45:05', 'realisasi', 1),
	(290, 73, 23, '-', 44, NULL, '', 83, 58, 25, 11654, 0, 0, 967282, '', '2025-08-15 08:45:26', 'realisasi', '2025-08-15 08:45:26', 'realisasi', 1),
	(291, 73, 23, '-', 45, NULL, '', 83, 58, 25, 14568, 0, 0, 1209144, '', '2025-08-15 08:45:39', 'realisasi', '2025-08-15 08:45:39', 'realisasi', 1),
	(292, 73, 23, '-', 46, NULL, '', 83, 58, 25, 194241, 0, 0, 16122003, '', '2025-08-15 08:45:55', 'realisasi', '2025-08-15 08:45:56', 'realisasi', 1),
	(293, 73, 23, '-', 47, NULL, '', 83, 58, 25, 97120, 0, 0, 8060960, '', '2025-08-15 08:46:23', 'realisasi', '2025-08-15 08:46:24', 'realisasi', 1),
	(294, 73, 23, '-', 48, NULL, '', 83, 58, 25, 179672, 0, 0, 14912776, '', '2025-08-15 08:46:54', 'realisasi', '2025-08-15 08:46:54', 'realisasi', 1),
	(295, 74, 24, 'Edsel kue', 49, NULL, '', 10, 10, 0, 35000, 0, 0, 350000, '', '2025-08-15 08:54:08', 'realisasi', '2025-08-15 08:54:08', 'realisasi', 1),
	(296, 74, 24, 'Edsel kue', 50, NULL, '', 10, 10, 0, 20000, 0, 0, 200000, '', '2025-08-15 08:54:26', 'realisasi', '2025-08-15 08:54:26', 'realisasi', 1),
	(297, 75, 24, 'NIKI ECHO', 49, NULL, '', 90, 44, 46, 40500, 0, 0, 3645000, '', '2025-08-15 08:54:59', 'realisasi', '2025-08-15 08:54:59', 'realisasi', 1),
	(298, 75, 24, 'NIKI ECHO', 50, NULL, '', 98, 49, 49, 19200, 0, 0, 1881600, '', '2025-08-15 08:55:18', 'realisasi', '2025-08-15 08:55:18', 'realisasi', 1),
	(299, 76, 24, '-', 49, NULL, '', 10, 7, 3, 35000, 0, 0, 350000, '', '2025-08-15 09:02:03', 'realisasi', '2025-08-15 09:02:04', 'realisasi', 1),
	(300, 76, 24, '-', 50, NULL, '', 10, 7, 3, 20000, 0, 0, 200000, '', '2025-08-15 09:02:20', 'realisasi', '2025-08-15 09:02:21', 'realisasi', 1),
	(301, 77, 24, '-', 49, NULL, '', 15, 10, 5, 35000, 0, 0, 525000, '', '2025-08-15 09:02:50', 'realisasi', '2025-08-15 09:02:51', 'realisasi', 1),
	(302, 77, 24, '-', 50, NULL, '', 15, 10, 5, 15000, 0, 0, 225000, '', '2025-08-15 09:03:03', 'realisasi', '2025-08-15 09:03:04', 'realisasi', 1),
	(303, 78, 24, '-', 49, NULL, '', 19, 8, 11, 37500, 0, 0, 712500, '', '2025-08-15 09:03:38', 'realisasi', '2025-08-15 09:03:39', 'realisasi', 1),
	(304, 78, 24, '-', 50, NULL, '', 19, 8, 11, 37500, 0, 0, 712500, '', '2025-08-15 09:03:53', 'realisasi', '2025-08-15 09:03:54', 'realisasi', 1),
	(305, 79, 25, 'Edsel kue', 52, NULL, '', 50, 0, 0, 15000, 0, 0, 750000, '', '2025-08-15 09:05:57', 'realisasi', '2025-08-15 09:05:58', 'realisasi', 1),
	(306, 79, 25, 'MUSA GRAFIKA', 54, NULL, '', 1, 0, 0, 255000, 0, 0, 255000, '', '2025-08-15 09:06:33', 'realisasi', '2025-08-15 09:06:34', 'realisasi', 1),
	(307, 79, 25, 'MUSA GRAFIKA', 56, NULL, '', 1, 0, 0, 588000, 0, 0, 588000, '', '2025-08-15 09:06:48', 'realisasi', '2025-08-15 09:06:49', 'realisasi', 1),
	(308, 79, 25, 'UD MAULANA', 57, NULL, '', 6, 0, 0, 20000, 0, 0, 120000, '', '2025-08-15 09:07:13', 'realisasi', '2025-08-15 09:07:14', 'realisasi', 1),
	(309, 79, 25, 'UD MAULANA', 58, NULL, '', 8, 0, 0, 25000, 0, 0, 200000, '', '2025-08-15 09:07:30', 'realisasi', '2025-08-15 09:07:30', 'realisasi', 1),
	(310, 80, 25, 'Edsel kue', 52, NULL, '', 40, 0, 0, 15000, 0, 0, 600000, '', '2025-08-15 09:08:32', 'realisasi', '2025-08-15 09:08:33', 'realisasi', 1),
	(311, 80, 25, 'MUSA GRAFIKA', 56, NULL, '', 1, 0, 0, 482900, 0, 0, 482900, '', '2025-08-15 09:08:54', 'realisasi', '2025-08-15 09:08:54', 'realisasi', 1),
	(312, 80, 25, 'IDCOPYPREMIUM', 59, NULL, '', 1, 0, 0, 1800000, 0, 0, 1800000, '', '2025-08-15 09:09:24', 'realisasi', '2025-08-15 09:09:24', 'realisasi', 1),
	(313, 81, 25, 'Edsel kue', 52, NULL, '', 80, 0, 0, 15000, 0, 0, 1200000, '', '2025-08-15 09:10:08', 'realisasi', '2025-08-15 09:10:09', 'realisasi', 1),
	(314, 81, 25, 'MUSA GRAFIKA', 56, NULL, '', 1, 0, 0, 800500, 0, 0, 800500, '', '2025-08-15 09:10:31', 'realisasi', '2025-08-15 09:10:31', 'realisasi', 1),
	(315, 82, 25, 'Edsel kue', 52, NULL, '', 50, 0, 0, 15000, 0, 0, 750000, '', '2025-08-15 09:11:08', 'realisasi', '2025-08-15 09:11:09', 'realisasi', 1),
	(316, 82, 25, 'MUSA GRAFIKA', 56, NULL, '', 1, 0, 0, 255000, 0, 0, 255000, '', '2025-08-15 09:11:29', 'realisasi', '2025-08-15 09:11:29', 'realisasi', 1),
	(317, 82, 25, 'SUARA SURABAYA', 65, NULL, '', 1, 0, 0, 7657658, 842342, 0, 8500000, '', '2025-08-15 09:12:01', 'realisasi', '2025-08-15 09:12:01', 'realisasi', 1),
	(318, 83, 25, 'Edsel kue', 52, NULL, '', 50, 0, 0, 15000, 0, 0, 750000, '', '2025-08-15 09:13:39', 'realisasi', '2025-08-15 09:13:40', 'realisasi', 1),
	(319, 83, 25, 'MUSA GRAFIKA', 56, NULL, '', 1, 0, 0, 399500, 0, 0, 399500, '', '2025-08-15 09:13:55', 'realisasi', '2025-08-15 09:13:56', 'realisasi', 1),
	(320, 84, 25, 'MUSA GRAFIKA', 54, NULL, '', 1, 0, 0, 257000, 0, 0, 257000, '', '2025-08-15 09:14:42', 'realisasi', '2025-08-15 09:14:42', 'realisasi', 1),
	(321, 84, 25, 'MUSA GRAFIKA', 56, NULL, '', 1, 0, 0, 843500, 0, 0, 843500, '', '2025-08-15 09:14:58', 'realisasi', '2025-08-15 09:14:59', 'realisasi', 1),
	(322, 84, 25, 'Dancell Mojosari', 61, NULL, '', 2, 0, 0, 129000, 0, 0, 258000, '', '2025-08-15 09:15:19', 'realisasi', '2025-08-15 09:15:20', 'realisasi', 1),
	(323, 85, 26, '-', 70, NULL, '', 2, 1, 1, 5570000, 0, 0, 11140000, '', '2025-08-15 09:17:20', 'realisasi', '2025-08-15 09:17:21', 'realisasi', 1),
	(324, 85, 26, '-', 71, NULL, '', 33, 26, 7, 5477000, 0, 0, 180741000, '', '2025-08-15 09:17:52', 'realisasi', '2025-08-15 09:17:52', 'realisasi', 1),
	(325, 86, 26, 'PT. Febri Dharma Mandiri', 70, NULL, '', 2, 1, 1, 5570000, 0, 0, 11140000, '', '2025-08-15 09:18:35', 'realisasi', '2025-08-15 09:18:35', 'realisasi', 1),
	(326, 86, 26, 'PT. Febri Dharma', 71, NULL, '', 33, 26, 7, 5477000, 0, 0, 180741000, '', '2025-08-15 09:18:53', 'realisasi', '2025-08-15 09:18:54', 'realisasi', 1),
	(327, 86, 26, 'PT. Ganendra', 72, NULL, '', 1, 0, 0, 22919590, 0, 0, 22919590, '', '2025-08-15 09:19:12', 'realisasi', '2025-08-15 09:19:13', 'realisasi', 1),
	(328, 86, 26, 'PT. Febri Dharma', 73, NULL, '', 1, 0, 0, 18900000, 0, 0, 18900000, '', '2025-08-15 09:19:30', 'realisasi', '2025-08-15 09:19:31', 'realisasi', 1),
	(329, 87, 26, 'PT. Febri Dharma', 70, NULL, '', 2, 1, 1, 5570000, 0, 0, 11140000, '', '2025-08-15 09:20:12', 'realisasi', '2025-08-15 09:20:12', 'realisasi', 1),
	(330, 87, 26, 'PT. Febri Dharma', 71, NULL, '', 33, 26, 7, 5477000, 0, 0, 180741000, '', '2025-08-15 09:20:27', 'realisasi', '2025-08-15 09:20:28', 'realisasi', 1),
	(331, 88, 26, '-', 70, NULL, '', 2, 1, 1, 5570000, 0, 0, 11140000, '', '2025-08-15 09:21:01', 'realisasi', '2025-08-15 09:21:02', 'realisasi', 1),
	(332, 88, 26, 'PT. Febri Dharma', 71, NULL, '', 33, 26, 7, 5477000, 0, 0, 180741000, '', '2025-08-15 09:21:18', 'realisasi', '2025-08-15 09:21:19', 'realisasi', 1),
	(333, 89, 26, '-', 70, NULL, '', 2, 1, 1, 5570000, 0, 0, 11140000, '', '2025-08-15 09:21:50', 'realisasi', '2025-08-15 09:21:50', 'realisasi', 1),
	(334, 89, 26, '-', 71, NULL, '', 33, 26, 7, 5477000, 0, 0, 180741000, '', '2025-08-15 09:22:04', 'realisasi', '2025-08-15 09:22:05', 'realisasi', 1),
	(335, 90, 26, '-', 70, NULL, '', 2, 1, 1, 5570000, 0, 0, 11140000, '', '2025-08-15 09:22:34', 'realisasi', '2025-08-15 09:22:34', 'realisasi', 1),
	(336, 90, 26, '-', 71, NULL, '', 33, 26, 7, 5477000, 0, 0, 180741000, '', '2025-08-15 09:22:47', 'realisasi', '2025-08-15 09:22:48', 'realisasi', 1),
	(337, 91, 27, 'PT. Sinar bima sakti', 74, NULL, '', 12, 12, 0, 5733503, 0, 0, 68802036, '', '2025-08-15 09:29:38', 'realisasi', '2025-08-15 09:29:38', 'realisasi', 1),
	(338, 92, 27, 'PT. Sinar bima sakti', 74, NULL, '', 12, 12, 0, 5733503, 0, 0, 68802036, '', '2025-08-15 09:30:11', 'realisasi', '2025-08-15 09:30:11', 'realisasi', 1),
	(339, 93, 27, 'PT. Sinar bima sakti', 74, NULL, '', 12, 12, 0, 5733503, 0, 0, 68802036, '', '2025-08-15 09:30:40', 'realisasi', '2025-08-15 09:30:41', 'realisasi', 1),
	(340, 94, 27, 'PT. Sinar bima sakti', 74, NULL, '', 12, 12, 0, 5733503, 0, 0, 68802036, '', '2025-08-15 09:31:13', 'realisasi', '2025-08-15 09:31:14', 'realisasi', 1),
	(341, 95, 27, 'PT. Sinar bima sakti', 74, NULL, '', 12, 12, 0, 5733503, 0, 0, 68802036, '', '2025-08-15 09:31:54', 'realisasi', '2025-08-15 09:31:54', 'realisasi', 1),
	(342, 95, 27, 'PT. Sinar bima sakti', 75, NULL, '', 12, 12, 0, 1000000, 0, 0, 12000000, '', '2025-08-15 09:32:09', 'realisasi', '2025-08-15 09:32:09', 'realisasi', 1),
	(343, 95, 27, 'PT. Sinar bima sakti', 76, NULL, '', 5, 5, 0, 250000, 0, 0, 1250000, '', '2025-08-15 09:32:26', 'realisasi', '2025-08-15 09:32:27', 'realisasi', 1),
	(344, 96, 27, 'PT. Sinar bima sakti', 74, NULL, '', 12, 12, 0, 5733503, 0, 0, 68802036, '', '2025-08-15 09:32:59', 'realisasi', '2025-08-15 09:32:59', 'realisasi', 1),
	(345, 97, 29, 'TELKOM', 176, NULL, '', 1, 0, 0, 465347, 0, 0, 465347, '', '2025-08-15 09:34:34', 'realisasi', '2025-08-15 09:34:34', 'realisasi', 1),
	(346, 97, 29, 'PDAM', 177, NULL, '', 1, 0, 0, 1912250, 0, 0, 1912250, '', '2025-08-15 09:34:47', 'realisasi', '2025-08-15 09:34:48', 'realisasi', 1),
	(347, 97, 29, 'PLN', 178, NULL, '', 1, 0, 0, 57563243, 0, 0, 57563243, '', '2025-08-15 09:35:05', 'realisasi', '2025-08-15 09:35:05', 'realisasi', 1),
	(348, 97, 29, 'PT. Trimitra Usaha Sejahtera', 179, NULL, '', 1, 0, 0, 11036036, 1213964, 0, 12250000, '', '2025-08-15 09:35:37', 'realisasi', '2025-08-15 09:35:37', 'realisasi', 1),
	(349, 98, 36, 'TRANSPORT', 183, NULL, '', 39, 9, 30, 100000, 0, 0, 3900000, '', '2025-08-15 09:39:02', 'realisasi', '2025-08-15 09:39:03', 'realisasi', 1),
	(350, 98, 36, 'dr yudha dan bu Ninik', 186, NULL, '', 2, 2, 0, 450000, 0, 0, 900000, '', '2025-08-15 09:39:36', 'realisasi', '2025-08-15 09:39:36', 'realisasi', 1),
	(351, 99, 42, '-', 188, NULL, '', 1, 1, 0, 60000000, 0, 0, 60000000, '', '2025-08-15 09:42:20', 'realisasi', '2025-08-15 09:42:21', 'realisasi', 1),
	(352, 100, 42, '-', 187, NULL, '', 1, 1, 0, 1494520004, 0, 0, 1494520004, '', '2025-08-15 09:43:01', 'realisasi', '2025-08-15 09:43:02', 'realisasi', 1),
	(353, 101, 45, '-', 189, NULL, '', 9214, 0, 0, 25169, 0, 0, 231907166, '', '2025-08-15 09:44:09', 'realisasi', '2025-08-15 09:44:09', 'realisasi', 1),
	(354, 102, 45, '-', 189, NULL, '', 7865, 0, 0, 25101, 0, 0, 197419365, '', '2025-08-15 09:44:36', 'realisasi', '2025-08-15 09:44:36', 'realisasi', 1),
	(355, 103, 45, '-', 189, NULL, '', 7199, 0, 0, 25324, 0, 0, 182307476, '', '2025-08-15 09:45:02', 'realisasi', '2025-08-15 09:45:02', 'realisasi', 1),
	(356, 104, 45, '-', 189, NULL, '', 8505, 0, 0, 25200, 0, 0, 214326000, '', '2025-08-15 09:48:19', 'realisasi', '2025-08-15 09:48:20', 'realisasi', 1),
	(357, 105, 51, '-', 191, NULL, '', 60, 37, 23, 15000, 0, 0, 900000, '', '2025-08-15 09:50:53', 'realisasi', '2025-08-15 09:50:54', 'realisasi', 1),
	(358, 106, 52, '-', 194, NULL, '', 180, 180, 0, 15000, 0, 0, 2700000, '', '2025-08-15 09:51:49', 'realisasi', '2025-08-15 09:51:50', 'realisasi', 1),
	(359, 106, 52, '-', 195, NULL, '', 110, 110, 0, 35000, 0, 0, 3850000, '', '2025-08-15 09:52:06', 'realisasi', '2025-08-15 09:52:07', 'realisasi', 1),
	(360, 107, 54, '-', 201, NULL, '', 1, 0, 0, 12200000, 0, 0, 12200000, '', '2025-08-15 09:53:10', 'realisasi', '2025-08-15 09:53:11', 'realisasi', 1),
	(361, 108, 54, '-', 203, NULL, '', 1, 0, 0, 427355964, 0, 0, 427355964, '', '2025-08-15 09:53:57', 'realisasi', '2025-08-15 09:53:58', 'realisasi', 1),
	(362, 108, 54, '-', 205, NULL, '', 1, 0, 0, 12600000, 0, 0, 12600000, '', '2025-08-15 09:54:15', 'realisasi', '2025-08-15 09:54:16', 'realisasi', 1),
	(363, 109, 54, '-', 202, NULL, '', 1, 0, 0, 113708800, 0, 0, 113708800, '', '2025-08-15 09:54:52', 'realisasi', '2025-08-15 09:54:52', 'realisasi', 1),
	(364, 110, 54, '-', 204, NULL, '', 1, 0, 0, 34514895, 0, 0, 34514895, '', '2025-08-15 09:55:25', 'realisasi', '2025-08-15 09:55:25', 'realisasi', 1),
	(365, 110, 54, '-', 205, NULL, '', 1, 0, 0, 6300000, 0, 0, 6300000, '', '2025-08-15 09:55:41', 'realisasi', '2025-08-15 09:55:42', 'realisasi', 1),
	(366, 111, 54, '-', 205, NULL, '', 1, 0, 0, 36720000, 0, 0, 36720000, '', '2025-08-15 09:56:10', 'realisasi', '2025-08-15 09:56:10', 'realisasi', 1),
	(367, 112, 54, '-', 205, NULL, '', 2, 0, 0, 6930000, 0, 0, 13860000, '', '2025-08-15 09:56:40', 'realisasi', '2025-08-15 09:56:40', 'realisasi', 1),
	(368, 113, 54, '-', 205, NULL, '', 2, 0, 0, 7560000, 0, 0, 15120000, '', '2025-08-15 09:57:05', 'realisasi', '2025-08-15 09:57:06', 'realisasi', 1),
	(369, 114, 62, 'NIKI ECHO', 170, NULL, '', 73, 0, 0, 15000, 0, 0, 1095000, '', '2025-08-15 09:58:04', 'realisasi', '2025-08-15 09:58:05', 'realisasi', 1),
	(370, 114, 62, 'NIKI ECHO', 171, NULL, '', 73, 0, 0, 35000, 0, 0, 2555000, '', '2025-08-15 09:58:18', 'realisasi', '2025-08-15 09:58:18', 'realisasi', 1),
	(371, 115, 62, '-', 170, NULL, '', 32, 0, 0, 15000, 0, 0, 480000, '', '2025-08-15 09:58:54', 'realisasi', '2025-08-15 09:58:55', 'realisasi', 1),
	(372, 115, 62, '-', 171, NULL, '', 32, 0, 0, 35000, 0, 0, 1120000, '', '2025-08-15 09:59:06', 'realisasi', '2025-08-15 09:59:07', 'realisasi', 1),
	(373, 116, 62, '-', 213, NULL, '', 1, 0, 0, 62550000, 0, 0, 62550000, '', '2025-08-15 09:59:41', 'realisasi', '2025-08-15 09:59:41', 'realisasi', 1),
	(374, 116, 62, '-', 170, NULL, '', 105, 0, 0, 15000, 0, 0, 1575000, '', '2025-08-15 10:00:01', 'realisasi', '2025-08-15 10:00:01', 'realisasi', 1),
	(375, 116, 62, '-', 171, NULL, '', 105, 0, 0, 35000, 0, 0, 3675000, '', '2025-08-15 10:00:15', 'realisasi', '2025-08-15 10:00:15', 'realisasi', 1),
	(376, 117, 63, '-', 170, NULL, '', 85, 0, 0, 15000, 0, 0, 1275000, '', '2025-08-15 10:01:27', 'realisasi', '2025-08-15 10:01:27', 'realisasi', 1),
	(377, 117, 63, '-', 171, NULL, '', 85, 0, 0, 35000, 0, 0, 2975000, '', '2025-08-15 10:01:41', 'realisasi', '2025-08-15 10:01:41', 'realisasi', 1),
	(378, 118, 64, 'Setia Graha', 166, NULL, '', 1, 0, 0, 11460894, 0, 0, 11460894, '', '2025-08-15 10:02:35', 'realisasi', '2025-08-15 10:02:36', 'realisasi', 1),
	(379, 119, 65, 'Setia Graha', 166, NULL, '', 1, 0, 0, 15423299, 0, 0, 15423299, '', '2025-08-15 10:03:28', 'realisasi', '2025-08-15 10:03:29', 'realisasi', 1),
	(380, 120, 68, '-', 166, NULL, '', 1, 0, 0, 25008300, 0, 0, 25008300, '', '2025-08-15 10:04:29', 'realisasi', '2025-08-15 10:04:30', 'realisasi', 1),
	(381, 121, 69, 'Dewi Permata', 166, NULL, '', 1, 0, 0, 50000172, 0, 0, 50000172, '', '2025-08-15 10:05:21', 'realisasi', '2025-08-15 10:05:21', 'realisasi', 1);

-- Dumping structure for table budgeting.urusan_pemerintah
CREATE TABLE IF NOT EXISTS `urusan_pemerintah` (
  `idurusan_pemerintah` int NOT NULL AUTO_INCREMENT,
  `no_rekening_urusan` varchar(50) DEFAULT NULL,
  `nama_urusan` varchar(200) DEFAULT NULL,
  `tahun_anggaran_urusan` varchar(50) DEFAULT NULL,
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idurusan_pemerintah`),
  KEY `no_rekening_urusan` (`no_rekening_urusan`),
  KEY `nama_urusan` (`nama_urusan`),
  KEY `tahun_anggaran_urusan` (`tahun_anggaran_urusan`),
  KEY `is_active` (`is_active`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table budgeting.urusan_pemerintah: ~7 rows (approximately)
INSERT INTO `urusan_pemerintah` (`idurusan_pemerintah`, `no_rekening_urusan`, `nama_urusan`, `tahun_anggaran_urusan`, `is_active`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(1, '1', 'URUSAN PEMERINTAHAN WAJIB YANG BERKAITAN DENGAN PELAYANAN DASAR', '2025', 1, '2025-06-05 14:16:31', 'BucinBGMID', '2025-07-22 17:32:08', 'superadmin', 1),
	(2, '2', 'URUSAN PEMERINTAHAN', '2024', 1, '2025-06-05 14:20:39', 'BucinBGMID', '2025-06-23 13:46:25', 'BucinBGMID', 1),
	(3, '22222', 'URUSAN PEMERINTAHAN oke', '2024', 1, '2025-06-05 14:53:41', 'BucinBGMID', '2025-06-05 14:53:51', 'BucinBGMID', 0),
	(4, '2', 'URUSAN PEMERINTAHAN', '2025', 1, '2025-06-05 14:55:53', 'BucinBGMID', '2025-06-05 14:55:59', 'BucinBGMID', 0),
	(5, '3', 'URUSAN ', '2024', 0, '2025-06-16 11:25:50', 'BucinBGMID', '2025-06-16 11:25:50', 'BucinBGMID', 1),
	(6, '4', 'okok', '2025', 1, '2025-06-23 13:47:54', 'BucinBGMID', '2025-06-23 13:57:40', 'BucinBGMID', 0),
	(7, '45', 'okok ssss', '2025', 1, '2025-06-23 13:57:23', 'BucinBGMID', '2025-06-23 13:57:27', 'BucinBGMID', 0);

-- Dumping structure for table budgeting.user
CREATE TABLE IF NOT EXISTS `user` (
  `iduser` bigint unsigned NOT NULL AUTO_INCREMENT,
  `idrole` int DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`iduser`),
  KEY `FK_user_role` (`idrole`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Dumping data for table budgeting.user: ~7 rows (approximately)
INSERT INTO `user` (`iduser`, `idrole`, `username`, `password`, `name`, `email`, `is_active`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(1, NULL, 'BucinBGMID', '5f4dcc3b5aa765d61d8327deb882cf99', 'IT Support', NULL, 1, '2018-04-03 14:14:10', NULL, '2018-04-03 14:14:10', NULL, 1),
	(2, 1, 'superadmin', '5f4dcc3b5aa765d61d8327deb882cf99', 'superadmin', 'superadmin@gmail.com', 1, '2018-08-02 08:38:40', 'superadmin', '2025-06-25 10:24:24', 'BucinBGMID', 1),
	(8, 4, 'verifikator', '2ea2aa47b5cbf1f95b9dd18c1bf8dd4c', 'admin verifikator', NULL, 1, '2025-07-19 09:57:21', 'superadmin', '2025-07-19 09:57:22', 'superadmin', 1),
	(9, 5, 'bendahara', 'c9ccd7f3c1145515a9d3f7415d5bcbea', 'admin bendahara', NULL, 1, '2025-07-19 09:57:38', 'superadmin', '2025-07-19 09:57:38', 'superadmin', 1),
	(10, 6, 'realisasi', '0d908900d878afb970ee92973fcf4760', 'admin realisasi', NULL, 1, '2025-07-19 09:58:08', 'superadmin', '2025-07-19 09:58:09', 'superadmin', 1),
	(11, 7, 'direktur', '4fbfd324f5ffcdff5dbf6f019b02eca8', 'Direktur RSUD Sumberglagah', NULL, 1, '2025-08-14 13:11:08', 'superadmin', '2025-08-14 13:11:08', 'superadmin', 1),
	(12, 8, 'npd', 'e7bb7aa4ffa0c3f89b80ac8d6f189888', 'admin npd', NULL, 1, '2025-08-14 14:27:04', 'superadmin', '2025-08-14 14:27:05', 'superadmin', 1);

-- Dumping structure for table budgeting.user_role
CREATE TABLE IF NOT EXISTS `user_role` (
  `iduser_role` int NOT NULL AUTO_INCREMENT,
  `idrole` int DEFAULT NULL,
  `menu_name` varchar(200) DEFAULT NULL,
  `access` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`iduser_role`),
  KEY `FK_user_role_role` (`idrole`),
  CONSTRAINT `FK_user_role_role` FOREIGN KEY (`idrole`) REFERENCES `role` (`idrole`)
) ENGINE=InnoDB AUTO_INCREMENT=220 DEFAULT CHARSET=latin1;

-- Dumping data for table budgeting.user_role: ~157 rows (approximately)
INSERT INTO `user_role` (`iduser_role`, `idrole`, `menu_name`, `access`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(45, 1, 'role_table', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(46, 1, 'dashboard', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(47, 1, 'master', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(48, 1, 'master_urusan_pemerintah', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(49, 1, 'master_bidang_urusan', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(50, 1, 'master_program', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(51, 1, 'master_kegiatan', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(52, 1, 'master_sub_kegiatan', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(53, 1, 'master_paket_belanja', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(54, 1, 'master_akun_belanja', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(55, 1, 'master_kategori', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(56, 1, 'master_sub_kategori', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(57, 1, 'master_kendaraan', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(58, 1, 'master_ruang', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(59, 1, 'master_satuan', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(60, 1, 'realisasi_anggaran', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(61, 1, 'evaluasi_anggaran', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(62, 1, 'acc_report', 1, '2025-06-25 10:24:15', 'BucinBGMID', NULL, NULL, 1),
	(63, 1, 'user', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(64, 1, 'user_user', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(65, 1, 'user_user_role', 1, '2025-06-25 10:24:15', 'BucinBGMID', '2025-08-11 10:52:28', 'superadmin', 1),
	(66, 1, 'verifikasi_dokumen', 1, '2025-07-09 19:56:17', 'administrator', '2025-08-11 10:52:28', 'superadmin', 1),
	(67, 1, 'pembayaran', 1, '2025-07-09 19:56:17', 'administrator', '2025-08-11 10:52:28', 'superadmin', 1),
	(68, 1, 'role_bypass', 1, '2025-07-10 14:19:21', 'administrator', '2025-08-11 10:52:28', 'superadmin', 1),
	(69, 1, 'role_crud', 1, '2025-07-17 09:38:00', 'superadmin', '2025-08-11 10:52:28', 'superadmin', 1),
	(70, 1, 'role_verificator', 1, '2025-07-17 09:38:00', 'superadmin', '2025-08-11 10:52:28', 'superadmin', 1),
	(71, 6, 'role_table', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(72, 6, 'dashboard', 1, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(73, 6, 'master', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(74, 6, 'master_urusan_pemerintah', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(75, 6, 'master_bidang_urusan', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(76, 6, 'master_program', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(77, 6, 'master_kegiatan', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(78, 6, 'master_sub_kegiatan', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(79, 6, 'master_paket_belanja', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(80, 6, 'master_akun_belanja', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(81, 6, 'master_kategori', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(82, 6, 'master_sub_kategori', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(83, 6, 'master_kendaraan', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(84, 6, 'master_ruang', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(85, 6, 'master_satuan', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(86, 6, 'role_bypass', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(87, 6, 'realisasi_anggaran', 1, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(88, 6, 'role_crud', 1, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(89, 6, 'role_verificator', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(90, 6, 'verifikasi_dokumen', 1, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(91, 6, 'pembayaran', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(92, 6, 'evaluasi_anggaran', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(93, 6, 'user', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(94, 6, 'user_user', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(95, 6, 'user_user_role', 0, '2025-07-19 10:36:15', 'superadmin', '2025-07-19 10:36:45', 'superadmin', 1),
	(96, 4, 'role_table', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(97, 4, 'dashboard', 1, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(98, 4, 'master', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(99, 4, 'master_urusan_pemerintah', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(100, 4, 'master_bidang_urusan', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(101, 4, 'master_program', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(102, 4, 'master_kegiatan', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(103, 4, 'master_sub_kegiatan', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(104, 4, 'master_paket_belanja', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(105, 4, 'master_akun_belanja', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(106, 4, 'master_kategori', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(107, 4, 'master_sub_kategori', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(108, 4, 'master_kendaraan', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(109, 4, 'master_ruang', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(110, 4, 'master_satuan', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(111, 4, 'role_bypass', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(112, 4, 'realisasi_anggaran', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(113, 4, 'role_crud', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(114, 4, 'role_verificator', 1, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(115, 4, 'verifikasi_dokumen', 1, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(116, 4, 'pembayaran', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(117, 4, 'evaluasi_anggaran', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(118, 4, 'user', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(119, 4, 'user_user', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(120, 4, 'user_user_role', 0, '2025-07-19 10:36:34', 'superadmin', NULL, NULL, 1),
	(121, 5, 'role_table', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(122, 5, 'dashboard', 1, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(123, 5, 'master', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(124, 5, 'master_urusan_pemerintah', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(125, 5, 'master_bidang_urusan', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(126, 5, 'master_program', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(127, 5, 'master_kegiatan', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(128, 5, 'master_sub_kegiatan', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(129, 5, 'master_paket_belanja', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(130, 5, 'master_akun_belanja', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(131, 5, 'master_kategori', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(132, 5, 'master_sub_kategori', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(133, 5, 'master_kendaraan', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(134, 5, 'master_ruang', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(135, 5, 'master_satuan', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(136, 5, 'role_bypass', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(137, 5, 'realisasi_anggaran', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(138, 5, 'role_crud', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(139, 5, 'role_verificator', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(140, 5, 'verifikasi_dokumen', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(141, 5, 'pembayaran', 1, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(142, 5, 'evaluasi_anggaran', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(143, 5, 'user', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(144, 5, 'user_user', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(145, 5, 'user_user_role', 0, '2025-07-19 10:37:03', 'superadmin', NULL, NULL, 1),
	(148, 1, 'role_view_paket_belanja', 0, '2025-07-22 21:16:52', 'superadmin', '2025-08-11 10:52:28', 'superadmin', 1),
	(149, 1, 'role_view_realisasi_anggaran', 0, '2025-07-22 21:25:59', 'superadmin', '2025-08-11 10:52:28', 'superadmin', 1),
	(150, 1, 'role_report_realisasi_anggaran', 1, '2025-08-11 08:14:28', 'superadmin', '2025-08-11 10:52:28', 'superadmin', 1),
	(151, 1, 'role_report_sisa_realisasi_anggaran', 1, '2025-08-11 08:14:28', 'superadmin', '2025-08-11 10:52:28', 'superadmin', 1),
	(152, 1, 'report', 1, '2025-08-11 08:14:28', 'superadmin', '2025-08-11 10:52:28', 'superadmin', 1),
	(153, 1, 'npd', 1, '2025-08-11 10:43:01', 'superadmin', '2025-08-11 10:52:28', 'superadmin', 1),
	(154, 1, 'information', 1, '2025-08-11 10:52:28', 'superadmin', NULL, NULL, 1),
	(155, 7, 'role_table', 1, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(156, 7, 'dashboard', 1, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(157, 7, 'master', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(158, 7, 'master_urusan_pemerintah', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(159, 7, 'master_bidang_urusan', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(160, 7, 'master_program', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(161, 7, 'master_kegiatan', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(162, 7, 'master_sub_kegiatan', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(163, 7, 'role_view_paket_belanja', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(164, 7, 'master_paket_belanja', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(165, 7, 'master_akun_belanja', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(166, 7, 'master_kategori', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(167, 7, 'master_sub_kategori', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(168, 7, 'master_kendaraan', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(169, 7, 'master_ruang', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(170, 7, 'master_satuan', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(171, 7, 'role_bypass', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(172, 7, 'role_view_realisasi_anggaran', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(173, 7, 'realisasi_anggaran', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(174, 7, 'role_crud', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(175, 7, 'role_verificator', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(176, 7, 'verifikasi_dokumen', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(177, 7, 'npd', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(178, 7, 'pembayaran', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(179, 7, 'evaluasi_anggaran', 1, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(180, 7, 'role_report_realisasi_anggaran', 1, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(181, 7, 'role_report_sisa_realisasi_anggaran', 1, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(182, 7, 'report', 1, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(183, 7, 'information', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(184, 7, 'user', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(185, 7, 'user_user', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(186, 7, 'user_user_role', 0, '2025-08-14 13:11:28', 'superadmin', NULL, NULL, 1),
	(187, 8, 'role_table', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(188, 8, 'dashboard', 1, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(189, 8, 'master', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(190, 8, 'master_urusan_pemerintah', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(191, 8, 'master_bidang_urusan', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(192, 8, 'master_program', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(193, 8, 'master_kegiatan', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(194, 8, 'master_sub_kegiatan', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(195, 8, 'role_view_paket_belanja', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(196, 8, 'master_paket_belanja', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(197, 8, 'master_akun_belanja', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(198, 8, 'master_kategori', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(199, 8, 'master_sub_kategori', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(200, 8, 'master_kendaraan', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(201, 8, 'master_ruang', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(202, 8, 'master_satuan', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(203, 8, 'role_bypass', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(204, 8, 'role_view_realisasi_anggaran', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(205, 8, 'realisasi_anggaran', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(206, 8, 'role_crud', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(207, 8, 'role_verificator', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(208, 8, 'verifikasi_dokumen', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(209, 8, 'npd', 1, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(210, 8, 'pembayaran', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(211, 8, 'evaluasi_anggaran', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(212, 8, 'role_report_realisasi_anggaran', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(213, 8, 'role_report_sisa_realisasi_anggaran', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(214, 8, 'report', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(215, 8, 'information', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(216, 8, 'user', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(217, 8, 'user_user', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(218, 8, 'user_user_role', 0, '2025-08-14 14:27:36', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1),
	(219, 8, 'role_view_npd', 0, '2025-08-15 11:18:37', 'superadmin', '2025-08-15 11:18:47', 'superadmin', 1);

-- Dumping structure for table budgeting.verification
CREATE TABLE IF NOT EXISTS `verification` (
  `idverification` int NOT NULL AUTO_INCREMENT,
  `verification_date_created` datetime DEFAULT NULL COMMENT 'tanggal input dokumen',
  `confirm_verification_date` datetime DEFAULT NULL COMMENT 'tanggal verifikasi dokumen',
  `verification_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'nomor dokumen',
  `verification_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'DRAFT' COMMENT 'INPUT DATA 		-> data diinputkan dimenu realisasi anggaran (oleh user realisasi); \r\nMENUNGGU VERIFIKASI 	-> data diinputkan di menu verifikasi dokumen (oleh user realisasi); \r\nSUDAH DIVERIFIKASI 	-> data sudah diverifikasi (oleh user verifikator); \r\nINPUT NPD		-> data diinputkan di menu npd (oleh user npd);\r\nMENUNGGU PEMBAYARAN	-> data sudah dikirim ke bendahara (oleh user npd);\r\nSUDAH DIBAYAR BENDAHARA -> data sudah dibayar bendahara (oleh user bendahara);',
  `updated_status` datetime DEFAULT NULL,
  `status_approve` varchar(50) DEFAULT NULL COMMENT 'DISETUJUI, DITOLAK',
  `verification_description` text,
  `iduser_created` bigint DEFAULT NULL COMMENT 'user yang membuat dokumen',
  `iduser_verification` bigint DEFAULT NULL COMMENT 'user yang memverifikasi dokumen',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idverification`),
  KEY `verification_code` (`verification_code`),
  KEY `iduser_created` (`iduser_created`),
  KEY `iduser_verification` (`iduser_verification`),
  KEY `status` (`status`),
  KEY `transaction_date_created` (`verification_date_created`) USING BTREE,
  KEY `confirm_verification` (`confirm_verification_date`) USING BTREE,
  KEY `verification_status` (`verification_status`),
  KEY `status_approve` (`status_approve`),
  KEY `updated_status` (`updated_status`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.verification: ~16 rows (approximately)
INSERT INTO `verification` (`idverification`, `verification_date_created`, `confirm_verification_date`, `verification_code`, `verification_status`, `updated_status`, `status_approve`, `verification_description`, `iduser_created`, `iduser_verification`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(10, '2025-06-03 10:10:49', NULL, 'AP202508150001', 'MENUNGGU VERIFIKASI', NULL, NULL, NULL, 10, NULL, '2025-08-15 10:11:26', 'realisasi', '2025-08-15 10:12:03', 'realisasi', 1),
	(11, '2025-02-28 10:12:36', NULL, 'AP202508150002', 'MENUNGGU VERIFIKASI', NULL, NULL, NULL, 10, NULL, '2025-08-15 10:12:54', 'realisasi', '2025-08-15 10:13:01', 'realisasi', 1),
	(12, '2025-05-05 10:13:31', '2025-08-15 11:02:44', 'AP202508150003', 'SUDAH DIBAYAR BENDAHARA', '2025-08-15 14:44:03', 'DISETUJUI', 'acc', 10, 8, '2025-08-15 10:13:50', 'realisasi', '2025-08-15 11:02:45', 'verifikator', 1),
	(13, '2025-05-06 10:15:40', '2025-08-20 17:38:03', 'AP202508150004', 'DITOLAK VERIFIKATOR', NULL, 'DITOLAK', 'revisi, dokumen kurang lengkap', 10, 8, '2025-08-15 10:16:20', 'realisasi', '2025-08-20 17:38:04', 'superadmin', 1),
	(14, '2025-07-02 10:16:47', NULL, 'AP202508150005', 'MENUNGGU VERIFIKASI', NULL, NULL, NULL, 10, NULL, '2025-08-15 10:16:58', 'realisasi', '2025-08-15 10:17:03', 'realisasi', 1),
	(15, '2025-03-25 10:17:13', '2025-08-15 11:02:12', 'AP202508150006', 'INPUT NPD', '2025-08-15 14:38:51', 'DISETUJUI', 'acc', 10, 8, '2025-08-15 10:17:26', 'realisasi', '2025-08-15 11:02:12', 'verifikator', 1),
	(16, '2025-07-03 10:18:35', '2025-08-15 11:03:29', 'AP202508150007', 'MENUNGGU PEMBAYARAN', '2025-08-15 14:42:49', 'DISETUJUI', 'acc', 10, 8, '2025-08-15 10:19:04', 'realisasi', '2025-08-15 11:03:29', 'verifikator', 1),
	(17, '2025-04-10 10:19:46', '2025-08-15 11:03:44', 'AP202508150008', 'SUDAH DIBAYAR BENDAHARA', '2025-08-15 14:44:37', 'DISETUJUI', 'acc', 10, 8, '2025-08-15 10:20:04', 'realisasi', '2025-08-15 11:03:44', 'verifikator', 1),
	(18, '2025-07-04 10:20:42', '2025-08-15 11:04:34', 'AP202508150009', 'SUDAH DIVERIFIKASI', NULL, 'DISETUJUI', 'acc', 10, 8, '2025-08-15 10:20:57', 'realisasi', '2025-08-15 11:04:34', 'verifikator', 1),
	(19, '2025-05-07 10:21:09', '2025-08-15 11:04:43', 'AP202508150010', 'SUDAH DIVERIFIKASI', NULL, 'DISETUJUI', 'acc', 10, 8, '2025-08-15 10:21:21', 'realisasi', '2025-08-15 11:04:43', 'verifikator', 1),
	(20, '2025-03-06 10:21:30', '2025-08-15 11:04:55', 'AP202508150011', 'INPUT NPD', '2025-08-15 14:38:30', 'DISETUJUI', 'acc', 10, 8, '2025-08-15 10:21:48', 'realisasi', '2025-08-15 11:04:56', 'verifikator', 1),
	(21, '2025-01-13 10:22:23', NULL, 'AP202508150012', 'MENUNGGU VERIFIKASI', NULL, NULL, NULL, 10, NULL, '2025-08-15 10:22:34', 'realisasi', '2025-08-15 10:22:37', 'realisasi', 1),
	(22, '2025-05-05 10:22:51', '2025-08-15 10:59:22', 'AP202508150013', 'SUDAH DIVERIFIKASI', NULL, 'DISETUJUI', 'ACC', 10, 8, '2025-08-15 10:23:14', 'realisasi', '2025-08-15 10:59:23', 'verifikator', 1),
	(23, '2025-07-02 10:23:35', '2025-08-15 11:10:40', 'AP202508150014', 'SUDAH DIVERIFIKASI', NULL, 'DISETUJUI', 'ACC', 10, 8, '2025-08-15 10:23:43', 'realisasi', '2025-08-15 11:10:41', 'verifikator', 1),
	(24, '2025-07-02 10:23:49', '2025-08-20 17:48:04', 'AP202508150015', 'SUDAH DIVERIFIKASI', NULL, 'DISETUJUI', 'revisinya sudah sesuai', 10, 2, '2025-08-15 10:24:04', 'realisasi', '2025-08-20 17:48:05', 'superadmin', 1),
	(25, '2025-07-03 10:24:14', NULL, 'AP202508150016', 'MENUNGGU VERIFIKASI', NULL, NULL, NULL, 10, NULL, '2025-08-15 10:24:25', 'realisasi', '2025-08-15 10:24:27', 'realisasi', 1);

-- Dumping structure for table budgeting.verification_detail
CREATE TABLE IF NOT EXISTS `verification_detail` (
  `idverification_detail` bigint NOT NULL AUTO_INCREMENT,
  `idverification` int NOT NULL,
  `idtransaction` int DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idverification_detail`) USING BTREE,
  KEY `idverification` (`idverification`),
  KEY `idtransaction` (`idtransaction`),
  KEY `status` (`status`),
  CONSTRAINT `FK_transaction_detail_verification` FOREIGN KEY (`idverification`) REFERENCES `verification` (`idverification`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table budgeting.verification_detail: ~33 rows (approximately)
INSERT INTO `verification_detail` (`idverification_detail`, `idverification`, `idtransaction`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(27, 10, 55, '2025-08-15 10:11:26', 'realisasi', '2025-08-15 10:11:27', 'realisasi', 1),
	(28, 10, 65, '2025-08-15 10:11:34', 'realisasi', '2025-08-15 10:11:34', 'realisasi', 1),
	(29, 10, 66, '2025-08-15 10:11:40', 'realisasi', '2025-08-15 10:11:40', 'realisasi', 1),
	(30, 10, 67, '2025-08-15 10:11:45', 'realisasi', '2025-08-15 10:11:45', 'realisasi', 1),
	(31, 10, 68, '2025-08-15 10:11:49', 'realisasi', '2025-08-15 10:11:50', 'realisasi', 1),
	(32, 11, 114, '2025-08-15 10:12:54', 'realisasi', '2025-08-15 10:12:55', 'realisasi', 1),
	(33, 11, 115, '2025-08-15 10:13:00', 'realisasi', '2025-08-15 10:13:00', 'realisasi', 1),
	(34, 12, 58, '2025-08-15 10:13:50', 'realisasi', '2025-08-15 10:13:51', 'realisasi', 1),
	(35, 12, 69, '2025-08-15 10:13:54', 'realisasi', '2025-08-15 10:13:54', 'realisasi', 1),
	(36, 12, 70, '2025-08-15 10:13:59', 'realisasi', '2025-08-15 10:13:59', 'realisasi', 1),
	(37, 12, 71, '2025-08-15 10:14:04', 'realisasi', '2025-08-15 10:14:04', 'realisasi', 1),
	(38, 12, 72, '2025-08-15 10:14:26', 'realisasi', '2025-08-15 10:14:26', 'realisasi', 1),
	(39, 13, 75, '2025-08-15 10:16:20', 'realisasi', '2025-08-15 10:16:20', 'realisasi', 1),
	(40, 13, 78, '2025-08-15 10:16:24', 'realisasi', '2025-08-15 10:16:24', 'realisasi', 1),
	(41, 14, 52, '2025-08-15 10:16:58', 'realisasi', '2025-08-15 10:16:58', 'realisasi', 1),
	(42, 14, 59, '2025-08-15 10:17:01', 'realisasi', '2025-08-15 10:17:02', 'realisasi', 1),
	(43, 15, 99, '2025-08-15 10:17:26', 'realisasi', '2025-08-15 10:17:27', 'realisasi', 1),
	(44, 15, 100, '2025-08-15 10:17:30', 'realisasi', '2025-08-15 10:17:30', 'realisasi', 1),
	(45, 16, 90, '2025-08-15 10:19:04', 'realisasi', '2025-08-15 10:19:04', 'realisasi', 1),
	(46, 16, 89, '2025-08-15 10:19:08', 'realisasi', '2025-08-15 10:19:09', 'realisasi', 1),
	(47, 16, 47, '2025-08-15 10:19:12', 'realisasi', '2025-08-15 10:19:39', 'realisasi', 1),
	(48, 17, 85, '2025-08-15 10:20:04', 'realisasi', '2025-08-15 10:20:05', 'realisasi', 1),
	(49, 17, 86, '2025-08-15 10:20:08', 'realisasi', '2025-08-15 10:20:08', 'realisasi', 1),
	(50, 17, 87, '2025-08-15 10:20:12', 'realisasi', '2025-08-15 10:20:13', 'realisasi', 1),
	(51, 17, 88, '2025-08-15 10:20:16', 'realisasi', '2025-08-15 10:20:16', 'realisasi', 1),
	(52, 18, 48, '2025-08-15 10:20:57', 'realisasi', '2025-08-15 10:20:57', 'realisasi', 1),
	(53, 18, 96, '2025-08-15 10:21:03', 'realisasi', '2025-08-15 10:21:03', 'realisasi', 1),
	(54, 19, 95, '2025-08-15 10:21:21', 'realisasi', '2025-08-15 10:21:22', 'realisasi', 1),
	(55, 19, 94, '2025-08-15 10:21:25', 'realisasi', '2025-08-15 10:21:26', 'realisasi', 1),
	(56, 20, 91, '2025-08-15 10:21:48', 'realisasi', '2025-08-15 10:21:48', 'realisasi', 1),
	(57, 20, 92, '2025-08-15 10:21:56', 'realisasi', '2025-08-15 10:21:56', 'realisasi', 1),
	(58, 20, 93, '2025-08-15 10:22:00', 'realisasi', '2025-08-15 10:22:00', 'realisasi', 1),
	(59, 21, 97, '2025-08-15 10:22:34', 'realisasi', '2025-08-15 10:22:35', 'realisasi', 1),
	(60, 22, 121, '2025-08-15 10:23:14', 'realisasi', '2025-08-15 10:23:14', 'realisasi', 1),
	(61, 23, 50, '2025-08-15 10:23:43', 'realisasi', '2025-08-15 10:23:43', 'realisasi', 1),
	(62, 24, 51, '2025-08-15 10:24:04', 'realisasi', '2025-08-15 10:24:04', 'realisasi', 1),
	(63, 25, 120, '2025-08-15 10:24:25', 'realisasi', '2025-08-15 10:24:26', 'realisasi', 1);

-- Dumping structure for table budgeting.verification_history
CREATE TABLE IF NOT EXISTS `verification_history` (
  `idverification_history` int NOT NULL AUTO_INCREMENT,
  `idverification` int DEFAULT NULL,
  `confirm_verification_date` datetime DEFAULT NULL,
  `status_approve` varchar(50) DEFAULT NULL,
  `verification_description` text,
  `iduser_verification` bigint DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idverification_history`),
  KEY `idverification` (`idverification`),
  KEY `confirm_verification_date` (`confirm_verification_date`),
  KEY `status_approve` (`status_approve`),
  KEY `iduser_verification` (`iduser_verification`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table budgeting.verification_history: ~1 rows (approximately)
INSERT INTO `verification_history` (`idverification_history`, `idverification`, `confirm_verification_date`, `status_approve`, `verification_description`, `iduser_verification`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(1, 13, '2025-08-20 17:38:03', 'DITOLAK', 'revisi, dokumen kurang lengkap', 8, '2025-08-20 17:38:03', 'superadmin', '2025-08-20 17:38:04', 'superadmin', 1),
	(2, 24, '2025-08-20 17:41:49', 'DITOLAK', 'kurang', 2, '2025-08-20 17:41:49', 'superadmin', '2025-08-20 17:41:50', 'superadmin', 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;


CREATE TABLE IF NOT EXISTS `sumber_dana` (
  `idsumber_dana` int NOT NULL AUTO_INCREMENT,
  `nama_sumber_dana` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idsumber_dana`) USING BTREE,
  KEY `is_active` (`is_active`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `nama_sumber_dana` (`nama_sumber_dana`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

ALTER TABLE `sub_kategori`
	ADD COLUMN `idsumber_dana` INT NULL DEFAULT NULL AFTER `nama_sub_kategori`,
	ADD INDEX `idsumber_dana` (`idsumber_dana`);


CREATE TABLE IF NOT EXISTS `kode_rekening` (
  `idkode_rekening` int NOT NULL AUTO_INCREMENT,
  `kode_rekening` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `is_active` int DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `createdby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updatedby` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `status` int DEFAULT '1',
  PRIMARY KEY (`idkode_rekening`) USING BTREE,
  KEY `is_active` (`is_active`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `nama_sumber_dana` (`kode_rekening`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

ALTER TABLE `sub_kategori`
	ADD COLUMN `idkode_rekening` INT(10) NULL DEFAULT NULL AFTER `idsumber_dana`,
	ADD INDEX `idkode_rekening` (`idkode_rekening`);


-- start sync
ALTER TABLE `verification_history`
	ADD COLUMN `idtransaction` INT(10) NULL DEFAULT NULL AFTER `idverification`,
	ADD INDEX `idtransaction` (`idtransaction`);

ALTER TABLE `transaction`
	ADD COLUMN `transaction_description` TEXT NULL COMMENT 'untuk menyimpan deskripsi yang diinput sebelum verifikasi dokumen' AFTER `transaction_status`;

ALTER TABLE `paket_belanja_detail_sub`
	ADD COLUMN `idpaket_belanja` INT(10) NULL DEFAULT NULL COMMENT 'turunan dari tabel paket_belanja' AFTER `idsub_kategori`,
	ADD INDEX `idpaket_belanja` (`idpaket_belanja`);

CREATE TABLE `npd_panjer` (
	`idnpd_panjer` INT NOT NULL AUTO_INCREMENT,
	`npd_panjer_date` DATETIME NULL COMMENT 'tanggal npd panjer',
	`npd_panjer_code` VARCHAR(50) NULL DEFAULT NULL COMMENT 'nomor invoice',
	`npd_panjer_status` VARCHAR(50) NULL DEFAULT NULL COMMENT 'OK; DRAFT',
	`total_realisasi` DOUBLE NULL DEFAULT NULL COMMENT 'total dalam 1 invoice',
	`iduser_created` BIGINT NULL DEFAULT NULL COMMENT 'user yang membuat invoice',
	`created` DATETIME NULL,
	`createdby` VARCHAR(50) NULL DEFAULT NULL,
	`updated` DATETIME NULL,
	`updatedby` VARCHAR(50) NULL DEFAULT NULL,
	`status` INT NULL DEFAULT '1',
	PRIMARY KEY (`idnpd_panjer`),
	INDEX `npd_panjer_date` (`npd_panjer_date`),
	INDEX `npd_panjer_code` (`npd_panjer_code`),
	INDEX `npd_panjer_status` (`npd_panjer_status`),
	INDEX `total_realisasi` (`total_realisasi`),
	INDEX `iduser_created` (`iduser_created`),
	INDEX `status` (`status`)
)
COLLATE='utf8mb4_0900_ai_ci'
;

CREATE TABLE `npd_panjer_detail` (
	`idnpd_panjer_detail` INT NOT NULL AUTO_INCREMENT,
	`idnpd_panjer` INT NULL,
	`idpaket_belanja` INT NULL,
	`penyedia` VARCHAR(50) NULL DEFAULT NULL,
	`iduraian` INT NULL,
	`idruang` INT NULL,
	`nama_training` VARCHAR(200) NULL DEFAULT NULL,
	`volume` DOUBLE NULL DEFAULT NULL,
	`laki` DOUBLE NULL DEFAULT NULL,
	`perempuan` DOUBLE NULL DEFAULT NULL,
	`harga_satuan` DOUBLE NULL DEFAULT NULL,
	`ppn` DOUBLE NULL DEFAULT NULL,
	`pph` DOUBLE NULL DEFAULT NULL,
	`total` DOUBLE NULL DEFAULT NULL,
	`npd_detail_description` TEXT NULL,
	`created` DATETIME NULL,
	`createdby` VARCHAR(50) NULL DEFAULT NULL,
	`updated` DATETIME NULL,
	`updatedby` VARCHAR(50) NULL DEFAULT NULL,
	`status` INT NULL DEFAULT '1',
	PRIMARY KEY (`idnpd_panjer_detail`),
	INDEX `idnpd_panjer` (`idnpd_panjer`),
	INDEX `idpaket_belanja` (`idpaket_belanja`),
	INDEX `penyedia` (`penyedia`),
	INDEX `iduraian` (`iduraian`),
	INDEX `idruang` (`idruang`),
	INDEX `nama_training` (`nama_training`),
	INDEX `volume` (`volume`),
	INDEX `laki` (`laki`),
	INDEX `perempuan` (`perempuan`),
	INDEX `harga_satuan` (`harga_satuan`),
	INDEX `ppn` (`ppn`),
	INDEX `pph` (`pph`),
	INDEX `total` (`total`),
	INDEX `status` (`status`),
	CONSTRAINT `FK__npd_panjer` FOREIGN KEY (`idnpd_panjer`) REFERENCES `npd_panjer` (`idnpd_panjer`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8mb4_0900_ai_ci'
;

ALTER TABLE `npd`
	ADD COLUMN `is_print` INT NULL DEFAULT '0' COMMENT 'untuk menandakan sudah pernah print atau belum' AFTER `npd_status`,
	ADD INDEX `is_print` (`is_print`);

-- ALTER TABLE `npd_detail`
-- 	DROP FOREIGN KEY `npd_detail_ibfk_1`;
ALTER TABLE `npd_detail`
	ADD CONSTRAINT `FK_npd_detail_npd` FOREIGN KEY (`idnpd`) REFERENCES `npd` (`idnpd`) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE `paket_belanja_detail`
	ADD CONSTRAINT `FK_paket_belanja_detail_paket_belanja` FOREIGN KEY (`idpaket_belanja`) REFERENCES `paket_belanja` (`idpaket_belanja`) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE `verification_history`
	ADD CONSTRAINT `FK_verification_history_verification` FOREIGN KEY (`idverification`) REFERENCES `verification` (`idverification`) ON UPDATE CASCADE ON DELETE CASCADE;

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'kpa', 'KPA', 'KPA', '2025-10-23 13:12:50', 'superadmin', '2025-10-23 13:12:56', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'ppk', 'PPK', 'PPK', '2025-10-23 13:13:33', 'superadmin', '2025-10-23 13:13:35', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'ppa', 'PPA', 'PPA', '2025-10-23 13:13:33', 'superadmin', '2025-10-23 13:13:35', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'ppkom', 'PPKom', 'PPKom', '2025-10-23 13:17:15', 'superadmin', '2025-10-23 13:17:20', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'pptk', 'PPTK', 'PPTK', '2025-10-23 13:17:52', 'superadmin', '2025-10-23 13:17:56', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'kontrak_pengadaan', 'Kontrak Pengadaan', 'Kontrak Pengadaan', '2025-10-23 13:18:20', 'superadmin', '2025-10-23 13:18:20', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'realisasi_anggaran', 'Realisasi Anggaran', 'Realisasi Anggaran', '2025-10-23 13:22:12', 'superadmin', '2025-10-23 13:22:16', 'superadmin');

ALTER TABLE `paket_belanja`
	ADD COLUMN `select_ppkom_pptk` INT(10) NULL DEFAULT NULL COMMENT 'PPKom / PPTK; opsi ini harus terisi dulu sebelum lanjut ke step berikutnya' AFTER `idprogram`,
	ADD INDEX `select_ppkom_pptk` (`select_ppkom_pptk`);
ALTER TABLE `paket_belanja`
	CHANGE COLUMN `select_ppkom_pptk` `select_ppkom_pptk` VARCHAR(50) NULL DEFAULT NULL COMMENT 'PPKom / PPTK; opsi ini harus terisi dulu sebelum lanjut ke step berikutnya' AFTER `idprogram`;

ALTER TABLE `paket_belanja_detail_sub`
	ADD COLUMN `spesifikasi` TEXT NULL COMMENT 'spesifikasi dari uraian yang dipilih' AFTER `is_subkategori`,
	ADD COLUMN `link_url` VARCHAR(200) NULL DEFAULT NULL COMMENT 'link url dari spesifikasi yang diisi' AFTER `spesifikasi`,
	ADD INDEX `link_url` (`link_url`);

CREATE TABLE `purchase_plan` (
	`idpurchase_plan` INT NOT NULL,
	`purchase_plan_date` DATETIME NULL COMMENT 'tanggal rencana pengadaan',
	`purchase_plan_code` VARCHAR(50) NULL DEFAULT NULL COMMENT 'nomor rencana pengadaan',
	`purchase_plan_status` VARCHAR(50) NULL DEFAULT NULL COMMENT 'status rencana pengadaan',
	`updated_status` DATETIME NULL COMMENT 'tanggal update status',
	`total_budget` DOUBLE NULL DEFAULT NULL COMMENT 'total anggaran rencana pengadaan',
	`iduser_created` BIGINT NULL DEFAULT NULL COMMENT 'id user yang membuat',
	`created` DATETIME NULL,
	`createdby` VARCHAR(50) NULL DEFAULT NULL,
	`updated` DATETIME NULL,
	`updatedby` VARCHAR(50) NULL DEFAULT NULL,
	`status` INT NULL DEFAULT '1',
	PRIMARY KEY (`idpurchase_plan`),
	INDEX `purchase_plan_date` (`purchase_plan_date`),
	INDEX `purchase_plan_code` (`purchase_plan_code`),
	INDEX `purchase_plan_status` (`purchase_plan_status`),
	INDEX `updated_status` (`updated_status`),
	INDEX `total_budget` (`total_budget`),
	INDEX `iduser_created` (`iduser_created`),
	INDEX `status` (`status`)
)
COLLATE='utf8mb4_0900_ai_ci'
;

CREATE TABLE `purchase_plan_detail` (
	`idpurchase_plan_detail` INT NOT NULL,
	`idpurchase_plan` INT NULL,
	`idpaket_belanja` INT NULL COMMENT 'ambil data dari table paket belanja detail sub',
	`idpaket_belanja_detail_sub` INT NULL COMMENT 'ambil data dari table paket belanja detail sub',
	`volume` DOUBLE NULL DEFAULT NULL,
	`purchase_plan_detail_total` DOUBLE NULL DEFAULT NULL,
	`created` DATETIME NULL,
	`createdby` VARCHAR(50) NULL DEFAULT NULL,
	`updated` DATETIME NULL,
	`updatedby` VARCHAR(50) NULL DEFAULT NULL,
	`status` INT NULL DEFAULT '1',
	PRIMARY KEY (`idpurchase_plan_detail`),
	INDEX `idpurchase_plan` (`idpurchase_plan`),
	INDEX `idpaket_belanja` (`idpaket_belanja`),
	INDEX `idpaket_belanja_detail_sub` (`idpaket_belanja_detail_sub`),
	INDEX `volume` (`volume`),
	INDEX `purchase_plan_detail_total` (`purchase_plan_detail_total`),
	INDEX `status` (`status`),
	CONSTRAINT `FK__purchase_plan` FOREIGN KEY (`idpurchase_plan`) REFERENCES `purchase_plan` (`idpurchase_plan`) ON UPDATE CASCADE ON DELETE CASCADE
)
COLLATE='utf8mb4_0900_ai_ci'
;

ALTER TABLE `paket_belanja_detail_sub`
	ADD COLUMN `status_detail_step` VARCHAR(50) NULL DEFAULT NULL COMMENT 'untuk menandakan id tabel ini di step mana' AFTER `is_idpaket_belanja_detail_sub`,
	ADD INDEX `status_detail_step` (`status_detail_step`);

ALTER TABLE `purchase_plan_detail`
	DROP FOREIGN KEY `FK__purchase_plan`;
ALTER TABLE `purchase_plan`
	CHANGE COLUMN `idpurchase_plan` `idpurchase_plan` INT(10) NOT NULL AUTO_INCREMENT FIRST;
ALTER TABLE `purchase_plan_detail`
	CHANGE COLUMN `idpurchase_plan_detail` `idpurchase_plan_detail` INT(10) NOT NULL AUTO_INCREMENT FIRST,
	ADD CONSTRAINT `FK_purchase_plan_detail_purchase_plan` FOREIGN KEY (`idpurchase_plan`) REFERENCES `purchase_plan` (`idpurchase_plan`) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE `contract` (
	`idcontract` INT NOT NULL AUTO_INCREMENT,
	`contract_date` DATETIME NULL,
	`contract_code` VARCHAR(50) NULL DEFAULT NULL,
	`contract_status` VARCHAR(50) NULL DEFAULT NULL,
	`updated_status` DATETIME NULL,
	`contract_spt` VARCHAR(200) NULL DEFAULT NULL,
	`contract_invitation_number` VARCHAR(200) NULL DEFAULT NULL,
	`contract_sp` VARCHAR(200) NULL DEFAULT NULL,
	`contract_spk` VARCHAR(200) NULL DEFAULT NULL,
	`contract_honor` VARCHAR(200) NULL DEFAULT NULL,
	`created` DATETIME NULL DEFAULT NULL,
	`createdby` VARCHAR(50) NULL DEFAULT NULL,
	`updated` DATETIME NULL DEFAULT NULL,
	`updatedby` VARCHAR(50) NULL DEFAULT NULL,
	`status` INT NULL DEFAULT '1',
	PRIMARY KEY (`idcontract`),
	INDEX `contract_date` (`contract_date`),
	INDEX `contract_code` (`contract_code`),
	INDEX `contract_status` (`contract_status`),
	INDEX `updated_status` (`updated_status`),
	INDEX `contract_spt` (`contract_spt`),
	INDEX `contract_invitation_number` (`contract_invitation_number`),
	INDEX `contract_sp` (`contract_sp`),
	INDEX `contract_spk` (`contract_spk`),
	INDEX `contract_honor` (`contract_honor`),
	INDEX `status` (`status`)
)
COLLATE='utf8mb4_0900_ai_ci'
;

CREATE TABLE `contract_detail` (
	`idcontract_detail` INT NOT NULL AUTO_INCREMENT,
	`idcontract` INT NULL,
	`idpurchase_plan` INT NULL,
	`created` DATETIME NULL DEFAULT NULL,
	`createdby` VARCHAR(50) NULL DEFAULT NULL,
	`updated` DATETIME NULL,
	`updatedby` VARCHAR(50) NULL DEFAULT NULL,
	`status` INT NULL DEFAULT '1',
	PRIMARY KEY (`idcontract_detail`),
	INDEX `idcontract` (`idcontract`),
	INDEX `idpurchase_plan` (`idpurchase_plan`),
	INDEX `status` (`status`)
)
COLLATE='utf8mb4_0900_ai_ci'
;
ALTER TABLE `contract`
	ADD COLUMN `iduser_created` BIGINT NULL DEFAULT NULL AFTER `updated_status`,
	ADD INDEX `iduser_created` (`iduser_created`);


CREATE TABLE `budget_realization` (
	`idbudget_realization` INT NOT NULL AUTO_INCREMENT,
	`realization_date` DATETIME NULL,
	`realization_code` VARCHAR(50) NULL DEFAULT NULL,
	`realization_status` VARCHAR(50) NULL DEFAULT NULL,
	`realization_description` TEXT NULL,
	`updated_status` DATETIME NULL,
	`total_realization` DOUBLE NULL DEFAULT NULL,
	`iduser_created` BIGINT NULL DEFAULT NULL,
	`created` DATETIME NULL DEFAULT NULL,
	`createdby` VARCHAR(50) NULL DEFAULT NULL,
	`updated` DATETIME NULL,
	`updatedby` VARCHAR(50) NULL DEFAULT NULL,
	`status` INT NULL DEFAULT '1',
	PRIMARY KEY (`idbudget_realization`),
	INDEX `realization_date` (`realization_date`),
	INDEX `realization_code` (`realization_code`),
	INDEX `realization_status` (`realization_status`),
	INDEX `updated_status` (`updated_status`),
	INDEX `total_realization` (`total_realization`),
	INDEX `iduser_created` (`iduser_created`),
	INDEX `status` (`status`)
)
COLLATE='utf8mb4_0900_ai_ci'
;

CREATE TABLE `budget_realization_detail` (
	`idbudget_realization_detail` BIGINT NOT NULL AUTO_INCREMENT,
	`idbudget_realization` INT NULL,
	`idpaket_belanja` INT NULL,
	`provider` VARCHAR(50) NULL DEFAULT NULL COMMENT 'penyedia',
	`idsub_kategori` INT NULL COMMENT 'uraian',
	`idruang` INT NULL COMMENT 'id ruang',
	`training_name` VARCHAR(50) NULL DEFAULT NULL COMMENT 'nama pelatihan',
	`volume` DOUBLE NULL DEFAULT NULL,
	`male` DOUBLE NULL DEFAULT NULL,
	`female` DOUBLE NULL DEFAULT NULL,
	`unit_price` DOUBLE NULL DEFAULT NULL COMMENT 'harga satuan',
	`ppn` DOUBLE NULL DEFAULT NULL,
	`pph` DOUBLE NULL DEFAULT NULL,
	`total_realization_detail` DOUBLE NULL DEFAULT NULL COMMENT 'total per detail',
	`realization_detail_description` TEXT NULL,
	`created` DATETIME NULL DEFAULT NULL,
	`createdby` VARCHAR(50) NULL DEFAULT NULL,
	`updated` DATETIME NULL,
	`updatedby` VARCHAR(50) NULL DEFAULT NULL,
	`status` INT NULL DEFAULT '1',
	PRIMARY KEY (`idbudget_realization_detail`),
	INDEX `idbudget_realization` (`idbudget_realization`),
	INDEX `idpaket_belanja` (`idpaket_belanja`),
	INDEX `provider` (`provider`),
	INDEX `idsub_kategori` (`idsub_kategori`),
	INDEX `idruang` (`idruang`),
	INDEX `training_name` (`training_name`),
	INDEX `volume` (`volume`),
	INDEX `male` (`male`),
	INDEX `female` (`female`),
	INDEX `unit_price` (`unit_price`),
	INDEX `ppn` (`ppn`),
	INDEX `pph` (`pph`),
	INDEX `total_realization_detail` (`total_realization_detail`),
	INDEX `status` (`status`)
)
COLLATE='utf8mb4_0900_ai_ci'
;
ALTER TABLE `budget_realization_detail`
	CHANGE COLUMN `idpaket_belanja` `idcontract_detail` INT(10) NULL DEFAULT NULL AFTER `idbudget_realization`,
	ADD COLUMN `idpurchase_plan_detail` INT(10) NULL DEFAULT NULL AFTER `idcontract_detail`,
	DROP INDEX `idpaket_belanja`,
	ADD INDEX `idcontract_detail` (`idcontract_detail`),
	ADD INDEX `idpurchase_plan_detail` (`idpurchase_plan_detail`);


ALTER TABLE `budget_realization_detail`
	ADD CONSTRAINT `FK_budget_realization_detail_budget_realization` FOREIGN KEY (`idbudget_realization`) REFERENCES `budget_realization` (`idbudget_realization`) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE `contract_detail`
	ADD CONSTRAINT `FK_contract_detail_contract` FOREIGN KEY (`idcontract`) REFERENCES `contract` (`idcontract`) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE `verification`
	ADD COLUMN `idbudget_realization` INT(10) NULL DEFAULT NULL AFTER `idverification`,
	ADD INDEX `idbudget_realization` (`idbudget_realization`);

ALTER TABLE `verification_history`
	CHANGE COLUMN `idtransaction` `idbudget_realization` INT(10) NULL DEFAULT NULL AFTER `idverification`,
	DROP INDEX `idtransaction`,
	ADD INDEX `idbudget_realization` (`idbudget_realization`) USING BTREE;

ALTER TABLE `paket_belanja_detail_sub`
	ADD COLUMN `volume_realization` DOUBLE NULL DEFAULT '0' COMMENT 'volume yang sudah diproses ' AFTER `volume`,
	ADD INDEX `volume_realization` (`volume_realization`);


ALTER TABLE `purchase_plan_detail`
	ADD COLUMN `volume_realization` DOUBLE NULL DEFAULT NULL AFTER `volume`,
	ADD INDEX `volume_realization` (`volume_realization`);

ALTER TABLE `purchase_plan_detail`
	ADD COLUMN `purchase_plan_detail_status` VARCHAR(50) NULL DEFAULT 'KONTRAK PENGADAAN' AFTER `purchase_plan_detail_total`,
	ADD INDEX `purchase_plan_detail_status` (`purchase_plan_detail_status`);


-- ubah urutan role name
ALTER TABLE `user_role`
	DROP FOREIGN KEY `FK_user_role_role`;

UPDATE `role` SET `idrole`='2' WHERE  `idrole`=6;
DELETE FROM `role` WHERE  `idrole`=4;
DELETE FROM `role` WHERE  `idrole`=5;
DELETE FROM `role` WHERE  `idrole`=7;
DELETE FROM `role` WHERE  `idrole`=8;
DELETE FROM `role` WHERE  `idrole`=9;
DELETE FROM `role` WHERE  `idrole`=10;
DELETE FROM `role` WHERE  `idrole`=11;
DELETE FROM `role` WHERE  `idrole`=12;
DELETE FROM `role` WHERE  `idrole`=13;
DELETE FROM `role` WHERE  `idrole`=14;
DELETE FROM `role` WHERE  `idrole`=15;

ALTER TABLE `role`
	AUTO_INCREMENT=3;

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'direktur', 'Direktur', 'Direktur', '2025-12-31 16:00:33', 'superadmin', '2025-12-31 16:00:38', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'ppa', 'PPA', 'PPA', '2025-12-31 16:02:08', 'superadmin', '2025-12-31 16:02:15', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'pptk', 'PPTK', 'PPTK', '2025-12-31 16:02:43', 'superadmin', '2025-12-31 16:02:49', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'user_spesifikasi', 'User Spesifikasi', 'User Spesifikasi', '2025-12-31 16:03:46', 'superadmin', '2025-12-31 16:03:51', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'ppk', 'PPK', 'PPK', '2025-12-31 16:04:17', 'superadmin', '2025-12-31 16:04:23', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'pp', 'PP', 'PP', '2025-12-31 16:04:47', 'superadmin', '2025-12-31 16:04:53', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'kontrak_pengadaan', 'Kontrak Pengadaan', 'Kontrak Pengadaan', '2025-12-31 16:05:26', 'superadmin', '2025-12-31 16:05:32', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'realisasi_anggaran', 'Realisasi Anggaran', 'Realisasi Anggaran', '2025-12-31 16:09:27', 'superadmin', '2025-12-31 16:09:34', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'verifikator', 'Verifikator', 'Verifikator', '2025-12-31 16:10:42', 'superadmin', '2025-12-31 16:10:48', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'npd', 'NPD', 'NPD', '2025-12-31 16:11:14', 'superadmin', '2025-12-31 16:11:20', 'superadmin');

INSERT INTO `role` (`parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`) VALUES ('0', 'bendahara', 'Bendahara', 'Bendahara', '2025-12-31 16:11:45', 'superadmin', '2025-12-31 16:11:51', 'superadmin');

ALTER TABLE `user_role`
	ADD CONSTRAINT `FK_user_role_role` FOREIGN KEY (`idrole`) REFERENCES `role` (`idrole`) ON UPDATE CASCADE ON DELETE CASCADE;


-- delete data table user role dahulu
ALTER TABLE `user_role`
	AUTO_INCREMENT=1;


DELETE FROM `noval`.`user` WHERE  `iduser`=8;
DELETE FROM `noval`.`user` WHERE  `iduser`=9;
DELETE FROM `noval`.`user` WHERE  `iduser`=10;
DELETE FROM `noval`.`user` WHERE  `iduser`=11;
DELETE FROM `noval`.`user` WHERE  `iduser`=12;
DELETE FROM `noval`.`user` WHERE  `iduser`=13;
DELETE FROM `noval`.`user` WHERE  `iduser`=14;
DELETE FROM `noval`.`user` WHERE  `iduser`=15;
DELETE FROM `noval`.`user` WHERE  `iduser`=16;
DELETE FROM `noval`.`user` WHERE  `iduser`=17;
DELETE FROM `noval`.`user` WHERE  `iduser`=18;
DELETE FROM `noval`.`user` WHERE  `iduser`=19;
DELETE FROM `noval`.`user` WHERE  `iduser`=20;
DELETE FROM `noval`.`user` WHERE  `iduser`=21;
DELETE FROM `noval`.`user` WHERE  `iduser`=22;

ALTER TABLE `user`
	AUTO_INCREMENT=3;

ALTER TABLE `budget_realization`
	CHANGE COLUMN `realization_description` `realization_description` TEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci' AFTER `updated_status`,
	ADD COLUMN `log_date_description` DATETIME NULL DEFAULT NULL COMMENT 'untuk catat perubahan pengisian keterangan verifikasi' AFTER `realization_description`;

