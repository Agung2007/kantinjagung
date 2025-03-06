-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 06, 2025 at 02:38 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kantin`
--

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `admin_id` int NOT NULL,
  `message` text NOT NULL,
  `sender` enum('user','admin') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `user_id`, `admin_id`, `message`, `sender`, `created_at`) VALUES
(7, 10, 1, 'p', 'user', '2025-02-27 02:19:46'),
(8, 10, 1, 'iya', 'user', '2025-03-05 06:58:27'),
(9, 10, 5, 'iya', 'admin', '2025-03-05 07:12:30'),
(10, 10, 1, 'p', 'user', '2025-03-05 07:12:38'),
(11, 10, 5, 'c', 'admin', '2025-03-05 07:12:45');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `price`, `image`, `created_add`) VALUES
(40, 'Ayam Geprek', 15000.00, 'images/1741062542_52ca73c1-5103-4ea3-ace1-3a3b198e9d50.jpeg', '2025-03-04 04:29:02'),
(41, 'ICE MATCHA', 10000.00, 'images/1741062575_Download premium image of Refreshing iced matcha green tea by Pinn about matcha in plastic cup, ice matcha, iced matcha, matcha, and iced matcha cup 15197288.jpeg', '2025-03-04 04:29:35'),
(42, 'Soto Spesial', 20000.00, 'images/1741062611_Soto Ayam Recipe (Indonesian Chicken Soup with Vermicelli).jpeg', '2025-03-04 04:30:11'),
(43, 'Ayam Bakar', 35000.00, 'images/1741062634_Resep Ayam Taliwang Bakar Teflon dari @yscooking.jpeg', '2025-03-04 04:30:34'),
(44, 'Baso Aci', 5000.00, 'images/1741073990_Baksoaci_jando.jpeg', '2025-03-04 07:39:50'),
(45, 'Baso ', 10000.00, 'images/1741074031_baso.jpg', '2025-03-04 07:40:31'),
(46, 'Sosis Bakar', 2000.00, 'images/1741074067_Resep Sosis Bakar oleh Dada.jpeg', '2025-03-04 07:41:07'),
(47, 'Mie Ayam', 12000.00, 'images/1741074091_94821a54-cce8-4369-a10e-a3288b24ec25.jpeg', '2025-03-04 07:41:31');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `menu_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','canceled') DEFAULT 'pending',
  `order_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `menu_id`, `quantity`, `total_price`, `status`, `order_date`) VALUES
(52, 10, NULL, NULL, 100000.00, 'pending', '2025-02-25 11:37:06'),
(53, 10, NULL, NULL, 5000.00, 'pending', '2025-02-26 03:57:26'),
(54, 10, NULL, NULL, 1000.00, 'pending', '2025-02-26 07:19:49'),
(55, 11, NULL, NULL, 10000.00, 'pending', '2025-02-27 03:33:13'),
(60, 11, NULL, NULL, 10000.00, 'pending', '2025-02-27 03:47:58'),
(61, 11, NULL, NULL, 10000.00, 'pending', '2025-02-27 04:02:38'),
(62, 11, NULL, NULL, 20000.00, 'pending', '2025-02-27 04:05:25'),
(63, 11, NULL, NULL, 10000.00, 'pending', '2025-02-27 04:07:34'),
(64, 10, NULL, NULL, 20000.00, 'pending', '2025-02-27 06:17:11'),
(65, 10, NULL, NULL, 10000.00, 'pending', '2025-02-27 06:54:42'),
(66, 10, NULL, NULL, 100000.00, 'pending', '2025-02-27 08:09:16'),
(67, 5, NULL, NULL, 10000.00, 'pending', '2025-02-28 11:01:20'),
(68, 10, NULL, NULL, 10000.00, 'pending', '2025-03-03 02:43:24'),
(69, 10, NULL, NULL, 12340000.00, 'pending', '2025-03-04 04:10:25'),
(70, 10, NULL, NULL, 10000.00, 'pending', '2025-03-04 06:03:39'),
(71, 10, NULL, NULL, 15000.00, 'pending', '2025-03-05 06:18:57'),
(72, 10, NULL, NULL, 15000.00, 'pending', '2025-03-05 06:20:41'),
(73, 10, NULL, NULL, 15000.00, 'pending', '2025-03-05 07:51:30');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `menu_id` int DEFAULT '1',
  `quantity` int DEFAULT '0',
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `menu_id`, `quantity`, `price`) VALUES
(62, 70, 41, 1, 10000.00),
(63, 71, 40, 1, 15000.00),
(64, 72, 40, 1, 15000.00),
(65, 73, 40, 1, 15000.00);

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `order_id` int DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_method` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `order_id`, `total_price`, `status`, `created_at`, `payment_method`) VALUES
(18, 11, 61, 10000.00, 'completed', '2025-02-27 04:02:38', 'Dana'),
(20, 11, 63, 10000.00, 'pending', '2025-02-27 04:07:34', 'COD'),
(21, 10, 64, 20000.00, 'pending', '2025-02-27 06:17:11', 'Dana'),
(22, 10, 65, 10000.00, 'pending', '2025-02-27 06:54:42', 'Shope.pay'),
(23, 10, 66, 100000.00, 'completed', '2025-02-27 08:09:16', 'COD'),
(24, 5, 67, 10000.00, 'processed', '2025-02-28 11:01:20', 'Dana'),
(25, 10, 68, 10000.00, 'completed', '2025-03-03 02:43:24', 'Dana'),
(26, 10, 69, 12340000.00, 'processed', '2025-03-04 04:10:25', 'Shope.pay'),
(27, 10, 70, 10000.00, 'processed', '2025-03-04 06:03:39', 'COD'),
(28, 10, 71, 15000.00, 'pending', '2025-03-05 06:18:57', 'COD'),
(29, 10, 72, 15000.00, 'pending', '2025-03-05 06:20:41', 'ShopeePay'),
(30, 10, 73, 15000.00, 'processed', '2025-03-05 07:51:31', 'ShopeePay');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `email`, `profile_picture`) VALUES
(5, 'agung123', '$2y$10$Wzu0McKyFcCQL0FN2EHj/eoPefxwUuxae9Q4COAQxrfuOB7xXzXjK', 'admin', '2025-02-19 06:16:36', 'aisyah@gmail.com', 'uploads/download.jpeg'),
(10, 'super agung', '$2y$10$QHmXzKZ/Z6Bv9fYj.3TO7.MKRcNJ/BPFpPijvRpFyQS4fdoY8ZwRa', 'user', '2025-02-25 10:48:26', 'cristianoagung@gmail.com', 'uploads/WhatsApp Image 2025-02-04 at 11.46.43_0b6f5dd2.jpg'),
(11, 'user22', '$2y$10$8C8s5M.LUZ923/.67S2VsOU4V3yccipFmQYwf79JnvKfmegD5DVNO', 'user', '2025-02-27 02:37:15', 'aaaisyah@gmail.com', 'uploads/WhatsApp Image 2025-02-04 at 11.46.43_0b6f5dd2.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_id` (`menu_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
