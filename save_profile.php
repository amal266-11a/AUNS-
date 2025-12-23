<?php
session_start();

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auns";
$port = 3306;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]);
    exit();
}

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

$childName = trim($data['childName'] ?? '');
$childAge = trim($data['childAge'] ?? '');

$gender = null;

$parentId = $_SESSION['user_id'] ?? null;

if (!$parentId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized: Please log in to add a child.']);
    exit();
}

$errors = [];

if (empty($childName) || !preg_match("/^[\p{L}\s]+$/u", $childName)) {
    $errors[] = "Child's name must contain only letters and spaces.";
}

if (!filter_var($childAge, FILTER_VALIDATE_INT) || $childAge < 1 || $childAge > 12) {
    $errors[] = "Child's age must be a whole number between 1 and 12.";
}

if (empty($errors)) {
    $stmt = $conn->prepare("INSERT INTO children (parent_id, name, age, gender) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $parentId, $childName, $childAge, $gender); 

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => "Error inserting record: " . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => "Validation errors", 'errors' => $errors]);
}

$conn->close();
?>