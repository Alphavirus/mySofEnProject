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
    echo "✅ Database connection successful\n";
    
    // Test query
    $result = $pdo->query("SELECT COUNT(*) FROM students");
    $count = $result->fetchColumn();
    echo "Number of students: " . $count . "\n";
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?> 