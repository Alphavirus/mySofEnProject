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
    echo "✅ Database connection successful\n\n";
    
    // Check if students table exists
    $tables = $pdo->query("SHOW TABLES LIKE 'students'")->fetchAll();
    if (count($tables) > 0) {
        echo "✅ Students table exists\n";
        
        // Check table structure
        $columns = $pdo->query("DESCRIBE students")->fetchAll(PDO::FETCH_COLUMN);
        echo "\nTable structure:\n";
        print_r($columns);
        
        // Check if there are any students
        $count = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
        echo "\nNumber of students in database: " . $count . "\n";
        
        // Show sample data
        echo "\nSample data:\n";
        $students = $pdo->query("SELECT * FROM students LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
        print_r($students);
    } else {
        echo "❌ Students table does not exist\n";
    }
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 