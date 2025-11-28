-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 28, 2025 at 06:13 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kelas_profile`
--

-- --------------------------------------------------------

--
-- Table structure for table `class_structure`
--

CREATE TABLE `class_structure` (
  `id` int NOT NULL,
  `position` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `photo` varchar(255) DEFAULT 'default.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `class_structure`
--

INSERT INTO `class_structure` (`id`, `position`, `name`, `photo`) VALUES
(1, 'Wali Kelas', 'Zia Imam Perdana, S.Kom.', '1763702755_default.png'),
(2, 'Wakil KM', 'Butsaina Dzurwa Mumtaza', '1763702771_default.png'),
(3, 'KM', 'Bintang Rizky Kurnia', '1764309180_default.png'),
(4, 'Bendahara 1', 'Annida Khoerunisa', '1763703273_default.png'),
(5, 'Bendahara 2', 'Ristian Dwi Yanto', '1764309191_default.png'),
(6, 'Sekretaris 1', 'Mita', '1763707700_default.png'),
(8, 'Humas', 'Sinta Meilinda', '1763703364_default.png'),
(9, 'Seksi Keamanan', 'Muhammad Fahri Al Hidayat', '1763703418_default.png'),
(10, 'Seksi Kerohanian', 'Nur Afni Herawati', '1763703479_default.png'),
(11, 'Seksi Upacara', 'Natasya Saputri', '1763703519_default.png'),
(12, 'Seksi Kesehatan', 'Desi Nur Lestari', '1763703578_default.png'),
(13, 'Seksi Olahraga', 'Muhamad Afdzan Sbastian', '1763703614_default.png'),
(14, 'Seksi Kewirausahaan', 'Meili Rizkina Putri', '1763703677_default.png'),
(15, 'Seksi Kesenian', 'Otniel Gabriel Shen Cou Lambagu', '1763703707_default.png'),
(16, 'Seksi Absensi', 'Airin Maulinda', '1763703749_default.png'),
(17, 'Sekretaris 2', 'Yasmin Amila Dewi', '1763703163_default.png'),
(18, 'Anggota Kelas', 'Ahmad Refan', '1763703163_default.png'),
(19, 'Anggota Kelas', 'Agni Rahayu', '1763703163_default.png'),
(20, 'Anggota Kelas', 'Aliya Khoerunisa', '1763703163_default.png'),
(21, 'Anggota Kelas', 'Alyssa Sri Nurdewi', '1763703163_default.png'),
(27, 'Anggota Kelas', 'Alyssa Sri Nurdewi', '1763703163_default.png'),
(28, 'Anggota Kelas', 'Alyssa Sri Nurdewi', '1763703163_default.png'),
(29, 'Anggota Kelas', 'Alyssa Sri Nurdewi', '1763703163_default.png'),
(30, 'Anggota Kelas', 'Anisa Paradina Agustin', '1763703163_default.png'),
(31, 'Anggota Kelas', 'Denia Nurfalah', '1763703163_default.png'),
(32, 'Anggota Kelas', 'Desinta Nisri Kusuma', '1763703163_default.png'),
(33, 'Anggota Kelas', 'Fauzan Karima', '1763703163_default.png'),
(34, 'Anggota Kelas', 'Gibran Syahreza', '1763703163_default.png'),
(35, 'Anggota Kelas', 'Hendri Maulana Yusuf', '1763703163_default.png'),
(36, 'Anggota Kelas', 'Moni Astriyani', '1763703163_default.png'),
(37, 'Anggota Kelas', 'Muhammad Nafis Shiddiq', '1763703163_default.png'),
(38, 'Anggota Kelas', 'Nayara Khansa Zenina', '1763703163_default.png'),
(39, 'Anggota Kelas', 'Nazwa Fitria Nabila', '1763703163_default.png'),
(40, 'Anggota Kelas', 'Ragil Agustino Ananda Suryanto', '1763703163_default.png'),
(41, 'Anggota Kelas', 'Raihan Shandy Pratama', '1763703163_default.png'),
(42, 'Anggota Kelas', 'Razief Afkar Rusli', '1763703163_default.png'),
(43, 'Anggota Kelas', 'Rizal Andhika Wijaya', '1763703163_default.png'),
(44, 'Anggota Kelas', 'Zahra Agustin Putri', '1763703163_default.png');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `type` enum('photo','video') DEFAULT NULL,
  `file_path` varchar(500) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `type`, `file_path`, `description`, `created_at`) VALUES
(2, 1, 'photo', 'uploads/1763956574_7019aeae-9e58-450d-af97-4c2d4169a9a0.jpg', 'Zero Waste & Zero Sugar di adakan di Smp 2 Purwakarta', '2025-11-24 03:56:14');

-- --------------------------------------------------------

--
-- Table structure for table `post_comments`
--

CREATE TABLE `post_comments` (
  `id` int NOT NULL,
  `post_id` int DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `id` int NOT NULL,
  `post_id` int DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`id`, `post_id`, `user_name`, `created_at`) VALUES
(6, 2, 'afdzan', '2025-11-24 03:58:20');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','member') DEFAULT 'member',
  `profile_pic` varchar(255) DEFAULT 'default.jpg',
  `bio` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `profile_pic`, `bio`) VALUES
(1, 'admin', '$2a$12$KyNkr2k8GiGOvPQyA4WNzuCDpxXMDUo77N3kubMk/G1kZEy/BTBcG', 'admin', '1764309271_default.png', 'Selamat Datang di web XI RPL\r\n\r\nDibuat oleh: Muhamad afdzan sbastian (XI RPL)');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `class_structure`
--
ALTER TABLE `class_structure`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`post_id`,`user_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `class_structure`
--
ALTER TABLE `class_structure`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `post_likes`
--
ALTER TABLE `post_likes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `post_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
