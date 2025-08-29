<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('config/mysql.php');

// Escape the post content to prevent SQL injection
$post = mysqli_real_escape_string($conn, $_POST['content']);

// Ensure that the user is logged in and get the user_id
if (!isset($_SESSION['id'])) {
    echo "You must be logged in to post.";
    exit();
}
$user_id = $_SESSION['id'];

$file = null;
$fileType = null;

if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    // Get the MIME type of the uploaded file
    $fileType = $_FILES['file']['type'];

    // List of allowed image and video MIME types
    $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
    $allowedVideoTypes = ['video/mp4', 'video/avi', 'video/mpeg'];

    if (in_array($fileType, $allowedImageTypes)) {
        // The file is an image
        $file = addslashes(file_get_contents($_FILES['file']['tmp_name']));
    } elseif (in_array($fileType, $allowedVideoTypes)) {
        // The file is a video
        $file = addslashes(file_get_contents($_FILES['file']['tmp_name']));
    } else {
        exit();
    }
} else {
    echo "File upload error: " . $_FILES['file']['error'];
    exit();
}

// Prepare the SQL query
$query = "INSERT INTO forum (post, file, user_id) VALUES ('$post', '$file', '$user_id')";

if (mysqli_query($conn, $query)) {
    header("refresh:2;url=forum-1.php");
} else {
    echo "MySQL error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
