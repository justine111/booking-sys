-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2025 at 08:48 AM
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
  `email` varchar(200) DEFAULT NULL,
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
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `name`) VALUES
(1, 'star'),
(2, 'deluxe'),
(3, 'luxury'),
(4, 'budget');

-- --------------------------------------------------------

--
-- Table structure for table `hosts`
--

CREATE TABLE `hosts` (
  `host_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `hosts`
--

INSERT INTO `hosts` (`host_id`, `name`, `date_created`) VALUES
(1, 'Jorge Lang Sakalam', '2025-11-24 20:53:03');

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

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `property_id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
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
  `category_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`property_id`, `host_id`, `user_id`, `title`, `description`, `address`, `city`, `price_per_night`, `amenities`, `img1`, `img2`, `img3`, `img4`, `status`, `category_id`, `created_at`, `is_active`) VALUES
(1, 1, 1, 'Balay ni tatay', 'Wait a few minutes and refresh the page. If that still doesn\'t work, please contact the developer of this page for more information.', 'Brgy Pagsulhugon Babatngon, Leyte', 'Tacloban', '2500.00', 'WiFi, Smart TV and Queen size bed', 'hotel_img_692f9ec0e31c3.png', 'hotel_img_692f9ec0e339a.png', 'hotel_img_692f9ec0e351a.jpg', 'hotel_img_692f9ec0e37a8.jpg', 6, 0, '2025-12-03 10:21:52', 0),
(2, 2, 2, 'Hotel Ranelo', 'teest', 'Brgy 110 Utap Tacloban City', 'Tacloban', '1000.00', 'WiFi', 'hotel_img_692fd69b14406.png', 'hotel_img_692fd69b145c4.png', 'hotel_img_692fd69b14753.png', 'hotel_img_692fd69b17e98.png', 6, 0, '2025-12-03 14:20:11', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Anonymous',
  `rating` int(1) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `property_id`, `client_name`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 'Joe Doe', 1, 'poor', '2025-12-03 05:43:25');

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
(1, 'James Doe', 'james@gmail.com', 'b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86', 1, NULL, '2025-06-08 17:18:19'),
(2, 'Jorge Lang Sakalam', 'jorge@gmail.com', 'b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86', 3, '', '2025-11-24 21:52:58'),
(3, 'Joshua Hernandez', 'joshua@gmail.com', 'b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86', 2, '', '2025-11-25 08:54:05');

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
(2, 'moderator'),
(3, 'host');

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
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

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
  ADD PRIMARY KEY (`property_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `property_id` (`property_id`);

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
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_status`
--
ALTER TABLE `booking_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `property_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_review_property` FOREIGN KEY (`property_id`) REFERENCES `properties` (`property_id`) ON DELETE CASCADE;

DELIMITER $$
--
-- Events
--
CREATE DEFINER=`root`@`localhost` EVENT `update_expired_bookings` ON SCHEDULE EVERY 1 DAY STARTS '2025-12-03 16:17:21' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
  -- Declare variables for tracking
  DECLARE updated_bookings INT DEFAULT 0;
  DECLARE updated_properties INT DEFAULT 0;

  -- 1. Update bookings that have expired (check_out_date has passed)
  -- Change booking_status to 6 (completed)
  UPDATE bookings
  SET booking_status = 6
  WHERE check_out_date < CURDATE()
    AND booking_status = 1  -- Only update confirmed bookings
    AND booking_status != 6; -- Don't update already completed bookings
  
  SET updated_bookings = ROW_COUNT();

  -- 2. Update properties to available (status = 5) 
  -- when their current booking has expired
  UPDATE properties p
  INNER JOIN bookings b ON p.property_id = b.property_id
  SET p.status = 5  -- Available
  WHERE b.check_out_date < CURDATE()
    AND b.booking_status = 6  -- Completed bookings
    AND p.status = 6;  -- Currently booked/unavailable
  
  SET updated_properties = ROW_COUNT();

  -- Optional: Log the update (create a log table if needed)
  -- INSERT INTO booking_status_log (updated_at, bookings_updated, properties_updated)
  -- VALUES (NOW(), updated_bookings, updated_properties);

END$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
