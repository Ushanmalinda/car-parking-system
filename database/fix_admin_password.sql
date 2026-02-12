-- Fix admin password for Car Parking System
-- This will update the admin password to 'admin123'

USE car_parking_system;

-- Update admin password with correct hash for 'admin123'
UPDATE users 
SET password = '$2y$10$rBBXHwOZN4FLhHYfVQg7E.gVzqAzP7JKcSVMqwqmQWZ4sQQX8d/TW' 
WHERE username = 'admin';

-- If admin doesn't exist, create it
INSERT INTO users (username, password, full_name, email, phone, role, status) 
SELECT 'admin', '$2y$10$rBBXHwOZN4FLhHYfVQg7E.gVzqAzP7JKcSVMqwqmQWZ4sQQX8d/TW', 'System Administrator', 'admin@parking.com', '9876543210', 'admin', 'active'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'admin');

-- Verify the update
SELECT id, username, email, role, status, 
       CASE 
           WHEN password = '$2y$10$rBBXHwOZN4FLhHYfVQg7E.gVzqAzP7JKcSVMqwqmQWZ4sQQX8d/TW' 
           THEN 'Password Updated ✓' 
           ELSE 'Password Different ✗' 
       END as password_status
FROM users 
WHERE username = 'admin';
