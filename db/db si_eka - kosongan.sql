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

-- Dumping structure for table si_eka.akun_belanja
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.akun_belanja: ~0 rows (approximately)

-- Dumping structure for table si_eka.bidang_urusan
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.bidang_urusan: ~0 rows (approximately)

-- Dumping structure for table si_eka.config
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

-- Dumping data for table si_eka.config: ~5 rows (approximately)
INSERT INTO `config` (`idconfig`, `key`, `value`, `type`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(1, 'app_name', 'Si Eka', 'text', '2022-04-04 02:02:19', NULL, '2022-04-04 02:02:19', NULL, 1),
	(2, 'PPTK', 'dr. LUCKY MURNIASIH FITRI IKA MUHERI', 'text', NULL, NULL, NULL, NULL, 1),
	(3, 'nomor_DPA', 'DPA/A.1/1.02.0.00.0.00.01.0000/001/2025', 'text', NULL, NULL, NULL, NULL, 1),
	(4, 'tahun_anggaran', '2025', 'text', NULL, NULL, NULL, NULL, 1),
	(5, 'kode_surat_NPD', '900', 'text', NULL, NULL, NULL, NULL, 1);

-- Dumping structure for table si_eka.config_app
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

-- Dumping data for table si_eka.config_app: ~1 rows (approximately)
INSERT INTO `config_app` (`idconfig_app`, `key`, `value`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(1, 'disk_space', '20000', '2018-08-29 09:51:31', NULL, '2018-08-29 09:51:31', NULL, 1);

-- Dumping structure for table si_eka.kategori
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.kategori: ~0 rows (approximately)

-- Dumping structure for table si_eka.kegiatan
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.kegiatan: ~0 rows (approximately)

-- Dumping structure for table si_eka.kendaraan
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.kendaraan: ~0 rows (approximately)

-- Dumping structure for table si_eka.npd
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.npd: ~0 rows (approximately)

-- Dumping structure for table si_eka.npd_detail
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.npd_detail: ~0 rows (approximately)

-- Dumping structure for table si_eka.paket_belanja
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.paket_belanja: ~0 rows (approximately)

-- Dumping structure for table si_eka.paket_belanja_detail
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.paket_belanja_detail: ~0 rows (approximately)

-- Dumping structure for table si_eka.paket_belanja_detail_sub
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.paket_belanja_detail_sub: ~0 rows (approximately)

-- Dumping structure for table si_eka.program
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.program: ~0 rows (approximately)

-- Dumping structure for table si_eka.role
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

-- Dumping data for table si_eka.role: ~6 rows (approximately)
INSERT INTO `role` (`idrole`, `parent`, `name`, `title`, `description`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(1, 0, 'administrator', 'Superadmin', 'Superadmin', '2022-04-04 12:00:00', 'superadmin', '2022-04-04 12:00:00', 'superadmin', 1),
	(4, 0, 'verifikator', 'Verifikator', 'Verifikator', '2022-04-04 12:00:00', 'superadmin', '2022-04-04 12:00:00', 'superadmin', 1),
	(5, 0, 'bendahara', 'Bendahara', 'Bendahara', '2022-04-04 12:00:00', 'superadmin', '2022-04-04 12:00:00', 'superadmin', 1),
	(6, 0, 'admin', 'Admin', 'Admin', '2022-04-04 12:00:00', 'superadmin', '2022-04-04 12:00:00', 'superadmin', 1),
	(7, 0, 'direktur', 'Direktur', 'Direktur', '2022-04-04 12:00:00', 'superadmin', '2022-04-04 12:00:00', 'superadmin', 1),
	(8, 0, 'npd', 'NPD', 'NPD', '2022-04-04 12:00:00', 'superadmin', '2022-04-04 12:00:00', 'superadmin', 1);

-- Dumping structure for table si_eka.ruang
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.ruang: ~0 rows (approximately)

-- Dumping structure for table si_eka.satuan
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.satuan: ~0 rows (approximately)

-- Dumping structure for table si_eka.sub_kategori
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.sub_kategori: ~0 rows (approximately)

-- Dumping structure for table si_eka.sub_kegiatan
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.sub_kegiatan: ~0 rows (approximately)

-- Dumping structure for table si_eka.transaction
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.transaction: ~0 rows (approximately)

-- Dumping structure for table si_eka.transaction_detail
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.transaction_detail: ~0 rows (approximately)

-- Dumping structure for table si_eka.urusan_pemerintah
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table si_eka.urusan_pemerintah: ~0 rows (approximately)

-- Dumping structure for table si_eka.user
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

-- Dumping data for table si_eka.user: ~7 rows (approximately)
INSERT INTO `user` (`iduser`, `idrole`, `username`, `password`, `name`, `email`, `is_active`, `created`, `createdby`, `updated`, `updatedby`, `status`) VALUES
	(1, NULL, 'BucinBGMID', '5f4dcc3b5aa765d61d8327deb882cf99', 'IT Support', NULL, 1, '2018-04-03 14:14:10', NULL, '2018-04-03 14:14:10', NULL, 1),
	(2, 1, 'superadmin', '5f4dcc3b5aa765d61d8327deb882cf99', 'superadmin', 'superadmin@gmail.com', 1, '2018-08-02 08:38:40', 'superadmin', '2025-06-25 10:24:24', 'BucinBGMID', 1),
	(8, 4, 'verifikator', '2ea2aa47b5cbf1f95b9dd18c1bf8dd4c', 'admin verifikator', NULL, 1, '2025-07-19 09:57:21', 'superadmin', '2025-07-19 09:57:22', 'superadmin', 1),
	(9, 5, 'bendahara', 'c9ccd7f3c1145515a9d3f7415d5bcbea', 'admin bendahara', NULL, 1, '2025-07-19 09:57:38', 'superadmin', '2025-07-19 09:57:38', 'superadmin', 1),
	(10, 6, 'realisasi', '0d908900d878afb970ee92973fcf4760', 'admin realisasi', NULL, 1, '2025-07-19 09:58:08', 'superadmin', '2025-07-19 09:58:09', 'superadmin', 1),
	(11, 7, 'direktur', '4fbfd324f5ffcdff5dbf6f019b02eca8', 'Direktur RSUD Sumberglagah', NULL, 1, '2025-08-14 13:11:08', 'superadmin', '2025-08-14 13:11:08', 'superadmin', 1),
	(12, 8, 'npd', 'e7bb7aa4ffa0c3f89b80ac8d6f189888', 'admin npd', NULL, 1, '2025-08-14 14:27:04', 'superadmin', '2025-08-14 14:27:05', 'superadmin', 1);

-- Dumping structure for table si_eka.user_role
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

-- Dumping data for table si_eka.user_role: ~0 rows (approximately)
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

-- Dumping structure for table si_eka.verification
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.verification: ~0 rows (approximately)

-- Dumping structure for table si_eka.verification_detail
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;

-- Dumping data for table si_eka.verification_detail: ~0 rows (approximately)

-- Dumping structure for table si_eka.verification_history
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table si_eka.verification_history: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
