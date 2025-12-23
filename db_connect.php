<?php

$servername = "localhost";
$username = "root";        
$password = "";          
$dbname = "auns";    

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) { 
    die("Connection failed: " . $conn->connect_error); 
}

if (!$conn->set_charset("utf8mb4")) {
    // Optionally handle the error if character set fails to load
    // die("Error loading character set utf8mb4: " . $conn->error);
}

?>