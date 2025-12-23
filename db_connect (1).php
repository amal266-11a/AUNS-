<?php
// Database connection settings
$servername = "localhost";
$username = "root"; // Default in XAMPP
$password = ""; // Default is empty
$dbname = "auns";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
// die = Stop the page execution immediately and print the message.
die("Connection failed: " . $conn->connect_error);
}
?>