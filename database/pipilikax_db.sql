-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 07, 2025 at 01:31 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pipilikax_db`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `active_navigation`
-- (See below for the actual view)
--
CREATE TABLE `active_navigation` (
`id` int(11)
,`title` varchar(100)
,`url` varchar(255)
,`target` varchar(20)
,`display_order` int(11)
,`is_active` tinyint(1)
,`parent_id` int(11)
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `active_team`
-- (See below for the actual view)
--
CREATE TABLE `active_team` (
`id` int(11)
,`name` varchar(100)
,`position` varchar(100)
,`photo` varchar(255)
,`bio` text
,`email` varchar(100)
,`phone` varchar(20)
,`facebook_url` varchar(255)
,`linkedin_url` varchar(255)
,`twitter_url` varchar(255)
,`github_url` varchar(255)
,`display_order` int(11)
,`is_active` tinyint(1)
,`created_at` timestamp
,`updated_at` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'login', 'user', 1, 'Admin user logged in', '127.0.0.1', NULL, '2025-12-03 15:25:04'),
(2, 2, 'create', 'blog_post', 1, 'Created new blog post: Webb Sees Sombrero Galaxy', '127.0.0.1', NULL, '2025-12-03 15:25:04'),
(3, 2, 'publish', 'blog_post', 1, 'Published blog post: Webb Sees Sombrero Galaxy', '127.0.0.1', NULL, '2025-12-03 15:25:04'),
(4, NULL, 'failed_login', 'user', NULL, 'Failed login attempt for username: admin', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-03 15:59:59'),
(5, 1, 'login', 'user', 1, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-03 16:01:00'),
(6, 1, 'login', 'user', 1, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-03 16:03:09'),
(7, 1, 'create', 'blog_post', 7, 'Created post: Something', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-03 16:03:43'),
(8, 1, 'update', 'blog_post', 7, 'Updated post: Something', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-03 16:12:02'),
(9, NULL, 'failed_login', 'user', NULL, 'Failed login attempt for username: admin', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 00:30:07'),
(10, 1, 'login', 'user', 1, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 00:30:14'),
(11, 1, 'login', 'user', 1, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 17:24:36'),
(12, 1, 'login', 'user', 1, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 23:57:26'),
(13, 1, 'update', 'category', 5, 'Updated category: NASA Updates Something', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-04 23:57:48'),
(14, 1, 'login', 'user', 1, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-05 16:50:09'),
(15, 1, 'login', 'user', 1, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 15:52:19'),
(16, 1, 'update', 'settings', NULL, 'Updated general settings', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 16:07:30'),
(17, 1, 'update', 'user', 1, 'Updated user: admin', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 16:13:37'),
(18, 1, 'update', 'settings', NULL, 'Updated contact settings', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 16:22:10'),
(19, 1, 'update', 'settings', NULL, 'Updated contact settings', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 16:46:30'),
(20, 1, 'create', 'navigation', 5, 'Added menu item: sascas', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 17:02:05'),
(21, 1, 'create', 'navigation', 6, 'Added menu item: sascas', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 17:02:16'),
(22, 1, 'delete', 'navigation', 6, 'Deleted menu item', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 17:02:41'),
(23, 1, 'update', 'navigation', 5, 'Toggled menu item visibility', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 17:03:05'),
(24, 1, 'update', 'navigation', 5, 'Toggled menu item visibility', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 17:05:30'),
(25, 1, 'update', 'navigation', 5, 'Updated menu item: Join Now', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 17:07:52'),
(26, 1, 'update', 'settings', NULL, 'Updated contact settings', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 17:10:43'),
(27, 1, 'update', 'settings', NULL, 'Updated contact settings', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 17:15:21'),
(28, 1, 'update', 'settings', NULL, 'Updated contact settings', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 17:17:18'),
(29, 1, 'update', 'settings', NULL, 'Updated contact settings', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 17:17:40'),
(30, 1, 'update', 'settings', NULL, 'Updated contact settings', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 17:18:08'),
(31, 1, 'update', 'settings', NULL, 'Updated homepage settings', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 17:23:10'),
(32, 1, 'update', 'settings', NULL, 'Updated homepage settings', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 17:23:33'),
(33, 1, 'update', 'settings', NULL, 'Updated contact settings', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 17:35:36'),
(34, 1, 'login', 'user', 1, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 18:19:27'),
(35, 1, 'update', 'blog_post', 1, 'Updated post: Webb Sees Sombrero Galaxy in Near-Infrared', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-12-06 18:19:45'),
(36, 1, 'login', 'user', 1, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 09:54:37'),
(37, NULL, 'failed_login', 'user', NULL, 'Failed login attempt for username: admin', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 10:38:39'),
(38, 1, 'login', 'user', 1, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 10:38:51'),
(39, 1, 'create', 'blog_post', 8, 'Created post: @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 10:57:29'),
(40, 1, 'create', 'blog_post', 9, 'Created post: gybyuhj', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:03:54'),
(41, 1, 'update', 'blog_post', 9, 'Updated post: gybyuhj', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:10:23'),
(42, 1, 'update', 'blog_post', 9, 'Updated post: gybyuhj', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:10:47'),
(43, 1, 'create', 'category', 6, 'Created category: dajdaufjwlfnaljdfnuhlqjwenlj a,md f', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:12:26'),
(44, 1, 'create', 'category', 7, 'Created category: 12', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:12:52'),
(45, 1, 'create', 'blog_post', 10, 'Created post: esdc n lm dka.아', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:13:26'),
(46, 1, 'create', 'team_member', 5, 'Created team member: 12', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:14:10'),
(47, 1, 'create', 'team_member', 6, 'Created team member: 1233', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:15:14'),
(48, 1, 'create', 'team_member', 7, 'Created team member: sdcasdc', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:16:44'),
(49, 1, 'delete', 'team_member', 5, 'Deleted team member', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:22:42'),
(50, 1, 'delete', 'team_member', 6, 'Deleted team member', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:22:53'),
(51, 1, 'delete', 'team_member', 7, 'Deleted team member', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:22:58'),
(52, 1, 'create', 'team_member', 8, 'Created team member: Hiuaosjcas', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:23:21'),
(53, 1, 'delete', 'team_member', 8, 'Deleted team member', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:23:51'),
(54, 1, 'create', 'team_member', 9, 'Created team member: dajdaufjwlfnaljdfnuhlqjwenlj a,md f', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:25:23'),
(55, 1, 'delete', 'team_member', 9, 'Deleted team member', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:27:08'),
(56, 1, 'create', 'team_member', 10, 'Created team member: dsacascsadc', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:27:21'),
(57, 1, 'delete', 'team_member', 10, 'Deleted team member', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:30:04'),
(58, 1, 'create', 'team_member', 11, 'Created team member: sacad', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:30:12'),
(59, 1, 'create', 'team_member', 12, 'Created team member: dfzvdf', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:33:36'),
(60, 1, 'create', 'blog_post', 11, 'Created post: efavjkd nvkjnadfnvkj d', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:40:27'),
(61, 1, 'create', 'blog_post', 12, 'Created post: aodi', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:40:46'),
(62, 1, 'create', 'blog_post', 13, 'Created post: ;qiwer', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:41:37'),
(63, 1, 'update', 'blog_post', 13, 'Updated post: ;qiwer', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:41:50'),
(64, 1, 'update', 'blog_post', 12, 'Updated post: aodi', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:41:57'),
(65, 1, 'update', 'blog_post', 11, 'Updated post: efavjkd nvkjnadfnvkj d', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:42:05'),
(66, 1, 'update', 'blog_post', 10, 'Updated post: esdc n lm dka.아', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:42:11'),
(67, 1, 'create', 'user', 4, 'Created user: azharcodna', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:44:15'),
(68, 1, 'create', 'user', 5, 'Created user: mdtaohid', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:45:39'),
(69, 1, 'update', 'settings', NULL, 'Updated contact settings', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:46:28'),
(70, 1, 'update', 'settings', NULL, 'Updated contact settings', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:46:33'),
(71, 1, 'update', 'settings', NULL, 'Updated contact settings', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:46:59'),
(72, 1, 'create', 'user', 6, 'Created user: user1', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:47:48'),
(73, 1, 'create', 'user', 7, 'Created user: user2', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:48:11'),
(74, 1, 'create', 'user', 8, 'Created user: user3', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:48:38'),
(75, 1, 'create', 'user', 9, 'Created user: user4', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:49:33'),
(76, 1, 'logout', 'user', 1, 'User logged out', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:49:56'),
(77, 7, 'login', 'user', 7, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:50:04'),
(78, 7, 'logout', 'user', 7, 'User logged out', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:50:31'),
(79, 6, 'login', 'user', 6, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:50:43'),
(80, 6, 'logout', 'user', 6, 'User logged out', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:50:51'),
(81, 6, 'login', 'user', 6, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:50:58'),
(82, 6, 'logout', 'user', 6, 'User logged out', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:51:29'),
(83, 8, 'login', 'user', 8, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:51:40'),
(84, 8, 'logout', 'user', 8, 'User logged out', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:52:34'),
(85, 9, 'login', 'user', 9, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:53:03'),
(86, 9, 'logout', 'user', 9, 'User logged out', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:55:39'),
(87, 6, 'login', 'user', 6, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:55:51'),
(88, 6, 'logout', 'user', 6, 'User logged out', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:56:17'),
(89, 1, 'login', 'user', 1, 'User logged in', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:56:24'),
(90, 1, 'delete', 'team_member', 11, 'Deleted team member', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:59:38'),
(91, 1, 'delete', 'team_member', 12, 'Deleted team member', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:59:43'),
(92, 1, 'delete', 'category', 7, 'Deleted category', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 12:05:42'),
(93, 1, 'delete', 'category', 6, 'Deleted category', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 12:05:47'),
(94, 1, 'update', 'blog_post', 10, 'Updated post: esdc n lm dka.아', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 12:24:10'),
(95, 1, 'update', 'blog_post', 10, 'Updated post: esdc n lm dka.아', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 12:24:55');

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `views` int(11) DEFAULT 0,
  `allow_comments` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `published_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `author_id`, `category_id`, `status`, `views`, `allow_comments`, `created_at`, `updated_at`, `published_at`) VALUES
