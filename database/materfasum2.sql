-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2026 at 07:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `materfasum2`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('materfasum2-cache-34936bf061f00c70cb0ea4458cea287a', 'i:1;', 1782669154),
('materfasum2-cache-34936bf061f00c70cb0ea4458cea287a:timer', 'i:1782669154;', 1782669154);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'bi-tag',
  `color` varchar(255) NOT NULL DEFAULT '#64748b',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `slug`, `name`, `icon`, `color`, `created_at`, `updated_at`) VALUES
(1, 'jalan', 'Jalan', 'bi-cone-striped', '#6366f1', '2026-06-17 09:33:59', '2026-06-17 09:33:59'),
(2, 'jembatan', 'Jembatan', 'bi-tools', '#ec4899', '2026-06-17 09:33:59', '2026-06-17 09:33:59'),
(3, 'lampu', 'Lampu Jalan', 'bi-lightbulb-fill', '#f59e0b', '2026-06-17 09:33:59', '2026-06-17 09:33:59'),
(4, 'taman', 'Taman', 'bi-tree-fill', '#10b981', '2026-06-17 09:33:59', '2026-06-17 09:33:59'),
(5, 'drainase', 'Drainase', 'bi-droplet-fill', '#0ea5e9', '2026-06-17 09:33:59', '2026-06-17 09:33:59'),
(6, 'fasilitas_umum', 'Fasilitas Umum', 'bi-building', '#64748b', '2026-06-17 09:33:59', '2026-06-17 09:33:59'),
(7, 'lainnya', 'Lainnya', 'bi-tags-fill', '#64748b', '2026-06-17 09:33:59', '2026-06-17 09:33:59');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `created_at`, `updated_at`, `phone`, `email`, `address`) VALUES
(1, 'Dinas Pekerjaan Umum dan Penataan Ruang', 'DPUTR', '2026-06-17 09:33:59', '2026-06-17 09:33:59', NULL, NULL, NULL),
(2, 'Dinas Perhubungan', 'DISHUB', '2026-06-17 09:33:59', '2026-06-17 09:33:59', NULL, NULL, NULL),
(3, 'Dinas Lingkungan Hidup', 'DLH', '2026-06-17 09:33:59', '2026-06-28 03:01:21', '081234567890', 'DLH@gmail.com', NULL),
(4, 'Satuan Polisi Pamong Praja', 'SATPOL_PP', '2026-06-17 09:33:59', '2026-06-17 09:33:59', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_01_01_000001_create_reports_table', 1),
(5, '2024_01_01_000002_create_report_updates_table', 1),
(6, '2024_01_01_000003_create_notifications_table', 1),
(7, '2026_04_17_172056_create_report_photos_table', 2),
(8, '2026_04_17_172120_add_is_active_to_users_table', 2),
(9, '2026_06_17_161853_create_supports_table', 3),
(10, '2026_06_17_161902_create_report_comments_table', 3),
(11, '2026_06_17_161908_add_rating_fields_to_reports_table', 3),
(12, '2026_06_17_163236_create_categories_table', 4),
(13, '2026_06_17_163244_create_departments_table', 4),
(14, '2026_06_17_163251_update_reports_table_for_admin_improvements', 4),
(15, '2026_06_28_095137_add_phone_email_address_to_departments_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('02a4f9e2-3026-4d47-8b20-728fc1d21357', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":5,\"title\":\"jalan berlubang\",\"status\":\"diproses\",\"status_label\":\"Diproses\",\"note\":null,\"message\":\"Laporan \\\"jalan berlubang\\\" telah diperbarui menjadi Diproses.\"}', '2026-05-20 09:18:26', '2026-05-19 06:04:48', '2026-05-20 09:18:26'),
('0b5e7f2a-0a64-41bc-b7f3-a48a67c76866', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":18,\"title\":\"jalan berlubang\",\"status\":\"selesai\",\"status_label\":\"Selesai\",\"note\":null,\"message\":\"Laporan \\\"jalan berlubang\\\" telah diperbarui menjadi Selesai.\"}', '2026-06-17 09:29:22', '2026-06-17 09:28:59', '2026-06-17 09:29:22'),
('0b87634d-6063-4c0e-8d6c-b6437ce3957f', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":1,\"title\":\"lampu rusak\",\"status\":\"ditolak\",\"status_label\":\"Ditolak\",\"note\":null,\"message\":\"Laporan \\\"lampu rusak\\\" telah diperbarui menjadi Ditolak.\"}', '2026-03-22 03:29:47', '2026-03-22 03:28:54', '2026-03-22 03:29:47'),
('181f2dd5-4a60-4697-af81-8762fc9036a4', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":3,\"title\":\"Jembata roboh\",\"status\":\"diproses\",\"status_label\":\"Diproses\",\"note\":\"terimah kasih telah melapor\",\"message\":\"Laporan \\\"Jembata roboh\\\" telah diperbarui menjadi Diproses.\"}', '2026-04-17 16:46:36', '2026-04-17 16:43:48', '2026-04-17 16:46:36'),
('2a7d4a69-1913-4289-b1c2-8486c95dbbca', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":2,\"title\":\"JALAN RUSAK\",\"status\":\"selesai\",\"status_label\":\"Selesai\",\"note\":\"perbaikan sudah selesai terimah kasih atas laporannyaa\",\"message\":\"Laporan \\\"JALAN RUSAK\\\" telah diperbarui menjadi Selesai.\"}', '2026-03-22 17:27:03', '2026-03-22 17:16:07', '2026-03-22 17:27:03'),
('2e74c6ca-13f2-4de9-a06f-18fedaa901c6', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":18,\"title\":\"jalan berlubang\",\"status\":\"diproses\",\"status_label\":\"Diproses\",\"note\":null,\"message\":\"Laporan \\\"jalan berlubang\\\" telah diperbarui menjadi Diproses.\"}', '2026-06-17 09:29:22', '2026-06-17 09:28:25', '2026-06-17 09:29:22'),
('33288ca1-9392-4723-b6cb-3533b28c63cf', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":14,\"title\":\"jalan berlubang\",\"status\":\"selesai\",\"status_label\":\"Selesai\",\"note\":\"aaaaa\",\"message\":\"Laporan \\\"jalan berlubang\\\" telah diperbarui menjadi Selesai.\"}', '2026-05-23 22:31:28', '2026-05-22 02:48:09', '2026-05-23 22:31:28'),
('39ece3df-d8aa-44e0-8771-349a0c927c37', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":15,\"title\":\"jalan\",\"status\":\"selesai\",\"status_label\":\"Selesai\",\"note\":null,\"message\":\"Laporan \\\"jalan\\\" telah diperbarui menjadi Selesai.\"}', '2026-05-28 02:55:48', '2026-05-23 22:33:51', '2026-05-28 02:55:48'),
('39fcffff-2a3c-421e-91c6-7bbb1d801406', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":3,\"title\":\"Jembata roboh\",\"status\":\"selesai\",\"status_label\":\"Selesai\",\"note\":null,\"message\":\"Laporan \\\"Jembata roboh\\\" telah diperbarui menjadi Selesai.\"}', '2026-04-17 16:46:36', '2026-04-17 16:45:37', '2026-04-17 16:46:36'),
('3bf52d8b-7b59-4e90-bee6-9a78610d0999', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":13,\"title\":\"ss\",\"status\":\"diproses\",\"status_label\":\"Diproses\",\"note\":null,\"message\":\"Laporan \\\"ss\\\" telah diperbarui menjadi Diproses.\"}', '2026-05-23 22:31:28', '2026-05-22 02:41:00', '2026-05-23 22:31:28'),
('5ae9c614-55a4-4abe-8b6c-05353d621680', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":9,\"title\":\"jalan berlubang\",\"status\":\"ditolak\",\"status_label\":\"Ditolak\",\"note\":null,\"message\":\"Laporan \\\"jalan berlubang\\\" telah diperbarui menjadi Ditolak.\"}', '2026-05-22 02:33:48', '2026-05-20 09:41:19', '2026-05-22 02:33:48'),
('7d8c2830-1413-41ab-a8e9-d132de657b5c', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":17,\"title\":\"Lampu\",\"status\":\"diproses\",\"status_label\":\"Diproses\",\"note\":null,\"message\":\"Laporan \\\"Lampu\\\" telah diperbarui menjadi Diproses.\"}', '2026-06-12 03:46:25', '2026-06-10 05:48:43', '2026-06-12 03:46:25'),
('8615afa7-60a6-4e75-92cd-430dd25e094f', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":17,\"title\":\"Lampu\",\"status\":\"selesai\",\"status_label\":\"Selesai\",\"note\":null,\"message\":\"Laporan \\\"Lampu\\\" telah diperbarui menjadi Selesai.\"}', '2026-06-17 09:25:21', '2026-06-17 09:14:19', '2026-06-17 09:25:21'),
('89115580-bcd9-44a9-8af0-e638ca7eb411', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":5,\"title\":\"jalan berlubang\",\"status\":\"selesai\",\"status_label\":\"Selesai\",\"note\":null,\"message\":\"Laporan \\\"jalan berlubang\\\" telah diperbarui menjadi Selesai.\"}', '2026-05-23 22:31:28', '2026-05-22 02:41:39', '2026-05-23 22:31:28'),
('a22563d8-da71-4865-b80d-eebdcea89866', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":13,\"title\":\"ss\",\"status\":\"ditolak\",\"status_label\":\"Ditolak\",\"note\":null,\"message\":\"Laporan \\\"ss\\\" telah diperbarui menjadi Ditolak.\"}', '2026-05-28 02:55:48', '2026-05-23 22:34:19', '2026-05-28 02:55:48'),
('a29631e2-a151-497d-a9e7-618581425fb7', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":19,\"title\":\"jalan berlubang\",\"status\":\"diproses\",\"status_label\":\"Diproses\",\"note\":null,\"message\":\"Laporan \\\"jalan berlubang\\\" telah diperbarui menjadi Diproses.\"}', '2026-06-28 09:47:37', '2026-06-28 03:07:07', '2026-06-28 09:47:37'),
('a8750253-7b1e-4108-8ed9-3e055366d023', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":8,\"title\":\"jalan rusak\",\"status\":\"diproses\",\"status_label\":\"Diproses\",\"note\":null,\"message\":\"Laporan \\\"jalan rusak\\\" telah diperbarui menjadi Diproses.\"}', '2026-05-20 09:18:26', '2026-05-20 06:46:02', '2026-05-20 09:18:26'),
('b05678e2-995c-4c08-8509-bd3dd1add45b', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":15,\"title\":\"jalan\",\"status\":\"diproses\",\"status_label\":\"Diproses\",\"note\":null,\"message\":\"Laporan \\\"jalan\\\" telah diperbarui menjadi Diproses.\"}', '2026-05-28 02:55:48', '2026-05-23 22:33:27', '2026-05-28 02:55:48'),
('b44b9719-7a84-45a5-ad22-4edd232e882c', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":14,\"title\":\"jalan berlubang\",\"status\":\"diproses\",\"status_label\":\"Diproses\",\"note\":\"Thank You!!!\",\"message\":\"Laporan \\\"jalan berlubang\\\" telah diperbarui menjadi Diproses.\"}', '2026-05-23 22:31:28', '2026-05-22 02:40:41', '2026-05-23 22:31:28'),
('d073135d-b9b1-4682-9561-849723543e1a', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":2,\"title\":\"JALAN RUSAK\",\"status\":\"diproses\",\"status_label\":\"Diproses\",\"note\":\"laporan sedang dala proses perbaikan\",\"message\":\"Laporan \\\"JALAN RUSAK\\\" telah diperbarui menjadi Diproses.\"}', '2026-03-22 17:27:03', '2026-03-22 17:14:49', '2026-03-22 17:27:03'),
('deef670d-a616-4950-837d-1463319f0424', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":12,\"title\":\"lampu\",\"status\":\"ditolak\",\"status_label\":\"Ditolak\",\"note\":null,\"message\":\"Laporan \\\"lampu\\\" telah diperbarui menjadi Ditolak.\"}', '2026-05-23 22:31:28', '2026-05-22 02:41:18', '2026-05-23 22:31:28'),
('eb177cd1-1e4c-4358-92c9-98c585111e13', 'App\\Notifications\\ReportStatusUpdated', 'App\\Models\\User', 3, '{\"report_id\":10,\"title\":\"jalan berlubang\",\"status\":\"diproses\",\"status_label\":\"Diproses\",\"note\":null,\"message\":\"Laporan \\\"jalan berlubang\\\" telah diperbarui menjadi Diproses.\"}', '2026-05-20 09:18:26', '2026-05-20 06:58:31', '2026-05-20 09:18:26');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('khsh01668@gmail.com', '$2y$12$9YWfI7/y2ml1CfANYJN2yOfQc4.05UnBV/TXJNVW8cmoZGQ16xk3S', '2026-05-29 00:57:54');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','diproses','selesai','ditolak') NOT NULL DEFAULT 'menunggu',
  `rejection_reason` text DEFAULT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `rating_comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `target_completion_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `user_id`, `title`, `description`, `location`, `latitude`, `longitude`, `category`, `photo`, `status`, `rejection_reason`, `rating`, `rating_comment`, `created_at`, `updated_at`, `department_id`, `target_completion_date`) VALUES
(1, 3, 'lampu rusak', 'lampu rusak karena angin', 'jl.ahmad yani no. 5', NULL, NULL, 'lampu', 'reports/vBcAWNbm7JtzWydeDPXvo0u6732P1MxWaGtTRDmy.jpg', 'ditolak', 'karena bohong', NULL, NULL, '2026-03-22 03:27:40', '2026-03-22 03:28:42', NULL, NULL),
(2, 3, 'JALAN RUSAK', '1. Karakteristik Fisik\r\nBentuk: Biasanya tidak beraturan, mulai dari retakan kecil (seperti kulit buaya) hingga lubang dalam yang menyerupai kawah kecil.\r\n\r\nMaterial: Di dalam lubang sering terlihat lapisan pondasi jalan berupa kerikil, tanah, atau patahan aspal yang tajam.\r\n\r\nGenangan: Saat hujan, lubang ini tertutup air sehingga tampak rata dengan permukaan jalan lainnya, yang sangat mengecoh pengendara.', 'JL. SOEDIRMAN NO.6', NULL, NULL, 'jalan', NULL, 'selesai', NULL, NULL, NULL, '2026-03-22 17:11:40', '2026-03-22 17:16:02', NULL, NULL),
(3, 3, 'Jembata roboh', 'karena kemarin cuaca yang buruk jadi jembatanya roboh', 'JL. SOEDIRMAN NO.6', '-7.1686561', '112.6242741', 'jembatan', NULL, 'selesai', NULL, NULL, NULL, '2026-04-17 16:41:41', '2026-06-11 09:53:48', NULL, NULL),
(5, 3, 'jalan berlubang', 'jalan utama berlubang', 'JL. SOEDIRMAN NO.6', '-7.1623913', '112.6470193', 'jembatan', NULL, 'selesai', NULL, NULL, NULL, '2026-05-19 06:02:27', '2026-06-11 09:53:48', NULL, NULL),
(9, 3, 'jalan berlubang', 'jalan utama berlubang', 'JL. SOEDIRMAN NO.6', '-7.1725728', '112.6498517', 'jalan', NULL, 'ditolak', 'maaf deskripsi tidak lengkap', NULL, NULL, '2026-05-20 06:48:19', '2026-06-11 09:53:48', NULL, NULL),
(12, 3, 'lampu', 'aknjasbjhasvchvhvhsvhv', 'Sidayu', '-6.97851', '112.57071', 'lampu', NULL, 'ditolak', 'haloooo', NULL, NULL, '2026-05-20 10:32:46', '2026-06-11 09:53:48', NULL, NULL),
(13, 3, 'ss', 'aaaaaaaaaaaaaaaaaaaaaaa', 'jl.ahmad yani no. 5', '-7.1695342', '112.6543715', 'jembatan', NULL, 'ditolak', 'halooooo', NULL, NULL, '2026-05-20 10:39:35', '2026-06-11 09:53:48', NULL, NULL),
(14, 3, 'jalan berlubang', 'jalan di jalan utama berlubang', 'Sidayu', '-6.99144', '112.55885', 'jalan', NULL, 'selesai', NULL, NULL, NULL, '2026-05-22 02:38:51', '2026-06-11 09:53:48', NULL, NULL),
(15, 3, 'jalan', 'jalan berlubangdan rusak', 'jl.ahmad yani no. 5', '-7.1656887', '112.6359203', 'lampu', NULL, 'selesai', NULL, NULL, NULL, '2026-05-23 22:31:56', '2026-06-11 09:53:48', NULL, NULL),
(17, 3, 'Lampu', 'lampu rusak dan tidak berfungsi lagi', 'jl.ahmad yani no. 5', '-7.1970377', '112.6072583', 'jembatan', NULL, 'selesai', NULL, NULL, NULL, '2026-06-10 05:45:58', '2026-06-17 09:14:00', NULL, NULL),
(18, 3, 'jalan berlubang', 'jalan berlubang di jalan raya utama sangat menganggu', 'Golokan, Gresik, East Java, Java, 61153, Indonesia', '-6.9779523', '112.5380485', 'jalan', NULL, 'selesai', NULL, 5, NULL, '2026-06-17 09:25:13', '2026-06-17 09:29:52', NULL, NULL),
(19, 3, 'jalan berlubang', 'jalan bolong semua nih tolong perbaiki', 'Golokan, Gresik, East Java, 61153, Indonesia', '-6.9780650', '112.5379754', 'jalan', NULL, 'diproses', NULL, NULL, NULL, '2026-06-28 03:03:03', '2026-06-28 03:06:34', 1, '2026-06-29 17:00:00'),
(20, 4, 'jalan bolong', 'jalan bolong nih perbaiki lahh', 'Golokan, Gresik, East Java, Java, 61153, Indonesia', '-6.9780715', '112.5379714', 'jalan', NULL, 'menunggu', NULL, NULL, NULL, '2026-06-28 09:52:34', '2026-06-28 09:52:34', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `report_comments`
--

CREATE TABLE `report_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_comments`
--

INSERT INTO `report_comments` (`id`, `report_id`, `user_id`, `body`, `created_at`, `updated_at`) VALUES
(1, 18, 3, 'haloo', '2026-06-17 09:30:02', '2026-06-17 09:30:02');

-- --------------------------------------------------------

--
-- Table structure for table `report_photos`
--

CREATE TABLE `report_photos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_photos`
--

INSERT INTO `report_photos` (`id`, `report_id`, `path`, `order`, `created_at`, `updated_at`) VALUES
(1, 3, 'reports/7Xq9qy3K4O7K7ynDY9n9j34pf3Kihe7l3PEaN1a2.jpg', 0, '2026-04-17 16:41:43', '2026-04-17 16:41:43'),
(3, 5, 'reports/FUx6KmMa77k5iNngpiGgaKc1hwJMqX3eXQs2GnBU.jpg', 0, '2026-05-19 06:02:27', '2026-05-19 06:02:27'),
(6, 9, 'reports/c0tTyMIxVAsFfaplkhbanoMe571k371GNbBnx0E6.jpg', 0, '2026-05-20 06:48:19', '2026-05-20 06:48:19'),
(7, 14, 'reports/agQQgNjxlUoQBI7T4Pdz1FQX0mxwCSmltcbWhPQF.jpg', 0, '2026-05-22 02:38:51', '2026-05-22 02:38:51'),
(8, 15, 'reports/PBF1OnPt3BFOA1qbc1I0cUf84uUfIMDLgMWMYSEj.jpg', 0, '2026-05-23 22:31:57', '2026-05-23 22:31:57'),
(10, 18, 'reports/soueQNm0ZBz3OYNHYPXj0Lyh0yfxEPQ8jGmwSFnj.jpg', 0, '2026-06-17 09:25:14', '2026-06-17 09:25:14'),
(11, 19, 'reports/iPO9rEiI3P28nrH5QNwV7Bt1x9uKO9ia5noH9nXc.jpg', 0, '2026-06-28 03:03:05', '2026-06-28 03:03:05');

-- --------------------------------------------------------

--
-- Table structure for table `report_updates`
--

CREATE TABLE `report_updates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('menunggu','diproses','selesai','ditolak') NOT NULL,
  `note` text DEFAULT NULL,
  `photo_after` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_updates`
--

INSERT INTO `report_updates` (`id`, `report_id`, `admin_id`, `status`, `note`, `photo_after`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'ditolak', NULL, NULL, '2026-03-22 03:28:42', '2026-03-22 03:28:42'),
(2, 2, 1, 'diproses', 'laporan sedang dala proses perbaikan', NULL, '2026-03-22 17:12:41', '2026-03-22 17:12:41'),
(3, 2, 1, 'diproses', 'laporan sedang dala proses perbaikan', NULL, '2026-03-22 17:14:44', '2026-03-22 17:14:44'),
(4, 2, 1, 'selesai', 'perbaikan sudah selesai terimah kasih atas laporannyaa', 'report_updates/ZqJoLXKUOv7Bh18qsfEHzROzaMKn0STuCETI3beC.jpg', '2026-03-22 17:16:02', '2026-03-22 17:16:02'),
(5, 3, 1, 'diproses', 'terimah kasih telah melapor', NULL, '2026-04-17 16:42:56', '2026-04-17 16:42:56'),
(6, 3, 1, 'selesai', NULL, NULL, '2026-04-17 16:45:29', '2026-04-17 16:45:29'),
(7, 5, 1, 'diproses', NULL, NULL, '2026-05-19 06:04:32', '2026-05-19 06:04:32'),
(10, 9, 1, 'ditolak', NULL, NULL, '2026-05-20 09:40:47', '2026-05-20 09:40:47'),
(11, 14, 1, 'diproses', 'Thank You!!!', NULL, '2026-05-22 02:40:22', '2026-05-22 02:40:22'),
(12, 13, 1, 'diproses', NULL, NULL, '2026-05-22 02:40:55', '2026-05-22 02:40:55'),
(13, 12, 1, 'ditolak', NULL, NULL, '2026-05-22 02:41:13', '2026-05-22 02:41:13'),
(14, 5, 1, 'selesai', NULL, NULL, '2026-05-22 02:41:34', '2026-05-22 02:41:34'),
(15, 14, 1, 'selesai', 'aaaaa', NULL, '2026-05-22 02:48:04', '2026-05-22 02:48:04'),
(16, 15, 1, 'diproses', NULL, NULL, '2026-05-23 22:33:13', '2026-05-23 22:33:13'),
(17, 15, 1, 'selesai', NULL, NULL, '2026-05-23 22:33:46', '2026-05-23 22:33:46'),
(18, 13, 1, 'ditolak', NULL, NULL, '2026-05-23 22:34:14', '2026-05-23 22:34:14'),
(19, 17, 1, 'diproses', NULL, NULL, '2026-06-10 05:48:01', '2026-06-10 05:48:01'),
(20, 17, 1, 'selesai', NULL, NULL, '2026-06-17 09:14:00', '2026-06-17 09:14:00'),
(21, 18, 1, 'diproses', NULL, NULL, '2026-06-17 09:28:21', '2026-06-17 09:28:21'),
(22, 18, 1, 'selesai', NULL, NULL, '2026-06-17 09:28:32', '2026-06-17 09:28:32'),
(23, 19, 1, 'diproses', NULL, NULL, '2026-06-28 03:06:34', '2026-06-28 03:06:34');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ddPzdX9BJ1KVRdXSwMSaVBkvcho1dfQc8agUksPs', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNVcxMTM0UGRRZ0h4N0s2d2hrNEV0UU9pcTI2WWNZYjF2RVVXbkx5UCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1782665349),
('euluaQ0MyqNsj6gccCOzy97JOdv1pq0yGAa8mc2A', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibFlPYzQ0TXFtaGh0ZE9tbW00eDdNT0dadDZZSnJJTm1jT0lYZ1BnMyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2VyL3JlcG9ydHMvY3JlYXRlIjtzOjU6InJvdXRlIjtzOjE5OiJ1c2VyLnJlcG9ydHMuY3JlYXRlIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1782665556),
('hXBUTpvVhx82FoifOKm1JC5jeRGnqVJAAI8zV8Oq', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia3N4WnpIaldtNjhneFpib1VSR3NIUTNsUUlzZWFIU29OOTNvMEo4OSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2VycyI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4udXNlcnMuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1782641683),
('LDzRfCWTDKIBPGYyhxWvXHVHI1lIuMXmIy8r9dvI', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQm5Xa0NDNXBQcjBVaEFJa3lFS1pKb0hpMWVYNGs0eEtzeVM5em12UiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1782639997);

-- --------------------------------------------------------

--
-- Table structure for table `supports`
--

CREATE TABLE `supports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `report_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supports`
--

INSERT INTO `supports` (`id`, `user_id`, `report_id`, `created_at`, `updated_at`) VALUES
(2, 4, 18, '2026-06-17 09:26:50', '2026-06-17 09:26:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `role`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@materfasum.id', '081234567890', NULL, 'admin', 1, NULL, '$2y$12$rT/r/mFfi2j0R6qYY0ujFeUmaaGUO5b0gdv8Kmacegutu/YH/OkIC', 'e0UlZgrECfcencduuLKsSh3k3PqRjHgnw1X97Nm91Y2Drp5WBj9oJ5l9ASAw', '2026-03-22 03:12:52', '2026-06-17 09:33:59'),
(2, 'Budi Santoso', 'user@materfasum.id', '089876543210', NULL, 'user', 1, NULL, '$2y$12$.nGRUtXwPHt/lNlUWbyjzOBWUtCyU.UwQa5D9TZ7e91VZ0YuFn88K', NULL, '2026-03-22 03:12:53', '2026-06-17 09:33:59'),
(3, 'Khildan Ash Kahfi', 'khsh01668@gmail.com', '082141386792', 'golokan sidayu gresik', 'user', 1, NULL, '$2y$12$KS4D8XYiVnwtUgDLw36GMeE44VCXO6a8yTpQr6jIUBkrGk4KlXiKu', 'baZNcjPlcqUOtgjeq8xUScV2pGr0uDJr8NfVewrvUeq74wnMrCVYiJzEyQBe', '2026-03-22 03:27:08', '2026-03-22 03:27:08'),
(4, 'Neuer', 'gkahfi513@gmail.com', '+6285735295910', 'jl golokan gresik', 'user', 1, NULL, '$2y$12$nS4GgakJsHyUExN9Q9RrH.b6mIXF.zAyaWjsbG1ERZNXx2VBfEB2.', NULL, '2026-06-04 05:28:26', '2026-06-04 05:28:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_code_unique` (`code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_user_id_foreign` (`user_id`),
  ADD KEY `reports_department_id_foreign` (`department_id`);

--
-- Indexes for table `report_comments`
--
ALTER TABLE `report_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_comments_report_id_foreign` (`report_id`),
  ADD KEY `report_comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `report_photos`
--
ALTER TABLE `report_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_photos_report_id_foreign` (`report_id`);

--
-- Indexes for table `report_updates`
--
ALTER TABLE `report_updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_updates_report_id_foreign` (`report_id`),
  ADD KEY `report_updates_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `supports`
--
ALTER TABLE `supports`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supports_user_id_report_id_unique` (`user_id`,`report_id`),
  ADD KEY `supports_report_id_foreign` (`report_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `report_comments`
--
ALTER TABLE `report_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `report_photos`
--
ALTER TABLE `report_photos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `report_updates`
--
ALTER TABLE `report_updates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `supports`
--
ALTER TABLE `supports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `report_comments`
--
ALTER TABLE `report_comments`
  ADD CONSTRAINT `report_comments_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `report_photos`
--
ALTER TABLE `report_photos`
  ADD CONSTRAINT `report_photos_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `report_updates`
--
ALTER TABLE `report_updates`
  ADD CONSTRAINT `report_updates_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_updates_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supports`
--
ALTER TABLE `supports`
  ADD CONSTRAINT `supports_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `reports` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
