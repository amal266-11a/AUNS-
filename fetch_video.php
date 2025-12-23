<?php
require_once "db_connect (1).php"; 

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $video_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    if (!is_numeric($video_id) || $video_id <= 0) {
        echo json_encode(['error' => 'Invalid Video ID']);
        $conn->close();
        exit;
    }

    $sql = "SELECT * FROM videos WHERE video_id = $video_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode($row); 
    } else {
        echo json_encode(['error' => 'Video not found']);
    }

    $conn->close();
} else {
    echo json_encode(['error' => 'No ID specified']);
}
?>