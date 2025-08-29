<?php
session_start();
include('../config/mysql.php'); // Include your database connection

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    // Redirect back to the forum with an error message
    header("Location: forum.php?error=You need to log in to comment.");
    exit();
}

// Check if comment and post ID are set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'], $_POST['post_id'])) {
    $comment = trim($_POST['comment']);
    $post_id = (int)$_POST['post_id']; // Ensure post ID is an integer
    $user_id = $_SESSION['id']; // Get the logged-in user's ID

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment, timestamp) VALUES (?, ?, ?, NOW())");
    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("iis", $post_id, $user_id, $comment);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect back to the forum with a success message
            header("Location: forum.php?success=Comment added successfully.");
        } else {
            // Redirect back with an error
            header("Location: forum.php?error=Failed to add comment. Please try again.");
        }

        // Close the statement
        $stmt->close();
    } else {
        // Handle preparation error
        header("Location: forum.php?error=Failed to prepare statement.");
    }
} else {
    // Redirect back if invalid request
    header("Location: forum.php?error=Invalid request.");
}

// Close the database connection
$conn->close();
?>
