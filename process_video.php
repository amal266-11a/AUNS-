<?php
require_once "db_connect (1).php";

function throwAlertAndRedirect($message, $redirectPage) {
    echo "<script>";
    echo "alert('$message');";
    echo "window.location.href = '$redirectPage';";
    echo "</script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];
    $video_id = filter_var($_POST["video_id"], FILTER_SANITIZE_NUMBER_INT);

    if ($action === "delete") {
        if (!is_numeric($video_id) || $video_id <= 0) {
            die("Invalid Video ID.");
        }

        $sql = "DELETE FROM videos WHERE video_id = $video_id";

        if ($conn->query($sql) === TRUE) {
            echo "Video ID: $video_id was deleted successfully.";
        } else {
            echo "Error deleting video: " . $conn->error;
        }

    } else if ($action === "update") {
        
        $name = $conn->real_escape_string($_POST["name"]);
        $description = $conn->real_escape_string($_POST["description"]);
        $link = $conn->real_escape_string($_POST["link"]);
        $producer = $conn->real_escape_string($_POST["producer"]);
        $rating = $conn->real_escape_string($_POST["rating"]);
        $category = $conn->real_escape_string($_POST["category"]);

        $sql = "UPDATE videos SET 
                name = '$name', 
                description = '$description', 
                link = '$link', 
                producer = '$producer', 
                rating = '$rating', 
                category = '$category'
                WHERE video_id = $video_id";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Video was updated successfully";
            throwAlertAndRedirect($success_message, "ManageVideo.php");
        } else {
            $error_message = "Video updating failed! Details: " . $conn->error;
            throwAlertAndRedirect($error_message, "ManageVideo.php");
        }
    } else {
        $error_message = "Invalid action specified." . $conn->error;
        throwAlertAndRedirect($error_message, "ManageVideo.php");
    }

    $conn->close();

} else {
    throwAlertAndRedirect("Invalid request or missing action parameter.", "ManageVideo.php");
}
?>