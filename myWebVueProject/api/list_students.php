<?php
header('Content-Type: text/plain');

try {
    // Database connection
    $host = 'localhost';
    $dbname = 'university_db';
    $username = 'root';
    $password = '';
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all students
    $stmt = $pdo->query("SELECT * FROM students ORDER BY student_id");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Total number of students: " . count($students) . "\n\n";
    
    foreach ($students as $student) {
        echo "Student ID: " . $student['student_id'] . "\n";
        echo "Name: " . $student['name'] . "\n";
        echo "Email: " . $student['email'] . "\n";
        echo "Phone: " . $student['phone'] . "\n";
        echo "Course: " . $student['course'] . "\n";
        echo "GPA: " . $student['gpa'] . "\n";
        echo "Registered on: " . $student['created_at'] . "\n";
        echo "----------------------------------------\n";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 