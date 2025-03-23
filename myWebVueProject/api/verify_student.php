<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'university_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $student_id = $_GET['id'] ?? null;
    
    if (!$student_id) {
        echo json_encode(['error' => 'Student ID is required']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
        $stmt->execute([$student_id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($student) {
            // Student found - return success with student info
            echo json_encode([
                'status' => 'success',
                'message' => 'Valid university member',
                'student' => [
                    'id' => $student['student_id'],
                    'name' => $student['name'],
                    'course' => $student['course'],
                    'gpa' => $student['gpa']
                ]
            ]);
        } else {
            // Student not found
            echo json_encode([
                'status' => 'error',
                'message' => 'Not a registered university member'
            ]);
        }
    } catch(PDOException $e) {
        echo json_encode(['error' => 'Error verifying student: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?> 