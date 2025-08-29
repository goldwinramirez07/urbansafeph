<?php
include('config/mysql.php'); // Include your database connection file

// Update the active status of notifications to 0
$sql = "UPDATE firereport SET active = 0 WHERE active = 1";
$conn->query($sql);

// Close the connection
$conn->close();
?>
