-- Create reviews table for hotel/room ratings (simplified - no booking validation)
CREATE TABLE IF NOT EXISTS `reviews` (
  `review_id` INT(11) NOT NULL AUTO_INCREMENT,
  `property_id` INT(11) NOT NULL,
  `client_name` VARCHAR(255) DEFAULT 'Anonymous',
  `rating` INT(1) NOT NULL CHECK (`rating` >= 1 AND `rating` <= 5),
  `comment` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `fk_review_property` FOREIGN KEY (`property_id`) REFERENCES `properties` (`property_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
