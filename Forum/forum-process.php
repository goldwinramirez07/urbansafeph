<?php
session_start();
include('../config/mysql.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $userId = $_SESSION['id'];
    $current_time = time(); // Get current Unix timestamp

    // Check if a file is uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $fileName = $_FILES['file']['name'];
        $fileTmp = $_FILES['file']['tmp_name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];

        // Validate the file upload time
        $fileTime = filemtime($fileTmp); // Get the file's upload time (Unix timestamp)

        // Allow only current-time images/videos (you can allow a small range if needed, e.g., within the last minute)
        if (abs($current_time - $fileTime) > 60) { // Allow a 1-minute difference
            echo "Error: File timestamp is not valid. Only current-time uploads are allowed.";
            exit;
        }

        // Read file data and store it as LONGBLOB
        $fileData = addslashes(file_get_contents($fileTmp));

        // Save the post content, file, and user ID in the forum table
        $query = "INSERT INTO forum (post, file, user_id, timestamp) VALUES ('$content', '$fileData', '$userId', NOW())";
        if (mysqli_query($conn, $query)) {
            header('Location: forum.php');
        } else {
            echo "Error: Could not save the post. " . mysqli_error($conn);
        }
    } else {
        // No file uploaded, just save the post
        $query = "INSERT INTO forum (post, user_id, timestamp) VALUES ('$content', '$userId', NOW())";
        if (mysqli_query($conn, $query)) {
            header('Location: forum.php');
        } else {
            echo "Error: Could not save the post. " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
}
?>
