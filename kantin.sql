-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 25, 2025 at 07:33 AM
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
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(10, 'Makanan Ringan'),
(11, 'Minuman'),
(12, 'Makanan Berat'),
(17, 'Produk Lokal'),
(18, 'Sehat'),
(19, 'Produk Mamah Agung');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `category` varchar(255) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `price`, `image`, `created_add`, `category`, `stock`, `description`) VALUES
(83, 'Teh Gelas', 1000.00, 'images/1743776836_teh-gelas_teh-gelas-original-cup-180ml_full02.webp', '2025-04-04 14:27:16', 'Minuman', 100, ''),
(86, 'Susu Milo', 3500.00, 'images/1743777530_eec496e527ff0f8ba57692df7e622e49.jpg_720x720q80.jpg', '2025-04-04 14:38:50', 'Minuman', 100, ''),
(89, 'Chitato Lite', 1000.00, 'images/1743811354_Chitato Snack Potato Lite Nori Seaweed 120G.jpeg', '2025-04-05 00:02:34', 'Makanan Ringan', 60, ''),
(90, 'Fruit Tea', 5000.00, 'images/1743811423_Fruit Tea Pet Black Currant 500Ml.jpeg', '2025-04-05 00:03:43', 'Minuman', 0, ''),
(91, 'Ultra Milk Full Cream', 3000.00, 'images/1743811582_Ultra Milk Plain 200Ml.jpeg', '2025-04-05 00:06:22', 'Minuman', 100, ''),
(92, 'Seblak Spesial', 10000.00, 'images/1743811677_download (9).jpeg', '2025-04-05 00:07:57', 'Makanan Berat', 100, ''),
(93, 'Soto Ayam', 7000.00, 'images/1743811774_Soto.jpeg', '2025-04-05 00:09:34', 'Makanan Berat', 2000, ''),
(94, 'Ayam Geprek', 12000.00, 'images/1743811851_( Donna Jos Sia ).jpeg', '2025-04-05 00:10:51', 'Makanan Berat', 95, ''),
(100, 'Sari Gandum', 2000.00, 'images/1744508882_Roma Sari Gandum Coklat 39 gr.jpeg', '2025-04-13 01:48:02', 'Makanan Ringan', 20, ''),
(101, 'Pocari Sweet', 4500.00, 'images/1744508960_b20fb891-3b9c-4b84-9fe2-b4f7998c0fc0.jpeg', '2025-04-13 01:49:20', 'Minuman', 30, ''),
(102, 'Taro Net', 2000.00, 'images/1744509036_TARO NET SEAWEED 32 GR.jpeg', '2025-04-13 01:50:36', 'Makanan Ringan', 10, ''),
(103, 'Cireng Isi Ayam', 2000.00, 'images/1744509325_d8950b5ea7b45c72e8410608f611f0d3.jpg', '2025-04-13 01:55:25', 'Produk Lokal', 1000, ''),
(104, 'Qtela', 3500.00, 'images/1744509441_Q - TELA BARBEQUE 180 GR.jpeg', '2025-04-13 01:57:21', 'Makanan Ringan', 199, ''),
(105, 'Bakwan Racing', 500.00, 'images/1744509503_Bala-bala.jpeg', '2025-04-13 01:58:23', 'Produk Lokal', 200, ''),
(106, 'Le Mineral', 3200.00, 'images/1744509634_LE MINERALE PET 1500 ML - AIR MINERAL.jpeg', '2025-04-13 02:00:34', 'Sehat', 195, ''),
(107, 'Good Days', 7000.00, 'images/1744509709_748e6eb8-0e09-4c04-a255-fe8636d9aff9.jpeg', '2025-04-13 02:01:49', 'Minuman', 1, ''),
(108, 'C1000 Lemon', 10000.00, 'images/1744509803_6c6026f5e2694a45db22b68f4ac37951.jpg', '2025-04-13 02:03:23', 'Sehat', 7, ''),
(109, 'Rengginang', 20000.00, 'images/1744509955_Desain kemasan snack, Desain kemasan standing….jpeg', '2025-04-13 02:05:55', 'Produk Mamah Agung', 0, ''),
(110, 'Produk', 1000.00, 'images/1745468286_Ainun Cake.jpg', '2025-04-24 04:18:06', 'Sehat', 20, '');

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
(122, 38, NULL, NULL, 12000.00, 'pending', '2025-04-16 00:53:48'),
(123, 38, NULL, NULL, 36000.00, 'canceled', '2025-04-19 05:24:02'),
(124, 38, NULL, NULL, 6400.00, 'canceled', '2025-04-19 05:30:07'),
(125, 38, NULL, NULL, 3200.00, 'pending', '2025-04-19 08:28:38'),
(126, 38, NULL, NULL, 24000.00, 'pending', '2025-04-20 05:48:44'),
(127, 38, NULL, NULL, 72000.00, 'pending', '2025-04-20 05:55:56'),
(128, 40, NULL, NULL, 7000.00, 'pending', '2025-04-21 11:46:44'),
(129, 38, NULL, NULL, 2000.00, 'pending', '2025-04-22 15:54:01'),
(130, 38, NULL, NULL, 3500.00, 'pending', '2025-04-22 15:54:33'),
(131, 38, NULL, NULL, 3200.00, 'pending', '2025-04-22 15:55:31'),
(132, 38, NULL, NULL, 21000.00, 'pending', '2025-04-23 11:51:22'),
(133, 38, NULL, NULL, 7000.00, 'pending', '2025-04-24 00:32:07'),
(134, 42, NULL, NULL, 14000.00, 'canceled', '2025-04-24 04:12:14'),
(135, 42, NULL, NULL, 21000.00, 'pending', '2025-04-24 04:15:30'),
(136, 38, NULL, NULL, 20000.00, 'pending', '2025-04-24 06:48:57');

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
(114, 122, 94, 1, 12000.00),
(115, 123, 94, 3, 12000.00),
(116, 124, 106, 2, 3200.00),
(117, 125, 106, 1, 3200.00),
(118, 126, 94, 2, 12000.00),
(119, 127, 94, 6, 12000.00),
(120, 128, 107, 1, 7000.00),
(121, 129, 102, 1, 2000.00),
(122, 130, 104, 1, 3500.00),
(123, 131, 106, 1, 3200.00),
(124, 132, 107, 3, 7000.00),
(125, 133, 107, 1, 7000.00),
(126, 134, 107, 2, 7000.00),
(127, 135, 107, 3, 7000.00),
(128, 136, 108, 2, 10000.00);

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
  `payment_method` varchar(50) NOT NULL,
  `profit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `transaction_date` date DEFAULT NULL,
  `catatan` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `order_id`, `total_price`, `status`, `created_at`, `payment_method`, `profit`, `transaction_date`, `catatan`) VALUES
(79, 38, 122, 12000.00, 'completed', '2025-04-16 00:53:48', 'ShopeePay', 0.00, NULL, 'Rps 1'),
(80, 38, 123, 36000.00, 'canceled', '2025-04-19 05:24:02', 'COD', 0.00, NULL, 'Masjid'),
(81, 38, 124, 6400.00, 'canceled', '2025-04-19 05:30:07', 'ShopeePay', 0.00, NULL, 'teffa'),
(82, 38, 125, 3200.00, 'completed', '2025-04-19 08:28:38', 'COD', 0.00, NULL, 'Tu'),
(83, 38, 126, 24000.00, 'pending', '2025-04-20 05:48:44', 'COD', 0.00, NULL, 'rpl satu'),
(84, 38, 127, 72000.00, 'pending', '2025-04-20 05:55:56', 'COD', 0.00, NULL, 'jjnjnjn'),
(85, 40, 128, 7000.00, 'completed', '2025-04-21 11:46:44', 'ShopeePay', 0.00, NULL, 'Rpl2'),
(86, 38, 129, 2000.00, 'completed', '2025-04-22 15:54:01', 'COD', 0.00, NULL, '10 rpl 2'),
(87, 38, 130, 3500.00, 'completed', '2025-04-22 15:54:33', 'Dana', 0.00, NULL, '12 rpl 1'),
(88, 38, 131, 3200.00, 'completed', '2025-04-22 15:55:31', 'ShopeePay', 0.00, NULL, '11 rpl 2'),
(89, 38, 132, 21000.00, 'completed', '2025-04-23 11:51:22', 'COD', 0.00, NULL, 'teffa'),
(90, 38, 133, 7000.00, 'completed', '2025-04-24 00:32:07', 'ShopeePay', 0.00, NULL, 'rpl 1'),
(91, 42, 134, 14000.00, 'pending', '2025-04-24 04:12:14', 'COD', 0.00, NULL, '12Rpl1'),
(92, 42, 135, 21000.00, 'completed', '2025-04-24 04:15:30', 'ShopeePay', 0.00, NULL, 'Tefa'),
(93, 38, 136, 20000.00, 'completed', '2025-04-24 06:48:57', 'COD', 0.00, NULL, 'Tefa');

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
(30, 'super admin', '$2y$10$EMM8/LyVWir2OhjZno8c1ew4uNm27q/tV3grZJ7648JToIwPB1xi.', 'admin', '2025-03-17 06:43:31', 'aisyah@gmail.com', NULL),
(38, 'agung01', '$2y$10$KgjBzUUjAsTl1DGJsZUzk.P/cM3pp4ZqtsG0Iv3Nk2qcwm9XN4duG', 'user', '2025-04-16 00:47:27', 'agungpesn@gmail.com', 'uploads/agung.jpg'),
(39, 'User12', '$2y$10$UbeRfpjhqR3s2rOppupPoOCai08PMv0LThjdsgGRP5Q3tPXVJx26.', 'user', '2025-04-21 10:42:14', 'ronaldo@gmail.com', NULL),
(40, 'Michie02', '$2y$10$kX1908Ha2jMiAqqp.3hYxe60Y.f6KmbxrKLw8F3AXXYEucWjGYKNK', 'user', '2025-04-21 10:43:09', 'bianca@gmail.com', 'uploads/download (12).jpeg'),
(41, 'admin123', '$2y$10$/ga.xqfn4jQfggTa5gzfWeVhTYkBrpQLseQRNSgYb5jsWJKb3uegS', 'admin', '2025-04-22 15:50:53', 'testing@gmail.com', NULL),
(42, 'cahya', '$2y$10$rXR.N4NZAeYdDjnVtHZxRer1o7SRawmVfgVJQdHCZZPJ4wXtatuya', 'user', '2025-04-24 03:59:23', 'cahya@gmail.com', 'uploads/icon.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
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
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

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
