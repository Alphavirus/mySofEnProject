<?php
function checkAuth() {
    $headers = getallheaders();
    
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(['error' => 'No token provided']);
        exit();
    }

    $token = str_replace('Bearer ', '', $headers['Authorization']);
    
    try {
        $decoded = json_decode(base64_decode($token));
        if (!$decoded || !isset($decoded->admin_id)) {
            throw new Exception('Invalid token');
        }
        return $decoded->admin_id;
    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid token']);
        exit();
    }
}
?> 