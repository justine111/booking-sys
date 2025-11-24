-- Role-Based Access Control Migration Script
-- This script adds the necessary database changes for RBAC implementation

-- Step 1: Add 'host' role to user_roles table
INSERT INTO `user_roles` (`user_role_id`, `name`) VALUES (3, 'host');

-- Step 2: Add user_id column to properties table to track ownership
ALTER TABLE `properties` 
ADD COLUMN `user_id` INT(11) DEFAULT NULL AFTER `host_id`,
ADD INDEX `idx_user_id` (`user_id`);

-- Step 3: Add foreign key constraint between users.user_type and user_roles.user_role_id
ALTER TABLE `users`
ADD CONSTRAINT `fk_user_type_role`
FOREIGN KEY (`user_type`) REFERENCES `user_roles` (`user_role_id`)
ON DELETE RESTRICT
ON UPDATE CASCADE;

-- Step 4: Update existing properties to have a default user_id (optional - set to NULL for now)
-- You can manually assign properties to users later via the admin panel
UPDATE `properties` SET `user_id` = NULL WHERE `user_id` IS NULL;

-- Step 5: Add approval status field to properties (for moderator workflow)
-- Note: The existing 'status' field will be used for this purpose
-- Status values: 0=pending approval, 1=rejected, 5=available, 6=booked
-- We'll use: 0=pending approval, 1=approved/available, 2=rejected

-- Verification queries
-- SELECT * FROM user_roles;
-- DESCRIBE properties;
-- SELECT * FROM users;