(1, 'Webb Sees Sombrero Galaxy in Near-Infrared', 'webb-sees-sombrero-galaxy-in-near-infrared', 'NASA&#039;s James Webb Space Telescope recently imaged the Sombrero Galaxy with its NIRCam, revealing stunning details of this cosmic wonder.', '<p>NASA\'s James Webb Space Telescope recently imaged the Sombrero Galaxy with its NIRCam (Near-Infrared Camera), which shows dust from the galaxy\'s outer ring blocking stellar light from stars within the galaxy. In the central region of the galaxy, the roughly 2,000 globular clusters, or collections of hundreds of thousands of old stars held together by gravity, glow in the near-infrared.</p><p>The Sombrero Galaxy is around 30 million light-years from Earth in the constellation Virgo. From Earth, we see this galaxy nearly \"edge-on,\" or from the side.</p><p>This new image reveals the smooth, clumpy nature of the dust that makes up the galaxy\'s outer ring, where new stars are forming. The JWST\'s infrared view is giving astronomers and space enthusiasts a whole new perspective on this iconic galaxy.</p>', 'image-of-the-day.jpg', 2, 2, 'published', 173, 1, '2025-12-03 15:25:03', '2025-12-07 11:11:03', '2024-12-01 01:30:00'),
(2, 'The Future of Mars Exploration', 'the-future-of-mars-exploration', 'As we stand on the brink of a new era in space exploration, Mars missions are becoming more ambitious and closer to reality.', '<p>The red planet has captivated humanity for centuries, and now we\'re closer than ever to establishing a human presence on Mars. Recent missions by NASA, SpaceX, and other space agencies have paved the way for future exploration.</p><h2>Current Missions</h2><p>The Perseverance rover continues to explore the Jezero Crater, searching for signs of ancient microbial life. Meanwhile, the Ingenuity helicopter has proven that powered flight is possible in Mars\' thin atmosphere.</p><h2>Future Plans</h2><p>SpaceX\'s Starship is being developed specifically for Mars missions, with Elon Musk aiming for the first crewed mission by the 2030s. NASA\'s Artemis program will also play a crucial role, testing technologies on the Moon before applying them to Mars.</p>', 'mars-image.jpg', 2, 1, 'published', 255, 1, '2025-12-03 15:25:03', '2025-12-07 10:04:59', '2024-11-28 05:20:00'),
(3, 'Understanding Black Holes: A Beginner\'s Guide', 'understanding-black-holes-beginners-guide', 'Black holes are among the most mysterious objects in the universe. Let\'s explore what they are and how they work.', '<p>Black holes are regions of spacetime where gravity is so strong that nothing, not even light, can escape from them. They form when massive stars collapse at the end of their life cycles.</p><h2>Types of Black Holes</h2><p>There are several types of black holes: stellar-mass black holes, intermediate-mass black holes, and supermassive black holes that sit at the centers of galaxies.</p><h2>Event Horizon</h2><p>The boundary of a black hole is called the event horizon. Once anything crosses this point, it can never return. Inside the event horizon lies the singularity, a point of infinite density.</p><p>Recent observations using the Event Horizon Telescope have even captured the first images of black holes, revolutionizing our understanding of these cosmic phenomena.</p>', 'space.jpg', 3, 2, 'published', 193, 1, '2025-12-03 15:25:03', '2025-12-07 11:11:16', '2024-11-25 00:15:00'),
(4, 'SpaceX Starship: The Rocket That Will Take Us to Mars', 'spacex-starship-rocket-to-mars', 'SpaceX\'s Starship represents the future of space travel, designed to carry both crew and cargo to the Moon, Mars, and beyond.', '<p>Starship is the world\'s most powerful launch vehicle ever developed, capable of carrying up to 100 people on long-duration interplanetary flights. Standing at 120 meters tall, it\'s a fully reusable transportation system designed to revolutionize space travel.</p><h2>Key Features</h2><p>The spacecraft consists of two stages: the Super Heavy booster and the Starship spacecraft. Both stages are designed to be fully and rapidly reusable, dramatically reducing the cost of space access.</p><h2>Test Flights</h2><p>SpaceX has conducted multiple test flights, each providing valuable data to improve the design. The company continues to iterate rapidly, bringing humanity closer to becoming a multi-planetary species.</p>', 'spaceX-flight-rocket.jpg', 2, 3, 'published', 318, 1, '2025-12-03 15:25:03', '2025-12-07 11:11:09', '2024-11-20 07:45:00'),
(5, 'The International Space Station: 25 Years of Science', 'international-space-station-25-years', 'For over two decades, the ISS has served as a unique laboratory for scientific research and international cooperation.', '<p>The International Space Station (ISS) represents one of humanity\'s greatest achievements in space exploration. Launched in 1998, it has been continuously inhabited for over 20 years, hosting astronauts from around the world.</p><h2>Scientific Research</h2><p>The ISS serves as a microgravity laboratory where researchers conduct experiments in biology, human biology, physics, astronomy, and meteorology. The unique environment has led to breakthroughs in medicine, materials science, and our understanding of how the human body adapts to space.</p><h2>International Cooperation</h2><p>Involving five space agencies (NASA, Roscosmos, JAXA, ESA, and CSA), the ISS stands as a symbol of international cooperation and peaceful use of space.</p>', 'sapceX-image.jpg', 3, 1, 'published', 175, 1, '2025-12-03 15:25:03', '2025-12-07 12:26:10', '2024-11-15 02:30:00'),
(6, 'Discovering Exoplanets: Worlds Beyond Our Solar System', 'discovering-exoplanets-worlds-beyond', 'The search for planets orbiting other stars has revealed thousands of worlds, some of which might harbor life.', '<p>Since the first confirmed detection in 1992, astronomers have discovered over 5,000 exoplanets. These discoveries have revolutionized our understanding of planetary systems and the potential for life beyond Earth.</p><h2>Detection Methods</h2><p>Scientists use several methods to detect exoplanets, including the transit method (observing dimming as a planet passes in front of its star) and the radial velocity method (detecting wobbles in a star caused by orbiting planets).</p><h2>Habitable Worlds</h2><p>Some exoplanets lie in their star\'s habitable zone, where conditions might allow liquid water to exist on the surface. These worlds are prime targets in the search for extraterrestrial life.</p>', 'planets-image.jpg', 2, 2, 'published', 201, 1, '2025-12-03 15:25:03', '0000-00-00 00:00:00', '2024-11-09 23:00:00'),
(7, 'Something', 'something', '', 'az zcx xc', NULL, 1, 5, 'published', 9, 1, '2025-12-03 16:03:43', '2025-12-07 09:56:19', '2025-12-03 16:12:02'),
(8, '@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@', 'gotucagol', 'd,sflje fa ,e jfib,uiarh.ufn.runasd.lnvsadn ;ofwue kmnl ,a,kjn faj liejsd r,nf lisyblf weu', 'eto guta guti ka ', NULL, 1, NULL, 'draft', 0, 1, '2025-12-07 10:57:29', '0000-00-00 00:00:00', NULL),
(9, 'gybyuhj', 'gybyuhj', '', 'mjmk jk ', '6935609f00f6c_1765105823.png', 1, 2, 'published', 1, 1, '2025-12-07 11:03:54', '2025-12-07 11:10:53', '2025-12-07 11:10:47'),
(10, 'esdc n lm dka.아', 'esdc-n-lm-dka', 'ㅇ마ㅜㄴ.어 ㅜ.ㅊ머누이ㅓ춤어ㅝㅁ어', 'dkldlkn.kcnlnladifnlaeriu', NULL, 1, 4, 'published', 2, 1, '2025-12-07 11:13:26', '2025-12-07 12:25:05', '2025-12-07 11:42:11'),
(11, 'efavjkd nvkjnadfnvkj d', 'fajsdfiandjcna;sdn', 'adnasnd.,cna.skdjnc;jkasnd;cj', ';dvnajnsldfnlasndlf;ije;oifjc;asdm;cloiasl', NULL, 1, 2, 'published', 2, 1, '2025-12-07 11:40:27', '2025-12-07 12:17:37', '2025-12-07 11:42:05'),
(12, 'aodi', 'sdanldnlj', 'sdvbakjsdhl', 'diuahdiucnkjalsdjnl', NULL, 1, 1, 'published', 2, 1, '2025-12-07 11:40:46', '2025-12-07 12:25:56', '2025-12-07 11:41:57'),
(13, ';qiwer', 'wdjh', 'Dslcj', 'ㅇ니어hluSdunㅜ이n', NULL, 1, NULL, 'published', 0, 1, '2025-12-07 11:41:37', '2025-12-07 11:41:50', '2025-12-07 11:41:50');

