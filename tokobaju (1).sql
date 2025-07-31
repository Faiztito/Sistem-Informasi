-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2025 at 02:03 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tokobaju`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `size` varchar(10) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','dipacking','dikirim','selesai') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `email`, `phone`, `address`, `total_price`, `status`, `created_at`) VALUES
(27, 2, 'Muhammad Azmi Fadhlurrohman', 'm.azmi.f12345@gmail.com', '081220562243', 'jln bbk tarogong no 435', 215000.00, 'pending', '2025-07-31 18:31:35');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(35, 27, 17, 1, 200000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `stock`, `category`, `image`, `created_at`) VALUES
(3, 'Baju Hitam Wanita', 'Hanya untuk wanita ukuran S,M dan L', 200000.00, 50, 'Baju', 'baju1.jpg', '2025-07-21 12:20:36'),
(4, 'Baju Putih Wanita', 'Hanya untuk wanita ukuran s,M dan L', 350000.00, 50, 'Baju', 'baju4.jpg', '2025-07-21 12:21:17'),
(5, 'black Dark', 'sadasd', 350000.00, 50, 'Baju', 'baju3.jpg', '2025-07-21 13:51:32'),
(6, 'Hitam Black', 'Bagus banget', 320000.00, 250, 'Baju', 'baju2.jpg', '2025-07-23 07:34:03'),
(7, 'baju hitam pria', 'cocok untuk sehari hari', 200000.00, 25, 'Baju', 'baju5.jpg', '2025-07-31 07:19:18'),
(8, 'baju pria', 'cocok untuk  sehari hari', 200000.00, 25, 'Baju', 'baju6.jpg', '2025-07-31 07:19:48'),
(9, 'baju pria', 'cocok untuk sehari hari', 200000.00, 25, 'Baju', 'baju7.jpg', '2025-07-31 07:20:10'),
(10, 'baju pria', 'cocok untuk sehari hari', 220000.00, 25, 'Baju', 'baju8.jpg', '2025-07-31 07:21:15'),
(11, 'Celana Pria 1', 'cocok untuk hangout', 150000.00, 10, 'Celana', 'celana3.jpg', '2025-07-31 07:26:51'),
(12, 'Celana Pria 2', 'cocok untuk meeting', 250000.00, 10, 'Celana', 'celana4.jpg', '2025-07-31 07:27:17'),
(13, 'celana wanita 1', 'cocok untuk main', 300000.00, 25, 'Celana', 'celana1.jpg', '2025-07-31 07:27:41'),
(14, 'celana wanita 2', 'cocok untuk hangout', 320000.00, 25, 'Celana', 'celana2.jpg', '2025-07-31 07:28:02'),
(15, 'kemeja pria 1', 'untuk meeting', 200000.00, 25, 'Kemeja', 'kemeja11.jpg', '2025-07-31 07:32:09'),
(16, 'kemeja pria 2', 'untuk hangout', 200000.00, 25, 'Kemeja', 'kemeja22.jpg', '2025-07-31 07:32:25'),
(17, 'kemeja pria 3', 'untuk nikahan', 200000.00, 25, 'Kemeja', 'kemeja33.jpg', '2025-07-31 07:32:39'),
(18, 'kemeja pria 4', 'untuk ke alam', 220000.00, 25, 'Kemeja', 'kemeja44.jpg', '2025-07-31 07:32:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `role`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2025-07-21 01:36:35', 'admin'),
(2, 'azmi', '$2y$10$DpRGNsp3VI/SWpDMSVQERO3WFmIyV9w3riVP2ey8BGd7yNHxkW9LK', '2025-07-21 01:52:37', 'admin'),
(8, 'gudir', '$2y$10$DpI/.PmdioOX4ThAGp/axOpdVy36NvSnqQNviXJySDAn3oR2YH8kK', '2025-07-21 02:31:19', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
