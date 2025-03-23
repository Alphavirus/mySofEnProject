-- Drop existing database if it exists
DROP DATABASE IF EXISTS university_db;

-- Create the database
CREATE DATABASE university_db;
USE university_db;

-- Create admin table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (password: admin123)
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Create students table with 6-digit student_id
CREATE TABLE students (
    student_id INT PRIMARY KEY CHECK (student_id >= 100000 AND student_id <= 999999),
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    course VARCHAR(100) NOT NULL,
    gpa DECIMAL(3,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample student data with 6-digit IDs and new names
INSERT INTO students (student_id, name, email, phone, course, gpa) VALUES
(100001, 'Kader Traore', 'kader.traore@university.edu', '123-456-7890', 'Computer Science', 3.85),
(100002, 'Kouame Paul', 'kouame.paul@university.edu', '234-567-8901', 'Mathematics', 3.92),
(100003, 'Kemi Coffi', 'kemi.coffi@university.edu', '345-678-9012', 'Physics', 3.78),
(100004, 'Yann Dele', 'yann.dele@university.edu', '456-789-0123', 'Biology', 3.95),
(100005, 'Brou Kouame', 'brou.kouame@university.edu', '567-890-1234', 'Chemistry', 3.88); 