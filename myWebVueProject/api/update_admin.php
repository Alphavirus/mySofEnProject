<?php
require_once 'config.php';

try {
    // Clear existing admin records
    $conn->exec("TRUNCATE TABLE admins");
    
    // Create new admin with proper password hash
    $username = 'admin';
    $password = 'admin123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password);
    
    if($stmt->execute()) {
        echo "Admin credentials updated successfully!\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
    } else {
        echo "Error updating admin credentials";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 