-- Car Parking Management System Database
-- Create Database
CREATE DATABASE IF NOT EXISTS car_parking_system;
USE car_parking_system;

-- Users Table (Admin and Staff)
CREATE TABLE IF NOT EXISTS users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15),
    role ENUM('admin', 'staff') DEFAULT 'staff',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Parking Slots Table
CREATE TABLE IF NOT EXISTS parking_slots (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    slot_number VARCHAR(10) NOT NULL UNIQUE,
    slot_floor VARCHAR(10) NOT NULL,
    vehicle_type ENUM('two_wheeler', 'four_wheeler') NOT NULL,
    status ENUM('available', 'occupied', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Vehicle Categories Table
CREATE TABLE IF NOT EXISTS vehicle_categories (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(50) NOT NULL,
    category_type ENUM('two_wheeler', 'four_wheeler') NOT NULL,
    rate_per_hour DECIMAL(10,2) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Parking Bookings Table
CREATE TABLE IF NOT EXISTS parking_bookings (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    booking_number VARCHAR(20) NOT NULL UNIQUE,
    slot_id INT(11) NOT NULL,
    owner_name VARCHAR(100) NOT NULL,
    owner_phone VARCHAR(15) NOT NULL,
    owner_email VARCHAR(100),
    vehicle_number VARCHAR(20) NOT NULL,
    vehicle_type ENUM('two_wheeler', 'four_wheeler') NOT NULL,
    vehicle_category_id INT(11),
    vehicle_company VARCHAR(50),
    entry_time DATETIME NOT NULL,
    exit_time DATETIME,
    parking_charge DECIMAL(10,2),
    status ENUM('parked', 'exited', 'cancelled') DEFAULT 'parked',
    remarks TEXT,
    created_by INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (slot_id) REFERENCES parking_slots(id) ON DELETE RESTRICT,
    FOREIGN KEY (vehicle_category_id) REFERENCES vehicle_categories(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Payments Table
CREATE TABLE IF NOT EXISTS payments (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    booking_id INT(11) NOT NULL,
    payment_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card', 'upi', 'online') DEFAULT 'cash',
    payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending',
    transaction_id VARCHAR(100),
    payment_date DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES parking_bookings(id) ON DELETE CASCADE
);

-- System Settings Table
CREATE TABLE IF NOT EXISTS system_settings (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value TEXT NOT NULL,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Activity Logs Table
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11),
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert Default Admin User (Password: admin123)
INSERT INTO users (username, password, full_name, email, phone, role, status) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin@parking.com', '9876543210', 'admin', 'active');

-- Insert Default Vehicle Categories
INSERT INTO vehicle_categories (category_name, category_type, rate_per_hour, description) VALUES
('Bicycle', 'two_wheeler', 5.00, 'Bicycles and cycles'),
('Motorcycle', 'two_wheeler', 10.00, 'Motorcycles and scooters'),
('Car', 'four_wheeler', 20.00, 'Standard cars and sedans'),
('SUV', 'four_wheeler', 30.00, 'SUVs and large vehicles');

-- Insert Sample Parking Slots
INSERT INTO parking_slots (slot_number, slot_floor, vehicle_type, status) VALUES
('A-001', 'Ground', 'two_wheeler', 'available'),
('A-002', 'Ground', 'two_wheeler', 'available'),
('A-003', 'Ground', 'two_wheeler', 'available'),
('A-004', 'Ground', 'two_wheeler', 'available'),
('A-005', 'Ground', 'two_wheeler', 'available'),
('B-001', 'Ground', 'four_wheeler', 'available'),
('B-002', 'Ground', 'four_wheeler', 'available'),
('B-003', 'Ground', 'four_wheeler', 'available'),
('B-004', 'Ground', 'four_wheeler', 'available'),
('B-005', 'Ground', 'four_wheeler', 'available'),
('C-001', 'First', 'two_wheeler', 'available'),
('C-002', 'First', 'two_wheeler', 'available'),
('C-003', 'First', 'two_wheeler', 'available'),
('D-001', 'First', 'four_wheeler', 'available'),
('D-002', 'First', 'four_wheeler', 'available');

-- Insert System Settings
INSERT INTO system_settings (setting_key, setting_value, description) VALUES
('site_name', 'Car Parking Management System', 'Website name'),
('two_wheeler_rate', '10', 'Rate per hour for two wheelers'),
('four_wheeler_rate', '20', 'Rate per hour for four wheelers'),
('currency', 'USD', 'System currency code (ISO 4217)'),
('timezone', 'Asia/Kolkata', 'System timezone');
