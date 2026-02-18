-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 18, 2026 at 06:41 PM
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
-- Database: `inventory_and_sale_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `unit` enum('kg','Sack','Pack') NOT NULL,
  `category` enum('Livestock','Dog Food','Cat Food') DEFAULT NULL,
  `quantity_in_stock` int(11) NOT NULL DEFAULT 0,
  `reorder_level` int(11) NOT NULL DEFAULT 0,
  `capital_price` double(10,2) NOT NULL,
  `price` double(10,2) NOT NULL,
  `status` enum('In Stock','Low Stock','Sold Out') DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `unit`, `category`, `quantity_in_stock`, `reorder_level`, `capital_price`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Premium Dog Food', 'kg', 'Dog Food', 50, 10, 20.00, 30.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(2, 'Chicken Feed', 'Sack', 'Livestock', 100, 20, 15.00, 25.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(3, 'Cat Delight', 'Pack', 'Cat Food', 30, 5, 10.00, 18.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(4, 'Beef Feed', 'Sack', 'Livestock', 80, 15, 25.00, 40.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(5, 'Salmon Cat Food', 'kg', 'Cat Food', 25, 5, 12.00, 20.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(6, 'Puppy Starter', 'Pack', 'Dog Food', 40, 10, 18.00, 28.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(7, 'Organic Chicken Feed', 'Sack', 'Livestock', 60, 10, 17.00, 30.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(8, 'Kitty Crunch', 'kg', 'Cat Food', 35, 5, 11.00, 19.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(9, 'Lamb Feed', 'Sack', 'Livestock', 70, 15, 22.00, 35.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(10, 'Adult Dog Meal', 'kg', 'Dog Food', 45, 10, 20.00, 32.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(11, 'Fish Cat Food', 'Pack', 'Cat Food', 20, 5, 9.00, 16.00, 'Low Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(12, 'Goat Feed', 'Sack', 'Livestock', 55, 10, 18.00, 28.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(13, 'Senior Dog Food', 'kg', 'Dog Food', 30, 8, 22.00, 35.00, 'Low Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(14, 'Rabbit Feed', 'Sack', 'Livestock', 50, 10, 14.00, 22.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(15, 'Kitten Delight', 'Pack', 'Cat Food', 15, 5, 8.00, 14.00, 'Low Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(16, 'Beef Dog Food', 'kg', 'Dog Food', 40, 10, 25.00, 38.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(17, 'Chick Starter Feed', 'Sack', 'Livestock', 90, 20, 12.00, 20.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(18, 'Salmon Treats', 'Pack', 'Cat Food', 10, 5, 7.00, 12.00, 'Low Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(19, 'Horse Feed', 'Sack', 'Livestock', 100, 25, 30.00, 50.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(20, 'Adult Cat Meal', 'kg', 'Cat Food', 25, 5, 13.00, 22.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(21, 'Chicken Dog Food', 'kg', 'Dog Food', 35, 10, 18.00, 28.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(22, 'Duck Feed', 'Sack', 'Livestock', 60, 15, 16.00, 28.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(23, 'Turkey Cat Food', 'Pack', 'Cat Food', 20, 5, 10.00, 18.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(24, 'Puppy Snack', 'Pack', 'Dog Food', 30, 8, 15.00, 25.00, 'Low Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(25, 'Calf Feed', 'Sack', 'Livestock', 50, 10, 20.00, 32.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(26, 'Tuna Cat Food', 'kg', 'Cat Food', 15, 5, 12.00, 20.00, 'Low Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(27, 'Adult Horse Feed', 'Sack', 'Livestock', 70, 15, 28.00, 45.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(28, 'Senior Cat Food', 'Pack', 'Cat Food', 10, 5, 9.00, 16.00, 'Low Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(29, 'Dog Treats', 'kg', 'Dog Food', 40, 10, 14.00, 22.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(30, 'Organic Goat Feed', 'Sack', 'Livestock', 60, 15, 18.00, 30.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(31, 'Kitty Snacks', 'Pack', 'Cat Food', 12, 5, 8.00, 14.00, 'Low Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(32, 'Beef Cattle Feed', 'Sack', 'Livestock', 80, 20, 25.00, 40.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(33, 'Chicken Cat Food', 'kg', 'Cat Food', 18, 5, 11.00, 18.00, 'Low Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(34, 'Adult Dog Kibble', 'kg', 'Dog Food', 45, 10, 20.00, 32.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(35, 'Lamb Cat Food', 'Pack', 'Cat Food', 15, 5, 10.00, 18.00, 'Low Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(36, 'Organic Chicken Dog Feed', 'Sack', 'Dog Food', 50, 15, 22.00, 35.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(37, 'Rabbit Cat Food', 'Pack', 'Cat Food', 12, 5, 9.00, 16.00, 'Low Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(38, 'Calf Dog Food', 'kg', 'Dog Food', 35, 10, 18.00, 28.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(39, 'Duck Cat Food', 'Pack', 'Cat Food', 10, 5, 8.00, 14.00, 'Low Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(40, 'Turkey Dog Food', 'kg', 'Dog Food', 30, 8, 20.00, 32.00, 'Low Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(41, 'Horse Cat Food', 'Pack', 'Cat Food', 8, 5, 7.00, 12.00, 'Low Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21'),
(42, 'Fish Dog Food', 'kg', 'Dog Food', 40, 10, 18.00, 28.00, 'In Stock', '2026-02-19 01:34:21', '2026-02-19 01:34:21');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `total_price` double(10,2) NOT NULL,
  `total_capital` double(10,2) NOT NULL,
  `profit` double(10,2) NOT NULL,
  `date_sold` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('admin','superadmin','','') NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`) VALUES
(2, 'superadmin', '$2y$10$GMrsGHuGQXYnKm56NfBqWOLm8qpcUOIQbEfSCJRwdfNpvP/5185kC', 'superadmin', '2026-02-19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

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
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
