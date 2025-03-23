<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require_once 'config.php';

// Test credentials
$test_username = 'admin';
$test_password = 'admin123';

try {
    // First, check if admin exists
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$test_username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "Checking admin account...\n";
    if ($admin) {
        echo "Admin account found!\n";
        echo "Username: " . $admin['username'] . "\n";
        echo "Stored Hash: " . $admin['password'] . "\n";
        
        // Test password verification
        $verify = password_verify($test_password, $admin['password']);
        echo "Password verification result: " . ($verify ? "SUCCESS" : "FAILED") . "\n";
        
        if ($verify) {
            // Generate token
            $token = base64_encode(json_encode([
                'admin_id' => $admin['id'],
                'username' => $admin['username']
            ]));
            
            echo "\nGenerated Token: " . $token . "\n";
        }
    } else {
        echo "Admin account not found!\n";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 