--
-- Triggers `blog_posts`
--
DELIMITER $$
CREATE TRIGGER `decrement_category_count` AFTER DELETE ON `blog_posts` FOR EACH ROW BEGIN
    IF OLD.status = 'published' THEN
        UPDATE categories SET post_count = post_count - 1 WHERE id = OLD.category_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `increment_category_count` AFTER INSERT ON `blog_posts` FOR EACH ROW BEGIN
    IF NEW.status = 'published' THEN
        UPDATE categories SET post_count = post_count + 1 WHERE id = NEW.category_id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_category_count` AFTER UPDATE ON `blog_posts` FOR EACH ROW BEGIN
    IF OLD.status != NEW.status THEN
        IF NEW.status = 'published' AND OLD.status != 'published' THEN
            UPDATE categories SET post_count = post_count + 1 WHERE id = NEW.category_id;
        ELSEIF OLD.status = 'published' AND NEW.status != 'published' THEN
            UPDATE categories SET post_count = post_count - 1 WHERE id = OLD.category_id;
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `post_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `post_count`, `created_at`, `updated_at`) VALUES
(1, 'Space Exploration', 'space-exploration', 'Articles about space missions and cosmic discoveries', 3, '2025-12-03 15:25:03', '2025-12-07 11:41:57'),
(2, 'Astronomy', 'astronomy', 'Celestial phenomena and sky observations', 5, '2025-12-03 15:25:03', '2025-12-07 11:42:05'),
(3, 'Technology', 'technology', 'Space technology and innovations', 1, '2025-12-03 15:25:03', '2025-12-03 15:25:04'),
(4, 'Science', 'science', 'Scientific breakthroughs and research', 1, '2025-12-03 15:25:03', '2025-12-07 11:42:11'),
(5, 'NASA Updates Something', 'nasa-updates', 'Latest news from NASA missions', 1, '2025-12-03 15:25:03', '2025-12-04 23:57:48');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','replied','archived') DEFAULT 'new',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `read_at` timestamp NULL DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `status`, `ip_address`, `user_agent`, `created_at`, `read_at`, `replied_at`) VALUES
(1, 'John Doe', 'john.doe@example.com', 'Question about Mars mission', 'I loved your article about Mars exploration! When do you think humans will actually land on Mars?', 'read', '192.168.1.1', NULL, '2024-12-03 01:30:00', NULL, NULL),
(2, 'Sarah Johnson', 'sarah.j@example.com', 'Collaboration inquiry', 'I\'m interested in collaborating on space education content. Could we schedule a call?', 'read', '192.168.1.2', NULL, '2024-12-02 05:20:00', NULL, NULL),
(3, 'Mike Chen', 'mike.chen@example.com', 'Technical question', 'Great content! I have a question about black hole formation. Can you provide more details?', 'new', '192.168.1.3', NULL, '2024-12-01 00:15:00', NULL, NULL),
(4, 'Md Anwar Hosen', 'azharanowar@gmail.com', '', 'dbdfxbfdxb', 'new', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 10:09:03', NULL, NULL),
(5, '123456789', 'goru@gmail.com', 'moira ja tui', 'morar moto kotha kos kn?', 'new', '::1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-07 11:37:25', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `navigation_menu`
--

CREATE TABLE `navigation_menu` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `url` varchar(255) NOT NULL,
  `target` varchar(20) DEFAULT '_self',
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `is_cta` tinyint(1) DEFAULT 0,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `navigation_menu`
--

INSERT INTO `navigation_menu` (`id`, `title`, `url`, `target`, `display_order`, `is_active`, `is_cta`, `parent_id`, `created_at`, `updated_at`) VALUES
(1, 'Home', 'index.php', '_self', 1, 1, 0, NULL, '2025-12-03 15:25:03', '2025-12-06 17:07:01'),
(2, 'Blogs', 'blogs.php', '_self', 2, 1, 0, NULL, '2025-12-03 15:25:03', '2025-12-06 17:07:01'),
(3, 'About Us', 'about.php', '_self', 3, 1, 0, NULL, '2025-12-03 15:25:03', '0000-00-00 00:00:00'),
(4, 'Contact Us', 'contact.php', '_self', 4, 1, 0, NULL, '2025-12-03 15:25:03', '2025-12-06 17:07:07'),
(5, 'Join Now', '#', '_self', 5, 1, 1, NULL, '2025-12-06 17:02:05', '2025-12-06 17:07:52');

-- --------------------------------------------------------

--
-- Stand-in structure for view `published_posts`
-- (See below for the actual view)
--
CREATE TABLE `published_posts` (
`id` int(11)
,`title` varchar(255)
,`slug` varchar(255)
,`excerpt` text
,`featured_image` varchar(255)
,`views` int(11)
,`published_at` timestamp
,`author_name` varchar(100)
,`author_username` varchar(50)
,`category_name` varchar(100)
,`category_slug` varchar(100)
);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('text','textarea','image','boolean','number','url') DEFAULT 'text',
  `setting_group` varchar(50) DEFAULT 'general',
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `setting_group`, `description`, `updated_at`, `updated_by`) VALUES
(1, 'site_name', 'PipilikaX', 'text', 'general', 'Website name', '2025-12-06 16:07:30', 1),
(2, 'site_tagline', 'Our Effort for Your Space Exploration', 'text', 'general', 'Website tagline', '2025-12-06 16:07:30', 1),
(3, 'site_logo', 'pipilika-logo.png', 'image', 'general', 'Main site logo', '2025-12-06 17:23:33', NULL),
(4, 'site_logo_white', 'pipilika-logo-main-white.png', 'image', 'general', 'White logo for dark backgrounds', '2025-12-06 17:23:33', NULL),
(5, 'site_favicon', 'pipilika-favicon.png', 'image', 'general', 'Site favicon', '2025-12-06 17:23:33', NULL),
(6, 'hero_welcome_title', 'Welcome to PipilikaX', 'text', 'homepage', 'Hero section main title', '2025-12-06 17:23:33', 1),
(7, 'hero_intro_text', 'Adventures beyond the stars, adrenaline that defies gravity – welcome to:', 'textarea', 'homepage', 'Hero introduction text', '2025-12-06 17:24:38', 1),
(8, 'hero_phrase_1', 'Explore our world.', 'text', 'homepage', 'Typing animation phrase 1', '2025-12-06 17:24:38', 1),
(9, 'hero_phrase_2', 'Discover new horizons.', 'text', 'homepage', 'Typing animation phrase 2', '2025-12-06 17:24:38', 1),
(10, 'hero_phrase_3', 'Unleash your curiosity.', 'text', 'homepage', 'Typing animation phrase 3', '2025-12-06 17:24:38', 1),
(11, 'hero_phrase_4', 'Journey into the unknown.', 'text', 'homepage', 'Typing animation phrase 4', '2025-12-06 17:24:38', 1),
(12, 'hero_phrase_5', 'Experience the extraordinary.', 'text', 'homepage', 'Typing animation phrase 5', '2025-12-06 17:24:38', 1),
(13, 'hero_cta_text', 'Join the Journey', 'text', 'homepage', 'Hero button text', '2025-12-06 17:23:33', 1),
(14, 'hero_cta_url', '#', 'text', 'homepage', 'Hero button URL', '2025-12-06 17:23:33', 1),
(15, 'about_title', 'Get to know PipilikaX', 'text', 'homepage', 'About section title', '2025-12-06 17:23:33', 1),
(16, 'about_text', 'Pipilika X is a visionary initiative driven by curiosity, exploration, and the pursuit of knowledge. Inspired by the determination of ants (pipilika in Sanskrit) and the bold ambition of pioneers like SpaceX, Pipilika X is on a mission to make complex information about Earth and the universe easily accessible to everyone.\r\n\r\nWe believe that knowledge should not be reserved for a few—it should flow freely, like stardust, reaching minds across the globe. Whether it\'s decoding the wonders of space, uncovering hidden patterns of our planet, or translating scientific breakthroughs into everyday language, Pipilika X is here to deliver insights that matter.\r\n\r\nOur journey isn\'t just about reaching the stars—it\'s about understanding them, and bringing that understanding back to Earth in the simplest, most engaging way possible.\r\n\r\nPipilika X – Know the world. Explore beyond.', 'textarea', 'homepage', 'About section content', '2025-12-06 17:23:33', 1),
(17, 'about_cta_text', 'Join the Journey Now', 'text', 'homepage', 'About button text', '2025-12-06 17:23:33', 1),
(18, 'footer_brand_name', 'PipilikaX', 'text', 'footer', 'Footer brand name', '2025-12-06 17:23:33', NULL),
(19, 'footer_copyright', 'Copyright © 2025 – All rights reserved.', 'text', 'footer', 'Copyright text', '2025-12-06 17:23:33', NULL),
(20, 'footer_github_url', 'https://github.com/azharanowar/pipilikaX', 'url', 'footer', 'GitHub repository link', '2025-12-06 17:23:33', NULL),
(21, 'contact_email', 'azharanowar@gmail.com', 'text', 'contact', 'Contact email', '2025-12-07 11:46:59', 1),
(22, 'contact_phone', '010-5149-3665', 'text', 'contact', 'Contact phone', '2025-12-07 11:46:59', 1),
(23, 'contact_address', 'Dong-Eui University, Busan, South Korea.', 'text', 'contact', 'Contact address', '2025-12-07 11:46:59', 1),
(24, 'facebook_url', 'https://fb.com/#', 'url', 'contact', 'Facebook page URL', '2025-12-07 11:46:59', 1),
(25, 'twitter_url', 'https://twitter.com/#', 'url', 'contact', 'Twitter profile URL', '2025-12-07 11:46:59', 1),
(26, 'linkedin_url', 'https://linkedin.com/in/#', 'url', 'contact', 'LinkedIn page URL', '2025-12-07 11:46:59', 1),
(27, 'github_url', 'https://github.com/azharanowar/pipilikaX', 'url', 'contact', 'GitHub repository URL', '2025-12-07 11:46:59', 1),
(28, 'about_page_subtitle', 'We are a passionate team dedicated to creating cutting-edge AI solutions that impact the future.', 'textarea', 'about', 'About page subtitle', '0000-00-00 00:00:00', NULL),
(29, 'header_cta_text', 'Join Now', 'text', 'header', 'Header CTA Button Text', '0000-00-00 00:00:00', NULL),
(30, 'header_cta_url', '#', 'url', 'header', 'Header CTA Button URL', '0000-00-00 00:00:00', NULL),
(31, 'contact_map_embed', 'https://maps.google.com/maps?q=Dong-Eui University, Busan, South Korea&t=k&z=13&ie=UTF8&iwloc=&output=embed', 'textarea', 'contact', 'Google Maps Embed URL for contact page', '2025-12-07 11:46:59', 1);

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `facebook_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `github_url` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `name`, `position`, `photo`, `bio`, `email`, `phone`, `facebook_url`, `linkedin_url`, `twitter_url`, `github_url`, `display_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Azhar Anowar', 'Founder & CEO', 'team-1.jpg', 'Visionary leader driving PipilikaX towards making space knowledge accessible to everyone.', 'azhar@pipilikax.com', NULL, 'https://facebook.com/azharanowar', 'https://www.linkedin.com/in/azharanowar/', 'https://x.com/AzharAnowar', 'https://github.com/azharanowar', 1, 1, '2025-12-03 15:25:04', '0000-00-00 00:00:00'),
(2, 'Md Arafat Hossain', 'Chief Technology Officer', 'team-2.jpg', 'Technology enthusiast building innovative solutions for space education.', 'arafat@pipilikax.com', NULL, 'https://www.facebook.com/sijan.khan4646', '#', '#', '#', 2, 1, '2025-12-03 15:25:04', '0000-00-00 00:00:00'),
(3, 'Al-Rafi Azad', 'Product Designer', 'team-3.jpg', 'Creating beautiful and intuitive designs that bring space closer to people.', 'rafi@pipilikax.com', NULL, 'https://www.facebook.com/alrafi.azad.9', '#', '#', '#', 3, 1, '2025-12-03 15:25:04', '0000-00-00 00:00:00'),
(4, 'Taohidul Islam', 'Marketing Lead', 'team-4.jpg', 'Spreading the word about space exploration and making PipilikaX accessible to all.', 'taohid@pipilikax.com', NULL, 'https://www.facebook.com/mdtaohid.binbhuiyan', '#', '#', '#', 4, 1, '2025-12-03 15:25:04', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `role` enum('admin','editor','author','subscriber') DEFAULT 'subscriber',
  `is_active` tinyint(1) DEFAULT 1,
  `avatar` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `role`, `is_active`, `avatar`, `bio`, `created_at`, `updated_at`, `last_login`) VALUES
(1, 'admin', 'admin@pipilikax.com', '$2y$10$SebxqUzYyqsHtoIGiP4Yp.kW8TEAc5cAY.haJr1NGZ8w6tW/p81hm', 'Azhar Anowar', 'admin', 1, '', 'System administrator', '2025-12-03 15:25:03', '2025-12-07 11:56:24', '2025-12-07 11:56:24'),
(2, 'azhar', 'azhar@pipilikax.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Azhar Anowar', 'editor', 1, NULL, 'Founder & CEO', '2025-12-03 15:25:03', '0000-00-00 00:00:00', NULL),
(3, 'arafat', 'arafat@pipilikax.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Md Arafat Hossain', 'author', 1, NULL, 'Chief Technology Officer', '2025-12-03 15:25:03', '0000-00-00 00:00:00', NULL),
(4, 'azharcodna', 'codna@gmail.com', '$2y$10$AlaH.yaC.SuCKZmjKy2ScujbwGE8cARGglajZuXMIJfZTS3PLokzS', 'c', 'subscriber', 1, '', '', '2025-12-07 11:44:15', '0000-00-00 00:00:00', NULL),
(5, 'mdtaohid', '1212@gmail.com', '$2y$10$ShVMmlr4.jsg2FqtGkbJDOuWsQPtVNAHyfufp2DnQozg8QeglY0wu', '', 'admin', 1, '', '12', '2025-12-07 11:45:39', '0000-00-00 00:00:00', NULL),
(6, 'user1', 'user1@gmail.com', '$2y$10$Q6dmXzLn3S4q/xpWX157RuOtZHeA77mV5k0TM8HqWk9PyR4C9qc/C', '', 'subscriber', 1, '', '', '2025-12-07 11:47:48', '2025-12-07 11:55:51', '2025-12-07 11:55:51'),
(7, 'user2', 'user2@gmail.com', '$2y$10$xTZh2l/1J92ju9ZZQ1orHepQtT/jH8rwab.iz1mm1mOuhJW1ifB1a', '', 'author', 1, '', '', '2025-12-07 11:48:11', '2025-12-07 11:50:04', '2025-12-07 11:50:04'),
(8, 'user3', 'user3@gmail.com', '$2y$10$05NOysoPBZX1r7H5n1tC8.5N2R15uAPshLRrGBdl.EALivy78bovW', '', 'editor', 1, '', '', '2025-12-07 11:48:38', '2025-12-07 11:51:40', '2025-12-07 11:51:40'),
(9, 'user4', 'user4@gmail.com', '$2y$10$tuK3Jtm/6yVpczjeW.Uw3uQrLoAQ30gOom0VD8ae.27Vt7RJzWrTa', '', 'admin', 1, '', '', '2025-12-07 11:49:33', '2025-12-07 11:53:03', '2025-12-07 11:53:03');

-- --------------------------------------------------------

--
-- Structure for view `active_navigation`
--
DROP TABLE IF EXISTS `active_navigation`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `active_navigation`  AS SELECT `navigation_menu`.`id` AS `id`, `navigation_menu`.`title` AS `title`, `navigation_menu`.`url` AS `url`, `navigation_menu`.`target` AS `target`, `navigation_menu`.`display_order` AS `display_order`, `navigation_menu`.`is_active` AS `is_active`, `navigation_menu`.`parent_id` AS `parent_id`, `navigation_menu`.`created_at` AS `created_at`, `navigation_menu`.`updated_at` AS `updated_at` FROM `navigation_menu` WHERE `navigation_menu`.`is_active` = 1 ORDER BY `navigation_menu`.`display_order` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `active_team`
--
DROP TABLE IF EXISTS `active_team`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `active_team`  AS SELECT `team_members`.`id` AS `id`, `team_members`.`name` AS `name`, `team_members`.`position` AS `position`, `team_members`.`photo` AS `photo`, `team_members`.`bio` AS `bio`, `team_members`.`email` AS `email`, `team_members`.`phone` AS `phone`, `team_members`.`facebook_url` AS `facebook_url`, `team_members`.`linkedin_url` AS `linkedin_url`, `team_members`.`twitter_url` AS `twitter_url`, `team_members`.`github_url` AS `github_url`, `team_members`.`display_order` AS `display_order`, `team_members`.`is_active` AS `is_active`, `team_members`.`created_at` AS `created_at`, `team_members`.`updated_at` AS `updated_at` FROM `team_members` WHERE `team_members`.`is_active` = 1 ORDER BY `team_members`.`display_order` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `published_posts`
--
DROP TABLE IF EXISTS `published_posts`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `published_posts`  AS SELECT `p`.`id` AS `id`, `p`.`title` AS `title`, `p`.`slug` AS `slug`, `p`.`excerpt` AS `excerpt`, `p`.`featured_image` AS `featured_image`, `p`.`views` AS `views`, `p`.`published_at` AS `published_at`, `u`.`full_name` AS `author_name`, `u`.`username` AS `author_username`, `c`.`name` AS `category_name`, `c`.`slug` AS `category_slug` FROM ((`blog_posts` `p` left join `users` `u` on(`p`.`author_id` = `u`.`id`)) left join `categories` `c` on(`p`.`category_id` = `c`.`id`)) WHERE `p`.`status` = 'published' ORDER BY `p`.`published_at` DESC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_entity` (`entity_type`,`entity_id`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_author` (`author_id`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_published_at` (`published_at`),
  ADD KEY `idx_posts_status_published` (`status`,`published_at`),
  ADD KEY `idx_posts_author_status` (`author_id`,`status`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_slug` (`slug`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `navigation_menu`
--
ALTER TABLE `navigation_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `idx_display_order` (`display_order`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_group` (`setting_group`),
  ADD KEY `idx_key` (`setting_key`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_display_order` (`display_order`),
  ADD KEY `idx_is_active` (`is_active`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `navigation_menu`
--
ALTER TABLE `navigation_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `blog_posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `navigation_menu`
--
ALTER TABLE `navigation_menu`
  ADD CONSTRAINT `navigation_menu_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `navigation_menu` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
