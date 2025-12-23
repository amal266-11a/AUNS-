<?php
require_once "db_connect (1).php";

function throwAlertAndRedirect($message, $redirectPage) {
    echo "<script>";
    echo "alert('$message');";
    echo "window.location.href = '$redirectPage';";
    echo "</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    if ($email === "" || $password === "") {
        throwAlertAndRedirect("Please fill in all fields.", "loginAdmin.php");
    }
    
    $stmt = $conn->prepare("SELECT password, username FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $hashed_password = $user['password'];

        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $email;
            header('Location: ManageVideo.php');
            exit;
        } else {
            throwAlertAndRedirect("Invalid email or password.", "loginAdmin.php");
        }
        
    } else {
        throwAlertAndRedirect("Invalid email or password.", "loginAdmin.php");
    }

    $stmt->close();
    $conn->close();
    
} else {
    echo "<h2>This page expects form data.</h2>";
    throwAlertAndRedirect("This page expects form data.", "loginAdmin.php");
}
?>