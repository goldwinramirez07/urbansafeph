<?php
include('config/mysql.php');

// Get the ID from the request
$id = $_POST['id'];

// Update the 'respond' attribute to true (1) in the database
$query = "UPDATE crimereport SET respond = 1 WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}

// Close the database connection
$stmt->close();
mysqli_close($conn);
?>
