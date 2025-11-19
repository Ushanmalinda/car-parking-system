-- Add Currency Support to Car Parking System
-- Run this if you already have the database installed

USE car_parking_system;

-- Add currency setting if it doesn't exist
INSERT INTO system_settings (setting_key, setting_value, description)
SELECT 'currency', 'USD', 'System currency code (ISO 4217)'
WHERE NOT EXISTS (
    SELECT 1 FROM system_settings WHERE setting_key = 'currency'
);

-- Update existing currency setting to proper description
UPDATE system_settings 
SET description = 'System currency code (ISO 4217)' 
WHERE setting_key = 'currency';

-- Add timezone setting if it doesn't exist
INSERT INTO system_settings (setting_key, setting_value, description)
SELECT 'timezone', 'Asia/Kolkata', 'System timezone'
WHERE NOT EXISTS (
    SELECT 1 FROM system_settings WHERE setting_key = 'timezone'
);

-- Verify the settings
SELECT * FROM system_settings WHERE setting_key IN ('currency', 'timezone');

-- Show confirmation message
SELECT 
    'Currency settings added successfully!' as status,
    COUNT(*) as total_settings
FROM system_settings 
WHERE setting_key IN ('currency', 'timezone', 'two_wheeler_rate', 'four_wheeler_rate', 'site_name');
