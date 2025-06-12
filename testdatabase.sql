-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2025 at 09:08 AM
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
  `guest_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `booking_status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `amount_paid` decimal(10,2) DEFAULT NULL,
  `payment_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','paid','failed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `filename` varchar(100) DEFAULT NULL,
  `status` enum('available','booked','inactive') DEFAULT 'available',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`property_id`, `host_id`, `title`, `description`, `address`, `city`, `price_per_night`, `amenities`, `filename`, `status`, `created_at`) VALUES
(4, 1, 'Summit Hotel', 'Test Description', 'Marasbaras street Tacloban city', 'Tacloban City', '2000.00', 'Aircon, Smart TV', 'pic1.jpg', 'available', '2025-06-08 17:20:12'),
(5, 1, 'Hotel Canelsa', 'test description', 'Tacloban City', 'Tacloban City', '1000.00', 'Aircon, Smart TV', 'pic2.jpg', 'available', '2025-06-08 17:21:16'),
(6, 1, 'Hotel Alejandro', 'Test description', 'Paterno street Tacloban city', 'Tacloban City', '1500.00', 'Smart TV, Aircon, Hot and cold shower', 'pic3.jpg', 'available', '2025-06-08 17:25:20'),
(7, 1, 'Kuya Jeromes ', 'Test description', 'Tacloban City', 'Tacloban City', '3000.00', 'TV', 'pic4.jpg', 'available', '2025-06-08 17:26:21'),
(8, 1, 'Loe\'s Lodge', 'Test description', 'Tacloban City', 'Tacloban City', '500.00', 'Aircondition', 'pic5.jpg', 'available', '2025-06-09 12:31:29'),
(9, 1, 'Avuer Hotel', 'test', 'Tacloban city', 'Tacloban City', '2000.00', 'Aircondition, Smart TV', 'pic6.jpg', 'available', '2025-06-09 12:32:29'),
(11, 1, 'BeHotel', 'test', 'Tacloban city', 'Tacloban City', '1000.00', 'Aircondition', 'pic7.jpg', 'available', '2025-06-09 12:33:42'),
(12, 1, 'Madona of Japan', 'test', 'Tacloban city', 'Tacloban City', '300.00', NULL, 'pic8.jpg', 'available', '2025-06-09 12:34:22'),
(13, 1, 'GRAND LA VOGUE HOTEL ', 'test description', 'GRAND LA VOGUE HOTEL ', 'Tacloban City', '5000.00', 'Smart TV, Aircondition', 'pic9.jpg', 'booked', '2025-06-12 14:18:58'),
(14, 1, 'Joshua Hotel Hub', 'test description', 'Tacloban city', 'Tacloban City', '3000.00', 'Aricondition', 'pic10.jpg', 'available', '2025-06-12 14:22:22'),
(15, 1, 'Koh Phangan', 'Koh Phangan', 'Koh Phangan', 'Tacloban City', '3500.00', 'TV', 'pic11.jpg', 'available', '2025-06-12 14:28:21'),
(16, 1, 'The Zero Star Hotel', 'test', 'test', 'Tacloban City', '6000.00', 'TV', 'pic12.jpg', 'available', '2025-06-12 14:29:52');

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
  `user_type` enum('host','guest') NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `user_type`, `phone_number`, `created_at`) VALUES
(1, 'James Doe', 'james@gmail.com', 'test', 'host', NULL, '2025-06-08 17:18:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `fk_bookings_guest` (`guest_id`),
  ADD KEY `fk_bookings_property` (`property_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `fk_payments_booking` (`booking_id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`property_id`),
  ADD KEY `fk_properties_host` (`host_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `fk_reviews_booking` (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_guest` FOREIGN KEY (`guest_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_bookings_property` FOREIGN KEY (`property_id`) REFERENCES `properties` (`property_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);

--
-- Constraints for table `properties`
--
ALTER TABLE `properties`
  ADD CONSTRAINT `fk_properties_host` FOREIGN KEY (`host_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
