<?php
require_once 'config.php';

try {
    echo "<h2>Database Connection Test</h2>";
    $conn->query("SELECT 1");
    echo "✅ Database connection successful!<br><br>";

    echo "<h2>Database and Tables Check</h2>";
    // Check if database exists
    $db_exists = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'university_db'")->fetch();
    if ($db_exists) {
        echo "✅ Database 'university_db' exists<br>";
    } else {
        echo "❌ Database 'university_db' does not exist<br>";
    }

    // Check if tables exist
    $tables = $conn->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<br>Tables found:<br>";
    foreach ($tables as $table) {
        echo "- $table<br>";
    }

    echo "<h2>Admin Account Check</h2>";
    $stmt = $conn->query("SELECT * FROM admins");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($admins) > 0) {
        echo "✅ Admin records found:<br>";
        foreach ($admins as $admin) {
            echo "ID: {$admin['id']}<br>";
            echo "Username: {$admin['username']}<br>";
            echo "Password Hash: {$admin['password']}<br>";
            echo "Created At: {$admin['created_at']}<br><br>";
        }
    } else {
        echo "❌ No admin records found!<br>";
    }

    echo "<h2>Test Login</h2>";
    $test_username = 'admin';
    $test_password = 'admin123';
    
    $stmt = $conn->prepare("SELECT password FROM admins WHERE username = ?");
    $stmt->execute([$test_username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin) {
        $verify = password_verify($test_password, $admin['password']);
        echo "Username 'admin' exists<br>";
        echo "Password verification: " . ($verify ? "✅ Success" : "❌ Failed") . "<br>";
    } else {
        echo "❌ Username 'admin' not found<br>";
    }

} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?> 