-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 14, 2025 at 02:35 PM
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `status` enum('available','booked','inactive') DEFAULT 'available',
  `created_at` datetime DEFAULT current_timestamp(),
  `img3` varchar(100) DEFAULT NULL,
  `img4` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`property_id`, `host_id`, `title`, `description`, `address`, `city`, `price_per_night`, `amenities`, `img1`, `img2`, `status`, `created_at`, `img3`, `img4`) VALUES
(4, 1, 'Summit Hotel', 'Test Description', 'Marasbaras street Tacloban city', 'Tacloban City', 2000.00, 'Aircon, Smart TV', 'pic1.jpg', '', 'available', '2025-06-08 17:20:12', NULL, NULL),
(5, 1, 'Hotel Canelsa', 'test description', 'Tacloban City', 'Tacloban City', 1000.00, 'Aircon, Smart TV', 'pic2.jpg', '', 'available', '2025-06-08 17:21:16', NULL, NULL),
(6, 1, 'Hotel Alejandro', 'Test description', 'Paterno street Tacloban city', 'Tacloban City', 1500.00, 'Smart TV, Aircon, Hot and cold shower', 'pic3.jpg', '', 'available', '2025-06-08 17:25:20', NULL, NULL),
(7, 1, 'Kuya Jeromes ', 'Test description', 'Tacloban City', 'Tacloban City', 3000.00, 'TV', 'pic4.jpg', '', 'available', '2025-06-08 17:26:21', NULL, NULL),
(8, 1, 'Loe\'s Lodge', 'Test description', 'Tacloban City', 'Tacloban City', 500.00, 'Aircondition', 'pic5.jpg', '', 'available', '2025-06-09 12:31:29', NULL, NULL),
(9, 1, 'Avuer Hotel', 'test', 'Tacloban city', 'Tacloban City', 2000.00, 'Aircondition, Smart TV', 'pic6.jpg', '', 'available', '2025-06-09 12:32:29', NULL, NULL),
(11, 1, 'BeHotel', 'test', 'Tacloban city', 'Tacloban City', 1000.00, 'Aircondition', 'pic7.jpg', '', 'available', '2025-06-09 12:33:42', NULL, NULL),
(12, 1, 'Madona of Japan', 'test', 'Tacloban city', 'Tacloban City', 300.00, NULL, 'pic8.jpg', '', 'available', '2025-06-09 12:34:22', NULL, NULL),
(13, 1, 'GRAND LA VOGUE HOTEL ', 'test description', 'GRAND LA VOGUE HOTEL ', 'Tacloban City', 5000.00, 'Smart TV, Aircondition', 'pic9.jpg', '', 'booked', '2025-06-12 14:18:58', NULL, NULL),
(14, 1, 'Joshua Hotel Hub', 'test description', 'Tacloban city', 'Tacloban City', 3000.00, 'Aricondition', 'pic10.jpg', '', 'available', '2025-06-12 14:22:22', NULL, NULL),
(15, 1, 'Koh Phangan', 'Koh Phangan', 'Koh Phangan', 'Tacloban City', 3500.00, 'TV', 'pic11.jpg', '', 'available', '2025-06-12 14:28:21', NULL, NULL),
(16, 1, 'The Zero Star Hotel', 'test', 'test', 'Tacloban City', 6000.00, 'TV', 'pic12.jpg', '', 'available', '2025-06-12 14:29:52', NULL, NULL),
(17, 1, 'test', 'asdasd', 'Bryg Rizal II Babatngon, Leyte', 'asdas', 1000.00, 'asdsa', 'hotel_img_691728c12b743.png', 'hotel_img_691728c12b8e4.png', 'available', '2025-11-14 21:04:01', 'hotel_img_691728c12bac1.png', 'hotel_img_691728c130a9b.png');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
