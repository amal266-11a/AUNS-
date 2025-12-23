<?php
session_start();
require_once "db_connect.php"; 

$errorMessage = null; 
$formType = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    if (isset($_POST['signup_submit'])) {
        $formType = 'signup';
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirm_password = isset($_POST['confirm-password']) ? $_POST['confirm-password'] : '';
        $errors = [];
        
        if ($password !== $confirm_password) {
            $errors[] = "Passwords do not match.";
        }
        if (empty($username) or empty($email) or empty($password)) {
            $errors[] = "Please fill in all required fields.";
        }

        $sql_check = "SELECT user_id FROM Users WHERE email = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows > 0) {
            $errors[] = "This email is already registered.";
        }
        $stmt_check->close();

        if (empty($errors)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql_insert = "INSERT INTO Users (name, email, password) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sss", $username, $email, $hashed_password);
            
            if ($stmt_insert->execute()) {
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['user_id'] = $conn->insert_id;
                $_SESSION['name'] = $username;
                $_SESSION['email'] = $email;

                header("Location: profile.html"); 
                exit;
            } else {
                $errorMessage = "Registration failed: " . $conn->error;
            }
            $stmt_insert->close();
            
        } else {
            $errorMessage = implode("<br>", $errors);
        }

    } elseif (isset($_POST['login_submit'])) {
        $formType = 'login';
        
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if (empty($email) || empty($password)) {
            $errorMessage = "Email and password are required.";
        } else {
            $sql = "SELECT user_id, name, email, password FROM Users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['loggedin'] = TRUE;
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['name'] = $user['name']; 
                        $_SESSION['email'] = $user['email']; 
                        header("Location: profile.html"); 
                        exit;
                    } else {
                        $errorMessage = "Invalid password."; 
                    }
                } else {
                    $errorMessage = "No user found with that email."; 
                }
            }
        }
    }
}