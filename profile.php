<?php
session_start();

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "auns"; 
$port = 3306; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Internal Server Error: Database connection failed.']);
    exit;
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    
    if (!$user_id) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized access. Session required.']);
        exit;
    }

    $user_data = null;
    $children_data = []; 

    $sql_user = "SELECT name, email FROM Users WHERE user_id = ?"; 
    $stmt_user = $conn->prepare($sql_user);

    if ($stmt_user) {
        $stmt_user->bind_param("i", $user_id);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();

        if ($result_user->num_rows === 1) {
            $user_data = $result_user->fetch_assoc();
            $user_data['pref_language'] = 'English'; 
        }
        $stmt_user->close();
    }
    
    if ($user_data) {
        echo json_encode(['success' => true, 'user' => $user_data, 'children' => $children_data]);
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'User data not found in DB.']);
    }

} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    if (!$user_id) { 
        http_response_code(401); 
        echo json_encode(['success' => false, 'message' => 'Unauthorized. Please log in again.']); 
        exit;
    }
    
    $json_data = file_get_contents("php://input");
    $data = json_decode($json_data, true);
    $action = isset($data['action']) ? $data['action'] : 'update_profile';

    if ($action === 'logout') {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Logged out successfully.']);
        exit;
        
    } elseif ($action === 'update_profile') {
        
        if (isset($data['name'])) {
            $new_name = trim($data['name']);
            $sql_update = "UPDATE Users SET name = ? WHERE user_id = ?";  
            $stmt_update = $conn->prepare($sql_update);
            
            if ($stmt_update) {
                $stmt_update->bind_param("si", $new_name, $user_id);  
                if ($stmt_update->execute()) {
                    $_SESSION['name'] = $new_name;  
                    echo json_encode(['success' => true, 'message' => 'Personal information updated successfully.']);
                } else {
                    http_response_code(500); echo json_encode(['success' => false, 'message' => 'Database update failed.']);
                }
                $stmt_update->close();
            }
        } else {
            http_response_code(400); echo json_encode(['success' => false, 'message' => 'Missing data (name) for update.']);
        }

    } elseif ($action === 'delete_child') {
        
        if (isset($data['child_id'])) {
            $child_id_to_delete = $data['child_id'];
            $sql_delete = "DELETE FROM Children WHERE child_id = ? AND parent_id = ?"; 
            
            $stmt_delete = $conn->prepare($sql_delete);
            
            if ($stmt_delete) {
                $stmt_delete->bind_param("ii", $child_id_to_delete, $user_id);
                $stmt_delete->execute();

                if ($stmt_delete->affected_rows > 0) {
                    echo json_encode(['success' => true, 'message' => 'Child deleted successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Child not found or could not be deleted.']);
                }
                $stmt_delete->close();
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Database error.']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing child ID.']);
        }

    } else {
        http_response_code(400); echo json_encode(['success' => false, 'message' => 'Invalid action specified.']);
    }
    
} else {
    http_response_code(405); echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
}

if (isset($conn)) {
    $conn->close();
}
?>