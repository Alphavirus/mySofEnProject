<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE');
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
    error_log("Database connection failed: " . $e->getMessage());
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Handle GET request - Fetch all students
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM students ORDER BY student_id");
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("Fetched " . count($students) . " students");
        echo json_encode($students);
    } catch(PDOException $e) {
        error_log("Error fetching students: " . $e->getMessage());
        echo json_encode(['error' => 'Error fetching students: ' . $e->getMessage()]);
    }
}

// Handle POST request - Add new student
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawData = file_get_contents('php://input');
    error_log("Raw POST data received: " . $rawData);
    
    $data = json_decode($rawData, true);
    error_log("Decoded POST data: " . print_r($data, true));
    
    // Validate required fields
    $requiredFields = ['student_id', 'name', 'email', 'phone', 'course', 'gpa'];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            error_log("Missing required field: " . $field);
            echo json_encode(['error' => "Missing required field: " . $field]);
            exit;
        }
    }
    
    try {
        // Check if student ID already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE student_id = ?");
        $stmt->execute([$data['student_id']]);
        if ($stmt->fetchColumn() > 0) {
            error_log("Student ID already exists: " . $data['student_id']);
            echo json_encode(['error' => 'Student ID already exists']);
            exit;
        }

        // Validate GPA
        $gpa = floatval($data['gpa']);
        if ($gpa < 0 || $gpa > 4.0) {
            error_log("Invalid GPA: " . $gpa);
            echo json_encode(['error' => 'GPA must be between 0 and 4.0']);
            exit;
        }

        // Check if email already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE email = ?");
        $stmt->execute([$data['email']]);
        if ($stmt->fetchColumn() > 0) {
            error_log("Email already exists: " . $data['email']);
            echo json_encode(['error' => 'Email already exists']);
            exit;
        }

        // Insert new student
        $stmt = $pdo->prepare("INSERT INTO students (student_id, name, email, phone, course, gpa) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['student_id'],
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['course'],
            $data['gpa']
        ]);
        
        error_log("Successfully added student with ID: " . $data['student_id']);
        echo json_encode(['message' => 'Student added successfully']);
    } catch(PDOException $e) {
        error_log("Error adding student: " . $e->getMessage());
        echo json_encode(['error' => 'Error adding student: ' . $e->getMessage()]);
    }
}

// Handle DELETE request - Delete student
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_GET['id'] ?? null;
    error_log("Received DELETE request for student ID: " . $id);
    
    if (!$id) {
        error_log("No student ID provided for deletion");
        echo json_encode(['error' => 'Student ID is required']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM students WHERE student_id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            error_log("Successfully deleted student with ID: " . $id);
            echo json_encode(['message' => 'Student deleted successfully']);
        } else {
            error_log("No student found with ID: " . $id);
            echo json_encode(['error' => 'Student not found']);
        }
    } catch(PDOException $e) {
        error_log("Error deleting student: " . $e->getMessage());
        echo json_encode(['error' => 'Error deleting student: ' . $e->getMessage()]);
    }
}
?> 