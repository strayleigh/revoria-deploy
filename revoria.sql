-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: revoria
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `absensi`
--

DROP TABLE IF EXISTS `absensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `absensi` (
  `id_absensi` int NOT NULL AUTO_INCREMENT,
  `tanggal_absensi` date DEFAULT NULL,
  `waktu_absen` time DEFAULT NULL,
  `status_hadir` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_anggota` int NOT NULL,
  `kode_kegiatan` int NOT NULL,
  PRIMARY KEY (`id_absensi`),
  KEY `fk_absensi_anggota` (`id_anggota`),
  KEY `fk_absensi_kegiatan` (`kode_kegiatan`),
  CONSTRAINT `fk_absensi_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_absensi_kegiatan` FOREIGN KEY (`kode_kegiatan`) REFERENCES `kegiatan` (`kode_kegiatan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `absensi`
--

LOCK TABLES `absensi` WRITE;
/*!40000 ALTER TABLE `absensi` DISABLE KEYS */;
INSERT INTO `absensi` VALUES (6,'2026-07-17','23:21:00','hadir',15,7),(8,'2026-07-17','09:09:00','hadir',14,7),(9,'2026-07-18','09:19:00','hadir',16,7),(10,'2026-07-18','09:32:00','hadir',16,2);
/*!40000 ALTER TABLE `absensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `anggota`
--

DROP TABLE IF EXISTS `anggota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anggota` (
  `id_anggota` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `no_hp` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_bergabung` date DEFAULT NULL,
  `status_anggota` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jabatan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `divisi_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id_anggota`),
  UNIQUE KEY `nik` (`nik`),
  KEY `anggota_divisi_id_foreign` (`divisi_id`),
  CONSTRAINT `anggota_divisi_id_foreign` FOREIGN KEY (`divisi_id`) REFERENCES `divisi` (`id_divisi`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anggota`
--

LOCK TABLES `anggota` WRITE;
/*!40000 ALTER TABLE `anggota` DISABLE KEYS */;
INSERT INTO `anggota` VALUES (14,'Dazai Osamu',NULL,'jljl','0812938210','2026-07-17','aktif','Ketua',1),(15,'Kasane Teto',NULL,'jljl','080808','2026-07-17','aktif','Kepala Divisi',5),(16,'Rintarou Okabe',NULL,'asjdsadsa','0000000','2026-07-18','aktif','Sekretaris',1);
/*!40000 ALTER TABLE `anggota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-budi@revoria.com|127.0.0.1','i:1;',1784211466),('laravel-cache-budi@revoria.com|127.0.0.1:timer','i:1784211466;',1784211466),('laravel-cache-len@revoria.com|127.0.0.1','i:1;',1784269604),('laravel-cache-len@revoria.com|127.0.0.1:timer','i:1784269604;',1784269604),('laravel-cache-miku@revoria.com|127.0.0.1','i:1;',1784269654),('laravel-cache-miku@revoria.com|127.0.0.1:timer','i:1784269654;',1784269654),('laravel-cache-siti@revoria.com|127.0.0.1','i:1;',1784264808),('laravel-cache-siti@revoria.com|127.0.0.1:timer','i:1784264808;',1784264808);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `divisi`
--

DROP TABLE IF EXISTS `divisi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `divisi` (
  `id_divisi` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_divisi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_divisi`),
  UNIQUE KEY `divisi_nama_divisi_unique` (`nama_divisi`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `divisi`
--

LOCK TABLES `divisi` WRITE;
/*!40000 ALTER TABLE `divisi` DISABLE KEYS */;
INSERT INTO `divisi` VALUES (5,'Bidang Ekonomi dan Usaha'),(3,'Bidang Olahraga dan Seni Budaya'),(4,'Bidang Pendidikan dan Latihan'),(2,'Bidang Rohani dan Sosial'),(1,'BPH');
/*!40000 ALTER TABLE `divisi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `folder`
--

DROP TABLE IF EXISTS `folder`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `folder` (
  `id_folder` int NOT NULL AUTO_INCREMENT,
  `nama_folder` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gdrive_folder` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_dibuat` date DEFAULT NULL,
  `kode_kegiatan` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id_folder`),
  KEY `fk_folder_kegiatan` (`kode_kegiatan`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `folder`
--

LOCK TABLES `folder` WRITE;
/*!40000 ALTER TABLE `folder` DISABLE KEYS */;
INSERT INTO `folder` VALUES (1,'dokumen 1',NULL,'2026-07-16',2),(2,'dokumen 1','https://drive.google.com/drive/folders/1MQBakHCwG3UNW1FTG0zV1YfTLTKnoqpt','2026-07-17',3),(3,'dokumen 2',NULL,'2026-07-17',6),(4,'dokumen 2','https://drive.google.com/drive/folders/1MQBakHCwG3UNW1FTG0zV1YfTLTKnoqpt','2026-07-17',3),(5,'dokumen 1','https://drive.google.com/drive/folders/1MQBakHCwG3UNW1FTG0zV1YfTLTKnoqpt','2026-07-17',3),(6,'dokumen 1','https://drive.google.com/drive/folders/1MQBakHCwG3UNW1FTG0zV1YfTLTKnoqpt','2026-07-17',3),(7,'dokumen 2','https://drive.google.com/drive/folders/1MQBakHCwG3UNW1FTG0zV1YfTLTKnoqpt','2026-07-17',3),(8,'Proposal','https://drive.google.com/drive/folders/15fFwP8Xq_pJwfbQFXoNscYZCAqf1evGP','2026-07-17',7),(9,'dokumen 1','https://drive.google.com/drive/folders/1MQBakHCwG3UNW1FTG0zV1YfTLTKnoqpt','2026-07-18',6),(10,'Surat','https://drive.google.com/drive/folders/15fFwP8Xq_pJwfbQFXoNscYZCAqf1evGP','2026-07-18',7),(11,'LPJ','https://drive.google.com/drive/folders/15fFwP8Xq_pJwfbQFXoNscYZCAqf1evGP','2026-07-18',7),(12,'Arsip','https://drive.google.com/drive/folders/15fFwP8Xq_pJwfbQFXoNscYZCAqf1evGP','2026-07-18',7),(13,'Keanggotaan','https://drive.google.com/drive/folders/15fFwP8Xq_pJwfbQFXoNscYZCAqf1evGP','2026-07-18',7);
/*!40000 ALTER TABLE `folder` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kegiatan`
--

DROP TABLE IF EXISTS `kegiatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kegiatan` (
  `kode_kegiatan` int NOT NULL AUTO_INCREMENT,
  `nama_kegiatan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `lokasi` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `progres` int DEFAULT '0',
  PRIMARY KEY (`kode_kegiatan`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kegiatan`
--

LOCK TABLES `kegiatan` WRITE;
/*!40000 ALTER TABLE `kegiatan` DISABLE KEYS */;
INSERT INTO `kegiatan` VALUES (2,'Kerajinan Tangan dengan 3R','2026-07-31','Rumah orang','Reuse, Reduce, Recycle','terjadwal',10),(3,'Perayaan Hari Besar Keagamaan','2026-07-17',NULL,NULL,'berlangsung',88),(6,'Bakti Sosial','2026-08-06',NULL,NULL,'terjadwal',0),(7,'Kerja Bakti','2026-08-08','Pantai Ancol','quick-action-btn.btn-kegiatan {\r\n    background-color: #0d6efd !important;\r\n    border-color: #0d6efd !important;\r\n    color: #fff !important;\r\n}\r\n.quick-action-btn.btn-kegiatan:hover {\r\n    background-color: transparent !important;\r\n    color: #0d6efd !important;\r\n}','terjadwal',0),(8,'Festival Musik dan Tari','2026-07-29','Balai kota','Festival musik dan tari','terjadwal',25),(9,'Perayaan HUT Daerah Khusus Jakarta (DKJ)','2026-06-22',NULL,NULL,'selesai',100);
/*!40000 ALTER TABLE `kegiatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kepanitiaan`
--

DROP TABLE IF EXISTS `kepanitiaan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kepanitiaan` (
  `id_kepanitiaan` int NOT NULL AUTO_INCREMENT,
  `kode_kegiatan` int NOT NULL,
  `id_anggota` int NOT NULL,
  `posisi` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_kepanitiaan`),
  KEY `fk_kepanitiaan_kegiatan` (`kode_kegiatan`),
  KEY `fk_kepanitiaan_anggota` (`id_anggota`),
  CONSTRAINT `fk_kepanitiaan_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_kepanitiaan_kegiatan` FOREIGN KEY (`kode_kegiatan`) REFERENCES `kegiatan` (`kode_kegiatan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kepanitiaan`
--

LOCK TABLES `kepanitiaan` WRITE;
/*!40000 ALTER TABLE `kepanitiaan` DISABLE KEYS */;
/*!40000 ALTER TABLE `kepanitiaan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_07_16_121812_add_role_to_users_table',2),(5,'2026_07_16_122158_rename_role_ketua_to_pengurus_in_users_table',3),(6,'2026_07_16_125445_fix_anggota_nullable_columns',4),(7,'2026_07_16_131954_fix_nullable_kode_kegiatan_in_transaksi_keuangan',5),(8,'2026_07_14_172331_create_anggotas_table',1),(9,'2026_07_14_172332_create_kegiatans_table',1),(10,'2026_07_14_172333_create_kepanitiaans_table',1),(11,'2026_07_14_172334_create_absensis_table',1),(12,'2026_07_14_172335_create_transaksi_keuangans_table',1),(13,'2026_07_14_172336_create_folders_table',1),(14,'2026_07_14_172331_create_anggotas_table',1),(15,'2026_07_14_172332_create_kegiatans_table',1),(16,'2026_07_14_172333_create_kepanitiaans_table',1),(17,'2026_07_14_172334_create_absensis_table',1),(18,'2026_07_14_172335_create_transaksi_keuangans_table',1),(19,'2026_07_14_172336_create_folders_table',1),(20,'2026_07_16_121812_add_role_to_users_table',1),(21,'2026_07_16_122158_rename_role_ketua_to_pengurus_in_users_table',1),(22,'2026_07_16_125445_fix_anggota_nullable_columns',1),(23,'2026_07_16_131954_fix_nullable_kode_kegiatan_in_transaksi_keuangan',1),(24,'2026_07_16_135342_add_pembina_to_role_enum_in_users_table',6),(25,'2026_07_17_114726_add_no_hp_to_users_table',7),(26,'2026_07_17_133213_create_divisi_table',8),(27,'2026_07_17_133217_add_divisi_id_to_anggota_table',8),(28,'2026_07_17_135121_drop_id_divisi_from_anggota_table',9);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi_keuangan`
--

DROP TABLE IF EXISTS `transaksi_keuangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaksi_keuangan` (
  `id_transaksi` int NOT NULL AUTO_INCREMENT,
  `jenis_transaksi` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nominal` decimal(12,2) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `kategori` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kode_kegiatan` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `fk_transaksi_kegiatan` (`kode_kegiatan`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi_keuangan`
--

LOCK TABLES `transaksi_keuangan` WRITE;
/*!40000 ALTER TABLE `transaksi_keuangan` DISABLE KEYS */;
INSERT INTO `transaksi_keuangan` VALUES (1,'pemasukan',10000.00,'2026-07-16',NULL,'Kas',2),(4,'pengeluaran',50000.00,'2026-07-16',NULL,'Kas',NULL),(5,'pemasukan',100000.00,'2026-07-17',NULL,'Kas',NULL);
/*!40000 ALTER TABLE `transaksi_keuangan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `anggota_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('pengurus','anggota','pembina') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'anggota',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_anggota_id_index` (`anggota_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (2,NULL,'admin','admin@revoria.com',NULL,'pengurus',NULL,'$2y$12$beyARHwZozJSX5G8CmGkROmFl.KcVahFC.vyklJkQnh0jRvidof32',NULL,'2026-07-16 12:22:47','2026-07-16 12:22:47'),(12,14,'Dazai Osamu','dazai@revoria.com',NULL,'pengurus',NULL,'$2y$12$boCcyM3aMKySbJMLBX9/TuvHYnz2EkIFrQA9mTPQhp5Oy5QDIbk3.',NULL,'2026-07-17 13:53:12','2026-07-17 13:54:11'),(13,15,'Kasane Teto','teto@revoria.com',NULL,'anggota',NULL,'$2y$12$Mzhriw1i12vW1xKga.kiGuAbs2LLMCm.eUQuJLDsddD22g1/j16n6',NULL,'2026-07-17 13:56:12','2026-07-18 01:50:37'),(15,16,'Rintarou Okabe','rintarou@revoria.com',NULL,'pengurus',NULL,'$2y$12$1idSWvHcS/01ElGIrjGGcugyjMFYKqCQMczRCOHsmL5CWa8l2VRk2',NULL,'2026-07-18 02:13:43','2026-07-18 02:15:46'),(18,NULL,'Robert Pattinson','pattinson@revoria.com',NULL,'pembina',NULL,'$2y$12$zCdBLwORXUiKxEby2aU3M.j6Vpfz3hI5gm7fWm1Eoz8mIZ.wPWkU2',NULL,'2026-07-18 03:14:22','2026-07-18 03:14:22');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-07-18  5:56:59
