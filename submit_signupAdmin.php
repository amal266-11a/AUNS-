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
$username = htmlspecialchars($_POST["username"]);
$email = htmlspecialchars($_POST["email"]);
$password = htmlspecialchars($_POST["password"]);
$confirmPassword = htmlspecialchars($_POST["confirmPassword"]);

if ($username === "" || $email === "" || $password === "" || $confirmPassword === "") {
throwAlertAndRedirect("Please fill in all fields.", "loginAdmin.php");
exit;
}

if ($password !== $confirmPassword) {
throwAlertAndRedirect("Password confirmation does not match the password!", "loginAdmin.php");
exit;
}
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (username, email, password)
VALUES ('$username', '$email', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
$success_message = "Sign Up Successfully!";
throwAlertAndRedirect($success_message, "loginAdmin.php");
} else {
$error_message = "Error: Sign up failed. " . $conn->error;
throwAlertAndRedirect($error_message, "loginAdmin.php");
}

$conn->close();
} else {
throwAlertAndRedirect("This page expects form data.", "loginAdmin.php");
}
?>