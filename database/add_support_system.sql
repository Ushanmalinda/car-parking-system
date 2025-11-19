-- Add System Admin Support & Contact Management
-- Run this to add support contact features

USE car_parking_system;

-- Modify users table to add system_admin role
ALTER TABLE users 
MODIFY COLUMN role ENUM('admin', 'staff', 'system_admin') DEFAULT 'staff';

-- Create support_contacts table
CREATE TABLE IF NOT EXISTS support_contacts (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    support_email VARCHAR(100) NOT NULL,
    support_phone VARCHAR(20) NOT NULL,
    whatsapp_number VARCHAR(20) NOT NULL,
    support_name VARCHAR(100) NOT NULL DEFAULT 'System Administrator',
    company_name VARCHAR(200),
    is_active TINYINT(1) DEFAULT 1,
    updated_by INT(11),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Create bug_reports table
CREATE TABLE IF NOT EXISTS bug_reports (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11),
    user_name VARCHAR(100) NOT NULL,
    user_email VARCHAR(100) NOT NULL,
    user_phone VARCHAR(20),
    bug_title VARCHAR(200) NOT NULL,
    bug_description TEXT NOT NULL,
    bug_type ENUM('error', 'bug', 'feature_request', 'question', 'other') DEFAULT 'bug',
    priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    status ENUM('new', 'in_progress', 'resolved', 'closed') DEFAULT 'new',
    page_url VARCHAR(500),
    browser_info TEXT,
    screenshot_path VARCHAR(500),
    admin_notes TEXT,
    resolved_by INT(11),
    resolved_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (resolved_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Insert default support contact
INSERT INTO support_contacts (support_email, support_phone, whatsapp_number, support_name, company_name, is_active)
VALUES ('support@parkingsystem.com', '+1234567890', '+1234567890', 'System Administrator', 'Parking Management System', 1);

-- Add system_admin user (username: sysadmin, password: sysadmin123)
INSERT INTO users (username, password, full_name, email, phone, role, status)
VALUES ('sysadmin', '$2y$10$rBBXHwOZN4FLhHYfVQg7E.gVzqAzP7JKcSVMqwqmQWZ4sQQX8d/TW', 'System Administrator', 'sysadmin@parkingsystem.com', '+1234567890', 'system_admin', 'active')
ON DUPLICATE KEY UPDATE role = 'system_admin';

-- Add support settings to system_settings
INSERT INTO system_settings (setting_key, setting_value, description) VALUES
('enable_bug_reporting', '1', 'Enable bug reporting feature'),
('support_page_title', 'Contact Support', 'Support page title'),
('support_page_message', 'Found a bug or need help? Contact us!', 'Support page welcome message')
ON DUPLICATE KEY UPDATE setting_value = setting_value;

-- Create indexes for better performance
CREATE INDEX idx_bug_status ON bug_reports(status);
CREATE INDEX idx_bug_priority ON bug_reports(priority);
CREATE INDEX idx_bug_created ON bug_reports(created_at);

SELECT 'Support system installed successfully!' as status;
SELECT * FROM support_contacts;
