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
    echo json_encode(['success' => false, 'message' => "Database connection failed: " . $conn->connect_error]);
    exit();
}

$parentId = $_SESSION['user_id'] ?? null;

if (!$parentId) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized: Please log in to view children.']);
    exit();
}

$sql = "SELECT child_id, name, age, gender 
        FROM children 
        WHERE parent_id = ? 
        ORDER BY child_id DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $parentId); 
$stmt->execute();
$result = $stmt->get_result();

$children = [];
while ($row = $result->fetch_assoc()) {
    $children[] = $row;
}

echo json_encode([
    'success' => true,
    'data' => $children,
    'count' => count($children)
]);

$stmt->close();
$conn->close();
?>