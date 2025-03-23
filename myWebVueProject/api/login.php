<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (!isset($data->username) || !isset($data->password)) {
        http_response_code(400);
        echo json_encode(['error' => 'Username and password are required']);
        exit();
    }
    
    try {
        $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = :username");
        $stmt->bindParam(':username', $data->username);
        $stmt->execute();
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin) {
            $password_verify = password_verify($data->password, $admin['password']);
            if ($password_verify) {
                $token = base64_encode(json_encode([
                    'admin_id' => $admin['id'],
                    'username' => $admin['username']
                ]));
                
                echo json_encode([
                    'token' => $token,
                    'username' => $admin['username']
                ]);
            } else {
                http_response_code(401);
                echo json_encode([
                    'error' => 'Invalid password',
                    'debug' => [
                        'provided_password' => $data->password,
                        'stored_hash' => $admin['password'],
                        'verify_result' => $password_verify
                    ]
                ]);
            }
        } else {
            http_response_code(401);
            echo json_encode(['error' => 'User not found']);
        }
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?> 