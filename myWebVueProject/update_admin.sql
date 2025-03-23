-- Update admin credentials
USE university_db;

-- First, clear existing admin records
TRUNCATE TABLE admins;

-- Insert new admin with properly hashed password (admin123)
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$YourNewHashHere'); 