-- =====================================================
-- MYSQL EVENT FOR AUTO-UPDATING EXPIRED BOOKINGS
-- =====================================================
-- This event runs daily to update booking and property statuses
-- when the check_out_date has passed

-- Enable the event scheduler (run this once)
SET GLOBAL event_scheduler = ON;

-- Drop existing event if it exists
DROP EVENT IF EXISTS update_expired_bookings;

-- Create the event
DELIMITER $$

CREATE EVENT update_expired_bookings
ON SCHEDULE EVERY 1 DAY
STARTS CURRENT_TIMESTAMP
DO
BEGIN
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

-- =====================================================
-- VERIFY EVENT WAS CREATED
-- =====================================================
-- Run this to check if the event is active:
-- SHOW EVENTS WHERE Name = 'update_expired_bookings';

-- =====================================================
-- MANUAL RUN (FOR TESTING)
-- =====================================================
-- To manually trigger the event for testing:
-- CALL update_expired_bookings();

-- Or run the SQL directly:
/*
UPDATE bookings
SET booking_status = 6
WHERE check_out_date < CURDATE()
  AND booking_status = 1
  AND booking_status != 6;

UPDATE properties p
INNER JOIN bookings b ON p.property_id = b.property_id
SET p.status = 5
WHERE b.check_out_date < CURDATE()
  AND b.booking_status = 6
  AND p.status = 6;
*/

-- =====================================================
-- DISABLE/ENABLE EVENT
-- =====================================================
-- To disable the event:
-- ALTER EVENT update_expired_bookings DISABLE;

-- To enable the event:
-- ALTER EVENT update_expired_bookings ENABLE;

-- =====================================================
-- DELETE EVENT
-- =====================================================
-- To completely remove the event:
-- DROP EVENT IF EXISTS update_expired_bookings;
