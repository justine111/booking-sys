-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 08:50 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `testdatabase`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `client_token` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact_no` varchar(12) DEFAULT NULL,
  `duration` varchar(100) NOT NULL,
  `message` varchar(255) NOT NULL,
  `check_in_date` datetime DEFAULT NULL,
  `check_out_date` datetime DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `booking_status` int(11) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `property_id`, `client_token`, `name`, `contact_no`, `duration`, `message`, `check_in_date`, `check_out_date`, `total_amount`, `booking_status`, `created_at`) VALUES
(1, 19, 'df006ba7da44bde73546ad41a5b92f6c', 'Resamae R. Telemban', '09321312321', '3 days and 4 nights', '', '2025-11-22 15:16:00', '2025-11-23 15:16:00', '2000.00', 6, '2025-11-22 15:16:08'),
(2, 18, 'ac191e12e94fee1da5027c6a6bdbc22d', 'Justine Bengson', '0912312321', '3 days and 4 nights', '', '2025-11-22 15:47:00', '2025-11-23 15:47:00', '2500.00', 5, '2025-11-22 15:47:54'),
(3, 18, '68502fec8f0cb936d388aa8f2c7f13f3', 'Joshua', '08908908', '3 days and 4 nights', '', '2025-11-22 15:49:00', '2025-11-23 15:49:00', '2500.00', 6, '2025-11-22 15:49:48');

-- --------------------------------------------------------

--
-- Table structure for table `booking_status`
--

CREATE TABLE `booking_status` (
  `id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `booking_status`
--

INSERT INTO `booking_status` (`id`, `description`, `date`) VALUES
(1, 'pending', '2025-11-15 21:53:10'),
(2, 'confirmed', '2025-11-15 21:53:10'),
(3, 'cancelled', '2025-11-15 21:53:27'),
(4, 'complete', '2025-11-15 21:53:27'),
(5, 'available', '2025-11-16 20:54:16'),
(6, 'booked', '2025-11-16 20:54:16');

-- --------------------------------------------------------

--
-- Table structure for table `hosts`
--

CREATE TABLE `hosts` (
  `host_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `client_token` varchar(255) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `payment_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','paid','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `property_id`, `client_token`, `payment_method`, `amount_paid`, `payment_date`, `status`) VALUES
(1, 19, 'df006ba7da44bde73546ad41a5b92f6c', 'Cash', '2000.00', '2025-11-22 15:16:08', 'pending'),
(2, 19, 'df006ba7da44bde73546ad41a5b92f6c', 'Cash', '2000.00', '2025-11-22 15:47:15', ''),
(3, 18, 'ac191e12e94fee1da5027c6a6bdbc22d', 'Cash', '2500.00', '2025-11-22 15:47:54', 'pending'),
(4, 18, 'ac191e12e94fee1da5027c6a6bdbc22d', 'Cash', '2500.00', '2025-11-22 15:48:42', ''),
(5, 18, '68502fec8f0cb936d388aa8f2c7f13f3', 'Cash', '2500.00', '2025-11-22 15:49:48', 'pending'),
(6, 18, '68502fec8f0cb936d388aa8f2c7f13f3', 'Cash', '2500.00', '2025-11-22 15:50:02', '');

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `property_id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `price_per_night` decimal(10,2) DEFAULT NULL,
  `amenities` text DEFAULT NULL,
  `img1` varchar(100) DEFAULT NULL,
  `img2` varchar(100) DEFAULT NULL,
  `img3` varchar(100) DEFAULT NULL,
  `img4` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`property_id`, `host_id`, `title`, `description`, `address`, `city`, `price_per_night`, `amenities`, `img1`, `img2`, `img3`, `img4`, `status`, `created_at`) VALUES
(18, 1, 'Joshua house', 'Fresn and clean with high mountain view,', 'Brgy Naga-asan', 'Babatngon', '2500.00', '', 'hotel_img_6919cf7d0e10f.jpg', 'hotel_img_6919cf7d0e262.jpg', 'hotel_img_6919cf7d0e3ad.jpg', 'hotel_img_6919cf7d0e4d4.jpg', 6, '2025-11-16 21:19:57'),
(19, 1, 'Hotel Ranelo', 'test description', 'Brgy 110 Utap Tacloban Cityy', 'Tacloban', '2000.00', 'WiFi', 'hotel_img_69214a130197a.png', 'hotel_img_69214a1301ba2.png', 'hotel_img_69214a1301d46.jpg', 'hotel_img_69214a1301ed0.jpg', 6, '2025-11-22 13:28:51');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` int(11) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `user_type`, `phone_number`, `created_at`) VALUES
(1, 'James Doe', 'james@gmail.com', 'b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86', 1, NULL, '2025-06-08 17:18:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `user_role_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_roles`
--

INSERT INTO `user_roles` (`user_role_id`, `name`) VALUES
(1, 'admin'),
(2, 'moderator');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `booking_status`
--
ALTER TABLE `booking_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hosts`
--
ALTER TABLE `hosts`
  ADD PRIMARY KEY (`host_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`property_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`user_role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking_status`
--
ALTER TABLE `booking_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
