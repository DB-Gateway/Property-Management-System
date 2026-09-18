-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2026 at 02:21 AM
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
-- Database: `gateway_pms`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `subject_type`, `subject_id`, `description`, `metadata`, `ip_address`, `created_at`, `updated_at`) VALUES
(3, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 04:04:14', '2026-09-15 04:04:14'),
(4, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 04:07:05', '2026-09-15 04:07:05'),
(5, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 04:07:08', '2026-09-15 04:07:08'),
(6, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published a completed requests report (1 records).', NULL, '10.0.20.100', '2026-09-15 04:07:26', '2026-09-15 04:07:26'),
(7, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 04:08:47', '2026-09-15 04:08:47'),
(8, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 04:08:50', '2026-09-15 04:08:50'),
(9, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 04:09:04', '2026-09-15 04:09:04'),
(10, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 04:09:10', '2026-09-15 04:09:10'),
(11, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 04:13:27', '2026-09-15 04:13:27'),
(12, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 04:13:31', '2026-09-15 04:13:31'),
(13, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 04:14:01', '2026-09-15 04:14:01'),
(14, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 04:14:11', '2026-09-15 04:14:11'),
(15, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 04:14:35', '2026-09-15 04:14:35'),
(16, NULL, 'login', NULL, NULL, 'Dial Lead A signed in.', NULL, '10.0.20.100', '2026-09-15 04:20:25', '2026-09-15 04:20:25'),
(17, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 04:30:17', '2026-09-15 04:30:17'),
(18, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 04:35:34', '2026-09-15 04:35:34'),
(19, NULL, 'login', NULL, NULL, 'Dial Lead signed in.', NULL, '10.0.20.100', '2026-09-15 04:36:44', '2026-09-15 04:36:44'),
(20, NULL, 'logout', NULL, NULL, 'Dial Lead signed out.', NULL, '10.0.20.100', '2026-09-15 04:38:28', '2026-09-15 04:38:28'),
(21, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 04:38:38', '2026-09-15 04:38:38'),
(22, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 04:39:12', '2026-09-15 04:39:12'),
(23, NULL, 'login', NULL, NULL, 'Dial A Handyman 1 signed in.', NULL, '10.0.20.100', '2026-09-15 04:39:37', '2026-09-15 04:39:37'),
(24, NULL, 'logout', NULL, NULL, 'Dial A Handyman 1 signed out.', NULL, '10.0.20.100', '2026-09-15 04:39:51', '2026-09-15 04:39:51'),
(25, NULL, 'login', NULL, NULL, 'Dial Lead signed in.', NULL, '10.0.20.100', '2026-09-15 04:40:06', '2026-09-15 04:40:06'),
(26, NULL, 'logout', NULL, NULL, 'Dial Lead signed out.', NULL, '10.0.20.100', '2026-09-15 04:40:10', '2026-09-15 04:40:10'),
(27, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-15 04:40:13', '2026-09-15 04:40:13'),
(29, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-15 04:40:28', '2026-09-15 04:40:28'),
(30, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 04:40:34', '2026-09-15 04:40:34'),
(32, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 04:41:08', '2026-09-15 04:41:08'),
(33, NULL, 'login', NULL, NULL, 'Dial Lead signed in.', NULL, '10.0.20.100', '2026-09-15 04:41:15', '2026-09-15 04:41:15'),
(35, NULL, 'logout', NULL, NULL, 'Dial Lead signed out.', NULL, '10.0.20.100', '2026-09-15 04:41:36', '2026-09-15 04:41:36'),
(36, NULL, 'login', NULL, NULL, 'Dial A Handyman 1 signed in.', NULL, '10.0.20.100', '2026-09-15 04:41:43', '2026-09-15 04:41:43'),
(39, NULL, 'logout', NULL, NULL, 'Dial A Handyman 1 signed out.', NULL, '10.0.20.100', '2026-09-15 04:42:27', '2026-09-15 04:42:27'),
(40, NULL, 'login', NULL, NULL, 'Dial Lead signed in.', NULL, '10.0.20.100', '2026-09-15 04:42:32', '2026-09-15 04:42:32'),
(42, NULL, 'logout', NULL, NULL, 'Dial Lead signed out.', NULL, '10.0.20.100', '2026-09-15 04:42:46', '2026-09-15 04:42:46'),
(43, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 04:44:08', '2026-09-15 04:44:08'),
(44, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published a completed requests report (1 records).', '{\"search\":\"GPM-20260915-0001\"}', '10.0.20.100', '2026-09-15 04:44:23', '2026-09-15 04:44:23'),
(45, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 04:46:19', '2026-09-15 04:46:19'),
(46, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 04:46:27', '2026-09-15 04:46:27'),
(48, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 04:47:01', '2026-09-15 04:47:01'),
(49, NULL, 'login', NULL, NULL, 'Dial Lead signed in.', NULL, '10.0.20.100', '2026-09-15 04:49:04', '2026-09-15 04:49:04'),
(50, NULL, 'logout', NULL, NULL, 'Dial Lead signed out.', NULL, '10.0.20.100', '2026-09-15 04:49:09', '2026-09-15 04:49:09'),
(51, NULL, 'login', NULL, NULL, 'Dial Lead signed in.', NULL, '10.0.20.100', '2026-09-15 04:49:24', '2026-09-15 04:49:24'),
(54, NULL, 'logout', NULL, NULL, 'Dial Lead signed out.', NULL, '10.0.20.100', '2026-09-15 04:51:20', '2026-09-15 04:51:20'),
(55, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 04:51:23', '2026-09-15 04:51:23'),
(56, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 05:04:43', '2026-09-15 05:04:43'),
(57, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 05:04:46', '2026-09-15 05:04:46'),
(58, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published a completed requests report (1 records).', NULL, '10.0.20.100', '2026-09-15 05:04:57', '2026-09-15 05:04:57'),
(59, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 05:09:47', '2026-09-15 05:09:47'),
(60, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 05:11:05', '2026-09-15 05:11:05'),
(61, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 05:12:06', '2026-09-15 05:12:06'),
(62, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-15 05:12:10', '2026-09-15 05:12:10'),
(64, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-15 05:12:28', '2026-09-15 05:12:28'),
(65, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 05:12:32', '2026-09-15 05:12:32'),
(67, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 05:13:19', '2026-09-15 05:13:19'),
(68, NULL, 'login', NULL, NULL, 'Dial Lead signed in.', NULL, '10.0.20.100', '2026-09-15 05:13:27', '2026-09-15 05:13:27'),
(69, NULL, 'logout', NULL, NULL, 'Dial Lead signed out.', NULL, '10.0.20.100', '2026-09-15 05:13:35', '2026-09-15 05:13:35'),
(70, NULL, 'login', NULL, NULL, 'Dial Lead signed in.', NULL, '10.0.20.100', '2026-09-15 05:14:18', '2026-09-15 05:14:18'),
(73, NULL, 'logout', NULL, NULL, 'Dial Lead signed out.', NULL, '10.0.20.100', '2026-09-15 05:17:12', '2026-09-15 05:17:12'),
(74, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 05:17:17', '2026-09-15 05:17:17'),
(75, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 05:19:27', '2026-09-15 05:19:27'),
(76, NULL, 'login', NULL, NULL, 'Dial Lead signed in.', NULL, '10.0.20.100', '2026-09-15 05:19:55', '2026-09-15 05:19:55'),
(77, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 05:25:48', '2026-09-15 05:25:48'),
(78, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 05:31:57', '2026-09-15 05:31:57'),
(79, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 05:33:19', '2026-09-15 05:33:19'),
(80, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 05:34:06', '2026-09-15 05:34:06'),
(81, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 05:34:10', '2026-09-15 05:34:10'),
(82, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 05:40:40', '2026-09-15 05:40:40'),
(83, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 05:40:49', '2026-09-15 05:40:49'),
(84, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 05:41:15', '2026-09-15 05:41:15'),
(85, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 05:41:19', '2026-09-15 05:41:19'),
(87, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 05:42:13', '2026-09-15 05:42:13'),
(88, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 05:42:20', '2026-09-15 05:42:20'),
(90, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 05:47:40', '2026-09-15 05:47:40'),
(91, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 05:47:45', '2026-09-15 05:47:45'),
(92, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 05:47:50', '2026-09-15 05:47:50'),
(93, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 05:47:56', '2026-09-15 05:47:56'),
(94, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 05:49:09', '2026-09-15 05:49:09'),
(95, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 05:49:24', '2026-09-15 05:49:24'),
(96, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 05:49:31', '2026-09-15 05:49:31'),
(97, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 05:49:39', '2026-09-15 05:49:39'),
(98, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 05:50:28', '2026-09-15 05:50:28'),
(99, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 05:50:39', '2026-09-15 05:50:39'),
(100, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 05:52:43', '2026-09-15 05:52:43'),
(101, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 05:53:53', '2026-09-15 05:53:53'),
(102, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 05:54:21', '2026-09-15 05:54:21'),
(103, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 05:54:27', '2026-09-15 05:54:27'),
(105, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 05:54:47', '2026-09-15 05:54:47'),
(106, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 05:54:52', '2026-09-15 05:54:52'),
(109, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 06:00:25', '2026-09-15 06:00:25'),
(110, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 06:06:06', '2026-09-15 06:06:06'),
(111, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 06:06:14', '2026-09-15 06:06:14'),
(112, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 06:06:17', '2026-09-15 06:06:17'),
(114, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 06:06:27', '2026-09-15 06:06:27'),
(115, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 06:06:31', '2026-09-15 06:06:31'),
(119, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 06:07:33', '2026-09-15 06:07:33'),
(120, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-15 06:07:38', '2026-09-15 06:07:38'),
(121, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-15 06:07:40', '2026-09-15 06:07:40'),
(122, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 06:07:46', '2026-09-15 06:07:46'),
(124, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 06:08:03', '2026-09-15 06:08:03'),
(125, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 06:08:16', '2026-09-15 06:08:16'),
(126, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 06:08:39', '2026-09-15 06:08:39'),
(127, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 06:08:45', '2026-09-15 06:08:45'),
(128, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 06:09:48', '2026-09-15 06:09:48'),
(129, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 06:11:26', '2026-09-15 06:11:26'),
(131, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 06:25:48', '2026-09-15 06:25:48'),
(132, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 06:25:57', '2026-09-15 06:25:57'),
(137, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 06:47:02', '2026-09-15 06:47:02'),
(138, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 06:47:06', '2026-09-15 06:47:06'),
(139, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 06:52:36', '2026-09-15 06:52:36'),
(140, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 06:53:21', '2026-09-15 06:53:21'),
(141, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 07:06:57', '2026-09-15 07:06:57'),
(142, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 07:07:00', '2026-09-15 07:07:00'),
(143, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 07:11:44', '2026-09-15 07:11:44'),
(144, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 07:11:49', '2026-09-15 07:11:49'),
(145, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 07:21:59', '2026-09-15 07:21:59'),
(146, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 07:22:04', '2026-09-15 07:22:04'),
(153, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 07:34:02', '2026-09-15 07:34:02'),
(154, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 07:34:05', '2026-09-15 07:34:05'),
(156, NULL, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (12 records).', NULL, '127.0.0.1', '2026-09-15 07:35:51', '2026-09-15 07:35:51'),
(157, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (12 records).', NULL, '127.0.0.1', '2026-09-15 07:35:59', '2026-09-15 07:35:59'),
(158, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 07:36:36', '2026-09-15 07:36:36'),
(159, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 07:36:40', '2026-09-15 07:36:40'),
(160, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 07:38:22', '2026-09-15 07:38:22'),
(161, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 07:38:28', '2026-09-15 07:38:28'),
(162, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (12 records).', NULL, '10.0.20.100', '2026-09-15 07:38:46', '2026-09-15 07:38:46'),
(163, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 07:44:38', '2026-09-15 07:44:38'),
(164, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 07:44:43', '2026-09-15 07:44:43'),
(167, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 07:46:54', '2026-09-15 07:46:54'),
(168, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 07:46:58', '2026-09-15 07:46:58'),
(169, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 07:49:45', '2026-09-15 07:49:45'),
(170, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 07:49:48', '2026-09-15 07:49:48'),
(171, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 07:50:07', '2026-09-15 07:50:07'),
(172, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 07:50:10', '2026-09-15 07:50:10'),
(173, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 07:53:46', '2026-09-15 07:53:46'),
(174, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 07:53:51', '2026-09-15 07:53:51'),
(175, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 07:54:34', '2026-09-15 07:54:34'),
(176, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 07:54:37', '2026-09-15 07:54:37'),
(177, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 07:54:43', '2026-09-15 07:54:43'),
(178, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 07:54:46', '2026-09-15 07:54:46'),
(179, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 08:02:43', '2026-09-15 08:02:43'),
(180, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 08:02:46', '2026-09-15 08:02:46'),
(181, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 08:29:33', '2026-09-15 08:29:33'),
(182, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 08:30:08', '2026-09-15 08:30:08'),
(183, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 08:31:17', '2026-09-15 08:31:17'),
(184, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-15 08:31:20', '2026-09-15 08:31:20'),
(186, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-15 08:31:43', '2026-09-15 08:31:43'),
(187, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 08:31:47', '2026-09-15 08:31:47'),
(188, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 08:32:05', '2026-09-15 08:32:05'),
(189, 13, 'login', NULL, NULL, 'Maui Macusi signed in.', NULL, '10.0.20.100', '2026-09-15 08:32:11', '2026-09-15 08:32:11'),
(190, 13, 'logout', NULL, NULL, 'Maui Macusi signed out.', NULL, '10.0.20.100', '2026-09-15 08:42:33', '2026-09-15 08:42:33'),
(191, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 08:42:38', '2026-09-15 08:42:38'),
(192, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 08:42:43', '2026-09-15 08:42:43'),
(193, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 08:42:48', '2026-09-15 08:42:48'),
(195, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 08:43:08', '2026-09-15 08:43:08'),
(196, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 08:43:12', '2026-09-15 08:43:12'),
(197, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 08:43:14', '2026-09-15 08:43:14'),
(198, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 08:43:17', '2026-09-15 08:43:17'),
(202, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 08:53:59', '2026-09-15 08:53:59'),
(203, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 08:54:02', '2026-09-15 08:54:02'),
(204, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 08:58:19', '2026-09-15 08:58:19'),
(205, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 08:58:22', '2026-09-15 08:58:22'),
(208, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 09:09:02', '2026-09-15 09:09:02'),
(209, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 09:09:10', '2026-09-15 09:09:10'),
(210, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 09:12:08', '2026-09-15 09:12:08'),
(211, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-15 09:12:15', '2026-09-15 09:12:15'),
(213, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-15 09:14:08', '2026-09-15 09:14:08'),
(214, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 09:14:15', '2026-09-15 09:14:15'),
(215, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 09:15:44', '2026-09-15 09:15:44'),
(216, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-15 09:15:48', '2026-09-15 09:15:48'),
(217, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-15 09:19:18', '2026-09-15 09:19:18'),
(218, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-15 09:19:26', '2026-09-15 09:19:26'),
(222, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-15 09:32:07', '2026-09-15 09:32:07'),
(223, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 00:33:55', '2026-09-16 00:33:55'),
(225, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 00:35:25', '2026-09-16 00:35:25'),
(226, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 00:35:36', '2026-09-16 00:35:36'),
(227, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 00:36:18', '2026-09-16 00:36:18'),
(228, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 00:42:01', '2026-09-16 00:42:01'),
(229, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 00:42:12', '2026-09-16 00:42:12'),
(230, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '127.0.0.1', '2026-09-16 00:42:28', '2026-09-16 00:42:28'),
(232, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '127.0.0.1', '2026-09-16 00:42:44', '2026-09-16 00:42:44'),
(233, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '127.0.0.1', '2026-09-16 00:42:53', '2026-09-16 00:42:53'),
(234, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '127.0.0.1', '2026-09-16 00:43:01', '2026-09-16 00:43:01'),
(235, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 00:43:05', '2026-09-16 00:43:05'),
(236, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 00:43:08', '2026-09-16 00:43:08'),
(237, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 00:43:11', '2026-09-16 00:43:11'),
(238, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 00:43:22', '2026-09-16 00:43:22'),
(239, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 00:43:45', '2026-09-16 00:43:45'),
(240, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 00:43:48', '2026-09-16 00:43:48'),
(241, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 00:43:54', '2026-09-16 00:43:54'),
(243, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 00:45:32', '2026-09-16 00:45:32'),
(244, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 00:45:47', '2026-09-16 00:45:47'),
(245, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 00:46:21', '2026-09-16 00:46:21'),
(246, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 00:46:27', '2026-09-16 00:46:27'),
(247, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '127.0.0.1', '2026-09-16 00:47:02', '2026-09-16 00:47:02'),
(251, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 00:57:19', '2026-09-16 00:57:19'),
(252, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '127.0.0.1', '2026-09-16 00:57:41', '2026-09-16 00:57:41'),
(254, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '127.0.0.1', '2026-09-16 00:58:10', '2026-09-16 00:58:10'),
(255, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 00:59:05', '2026-09-16 00:59:05'),
(267, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 01:27:39', '2026-09-16 01:27:39'),
(268, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 01:27:44', '2026-09-16 01:27:44'),
(269, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 01:28:36', '2026-09-16 01:28:36'),
(270, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '127.0.0.1', '2026-09-16 01:28:49', '2026-09-16 01:28:49'),
(271, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (1 records).', '{\"search\":\"GPM-20260916-0002\"}', '127.0.0.1', '2026-09-16 01:34:03', '2026-09-16 01:34:03'),
(272, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (1 records).', '{\"search\":\"GPM-20260916-0001\"}', '127.0.0.1', '2026-09-16 01:34:42', '2026-09-16 01:34:42'),
(273, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '127.0.0.1', '2026-09-16 01:34:54', '2026-09-16 01:34:54'),
(274, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 01:34:59', '2026-09-16 01:34:59'),
(275, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 01:35:02', '2026-09-16 01:35:02'),
(276, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 01:35:05', '2026-09-16 01:35:05'),
(281, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 01:35:59', '2026-09-16 01:35:59'),
(282, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '127.0.0.1', '2026-09-16 01:36:03', '2026-09-16 01:36:03'),
(283, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (1 records).', '{\"search\":\"GPM-20260916-0003\"}', '127.0.0.1', '2026-09-16 01:36:11', '2026-09-16 01:36:11'),
(286, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 01:42:01', '2026-09-16 01:42:01'),
(287, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '127.0.0.1', '2026-09-16 01:42:08', '2026-09-16 01:42:08'),
(288, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '127.0.0.1', '2026-09-16 01:44:42', '2026-09-16 01:44:42'),
(289, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '127.0.0.1', '2026-09-16 01:44:47', '2026-09-16 01:44:47'),
(290, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '127.0.0.1', '2026-09-16 01:47:07', '2026-09-16 01:47:07'),
(291, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '127.0.0.1', '2026-09-16 01:47:16', '2026-09-16 01:47:16'),
(292, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '127.0.0.1', '2026-09-16 01:48:19', '2026-09-16 01:48:19'),
(293, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '127.0.0.1', '2026-09-16 01:48:56', '2026-09-16 01:48:56'),
(294, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '127.0.0.1', '2026-09-16 01:52:32', '2026-09-16 01:52:32'),
(295, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 01:52:37', '2026-09-16 01:52:37'),
(296, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 01:54:48', '2026-09-16 01:54:48'),
(297, 13, 'login', NULL, NULL, 'Maui Macusi signed in.', NULL, '127.0.0.1', '2026-09-16 01:55:00', '2026-09-16 01:55:00'),
(298, 13, 'logout', NULL, NULL, 'Maui Macusi signed out.', NULL, '127.0.0.1', '2026-09-16 02:18:37', '2026-09-16 02:18:37'),
(299, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 02:18:43', '2026-09-16 02:18:43'),
(303, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 02:21:17', '2026-09-16 02:21:17'),
(304, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '127.0.0.1', '2026-09-16 02:21:21', '2026-09-16 02:21:21'),
(305, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '127.0.0.1', '2026-09-16 02:21:52', '2026-09-16 02:21:52'),
(306, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 02:22:04', '2026-09-16 02:22:04'),
(307, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 02:22:11', '2026-09-16 02:22:11'),
(308, 13, 'login', NULL, NULL, 'Maui Macusi signed in.', NULL, '127.0.0.1', '2026-09-16 02:22:15', '2026-09-16 02:22:15'),
(310, 13, 'logout', NULL, NULL, 'Maui Macusi signed out.', NULL, '127.0.0.1', '2026-09-16 02:22:26', '2026-09-16 02:22:26'),
(311, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 02:22:34', '2026-09-16 02:22:34'),
(314, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 02:26:23', '2026-09-16 02:26:23'),
(315, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 02:26:28', '2026-09-16 02:26:28'),
(317, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 02:26:40', '2026-09-16 02:26:40'),
(318, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 02:26:45', '2026-09-16 02:26:45'),
(319, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 02:31:45', '2026-09-16 02:31:45'),
(320, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '127.0.0.1', '2026-09-16 02:31:52', '2026-09-16 02:31:52'),
(321, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '127.0.0.1', '2026-09-16 02:32:44', '2026-09-16 02:32:44'),
(322, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 02:32:53', '2026-09-16 02:32:53'),
(323, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 02:33:04', '2026-09-16 02:33:04'),
(324, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 02:33:10', '2026-09-16 02:33:10'),
(329, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 02:36:13', '2026-09-16 02:36:13'),
(330, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '127.0.0.1', '2026-09-16 02:36:17', '2026-09-16 02:36:17'),
(331, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (4 records).', NULL, '127.0.0.1', '2026-09-16 02:39:22', '2026-09-16 02:39:22'),
(332, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (4 records).', NULL, '127.0.0.1', '2026-09-16 02:40:03', '2026-09-16 02:40:03'),
(333, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (4 records).', NULL, '127.0.0.1', '2026-09-16 02:40:54', '2026-09-16 02:40:54'),
(334, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (4 records).', NULL, '127.0.0.1', '2026-09-16 02:41:22', '2026-09-16 02:41:22'),
(335, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (4 records).', NULL, '127.0.0.1', '2026-09-16 02:41:44', '2026-09-16 02:41:44'),
(336, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '127.0.0.1', '2026-09-16 02:42:45', '2026-09-16 02:42:45'),
(337, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 02:43:10', '2026-09-16 02:43:10'),
(339, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 03:04:07', '2026-09-16 03:04:07'),
(340, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 03:04:15', '2026-09-16 03:04:15'),
(345, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 03:18:34', '2026-09-16 03:18:34'),
(346, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '127.0.0.1', '2026-09-16 03:18:47', '2026-09-16 03:18:47'),
(347, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (1 records).', '{\"search\":\"GPM-20260916-0006\"}', '127.0.0.1', '2026-09-16 03:19:23', '2026-09-16 03:19:23'),
(348, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '127.0.0.1', '2026-09-16 03:20:33', '2026-09-16 03:20:33'),
(349, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 03:20:56', '2026-09-16 03:20:56'),
(350, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-16 03:39:05', '2026-09-16 03:39:05'),
(351, 1, 'user_created', 'User', 89, 'Created user Rojeme Renz Sapno (rojemerenz.sapno@gatewaygroup.ph).', NULL, '10.0.20.100', '2026-09-16 03:42:20', '2026-09-16 03:42:20'),
(352, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-16 03:42:24', '2026-09-16 03:42:24'),
(353, 89, 'login', NULL, NULL, 'Rojeme Renz Sapno signed in.', NULL, '10.0.20.100', '2026-09-16 03:43:08', '2026-09-16 03:43:08'),
(354, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 03:43:30', '2026-09-16 03:43:30'),
(355, 89, 'login', NULL, NULL, 'Rojeme Renz Sapno signed in.', NULL, '127.0.0.1', '2026-09-16 03:43:39', '2026-09-16 03:43:39'),
(356, 89, 'logout', NULL, NULL, 'Rojeme Renz Sapno signed out.', NULL, '127.0.0.1', '2026-09-16 03:48:56', '2026-09-16 03:48:56'),
(357, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '127.0.0.1', '2026-09-16 03:49:05', '2026-09-16 03:49:05'),
(358, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '127.0.0.1', '2026-09-16 03:51:21', '2026-09-16 03:51:21'),
(359, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 03:51:45', '2026-09-16 03:51:45'),
(360, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 03:59:11', '2026-09-16 03:59:11'),
(361, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 03:59:15', '2026-09-16 03:59:15'),
(363, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 04:42:05', '2026-09-16 04:42:05'),
(364, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 04:42:14', '2026-09-16 04:42:14'),
(365, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 04:44:18', '2026-09-16 04:44:18'),
(366, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 04:44:22', '2026-09-16 04:44:22'),
(367, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 04:58:20', '2026-09-16 04:58:20'),
(368, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 04:58:24', '2026-09-16 04:58:24'),
(369, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 04:59:36', '2026-09-16 04:59:36'),
(370, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 04:59:44', '2026-09-16 04:59:44'),
(372, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 04:59:52', '2026-09-16 04:59:52'),
(373, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 04:59:55', '2026-09-16 04:59:55'),
(374, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 05:05:23', '2026-09-16 05:05:23'),
(375, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 05:05:42', '2026-09-16 05:05:42'),
(376, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 05:06:34', '2026-09-16 05:06:34'),
(377, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 05:06:38', '2026-09-16 05:06:38'),
(378, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 05:09:56', '2026-09-16 05:09:56'),
(379, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 05:10:01', '2026-09-16 05:10:01'),
(381, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 05:10:33', '2026-09-16 05:10:33'),
(382, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 05:10:40', '2026-09-16 05:10:40'),
(387, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 05:44:24', '2026-09-16 05:44:24'),
(388, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 05:44:28', '2026-09-16 05:44:28'),
(390, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 05:44:58', '2026-09-16 05:44:58'),
(391, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 05:45:03', '2026-09-16 05:45:03'),
(397, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 05:57:10', '2026-09-16 05:57:10'),
(398, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 05:58:06', '2026-09-16 05:58:06'),
(399, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 06:06:36', '2026-09-16 06:06:36'),
(400, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 06:06:43', '2026-09-16 06:06:43'),
(401, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 06:07:08', '2026-09-16 06:07:08'),
(402, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 06:07:11', '2026-09-16 06:07:11'),
(405, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 06:13:47', '2026-09-16 06:13:47'),
(406, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 06:13:50', '2026-09-16 06:13:50'),
(407, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 06:14:03', '2026-09-16 06:14:03'),
(408, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 06:14:06', '2026-09-16 06:14:06'),
(410, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 06:14:16', '2026-09-16 06:14:16'),
(411, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 06:14:19', '2026-09-16 06:14:19'),
(415, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 06:20:46', '2026-09-16 06:20:46'),
(416, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 06:20:50', '2026-09-16 06:20:50'),
(418, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 06:25:21', '2026-09-16 06:25:21'),
(419, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 06:25:26', '2026-09-16 06:25:26'),
(422, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 06:40:08', '2026-09-16 06:40:08'),
(423, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 06:40:14', '2026-09-16 06:40:14'),
(425, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 06:40:35', '2026-09-16 06:40:35'),
(426, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 06:40:42', '2026-09-16 06:40:42'),
(428, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 06:40:52', '2026-09-16 06:40:52'),
(429, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 06:40:57', '2026-09-16 06:40:57'),
(433, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 06:45:15', '2026-09-16 06:45:15'),
(434, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 06:45:22', '2026-09-16 06:45:22'),
(436, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 06:45:33', '2026-09-16 06:45:33'),
(437, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 06:45:37', '2026-09-16 06:45:37'),
(443, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 06:49:15', '2026-09-16 06:49:15'),
(444, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 06:57:50', '2026-09-16 06:57:50'),
(447, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 07:37:28', '2026-09-16 07:37:28'),
(448, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 07:37:33', '2026-09-16 07:37:33'),
(454, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 07:49:09', '2026-09-16 07:49:09'),
(455, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 07:49:24', '2026-09-16 07:49:24'),
(456, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 07:53:00', '2026-09-16 07:53:00'),
(457, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 07:53:09', '2026-09-16 07:53:09'),
(458, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 07:54:22', '2026-09-16 07:54:22'),
(459, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 07:54:28', '2026-09-16 07:54:28'),
(460, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 13:50:04', '2026-09-16 13:50:04'),
(461, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 13:53:12', '2026-09-16 13:53:12'),
(462, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 13:53:17', '2026-09-16 13:53:17'),
(463, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 14:05:20', '2026-09-16 14:05:20'),
(464, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 14:24:10', '2026-09-16 14:24:10'),
(465, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 14:31:09', '2026-09-16 14:31:09'),
(466, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 14:31:20', '2026-09-16 14:31:20'),
(467, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 14:34:51', '2026-09-16 14:34:51'),
(468, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 14:42:59', '2026-09-16 14:42:59'),
(472, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 15:11:18', '2026-09-16 15:11:18'),
(473, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 15:11:22', '2026-09-16 15:11:22'),
(475, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 15:11:45', '2026-09-16 15:11:45'),
(476, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 15:11:51', '2026-09-16 15:11:51'),
(478, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 15:26:27', '2026-09-16 15:26:27'),
(479, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 15:26:32', '2026-09-16 15:26:32'),
(481, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-16 15:26:48', '2026-09-16 15:26:48'),
(482, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-16 15:26:54', '2026-09-16 15:26:54'),
(490, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-16 15:29:28', '2026-09-16 15:29:28'),
(491, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-16 15:29:32', '2026-09-16 15:29:32'),
(492, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-17 00:05:02', '2026-09-17 00:05:02'),
(493, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-17 00:05:50', '2026-09-17 00:05:50'),
(494, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-17 00:05:54', '2026-09-17 00:05:54'),
(498, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-17 00:33:10', '2026-09-17 00:33:10'),
(499, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-17 00:33:34', '2026-09-17 00:33:34'),
(501, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-17 00:33:49', '2026-09-17 00:33:49'),
(502, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '127.0.0.1', '2026-09-17 00:33:54', '2026-09-17 00:33:54'),
(503, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '127.0.0.1', '2026-09-17 00:33:55', '2026-09-17 00:33:55'),
(504, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-17 00:33:59', '2026-09-17 00:33:59'),
(509, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-17 00:37:49', '2026-09-17 00:37:49'),
(510, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-17 00:37:53', '2026-09-17 00:37:53'),
(512, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-17 00:38:06', '2026-09-17 00:38:06'),
(513, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-17 00:38:13', '2026-09-17 00:38:13'),
(515, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-17 00:39:21', '2026-09-17 00:39:21'),
(516, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-17 00:39:23', '2026-09-17 00:39:23'),
(518, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-17 00:39:31', '2026-09-17 00:39:31'),
(519, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-17 00:39:36', '2026-09-17 00:39:36'),
(527, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-17 00:40:44', '2026-09-17 00:40:44'),
(528, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '127.0.0.1', '2026-09-17 00:40:48', '2026-09-17 00:40:48'),
(529, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '127.0.0.1', '2026-09-17 00:41:37', '2026-09-17 00:41:37'),
(530, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-17 00:42:30', '2026-09-17 00:42:30'),
(531, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-17 00:43:21', '2026-09-17 00:43:21'),
(532, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-17 00:43:26', '2026-09-17 00:43:26'),
(536, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-17 00:45:05', '2026-09-17 00:45:05'),
(537, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-17 00:45:11', '2026-09-17 00:45:11'),
(539, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-17 00:45:26', '2026-09-17 00:45:26'),
(540, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-17 00:45:29', '2026-09-17 00:45:29'),
(545, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '127.0.0.1', '2026-09-17 00:49:59', '2026-09-17 00:49:59'),
(546, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '127.0.0.1', '2026-09-17 00:50:11', '2026-09-17 00:50:11'),
(548, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '127.0.0.1', '2026-09-17 00:50:23', '2026-09-17 00:50:23'),
(549, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '127.0.0.1', '2026-09-17 00:50:27', '2026-09-17 00:50:27'),
(552, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 01:03:33', '2026-09-17 01:03:33'),
(553, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.26', '2026-09-17 01:03:57', '2026-09-17 01:03:57'),
(554, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 01:04:09', '2026-09-17 01:04:09'),
(555, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 01:04:30', '2026-09-17 01:04:30'),
(559, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 01:06:16', '2026-09-17 01:06:16'),
(560, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-17 01:06:22', '2026-09-17 01:06:22'),
(561, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-17 01:06:54', '2026-09-17 01:06:54'),
(562, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 01:08:37', '2026-09-17 01:08:37'),
(568, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 01:40:24', '2026-09-17 01:40:24'),
(569, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 01:40:30', '2026-09-17 01:40:30'),
(570, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 02:00:52', '2026-09-17 02:00:52'),
(571, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 02:04:46', '2026-09-17 02:04:46'),
(572, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 02:05:10', '2026-09-17 02:05:10'),
(573, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 02:05:13', '2026-09-17 02:05:13'),
(575, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 02:05:25', '2026-09-17 02:05:25'),
(576, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 02:05:28', '2026-09-17 02:05:28'),
(581, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 02:06:08', '2026-09-17 02:06:08'),
(582, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 02:55:27', '2026-09-17 02:55:27'),
(583, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.26', '2026-09-17 02:57:32', '2026-09-17 02:57:32'),
(584, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.26', '2026-09-17 02:58:01', '2026-09-17 02:58:01'),
(585, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 03:00:12', '2026-09-17 03:00:12'),
(586, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 03:00:44', '2026-09-17 03:00:44');
INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `subject_type`, `subject_id`, `description`, `metadata`, `ip_address`, `created_at`, `updated_at`) VALUES
(587, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 03:30:03', '2026-09-17 03:30:03'),
(588, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 03:30:09', '2026-09-17 03:30:09'),
(589, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 04:28:11', '2026-09-17 04:28:11'),
(590, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 04:28:14', '2026-09-17 04:28:14'),
(591, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 04:29:53', '2026-09-17 04:29:53'),
(592, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 04:30:00', '2026-09-17 04:30:00'),
(593, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 04:30:48', '2026-09-17 04:30:48'),
(594, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-17 04:30:50', '2026-09-17 04:30:50'),
(596, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-17 04:31:09', '2026-09-17 04:31:09'),
(597, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 04:31:14', '2026-09-17 04:31:14'),
(1072, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 04:44:54', '2026-09-17 04:44:54'),
(1073, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 04:44:58', '2026-09-17 04:44:58'),
(1074, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 04:45:10', '2026-09-17 04:45:10'),
(1075, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 04:45:30', '2026-09-17 04:45:30'),
(1076, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 04:53:16', '2026-09-17 04:53:16'),
(1077, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-17 04:53:23', '2026-09-17 04:53:23'),
(1205, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-17 05:19:15', '2026-09-17 05:19:15'),
(1206, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 05:19:19', '2026-09-17 05:19:19'),
(1207, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 05:20:20', '2026-09-17 05:20:20'),
(1208, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 05:20:22', '2026-09-17 05:20:22'),
(1209, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 05:22:43', '2026-09-17 05:22:43'),
(1210, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 05:23:30', '2026-09-17 05:23:30'),
(1211, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 05:29:37', '2026-09-17 05:29:37'),
(1212, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 05:29:46', '2026-09-17 05:29:46'),
(1213, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 05:29:50', '2026-09-17 05:29:50'),
(1214, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 05:29:55', '2026-09-17 05:29:55'),
(1215, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 06:13:54', '2026-09-17 06:13:54'),
(1216, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 06:14:00', '2026-09-17 06:14:00'),
(1217, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 06:17:17', '2026-09-17 06:17:17'),
(1218, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 06:17:22', '2026-09-17 06:17:22'),
(1219, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 06:20:34', '2026-09-17 06:20:34'),
(1220, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-17 06:20:39', '2026-09-17 06:20:39'),
(1221, 1, 'requests_reset', NULL, NULL, 'Administrator reset all request records. Dealer and user data were preserved.', '{\"requests\":30,\"attachments\":57,\"request_logs\":127}', '10.0.20.100', '2026-09-17 06:20:51', '2026-09-17 06:20:51'),
(1222, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-17 06:20:55', '2026-09-17 06:20:55'),
(1223, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 06:21:01', '2026-09-17 06:21:01'),
(1224, 12, 'request_submitted', 'PropertyRequest', 195, 'GPM-20260917-0001 was submitted by Renie Laranga for Mitsubishi (Pasig).', NULL, '10.0.20.100', '2026-09-17 06:21:11', '2026-09-17 06:21:11'),
(1225, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 06:21:14', '2026-09-17 06:21:14'),
(1226, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 06:21:18', '2026-09-17 06:21:18'),
(1227, 88, 'inspection_date_set', 'PropertyRequest', 195, 'GPM-20260917-0001 inspection start date set to September 16, 2026 and status set to On-going by Dial-A Dial-A.', '{\"inspection_date\":\"2026-09-16\",\"inspection_start_time\":\"14:21:00\",\"inspection_representatives\":[\"Dial-A\"]}', '10.0.20.100', '2026-09-17 06:21:33', '2026-09-17 06:21:33'),
(1228, 88, 'inspection_completed', 'PropertyRequest', 195, 'GPM-20260917-0001 inspection was conducted and completed by Dial-A Dial-A with representative(s): Dial-A.', '{\"inspection_date\":\"2026-09-16\",\"inspection_end_date\":\"2026-09-17\",\"inspection_start_time\":\"14:21\",\"inspection_end_time\":\"14:21\",\"representatives\":[\"Dial-A\"],\"inspection_attachment\":\"GATEWAY Branch ISP Location Assignment.xlsx\",\"inspection_attachments_count\":1,\"template_confirmed\":true,\"inspection_completed_at\":\"2026-09-17T06:21:40.000000Z\"}', '10.0.20.100', '2026-09-17 06:21:40', '2026-09-17 06:21:40'),
(1229, 88, 'work_order_date_set', 'PropertyRequest', 195, 'GPM-20260917-0001 Work Order start date set to September 16, 2026 and status set to On-going by Dial-A Dial-A.', '{\"work_order_start_date\":\"2026-09-16\",\"work_order_start_time\":\"14:21:46\",\"work_order_representatives\":[\"Dial-A\"]}', '10.0.20.100', '2026-09-17 06:21:46', '2026-09-17 06:21:46'),
(1230, 88, 'work_order_completed', 'PropertyRequest', 195, 'GPM-20260917-0001 Work Order attachments were uploaded and completed by Dial-A Dial-A.', '{\"work_order_start_date\":\"2026-09-16\",\"work_order_end_date\":\"2026-09-17\",\"work_order_start_time\":\"14:21:46\",\"work_order_end_time\":\"14:21:52\",\"representatives\":[\"Dial-A\"]}', '10.0.20.100', '2026-09-17 06:21:53', '2026-09-17 06:21:53'),
(1231, 88, 'service_report_ongoing', 'PropertyRequest', 195, 'GPM-20260917-0001 Service Report file upload initiated and marked as On-going by Dial-A Dial-A.', '{\"service_report_date\":\"2026-09-17\"}', '10.0.20.100', '2026-09-17 06:21:58', '2026-09-17 06:21:58'),
(1232, 88, 'service_report_ongoing', 'PropertyRequest', 195, 'GPM-20260917-0001 Service Report file upload initiated and marked as On-going by Dial-A Dial-A.', '{\"service_report_date\":\"2026-09-17\"}', '10.0.20.100', '2026-09-17 06:22:19', '2026-09-17 06:22:19'),
(1233, 88, 'service_report_completed', 'PropertyRequest', 195, 'GPM-20260917-0001 Service Report was completed by Dial-A Dial-A.', NULL, '10.0.20.100', '2026-09-17 06:22:21', '2026-09-17 06:22:21'),
(1234, 88, 'request_completed', 'PropertyRequest', 195, 'GPM-20260917-0001 was reviewed, confirmed, and officially marked completed by Dial-A Dial-A.', NULL, '10.0.20.100', '2026-09-17 06:22:21', '2026-09-17 06:22:21'),
(1235, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 06:52:25', '2026-09-17 06:52:25'),
(1236, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 06:52:32', '2026-09-17 06:52:32'),
(1237, 12, 'request_submitted', 'PropertyRequest', 196, 'GPM-20260917-0002 was submitted by Renie Laranga for Mitsubishi (Pasig).', NULL, '10.0.20.100', '2026-09-17 06:52:49', '2026-09-17 06:52:49'),
(1238, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 06:52:52', '2026-09-17 06:52:52'),
(1239, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 06:52:57', '2026-09-17 06:52:57'),
(1240, 88, 'inspection_date_set', 'PropertyRequest', 196, 'GPM-20260917-0002 inspection start date set to September 16, 2026 and status set to On-going by Dial-A Dial-A.', '{\"inspection_date\":\"2026-09-16\",\"inspection_start_time\":\"14:53:00\",\"inspection_representatives\":[\"Dial-A\"]}', '10.0.20.100', '2026-09-17 06:53:15', '2026-09-17 06:53:15'),
(1241, 88, 'inspection_completed', 'PropertyRequest', 196, 'GPM-20260917-0002 inspection was conducted and completed by Dial-A Dial-A with representative(s): Dial-A.', '{\"inspection_date\":\"2026-09-16\",\"inspection_end_date\":\"2026-09-17\",\"inspection_start_time\":\"14:53\",\"inspection_end_time\":\"14:53\",\"representatives\":[\"Dial-A\"],\"inspection_attachment\":\"GATEWAY Branch ISP Location Assignment.xlsx\",\"inspection_attachments_count\":1,\"template_confirmed\":true,\"inspection_completed_at\":\"2026-09-17T06:53:22.000000Z\"}', '10.0.20.100', '2026-09-17 06:53:22', '2026-09-17 06:53:22'),
(1242, 88, 'work_order_date_set', 'PropertyRequest', 196, 'GPM-20260917-0002 Work Order start date set to September 16, 2026 and status set to On-going by Dial-A Dial-A.', '{\"work_order_start_date\":\"2026-09-16\",\"work_order_start_time\":\"14:53:27\",\"work_order_representatives\":[\"Dial-A\"]}', '10.0.20.100', '2026-09-17 06:53:28', '2026-09-17 06:53:28'),
(1243, 88, 'work_order_completed', 'PropertyRequest', 196, 'GPM-20260917-0002 Work Order attachments were uploaded and completed by Dial-A Dial-A.', '{\"work_order_start_date\":\"2026-09-16\",\"work_order_end_date\":\"2026-09-17\",\"work_order_start_time\":\"14:53:27\",\"work_order_end_time\":\"14:53:33\",\"representatives\":[\"Dial-A\"]}', '10.0.20.100', '2026-09-17 06:53:34', '2026-09-17 06:53:34'),
(1244, 88, 'service_report_ongoing', 'PropertyRequest', 196, 'GPM-20260917-0002 Service Report file upload initiated and marked as On-going by Dial-A Dial-A.', '{\"service_report_date\":\"2026-09-17\"}', '10.0.20.100', '2026-09-17 06:54:05', '2026-09-17 06:54:05'),
(1245, 88, 'service_report_uploaded', 'PropertyRequest', 196, 'GPM-20260917-0002 Service Report attachments were uploaded by Dial-A Dial-A.', NULL, '10.0.20.100', '2026-09-17 06:54:07', '2026-09-17 06:54:07'),
(1246, 88, 'request_completed', 'PropertyRequest', 196, 'GPM-20260917-0002 was reviewed, confirmed, and officially marked completed by Dial-A Dial-A.', NULL, '10.0.20.100', '2026-09-17 06:56:43', '2026-09-17 06:56:43'),
(1247, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 06:56:58', '2026-09-17 06:56:58'),
(1248, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 06:57:15', '2026-09-17 06:57:15'),
(1249, 12, 'request_submitted', 'PropertyRequest', 197, 'GPM-20260917-0003 was submitted by Renie Laranga for Mitsubishi (Pasig).', NULL, '10.0.20.100', '2026-09-17 06:58:03', '2026-09-17 06:58:03'),
(1250, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 06:58:10', '2026-09-17 06:58:10'),
(1251, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 06:58:16', '2026-09-17 06:58:16'),
(1252, 88, 'inspection_date_set', 'PropertyRequest', 197, 'GPM-20260917-0003 inspection start date set to September 16, 2026 and status set to On-going by Dial-A Dial-A.', '{\"inspection_date\":\"2026-09-16\",\"inspection_start_time\":\"14:58:00\",\"inspection_representatives\":[\"Dial-A\"]}', '10.0.20.100', '2026-09-17 06:58:26', '2026-09-17 06:58:26'),
(1253, 88, 'inspection_completed', 'PropertyRequest', 197, 'GPM-20260917-0003 inspection was conducted and completed by Dial-A Dial-A with representative(s): Dial-A.', '{\"inspection_date\":\"2026-09-16\",\"inspection_end_date\":\"2026-09-17\",\"inspection_start_time\":\"14:58\",\"inspection_end_time\":\"14:58\",\"representatives\":[\"Dial-A\"],\"inspection_attachment\":\"Dealer-Directory-20260916-231036.xlsx\",\"inspection_attachments_count\":1,\"template_confirmed\":true,\"inspection_completed_at\":\"2026-09-17T06:58:32.000000Z\"}', '10.0.20.100', '2026-09-17 06:58:32', '2026-09-17 06:58:32'),
(1254, 88, 'work_order_date_set', 'PropertyRequest', 197, 'GPM-20260917-0003 Work Order start date set to September 16, 2026 and status set to On-going by Dial-A Dial-A.', '{\"work_order_start_date\":\"2026-09-16\",\"work_order_start_time\":\"14:58:37\",\"work_order_representatives\":[\"Dial-A\"]}', '10.0.20.100', '2026-09-17 06:58:38', '2026-09-17 06:58:38'),
(1255, 88, 'work_order_completed', 'PropertyRequest', 197, 'GPM-20260917-0003 Work Order attachments were uploaded and completed by Dial-A Dial-A.', '{\"work_order_start_date\":\"2026-09-16\",\"work_order_end_date\":\"2026-09-17\",\"work_order_start_time\":\"14:58:37\",\"work_order_end_time\":\"14:58:43\",\"representatives\":[\"Dial-A\"]}', '10.0.20.100', '2026-09-17 06:58:44', '2026-09-17 06:58:44'),
(1256, 88, 'service_report_ongoing', 'PropertyRequest', 197, 'GPM-20260917-0003 Service Report file upload initiated and marked as On-going by Dial-A Dial-A.', '{\"service_report_date\":\"2026-09-17\"}', '10.0.20.100', '2026-09-17 06:58:50', '2026-09-17 06:58:50'),
(1257, 88, 'service_report_uploaded', 'PropertyRequest', 197, 'GPM-20260917-0003 Service Report attachments were uploaded by Dial-A Dial-A.', NULL, '10.0.20.100', '2026-09-17 06:58:51', '2026-09-17 06:58:51'),
(1258, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-18 00:15:24', '2026-09-18 00:15:24');

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
('gateway-property-management-system-cache-pms:notification-reminders', 'b:1;', 1789690897);

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
-- Table structure for table `dealers`
--

CREATE TABLE `dealers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `source_no` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `area` varchar(255) NOT NULL,
  `brand` varchar(255) NOT NULL,
  `point_person_1` varchar(255) DEFAULT NULL,
  `contact_1` varchar(50) DEFAULT NULL,
  `point_person_2` varchar(255) DEFAULT NULL,
  `contact_2` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dealers`
--

INSERT INTO `dealers` (`id`, `source_no`, `name`, `address`, `city`, `area`, `brand`, `point_person_1`, `contact_1`, `point_person_2`, `contact_2`, `created_at`, `updated_at`) VALUES
(1, 1, 'Mitsubishi Pasig', '56 E Rodriguez Jr. Avenue, C-5, Bagong Ilog, Pasig, 1611 Metro Manila', 'Pasig', 'Metro Manila', 'Mitsubishi', 'Renie Laranga - GM', '0917 874 9226', 'Angelie Francisco - ASM', '0917 137 0702', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(2, 2, 'MG Otis', '1535 Paz Mendoza Guazon Street, Paco, Manila, Kalakhang Maynila', 'Manila', 'Metro Manila', 'MG', 'Maui Macusi - GM', '0917 824 8833', 'Jeriel Ancheta - ASM', '0926 688 0460', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(3, 3, 'Kia Otis', '1535 Paz Mendoza Guazon Street, Paco, Manila, Kalakhang Maynila', 'Manila', 'Metro Manila', 'Kia', 'Maui Macusi - GM', '0917 824 8833', 'Jeriel Ancheta - ASM', '0926 688 0460', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(4, 4, 'Vinfast Otis', '', 'Manila', 'Metro Manila', 'VinFast', 'Maui Macusi - GM', '0917 824 8833', 'Jeriel Ancheta - ASM', '0926 688 0460', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(5, 5, 'Honda Manila Bay', 'Blk 2 Lot 34 Bradco Avenue, Brangay Baclaran, Lot 34 Bradco Ave, Aseana City, Parañaque, 1702 Metro Manila', 'Parañaque', 'Metro Manila', 'Honda', 'Janice Delfin - GM', '0917 526 0700', 'Rotchelle Peñamante Torres - Utility', '0945 991 4740', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(6, 6, 'Jetour - BAIC Manila Bay', 'Blk 2 Lot 34 Bradco Avenue, Brangay Baclaran, Lot 34 Bradco Ave, Aseana City, Parañaque, 1702 Metro Manila', 'Parañaque', 'Metro Manila', 'Jetour / BAIC', 'Janice Delfin - GM', '0917 526 0700', 'Rotchelle Peñamante Torres - Utility', '0945 991 4740', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(7, 7, 'Mitsubishi Fairview', 'Don Mariano Marcos ave, Cor Peseta, North Fairview, Quezon City, 1118 Metro Manila', 'Quezon City', 'Metro Manila', 'Mitsubishi', 'Dick Albao - GM', '0917 792 6243', 'Royal Valdehueza - Tool Keeper', '0995 725 3649', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(8, 8, 'Mitsubishi Quezon Ave', '113, Sto Domingo, 113 Quezon Ave, Quezon City, 1200 Metro Manila', 'Quezon City', 'Metro Manila', 'Mitsubishi', 'Randie Tungol - GM', '0917 835 9438', 'Rosallie Dominguez - ASM', '0947 615 2954', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(9, 9, 'Geely Mandaluyong', '526 Nueve de Febrero, Addition Hills, Mandaluyong City, 1550 Kalakhang Maynila', 'Mandaluyong', 'Metro Manila', 'Geely', 'Marie Adamos - GM', '0917 792 3617', '', '', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(10, 10, 'Geely Cainta', 'KM 17, Celilu Industrial Compound, Sto. Domingo Ext, Ortigas Ave, Cainta, 1900 Rizal', 'Cainta', 'Metro Manila', 'Geely', 'Ferdinand Uy - GM', '0920 964 7715', 'Glenn Sunga - GRM', '0929 856 4495', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(11, 11, 'Honda Fairview', 'Lot 4 Block 4, Neopolitan Business Center, Greater Lagro, Quezon City, Metro Manila', 'Quezon City', 'Metro Manila', 'Honda', 'Rachelle Dy Diyco - GM', '0917 520 9413', 'Rochelle Roxas - Service Admin Sup.', '0917 730 1415', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(12, 12, 'Honda Cainta', 'Km.22 Ortigas Ave Ext, Cainta, 1900 Rizal', 'Cainta', 'Metro Manila', 'Honda', 'Ferdinand Uy - GM', '0920 964 7715', 'Art Bustillos - ASM', '0917 844 4849', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(13, 13, 'Honda Cars Makati', '2280 Don Chino Roces Avenue, Extension, Makati City, 1231 Metro Manila', 'Makati', 'Metro Manila', 'Honda', 'Cora Ortega - GM', '0917 325 1997', 'Ryan Saizon - ASM', '0917 145 5439', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(14, 14, 'Hyundai Makati', '2290 Chino Roces Ave, Makati City, 1231 Metro Manila', 'Makati', 'Metro Manila', 'Hyundai', 'Gets Wambangco - GM', '0917 899 4387', 'Jade Rudolfo - Utility', '0931 794 3597', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(15, 15, 'Belfast Stockyard', 'Lot 5, Block 7, Belfast Street, Neopolitan Business Park, Fairview Quezon City', 'Quezon City', 'Metro Manila', 'Gateway Operations', 'Jun Samadan', '0917 838 9320', 'Robertson Capian - Security Head', '0935 056 5599', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(16, 16, 'Neopolitan Stockyard  FV', 'Greater Lagro, Novaliches Quezon City', 'Quezon City', 'Metro Manila', 'Gateway Operations', 'Jun Samadan', '0917 838 9320', 'Robertson Capian - Security Head', '0935 056 5599', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(17, 17, 'New FV Stockyard (beside Honda FV)', 'Barangay Pasong Putik, Novaliches Quezon City', 'Quezon City', 'Metro Manila', 'Gateway Operations', 'Jun Samadan', '0917 838 9320', 'Robertson Capian - Security Head', '0935 056 5599', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(18, 18, 'Labayani Stockyard', '# 7 Labayani Street, corner Dollar Street, North Fairview , Quezon City', 'Quezon City', 'Metro Manila', 'Gateway Operations', 'Jun Samadan', '0917 838 9320', 'Robertson Capian - Security Head', '0935 056 5599', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(19, 19, 'Taguig Stockyard', 'Palingon Tipaz , Taguig City', 'Taguig', 'Metro Manila', 'Gateway Operations', 'Jun Samadan', '0917 838 9320', 'Robertson Capian - Security Head', '0935 056 5599', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(20, 20, 'Otis Stockyard', 'Paco, Manila', 'Manila', 'Metro Manila', 'Gateway Operations', 'Jun Samadan', '0917 838 9320', 'Robertson Capian - Security Head', '0935 056 5599', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(21, 21, 'Mitsubishi Sucat', '8280 Brgy. San Isidro, Dr Arcadio Santos Ave, Sucat, Parañaque, 1700 Metro Manila', 'Parañaque', 'Metro Manila', 'Mitsubishi', 'Jay-Ar Dyogi - GM', '0920 952 7993', 'Marvin Sarmiento - GRM', '0917 538 3870', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(22, 22, 'Mitsubishi Greenhills', '65 Connecticut, San Juan City, 1503 Metro Manila', 'San Juan', 'Metro Manila', 'Mitsubishi', 'Reggie De Guzman - GM', '0917 847 8440', 'Cris Mark Linga - Utility', '0926 107 4947', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(23, 23, 'Honda Marcos Highway', '64, Marcos High Way, Dela Paz, Pasig, 1600 Metro Manila', 'Pasig', 'Metro Manila', 'Honda', 'Kim Lanzanas - GM', '0905 301 0905', 'Aly Boncajes - Service Admin & CR Sup.', '0962 080 5342', '2026-09-15 03:58:18', '2026-09-16 05:13:58'),
(24, 24, 'Honda Las Pinas', 'Brgy, L2-A Alabang–Zapote Rd, Talon Dos, Las Piñas, 1747 Metro Manila', 'Las Piñas', 'Metro Manila', 'Honda', 'Ernie Samson - GM', '0917 880 9374', '', '', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(25, 25, 'Honda Cars Alabang', 'Km 23, Udings Comp, 1771 W Service Rd, Muntinlupa, 1771 Metro Manila', 'Muntinlupa', 'Metro Manila', 'Honda', 'Ernie Samson - GM', '0917 880 9374', 'Robert Chiao - ASM', '0917 178 1149', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(26, 26, 'MG Las Piñas', 'Brgy, L2-B Alabang–Zapote Rd, Talon Dos, Las Piñas, 1747 Metro Manila', 'Las Piñas', 'Metro Manila', 'MG', 'Emely Salmo - GM', '0917 164 6226', '', '0955 692 0219', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(27, 27, 'Suzuki Pasong Tamo', '2326 Chino Roces Extn., Brgy. Magallanes, Makati City', 'Makati', 'Metro Manila', 'Suzuki', 'Mildred Martin - GM', '0915 589 2016', 'Donna Del Rosario - GRM', '0917 597 1818', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(28, 28, 'Omoda Jaecoo Abad Santos', '2931 151, Tondo, Manila, Metro Manila', 'Manila', 'Metro Manila', 'Omoda Jaecoo', 'Maui Macusi - GM', '0917 824 8833', 'Jeriel Ancheta - ASM', '0926 688 0460', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(29, 29, 'Omoda Jaecoo Greenhills', '', 'San Juan', 'Metro Manila', 'Omoda Jaecoo', 'Reggie De Guzman - GM', '0917 847 8440', '', '', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(30, 30, 'Omoda Jaecoo Bacoor', '6475 DAANG HARI RD, Molino Rd, Bacoor, 4102 Cavite', 'Bacoor', 'Metro Manila', 'Omoda Jaecoo', 'Patrick Carandang - GM', '0920 924 8762', 'Julius Abadia - ASM', '0906 577 4153', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(31, 31, 'Changan Bacoor', '6475 Daang Hari Road, Bacoor, Cavite', 'Bacoor', 'Metro Manila', 'Changan', 'Patrick Carandang - GM', '0920 924 8762', 'Julius Abadia - ASM', '0906 577 4153', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(32, 32, 'Jetour - BAIC BGC', 'G/F W City Center, 7th Street, cor 30th St, Bonifacio Global City, Taguig', 'Taguig', 'Metro Manila', 'Jetour / BAIC', 'Julius Fabe - GRM', '0917 272 0459', 'Roderick Arbol - Utility', '0966 680 5850', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(33, 33, 'Kodak Bldg', '2247 Chino Roces Ave, Makati City, 1231 Metro Manila', 'Makati', 'Metro Manila', 'Gateway Operations', '', '', '', '', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(34, 34, 'Gateway SM South Mall - inline', '', 'Las Piñas', 'Metro Manila', 'Gateway Operations', 'Jay-Ar Dyogi - GM', '0920 952 7993', 'Edzel Sumido - GRM', '0994 789 9285', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(35, 35, 'Las Pinas Stockyard (LP1)', 'Lot 304, Alabang Zapote Road, Talon Dos Las Pinas City', 'Las Piñas', 'Metro Manila', 'Gateway Operations', 'Jun Samadan', '0917 838 9320', 'Robertson Capian - Security Head', '0935 056 5599', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(36, 36, 'Las Pinas Stockyard (LP2)', '', 'Las Piñas', 'Metro Manila', 'Gateway Operations', 'Jun Samadan', '0917 838 9320', 'Robertson Capian - Security Head', '0935 056 5599', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(37, 37, 'Las Pinas Stockyard (LP3)', 'Almanza Las Pinas, Metro Manila', 'Las Piñas', 'Metro Manila', 'Gateway Operations', 'Jun Samadan', '0917 838 9320', 'Robertson Capian - Security Head', '0935 056 5599', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(38, 38, 'Cainta Stockyard', '', 'Cainta', 'Metro Manila', 'Gateway Operations', 'Jun Samadan', '0917 838 9320', 'Robertson Capian - Security Head', '0935 056 5599', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(39, 39, 'BRP Alabang (Honda)', '', 'Muntinlupa', 'Metro Manila', 'Gateway Operations', 'Ernie Samson - GM', '0917 880 9374', '', '', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(40, 40, 'Gateway SM San Mateo - inline', '', 'San Mateo', 'Metro Manila', 'Gateway Operations', 'Dick Albao - GM', '0917 792 6243', 'Tina Reyes - GRM', '0917 846 7575', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(41, 41, 'Bacoor Yard', '6475 Daang Hari Road, Bacoor City Cavite', 'Bacoor', 'Metro Manila', 'Gateway Operations', 'Jun Samadan', '0917 838 9320', 'Robertson Capian - Security Head', '0935 056 5599', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(42, 42, 'Gateway Calamba (Mitsu, Kia)', 'National Highway Crossing Calamba Laguna', 'Calamba', 'South Luzon', 'Gateway Operations', 'Bong Rosal - Area Head', '0918 914 6923', 'Ghie Tungol - GM', '0917 625 1775', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(43, 43, 'Mitsubishi Pili (CamSur)', 'zone 1 san juan, Palestina, Pili, 4410 Camarines Sur', 'Pili', 'South Luzon', 'Mitsubishi', 'Angie Janer-Luz - GM', '0917 514 4676', 'Chalen Padua - Ops Manager', '0917 526 7448', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(44, 44, 'Mitsubishi Legaspi', 'Rizal St, Legazpi Port District, Legazpi City, 4500 Albay', 'Legazpi', 'South Luzon', 'Mitsubishi', 'Cris Grajo - GM', '0977 842 1277', 'Chalen Padua - Ops Manager', '0917 526 7448', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(45, 45, 'Geely - Fuso Lipa', 'President Jose P. Laurel Hwy, Lipa City, Batangas', 'Lipa', 'South Luzon', 'Geely', 'Bong Rosal - Area Head', '0918 914 6923', 'Mariver Garcia - GM', '0906 293 0228', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(46, 46, 'Geely Naga', '', 'Naga', 'South Luzon', 'Geely', '', '', '', '', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(47, 47, 'Geely Dasma', '', 'Dasmariñas', 'South Luzon', 'Geely', '', '', '', '', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(48, 48, 'MG Batangas', 'Diversion Road, Brgy Balagtas Batangas City', 'Batangas', 'South Luzon', 'MG', 'Bong Rosal - Area Head', '0918 914 6923', 'Mariver Garcia - GM', '0906 293 0228', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(49, 49, 'MG San Pablo', '85, San Ignacio, San Pablo City, 4000 Laguna', 'San Pablo', 'South Luzon', 'MG', 'Bong Rosal - Area Head', '0918 914 6923', 'Ariane Legarte - GM', '0917 899 6652', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(50, 50, 'Kia Dasmarinas', 'SAN AGUSTIN II, 93 Emilio Aguinaldo Hwy, Dasmariñas, 4114 Cavite', 'Dasmariñas', 'South Luzon', 'Kia', 'Bong Rosal - Area Head', '0918 914 6923', 'Heart Reyes - GM', '0975 501 1854', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(51, 51, 'Gateway SM Dasma - inline', '', 'Dasmariñas', 'South Luzon', 'Gateway Operations', 'Bong Rosal - Area Head', '0918 914 6923', 'Jojo Legarra - GRM', '0997 844 5758', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(52, 52, 'Kia San Pablo', 'Maharlika Highway Brgy. San Ignacio San Pablo City', 'San Pablo', 'South Luzon', 'Kia', 'Bong Rosal - Area Head', '0918 914 6923', 'Ariane Legarte - GM', '0917 899 6652', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(53, 53, 'Suzuki San Pablo', '', 'San Pablo', 'South Luzon', 'Suzuki', 'Bong Rosal - Area Head', '0918 914 6923', 'Ariane Legarte - GM', '0917 899 6652', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(54, 54, 'Suzuki Sta Rosa', '4026 Santa Rosa - Tagaytay Rd, City of Santa Rosa, 4026 Laguna', 'Santa Rosa', 'South Luzon', 'Suzuki', 'Bong Rosal - Area Head', '0918 914 6923', 'Rona Bringino - GM', '0947 994 6813', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(55, 55, 'Suzuki Palawan', '231 national Highway corner BM Road, Brgy San Manuel Puerto Princesa Palawan', 'Puerto Princesa', 'South Luzon', 'Suzuki', 'Ivy Balerite - GRM', '0917 812 3859', '', '', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(56, 56, 'Omoda Jaecoo Carmona', 'Carmona Autoplex, South Luzon Expressway Toll Plaza Exit, Barangay Maduya, Carmona, Cavite', 'Carmona', 'South Luzon', 'Omoda Jaecoo', 'Jhojo Claros - GM', '0956 450 5147', '', '', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(57, 57, 'Omoda Jaecoo Lipa', '', 'Lipa', 'South Luzon', 'Omoda Jaecoo', 'Bong Rosal - Area Head', '0918 914 6923', 'Mariver Garcia - GM', '0906 293 0228', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(58, 58, 'Changan Batangas', '', 'Batangas', 'South Luzon', 'Changan', 'Bong Rosal - Area Head', '0918 914 6923', 'Mariver Garcia - GM', '0906 293 0228', '2026-09-15 03:58:18', '2026-09-15 03:58:18'),
(59, 59, 'X Carmona', '', 'Carmona', 'South Luzon', 'Gateway Operations', '', '', '', '', '2026-09-15 03:58:19', '2026-09-15 03:58:19'),
(60, 60, 'Gateway Sto Tomas (Lianas)', '', 'Santo Tomas', 'South Luzon', 'Gateway Operations', 'Bong Rosal - Area Head', '0918 914 6923', '', '', '2026-09-15 03:58:19', '2026-09-15 03:58:19'),
(61, 61, 'San Pablo Stock Yard (beside Kia)', 'Brgy. San Ignacio San Pablo City Laguna', 'San Pablo', 'South Luzon', 'Gateway Operations', 'Bong Rosal - Area Head', '0918 914 6923', '', '', '2026-09-15 03:58:19', '2026-09-15 03:58:19'),
(62, 62, 'Cabanas Showroom', 'Malolos, Bulacan', 'Malolos', 'North Luzon', 'Gateway Operations', 'Junie Diyco - Area Head', '0917 527 4767', 'Diana Rose Suba - GRM', '0939 904 0205', '2026-09-15 03:58:19', '2026-09-15 03:58:19'),
(63, 63, 'Honda Isabela', '26 Asian Highway, Brgy. Tagaran, Cauayan City, Isabela', 'Cauayan', 'North Luzon', 'Honda', 'Rolly Maur - GM', '0917 574 1339', 'Alejandrino Sagario Jr. - ASM', '0926 449 3423', '2026-09-15 03:58:19', '2026-09-15 03:58:19'),
(64, 64, 'Geely Tarlac', 'MacArthur Hwy, Brgy. San Sebastian, Tarlac City', 'Tarlac City', 'North Luzon', 'Geely', 'Dodie Ronquillo - Area Head', '0906 458 2260', 'Charlie Marcos - ASM', '0949 422 2651', '2026-09-15 03:58:19', '2026-09-15 03:58:19'),
(65, 65, 'Geely Angeles', 'Fil-Am Friendship Hwy, Brgy. Pampang, Angeles City, Pampanga', 'Angeles', 'North Luzon', 'Geely', 'Dodie Ronquillo - Area Head', '0906 458 2260', 'Apple De Leon - GRM', '0917 500 8708', '2026-09-15 03:58:19', '2026-09-15 03:58:19'),
(66, 66, 'Geely Baliuag', '', 'Baliuag', 'North Luzon', 'Geely', '', '', '', '', '2026-09-15 03:58:19', '2026-09-15 03:58:19'),
(67, 67, 'MG Marilao', 'McArthur High way  Ibayo Marilao Bulacan 3019', 'Marilao', 'North Luzon', 'MG', 'Junie Diyco - Area Head', '0917 527 4767', 'Diana Rose Suba - GRM', '0939 904 0205', '2026-09-15 03:58:19', '2026-09-15 03:58:19'),
(68, 68, 'MG Angeles', 'Lots 31-38 Blck 48, Fil-Am Friendship Highway, Pampanga, Amgeles City', 'Angeles', 'North Luzon', 'MG', 'Dodie Ronquillo - Area Head', '0906 458 2260', 'Aaron Pascual - GRM', '0999 778 8602', '2026-09-15 03:58:19', '2026-09-15 03:58:19'),
(69, 69, 'Changan Angeles', '', 'Angeles', 'North Luzon', 'Changan', 'Dodie Ronquillo - Area Head', '0906 458 2260', 'Aaron Pascual - GRM', '0999 778 8602', '2026-09-15 03:58:19', '2026-09-15 03:58:19'),
(70, 70, 'San Fernando Stockyard', '', 'San Fernando', 'North Luzon', 'Gateway Operations', 'Dodie Ronquillo - Area Head', '0906 458 2260', '', '', '2026-09-15 03:58:19', '2026-09-15 03:58:19');

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

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(85, 'default', '{\"uuid\":\"329e62a9-e521-461a-9047-98ab10b5238f\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"316a52a2-7abe-58e0-965b-3f96da1b7ae7\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789603775,\"delay\":null}', 0, NULL, 1789603775, 1789603775),
(86, 'default', '{\"uuid\":\"2048524c-b15c-438f-89a9-304784800580\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"4c315a5b-05e8-5d7f-89c2-3877dee52c0b\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789603778,\"delay\":null}', 0, NULL, 1789603778, 1789603778),
(87, 'default', '{\"uuid\":\"4dbd2a2b-50d9-47c9-9e7b-5d0b7bed9a53\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"cdd629cf-45a3-5d12-bad3-79f6dae237ee\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605253,\"delay\":null}', 0, NULL, 1789605253, 1789605253),
(88, 'default', '{\"uuid\":\"ea987594-9887-4658-a585-c8ad9adc43e2\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"60b03e79-2092-529e-ae49-285729545f18\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605302,\"delay\":null}', 0, NULL, 1789605303, 1789605303),
(89, 'default', '{\"uuid\":\"dc7a47ec-0408-4e21-9cba-d46930c3f57e\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"8a045fae-7442-5539-bdd5-e769fdc008e1\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605317,\"delay\":null}', 0, NULL, 1789605317, 1789605317),
(90, 'default', '{\"uuid\":\"df21ae7d-d89d-4c3a-921d-05a978eaac50\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"1de8a4bf-23c1-5719-8f61-a43e644740d5\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605331,\"delay\":null}', 0, NULL, 1789605331, 1789605331),
(91, 'default', '{\"uuid\":\"e366cf9d-02c2-4af1-84dd-50637ead49b2\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"7f4647a8-fc59-5454-a0ff-44d04124c659\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605505,\"delay\":null}', 0, NULL, 1789605505, 1789605505),
(92, 'default', '{\"uuid\":\"b6b1892d-ff27-4652-be53-a59250a2f19a\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"ecc1ef0c-fd28-5ac7-8471-af55baf35da4\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605584,\"delay\":null}', 0, NULL, 1789605584, 1789605584),
(93, 'default', '{\"uuid\":\"01c2bcb9-56f6-49c8-9fe2-a5024c7fb020\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"6298ddb2-ea50-537a-92c4-fe64795056cd\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605591,\"delay\":null}', 0, NULL, 1789605591, 1789605591),
(94, 'default', '{\"uuid\":\"cbd7b9f1-8540-4bc7-9bad-e255545d9b05\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"8e290e88-19e7-5725-b5d3-e582c0eee992\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605602,\"delay\":null}', 0, NULL, 1789605602, 1789605602),
(95, 'default', '{\"uuid\":\"a18d8926-dc3a-4e18-b8ce-921cbc6cdde5\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"eaf56dfc-384f-5b9c-977c-112cbcb5cadb\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605608,\"delay\":null}', 0, NULL, 1789605608, 1789605608),
(96, 'default', '{\"uuid\":\"f4b19168-bd49-4bed-9d14-ee94a7be9dce\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"3c3b9709-b40c-5e0f-8942-a64e0db0a0b3\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605616,\"delay\":null}', 0, NULL, 1789605616, 1789605616),
(97, 'default', '{\"uuid\":\"8d27f481-a76f-4619-930d-fe1718809929\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"eb8e7c7b-7dcb-52df-bf48-aa3c9123c0bb\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605620,\"delay\":null}', 0, NULL, 1789605620, 1789605620),
(98, 'default', '{\"uuid\":\"fac4f746-686e-47de-9a1f-742de8e840c1\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"676a7d13-3fa7-58a3-b307-64de800c1a86\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605821,\"delay\":null}', 0, NULL, 1789605821, 1789605821),
(99, 'default', '{\"uuid\":\"dd448784-eeff-40a3-9ae5-5c594d9472af\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"b802513b-749b-5ed3-b9a9-ed1b55a76402\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605870,\"delay\":null}', 0, NULL, 1789605870, 1789605870),
(100, 'default', '{\"uuid\":\"a6458f91-fc45-498e-87f4-b78a6f635ac2\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"b77e388a-742e-50a6-b218-dbbec2a429ad\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605890,\"delay\":null}', 0, NULL, 1789605890, 1789605890),
(101, 'default', '{\"uuid\":\"1cb4f0a0-d81c-47bf-992f-687a3ac651f4\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:63;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"ee475058-7741-567b-9511-8e99fdc6b1a5\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605891,\"delay\":null}', 0, NULL, 1789605891, 1789605891),
(102, 'default', '{\"uuid\":\"c5040548-6304-469b-bfeb-71f21827c909\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"0d0e033a-6e98-5750-8102-28164c243006\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789605941,\"delay\":null}', 0, NULL, 1789605941, 1789605941),
(103, 'default', '{\"uuid\":\"f464132a-dbcc-4fb9-af55-2b7ecdd9a6a5\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"4a64bfda-4a91-5a2a-81b2-cfd6a28838cf\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789606122,\"delay\":null}', 0, NULL, 1789606122, 1789606122),
(104, 'default', '{\"uuid\":\"08dcf095-5e65-4e41-9e50-59200d6e1e03\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"d2e05d55-c5ef-5170-ad46-e49f60dd7439\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789606139,\"delay\":null}', 0, NULL, 1789606139, 1789606139),
(105, 'default', '{\"uuid\":\"cb46608b-6e14-489c-99f1-d4be649b5659\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"e78b5ad8-40f3-581f-a07e-176929583901\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789606145,\"delay\":null}', 0, NULL, 1789606145, 1789606145),
(106, 'default', '{\"uuid\":\"c8a0b779-2509-498a-825c-cf204e1334ba\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"6595c033-44ca-576d-a6b6-6cf46c9b7cb9\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789606238,\"delay\":null}', 0, NULL, 1789606238, 1789606238),
(107, 'default', '{\"uuid\":\"d3d244f9-4aed-4ba5-b5ea-b15fb4ffccc2\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"e0a71b19-3cc2-5f91-bb78-2002135c13c1\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789606267,\"delay\":null}', 0, NULL, 1789606267, 1789606267),
(108, 'default', '{\"uuid\":\"7e62e8b2-a624-4ff8-95c5-4cc70967fd40\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"dcd50d43-05c8-5038-811d-3bf8e24c9a9c\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789607099,\"delay\":null}', 0, NULL, 1789607099, 1789607099),
(109, 'default', '{\"uuid\":\"26149ee3-77ae-4b91-b2f7-a817171fadc5\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"f516e2dc-b3d9-5de3-ab1b-f4657d5e4287\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789607110,\"delay\":null}', 0, NULL, 1789607110, 1789607110),
(110, 'default', '{\"uuid\":\"82702f6e-b984-4105-91f1-c1ca8c798abd\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"5f4dd19d-2954-59ed-888b-b28280818ace\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789607134,\"delay\":null}', 0, NULL, 1789607134, 1789607134),
(111, 'default', '{\"uuid\":\"aaec8422-1ad3-4eb4-965c-ab5b090be59a\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"2c4f38b4-7387-5061-b800-7fa5adaf1c04\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789607135,\"delay\":null}', 0, NULL, 1789607135, 1789607135),
(112, 'default', '{\"uuid\":\"f54fec32-0a17-431b-af8a-9478f3067013\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"68c11d37-8d1a-545a-a0f9-dcd051032faa\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789607505,\"delay\":null}', 0, NULL, 1789607506, 1789607506),
(113, 'default', '{\"uuid\":\"072bd780-d85e-45aa-a08a-528044cb2e33\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"cd43ed20-8e43-577f-b02c-84f2ce7e9ee0\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789607534,\"delay\":null}', 0, NULL, 1789607534, 1789607534),
(114, 'default', '{\"uuid\":\"1a1e1280-8616-43da-b565-dda3070409f0\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"5066f26d-e266-5b33-b08f-a808ad8b4fdc\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789607583,\"delay\":null}', 0, NULL, 1789607583, 1789607583),
(115, 'default', '{\"uuid\":\"7c680365-e70c-45e1-9272-4f0f3651cebe\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"64be7ee1-a04a-59fc-9290-fbc7eb646686\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789607642,\"delay\":null}', 0, NULL, 1789607642, 1789607642),
(116, 'default', '{\"uuid\":\"78ebc1d3-a1c2-4eb8-ad67-420ea33bf07e\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"a630d678-8ab6-5a30-b7fe-09615b7f67d9\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789608435,\"delay\":null}', 0, NULL, 1789608435, 1789608435),
(117, 'default', '{\"uuid\":\"7795b60d-d3dc-4c22-8fa5-beee96d5765c\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"7a5c3e0f-9e66-5871-ad2d-0deb1a41b730\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789608450,\"delay\":null}', 0, NULL, 1789608450, 1789608450),
(118, 'default', '{\"uuid\":\"c3123c67-c199-4b1b-8a9b-ad7c72f2320f\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"f55cd1c5-2baa-5c41-9aa3-57d90ff631bf\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789608900,\"delay\":null}', 0, NULL, 1789608901, 1789608901),
(119, 'default', '{\"uuid\":\"f26faa86-e845-4f24-82c7-fdf94a2a563c\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"3e907ff2-1b12-5910-95a5-baf5ac971a73\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789610723,\"delay\":null}', 0, NULL, 1789610723, 1789610723),
(120, 'default', '{\"uuid\":\"b8bbb0e4-8668-4946-80b1-53635f2d96e5\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"04bc35ee-5c17-5684-be22-53eeb24cf425\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789610736,\"delay\":null}', 0, NULL, 1789610736, 1789610736),
(121, 'default', '{\"uuid\":\"faf92cd8-e34a-4e07-ace3-05500f9a23d3\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"e70f4f21-6c4b-5447-86ae-6b4c35dfb4a5\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789610740,\"delay\":null}', 0, NULL, 1789610740, 1789610740),
(122, 'default', '{\"uuid\":\"c8d3f827-28d9-4f42-abfc-83c4cb8c9716\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"f0f51e59-2842-5a66-8ff0-5dc6c678cb60\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789610750,\"delay\":null}', 0, NULL, 1789610750, 1789610750),
(123, 'default', '{\"uuid\":\"601e302d-a948-42eb-8a95-829f9aad432f\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"f949f441-78b7-53fb-abc7-d22bb6e22889\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789610759,\"delay\":null}', 0, NULL, 1789610759, 1789610759),
(124, 'default', '{\"uuid\":\"b27658aa-3b5d-43cf-928f-c8a9603c1f27\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"85b3389c-96e7-54ee-abc3-46e072b6e41f\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789613583,\"delay\":null}', 0, NULL, 1789613583, 1789613583),
(125, 'default', '{\"uuid\":\"b00c4436-8fd0-42b9-8398-1fddad75bf37\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"eaabbd24-460f-51a5-897d-1483fb22bc18\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620047,\"delay\":null}', 0, NULL, 1789620047, 1789620047),
(126, 'default', '{\"uuid\":\"81f4fc46-7589-4659-a3a7-123024ae8cee\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"c3722d82-9a3c-50a2-a5f6-a29b9f5683ee\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620047,\"delay\":null}', 0, NULL, 1789620047, 1789620047),
(127, 'default', '{\"uuid\":\"922e2444-e804-4dc8-baa6-5b28aaf77361\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"8b4f3b8e-39e5-513b-8e76-c82f61ef03a5\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620047,\"delay\":null}', 0, NULL, 1789620047, 1789620047),
(128, 'default', '{\"uuid\":\"4a737d5c-c5b9-47f8-8628-c83fda79a8b3\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"b0951944-8307-56bf-a181-d29bdc2bdf66\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620047,\"delay\":null}', 0, NULL, 1789620047, 1789620047),
(129, 'default', '{\"uuid\":\"db59d933-9914-41f8-9667-66b508c51b88\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"a4ab6d62-8392-54b4-8634-e74fa16a6031\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620047,\"delay\":null}', 0, NULL, 1789620047, 1789620047),
(130, 'default', '{\"uuid\":\"49a5383e-6468-4833-9770-cd8453e78674\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"29ba5dc2-a2a1-5826-8b19-4888b26e6b5c\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620047,\"delay\":null}', 0, NULL, 1789620047, 1789620047),
(131, 'default', '{\"uuid\":\"254b8089-69e5-4284-af19-e48278e2d395\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"7684cd66-d690-5ef7-ab40-27ad29bf634f\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620047,\"delay\":null}', 0, NULL, 1789620047, 1789620047),
(132, 'default', '{\"uuid\":\"2ee4cea3-0e17-4fb4-9838-dc8ef757bd24\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"311ea988-535a-5477-87e8-4c1c4ceadeb8\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620047,\"delay\":null}', 0, NULL, 1789620047, 1789620047),
(133, 'default', '{\"uuid\":\"16a55a64-42e9-44da-bc46-d5d0c998a1fc\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"dc60e159-abba-5b29-b237-f19bda411100\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620047,\"delay\":null}', 0, NULL, 1789620047, 1789620047),
(134, 'default', '{\"uuid\":\"6907f7e6-752b-4374-ab2f-cca2ee7206ef\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"4af61eb6-e5bd-56ae-9f9a-44dc19711ab2\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620047,\"delay\":null}', 0, NULL, 1789620047, 1789620047),
(135, 'default', '{\"uuid\":\"e78da1a7-ca16-4ab1-8436-38cd002915c4\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"f883f36e-cec4-5cca-876b-f04e05afe5d0\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620047,\"delay\":null}', 0, NULL, 1789620047, 1789620047),
(136, 'default', '{\"uuid\":\"aa1ba747-f17f-478a-a76a-1885c6f4076c\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"7db8b720-8d17-5471-aeda-3ea38d86a2bd\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620047,\"delay\":null}', 0, NULL, 1789620047, 1789620047),
(137, 'default', '{\"uuid\":\"6407049a-cf7b-4baa-94c7-a60982b5d10c\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"4c19de77-4b4d-53ee-a8df-89ec42eb4cf4\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620047,\"delay\":null}', 0, NULL, 1789620047, 1789620047),
(138, 'default', '{\"uuid\":\"f40af46c-bf29-4c4e-a0ec-eb0563ade24f\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"1314cb05-4e5c-58d7-b16c-7b1ca6239240\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(139, 'default', '{\"uuid\":\"c22caa6f-dbd1-4be9-8229-667f84586532\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"6d7d0a24-d06e-5c43-b8f0-7e8eb02a34e0\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(140, 'default', '{\"uuid\":\"7ae40de9-bcd3-4a03-ba58-17f729b37083\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"8ce99563-2ff4-53c0-9ec6-29aadf930dc4\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(141, 'default', '{\"uuid\":\"f9f822e9-8d44-4a13-8a36-3dd09eb947c8\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"dcf238a1-e679-5964-bafe-c30eed16dca6\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(142, 'default', '{\"uuid\":\"a83de0a4-66e2-46e9-8e24-09359fc5ee11\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"07f2cf48-d771-5992-8495-6f41c2d06b53\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(143, 'default', '{\"uuid\":\"73291133-0385-48a2-b532-ce2fdb6f2906\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"1485da63-f70f-5dc9-a3d2-ea5b91c756cf\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(144, 'default', '{\"uuid\":\"31f6f377-6323-4ea3-a825-e72d6077e5ec\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"637a9434-e21f-523b-9e72-1cba4c04a5b4\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(145, 'default', '{\"uuid\":\"5ac5c8a9-5e41-482e-8528-3f7d8bd981ab\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"b603b1e3-452e-59fb-9576-3f72ef0c361f\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(146, 'default', '{\"uuid\":\"313ee7b8-551a-4609-8867-a674474e3426\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"3357c497-a735-5fe2-b2f5-48b8ef80854c\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(147, 'default', '{\"uuid\":\"c3ef4216-779d-480d-bffa-326c2f9bde03\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"60484900-d1ba-59af-80c9-1f506c9241fd\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(148, 'default', '{\"uuid\":\"e64e4145-878b-41bc-b0b5-f3ea79740719\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"e52fdafb-ea0e-5287-a560-4360b25e8eb3\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(149, 'default', '{\"uuid\":\"cad0169e-6011-4cc5-9338-46f06e4da549\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"b5369c37-d788-51bc-8d70-5f234a9fa5ed\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241);
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(150, 'default', '{\"uuid\":\"c1d19232-9d5b-4e0a-bba7-a77f09d76fe6\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"5aa5706d-2fef-5259-a119-d5eda64a4c33\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(151, 'default', '{\"uuid\":\"d83f149f-b057-49e3-a95f-7012305f1a84\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"3ac4dfaf-d0d6-57fc-bd0c-e5a1e0240661\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(152, 'default', '{\"uuid\":\"bc142462-5792-4b78-917c-13e499523bdf\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"8ebcf22d-d4a9-572b-bc13-8562bd78d95f\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(153, 'default', '{\"uuid\":\"c96a22d9-7e16-435f-88c9-23a2fca58338\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"5bd71119-9189-5bad-b663-dfcedc2df7ed\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(154, 'default', '{\"uuid\":\"fef11640-a6ea-4415-9823-0add05972c6a\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"55c88cb8-a370-5b9b-b8dc-dba741ddf0e9\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(155, 'default', '{\"uuid\":\"28a07145-e67e-4439-83a3-cfa77ae310d9\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"9fa655fd-651c-5d9f-9c66-bee507c4d297\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(156, 'default', '{\"uuid\":\"9489d25a-0f3f-4748-857a-c410e4646336\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"c2229ffb-da8c-5037-b7ee-9ce2638c24c9\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(157, 'default', '{\"uuid\":\"e4852e91-51e8-4726-ba63-e267a56fb40c\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"66862fc2-2e04-5853-97a8-5874494e253b\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(158, 'default', '{\"uuid\":\"11804241-6099-4d34-a15f-54f591e433ac\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"6a841f06-445d-577f-8166-118f8343e5d1\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(159, 'default', '{\"uuid\":\"ad50b0a6-15c9-47b7-9df4-bbfaf7982796\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"a985f286-bc16-54e5-b470-d44d65815b4d\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(160, 'default', '{\"uuid\":\"2f6b8bb6-dd1f-4195-b7d6-66f083999ca8\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"076cbfdd-9398-5656-94f5-590c23627d3f\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(161, 'default', '{\"uuid\":\"ce81f24c-80c1-4dc1-8bcb-51b4e8e63ff0\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"098ae8d6-d911-57c1-9313-b30dbe1037c0\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(162, 'default', '{\"uuid\":\"c9da144e-364e-4107-9430-9aee36028304\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"ccd332a7-e92c-5cdf-8e3e-63457cfa8db0\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789620241,\"delay\":null}', 0, NULL, 1789620241, 1789620241),
(163, 'default', '{\"uuid\":\"ea9e2258-3cf1-476c-a3e9-7df0d6db4aed\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"336e6864-a6b8-541f-92a1-29492b52d56a\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789622360,\"delay\":null}', 0, NULL, 1789622360, 1789622360),
(164, 'default', '{\"uuid\":\"e49a0007-cfc1-45e8-886c-c2f36dadb0e6\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"a06445c9-cce0-5834-aea6-6679e42d51f0\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789622360,\"delay\":null}', 0, NULL, 1789622360, 1789622360),
(165, 'default', '{\"uuid\":\"50577620-014a-45a0-8c8a-0a1e26e361f2\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"1378f70a-6f7d-59ee-a131-d75da1eec0f9\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789622360,\"delay\":null}', 0, NULL, 1789622360, 1789622360),
(166, 'default', '{\"uuid\":\"8e113853-3fff-459b-b051-cfe3abe0964d\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"455d28dd-4e0b-5c1a-abd7-25bbcbe2c66f\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789622360,\"delay\":null}', 0, NULL, 1789622360, 1789622360),
(167, 'default', '{\"uuid\":\"0b32024e-8a5a-4443-91db-7efc4dc440de\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"f4b0e2b8-777d-5521-8347-b1a1c249dfbe\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789622360,\"delay\":null}', 0, NULL, 1789622360, 1789622360),
(168, 'default', '{\"uuid\":\"6a86bf43-a2e8-4fb9-a1e9-23a466e3b105\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"1f0df97c-f234-5b6e-96ec-5d3e51799d98\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789622360,\"delay\":null}', 0, NULL, 1789622360, 1789622360),
(169, 'default', '{\"uuid\":\"22ae0392-0ac4-4b08-a4fc-4f87f37edc06\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"c48309ee-3f38-5127-8634-a0f3177bf526\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789622360,\"delay\":null}', 0, NULL, 1789622360, 1789622360),
(170, 'default', '{\"uuid\":\"4dc3aef4-32a8-422b-9871-df1bb0fd0399\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"214dd996-6d2b-5f90-b12a-2490f20f3000\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789622360,\"delay\":null}', 0, NULL, 1789622360, 1789622360),
(171, 'default', '{\"uuid\":\"054954c1-e10f-417d-8d60-6e656461fa7b\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"23a3ad31-0e2b-5f48-bb0c-611bfa3ff364\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789622360,\"delay\":null}', 0, NULL, 1789622360, 1789622360),
(172, 'default', '{\"uuid\":\"88727f39-f0a4-4b4d-a469-86d73a6a2b85\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"f824d03e-62c5-50ef-ad4c-d84152551275\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789622360,\"delay\":null}', 0, NULL, 1789622360, 1789622360),
(173, 'default', '{\"uuid\":\"b4bd3111-c68b-4da8-aff8-4e06b54ce435\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"cf3727fe-37c0-519b-9054-8eec28fb93a4\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789622360,\"delay\":null}', 0, NULL, 1789622360, 1789622360),
(174, 'default', '{\"uuid\":\"4a7f3ed4-b2f5-4ed0-a9fb-260cb6171386\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"2daa8654-6528-5bdf-a971-9404e3b1a452\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789626071,\"delay\":null}', 0, NULL, 1789626071, 1789626071),
(175, 'default', '{\"uuid\":\"f38068e9-5d40-44d4-b577-422ff0d9127b\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"0e1338f7-c4bb-5a85-b372-ae1b8ade4df4\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789626093,\"delay\":null}', 0, NULL, 1789626093, 1789626093),
(176, 'default', '{\"uuid\":\"91d6b3cb-fb70-47ff-aa4e-af426323067e\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"c43d8741-18e1-54d7-8c3d-c12829fc54d4\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789626100,\"delay\":null}', 0, NULL, 1789626100, 1789626100),
(177, 'default', '{\"uuid\":\"3d6b4209-7599-4a7c-afd9-06bd29244396\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"8dbcc16b-9500-54a1-bba8-c618bafe60b2\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789626106,\"delay\":null}', 0, NULL, 1789626106, 1789626106),
(178, 'default', '{\"uuid\":\"d7e9c4de-84b6-4f44-9aea-90421fddb67c\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"175b3651-f88e-57c1-92fe-0fee139a4e21\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789626112,\"delay\":null}', 0, NULL, 1789626113, 1789626113),
(179, 'default', '{\"uuid\":\"17a4a163-2d75-442d-a86f-457e02b261d9\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"9ff04beb-8ccf-5764-93a3-f0fb39673965\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789626118,\"delay\":null}', 0, NULL, 1789626119, 1789626119),
(180, 'default', '{\"uuid\":\"751ae68e-2f0b-470e-b51e-424b2e829ead\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"a14ee76b-6289-5a9e-be9a-28a4c0f0b077\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789626130,\"delay\":null}', 0, NULL, 1789626130, 1789626130),
(181, 'default', '{\"uuid\":\"847249a2-68b0-4852-b677-dae08aa7113c\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"eca97896-7579-5718-9688-7d2f3432a4d0\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789626141,\"delay\":null}', 0, NULL, 1789626141, 1789626141),
(182, 'default', '{\"uuid\":\"6b59f97f-3218-4cb9-a228-b67848d05691\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"bfd8f289-448b-5888-b9ef-985c7162cee4\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789627969,\"delay\":null}', 0, NULL, 1789627969, 1789627969),
(183, 'default', '{\"uuid\":\"d7708fee-d1a9-4169-8d10-b21a212ce8c1\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"a77881d8-bf09-57c8-96a1-28b16854ed6e\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789627995,\"delay\":null}', 0, NULL, 1789627995, 1789627995),
(184, 'default', '{\"uuid\":\"deb7d0c8-51d6-417a-8b49-799310fd6d39\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"74d82cbb-9dba-515c-a248-cef8f1ddd69e\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789628002,\"delay\":null}', 0, NULL, 1789628002, 1789628002),
(185, 'default', '{\"uuid\":\"cd711b2f-428d-47f6-8368-ed8a6e31ccb3\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"df1d5963-711a-54f6-b7ed-4b22361453ee\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789628008,\"delay\":null}', 0, NULL, 1789628008, 1789628008),
(186, 'default', '{\"uuid\":\"a846abc3-89b5-4618-bd93-5cb9a6de4b42\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"ce0c31f0-7070-5121-96ab-81099910dae0\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789628014,\"delay\":null}', 0, NULL, 1789628014, 1789628014),
(187, 'default', '{\"uuid\":\"7f152817-d336-4fc7-9d39-75d220af443f\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"aa1548fe-9c2e-564d-a866-cd4a07abe081\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789628045,\"delay\":null}', 0, NULL, 1789628045, 1789628045),
(188, 'default', '{\"uuid\":\"ff6fa4cd-2e96-4190-8778-ec7cec266ba3\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"15f662fc-d4b7-5638-abb0-855f60e853c9\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789628047,\"delay\":null}', 0, NULL, 1789628047, 1789628047),
(189, 'default', '{\"uuid\":\"bcd1fcfa-59bc-4657-b0a4-fb69d8484ca1\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"cdc7a7e1-dee1-5585-b053-3dfcd0218c98\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789628202,\"delay\":null}', 0, NULL, 1789628203, 1789628203),
(190, 'default', '{\"uuid\":\"63687ec2-f98b-47d4-8fbc-bed562559dd8\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"e62a3b92-ab2e-52a0-9c7d-371acb2f8ed5\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789628283,\"delay\":null}', 0, NULL, 1789628283, 1789628283),
(191, 'default', '{\"uuid\":\"6575081d-4c64-4407-a456-fb0da1f42178\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"dec1f76c-82e3-5769-85e6-fbe2b9a75450\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789628306,\"delay\":null}', 0, NULL, 1789628306, 1789628306),
(192, 'default', '{\"uuid\":\"5b49499f-a026-4fe6-9783-60d52cd976ff\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"cf17553e-8023-5218-8268-20924121a400\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789628312,\"delay\":null}', 0, NULL, 1789628312, 1789628312),
(193, 'default', '{\"uuid\":\"d9d67a45-af4c-4a67-9feb-13a8b7a7474a\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"c5bfc08d-37d5-5f93-8c7e-c76c7e02e1b8\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789628318,\"delay\":null}', 0, NULL, 1789628318, 1789628318),
(194, 'default', '{\"uuid\":\"cd17ed39-f7c3-440a-886e-8704b4572d84\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"b41a25c4-8f93-5e26-b50b-853c5c39ec88\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789628324,\"delay\":null}', 0, NULL, 1789628324, 1789628324),
(195, 'default', '{\"uuid\":\"cb6bd0e5-f124-4d40-9015-8aad3b61c81c\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"356eb041-75de-5cde-8a5e-8313eb895cfa\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789628330,\"delay\":null}', 0, NULL, 1789628330, 1789628330),
(196, 'default', '{\"uuid\":\"70f36a37-498c-460a-923a-09e74cf3449d\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"25d66027-60e6-54f1-a8ef-e52285d6ea14\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789628331,\"delay\":null}', 0, NULL, 1789628331, 1789628331);

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
(1, '0000_12_31_235959_create_dealers_table', 1),
(2, '0001_01_01_000000_create_users_table', 1),
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(5, '2026_09_15_000001_create_property_requests_table', 1),
(6, '2026_09_15_000002_create_request_attachments_table', 1),
(7, '2026_09_15_000003_create_audit_logs_table', 1),
(8, '2026_09_15_000004_add_pm_manager_role_to_users_table', 1),
(9, '2026_09_15_000005_add_request_workflow_fields', 1),
(10, '2026_09_15_000006_normalize_request_presets', 1),
(11, '2026_09_15_000007_add_representative_role_to_users_table', 1),
(12, '2026_09_15_000008_update_roles_for_dial_lead_and_handyman', 2),
(13, '2026_09_15_000009_add_dial_a_role_to_users_table', 3),
(14, '2026_09_15_000010_add_city_to_dealers_and_dealer_name_to_requests', 4),
(15, '2026_09_15_000011_add_work_order_details_to_property_requests', 5),
(16, '2026_09_15_000012_create_request_notifications', 6),
(17, '2026_09_16_000001_add_account_management_fields_to_users_table', 7),
(18, '2026_09_16_000002_rename_service_order_to_service_report_in_audit_logs', 8),
(19, '2026_09_16_000003_add_stage_timing_fields_to_property_requests', 9),
(20, '2026_09_16_133626_add_inspection_end_date_to_property_requests', 10),
(21, '2026_09_16_135758_add_work_order_times_to_property_requests', 11),
(22, '2026_09_16_220400_add_on_going_status_to_property_requests', 12);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `property_request_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `property_request_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('05e52a31-3d24-51b3-94b5-15f49848acc9', 'request_workflow', 'App\\Models\\User', 12, 197, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0003: Service Report: Not scheduled \\u2192 Sep 17, 2026.\"}', '2026-09-18 00:15:33', '2026-09-17 06:58:50', '2026-09-18 00:15:33'),
('07ae54f6-f279-539e-af27-d40e80ebbd92', 'request_workflow', 'App\\Models\\User', 89, 197, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0003: Inspection: Not scheduled \\u2192 Sep 16, 2026.\"}', NULL, '2026-09-17 06:58:26', '2026-09-17 06:58:26'),
('0a6ecd7e-e026-53e3-8b5e-a03f052b0d6b', 'request_workflow', 'App\\Models\\User', 89, 196, '{\"kind\":\"stage_completed\",\"title\":\"Service Report completed\",\"message\":\"GPM-20260917-0002: Service Report completed.\",\"stage\":\"service_report\",\"request_completed\":false}', NULL, '2026-09-17 06:54:07', '2026-09-17 06:54:07'),
('0e1338f7-c4bb-5a85-b372-ae1b8ade4df4', 'request_workflow', 'App\\Models\\User', 11, 195, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0001: Inspection: Not scheduled \\u2192 Sep 16, 2026.\"}', NULL, '2026-09-17 06:21:33', '2026-09-17 06:21:33'),
('0f458c48-fe45-59b7-a12c-9518c055c639', 'request_workflow', 'App\\Models\\User', 89, 196, '{\"kind\":\"stage_completed\",\"title\":\"Work completed\",\"message\":\"GPM-20260917-0002: Work completed.\",\"stage\":\"work_order\",\"request_completed\":false}', NULL, '2026-09-17 06:53:34', '2026-09-17 06:53:34'),
('15f662fc-d4b7-5638-abb0-855f60e853c9', 'request_workflow', 'App\\Models\\User', 11, 196, '{\"kind\":\"stage_completed\",\"title\":\"Service Report completed\",\"message\":\"GPM-20260917-0002: Service Report completed.\",\"stage\":\"service_report\",\"request_completed\":false}', NULL, '2026-09-17 06:54:07', '2026-09-17 06:54:07'),
('175b3651-f88e-57c1-92fe-0fee139a4e21', 'request_workflow', 'App\\Models\\User', 11, 195, '{\"kind\":\"stage_completed\",\"title\":\"Work completed\",\"message\":\"GPM-20260917-0001: Work completed.\",\"stage\":\"work_order\",\"request_completed\":false}', NULL, '2026-09-17 06:21:52', '2026-09-17 06:21:52'),
('19b4e25f-8963-5cbc-aa67-b8b05776bd62', 'request_workflow', 'App\\Models\\User', 89, 196, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0002: Inspection: Not scheduled \\u2192 Sep 16, 2026.\"}', NULL, '2026-09-17 06:53:15', '2026-09-17 06:53:15'),
('1eeb23ce-a376-5f91-8c10-72ce5f197088', 'request_workflow', 'App\\Models\\User', 12, 197, '{\"kind\":\"stage_completed\",\"title\":\"Work completed\",\"message\":\"GPM-20260917-0003: Work completed.\",\"stage\":\"work_order\",\"request_completed\":false}', '2026-09-18 00:15:33', '2026-09-17 06:58:44', '2026-09-18 00:15:33'),
('25d66027-60e6-54f1-a8ef-e52285d6ea14', 'request_workflow', 'App\\Models\\User', 11, 197, '{\"kind\":\"stage_completed\",\"title\":\"Service Report completed\",\"message\":\"GPM-20260917-0003: Service Report completed.\",\"stage\":\"service_report\",\"request_completed\":false}', NULL, '2026-09-17 06:58:51', '2026-09-17 06:58:51'),
('2daa8654-6528-5bdf-a971-9404e3b1a452', 'request_workflow', 'App\\Models\\User', 88, 195, '{\"kind\":\"new_request\",\"title\":\"New request\",\"message\":\"GPM-20260917-0001: General Repairs requested by Renie Laranga (Pasig).\"}', '2026-09-17 06:21:24', '2026-09-17 06:21:11', '2026-09-17 06:21:24'),
('356eb041-75de-5cde-8a5e-8313eb895cfa', 'request_workflow', 'App\\Models\\User', 11, 197, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0003: Service Report: Not scheduled \\u2192 Sep 17, 2026.\"}', NULL, '2026-09-17 06:58:50', '2026-09-17 06:58:50'),
('359de625-1a06-5d55-a3d3-7ce3656940dc', 'request_workflow', 'App\\Models\\User', 12, 195, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0001: Work: Not scheduled \\u2192 Sep 16, 2026.\"}', '2026-09-17 06:52:37', '2026-09-17 06:21:46', '2026-09-17 06:52:37'),
('4dd70ef0-658b-53ca-b82c-5cf013959fff', 'request_workflow', 'App\\Models\\User', 89, 197, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0003: Service Report: Not scheduled \\u2192 Sep 17, 2026.\"}', NULL, '2026-09-17 06:58:50', '2026-09-17 06:58:50'),
('4f21ba50-c0b8-53d1-a110-41cf86215609', 'request_workflow', 'App\\Models\\User', 89, 197, '{\"kind\":\"stage_completed\",\"title\":\"Work completed\",\"message\":\"GPM-20260917-0003: Work completed.\",\"stage\":\"work_order\",\"request_completed\":false}', NULL, '2026-09-17 06:58:44', '2026-09-17 06:58:44'),
('4f978d0e-c655-546d-b65f-4155ac7e6c52', 'request_workflow', 'App\\Models\\User', 89, 195, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0001: Service Report: Not scheduled \\u2192 Sep 17, 2026.\"}', NULL, '2026-09-17 06:21:58', '2026-09-17 06:21:58'),
('56f92901-5eb6-529b-8066-70e39d52911c', 'request_workflow', 'App\\Models\\User', 12, 197, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0003: Work: Not scheduled \\u2192 Sep 16, 2026.\"}', '2026-09-18 00:15:33', '2026-09-17 06:58:38', '2026-09-18 00:15:33'),
('5e0605f2-4a56-5c3d-951b-2590888d8cc1', 'request_workflow', 'App\\Models\\User', 12, 195, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0001: Service Report: Not scheduled \\u2192 Sep 17, 2026.\"}', '2026-09-17 06:52:37', '2026-09-17 06:21:58', '2026-09-17 06:52:37'),
('62efe226-c82e-5f9d-8370-5683fce141b7', 'request_workflow', 'App\\Models\\User', 89, 195, '{\"kind\":\"stage_completed\",\"title\":\"Service Report completed\",\"message\":\"GPM-20260917-0001: Service Report completed. All stages are complete and the request is now completed.\",\"stage\":\"service_report\",\"request_completed\":true}', NULL, '2026-09-17 06:22:21', '2026-09-17 06:22:21'),
('65df1516-234c-54a1-a90a-6ffb4aef3819', 'request_workflow', 'App\\Models\\User', 89, 195, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0001: Work: Not scheduled \\u2192 Sep 16, 2026.\"}', NULL, '2026-09-17 06:21:46', '2026-09-17 06:21:46'),
('68119521-2639-564b-9708-3a9ebad5db28', 'request_workflow', 'App\\Models\\User', 12, 196, '{\"kind\":\"request_completed\",\"title\":\"Request completed\",\"message\":\"GPM-20260917-0002: All stages are complete and Dial-A has confirmed completion.\"}', '2026-09-18 00:15:33', '2026-09-17 06:56:43', '2026-09-18 00:15:33'),
('6dc1061b-abe8-5488-9b6f-ab034d35e2c2', 'request_workflow', 'App\\Models\\User', 89, 195, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0001: Inspection: Not scheduled \\u2192 Sep 16, 2026.\"}', NULL, '2026-09-17 06:21:33', '2026-09-17 06:21:33'),
('6e1513d3-369e-5011-b733-02e618b93994', 'request_workflow', 'App\\Models\\User', 12, 196, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0002: Service Report: Not scheduled \\u2192 Sep 17, 2026.\"}', '2026-09-18 00:15:33', '2026-09-17 06:54:05', '2026-09-18 00:15:33'),
('72bc8bc2-f4c0-50d6-967b-3d7c38645ca3', 'request_workflow', 'App\\Models\\User', 12, 196, '{\"kind\":\"stage_completed\",\"title\":\"Work completed\",\"message\":\"GPM-20260917-0002: Work completed.\",\"stage\":\"work_order\",\"request_completed\":false}', '2026-09-18 00:15:33', '2026-09-17 06:53:34', '2026-09-18 00:15:33'),
('72bd963a-b2c3-58a8-b2cb-7e2449d443a5', 'request_workflow', 'App\\Models\\User', 12, 195, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0001: Inspection: Not scheduled \\u2192 Sep 16, 2026.\"}', '2026-09-17 06:52:37', '2026-09-17 06:21:33', '2026-09-17 06:52:37'),
('74cecec3-ee25-5a69-b452-00a4fada934d', 'request_workflow', 'App\\Models\\User', 12, 197, '{\"kind\":\"stage_completed\",\"title\":\"Inspection completed\",\"message\":\"GPM-20260917-0003: Inspection completed.\",\"stage\":\"inspection\",\"request_completed\":false}', '2026-09-18 00:15:33', '2026-09-17 06:58:32', '2026-09-18 00:15:33'),
('74d82cbb-9dba-515c-a248-cef8f1ddd69e', 'request_workflow', 'App\\Models\\User', 11, 196, '{\"kind\":\"stage_completed\",\"title\":\"Inspection completed\",\"message\":\"GPM-20260917-0002: Inspection completed.\",\"stage\":\"inspection\",\"request_completed\":false}', NULL, '2026-09-17 06:53:22', '2026-09-17 06:53:22'),
('808183c5-cc77-5c63-afd0-674f4323620a', 'request_workflow', 'App\\Models\\User', 12, 196, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0002: Inspection: Not scheduled \\u2192 Sep 16, 2026.\"}', '2026-09-18 00:15:33', '2026-09-17 06:53:15', '2026-09-18 00:15:33'),
('8317adbf-88ae-5c35-9e7c-987304d90056', 'request_workflow', 'App\\Models\\User', 12, 196, '{\"kind\":\"stage_completed\",\"title\":\"Service Report completed\",\"message\":\"GPM-20260917-0002: Service Report completed.\",\"stage\":\"service_report\",\"request_completed\":false}', '2026-09-18 00:15:33', '2026-09-17 06:54:07', '2026-09-18 00:15:33'),
('8c260fed-6952-5cda-88b1-23ccdbf21f79', 'request_workflow', 'App\\Models\\User', 89, 197, '{\"kind\":\"stage_completed\",\"title\":\"Inspection completed\",\"message\":\"GPM-20260917-0003: Inspection completed.\",\"stage\":\"inspection\",\"request_completed\":false}', NULL, '2026-09-17 06:58:32', '2026-09-17 06:58:32'),
('8dbcc16b-9500-54a1-bba8-c618bafe60b2', 'request_workflow', 'App\\Models\\User', 11, 195, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0001: Work: Not scheduled \\u2192 Sep 16, 2026.\"}', NULL, '2026-09-17 06:21:46', '2026-09-17 06:21:46'),
('9b5ad8a2-980a-52e6-b462-0db7af021d3e', 'request_workflow', 'App\\Models\\User', 12, 196, '{\"kind\":\"stage_completed\",\"title\":\"Inspection completed\",\"message\":\"GPM-20260917-0002: Inspection completed.\",\"stage\":\"inspection\",\"request_completed\":false}', '2026-09-18 00:15:33', '2026-09-17 06:53:22', '2026-09-18 00:15:33'),
('9ff04beb-8ccf-5764-93a3-f0fb39673965', 'request_workflow', 'App\\Models\\User', 11, 195, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0001: Service Report: Not scheduled \\u2192 Sep 17, 2026.\"}', NULL, '2026-09-17 06:21:58', '2026-09-17 06:21:58'),
('a14ee76b-6289-5a9e-be9a-28a4c0f0b077', 'request_workflow', 'App\\Models\\User', 88, 195, '{\"kind\":\"upcoming\",\"title\":\"Upcoming Service Report\",\"message\":\"GPM-20260917-0001: Service Report is due Sep 17, 2026.\"}', '2026-09-17 06:22:21', '2026-09-17 06:22:10', '2026-09-17 06:22:21'),
('a77881d8-bf09-57c8-96a1-28b16854ed6e', 'request_workflow', 'App\\Models\\User', 11, 196, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0002: Inspection: Not scheduled \\u2192 Sep 16, 2026.\"}', NULL, '2026-09-17 06:53:15', '2026-09-17 06:53:15'),
('aa1548fe-9c2e-564d-a866-cd4a07abe081', 'request_workflow', 'App\\Models\\User', 11, 196, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0002: Service Report: Not scheduled \\u2192 Sep 17, 2026.\"}', NULL, '2026-09-17 06:54:05', '2026-09-17 06:54:05'),
('ae631ca0-fce5-54aa-81f1-a94345cbac2d', 'request_workflow', 'App\\Models\\User', 89, 195, '{\"kind\":\"stage_completed\",\"title\":\"Work completed\",\"message\":\"GPM-20260917-0001: Work completed.\",\"stage\":\"work_order\",\"request_completed\":false}', NULL, '2026-09-17 06:21:53', '2026-09-17 06:21:53'),
('af8d90fc-3d5c-51e5-ba39-4f707e986085', 'request_workflow', 'App\\Models\\User', 89, 197, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0003: Work: Not scheduled \\u2192 Sep 16, 2026.\"}', NULL, '2026-09-17 06:58:38', '2026-09-17 06:58:38'),
('b0141bbd-9cb5-53c3-bfbc-e8d61cb3b328', 'request_workflow', 'App\\Models\\User', 89, 196, '{\"kind\":\"request_completed\",\"title\":\"Request completed\",\"message\":\"GPM-20260917-0002: All stages are complete and Dial-A has confirmed completion.\"}', NULL, '2026-09-17 06:56:43', '2026-09-17 06:56:43'),
('b1f6de22-2aae-538c-bb22-d19bc767b8bf', 'request_workflow', 'App\\Models\\User', 89, 196, '{\"kind\":\"stage_completed\",\"title\":\"Inspection completed\",\"message\":\"GPM-20260917-0002: Inspection completed.\",\"stage\":\"inspection\",\"request_completed\":false}', NULL, '2026-09-17 06:53:22', '2026-09-17 06:53:22'),
('b3987a56-2591-5c59-b600-d4db8094acba', 'request_workflow', 'App\\Models\\User', 12, 195, '{\"kind\":\"stage_completed\",\"title\":\"Inspection completed\",\"message\":\"GPM-20260917-0001: Inspection completed.\",\"stage\":\"inspection\",\"request_completed\":false}', '2026-09-17 06:52:37', '2026-09-17 06:21:40', '2026-09-17 06:52:37'),
('b41a25c4-8f93-5e26-b50b-853c5c39ec88', 'request_workflow', 'App\\Models\\User', 11, 197, '{\"kind\":\"stage_completed\",\"title\":\"Work completed\",\"message\":\"GPM-20260917-0003: Work completed.\",\"stage\":\"work_order\",\"request_completed\":false}', NULL, '2026-09-17 06:58:44', '2026-09-17 06:58:44'),
('bfd8f289-448b-5888-b9ef-985c7162cee4', 'request_workflow', 'App\\Models\\User', 88, 196, '{\"kind\":\"new_request\",\"title\":\"New request\",\"message\":\"GPM-20260917-0002: General Repairs requested by Renie Laranga (Pasig).\"}', '2026-09-17 06:53:05', '2026-09-17 06:52:49', '2026-09-17 06:53:05'),
('c43d8741-18e1-54d7-8c3d-c12829fc54d4', 'request_workflow', 'App\\Models\\User', 11, 195, '{\"kind\":\"stage_completed\",\"title\":\"Inspection completed\",\"message\":\"GPM-20260917-0001: Inspection completed.\",\"stage\":\"inspection\",\"request_completed\":false}', NULL, '2026-09-17 06:21:40', '2026-09-17 06:21:40'),
('c5bfc08d-37d5-5f93-8c7e-c76c7e02e1b8', 'request_workflow', 'App\\Models\\User', 11, 197, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0003: Work: Not scheduled \\u2192 Sep 16, 2026.\"}', NULL, '2026-09-17 06:58:38', '2026-09-17 06:58:38'),
('c80d7af5-2599-50ef-baf7-6edf34229005', 'request_workflow', 'App\\Models\\User', 89, 196, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0002: Work: Not scheduled \\u2192 Sep 16, 2026.\"}', NULL, '2026-09-17 06:53:28', '2026-09-17 06:53:28'),
('cab2a43f-ce0e-5845-9886-836d3a839d19', 'request_workflow', 'App\\Models\\User', 89, 196, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0002: Service Report: Not scheduled \\u2192 Sep 17, 2026.\"}', NULL, '2026-09-17 06:54:05', '2026-09-17 06:54:05'),
('cbe3d6fa-4d01-5b59-b8bf-c759c37c1104', 'request_workflow', 'App\\Models\\User', 89, 195, '{\"kind\":\"stage_completed\",\"title\":\"Inspection completed\",\"message\":\"GPM-20260917-0001: Inspection completed.\",\"stage\":\"inspection\",\"request_completed\":false}', NULL, '2026-09-17 06:21:40', '2026-09-17 06:21:40'),
('cdc7a7e1-dee1-5585-b053-3dfcd0218c98', 'request_workflow', 'App\\Models\\User', 11, 196, '{\"kind\":\"request_completed\",\"title\":\"Request completed\",\"message\":\"GPM-20260917-0002: All stages are complete and Dial-A has confirmed completion.\"}', NULL, '2026-09-17 06:56:42', '2026-09-17 06:56:42'),
('cdf80f5c-a751-5547-ab99-515b7160d9ae', 'request_workflow', 'App\\Models\\User', 12, 196, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0002: Work: Not scheduled \\u2192 Sep 16, 2026.\"}', '2026-09-18 00:15:33', '2026-09-17 06:53:28', '2026-09-18 00:15:33'),
('ce0c31f0-7070-5121-96ab-81099910dae0', 'request_workflow', 'App\\Models\\User', 11, 196, '{\"kind\":\"stage_completed\",\"title\":\"Work completed\",\"message\":\"GPM-20260917-0002: Work completed.\",\"stage\":\"work_order\",\"request_completed\":false}', NULL, '2026-09-17 06:53:34', '2026-09-17 06:53:34'),
('cf17553e-8023-5218-8268-20924121a400', 'request_workflow', 'App\\Models\\User', 11, 197, '{\"kind\":\"stage_completed\",\"title\":\"Inspection completed\",\"message\":\"GPM-20260917-0003: Inspection completed.\",\"stage\":\"inspection\",\"request_completed\":false}', NULL, '2026-09-17 06:58:32', '2026-09-17 06:58:32'),
('deb56be1-f62c-5acb-bebf-f052dddd37fa', 'request_workflow', 'App\\Models\\User', 89, 197, '{\"kind\":\"stage_completed\",\"title\":\"Service Report completed\",\"message\":\"GPM-20260917-0003: Service Report completed.\",\"stage\":\"service_report\",\"request_completed\":false}', NULL, '2026-09-17 06:58:51', '2026-09-17 06:58:51'),
('dec1f76c-82e3-5769-85e6-fbe2b9a75450', 'request_workflow', 'App\\Models\\User', 11, 197, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0003: Inspection: Not scheduled \\u2192 Sep 16, 2026.\"}', NULL, '2026-09-17 06:58:26', '2026-09-17 06:58:26'),
('df1d5963-711a-54f6-b7ed-4b22361453ee', 'request_workflow', 'App\\Models\\User', 11, 196, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0002: Work: Not scheduled \\u2192 Sep 16, 2026.\"}', NULL, '2026-09-17 06:53:28', '2026-09-17 06:53:28'),
('e41ed793-87e9-5f35-bc59-7032c366e677', 'request_workflow', 'App\\Models\\User', 12, 195, '{\"kind\":\"stage_completed\",\"title\":\"Service Report completed\",\"message\":\"GPM-20260917-0001: Service Report completed. All stages are complete and the request is now completed.\",\"stage\":\"service_report\",\"request_completed\":true}', '2026-09-17 06:52:37', '2026-09-17 06:22:21', '2026-09-17 06:52:37'),
('e43fb4a0-d81f-54ab-a12a-9577eb1bc2b8', 'request_workflow', 'App\\Models\\User', 12, 197, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260917-0003: Inspection: Not scheduled \\u2192 Sep 16, 2026.\"}', '2026-09-18 00:15:33', '2026-09-17 06:58:26', '2026-09-18 00:15:33'),
('e62a3b92-ab2e-52a0-9c7d-371acb2f8ed5', 'request_workflow', 'App\\Models\\User', 88, 197, '{\"kind\":\"new_request\",\"title\":\"New request\",\"message\":\"GPM-20260917-0003: Electrical Works requested by Renie Laranga (Pasig).\"}', '2026-09-17 06:58:20', '2026-09-17 06:58:03', '2026-09-17 06:58:20'),
('eca97896-7579-5718-9688-7d2f3432a4d0', 'request_workflow', 'App\\Models\\User', 11, 195, '{\"kind\":\"stage_completed\",\"title\":\"Service Report completed\",\"message\":\"GPM-20260917-0001: Service Report completed. All stages are complete and the request is now completed.\",\"stage\":\"service_report\",\"request_completed\":true}', NULL, '2026-09-17 06:22:21', '2026-09-17 06:22:21'),
('f826dce2-4844-51a8-a407-769874a704d5', 'request_workflow', 'App\\Models\\User', 12, 195, '{\"kind\":\"stage_completed\",\"title\":\"Work completed\",\"message\":\"GPM-20260917-0001: Work completed.\",\"stage\":\"work_order\",\"request_completed\":false}', '2026-09-17 06:52:37', '2026-09-17 06:21:52', '2026-09-17 06:52:37'),
('fa3dcb30-5e7d-5e2a-bb46-94968317aa2c', 'request_workflow', 'App\\Models\\User', 12, 197, '{\"kind\":\"stage_completed\",\"title\":\"Service Report completed\",\"message\":\"GPM-20260917-0003: Service Report completed.\",\"stage\":\"service_report\",\"request_completed\":false}', '2026-09-18 00:15:33', '2026-09-17 06:58:51', '2026-09-18 00:15:33');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_requests`
--

CREATE TABLE `property_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_no` varchar(255) NOT NULL,
  `dealer_id` bigint(20) UNSIGNED NOT NULL,
  `dealer_name` varchar(255) DEFAULT NULL,
  `submitted_by` bigint(20) UNSIGNED NOT NULL,
  `assigned_support_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `submitter_name` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `branch` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `request_type` varchar(255) NOT NULL,
  `priority` enum('low','regular','high','urgent') NOT NULL DEFAULT 'regular',
  `description` text NOT NULL,
  `request_date` date NOT NULL,
  `due_date` date NOT NULL,
  `approved_date` date DEFAULT NULL,
  `inspection_date` date DEFAULT NULL,
  `inspection_end_date` date DEFAULT NULL,
  `inspection_start_time` time DEFAULT NULL,
  `inspection_end_time` time DEFAULT NULL,
  `representative_1` varchar(255) DEFAULT NULL,
  `representative_2` varchar(255) DEFAULT NULL,
  `representative_3` varchar(255) DEFAULT NULL,
  `inspection_completed_at` timestamp NULL DEFAULT NULL,
  `work_order_start_date` date DEFAULT NULL,
  `work_order_start_time` time DEFAULT NULL,
  `work_order_end_date` date DEFAULT NULL,
  `work_order_end_time` time DEFAULT NULL,
  `work_order_representatives` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`work_order_representatives`)),
  `work_order_completed_at` timestamp NULL DEFAULT NULL,
  `service_report_completed_at` timestamp NULL DEFAULT NULL,
  `completion_notified_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','on_going','in_progress','awaiting_dealer','completed') NOT NULL DEFAULT 'pending',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `service_report_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_requests`
--

INSERT INTO `property_requests` (`id`, `reference_no`, `dealer_id`, `dealer_name`, `submitted_by`, `assigned_support_id`, `assigned_manager_id`, `submitter_name`, `designation`, `branch`, `area`, `request_type`, `priority`, `description`, `request_date`, `due_date`, `approved_date`, `inspection_date`, `inspection_end_date`, `inspection_start_time`, `inspection_end_time`, `representative_1`, `representative_2`, `representative_3`, `inspection_completed_at`, `work_order_start_date`, `work_order_start_time`, `work_order_end_date`, `work_order_end_time`, `work_order_representatives`, `work_order_completed_at`, `service_report_completed_at`, `completion_notified_at`, `status`, `completed_at`, `created_at`, `updated_at`, `service_report_date`) VALUES
(195, 'GPM-20260917-0001', 1, 'Mitsubishi', 12, 88, NULL, 'Renie Laranga', 'General Manager', 'Pasig', 'Metro Manila', 'General Repairs', 'regular', 'qwerytuiop[poiuytrewq', '2026-09-17', '2026-09-21', NULL, '2026-09-16', '2026-09-17', '14:21:00', '14:21:00', 'Dial-A', NULL, NULL, '2026-09-17 06:21:40', '2026-09-16', '14:21:46', '2026-09-17', '14:21:52', '[\"Dial-A\"]', '2026-09-17 06:21:52', '2026-09-17 06:22:21', NULL, 'completed', '2026-09-17 06:22:21', '2026-09-17 06:21:11', '2026-09-17 06:22:21', '2026-09-17'),
(196, 'GPM-20260917-0002', 1, 'Mitsubishi', 12, 88, NULL, 'Renie Laranga', 'General Manager', 'Pasig', 'Metro Manila', 'General Repairs', 'regular', 'qwe4r5t6y78i9o0poiuytrewqawsertyuiop[', '2026-09-17', '2026-09-21', NULL, '2026-09-16', '2026-09-17', '14:53:00', '14:53:00', 'Dial-A', NULL, NULL, '2026-09-17 06:53:22', '2026-09-16', '14:53:27', '2026-09-17', '14:53:33', '[\"Dial-A\"]', '2026-09-17 06:53:33', '2026-09-17 06:54:06', '2026-09-17 06:54:06', 'completed', '2026-09-17 06:56:42', '2026-09-17 06:52:49', '2026-09-17 06:56:42', '2026-09-17'),
(197, 'GPM-20260917-0003', 1, 'Mitsubishi', 12, 88, NULL, 'Renie Laranga', 'General Manager', 'Pasig', 'Metro Manila', 'Electrical Works', 'regular', 'as123wertyuiop[oiyutrewqrty', '2026-09-17', '2026-09-21', NULL, '2026-09-16', '2026-09-17', '14:58:00', '14:58:00', 'Dial-A', NULL, NULL, '2026-09-17 06:58:32', '2026-09-16', '14:58:37', '2026-09-17', '14:58:43', '[\"Dial-A\"]', '2026-09-17 06:58:43', '2026-09-17 06:58:51', '2026-09-17 06:58:51', 'on_going', NULL, '2026-09-17 06:58:03', '2026-09-17 06:58:51', '2026-09-17');

-- --------------------------------------------------------

--
-- Table structure for table `request_attachments`
--

CREATE TABLE `request_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `property_request_id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(30) NOT NULL DEFAULT 'request',
  `path` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `mime_type` varchar(100) NOT NULL,
  `size` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_attachments`
--

INSERT INTO `request_attachments` (`id`, `property_request_id`, `category`, `path`, `original_name`, `mime_type`, `size`, `created_at`, `updated_at`) VALUES
(415, 195, 'inspection', 'request-attachments/inspection/xqrrBgMMucg1UmaNLr2fjqGmfoCAHWjcJSp26J37.xlsx', 'GATEWAY Branch ISP Location Assignment.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 19293, '2026-09-17 06:21:40', '2026-09-17 06:21:40'),
(416, 195, 'work_order', 'request-attachments/work_order/FmN10pVq9VkcN4ZNJgER2x5ZwvjQO6UjmgzGQ8Rq.bin', 'Dealer-Directory-20260916-231036.xlsx', 'application/octet-stream', 3771, '2026-09-17 06:21:52', '2026-09-17 06:21:52'),
(417, 195, 'service_report', 'request-attachments/service_report/RBI369k0JfNCDYkPPxR7h8h5wqT4kGhFqVW3O4dC.xlsx', 'GATEWAY Branch ISP Location Assignment.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 19293, '2026-09-17 06:22:21', '2026-09-17 06:22:21'),
(418, 196, 'inspection', 'request-attachments/inspection/j79ihwYTU3oWEose8Tss7EobgTG1fq8Sj2jBLLnQ.xlsx', 'GATEWAY Branch ISP Location Assignment.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 19293, '2026-09-17 06:53:22', '2026-09-17 06:53:22'),
(419, 196, 'work_order', 'request-attachments/work_order/6eZtXzV1PNPUoLJQNaqlgyog8tpZo9LVJf3Vr4qj.bin', 'Requests-20260916-154817.xlsx', 'application/octet-stream', 4379, '2026-09-17 06:53:34', '2026-09-17 06:53:34'),
(420, 196, 'service_report', 'request-attachments/service_report/72NgUUtsBmVUD49l2QgfkW3P4BGUiKFqhHXDiRtR.xlsx', 'GATEWAY Branch ISP Location Assignment.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 19293, '2026-09-17 06:54:06', '2026-09-17 06:54:06'),
(421, 197, 'request', 'request-attachments/uhfaNfL534n8I3rKVhzVzNl1tiQMB3beGrQF2vZx.xlsx', 'GATEWAY Branch ISP Location Assignment.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 19293, '2026-09-17 06:58:03', '2026-09-17 06:58:03'),
(422, 197, 'inspection', 'request-attachments/inspection/NJ8uf7gaI6wMoncE1il3D1EwojFbwDsyHmiCdnol.bin', 'Dealer-Directory-20260916-231036.xlsx', 'application/octet-stream', 3771, '2026-09-17 06:58:32', '2026-09-17 06:58:32'),
(423, 197, 'work_order', 'request-attachments/work_order/QszvtUHob8m5SQsFtVET2yVaODeRLaFwvk2nYpkE.xlsx', 'GATEWAY Branch ISP Location Assignment.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 19293, '2026-09-17 06:58:44', '2026-09-17 06:58:44'),
(424, 197, 'service_report', 'request-attachments/service_report/MJqSw8LY1eYKav3HPD0iMOslAYMbempuE9OdEEpR.xlsx', 'GATEWAY Branch ISP Location Assignment.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 19293, '2026-09-17 06:58:51', '2026-09-17 06:58:51');

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
('wyz8UWHW3omna7II1S9IZ5CA5lA9awaI4keuCEfh', 12, '10.0.20.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWW5abkVEb3JrcXhLUUVvSXV1UTUyNXc3SU9MS0lPMnZZWTNOZjNBeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xMC4wLjIwLjEwMDo4MDAyL25vdGlmaWNhdGlvbnM/cGFnZT0xIjtzOjU6InJvdXRlIjtzOjE5OiJub3RpZmljYXRpb25zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTI7fQ==', 1789690837);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dealer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `role` enum('admin','dial_lead','pm_support','handyman','dial_a','pm_manager','representative','dealer') NOT NULL DEFAULT 'dealer',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `dealer_id`, `name`, `email`, `designation`, `role`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `must_change_password`, `deleted_at`) VALUES
(1, NULL, 'Administrator', 'administrator@gateway.com', 'Department Head / System Administrator', 'admin', 1, NULL, '$2y$12$y09wVrU1vf/gzhPi3xnd4e37S4QoQ1w8NdpaZgvDyNRXzzmSsunzq', NULL, '2026-09-15 03:58:19', '2026-09-15 03:58:19', 0, NULL),
(11, NULL, 'PM Manager', 'pmmanager@gateway.com', 'Property Management Manager', 'pm_manager', 1, NULL, '$2y$12$YSVrI8AkNXg46CaCBCyz0OME/BdUtT4HPgfJ0vSPT0uiV/K06DzUi', NULL, '2026-09-15 03:58:20', '2026-09-15 03:58:20', 0, NULL),
(12, 1, 'Renie Laranga', 'dealer@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$mpW7PTMVdcexJlGUOqPEl.Nz/tB8YLdSBh/XwOuC9k0y3OrQv0zOK', NULL, '2026-09-15 03:58:21', '2026-09-16 05:13:58', 0, NULL),
(13, 2, 'Maui Macusi', 'dealer2@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$gL80LWUdELDvOkcMv5.E1.h3NDpFlh607.ASFbaC1XW/aCkD0Tota', NULL, '2026-09-15 03:58:21', '2026-09-16 05:13:58', 0, NULL),
(14, 3, 'Maui Macusi', 'dealer3@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$KjAS4JwIBQfYqwBfkUTqPuj6S/ZVwXq/KOu.d4ZM3LUKqmgjL0xsG', NULL, '2026-09-15 03:58:21', '2026-09-16 05:13:58', 0, NULL),
(15, 4, 'Maui Macusi', 'dealer4@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$b0r3ddQC3iCRMYv1D1T5ru4Au03t8H7ECHN0bN0CO3r8rWsRSohve', NULL, '2026-09-15 03:58:22', '2026-09-16 05:13:58', 0, NULL),
(16, 5, 'Janice Delfin', 'dealer5@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$T.Nt3eSTrM8qD9h7gApmW./FVDRlsNTALITKKIVZP1cLNpbVUnuhy', NULL, '2026-09-15 03:58:22', '2026-09-16 05:13:58', 0, NULL),
(17, 6, 'Janice Delfin', 'dealer6@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$YkoofWi2/rNTuOSTobTwq.vzH6fLr0hE8THuWEcb/AiTF61IOZs42', NULL, '2026-09-15 03:58:22', '2026-09-16 05:13:58', 0, NULL),
(18, 7, 'Dick Albao', 'dealer7@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$sDL/Vk87.GPOt5TUoJ5zNO8t9yr8zjWto/lzwSMShXz3JjC5qlyd6', NULL, '2026-09-15 03:58:22', '2026-09-16 05:13:58', 0, NULL),
(19, 8, 'Randie Tungol', 'dealer8@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$a6jeBCw76zRjnpkTsCBNYOWfCmkAh2jh5GFDekY1in7wsT28U7vR.', NULL, '2026-09-15 03:58:23', '2026-09-16 05:13:58', 0, NULL),
(20, 9, 'Marie Adamos', 'dealer9@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$bT89tBG93Lch3USCMJmIAu/MrxzLqp8IzGtnMdR65b7cvEyiOi5hG', NULL, '2026-09-15 03:58:23', '2026-09-16 05:13:58', 0, NULL),
(21, 10, 'Ferdinand Uy', 'dealer10@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$jF/dap2i2ODXdLAuMoqzoOPos6Npc5mq.pfe/1KRHVvkSEavrLCH.', NULL, '2026-09-15 03:58:23', '2026-09-16 05:13:58', 0, NULL),
(22, 11, 'Rachelle Dy Diyco', 'dealer11@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$z8jip41dHpghGOyowZDV6.weVYsSvEaBuRb572eyHGi1LzpF0jXDa', NULL, '2026-09-15 03:58:24', '2026-09-16 05:13:58', 0, NULL),
(23, 12, 'Ferdinand Uy', 'dealer12@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$HOf3P5quMGOvtJhCZCJLiOdd6ab3oCOeu7llwCc2kx5MkKqP6ITuu', NULL, '2026-09-15 03:58:24', '2026-09-16 05:13:58', 0, NULL),
(24, 13, 'Cora Ortega', 'dealer13@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$ObMfXBXBfcF8qkAykjjawe3Qq.qOw1yPjAdISu9qvm57hhg1.b1gu', NULL, '2026-09-15 03:58:24', '2026-09-16 05:13:58', 0, NULL),
(25, 14, 'Gets Wambangco', 'dealer14@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$6.k3wGjlar4ToL5gOv6QDOBhb2ePYYkI2Z32GMSaWM3umG42WW/y6', NULL, '2026-09-15 03:58:24', '2026-09-16 05:13:58', 0, NULL),
(26, 15, 'Jun Samadan', 'dealer15@gateway.com', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$fgq5Jm5gtBpH8U0EX4g4aukl43TJKbmDBLF1K7yZHcwsFTSFPAcUO', NULL, '2026-09-15 03:58:25', '2026-09-16 05:13:58', 0, NULL),
(27, 16, 'Jun Samadan', 'dealer16@gateway.com', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$ST0SEvDQWjtTnsFWBFvrB.3NYZVKC0ilEJ/EcEAOlHWWOIxX5gHdO', NULL, '2026-09-15 03:58:25', '2026-09-16 05:13:59', 0, NULL),
(28, 17, 'Jun Samadan', 'dealer17@gateway.com', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$pZ/3.0yGy8DqpxRMyoIy4OhLKaJ/LqxQ.Bt95IwHePcOj1oztQ8Pi', NULL, '2026-09-15 03:58:25', '2026-09-16 05:13:59', 0, NULL),
(29, 18, 'Jun Samadan', 'dealer18@gateway.com', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$Hdg0qz5OT/hdvMhkozheNevl7Uuvep..PpB6oO8WLKumpP6xF1vHe', NULL, '2026-09-15 03:58:25', '2026-09-16 05:13:59', 0, NULL),
(30, 19, 'Jun Samadan', 'dealer19@gateway.com', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$y3VXHyIUHT2QsbfO/S.HJe3Ii/7tGU6Q9qJ559DCC8b6jB4AOM1G.', NULL, '2026-09-15 03:58:26', '2026-09-16 05:13:59', 0, NULL),
(31, 20, 'Jun Samadan', 'dealer20@gateway.com', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$HLE2WfPw5a90e20OpAB5CukGZQY3NtL6KUUixKNrGRfa2COqcvpcm', NULL, '2026-09-15 03:58:26', '2026-09-16 05:13:59', 0, NULL),
(32, 21, 'Jay-Ar Dyogi', 'dealer21@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$ZHHVJhoP0Hrzmv4eRMJ84OvqLFomu0Xe.w/dVlLSZQfyCqMopaM2m', NULL, '2026-09-15 03:58:26', '2026-09-16 05:13:59', 0, NULL),
(33, 22, 'Reggie De Guzman', 'dealer22@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$yXn7H2HQZFvB6xuPTyyPQuUlw8fTosjn4xfXyPyESJlwXCfCHOOlm', NULL, '2026-09-15 03:58:27', '2026-09-16 05:13:59', 0, NULL),
(34, 23, 'Kim Lanzanas', 'dealer23@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$58bfCN1ucCrIGWohglKbYehekIwCeYfKvLBlHkCnwGMZ9tBhah7GW', NULL, '2026-09-15 03:58:27', '2026-09-16 05:13:59', 0, NULL),
(35, 24, 'Ernie Samson', 'dealer24@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$8yl2oSy5VNMWDOIgm4Kq3eHTqpvkCPu8/GaQo2fGqhT1BHK3X6DBi', NULL, '2026-09-15 03:58:27', '2026-09-16 05:13:59', 0, NULL),
(36, 25, 'Ernie Samson', 'dealer25@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$V.XD3tuqr/8kNVrv0gAeIOF93e9IwA7v1q6Zo.6YSata.3rocrCMa', NULL, '2026-09-15 03:58:27', '2026-09-16 05:13:59', 0, NULL),
(37, 26, 'Emely Salmo', 'dealer26@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$qZ8wrZ62.buH7Ujg1LutJOKVVMmNf1DxUWIH.60JvwYoR/pVDeu/S', NULL, '2026-09-15 03:58:28', '2026-09-16 05:13:59', 0, NULL),
(38, 27, 'Mildred Martin', 'dealer27@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$vYwsJXiUWJUF5j8I503nQ.jrZEAf6eRQHP.h4wu.pwv9lYVh1qL9W', NULL, '2026-09-15 03:58:28', '2026-09-16 05:13:59', 0, NULL),
(39, 28, 'Maui Macusi', 'dealer28@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$LN6j0ZLqs8PtSew5FdCWuORBiYB6PC/ODFVcu7RP1DtNh2p6AIQpm', NULL, '2026-09-15 03:58:28', '2026-09-16 05:13:59', 0, NULL),
(40, 29, 'Reggie De Guzman', 'dealer29@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$CGfYceJx8j8YCNtSoBEpR.ldAeRQbX5hPJLunnUTt8F.ERCXnY4mG', NULL, '2026-09-15 03:58:28', '2026-09-16 05:13:59', 0, NULL),
(41, 30, 'Patrick Carandang', 'dealer30@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$X3oHKMuNSrIRKr.t/NLmcuHf1oGxQeUXHAP5JfsxdAHzvC0d2d2HK', NULL, '2026-09-15 03:58:29', '2026-09-16 05:13:59', 0, NULL),
(42, 31, 'Patrick Carandang', 'dealer31@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$6eEpXC8jdoYEjR/nPn8cWuSOn5pgDH/ha930ndeivW6KhSI6XA2ti', NULL, '2026-09-15 03:58:29', '2026-09-16 05:13:59', 0, NULL),
(43, 32, 'Julius Fabe', 'dealer32@gateway.com', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$tLAcKQZL4.hEUwBixENwTOMAzn/D2dYKWdHdLccvv7paUEEJ4uHGa', NULL, '2026-09-15 03:58:29', '2026-09-16 05:13:59', 0, NULL),
(44, 33, 'Kodak Bldg', 'dealer33@gateway.com', 'Dealer Representative', 'dealer', 1, NULL, '$2y$12$uqdeD0lpyxBBOs4iFdWKfudktOUbAC0YiwcKs.TCsDIAR3MRjHeem', NULL, '2026-09-15 03:58:30', '2026-09-15 03:58:30', 0, NULL),
(45, 34, 'Jay-Ar Dyogi', 'dealer34@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$tHUjGtXi.AyMwWW/Ks8y6epg96VzPgyBoCHrVZKAMKK9SZFs.xLcK', NULL, '2026-09-15 03:58:30', '2026-09-16 05:13:59', 0, NULL),
(46, 35, 'Jun Samadan', 'dealer35@gateway.com', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$fl/qVlgqV9kjqGB7JnCEX.hzchgFsO3UzjcVDwEg4zzqHZGE8cA22', NULL, '2026-09-15 03:58:30', '2026-09-16 05:13:59', 0, NULL),
(47, 36, 'Jun Samadan', 'dealer36@gateway.com', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$RtjSif9o09Pg4YKPMrVmeu3QQRGpQrOJ1C.wY4Ur94q8ygcentkHa', NULL, '2026-09-15 03:58:31', '2026-09-16 05:13:59', 0, NULL),
(48, 37, 'Jun Samadan', 'dealer37@gateway.com', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$b9UW9OOHDR1AAwFkkeCa/.A9nn5Mi1z0Knpr5ljpq2AM/rrJhgNN6', NULL, '2026-09-15 03:58:31', '2026-09-16 05:13:59', 0, NULL),
(49, 38, 'Jun Samadan', 'dealer38@gateway.com', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$O9430CsURm0NwzIE7r8voODfoxGEOizN.0kTJ6YKnBheF6p6x2xRu', NULL, '2026-09-15 03:58:31', '2026-09-16 05:13:59', 0, NULL),
(50, 39, 'Ernie Samson', 'dealer39@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$/1k86v8XRFKbrhNd662DTuEet0YphvGIUcmuUnPVIxabKWTc3rxuW', NULL, '2026-09-15 03:58:31', '2026-09-16 05:13:59', 0, NULL),
(51, 40, 'Dick Albao', 'dealer40@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$c7OzkhXmC1ItWZ4I5g1JSug/zuvUWsOcSLtObeYdSK6ZNC/RKn8RO', NULL, '2026-09-15 03:58:32', '2026-09-16 05:13:59', 0, NULL),
(52, 41, 'Jun Samadan', 'dealer41@gateway.com', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$3V.8qmSimiXNIlAd5i2dVetbfZAgPRs2Om7s9iQDUVQFqDZiKnsr2', NULL, '2026-09-15 03:58:32', '2026-09-16 05:13:59', 0, NULL),
(53, 42, 'Ghie Tungol', 'dealer42@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$lpwrbj2xkNqs8knKMV.5qOM28H2N65gJ7l91Tpeg4WGlZHfz1chsS', NULL, '2026-09-15 03:58:32', '2026-09-16 05:13:59', 0, NULL),
(54, 43, 'Angie Janer-Luz', 'dealer43@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$gUTskNpKR.gSHXnY4P35v.2zdBObhubrfJz6ELo3OZd2GWRDFa9he', NULL, '2026-09-15 03:58:32', '2026-09-16 05:13:59', 0, NULL),
(55, 44, 'Cris Grajo', 'dealer44@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$qRuJokfPM4bbBocChKT4uuAmh5Zuu8XcTSTWMiA4fU6vQr6yPd.QW', NULL, '2026-09-15 03:58:33', '2026-09-16 05:13:59', 0, NULL),
(56, 45, 'Mariver Garcia', 'dealer45@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$8q97Rpmuig/j.iqhEy2OoOxEyw53iQR7w1zlUNhCTGt.hVQZ9iTHi', NULL, '2026-09-15 03:58:33', '2026-09-16 05:13:59', 0, NULL),
(57, 46, 'Geely Naga', 'dealer46@gateway.com', 'Dealer Representative', 'dealer', 1, NULL, '$2y$12$lez.1iiFt1LIKle1Oy2W9OKQRUwfgOeeAgqBu7VovrxLqhD4fJgPS', NULL, '2026-09-15 03:58:33', '2026-09-15 03:58:33', 0, NULL),
(58, 47, 'Geely Dasma', 'dealer47@gateway.com', 'Dealer Representative', 'dealer', 1, NULL, '$2y$12$OGcrLb7JHweOPiNZ1hljauWDxXJvPbxJ1jtkWjWHsPMoQPgy3xqD.', NULL, '2026-09-15 03:58:33', '2026-09-15 03:58:33', 0, NULL),
(59, 48, 'Mariver Garcia', 'dealer48@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$eIT63iNKqy40RqTlG9AJ6.wVBp1MhRydRZMb8/r5pKUG9BNQsKCs.', NULL, '2026-09-15 03:58:34', '2026-09-16 05:13:59', 0, NULL),
(60, 49, 'Ariane Legarte', 'dealer49@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$LwmaJQv1fFoNpsLAXu/rUOsMtpVxxUSzefPenbHzN6DnTSwOG4Y4i', NULL, '2026-09-15 03:58:34', '2026-09-16 05:13:59', 0, NULL),
(61, 50, 'Heart Reyes', 'dealer50@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$zJaXd722ANJmMfyIOOU0huv/ajQBKeuWAis9sGIvxGdCwk7VE9XqC', NULL, '2026-09-15 03:58:34', '2026-09-16 05:13:59', 0, NULL),
(62, 51, 'Jojo Legarra', 'dealer51@gateway.com', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$S2RCiGpO0MQElJIE7cai2edfUiRjnpkohWsJS1QxdzynBFiOT/7Ve', NULL, '2026-09-15 03:58:35', '2026-09-16 05:13:59', 0, NULL),
(63, 52, 'Ariane Legarte', 'dealer52@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$cWLoKlbzglb7.ElRgBwAi.7to5DGO4Gwg2qkzahJ9xplGUQbb/uZi', NULL, '2026-09-15 03:58:35', '2026-09-16 05:13:59', 0, NULL),
(64, 53, 'Ariane Legarte', 'dealer53@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$nk2iz2GXS0eJm7fFufj3OuJ.mpLpf3MC5l9eMCf9FDCz2143OksJq', NULL, '2026-09-15 03:58:35', '2026-09-16 05:13:59', 0, NULL),
(65, 54, 'Rona Bringino', 'dealer54@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$JqHw42Ns9IfdfbQAtnx0GejuPSDc4dtspAQ0QC0E/mCRhRnJIukAe', NULL, '2026-09-15 03:58:35', '2026-09-16 05:13:59', 0, NULL),
(66, 55, 'Ivy Balerite', 'dealer55@gateway.com', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$ADFAAzphHImWfQ/sFVC3I.zffbVktvfdZ2xlL7FlJqdWoi.o3yx9G', NULL, '2026-09-15 03:58:35', '2026-09-16 05:13:59', 0, NULL),
(67, 56, 'Jhojo Claros', 'dealer56@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$zatwmZ8QpqSCjCxKL61cjOGMXFdoUDf8xBJTR459ZOq9E8OPf42qC', NULL, '2026-09-15 03:58:36', '2026-09-16 05:13:59', 0, NULL),
(68, 57, 'Mariver Garcia', 'dealer57@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$5WNRr4rqXALq28q5XktqIOhJswGdjCcPzOT1CYEryo/viVW6iEb9S', NULL, '2026-09-15 03:58:36', '2026-09-16 05:13:59', 0, NULL),
(69, 58, 'Mariver Garcia', 'dealer58@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$xbalTdTmxU1.b5NL6EaIQOkgTtvaVgk.hxRdRzoqE7bzPyebXJsqq', NULL, '2026-09-15 03:58:36', '2026-09-16 05:13:59', 0, NULL),
(70, 59, 'X Carmona', 'dealer59@gateway.com', 'Dealer Representative', 'dealer', 1, NULL, '$2y$12$4VZeC8Obkkk7KCpr.XyT3OuWKhWGqcrhBl.eSiMTRx9OYWTZFC.lK', NULL, '2026-09-15 03:58:37', '2026-09-15 03:58:37', 0, NULL),
(71, 60, 'Bong Rosal', 'dealer60@gateway.com', 'Area Head', 'dealer', 1, NULL, '$2y$12$i9mAiDLaJ3JEvzBvnfirpuWF3RbCYP3QJPXNkECiFffwgJGbtprSy', NULL, '2026-09-15 03:58:37', '2026-09-16 05:13:59', 0, NULL),
(72, 61, 'Bong Rosal', 'dealer61@gateway.com', 'Area Head', 'dealer', 1, NULL, '$2y$12$CWFa9Tqp2twpwhMKO7o6ruCmLrO67p/72ElMlDLVtLAKMoD.CJWmW', NULL, '2026-09-15 03:58:37', '2026-09-16 05:13:59', 0, NULL),
(73, 62, 'Diana Rose Suba', 'dealer62@gateway.com', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$LXDcew1DHB9jzD9kAvgbben6ooRi4vLA/4RWtu8BXuprXbjoDgMHK', NULL, '2026-09-15 03:58:37', '2026-09-16 05:13:59', 0, NULL),
(74, 63, 'Rolly Maur', 'dealer63@gateway.com', 'General Manager', 'dealer', 1, NULL, '$2y$12$2tFqjD5t0E7CwYmhz22cHe4Ptra.i9uldE7uLOn0mJ66iiBQfFBcW', NULL, '2026-09-15 03:58:38', '2026-09-16 05:13:59', 0, NULL),
(75, 64, 'Charlie Marcos', 'dealer64@gateway.com', 'Assistant Sales Manager', 'dealer', 1, NULL, '$2y$12$bsAwgBKH0F3io4sFHJifwu0WEzDfk1rgkLU6UCa3oHFg4OonRrYRS', NULL, '2026-09-15 03:58:38', '2026-09-16 05:13:59', 0, NULL),
(76, 65, 'Apple De Leon', 'dealer65@gateway.com', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$BcO4GPuZsPi3NqQWzW3eMOcYZam4lZ1xQSKdMD0K9Jb91ljJjDkRC', NULL, '2026-09-15 03:58:38', '2026-09-16 05:13:59', 0, NULL),
(77, 66, 'Geely Baliuag', 'dealer66@gateway.com', 'Dealer Representative', 'dealer', 1, NULL, '$2y$12$VkYgue9py32GNhiB3FJ4.ujmuahtjhi8V.5Ec3u9gwwTBSx4qT/pe', NULL, '2026-09-15 03:58:38', '2026-09-15 03:58:38', 0, NULL),
(78, 67, 'Diana Rose Suba', 'dealer67@gateway.com', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$m9OEtNjF1JVlaoJHZSsvsuUinOSPKTfoxTbt2GagakCuQk3ITVIfi', NULL, '2026-09-15 03:58:39', '2026-09-16 05:13:59', 0, NULL),
(79, 68, 'Aaron Pascual', 'dealer68@gateway.com', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$CsonAhK5zkVtQI.1cUdIJuYmrHIqbBf.AgZMbpgk70xZoniy0zZn.', NULL, '2026-09-15 03:58:39', '2026-09-16 05:13:59', 0, NULL),
(80, 69, 'Aaron Pascual', 'dealer69@gateway.com', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$M1j9sER0rZ1oPViLraE5TutUfpFyzGwsBi9NnwDVYcKdOO/fIcYrK', NULL, '2026-09-15 03:58:39', '2026-09-16 05:13:59', 0, NULL),
(81, 70, 'Dodie Ronquillo', 'dealer70@gateway.com', 'Area Head', 'dealer', 1, NULL, '$2y$12$2h0xuVr3BfoTSuGcuxL7oOsIMneUhRVhuYxSMirJK9qh7mO5mtJ3q', NULL, '2026-09-15 03:58:39', '2026-09-16 05:13:59', 0, NULL),
(88, NULL, 'Dial-A', 'diala@gateway.com', 'Dial-A / Property Management Support', 'dial_a', 1, NULL, '$2y$12$DPI7lC020QYrFbisE4yWYu8KsvaGUk3LQnc.efasfhhKULEyIZCHi', NULL, '2026-09-15 05:21:52', '2026-09-15 05:21:52', 0, NULL),
(89, 14, 'Rojeme Renz Sapno', 'rojemerenz.sapno@gatewaygroup.ph', 'PM Manager', 'pm_manager', 1, NULL, '$2y$12$nkhIiR/WT3vIUkrxJY9xkuTaMqfmq2Pqex0OYWLpOn1/K/EKKIxoG', NULL, '2026-09-16 03:42:20', '2026-09-16 03:42:20', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `web_push_subscriptions`
--

CREATE TABLE `web_push_subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `endpoint_hash` char(64) NOT NULL,
  `endpoint` text NOT NULL,
  `public_key` varchar(100) NOT NULL,
  `auth_token` varchar(32) NOT NULL,
  `device_token_hash` char(64) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `web_push_subscriptions`
--

INSERT INTO `web_push_subscriptions` (`id`, `user_id`, `endpoint_hash`, `endpoint`, `public_key`, `auth_token`, `device_token_hash`, `created_at`, `updated_at`) VALUES
(9, 11, 'ee9ac3e5300484a6658b99a1ee905a7622a75dfa4af9b4eb96099100797f48b7', 'https://fcm.googleapis.com/fcm/send/cgcYR_6AajI:APA91bE1ejEx7VcFau2nHICHI8Dw-Csc2e46zds2Tm57CkYh8y6aYvRgJnAeyu6Lu_mt-aHwawcsFGMykUidx0YfSmBy64WtUQGaY2fvhNbC50-Qcm4fcS0uha0-BtOFTIuw777QFa3t', 'BD7W2pBr8Bcn3-Y9UyKwqgqbWbbESxoIWlYsexwmRCC4FyfMsuEoiiYP9eOuhvYD_lZgRgaaY_v3LtdzUwlkGSg', 'xfEMrqaXo58KF3KS_GlO2w', 'b2d7d9fabe142d6ec4cb4b22d19b6b516cb1488bd93863e42d1c24cdeecbaaf8', '2026-09-16 00:47:59', '2026-09-16 00:47:59'),
(67, 88, '46fffe8818e81ce12424a6ea7a594db50e275787cfc71a0c7eab67173ea548a4', 'https://fcm.googleapis.com/fcm/send/fWU4rpYqTZ8:APA91bESvxruRdTwxSZ9PxCcL7uEpRpsnkNvXEiMV5VhI-KnkPNYDNwMJ2nqKEu8XjMsfhMl9nsnqOVNiCQsy2VsaCcQ9pvhIRRQd-n2istELcLzKH28fMY3G6gtZT2OLfr5eWjsZi4d', 'BM_mac6caHS4nMZOSsYVAdDBYLwakAuBua0iRbeth7WRQAtlhmuH7E9nu6nj-39b0RQuQh2VgcIspQ-XJqimsuc', 'ponryB2febRPjEYpP5SB0w', 'fda232d40f3691181d9fcfe49608d5024a55aeaa6b0c50adc00dc9ce538d0185', '2026-09-17 00:50:29', '2026-09-17 00:50:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`),
  ADD KEY `audit_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  ADD KEY `audit_logs_action_created_at_index` (`action`,`created_at`);

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
-- Indexes for table `dealers`
--
ALTER TABLE `dealers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dealers_source_no_unique` (`source_no`),
  ADD KEY `dealers_area_brand_index` (`area`,`brand`),
  ADD KEY `dealers_city_index` (`city`);

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
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  ADD KEY `notifications_property_request_id_foreign` (`property_request_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `property_requests`
--
ALTER TABLE `property_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `property_requests_reference_no_unique` (`reference_no`),
  ADD KEY `property_requests_submitted_by_foreign` (`submitted_by`),
  ADD KEY `property_requests_assigned_support_id_foreign` (`assigned_support_id`),
  ADD KEY `property_requests_status_due_date_index` (`status`,`due_date`),
  ADD KEY `property_requests_dealer_id_created_at_index` (`dealer_id`,`created_at`),
  ADD KEY `property_requests_assigned_manager_id_foreign` (`assigned_manager_id`);

--
-- Indexes for table `request_attachments`
--
ALTER TABLE `request_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_attachments_property_request_id_foreign` (`property_request_id`),
  ADD KEY `request_attachments_category_index` (`category`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_dealer_id_foreign` (`dealer_id`);

--
-- Indexes for table `web_push_subscriptions`
--
ALTER TABLE `web_push_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `web_push_subscriptions_endpoint_hash_unique` (`endpoint_hash`),
  ADD KEY `web_push_subscriptions_user_id_foreign` (`user_id`),
  ADD KEY `web_push_subscriptions_device_token_hash_index` (`device_token_hash`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1259;

--
-- AUTO_INCREMENT for table `dealers`
--
ALTER TABLE `dealers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `property_requests`
--
ALTER TABLE `property_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT for table `request_attachments`
--
ALTER TABLE `request_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=425;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `web_push_subscriptions`
--
ALTER TABLE `web_push_subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_property_request_id_foreign` FOREIGN KEY (`property_request_id`) REFERENCES `property_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `property_requests`
--
ALTER TABLE `property_requests`
  ADD CONSTRAINT `property_requests_assigned_manager_id_foreign` FOREIGN KEY (`assigned_manager_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `property_requests_assigned_support_id_foreign` FOREIGN KEY (`assigned_support_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `property_requests_dealer_id_foreign` FOREIGN KEY (`dealer_id`) REFERENCES `dealers` (`id`),
  ADD CONSTRAINT `property_requests_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `request_attachments`
--
ALTER TABLE `request_attachments`
  ADD CONSTRAINT `request_attachments_property_request_id_foreign` FOREIGN KEY (`property_request_id`) REFERENCES `property_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_dealer_id_foreign` FOREIGN KEY (`dealer_id`) REFERENCES `dealers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `web_push_subscriptions`
--
ALTER TABLE `web_push_subscriptions`
  ADD CONSTRAINT `web_push_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
