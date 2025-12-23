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
$name = htmlspecialchars($_POST["name"]);
$description = htmlspecialchars($_POST["description"]);
$link = htmlspecialchars($_POST["link"]);
$producer = htmlspecialchars($_POST["producer"]);
$rating = htmlspecialchars($_POST["rating"]);
$category = htmlspecialchars($_POST["category"]);

if ($name === "" || $description === "" || $link === "" || $producer === "" || $rating === "" || $category === "") {
throwAlertAndRedirect("Please fill in all fields.", "ManageVideo.php");
exit;
}

$sql = "INSERT INTO videos (name, description, link, producer, rating, category)
VALUES ('$name', '$description', '$link','$producer', '$rating', '$category')";

if ($conn->query($sql) === TRUE) {
$success_message = "Video was added successfully";
throwAlertAndRedirect($success_message, "ManageVideo.php");
} else {
$error_message = "Video addition failed! Details: " . $conn->error;
throwAlertAndRedirect($error_message, "ManageVideo.php");
}

$conn->close();
} else {
throwAlertAndRedirect("This page expects form data.", "ManageVideo.php");
}
?>