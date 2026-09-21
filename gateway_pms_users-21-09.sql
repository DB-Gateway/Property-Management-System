-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2026 at 11:55 AM
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
(1222, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-17 06:20:55', '2026-09-17 06:20:55'),
(1223, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 06:21:01', '2026-09-17 06:21:01'),
(1225, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 06:21:14', '2026-09-17 06:21:14'),
(1226, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 06:21:18', '2026-09-17 06:21:18'),
(1235, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 06:52:25', '2026-09-17 06:52:25'),
(1236, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 06:52:32', '2026-09-17 06:52:32'),
(1238, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 06:52:52', '2026-09-17 06:52:52'),
(1239, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 06:52:57', '2026-09-17 06:52:57'),
(1247, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-17 06:56:58', '2026-09-17 06:56:58'),
(1248, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-17 06:57:15', '2026-09-17 06:57:15'),
(1250, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-17 06:58:10', '2026-09-17 06:58:10'),
(1251, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-17 06:58:16', '2026-09-17 06:58:16'),
(1258, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-18 00:15:24', '2026-09-18 00:15:24'),
(1260, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-18 00:50:49', '2026-09-18 00:50:49'),
(1261, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-18 00:50:57', '2026-09-18 00:50:57'),
(1262, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-18 00:51:12', '2026-09-18 00:51:12'),
(1263, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-18 00:56:03', '2026-09-18 00:56:03'),
(1264, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-18 00:56:08', '2026-09-18 00:56:08'),
(1265, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-18 00:57:11', '2026-09-18 00:57:11'),
(1266, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-18 00:57:45', '2026-09-18 00:57:45'),
(1268, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-18 01:11:59', '2026-09-18 01:11:59'),
(1269, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-18 01:12:02', '2026-09-18 01:12:02'),
(1270, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-18 01:13:08', '2026-09-18 01:13:08'),
(1271, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-18 01:30:27', '2026-09-18 01:30:27'),
(1272, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-18 01:30:51', '2026-09-18 01:30:51'),
(1273, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-18 01:30:53', '2026-09-18 01:30:53'),
(1281, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-18 01:31:31', '2026-09-18 01:31:31'),
(1282, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-18 01:31:34', '2026-09-18 01:31:34'),
(1283, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-18 01:31:53', '2026-09-18 01:31:53'),
(1284, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-18 01:31:56', '2026-09-18 01:31:56'),
(1285, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-18 01:32:28', '2026-09-18 01:32:28'),
(1286, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-18 01:32:30', '2026-09-18 01:32:30'),
(1287, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-18 01:33:37', '2026-09-18 01:33:37'),
(1288, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-18 01:34:19', '2026-09-18 01:34:19'),
(1289, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-18 01:34:22', '2026-09-18 01:34:22'),
(1290, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-18 01:34:25', '2026-09-18 01:34:25'),
(1291, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-18 01:34:28', '2026-09-18 01:34:28'),
(1292, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-18 01:34:31', '2026-09-18 01:34:31'),
(1293, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-18 01:34:41', '2026-09-18 01:34:41'),
(1294, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-18 01:34:44', '2026-09-18 01:34:44'),
(1295, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-18 02:03:50', '2026-09-18 02:03:50'),
(1296, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-18 02:05:39', '2026-09-18 02:05:39'),
(1297, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-18 02:15:16', '2026-09-18 02:15:16'),
(1298, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-18 02:15:20', '2026-09-18 02:15:20'),
(1299, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-18 02:15:25', '2026-09-18 02:15:25'),
(1300, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-18 02:15:31', '2026-09-18 02:15:31'),
(1301, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-18 02:15:54', '2026-09-18 02:15:54'),
(1302, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-18 02:19:28', '2026-09-18 02:19:28'),
(1303, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-18 05:07:20', '2026-09-18 05:07:20'),
(1304, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-18 05:07:35', '2026-09-18 05:07:35'),
(1305, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-18 05:07:38', '2026-09-18 05:07:38'),
(1306, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-18 05:10:25', '2026-09-18 05:10:25'),
(1307, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-18 05:10:51', '2026-09-18 05:10:51'),
(1308, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-18 05:10:57', '2026-09-18 05:10:57'),
(1309, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-18 05:11:01', '2026-09-18 05:11:01'),
(1310, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-18 05:11:23', '2026-09-18 05:11:23'),
(1311, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-18 05:11:30', '2026-09-18 05:11:30'),
(1312, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-18 05:12:25', '2026-09-18 05:12:25'),
(1313, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-18 05:13:08', '2026-09-18 05:13:08'),
(1314, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-18 05:13:16', '2026-09-18 05:13:16'),
(1315, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-18 05:13:22', '2026-09-18 05:13:22'),
(1316, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-18 05:13:31', '2026-09-18 05:13:31'),
(1317, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-18 05:13:40', '2026-09-18 05:13:40'),
(1318, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-18 05:13:50', '2026-09-18 05:13:50'),
(1319, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-18 05:29:30', '2026-09-18 05:29:30'),
(1320, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-18 05:30:05', '2026-09-18 05:30:05'),
(1321, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-18 05:30:10', '2026-09-18 05:30:10'),
(1322, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-18 06:22:47', '2026-09-18 06:22:47'),
(1323, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-18 06:22:51', '2026-09-18 06:22:51'),
(1324, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-18 07:06:32', '2026-09-18 07:06:32'),
(1325, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-18 07:06:41', '2026-09-18 07:06:41'),
(1327, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-18 07:06:56', '2026-09-18 07:06:56'),
(1328, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-18 07:07:01', '2026-09-18 07:07:01'),
(1329, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-18 07:07:07', '2026-09-18 07:07:07'),
(1330, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-18 07:50:28', '2026-09-18 07:50:28'),
(1332, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-18 07:51:06', '2026-09-18 07:51:06'),
(1333, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-18 07:51:09', '2026-09-18 07:51:09'),
(1334, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-18 08:10:24', '2026-09-18 08:10:24'),
(1335, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-18 08:25:06', '2026-09-18 08:25:06'),
(1336, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-18 08:25:25', '2026-09-18 08:25:25'),
(1337, 12, 'login', NULL, NULL, 'Renie Laranga signed in.', NULL, '10.0.20.100', '2026-09-18 08:25:29', '2026-09-18 08:25:29'),
(1338, 12, 'logout', NULL, NULL, 'Renie Laranga signed out.', NULL, '10.0.20.100', '2026-09-18 08:25:47', '2026-09-18 08:25:47'),
(1339, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-19 00:41:13', '2026-09-19 00:41:13'),
(1340, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-19 01:33:01', '2026-09-19 01:33:01'),
(1341, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-19 01:55:13', '2026-09-19 01:55:13'),
(1342, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-19 02:28:54', '2026-09-19 02:28:54'),
(1343, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-19 02:46:31', '2026-09-19 02:46:31'),
(1344, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-19 02:46:37', '2026-09-19 02:46:37'),
(1345, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-19 02:46:57', '2026-09-19 02:46:57'),
(1346, 1, 'user_created', 'User', 90, 'Created user JR PACTOR (jrpactor@gatewaygroup.com.ph).', NULL, '10.0.20.100', '2026-09-19 02:47:46', '2026-09-19 02:47:46'),
(1347, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-19 02:47:48', '2026-09-19 02:47:48'),
(1348, 90, 'login', NULL, NULL, 'JR PACTOR signed in.', NULL, '10.0.20.100', '2026-09-19 02:58:44', '2026-09-19 02:58:44'),
(1349, 90, 'password_changed', 'User', 90, 'JR PACTOR changed their first-login password.', NULL, '10.0.20.100', '2026-09-19 02:58:58', '2026-09-19 02:58:58'),
(1350, 90, 'logout', NULL, NULL, 'JR PACTOR signed out.', NULL, '10.0.20.100', '2026-09-19 02:59:01', '2026-09-19 02:59:01'),
(1351, 90, 'login', NULL, NULL, 'JR PACTOR signed in.', NULL, '10.0.20.100', '2026-09-19 02:59:04', '2026-09-19 02:59:04'),
(1352, 90, 'logout', NULL, NULL, 'JR PACTOR signed out.', NULL, '10.0.20.100', '2026-09-19 02:59:05', '2026-09-19 02:59:05'),
(1353, 90, 'login', NULL, NULL, 'JR PACTOR signed in.', NULL, '10.0.20.100', '2026-09-19 03:29:14', '2026-09-19 03:29:14'),
(1354, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-21 01:14:21', '2026-09-21 01:14:21'),
(1355, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-21 01:27:51', '2026-09-21 01:27:51'),
(1356, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-21 01:38:11', '2026-09-21 01:38:11'),
(1357, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-21 02:45:50', '2026-09-21 02:45:50'),
(1358, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-21 02:46:18', '2026-09-21 02:46:18'),
(1359, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (4 records).', '{\"date_period\":\"month\",\"period_month\":\"2026-09\",\"status\":\"completed\",\"branch\":\"\",\"area\":\"\",\"selected_area_raw\":\"\"}', '10.0.20.100', '2026-09-21 02:47:08', '2026-09-21 02:47:08'),
(1360, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-21 02:47:22', '2026-09-21 02:47:22'),
(1361, 25, 'login', NULL, NULL, 'Gets Wambangco signed in.', NULL, '10.0.20.100', '2026-09-21 02:50:34', '2026-09-21 02:50:34'),
(1362, 25, 'logout', NULL, NULL, 'Gets Wambangco signed out.', NULL, '10.0.20.100', '2026-09-21 02:50:37', '2026-09-21 02:50:37'),
(1363, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-21 02:51:03', '2026-09-21 02:51:03'),
(1364, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-21 02:51:50', '2026-09-21 02:51:50'),
(1365, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-21 02:55:53', '2026-09-21 02:55:53'),
(1366, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-21 02:55:57', '2026-09-21 02:55:57'),
(1367, 90, 'login', NULL, NULL, 'JR PACTOR signed in.', NULL, '10.0.20.100', '2026-09-21 02:57:42', '2026-09-21 02:57:42'),
(1368, 90, 'logout', NULL, NULL, 'JR PACTOR signed out.', NULL, '10.0.20.100', '2026-09-21 03:02:27', '2026-09-21 03:02:27'),
(1369, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-21 03:02:30', '2026-09-21 03:02:30'),
(1371, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-21 03:16:40', '2026-09-21 03:16:40'),
(1372, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-21 03:16:47', '2026-09-21 03:16:47'),
(1373, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-21 03:16:50', '2026-09-21 03:16:50'),
(1374, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-21 03:16:53', '2026-09-21 03:16:53'),
(1375, 1, 'requests_reset', NULL, NULL, 'Administrator reset all request records. Dealer and user data were preserved.', '{\"requests\":4,\"attachments\":13,\"request_logs\":37}', '10.0.20.100', '2026-09-21 03:17:17', '2026-09-21 03:17:17'),
(1376, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-21 03:17:20', '2026-09-21 03:17:20'),
(1377, 25, 'login', NULL, NULL, 'Gets Wambangco signed in.', NULL, '10.0.20.100', '2026-09-21 03:17:24', '2026-09-21 03:17:24'),
(1378, 25, 'request_submitted', 'PropertyRequest', 199, 'GPM-20260921-0001 was submitted by Gets Wambangco for Hyundai (Makati).', NULL, '10.0.20.100', '2026-09-21 03:17:50', '2026-09-21 03:17:50'),
(1379, 25, 'logout', NULL, NULL, 'Gets Wambangco signed out.', NULL, '10.0.20.100', '2026-09-21 03:17:53', '2026-09-21 03:17:53'),
(1380, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-21 03:22:12', '2026-09-21 03:22:12'),
(1381, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-21 03:50:54', '2026-09-21 03:50:54'),
(1382, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-21 03:50:56', '2026-09-21 03:50:56'),
(1383, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-21 06:10:44', '2026-09-21 06:10:44'),
(1384, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-21 06:10:51', '2026-09-21 06:10:51'),
(1385, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-21 06:11:03', '2026-09-21 06:11:03'),
(1386, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-21 07:13:34', '2026-09-21 07:13:34'),
(1387, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-21 07:27:37', '2026-09-21 07:27:37'),
(1388, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-21 07:38:36', '2026-09-21 07:38:36'),
(1389, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-21 07:38:42', '2026-09-21 07:38:42'),
(1390, 25, 'login', NULL, NULL, 'Gets Wambangco signed in.', NULL, '10.0.20.100', '2026-09-21 07:38:46', '2026-09-21 07:38:46'),
(1391, 25, 'request_submitted', 'PropertyRequest', 200, 'GPM-20260921-0002 was submitted by Gets Wambangco for Hyundai (Makati).', NULL, '10.0.20.100', '2026-09-21 07:40:57', '2026-09-21 07:40:57'),
(1392, 25, 'logout', NULL, NULL, 'Gets Wambangco signed out.', NULL, '10.0.20.100', '2026-09-21 07:41:02', '2026-09-21 07:41:02'),
(1393, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-21 07:41:10', '2026-09-21 07:41:10'),
(1394, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-21 07:41:16', '2026-09-21 07:41:16'),
(1395, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-21 07:41:20', '2026-09-21 07:41:20'),
(1396, 11, 'request_pm_reviewed', 'PropertyRequest', 200, 'GPM-20260921-0002 was assigned to In house by PM Manager PM Manager.', '{\"previous_assignment_type\":\"pending_review\",\"assignment_type\":\"in_house\",\"previous_state\":{\"status\":\"pending\",\"completed_at\":null,\"in_house_completed_at\":null},\"actor\":{\"assignment_by_id\":11,\"assignment_by_name\":\"PM Manager\",\"assignment_by_role\":\"pm_manager\"},\"saved_workflow_and_files_retained\":true,\"priority\":\"urgent\",\"remarks\":\"qweqwewqrqwrqwtu\"}', '10.0.20.100', '2026-09-21 07:41:35', '2026-09-21 07:41:35'),
(1397, 11, 'request_assignment_changed', 'PropertyRequest', 200, 'GPM-20260921-0002 was assigned to Dial-A by PM Manager PM Manager.', '{\"previous_assignment_type\":\"in_house\",\"assignment_type\":\"dial_a\",\"previous_state\":{\"status\":\"on_going\",\"completed_at\":null,\"in_house_completed_at\":null},\"actor\":{\"assignment_by_id\":11,\"assignment_by_name\":\"PM Manager\",\"assignment_by_role\":\"pm_manager\"},\"saved_workflow_and_files_retained\":true,\"priority\":\"urgent\",\"remarks\":\"qweqwewqrqwrqwtu\"}', '10.0.20.100', '2026-09-21 07:42:56', '2026-09-21 07:42:56'),
(1398, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-21 07:43:08', '2026-09-21 07:43:08'),
(1399, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-21 07:43:14', '2026-09-21 07:43:14'),
(1400, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-21 07:43:20', '2026-09-21 07:43:20'),
(1401, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-21 07:43:25', '2026-09-21 07:43:25'),
(1402, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-21 07:44:16', '2026-09-21 07:44:16'),
(1403, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-21 07:44:19', '2026-09-21 07:44:19'),
(1404, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-21 07:51:21', '2026-09-21 07:51:21'),
(1405, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-21 07:52:23', '2026-09-21 07:52:23'),
(1406, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-21 07:55:20', '2026-09-21 07:55:20'),
(1407, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-21 07:55:23', '2026-09-21 07:55:23'),
(1408, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-21 07:55:35', '2026-09-21 07:55:35'),
(1409, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-21 07:55:42', '2026-09-21 07:55:42'),
(1410, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-21 07:55:57', '2026-09-21 07:55:57'),
(1411, 25, 'login', NULL, NULL, 'Gets Wambangco signed in.', NULL, '10.0.20.100', '2026-09-21 07:56:06', '2026-09-21 07:56:06'),
(1413, 25, 'logout', NULL, NULL, 'Gets Wambangco signed out.', NULL, '10.0.20.100', '2026-09-21 07:56:22', '2026-09-21 07:56:22'),
(1414, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-21 07:56:29', '2026-09-21 07:56:29'),
(1421, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-21 07:58:30', '2026-09-21 07:58:30'),
(1422, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-21 07:58:36', '2026-09-21 07:58:36'),
(1423, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-21 07:58:38', '2026-09-21 07:58:38'),
(1424, 88, 'login', NULL, NULL, 'Dial-A signed in.', NULL, '10.0.20.100', '2026-09-21 07:58:41', '2026-09-21 07:58:41'),
(1425, 88, 'inspection_date_set', 'PropertyRequest', 200, 'GPM-20260921-0002 inspection start date set to September 20, 2026 and status set to On-going by Dial-A Dial-A.', '{\"inspection_date\":\"2026-09-20\",\"inspection_start_time\":\"15:58:00\",\"inspection_representatives\":[\"Dial-A\"]}', '10.0.20.100', '2026-09-21 07:58:54', '2026-09-21 07:58:54'),
(1426, 88, 'inspection_completed', 'PropertyRequest', 200, 'GPM-20260921-0002 inspection was conducted and completed by Dial-A Dial-A with representative(s): Dial-A.', '{\"inspection_date\":\"2026-09-20\",\"inspection_end_date\":\"2026-09-21\",\"inspection_start_time\":\"15:58\",\"inspection_end_time\":\"15:59\",\"representatives\":[\"Dial-A\"],\"inspection_attachment\":\"SSID-FORMAT (1).xlsx\",\"inspection_attachments_count\":1,\"template_confirmed\":true,\"inspection_completed_at\":\"2026-09-21T07:59:01.000000Z\"}', '10.0.20.100', '2026-09-21 07:59:01', '2026-09-21 07:59:01'),
(1427, 88, 'work_order_date_set', 'PropertyRequest', 200, 'GPM-20260921-0002 Work Order start date set to September 20, 2026 and status set to On-going by Dial-A Dial-A.', '{\"work_order_start_date\":\"2026-09-20\",\"work_order_start_time\":\"15:59:11\",\"work_order_representatives\":[\"Dial-A\"]}', '10.0.20.100', '2026-09-21 07:59:11', '2026-09-21 07:59:11'),
(1428, 88, 'work_order_completed', 'PropertyRequest', 200, 'GPM-20260921-0002 Work Order attachments were uploaded and completed by Dial-A Dial-A.', '{\"work_order_start_date\":\"2026-09-20\",\"work_order_end_date\":\"2026-09-21\",\"work_order_start_time\":\"15:59:11\",\"work_order_end_time\":\"15:59:17\",\"representatives\":[\"Dial-A\"]}', '10.0.20.100', '2026-09-21 07:59:17', '2026-09-21 07:59:17'),
(1429, 88, 'service_report_ongoing', 'PropertyRequest', 200, 'GPM-20260921-0002 Service Report file upload initiated and marked as On-going by Dial-A Dial-A.', '{\"service_report_date\":\"2026-09-21\"}', '10.0.20.100', '2026-09-21 07:59:25', '2026-09-21 07:59:25'),
(1430, 88, 'service_report_uploaded', 'PropertyRequest', 200, 'GPM-20260921-0002 Service Report attachments were uploaded by Dial-A Dial-A.', NULL, '10.0.20.100', '2026-09-21 07:59:26', '2026-09-21 07:59:26'),
(1431, 88, 'request_completed', 'PropertyRequest', 200, 'GPM-20260921-0002 was reviewed, confirmed, and officially marked completed by Dial-A Dial-A.', NULL, '10.0.20.100', '2026-09-21 07:59:33', '2026-09-21 07:59:33'),
(1432, 88, 'logout', NULL, NULL, 'Dial-A signed out.', NULL, '10.0.20.100', '2026-09-21 07:59:42', '2026-09-21 07:59:42'),
(1433, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-21 07:59:45', '2026-09-21 07:59:45'),
(1434, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (1 records).', '{\"search\":\"GPM-20260921-0002\",\"date_period\":\"month\",\"period_month\":\"2026-09\",\"status\":\"completed\",\"branch\":\"\",\"area\":\"\",\"selected_area_raw\":\"\"}', '10.0.20.100', '2026-09-21 08:00:33', '2026-09-21 08:00:33'),
(1435, 11, 'report_published', NULL, NULL, 'PM Manager PM Manager published an operations report (1 records).', '{\"search\":\"GPM-20260921-0002\",\"date_period\":\"month\",\"period_month\":\"2026-09\",\"status\":\"completed\",\"branch\":\"\",\"area\":\"\",\"selected_area_raw\":\"\"}', '10.0.20.100', '2026-09-21 08:17:53', '2026-09-21 08:17:53'),
(1436, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-21 08:18:44', '2026-09-21 08:18:44'),
(1437, 25, 'login', NULL, NULL, 'Gets Wambangco signed in.', NULL, '10.0.20.100', '2026-09-21 08:18:47', '2026-09-21 08:18:47'),
(1438, 25, 'logout', NULL, NULL, 'Gets Wambangco signed out.', NULL, '10.0.20.100', '2026-09-21 08:18:54', '2026-09-21 08:18:54'),
(1439, 1, 'login', NULL, NULL, 'Administrator signed in.', NULL, '10.0.20.100', '2026-09-21 08:18:58', '2026-09-21 08:18:58'),
(1440, 1, 'request_deleted', NULL, NULL, 'GPM-20260921-0003 was permanently deleted by administrator Administrator.', '{\"reference_no\":\"GPM-20260921-0003\",\"attachments_removed\":0}', '10.0.20.100', '2026-09-21 08:19:08', '2026-09-21 08:19:08'),
(1441, 1, 'logout', NULL, NULL, 'Administrator signed out.', NULL, '10.0.20.100', '2026-09-21 08:19:15', '2026-09-21 08:19:15'),
(1442, 25, 'login', NULL, NULL, 'Gets Wambangco signed in.', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 08:38:45', '2026-09-21 08:38:45'),
(1443, 25, 'logout', NULL, NULL, 'Gets Wambangco signed out.', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 08:46:53', '2026-09-21 08:46:53'),
(1444, 25, 'login', NULL, NULL, 'Gets Wambangco signed in.', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 08:46:59', '2026-09-21 08:46:59'),
(1445, 25, 'logout', NULL, NULL, 'Gets Wambangco signed out.', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 08:47:09', '2026-09-21 08:47:09'),
(1446, 88, 'login', NULL, NULL, 'Dial-A signed in.', '{\"history_actor_name\":\"Dial-A\",\"history_actor_role\":\"dial_a\"}', '10.0.20.100', '2026-09-21 08:47:12', '2026-09-21 08:47:12'),
(1447, 88, 'logout', NULL, NULL, 'Dial-A signed out.', '{\"history_actor_name\":\"Dial-A\",\"history_actor_role\":\"dial_a\"}', '10.0.20.100', '2026-09-21 09:10:27', '2026-09-21 09:10:27'),
(1448, 25, 'login', NULL, NULL, 'Gets Wambangco signed in.', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 09:10:33', '2026-09-21 09:10:33'),
(1449, 25, 'logout', NULL, NULL, 'Gets Wambangco signed out.', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 09:16:53', '2026-09-21 09:16:53'),
(1450, 11, 'login', NULL, NULL, 'PM Manager signed in.', '{\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:16:56', '2026-09-21 09:16:56'),
(1451, 11, 'logout', NULL, NULL, 'PM Manager signed out.', '{\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:17:12', '2026-09-21 09:17:12'),
(1452, 25, 'login', NULL, NULL, 'Gets Wambangco signed in.', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 09:17:16', '2026-09-21 09:17:16'),
(1453, 25, 'request_submitted', 'PropertyRequest', 202, 'GPM-20260921-0003 was submitted by Gets Wambangco for Hyundai (Makati).', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 09:17:26', '2026-09-21 09:17:26'),
(1454, 25, 'logout', NULL, NULL, 'Gets Wambangco signed out.', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 09:19:05', '2026-09-21 09:19:05'),
(1455, 11, 'login', NULL, NULL, 'PM Manager signed in.', '{\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:19:08', '2026-09-21 09:19:08'),
(1456, 11, 'request_pm_reviewed', 'PropertyRequest', 202, 'GPM-20260921-0003 was assigned to In house by PM Manager PM Manager.', '{\"previous_assignment_type\":\"pending_review\",\"assignment_type\":\"in_house\",\"previous_state\":{\"status\":\"pending\",\"completed_at\":null,\"in_house_completed_at\":null},\"actor\":{\"assignment_by_id\":11,\"assignment_by_name\":\"PM Manager\",\"assignment_by_role\":\"pm_manager\"},\"saved_workflow_and_files_retained\":true,\"priority\":\"regular\",\"remarks\":\"qwertyuiop[\",\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:20:54', '2026-09-21 09:20:54'),
(1457, 11, 'request_assignment_proceeded', 'PropertyRequest', 202, 'GPM-20260921-0003 assignment was confirmed by PM Manager PM Manager.', '{\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:22:26', '2026-09-21 09:22:26'),
(1458, 11, 'logout', NULL, NULL, 'PM Manager signed out.', '{\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:23:03', '2026-09-21 09:23:03'),
(1459, 1, 'login', NULL, NULL, 'Administrator signed in.', '{\"history_actor_name\":\"Administrator\",\"history_actor_role\":\"admin\"}', '10.0.20.100', '2026-09-21 09:23:07', '2026-09-21 09:23:07'),
(1460, 1, 'request_assignment_undone', 'PropertyRequest', 202, 'GPM-20260921-0003 assignment was reopened by Administrator Administrator.', '{\"before\":{\"assignment_type\":\"in_house\",\"assignment_phase\":\"proceeded\",\"priority\":\"regular\",\"priority_remarks\":\"qwertyuiop[\",\"assigned_at\":\"2026-09-21T09:20:54.000000Z\",\"assignment_proceeded_at\":\"2026-09-21T09:22:26.000000Z\"},\"workflow_and_files_retained\":true,\"history_actor_name\":\"Administrator\",\"history_actor_role\":\"admin\"}', '10.0.20.100', '2026-09-21 09:23:15', '2026-09-21 09:23:15'),
(1461, 1, 'logout', NULL, NULL, 'Administrator signed out.', '{\"history_actor_name\":\"Administrator\",\"history_actor_role\":\"admin\"}', '10.0.20.100', '2026-09-21 09:23:19', '2026-09-21 09:23:19'),
(1462, 11, 'login', NULL, NULL, 'PM Manager signed in.', '{\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:23:22', '2026-09-21 09:23:22'),
(1463, 11, 'request_pm_reviewed', 'PropertyRequest', 202, 'GPM-20260921-0003 was assigned to Dial-A by PM Manager PM Manager.', '{\"previous_assignment_type\":\"in_house\",\"assignment_type\":\"dial_a\",\"previous_state\":{\"status\":\"on_going\",\"completed_at\":null,\"in_house_completed_at\":null},\"actor\":{\"assignment_by_id\":11,\"assignment_by_name\":\"PM Manager\",\"assignment_by_role\":\"pm_manager\"},\"saved_workflow_and_files_retained\":true,\"priority\":\"regular\",\"remarks\":\"qwertyuiop[\",\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:23:36', '2026-09-21 09:23:36'),
(1464, 11, 'request_assignment_proceeded', 'PropertyRequest', 202, 'GPM-20260921-0003 assignment was confirmed by PM Manager PM Manager.', '{\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:23:41', '2026-09-21 09:23:41'),
(1465, 11, 'logout', NULL, NULL, 'PM Manager signed out.', '{\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:23:56', '2026-09-21 09:23:56'),
(1466, 88, 'login', NULL, NULL, 'Dial-A signed in.', '{\"history_actor_name\":\"Dial-A\",\"history_actor_role\":\"dial_a\"}', '10.0.20.100', '2026-09-21 09:24:00', '2026-09-21 09:24:00'),
(1467, 88, 'logout', NULL, NULL, 'Dial-A signed out.', '{\"history_actor_name\":\"Dial-A\",\"history_actor_role\":\"dial_a\"}', '10.0.20.100', '2026-09-21 09:25:33', '2026-09-21 09:25:33'),
(1468, 11, 'login', NULL, NULL, 'PM Manager signed in.', '{\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:25:36', '2026-09-21 09:25:36'),
(1469, 11, 'logout', NULL, NULL, 'PM Manager signed out.', '{\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:26:47', '2026-09-21 09:26:47'),
(1470, 11, 'login', NULL, NULL, 'PM Manager signed in.', '{\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:27:17', '2026-09-21 09:27:17'),
(1471, 11, 'logout', NULL, NULL, 'PM Manager signed out.', '{\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:29:32', '2026-09-21 09:29:32'),
(1472, 25, 'login', NULL, NULL, 'Gets Wambangco signed in.', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 09:29:40', '2026-09-21 09:29:40'),
(1473, 25, 'logout', NULL, NULL, 'Gets Wambangco signed out.', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 09:32:02', '2026-09-21 09:32:02'),
(1474, 88, 'login', NULL, NULL, 'Dial-A signed in.', '{\"history_actor_name\":\"Dial-A\",\"history_actor_role\":\"dial_a\"}', '10.0.20.100', '2026-09-21 09:32:04', '2026-09-21 09:32:04'),
(1475, 88, 'logout', NULL, NULL, 'Dial-A signed out.', '{\"history_actor_name\":\"Dial-A\",\"history_actor_role\":\"dial_a\"}', '10.0.20.100', '2026-09-21 09:33:43', '2026-09-21 09:33:43'),
(1476, 25, 'login', NULL, NULL, 'Gets Wambangco signed in.', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 09:33:48', '2026-09-21 09:33:48'),
(1477, 25, 'logout', NULL, NULL, 'Gets Wambangco signed out.', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 09:33:51', '2026-09-21 09:33:51'),
(1478, 25, 'login', NULL, NULL, 'Gets Wambangco signed in.', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 09:37:28', '2026-09-21 09:37:28'),
(1479, 25, 'logout', NULL, NULL, 'Gets Wambangco signed out.', '{\"history_actor_name\":\"Gets Wambangco\",\"history_actor_role\":\"dealer\"}', '10.0.20.100', '2026-09-21 09:37:32', '2026-09-21 09:37:32'),
(1480, 88, 'login', NULL, NULL, 'Dial-A signed in.', '{\"history_actor_name\":\"Dial-A\",\"history_actor_role\":\"dial_a\"}', '10.0.20.100', '2026-09-21 09:37:35', '2026-09-21 09:37:35'),
(1481, 88, 'logout', NULL, NULL, 'Dial-A signed out.', '{\"history_actor_name\":\"Dial-A\",\"history_actor_role\":\"dial_a\"}', '10.0.20.100', '2026-09-21 09:37:40', '2026-09-21 09:37:40'),
(1482, 11, 'login', NULL, NULL, 'PM Manager signed in.', '{\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:37:43', '2026-09-21 09:37:43'),
(1483, 11, 'logout', NULL, NULL, 'PM Manager signed out.', '{\"history_actor_name\":\"PM Manager\",\"history_actor_role\":\"pm_manager\"}', '10.0.20.100', '2026-09-21 09:43:05', '2026-09-21 09:43:05'),
(1484, 11, 'login', NULL, NULL, 'PM Manager signed in.', NULL, '10.0.20.100', '2026-09-21 09:50:34', '2026-09-21 09:50:34'),
(1485, 11, 'logout', NULL, NULL, 'PM Manager signed out.', NULL, '10.0.20.100', '2026-09-21 09:50:47', '2026-09-21 09:50:47');

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
('gateway-property-management-system-cache-pms:notification-reminders', 'b:1;', 1789984296);

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
(196, 'default', '{\"uuid\":\"70f36a37-498c-460a-923a-09e74cf3449d\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"25d66027-60e6-54f1-a8ef-e52285d6ea14\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789628331,\"delay\":null}', 0, NULL, 1789628331, 1789628331),
(197, 'default', '{\"uuid\":\"d498dc08-6c21-4e50-8e77-5edec6ce1f42\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"78edcd71-40f2-5cf7-b34b-cbc81731b6d0\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789692528,\"delay\":null}', 0, NULL, 1789692528, 1789692528),
(198, 'default', '{\"uuid\":\"18628492-c4b8-43b7-a1c4-2a89fe74177e\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"8847b3e0-4abc-51d0-9e0a-f5ad63644019\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789693913,\"delay\":null}', 0, NULL, 1789693913, 1789693913),
(199, 'default', '{\"uuid\":\"3268a832-0a56-4c3a-9edc-5920c5d67523\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"fb99c583-451c-53e9-9839-6568443f8244\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789695060,\"delay\":null}', 0, NULL, 1789695060, 1789695060),
(200, 'default', '{\"uuid\":\"fcec873e-a3ba-49c8-bc19-d863b5ffd2ad\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"2c1e46eb-b1e1-5174-b8f1-85e2e7d9b60b\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789695064,\"delay\":null}', 0, NULL, 1789695064, 1789695064),
(201, 'default', '{\"uuid\":\"cb2b7171-ce45-4485-89a5-8045383d9c2e\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"e614cb94-b13e-5b16-bebc-03773c19736d\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789695068,\"delay\":null}', 0, NULL, 1789695068, 1789695068),
(202, 'default', '{\"uuid\":\"c1418796-02a3-45af-a0db-4cacfb2ffe47\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"7e4a0eac-f52d-5182-8fa0-90c6552eec01\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789695073,\"delay\":null}', 0, NULL, 1789695073, 1789695073),
(203, 'default', '{\"uuid\":\"aac0a8ce-25be-4f89-baa4-996adf8fad13\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"669271a1-13e2-50c4-bf43-0a90c410284b\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789695081,\"delay\":null}', 0, NULL, 1789695081, 1789695081),
(204, 'default', '{\"uuid\":\"da3d5733-791e-4065-be7c-a79834748fe8\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"94979781-f356-579c-bebd-66694d43b70c\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789695081,\"delay\":null}', 0, NULL, 1789695081, 1789695081),
(205, 'default', '{\"uuid\":\"9a410ceb-7653-417b-966f-482dc27af830\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"5b96f6c8-fd47-54a0-9222-d2779097fe60\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789695086,\"delay\":null}', 0, NULL, 1789695086, 1789695086),
(206, 'default', '{\"uuid\":\"b9580a19-a6eb-4c60-9477-2900d9eb05a8\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"30a91d2c-95e3-52e2-bc32-f018a12beff8\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789960670,\"delay\":null}', 0, NULL, 1789960670, 1789960670),
(207, 'default', '{\"uuid\":\"6a56a237-d828-4a6d-856b-cb3820c0a5a2\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"9959e70d-15b2-58fe-bb97-0dd9a85963f5\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789976457,\"delay\":null}', 0, NULL, 1789976457, 1789976457),
(208, 'default', '{\"uuid\":\"e842eeaf-4e05-49cb-8a7e-4f8fddc6a697\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"fff6932d-3115-5663-81ee-d7d188075d49\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789976495,\"delay\":null}', 0, NULL, 1789976495, 1789976495),
(209, 'default', '{\"uuid\":\"ae981710-24c7-4a31-a02c-c5cfe667f297\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"a45c8457-0cb4-5112-a34f-c84275dfd14f\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789976576,\"delay\":null}', 0, NULL, 1789976576, 1789976576),
(210, 'default', '{\"uuid\":\"131945e2-667a-43f1-a8ec-01daf4ed4e00\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"b258c3c8-c943-5a23-a220-10d855b647ba\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789976576,\"delay\":null}', 0, NULL, 1789976576, 1789976576),
(211, 'default', '{\"uuid\":\"b71bc394-6a05-4ad8-bc4b-290548a57381\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"527f8f64-0b58-554a-9781-02c852742898\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977378,\"delay\":null}', 0, NULL, 1789977378, 1789977378),
(212, 'default', '{\"uuid\":\"99f70125-a8d5-42e1-97aa-da3dc5eabdfe\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"4236d10b-a148-559f-ba3b-dd12683cd32c\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977406,\"delay\":null}', 0, NULL, 1789977406, 1789977406),
(213, 'default', '{\"uuid\":\"2cccd524-6d4f-422f-869d-95f8facab877\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"d728ded5-5baf-5280-9af2-ae338847b500\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977471,\"delay\":null}', 0, NULL, 1789977471, 1789977471),
(214, 'default', '{\"uuid\":\"0bfaa02d-7576-4839-b841-13f449eb5474\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"d38b1b14-ef35-5b81-b982-5a594460a9f3\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977479,\"delay\":null}', 0, NULL, 1789977479, 1789977479);
INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(215, 'default', '{\"uuid\":\"a36948e6-c0a9-4b51-af99-a6a049fdbc76\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"4e0d008d-7b7e-519e-aa88-883b3d3499dd\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977490,\"delay\":null}', 0, NULL, 1789977490, 1789977490),
(216, 'default', '{\"uuid\":\"a4273329-d3ff-42cd-8a6b-be2f6382575b\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"35ae09aa-8828-5790-99a1-3568ab631408\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977493,\"delay\":null}', 0, NULL, 1789977493, 1789977493),
(217, 'default', '{\"uuid\":\"6309286d-b985-474b-8997-0fc6d3651f20\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"317c1522-5b80-5862-968b-f683487061c6\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977505,\"delay\":null}', 0, NULL, 1789977505, 1789977505),
(218, 'default', '{\"uuid\":\"159bb37a-0a98-4bbc-889a-b004ab719ee1\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"3b93eae9-053f-561d-bbc2-78eaaa0c67b3\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977505,\"delay\":null}', 0, NULL, 1789977505, 1789977505),
(219, 'default', '{\"uuid\":\"3eec591a-59c5-4ee4-9196-8d2dd11f15f1\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"aaa5d1fb-5461-55a0-aab1-6df16e56c548\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977534,\"delay\":null}', 0, NULL, 1789977534, 1789977534),
(220, 'default', '{\"uuid\":\"54fd5e4c-23c2-494f-9155-810e8729947d\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"f3a8bc04-e7bb-5386-8688-65b868449564\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977541,\"delay\":null}', 0, NULL, 1789977541, 1789977541),
(221, 'default', '{\"uuid\":\"7b40ab89-2f77-4cbb-8d4b-f84cbd76cccf\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"8f1ce228-01d4-5799-92f5-c491fa5a522c\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977551,\"delay\":null}', 0, NULL, 1789977551, 1789977551),
(222, 'default', '{\"uuid\":\"067c5c9b-3832-40bd-a421-0344793bc2af\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"e201015c-6918-53b3-bc31-1ebb17ba6359\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977557,\"delay\":null}', 0, NULL, 1789977557, 1789977557),
(223, 'default', '{\"uuid\":\"13688868-cd49-4638-9e45-7ae7433c5e8c\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"1b7cdfb8-af1d-55ed-90f7-9ad261e7de41\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977565,\"delay\":null}', 0, NULL, 1789977565, 1789977565),
(224, 'default', '{\"uuid\":\"1ec98c7e-c5bb-4d90-89ec-5acfd4d09268\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"6dd65112-0422-5b1f-ac29-b59f4e2a0df9\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977566,\"delay\":null}', 0, NULL, 1789977566, 1789977566),
(225, 'default', '{\"uuid\":\"13da99d5-d2f1-4e2f-a291-6faa3e174069\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"0bf0db79-e9eb-5a6d-ac46-24c97ad617e6\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789977573,\"delay\":null}', 0, NULL, 1789977573, 1789977573),
(226, 'default', '{\"uuid\":\"3863c796-3107-4c6d-b8b1-1b8fcfae4b29\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"b3b5ffac-6890-5774-a040-dde2eee00934\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789982246,\"delay\":null}', 0, NULL, 1789982246, 1789982246),
(227, 'default', '{\"uuid\":\"c5b4dba4-5cab-480b-a784-c591a866e9bb\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"5a43ab9f-fb8a-56fb-baf9-4e6dd19ba9e4\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789982454,\"delay\":null}', 0, NULL, 1789982454, 1789982454),
(228, 'default', '{\"uuid\":\"e78c85ef-70e4-4ff9-96e3-7443d3252219\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"1296d255-6211-5781-a535-91efd7ced344\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789982546,\"delay\":null}', 0, NULL, 1789982546, 1789982546),
(229, 'default', '{\"uuid\":\"4866a463-f152-4a27-96f6-2280ec873518\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"871e3fe5-8662-5904-90f5-b9dd1dd8bec4\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789982595,\"delay\":null}', 0, NULL, 1789982595, 1789982595),
(230, 'default', '{\"uuid\":\"fc56f29b-1ea5-4af2-bac8-721a494960c7\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"b2b099a2-d9d3-5899-aa80-a6f1b68aef17\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789982595,\"delay\":null}', 0, NULL, 1789982595, 1789982595),
(231, 'default', '{\"uuid\":\"48f9db89-56e0-494d-bd6c-0c9dcf2f5f18\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"294731ac-5d84-5eb8-8bed-3ef871bc8285\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789982616,\"delay\":null}', 0, NULL, 1789982616, 1789982616),
(232, 'default', '{\"uuid\":\"c6abb5f4-68f1-4b77-bb8d-f1471daf3828\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:9;s:6:\\\"userId\\\";i:11;s:14:\\\"notificationId\\\";s:36:\\\"041dbecb-133c-51b8-9e45-f408e8004a51\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789982621,\"delay\":null}', 0, NULL, 1789982621, 1789982621),
(233, 'default', '{\"uuid\":\"e31c556e-db1e-4d6d-958a-ae22277d5cc1\",\"displayName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"30,120\",\"timeout\":30,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendBrowserPush\",\"command\":\"O:24:\\\"App\\\\Jobs\\\\SendBrowserPush\\\":6:{s:14:\\\"subscriptionId\\\";i:67;s:6:\\\"userId\\\";i:88;s:14:\\\"notificationId\\\";s:36:\\\"b9d6ab63-ce4f-526b-a8c8-6aaecd28db7c\\\";s:10:\\\"connection\\\";s:8:\\\"database\\\";s:5:\\\"queue\\\";s:7:\\\"default\\\";s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1789982621,\"delay\":null}', 0, NULL, 1789982621, 1789982621);

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
(22, '2026_09_16_220400_add_on_going_status_to_property_requests', 12),
(23, '2026_09_18_161500_add_priority_remarks_to_property_requests_table', 13),
(24, '2026_09_21_000001_add_pm_admin_role_to_users_table', 14),
(25, '2026_09_21_000002_add_in_house_request_workflow', 14),
(26, '2026_09_21_000003_add_pm_review_to_property_requests', 15),
(27, '2026_09_21_000004_add_assignment_confirmation_steps', 16);

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
('041dbecb-133c-51b8-9e45-f408e8004a51', 'request_workflow', 'App\\Models\\User', 11, 202, '{\"kind\":\"assignment_proceeded\",\"title\":\"Assignment confirmed\",\"message\":\"GPM-20260921-0003: Proceed confirmed for Dial-A. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', '2026-09-21 09:29:14', '2026-09-21 09:23:41', '2026-09-21 09:29:14'),
('08d19d6d-72af-501a-8e7c-31dc52237889', 'request_workflow', 'App\\Models\\User', 91, 202, '{\"kind\":\"assignment_changed\",\"title\":\"Assigned \\u2014 awaiting Proceed\",\"message\":\"GPM-20260921-0003: Assigned \\u2014 awaiting Proceed. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:20:54', '2026-09-21 09:20:54'),
('08d9e6f1-be5c-5ac2-8c68-21bc3cfff533', 'request_workflow', 'App\\Models\\User', 90, 202, '{\"kind\":\"assignment_changed\",\"title\":\"Assigned \\u2014 awaiting Proceed\",\"message\":\"GPM-20260921-0003: Assigned \\u2014 awaiting Proceed. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:23:36', '2026-09-21 09:23:36'),
('0bf0db79-e9eb-5a6d-ac46-24c97ad617e6', 'request_workflow', 'App\\Models\\User', 11, 200, '{\"kind\":\"request_completed\",\"title\":\"Request completed\",\"message\":\"GPM-20260921-0002: All stages are complete and Dial-A has confirmed completion.\"}', '2026-09-21 08:00:47', '2026-09-21 07:59:33', '2026-09-21 08:00:47'),
('1296d255-6211-5781-a535-91efd7ced344', 'request_workflow', 'App\\Models\\User', 11, 202, '{\"kind\":\"assignment_proceeded\",\"title\":\"Assignment confirmed\",\"message\":\"GPM-20260921-0003: Proceed confirmed for In house. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', '2026-09-21 09:29:14', '2026-09-21 09:22:26', '2026-09-21 09:29:14'),
('1371f2de-3ce1-54b3-9da0-693306aef97d', 'request_workflow', 'App\\Models\\User', 91, 200, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260921-0002: Service Report: Not scheduled \\u2192 Sep 21, 2026.\"}', NULL, '2026-09-21 07:59:25', '2026-09-21 07:59:25'),
('13df09c3-302f-55e7-852a-9d23c907182d', 'request_workflow', 'App\\Models\\User', 89, 200, '{\"kind\":\"assignment_changed\",\"title\":\"Request reassigned\",\"message\":\"GPM-20260921-0002: Assigned to Dial-A c\\/o PM Manager (PM Manager). Priority: Urgent. Remarks: qweqwewqrqwrqwtu\",\"assignment_type\":\"dial_a\"}', NULL, '2026-09-21 07:42:56', '2026-09-21 07:42:56'),
('14580cfe-9a58-5528-9b82-56ba24fa48e5', 'request_workflow', 'App\\Models\\User', 91, 200, '{\"kind\":\"assignment_changed\",\"title\":\"Request reassigned\",\"message\":\"GPM-20260921-0002: Assigned to Dial-A c\\/o PM Manager (PM Manager). Priority: Urgent. Remarks: qweqwewqrqwrqwtu\",\"assignment_type\":\"dial_a\"}', NULL, '2026-09-21 07:42:56', '2026-09-21 07:42:56'),
('15c0f1eb-7dbe-5948-9e65-633d8f0fe5fb', 'request_workflow', 'App\\Models\\User', 91, 200, '{\"kind\":\"stage_completed\",\"title\":\"Service Report completed\",\"message\":\"GPM-20260921-0002: Service Report completed.\",\"stage\":\"service_report\",\"request_completed\":false}', NULL, '2026-09-21 07:59:26', '2026-09-21 07:59:26'),
('174da203-c570-51c9-aefb-b5d5a7ae2cf4', 'request_workflow', 'App\\Models\\User', 25, 200, '{\"kind\":\"assignment_changed\",\"title\":\"Request reassigned\",\"message\":\"GPM-20260921-0002: Assigned to Dial-A c\\/o PM Manager (PM Manager). Priority: Urgent. Remarks: qweqwewqrqwrqwtu\",\"assignment_type\":\"dial_a\"}', '2026-09-21 08:18:50', '2026-09-21 07:42:56', '2026-09-21 08:18:50'),
('17658018-fd8e-5c89-864a-188a7b49be19', 'request_workflow', 'App\\Models\\User', 91, 200, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260921-0002: Inspection: Not scheduled \\u2192 Sep 20, 2026.\"}', NULL, '2026-09-21 07:58:54', '2026-09-21 07:58:54'),
('1b7cdfb8-af1d-55ed-90f7-9ad261e7de41', 'request_workflow', 'App\\Models\\User', 11, 200, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260921-0002: Service Report: Not scheduled \\u2192 Sep 21, 2026.\"}', '2026-09-21 08:00:47', '2026-09-21 07:59:25', '2026-09-21 08:00:47'),
('200b94e5-38b3-535b-98be-4a5b0c41de5b', 'request_workflow', 'App\\Models\\User', 25, 202, '{\"kind\":\"assignment_changed\",\"title\":\"Assigned \\u2014 awaiting Proceed\",\"message\":\"GPM-20260921-0003: Assigned \\u2014 awaiting Proceed. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', '2026-09-21 09:33:50', '2026-09-21 09:20:54', '2026-09-21 09:33:50'),
('245d2e70-30ac-5f19-891f-e38097720a8b', 'request_workflow', 'App\\Models\\User', 91, 200, '{\"kind\":\"stage_completed\",\"title\":\"Inspection completed\",\"message\":\"GPM-20260921-0002: Inspection completed.\",\"stage\":\"inspection\",\"request_completed\":false}', NULL, '2026-09-21 07:59:01', '2026-09-21 07:59:01'),
('25874ad0-86d5-5bd2-97ec-7b3afce33423', 'request_workflow', 'App\\Models\\User', 89, 202, '{\"kind\":\"assignment_changed\",\"title\":\"Assigned \\u2014 awaiting Proceed\",\"message\":\"GPM-20260921-0003: Assigned \\u2014 awaiting Proceed. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:20:54', '2026-09-21 09:20:54'),
('25ca6b09-b5af-58fa-867d-aa94bb1aec14', 'request_workflow', 'App\\Models\\User', 89, 200, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260921-0002: Work: Not scheduled \\u2192 Sep 20, 2026.\"}', NULL, '2026-09-21 07:59:11', '2026-09-21 07:59:11'),
('294731ac-5d84-5eb8-8bed-3ef871bc8285', 'request_workflow', 'App\\Models\\User', 11, 202, '{\"kind\":\"assignment_changed\",\"title\":\"Assigned \\u2014 awaiting Proceed\",\"message\":\"GPM-20260921-0003: Assigned \\u2014 awaiting Proceed. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', '2026-09-21 09:29:14', '2026-09-21 09:23:36', '2026-09-21 09:29:14'),
('3016f86d-7e7f-5010-a3ae-500fcaca6355', 'request_workflow', 'App\\Models\\User', 1, 202, '{\"kind\":\"assignment_proceeded\",\"title\":\"Assignment confirmed\",\"message\":\"GPM-20260921-0003: Proceed confirmed for In house. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:22:26', '2026-09-21 09:22:26'),
('30a91d2c-95e3-52e2-bc32-f018a12beff8', 'request_workflow', 'App\\Models\\User', 88, 199, '{\"kind\":\"new_request\",\"title\":\"New request\",\"message\":\"GPM-20260921-0001: General Repairs requested by Gets Wambangco (Makati).\"}', '2026-09-21 06:10:54', '2026-09-21 03:17:50', '2026-09-21 06:10:54'),
('31a18ad1-5327-540b-a8fc-98eb46d686fb', 'request_workflow', 'App\\Models\\User', 90, 202, '{\"kind\":\"assignment_changed\",\"title\":\"Assigned \\u2014 awaiting Proceed\",\"message\":\"GPM-20260921-0003: Assigned \\u2014 awaiting Proceed. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:20:54', '2026-09-21 09:20:54'),
('39d04b91-5862-557b-be32-a6c3d4403e58', 'request_workflow', 'App\\Models\\User', 89, 202, '{\"kind\":\"request_assignment_undone\",\"title\":\"Assignment undone by administrator\",\"message\":\"GPM-20260921-0003: Administrator Administrator undid the assignment. The request is awaiting PM review again. Previous workflow steps and uploaded files were kept.\",\"stage\":\"assignment\",\"history\":true,\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:23:15', '2026-09-21 09:23:15'),
('3ca11097-4390-5632-b784-66344d6a1bbe', 'request_workflow', 'App\\Models\\User', 25, 200, '{\"kind\":\"stage_completed\",\"title\":\"Work completed\",\"message\":\"GPM-20260921-0002: Work completed.\",\"stage\":\"work_order\",\"request_completed\":false}', '2026-09-21 08:18:50', '2026-09-21 07:59:17', '2026-09-21 08:18:50'),
('4107bb20-e3f7-507d-b225-a5a32f49fc80', 'request_workflow', 'App\\Models\\User', 25, 200, '{\"kind\":\"request_completed\",\"title\":\"Request completed\",\"message\":\"GPM-20260921-0002: All stages are complete and Dial-A has confirmed completion.\"}', '2026-09-21 08:18:50', '2026-09-21 07:59:33', '2026-09-21 08:18:50'),
('41ea4367-eed0-59d6-bea4-ecbe9cd9e066', 'request_workflow', 'App\\Models\\User', 89, 200, '{\"kind\":\"new_request\",\"title\":\"PM review required\",\"message\":\"GPM-20260921-0002: General Repairs requested by Gets Wambangco (Makati).\"}', NULL, '2026-09-21 07:40:57', '2026-09-21 07:40:57'),
('4550a594-6a8c-5ffe-b31b-9b178a2eeadb', 'request_workflow', 'App\\Models\\User', 89, 200, '{\"kind\":\"stage_completed\",\"title\":\"Service Report completed\",\"message\":\"GPM-20260921-0002: Service Report completed.\",\"stage\":\"service_report\",\"request_completed\":false}', NULL, '2026-09-21 07:59:26', '2026-09-21 07:59:26'),
('45974ad1-11bb-51f8-90c6-0f85a419b426', 'request_workflow', 'App\\Models\\User', 91, 200, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260921-0002: Work: Not scheduled \\u2192 Sep 20, 2026.\"}', NULL, '2026-09-21 07:59:11', '2026-09-21 07:59:11'),
('50fe6ae7-473a-5bcf-9f96-d27edece48df', 'request_workflow', 'App\\Models\\User', 25, 202, '{\"kind\":\"assignment_proceeded\",\"title\":\"Assignment confirmed\",\"message\":\"GPM-20260921-0003: Proceed confirmed for In house. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', '2026-09-21 09:33:50', '2026-09-21 09:22:26', '2026-09-21 09:33:50'),
('5a43ab9f-fb8a-56fb-baf9-4e6dd19ba9e4', 'request_workflow', 'App\\Models\\User', 11, 202, '{\"kind\":\"assignment_changed\",\"title\":\"Assigned \\u2014 awaiting Proceed\",\"message\":\"GPM-20260921-0003: Assigned \\u2014 awaiting Proceed. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', '2026-09-21 09:29:14', '2026-09-21 09:20:54', '2026-09-21 09:29:14'),
('5a9800be-0727-5b1d-a831-adc69c3a9031', 'request_workflow', 'App\\Models\\User', 90, 202, '{\"kind\":\"assignment_proceeded\",\"title\":\"Assignment confirmed\",\"message\":\"GPM-20260921-0003: Proceed confirmed for In house. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:22:26', '2026-09-21 09:22:26'),
('5f55c5b6-c1b9-5624-b946-732ec2ca98ec', 'request_workflow', 'App\\Models\\User', 91, 202, '{\"kind\":\"assignment_proceeded\",\"title\":\"Assignment confirmed\",\"message\":\"GPM-20260921-0003: Proceed confirmed for Dial-A. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:23:41', '2026-09-21 09:23:41'),
('6d17e5f8-a20d-52f2-b281-581493ff6032', 'request_workflow', 'App\\Models\\User', 89, 200, '{\"kind\":\"stage_completed\",\"title\":\"Work completed\",\"message\":\"GPM-20260921-0002: Work completed.\",\"stage\":\"work_order\",\"request_completed\":false}', NULL, '2026-09-21 07:59:17', '2026-09-21 07:59:17'),
('6dd65112-0422-5b1f-ac29-b59f4e2a0df9', 'request_workflow', 'App\\Models\\User', 11, 200, '{\"kind\":\"stage_completed\",\"title\":\"Service Report completed\",\"message\":\"GPM-20260921-0002: Service Report completed.\",\"stage\":\"service_report\",\"request_completed\":false}', '2026-09-21 08:00:47', '2026-09-21 07:59:26', '2026-09-21 08:00:47'),
('733da59e-6356-5c79-8784-6f4a37d7937e', 'request_workflow', 'App\\Models\\User', 91, 200, '{\"kind\":\"request_completed\",\"title\":\"Request completed\",\"message\":\"GPM-20260921-0002: All stages are complete and Dial-A has confirmed completion.\"}', NULL, '2026-09-21 07:59:33', '2026-09-21 07:59:33'),
('77d14d2e-d71a-59a1-a8ec-ae9489e00491', 'request_workflow', 'App\\Models\\User', 89, 202, '{\"kind\":\"new_request\",\"title\":\"PM review required\",\"message\":\"GPM-20260921-0003: General Repairs requested by Gets Wambangco (Makati).\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:17:26', '2026-09-21 09:17:26'),
('7948f95b-4e93-50a7-8263-e54f4f189f6a', 'request_workflow', 'App\\Models\\User', 91, 202, '{\"kind\":\"assignment_changed\",\"title\":\"Assigned \\u2014 awaiting Proceed\",\"message\":\"GPM-20260921-0003: Assigned \\u2014 awaiting Proceed. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:23:36', '2026-09-21 09:23:36'),
('7adbf0da-0f30-5e6a-b8d6-285482a488eb', 'request_workflow', 'App\\Models\\User', 1, 202, '{\"kind\":\"assignment_proceeded\",\"title\":\"Assignment confirmed\",\"message\":\"GPM-20260921-0003: Proceed confirmed for Dial-A. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:23:41', '2026-09-21 09:23:41'),
('7bcb502b-374b-5bbf-bff8-071b07420b02', 'request_workflow', 'App\\Models\\User', 89, 200, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260921-0002: Inspection: Not scheduled \\u2192 Sep 20, 2026.\"}', NULL, '2026-09-21 07:58:54', '2026-09-21 07:58:54'),
('82724655-353c-5a94-b47e-18c2e792c696', 'request_workflow', 'App\\Models\\User', 90, 202, '{\"kind\":\"request_assignment_undone\",\"title\":\"Assignment undone by administrator\",\"message\":\"GPM-20260921-0003: Administrator Administrator undid the assignment. The request is awaiting PM review again. Previous workflow steps and uploaded files were kept.\",\"stage\":\"assignment\",\"history\":true,\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:23:15', '2026-09-21 09:23:15'),
('871e3fe5-8662-5904-90f5-b9dd1dd8bec4', 'request_workflow', 'App\\Models\\User', 11, 202, '{\"kind\":\"request_assignment_undone\",\"title\":\"Assignment undone by administrator\",\"message\":\"GPM-20260921-0003: Administrator Administrator undid the assignment. The request is awaiting PM review again. Previous workflow steps and uploaded files were kept.\",\"stage\":\"assignment\",\"history\":true,\"reference_no\":\"GPM-20260921-0003\"}', '2026-09-21 09:29:14', '2026-09-21 09:23:15', '2026-09-21 09:29:14'),
('8e87d421-b19e-5656-a3d8-3b6c06403fe8', 'request_workflow', 'App\\Models\\User', 1, 202, '{\"kind\":\"new_request\",\"title\":\"PM review required\",\"message\":\"GPM-20260921-0003: General Repairs requested by Gets Wambangco (Makati).\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:17:25', '2026-09-21 09:17:25'),
('8f1ce228-01d4-5799-92f5-c491fa5a522c', 'request_workflow', 'App\\Models\\User', 11, 200, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260921-0002: Work: Not scheduled \\u2192 Sep 20, 2026.\"}', '2026-09-21 08:00:47', '2026-09-21 07:59:11', '2026-09-21 08:00:47'),
('901008b0-03d7-5557-9923-fc661c25b5e2', 'request_workflow', 'App\\Models\\User', 25, 200, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260921-0002: Service Report: Not scheduled \\u2192 Sep 21, 2026.\"}', '2026-09-21 08:18:50', '2026-09-21 07:59:25', '2026-09-21 08:18:50'),
('935327e8-5d57-5336-b02a-fb2a130610aa', 'request_workflow', 'App\\Models\\User', 25, 200, '{\"kind\":\"assignment_changed\",\"title\":\"PM review completed\",\"message\":\"GPM-20260921-0002: Assigned to In house c\\/o PM Manager (PM Manager). Priority: Urgent. Remarks: qweqwewqrqwrqwtu\",\"assignment_type\":\"in_house\"}', '2026-09-21 08:18:50', '2026-09-21 07:41:35', '2026-09-21 08:18:50'),
('98deffa2-a1fb-5db4-9253-542f4ad9ba17', 'request_workflow', 'App\\Models\\User', 25, 200, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260921-0002: Work: Not scheduled \\u2192 Sep 20, 2026.\"}', '2026-09-21 08:18:50', '2026-09-21 07:59:11', '2026-09-21 08:18:50'),
('9959e70d-15b2-58fe-bb97-0dd9a85963f5', 'request_workflow', 'App\\Models\\User', 11, 200, '{\"kind\":\"new_request\",\"title\":\"PM review required\",\"message\":\"GPM-20260921-0002: General Repairs requested by Gets Wambangco (Makati).\"}', '2026-09-21 08:00:47', '2026-09-21 07:40:57', '2026-09-21 08:00:47'),
('a45c8457-0cb4-5112-a34f-c84275dfd14f', 'request_workflow', 'App\\Models\\User', 11, 200, '{\"kind\":\"assignment_changed\",\"title\":\"Request reassigned\",\"message\":\"GPM-20260921-0002: Assigned to Dial-A c\\/o PM Manager (PM Manager). Priority: Urgent. Remarks: qweqwewqrqwrqwtu\",\"assignment_type\":\"dial_a\"}', '2026-09-21 08:00:47', '2026-09-21 07:42:56', '2026-09-21 08:00:47'),
('a71957c6-7d4f-58ad-9292-7b8e1285d73c', 'request_workflow', 'App\\Models\\User', 25, 200, '{\"kind\":\"stage_completed\",\"title\":\"Inspection completed\",\"message\":\"GPM-20260921-0002: Inspection completed.\",\"stage\":\"inspection\",\"request_completed\":false}', '2026-09-21 08:18:50', '2026-09-21 07:59:01', '2026-09-21 08:18:50'),
('aaa5d1fb-5461-55a0-aab1-6df16e56c548', 'request_workflow', 'App\\Models\\User', 11, 200, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260921-0002: Inspection: Not scheduled \\u2192 Sep 20, 2026.\"}', '2026-09-21 08:00:47', '2026-09-21 07:58:54', '2026-09-21 08:00:47'),
('b258c3c8-c943-5a23-a220-10d855b647ba', 'request_workflow', 'App\\Models\\User', 88, 200, '{\"kind\":\"assignment_changed\",\"title\":\"Request reassigned\",\"message\":\"GPM-20260921-0002: Assigned to Dial-A c\\/o PM Manager (PM Manager). Priority: Urgent. Remarks: qweqwewqrqwrqwtu\",\"assignment_type\":\"dial_a\"}', '2026-09-21 08:50:09', '2026-09-21 07:42:56', '2026-09-21 08:50:09'),
('b2b099a2-d9d3-5899-aa80-a6f1b68aef17', 'request_workflow', 'App\\Models\\User', 88, 202, '{\"kind\":\"request_assignment_undone\",\"title\":\"Assignment undone by administrator\",\"message\":\"GPM-20260921-0003: Administrator Administrator undid the assignment. The request is awaiting PM review again. Previous workflow steps and uploaded files were kept.\",\"stage\":\"assignment\",\"history\":true,\"reference_no\":\"GPM-20260921-0003\"}', '2026-09-21 09:32:40', '2026-09-21 09:23:15', '2026-09-21 09:32:40'),
('b3b5ffac-6890-5774-a040-dde2eee00934', 'request_workflow', 'App\\Models\\User', 11, 202, '{\"kind\":\"new_request\",\"title\":\"PM review required\",\"message\":\"GPM-20260921-0003: General Repairs requested by Gets Wambangco (Makati).\",\"reference_no\":\"GPM-20260921-0003\"}', '2026-09-21 09:29:14', '2026-09-21 09:17:25', '2026-09-21 09:29:14'),
('b50c1bcd-d114-5b36-8502-ed1d5f4cc91f', 'request_workflow', 'App\\Models\\User', 89, 200, '{\"kind\":\"assignment_changed\",\"title\":\"PM review completed\",\"message\":\"GPM-20260921-0002: Assigned to In house c\\/o PM Manager (PM Manager). Priority: Urgent. Remarks: qweqwewqrqwrqwtu\",\"assignment_type\":\"in_house\"}', NULL, '2026-09-21 07:41:35', '2026-09-21 07:41:35'),
('b50cf250-448e-5f11-85c6-bb5f21bb6f39', 'request_workflow', 'App\\Models\\User', 89, 200, '{\"kind\":\"stage_completed\",\"title\":\"Inspection completed\",\"message\":\"GPM-20260921-0002: Inspection completed.\",\"stage\":\"inspection\",\"request_completed\":false}', NULL, '2026-09-21 07:59:01', '2026-09-21 07:59:01'),
('b510e244-49ec-535b-bfd0-d35cd404a446', 'request_workflow', 'App\\Models\\User', 91, 200, '{\"kind\":\"stage_completed\",\"title\":\"Work completed\",\"message\":\"GPM-20260921-0002: Work completed.\",\"stage\":\"work_order\",\"request_completed\":false}', NULL, '2026-09-21 07:59:17', '2026-09-21 07:59:17'),
('b627fb46-56d0-5e5e-bdca-2e293067f95f', 'request_workflow', 'App\\Models\\User', 89, 202, '{\"kind\":\"assignment_changed\",\"title\":\"Assigned \\u2014 awaiting Proceed\",\"message\":\"GPM-20260921-0003: Assigned \\u2014 awaiting Proceed. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:23:36', '2026-09-21 09:23:36'),
('b9b30014-93a5-5aaa-883a-4f24aa05560a', 'request_workflow', 'App\\Models\\User', 25, 202, '{\"kind\":\"assignment_proceeded\",\"title\":\"Assignment confirmed\",\"message\":\"GPM-20260921-0003: Proceed confirmed for Dial-A. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', '2026-09-21 09:33:50', '2026-09-21 09:23:41', '2026-09-21 09:33:50'),
('b9d6ab63-ce4f-526b-a8c8-6aaecd28db7c', 'request_workflow', 'App\\Models\\User', 88, 202, '{\"kind\":\"assignment_proceeded\",\"title\":\"Assignment confirmed\",\"message\":\"GPM-20260921-0003: Proceed confirmed for Dial-A. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', '2026-09-21 09:32:40', '2026-09-21 09:23:41', '2026-09-21 09:32:40'),
('bd6f286d-f69c-5a88-b951-5d85a87fb98a', 'request_workflow', 'App\\Models\\User', 1, 202, '{\"kind\":\"assignment_changed\",\"title\":\"Assigned \\u2014 awaiting Proceed\",\"message\":\"GPM-20260921-0003: Assigned \\u2014 awaiting Proceed. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:23:36', '2026-09-21 09:23:36'),
('bf8ac315-62c9-5ae9-8c59-0644b3bd30ea', 'request_workflow', 'App\\Models\\User', 25, 200, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260921-0002: Inspection: Not scheduled \\u2192 Sep 20, 2026.\"}', '2026-09-21 08:18:50', '2026-09-21 07:58:54', '2026-09-21 08:18:50'),
('c6ce3db9-b7e3-53d8-889f-0d4073046ec1', 'request_workflow', 'App\\Models\\User', 25, 200, '{\"kind\":\"stage_completed\",\"title\":\"Service Report completed\",\"message\":\"GPM-20260921-0002: Service Report completed.\",\"stage\":\"service_report\",\"request_completed\":false}', '2026-09-21 08:18:50', '2026-09-21 07:59:26', '2026-09-21 08:18:50'),
('c6db964b-7247-583f-80bc-4c6ff7674bf6', 'request_workflow', 'App\\Models\\User', 25, 202, '{\"kind\":\"request_assignment_undone\",\"title\":\"Assignment undone by administrator\",\"message\":\"GPM-20260921-0003: Administrator Administrator undid the assignment. The request is awaiting PM review again. Previous workflow steps and uploaded files were kept.\",\"stage\":\"assignment\",\"history\":true,\"reference_no\":\"GPM-20260921-0003\"}', '2026-09-21 09:33:50', '2026-09-21 09:23:15', '2026-09-21 09:33:50'),
('cdf77fa8-22df-5a1c-aac0-d3d47f9d65f5', 'request_workflow', 'App\\Models\\User', 90, 202, '{\"kind\":\"new_request\",\"title\":\"PM review required\",\"message\":\"GPM-20260921-0003: General Repairs requested by Gets Wambangco (Makati).\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:17:26', '2026-09-21 09:17:26'),
('cfef7378-5e24-5571-bb85-19d45cca95ae', 'request_workflow', 'App\\Models\\User', 91, 200, '{\"kind\":\"assignment_changed\",\"title\":\"PM review completed\",\"message\":\"GPM-20260921-0002: Assigned to In house c\\/o PM Manager (PM Manager). Priority: Urgent. Remarks: qweqwewqrqwrqwtu\",\"assignment_type\":\"in_house\"}', NULL, '2026-09-21 07:41:35', '2026-09-21 07:41:35'),
('d35de74a-aabd-53d2-b4a7-8f17a1b4fee7', 'request_workflow', 'App\\Models\\User', 89, 202, '{\"kind\":\"assignment_proceeded\",\"title\":\"Assignment confirmed\",\"message\":\"GPM-20260921-0003: Proceed confirmed for Dial-A. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:23:41', '2026-09-21 09:23:41'),
('d8307448-51eb-5b20-b958-a5bb94f2d34c', 'request_workflow', 'App\\Models\\User', 91, 202, '{\"kind\":\"request_assignment_undone\",\"title\":\"Assignment undone by administrator\",\"message\":\"GPM-20260921-0003: Administrator Administrator undid the assignment. The request is awaiting PM review again. Previous workflow steps and uploaded files were kept.\",\"stage\":\"assignment\",\"history\":true,\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:23:15', '2026-09-21 09:23:15'),
('d8523ab6-9060-5d56-8d5c-7d59bca8e442', 'request_workflow', 'App\\Models\\User', 89, 200, '{\"kind\":\"schedule_changed\",\"title\":\"Schedule changed\",\"message\":\"GPM-20260921-0002: Service Report: Not scheduled \\u2192 Sep 21, 2026.\"}', NULL, '2026-09-21 07:59:25', '2026-09-21 07:59:25'),
('da1b2498-b444-5093-abd7-439e67bae0b0', 'request_workflow', 'App\\Models\\User', 1, 202, '{\"kind\":\"assignment_changed\",\"title\":\"Assigned \\u2014 awaiting Proceed\",\"message\":\"GPM-20260921-0003: Assigned \\u2014 awaiting Proceed. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:20:54', '2026-09-21 09:20:54'),
('da2595ff-6070-5ce8-8c27-156c61fe2aba', 'request_workflow', 'App\\Models\\User', 90, 202, '{\"kind\":\"assignment_proceeded\",\"title\":\"Assignment confirmed\",\"message\":\"GPM-20260921-0003: Proceed confirmed for Dial-A. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:23:41', '2026-09-21 09:23:41'),
('e201015c-6918-53b3-bc31-1ebb17ba6359', 'request_workflow', 'App\\Models\\User', 11, 200, '{\"kind\":\"stage_completed\",\"title\":\"Work completed\",\"message\":\"GPM-20260921-0002: Work completed.\",\"stage\":\"work_order\",\"request_completed\":false}', '2026-09-21 08:00:47', '2026-09-21 07:59:17', '2026-09-21 08:00:47'),
('e2680572-b1e9-56d5-8f79-ab90f9442587', 'request_workflow', 'App\\Models\\User', 1, 202, '{\"kind\":\"request_assignment_undone\",\"title\":\"Assignment undone by administrator\",\"message\":\"GPM-20260921-0003: Administrator Administrator undid the assignment. The request is awaiting PM review again. Previous workflow steps and uploaded files were kept.\",\"stage\":\"assignment\",\"history\":true,\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:23:15', '2026-09-21 09:23:15'),
('ec0c9a40-6d2f-5d3f-b983-d70c609f48d0', 'request_workflow', 'App\\Models\\User', 91, 202, '{\"kind\":\"assignment_proceeded\",\"title\":\"Assignment confirmed\",\"message\":\"GPM-20260921-0003: Proceed confirmed for In house. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:22:26', '2026-09-21 09:22:26'),
('ec37fc68-d8f9-51d8-aac3-79c2192de225', 'request_workflow', 'App\\Models\\User', 91, 200, '{\"kind\":\"new_request\",\"title\":\"PM review required\",\"message\":\"GPM-20260921-0002: General Repairs requested by Gets Wambangco (Makati).\"}', NULL, '2026-09-21 07:40:57', '2026-09-21 07:40:57'),
('f00f2890-bbfd-5612-b2d8-4a20b2248cb0', 'request_workflow', 'App\\Models\\User', 25, 202, '{\"kind\":\"assignment_changed\",\"title\":\"Assigned \\u2014 awaiting Proceed\",\"message\":\"GPM-20260921-0003: Assigned \\u2014 awaiting Proceed. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', '2026-09-21 09:33:50', '2026-09-21 09:23:36', '2026-09-21 09:33:50'),
('f3a8bc04-e7bb-5386-8688-65b868449564', 'request_workflow', 'App\\Models\\User', 11, 200, '{\"kind\":\"stage_completed\",\"title\":\"Inspection completed\",\"message\":\"GPM-20260921-0002: Inspection completed.\",\"stage\":\"inspection\",\"request_completed\":false}', '2026-09-21 08:00:47', '2026-09-21 07:59:01', '2026-09-21 08:00:47'),
('f6da0218-2618-59d4-9f7b-d02cb0602451', 'request_workflow', 'App\\Models\\User', 89, 200, '{\"kind\":\"request_completed\",\"title\":\"Request completed\",\"message\":\"GPM-20260921-0002: All stages are complete and Dial-A has confirmed completion.\"}', NULL, '2026-09-21 07:59:33', '2026-09-21 07:59:33'),
('f7f8e45d-9a10-57f7-a8a7-cfd9e1850845', 'request_workflow', 'App\\Models\\User', 91, 202, '{\"kind\":\"new_request\",\"title\":\"PM review required\",\"message\":\"GPM-20260921-0003: General Repairs requested by Gets Wambangco (Makati).\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:17:26', '2026-09-21 09:17:26'),
('f8d65603-2f25-51fb-9bb2-a22b418bcdcd', 'request_workflow', 'App\\Models\\User', 89, 202, '{\"kind\":\"assignment_proceeded\",\"title\":\"Assignment confirmed\",\"message\":\"GPM-20260921-0003: Proceed confirmed for In house. Priority: Regular. Remarks: qwertyuiop[\",\"reference_no\":\"GPM-20260921-0003\"}', NULL, '2026-09-21 09:22:26', '2026-09-21 09:22:26'),
('fff6932d-3115-5663-81ee-d7d188075d49', 'request_workflow', 'App\\Models\\User', 11, 200, '{\"kind\":\"assignment_changed\",\"title\":\"PM review completed\",\"message\":\"GPM-20260921-0002: Assigned to In house c\\/o PM Manager (PM Manager). Priority: Urgent. Remarks: qweqwewqrqwrqwtu\",\"assignment_type\":\"in_house\"}', '2026-09-21 08:00:47', '2026-09-21 07:41:35', '2026-09-21 08:00:47');

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
  `priority_remarks` text DEFAULT NULL,
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
  `service_report_date` date DEFAULT NULL,
  `assignment_type` varchar(20) NOT NULL DEFAULT 'dial_a',
  `assignment_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assignment_by_name` varchar(255) DEFAULT NULL,
  `assignment_by_role` varchar(30) DEFAULT NULL,
  `in_house_inspection_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `in_house_inspection_by_name` varchar(255) DEFAULT NULL,
  `in_house_inspection_by_role` varchar(30) DEFAULT NULL,
  `in_house_work_order_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `in_house_work_order_by_name` varchar(255) DEFAULT NULL,
  `in_house_work_order_by_role` varchar(30) DEFAULT NULL,
  `in_house_completion_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `in_house_completion_by_name` varchar(255) DEFAULT NULL,
  `in_house_completion_by_role` varchar(30) DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `in_house_requested_at` timestamp NULL DEFAULT NULL,
  `in_house_work_order` text DEFAULT NULL,
  `in_house_work_order_at` timestamp NULL DEFAULT NULL,
  `in_house_completed_at` timestamp NULL DEFAULT NULL,
  `dial_a_status` varchar(30) DEFAULT NULL,
  `dial_a_completed_at` timestamp NULL DEFAULT NULL,
  `pm_reviewed_at` timestamp NULL DEFAULT NULL,
  `pm_reviewed_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pm_reviewed_by_name` varchar(255) DEFAULT NULL,
  `pm_reviewed_by_role` varchar(30) DEFAULT NULL,
  `assignment_phase` varchar(20) NOT NULL DEFAULT 'proceeded',
  `assignment_proceeded_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_requests`
--

INSERT INTO `property_requests` (`id`, `reference_no`, `dealer_id`, `dealer_name`, `submitted_by`, `assigned_support_id`, `assigned_manager_id`, `submitter_name`, `designation`, `branch`, `area`, `request_type`, `priority`, `priority_remarks`, `description`, `request_date`, `due_date`, `approved_date`, `inspection_date`, `inspection_end_date`, `inspection_start_time`, `inspection_end_time`, `representative_1`, `representative_2`, `representative_3`, `inspection_completed_at`, `work_order_start_date`, `work_order_start_time`, `work_order_end_date`, `work_order_end_time`, `work_order_representatives`, `work_order_completed_at`, `service_report_completed_at`, `completion_notified_at`, `status`, `completed_at`, `created_at`, `updated_at`, `service_report_date`, `assignment_type`, `assignment_by_id`, `assignment_by_name`, `assignment_by_role`, `in_house_inspection_by_id`, `in_house_inspection_by_name`, `in_house_inspection_by_role`, `in_house_work_order_by_id`, `in_house_work_order_by_name`, `in_house_work_order_by_role`, `in_house_completion_by_id`, `in_house_completion_by_name`, `in_house_completion_by_role`, `assigned_at`, `in_house_requested_at`, `in_house_work_order`, `in_house_work_order_at`, `in_house_completed_at`, `dial_a_status`, `dial_a_completed_at`, `pm_reviewed_at`, `pm_reviewed_by_id`, `pm_reviewed_by_name`, `pm_reviewed_by_role`, `assignment_phase`, `assignment_proceeded_at`) VALUES
(199, 'GPM-20260921-0001', 14, 'Hyundai', 25, 88, NULL, 'Gets Wambangco', 'General Manager', 'Makati', 'Metro Manila', 'General Repairs', 'urgent', NULL, 'wqertyuiop[poiuytwqwertyuiop[iuytrewqqtrertyuiol;', '2026-09-21', '2026-09-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, '2026-09-21 03:17:50', '2026-09-21 03:17:50', NULL, 'dial_a', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'proceeded', NULL),
(200, 'GPM-20260921-0002', 14, 'Hyundai', 25, 88, NULL, 'Gets Wambangco', 'General Manager', 'Makati', 'Metro Manila', 'General Repairs', 'urgent', 'qweqwewqrqwrqwtu', 'qwertyupwqqwertyuiowqetyui', '2026-09-21', '2026-09-25', NULL, '2026-09-20', '2026-09-21', '15:58:00', '15:59:00', 'Dial-A', NULL, NULL, '2026-09-21 07:59:01', '2026-09-20', '15:59:11', '2026-09-21', '15:59:17', '[\"Dial-A\"]', '2026-09-21 07:59:17', '2026-09-21 07:59:26', '2026-09-21 07:59:26', 'completed', '2026-09-21 07:59:33', '2026-09-21 07:40:55', '2026-09-21 07:59:33', '2026-09-21', 'dial_a', 11, 'PM Manager', 'pm_manager', 11, 'PM Manager', 'pm_manager', NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-21 07:42:56', '2026-09-21 07:41:35', NULL, NULL, NULL, 'pending', NULL, '2026-09-21 07:41:35', 11, 'PM Manager', 'pm_manager', 'proceeded', NULL),
(202, 'GPM-20260921-0003', 14, 'Hyundai', 25, 88, NULL, 'Gets Wambangco', 'General Manager', 'Makati', 'Metro Manila', 'General Repairs', 'regular', 'qwertyuiop[', 'qweqeqwrtyyiuyweyuiopuertyioup[iuytrewy', '2026-09-21', '2026-09-25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, '2026-09-21 09:17:25', '2026-09-21 09:23:41', NULL, 'dial_a', 11, 'PM Manager', 'pm_manager', 11, 'PM Manager', 'pm_manager', NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-21 09:23:36', '2026-09-21 09:20:54', NULL, NULL, NULL, 'pending', NULL, '2026-09-21 09:23:36', 11, 'PM Manager', 'pm_manager', 'proceeded', '2026-09-21 09:23:41');

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `uploaded_by_id` bigint(20) UNSIGNED DEFAULT NULL,
  `uploaded_by_name` varchar(255) DEFAULT NULL,
  `uploaded_by_role` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_attachments`
--

INSERT INTO `request_attachments` (`id`, `property_request_id`, `category`, `path`, `original_name`, `mime_type`, `size`, `created_at`, `updated_at`, `uploaded_by_id`, `uploaded_by_name`, `uploaded_by_role`) VALUES
(428, 200, 'inspection', 'request-attachments/inspection/edfnUawBU61ycETzTNHwnvmKnC8CIC7fj74A7w1r.xlsx', 'SSID-FORMAT (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 11119, '2026-09-21 07:59:01', '2026-09-21 07:59:01', NULL, NULL, NULL),
(429, 200, 'work_order', 'request-attachments/work_order/w5jjAENacuVAKuejyDoPcl5HoGaFgFCAJokSCHxZ.png', 'Gateway Combined 5s Audit App Mockup.png', 'image/png', 1579492, '2026-09-21 07:59:17', '2026-09-21 07:59:17', NULL, NULL, NULL),
(430, 200, 'service_report', 'request-attachments/service_report/M6q8oh07VGRfAmPOjdmpaw7fLrj21Efka6BGl5n3.xlsx', 'SSID-FORMAT (1).xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 11119, '2026-09-21 07:59:26', '2026-09-21 07:59:26', NULL, NULL, NULL);

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
('FLfoWjVQDBKPMTP0RA0UOtMuv35lRU34G7bHavVT', NULL, '10.0.20.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMnlrSm9yOWxwUUswTjhxZ1FJekt4VkdWUGlTa283Z0N2MTNJY2YyayI7czo2OiJzdGF0dXMiO3M6MDoiIjtzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MTp7aTowO3M6Njoic3RhdHVzIjt9fX0=', 1789982972),
('pP362dj0Fy19jGzzf8LRCjMU3H088MRfx15xnyDS', NULL, '10.0.20.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVUxvY0J5QU92bEF6ZmpYZmJ6RWNoSUNpaXl6Ujh2bjdYem1OUE04RyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMC4wLjIwLjEwMDo4MDAyL2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9fQ==', 1789984247),
('VWaC7fsxxgEyoKVpP2zwfpXsJN84mF5VD4BwuAsD', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT; Windows NT 10.0; en-PH) WindowsPowerShell/5.1.26100.9444', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidFRUYUk1Wks5N0J1UGs4d0ZzMzZUbmVVU0E5Z2FaNWY0eDlwaVh2cyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHA6Ly9sb2NhbGhvc3QvSlItZmlsZXMvUE1TL3B1YmxpYy9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1789977298);

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
  `role` enum('admin','dial_lead','pm_support','handyman','dial_a','pm_manager','pm_admin','representative','dealer') NOT NULL DEFAULT 'dealer',
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
(1, NULL, 'Administrator', 'IT.admin@gateway.ph', 'Department Head / System Administrator', 'admin', 1, NULL, '$2y$12$y09wVrU1vf/gzhPi3xnd4e37S4QoQ1w8NdpaZgvDyNRXzzmSsunzq', NULL, '2026-09-15 03:58:19', '2026-09-21 02:55:22', 0, NULL),
(11, NULL, 'PM Manager', 'pm.manager@gateway.ph', 'Property Management Manager', 'pm_manager', 1, NULL, '$2y$12$QDqOIbOu3WmLFOmY8j8dZOfQZbNGGhoJff1PjZZKNcOT/I8vfKS02', NULL, '2026-09-15 03:58:20', '2026-09-21 01:49:14', 0, NULL),
(12, 1, 'Renie Laranga', 'dealer.mitsubishi-pasig@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$shhZuihQgHHvwauL/m3rPuAnVLW5YpNye9aMjyv1WwXkP6NLpM1o6', NULL, '2026-09-15 03:58:21', '2026-09-21 01:49:03', 0, NULL),
(13, 2, 'Maui Macusi', 'dealer.mg-otis@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$CWsO5fHzrqE8LCykZZJIfupl.STfECRSqUKSwTtToK8yxgkzLGrS2', NULL, '2026-09-15 03:58:21', '2026-09-21 01:49:03', 0, NULL),
(14, 3, 'Maui Macusi', 'dealer.kia-otis@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$MNgPFUFBABXVm0zaZR813O6gwxH4QPcjxoCaxvCh1hquq5WqFCX66', NULL, '2026-09-15 03:58:21', '2026-09-21 01:49:03', 0, NULL),
(15, 4, 'Maui Macusi', 'dealer.vinfast-otis@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$YcNq0UCMvXLgR09dAs84te9kQhqoQjjb15Zz/b9GWO22J2gwdJyDq', NULL, '2026-09-15 03:58:22', '2026-09-21 01:49:03', 0, NULL),
(16, 5, 'Janice Delfin', 'dealer.honda-manila-bay@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$x98WGPTq4YBwyrxTZWMK4eEho8GOzrP9bMycvQWWjjFiMdit5tVzO', NULL, '2026-09-15 03:58:22', '2026-09-21 01:49:04', 0, NULL),
(17, 6, 'Janice Delfin', 'dealer.jetour-baic-manila-bay@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$6vPS7Xs4Kc13Ys0cLPK2weYFs1DIXpZYBmWWFFUuxoEZzfofbBAfK', NULL, '2026-09-15 03:58:22', '2026-09-21 01:49:04', 0, NULL),
(18, 7, 'Dick Albao', 'dealer.mitsubishi-fairview@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$0gutVTEhzChjfoFOTNAq3OPsDO32cBBx5A8Kt/MIfMlBdSVSCqd7S', NULL, '2026-09-15 03:58:22', '2026-09-21 01:49:04', 0, NULL),
(19, 8, 'Randie Tungol', 'dealer.mitsubishi-quezon-ave@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$75I2nSlTTEQOLEONuZkBve.UOpAwn0NjTCUspf9epLG8DAvpyNRbS', NULL, '2026-09-15 03:58:23', '2026-09-21 01:49:04', 0, NULL),
(20, 9, 'Marie Adamos', 'dealer.geely-mandaluyong@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$1B5uEBmbGuxZB0i7Tf92Qe9yoz9YFlxLwqK5VWvo37iFMZ0Md37MS', NULL, '2026-09-15 03:58:23', '2026-09-21 01:49:04', 0, NULL),
(21, 10, 'Ferdinand Uy', 'dealer.geely-cainta@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$LQMlC8pryor6C9MBDLwHWuAaFvwrjPTjc1gWY/kmwt.n5m7nVzOPe', NULL, '2026-09-15 03:58:23', '2026-09-21 01:49:04', 0, NULL),
(22, 11, 'Rachelle Dy Diyco', 'dealer.honda-fairview@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$p5c7Mr7h3T0.sO5UFmXz5uOJEzSH8SdL1mMmEm/t.uY2MZ2jqCCX.', NULL, '2026-09-15 03:58:24', '2026-09-21 01:49:05', 0, NULL),
(23, 12, 'Ferdinand Uy', 'dealer.honda-cainta@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$9Q4i4cWSdLmE87UWDSJUtemnQcr9FsU85c0zDR6jGObUm.t7XqCUK', NULL, '2026-09-15 03:58:24', '2026-09-21 01:49:05', 0, NULL),
(24, 13, 'Cora Ortega', 'dealer.honda-cars-makati@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$Ue6Yrw0Yn3YTzjFpWLgE8e6BvO2PAQ82axpuWWGQVvODBIiWHo7VK', NULL, '2026-09-15 03:58:24', '2026-09-21 01:49:05', 0, NULL),
(25, 14, 'Gets Wambangco', 'dealer.hyundai-makati@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$nNZkEgVfD7Tmaj3T2DrR8OYIkI1bhJWProbU89EC2hLZoFpmCWiGu', NULL, '2026-09-15 03:58:24', '2026-09-21 01:49:05', 0, NULL),
(26, 15, 'Jun Samadan', 'dealer.belfast-stockyard@gateway.ph', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$76kYkYrDhR4d326ALsCUs.y7KBA1Ji9M61ExR33eGqLb3A5CoQzei', NULL, '2026-09-15 03:58:25', '2026-09-21 01:49:05', 0, NULL),
(27, 16, 'Jun Samadan', 'dealer.neopolitan-stockyard-fv@gateway.ph', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$6cwadhgMxictBXdsN8gcoOFTCxbXlYAS5AxxQp5KdDQp9B3sOaQLS', NULL, '2026-09-15 03:58:25', '2026-09-21 01:49:05', 0, NULL),
(28, 17, 'Jun Samadan', 'dealer.new-fv-stockyard-beside-honda-fv@gateway.ph', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$lgsN9eaNFdRR/RzsRJBUHOS00GsML/yv7nEB4sQ4agb1d296BUe9e', NULL, '2026-09-15 03:58:25', '2026-09-21 01:49:06', 0, NULL),
(29, 18, 'Jun Samadan', 'dealer.labayani-stockyard@gateway.ph', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$cAPiKsXK5ZJqA81fc50wI.AfgwP3Tka4inCp4djfUEN718VjxhUH.', NULL, '2026-09-15 03:58:25', '2026-09-21 01:49:06', 0, NULL),
(30, 19, 'Jun Samadan', 'dealer.taguig-stockyard@gateway.ph', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$rMGMyAC7HCNRLstR/r8WW.HhI5W6u7BTb6WvmcM1CZRlFLIR6Zhti', NULL, '2026-09-15 03:58:26', '2026-09-21 01:49:06', 0, NULL),
(31, 20, 'Jun Samadan', 'dealer.otis-stockyard@gateway.ph', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$SKI.ZuX0IIcmbfiWelwOc.KndHZ4VCP2GBU8f./JSDr12.rMxSpfW', NULL, '2026-09-15 03:58:26', '2026-09-21 01:49:06', 0, NULL),
(32, 21, 'Jay-Ar Dyogi', 'dealer.mitsubishi-sucat@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$LDE/VK45MylynrF9RhOiBuIVvFV2bimlOoBG4BUUIm.68VG42hP0S', NULL, '2026-09-15 03:58:26', '2026-09-21 01:49:06', 0, NULL),
(33, 22, 'Reggie De Guzman', 'dealer.mitsubishi-greenhills@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$uOmItxg2/euRVFD0P5pyau6QR81TxkCtK2j3pvfZYrn5LONlEalIO', NULL, '2026-09-15 03:58:27', '2026-09-21 01:49:06', 0, NULL),
(34, 23, 'Kim Lanzanas', 'dealer.honda-marcos-highway@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$b0Fn/gUIG6wW5bmeLRCbdebhI0VtLiAVj.Ai9JeUVY5uuAj1usyHm', NULL, '2026-09-15 03:58:27', '2026-09-21 01:49:06', 0, NULL),
(35, 24, 'Ernie Samson', 'dealer.honda-las-pinas@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$3bKkvJH.lv/EUbyZpxbiwurtSIHAtLVgGVyd7RBaEI5chXZPaYfRG', NULL, '2026-09-15 03:58:27', '2026-09-21 01:49:07', 0, NULL),
(36, 25, 'Ernie Samson', 'dealer.honda-cars-alabang@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$bba7aBNJIvUskByzGQQnZuLRBQ55fAf3XXCGtVmyhmOZdGXy3sGt2', NULL, '2026-09-15 03:58:27', '2026-09-21 01:49:07', 0, NULL),
(37, 26, 'Emely Salmo', 'dealer.mg-las-pinas@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$iPgDM8S2TAO0MH90iKER0.MzkEDXiZYsB9pZ1kMsUQBlLZDJb4h4q', NULL, '2026-09-15 03:58:28', '2026-09-21 01:49:07', 0, NULL),
(38, 27, 'Mildred Martin', 'dealer.suzuki-pasong-tamo@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$YoaCiwsW0hCZfC4k2IO4Wu4CkzF9ipUjxV.T.TlB8cUrW8rGkaDRG', NULL, '2026-09-15 03:58:28', '2026-09-21 01:49:07', 0, NULL),
(39, 28, 'Maui Macusi', 'dealer.omoda-jaecoo-abad-santos@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$d6AuOrlrPI4lkzHkSFcWCuWC9l.PuXLwV9TP6CSRrvAISXXvUQzby', NULL, '2026-09-15 03:58:28', '2026-09-21 01:49:07', 0, NULL),
(40, 29, 'Reggie De Guzman', 'dealer.omoda-jaecoo-greenhills@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$oPsyT17hTU7sufjnfsBtmeHahut8AERNpa/qyu1WMFFZE4Sx9CBA6', NULL, '2026-09-15 03:58:28', '2026-09-21 01:49:07', 0, NULL),
(41, 30, 'Patrick Carandang', 'dealer.omoda-jaecoo-bacoor@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$Fnkm9rLLkW1kE7sO.pOVq.GFTl4t4QwStODDOdoTyqYgIcRGmV6gS', NULL, '2026-09-15 03:58:29', '2026-09-21 01:49:08', 0, NULL),
(42, 31, 'Patrick Carandang', 'dealer.changan-bacoor@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$988/wFJcb2yv0JWabJlLa.RV9IKCnte5KRE9GSGI9jVA3yH7Urdbq', NULL, '2026-09-15 03:58:29', '2026-09-21 01:49:08', 0, NULL),
(43, 32, 'Julius Fabe', 'dealer.jetour-baic-bgc@gateway.ph', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$EjhDJICcmdRGxjhDwRGBGule3yNE2sD5jxAeg8BhqlX2fTe4v3cuG', NULL, '2026-09-15 03:58:29', '2026-09-21 01:49:08', 0, NULL),
(44, 33, 'Kodak Bldg', 'dealer.kodak-bldg@gateway.ph', 'Dealer Representative', 'dealer', 1, NULL, '$2y$12$j3BlUUq0nG8v4GX5.qB2MeLLBFmNPeL2YQdu6/39g5tJUkYGMsk12', NULL, '2026-09-15 03:58:30', '2026-09-21 01:49:08', 0, NULL),
(45, 34, 'Jay-Ar Dyogi', 'dealer.gateway-sm-south-mall-inline@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$srUmX.ArymBsZdc9neBq3uyOgghD9zJdtCqseb0Z2L3kGZnoN0CO6', NULL, '2026-09-15 03:58:30', '2026-09-21 01:49:08', 0, NULL),
(46, 35, 'Jun Samadan', 'dealer.las-pinas-stockyard-lp1@gateway.ph', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$fqBXMUXsVGrz/3KyOfD4NO4TN1HZj0E6iMwc26oQdbHU/qkMNC6Zq', NULL, '2026-09-15 03:58:30', '2026-09-21 01:49:08', 0, NULL),
(47, 36, 'Jun Samadan', 'dealer.las-pinas-stockyard-lp2@gateway.ph', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$w8suB3jipsb7METTLXrtzuzp6ymZlhlEN1iCNAItUSBGcf82CFUZq', NULL, '2026-09-15 03:58:31', '2026-09-21 01:49:09', 0, NULL),
(48, 37, 'Jun Samadan', 'dealer.las-pinas-stockyard-lp3@gateway.ph', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$8.yrH0CCc4v2Swd3stXS0.0jJpKFDyGTSBrIUGgT5s2UaT7uW0zvu', NULL, '2026-09-15 03:58:31', '2026-09-21 01:49:09', 0, NULL),
(49, 38, 'Jun Samadan', 'dealer.cainta-stockyard@gateway.ph', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$JZoKSgoZQtcBerTEhrZNzObN79IcSc8M5Sc9/Q9zZ9nG478hDVV.S', NULL, '2026-09-15 03:58:31', '2026-09-21 01:49:09', 0, NULL),
(50, 39, 'Ernie Samson', 'dealer.brp-alabang-honda@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$rbNpfTOlzVIuO/S/k7PJ8OdaIoWIcPVklO3d36eFCFGImw2Bo9rZO', NULL, '2026-09-15 03:58:31', '2026-09-21 01:49:09', 0, NULL),
(51, 40, 'Dick Albao', 'dealer.gateway-sm-san-mateo-inline@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$hUIdVTTW5oaCK.82f2UoTeioEvljIRoh9Frca3E4JZIbIxAdMHdV.', NULL, '2026-09-15 03:58:32', '2026-09-21 01:49:09', 0, NULL),
(52, 41, 'Jun Samadan', 'dealer.bacoor-yard@gateway.ph', 'Stockyard Supervisor', 'dealer', 1, NULL, '$2y$12$WjccUIsBxKmrtB0izkcqlO4H9TLAzpKCaUy8l8.Sc5gulDY1L855K', NULL, '2026-09-15 03:58:32', '2026-09-21 01:49:09', 0, NULL),
(53, 42, 'Ghie Tungol', 'dealer.gateway-calamba-mitsu-kia@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$bnt.tBsAyd4DMCtB3ZJ28O5i7mEdRgguPMZHpRbdv/jLf.ddJDm.K', NULL, '2026-09-15 03:58:32', '2026-09-21 01:49:10', 0, NULL),
(54, 43, 'Angie Janer-Luz', 'dealer.mitsubishi-pili-camsur@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$pW1ZIw7PV.z9G2BrJXNck.kDjxWnZjWGhg5VHuK5tgahnm02tcVi.', NULL, '2026-09-15 03:58:32', '2026-09-21 01:49:10', 0, NULL),
(55, 44, 'Cris Grajo', 'dealer.mitsubishi-legaspi@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$lRWqU4IKXO4fEQ2xv75cJuaRkmpDBS6IK.8/AAXMB/tdB/QBmYhsS', NULL, '2026-09-15 03:58:33', '2026-09-21 01:49:10', 0, NULL),
(56, 45, 'Mariver Garcia', 'dealer.geely-fuso-lipa@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$ymSuDEvTnphjtQcgGulPdOQNpcFCZdvyF4XvOMQhTLlzDcRi4lnom', NULL, '2026-09-15 03:58:33', '2026-09-21 01:49:10', 0, NULL),
(57, 46, 'Geely Naga', 'dealer.geely-naga@gateway.ph', 'Dealer Representative', 'dealer', 1, NULL, '$2y$12$10M8oFcaSXrTmkUV.PZjJe5DtkRWvcN08pl1s.2BzeaxNFPddYpIq', NULL, '2026-09-15 03:58:33', '2026-09-21 01:49:10', 0, NULL),
(58, 47, 'Geely Dasma', 'dealer.geely-dasma@gateway.ph', 'Dealer Representative', 'dealer', 1, NULL, '$2y$12$xALws.Y08HhKNIbqNoDqw.Q0yrEqoDc3oinHUUZN.2wxI2A.25qWm', NULL, '2026-09-15 03:58:33', '2026-09-21 01:49:10', 0, NULL),
(59, 48, 'Mariver Garcia', 'dealer.mg-batangas@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$z0VqayqKkDg9YVNFAkB76eKnDNOUMR/faCANebCeEaW60Bn4Jb9xa', NULL, '2026-09-15 03:58:34', '2026-09-21 01:49:11', 0, NULL),
(60, 49, 'Ariane Legarte', 'dealer.mg-san-pablo@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$1.sSzebS74sDmPbrPBPkJeUsptmM8qyoqK5733R6mX7Jvl8c94rU.', NULL, '2026-09-15 03:58:34', '2026-09-21 01:49:11', 0, NULL),
(61, 50, 'Heart Reyes', 'dealer.kia-dasmarinas@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$PT6ug/g92sfZAa/wHutyJeUldeiDS8clbgeXO7ytIPqhAuCqw1Hy2', NULL, '2026-09-15 03:58:34', '2026-09-21 01:49:11', 0, NULL),
(62, 51, 'Jojo Legarra', 'dealer.gateway-sm-dasma-inline@gateway.ph', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$iE6fpd6jYr0xIA4j.xNskesW8Bs2q/4..5mnSqEoGniGlXe3b9uN2', NULL, '2026-09-15 03:58:35', '2026-09-21 01:49:11', 0, NULL),
(63, 52, 'Ariane Legarte', 'dealer.kia-san-pablo@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$3CwKg/qux2gn9jiLAfUIte.8y9BZqK6y7o20sfqwHvhJNU1M5Zt9u', NULL, '2026-09-15 03:58:35', '2026-09-21 01:49:11', 0, NULL),
(64, 53, 'Ariane Legarte', 'dealer.suzuki-san-pablo@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$eJxaXConnoC5qDv6uPnyVuRjczqq9YqplDeY7ldeGk8dlv.5O9FEW', NULL, '2026-09-15 03:58:35', '2026-09-21 01:49:11', 0, NULL),
(65, 54, 'Rona Bringino', 'dealer.suzuki-sta-rosa@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$4S9Q5otC24PqpUoxxIGcOedaO3v/6.2rxwrf2iioZiEy49ZshEuLK', NULL, '2026-09-15 03:58:35', '2026-09-21 01:49:12', 0, NULL),
(66, 55, 'Ivy Balerite', 'dealer.suzuki-palawan@gateway.ph', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$o9aVtIXlcKHrkaulMEBqIOWiH1vK.Ps0ghvAlzXTe08Beim989W.K', NULL, '2026-09-15 03:58:35', '2026-09-21 01:49:12', 0, NULL),
(67, 56, 'Jhojo Claros', 'dealer.omoda-jaecoo-carmona@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$eSCrkPDXmL1FgCXQoSEnSOai7nXwpYCP6K8Vd5KZGYaboioXyd1ue', NULL, '2026-09-15 03:58:36', '2026-09-21 01:49:12', 0, NULL),
(68, 57, 'Mariver Garcia', 'dealer.omoda-jaecoo-lipa@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$vcSs0fJrAf9J6gCLyK1xF.MKv5v/6hqyyAUf1Nd7rQazK5yb5N44.', NULL, '2026-09-15 03:58:36', '2026-09-21 01:49:12', 0, NULL),
(69, 58, 'Mariver Garcia', 'dealer.changan-batangas@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$nRGDEdc0Chw2JeJpWFln7uZPjSiqpsCq2o4gqyWAHeh4D.RvrXjK6', NULL, '2026-09-15 03:58:36', '2026-09-21 01:49:12', 0, NULL),
(70, 59, 'X Carmona', 'dealer.x-carmona@gateway.ph', 'Dealer Representative', 'dealer', 1, NULL, '$2y$12$pO3ndqSAVN9HE4m6IJiUu.UZqomadc1iWbCFdfplL/J6oMXJRJeSC', NULL, '2026-09-15 03:58:37', '2026-09-21 01:49:12', 0, NULL),
(71, 60, 'Bong Rosal', 'dealer.gateway-sto-tomas-lianas@gateway.ph', 'Area Head', 'dealer', 1, NULL, '$2y$12$47HzAt9jwuCM6TtzzL8qdew2bmJgXt5s36a6ivWX21hxtOlb9PWOC', NULL, '2026-09-15 03:58:37', '2026-09-21 01:49:13', 0, NULL),
(72, 61, 'Bong Rosal', 'dealer.san-pablo-stock-yard-beside-kia@gateway.ph', 'Area Head', 'dealer', 1, NULL, '$2y$12$Uyx9vEQN/hJPy2a2RxGCyusq1Rtmp.9mDxrTahs6F47QlUZPkEZJy', NULL, '2026-09-15 03:58:37', '2026-09-21 01:49:13', 0, NULL),
(73, 62, 'Diana Rose Suba', 'dealer.cabanas-showroom@gateway.ph', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$OpJhw4iqaMKSyF/OY12cIuphAzC3FkxVIAlxsIrNvhXs0y7dip/XK', NULL, '2026-09-15 03:58:37', '2026-09-21 01:49:13', 0, NULL),
(74, 63, 'Rolly Maur', 'dealer.honda-isabela@gateway.ph', 'General Manager', 'dealer', 1, NULL, '$2y$12$fEQWExpd4Y8ZrSYeXflPDOJJXt34v9837J3dn0ryexky73ApVUgcu', NULL, '2026-09-15 03:58:38', '2026-09-21 01:49:13', 0, NULL),
(75, 64, 'Charlie Marcos', 'dealer.geely-tarlac@gateway.ph', 'Assistant Sales Manager', 'dealer', 1, NULL, '$2y$12$qkfrhKmNg8yUXeLtfA3QVuIs6w3GxmjpS.atZACWZoexmNh3R57te', NULL, '2026-09-15 03:58:38', '2026-09-21 01:49:13', 0, NULL),
(76, 65, 'Apple De Leon', 'dealer.geely-angeles@gateway.ph', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$S3V4DtGBEGo/QX8jhLh6heBz1jPmfZU7crUUiv7zDsPnFHM2sZqYO', NULL, '2026-09-15 03:58:38', '2026-09-21 01:49:13', 0, NULL),
(77, 66, 'Geely Baliuag', 'dealer.geely-baliuag@gateway.ph', 'Dealer Representative', 'dealer', 1, NULL, '$2y$12$b9s8mS2IK61PgfbHr/k/Qu8Xvyvzq02pv5rUaS2Ub33SDhDM2slD2', NULL, '2026-09-15 03:58:38', '2026-09-21 01:49:14', 0, NULL),
(78, 67, 'Diana Rose Suba', 'dealer.mg-marilao@gateway.ph', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$ujnXfBBxWEvdR0H8hFswGuZyalEAEc3OOuIOyI4t9QP7YSsjpce32', NULL, '2026-09-15 03:58:39', '2026-09-21 01:49:14', 0, NULL),
(79, 68, 'Aaron Pascual', 'dealer.mg-angeles@gateway.ph', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$ReLgIjsMzw5VUmpvoisDbuWci39CVHDbHhseoBJ1xjGTFaR038Epq', NULL, '2026-09-15 03:58:39', '2026-09-21 01:49:14', 0, NULL),
(80, 69, 'Aaron Pascual', 'dealer.changan-angeles@gateway.ph', 'General Relations Manager', 'dealer', 1, NULL, '$2y$12$Q7DKE3hAdPOP3aM3UWVjcOqgZubMzNEdPfK.kneA/ImjZqRaLULtm', NULL, '2026-09-15 03:58:39', '2026-09-21 01:49:14', 0, NULL),
(81, 70, 'Dodie Ronquillo', 'dealer.san-fernando-stockyard@gateway.ph', 'Area Head', 'dealer', 1, NULL, '$2y$12$OhzNDcc0PIE7XtJvUZnc5.ZB3fBNLSXG49XoFnrIv23OFnwXdXIXa', NULL, '2026-09-15 03:58:39', '2026-09-21 01:49:14', 0, NULL),
(88, NULL, 'Dial-A', 'dial.handyman@gateway.ph', 'Dial-A / Property Management Support', 'dial_a', 1, NULL, '$2y$12$DPI7lC020QYrFbisE4yWYu8KsvaGUk3LQnc.efasfhhKULEyIZCHi', NULL, '2026-09-15 05:21:52', '2026-09-21 02:22:14', 0, NULL),
(89, 14, 'Rojeme Renz Sapno', 'rojemerenz.sapno@gatewaygroup.ph', 'PM Manager', 'pm_manager', 1, NULL, '$2y$12$nkhIiR/WT3vIUkrxJY9xkuTaMqfmq2Pqex0OYWLpOn1/K/EKKIxoG', NULL, '2026-09-16 03:42:20', '2026-09-16 03:42:20', 1, NULL),
(90, NULL, 'JR PACTOR', 'jrpactor@gatewaygroup.com.ph', 'Administrator', 'admin', 1, NULL, '$2y$12$EmUQyPNyxRNUQmpgFfUeN.PxoNk/DDooSxd0E6IMfEpT5HX6cEctG', NULL, '2026-09-19 02:47:46', '2026-09-21 02:57:20', 0, NULL),
(91, NULL, 'PM Admin', 'pm.admin@gateway.ph', 'Property Management Admin', 'pm_admin', 1, NULL, '$2y$12$62Di0Z.YLaUY6P2kSGii5uffmVBiFiuE/nUsr6eN4C6q3YiNbbx8y', NULL, '2026-09-21 03:47:48', '2026-09-21 03:47:48', 1, NULL);

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
  ADD KEY `property_requests_assigned_manager_id_foreign` (`assigned_manager_id`),
  ADD KEY `property_requests_assignment_by_id_foreign` (`assignment_by_id`),
  ADD KEY `property_requests_in_house_inspection_by_id_foreign` (`in_house_inspection_by_id`),
  ADD KEY `property_requests_in_house_work_order_by_id_foreign` (`in_house_work_order_by_id`),
  ADD KEY `property_requests_in_house_completion_by_id_foreign` (`in_house_completion_by_id`),
  ADD KEY `property_requests_assignment_type_index` (`assignment_type`),
  ADD KEY `property_requests_pm_reviewed_by_id_foreign` (`pm_reviewed_by_id`),
  ADD KEY `property_requests_assignment_phase_index` (`assignment_phase`);

--
-- Indexes for table `request_attachments`
--
ALTER TABLE `request_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_attachments_property_request_id_foreign` (`property_request_id`),
  ADD KEY `request_attachments_category_index` (`category`),
  ADD KEY `request_attachments_uploaded_by_id_foreign` (`uploaded_by_id`);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1486;

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=234;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `property_requests`
--
ALTER TABLE `property_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `request_attachments`
--
ALTER TABLE `request_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=431;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

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
  ADD CONSTRAINT `property_requests_assignment_by_id_foreign` FOREIGN KEY (`assignment_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `property_requests_dealer_id_foreign` FOREIGN KEY (`dealer_id`) REFERENCES `dealers` (`id`),
  ADD CONSTRAINT `property_requests_in_house_completion_by_id_foreign` FOREIGN KEY (`in_house_completion_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `property_requests_in_house_inspection_by_id_foreign` FOREIGN KEY (`in_house_inspection_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `property_requests_in_house_work_order_by_id_foreign` FOREIGN KEY (`in_house_work_order_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `property_requests_pm_reviewed_by_id_foreign` FOREIGN KEY (`pm_reviewed_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `property_requests_submitted_by_foreign` FOREIGN KEY (`submitted_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `request_attachments`
--
ALTER TABLE `request_attachments`
  ADD CONSTRAINT `request_attachments_property_request_id_foreign` FOREIGN KEY (`property_request_id`) REFERENCES `property_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_attachments_uploaded_by_id_foreign` FOREIGN KEY (`uploaded_by_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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
