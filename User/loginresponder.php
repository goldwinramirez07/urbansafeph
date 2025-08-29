<?php
session_start(); // Start session to store user data
include('../config/mysql.php');

if (isset($_POST['login'])) {
    // Sanitize inputs
    $code = strtolower(trim($_POST['code'])); // Assuming 'email' was meant to be 'code'
    $password = trim($_POST['password']);

    // Use prepared statements for security
    $sql = "SELECT * FROM admin WHERE LOWER(code) = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        echo "<script>alert('Database error. Please try again later.'); window.location.href='Responder/responder.php';</script>";
        exit;
    }

    mysqli_stmt_bind_param($stmt, "s", $code);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verify the password using password_verify
        if (password_verify($password, $row['password'])) {
            $responderType = $row['responder_type']; // Fetch responder type
            $roleMapping = [
                'Firefighter' => 'Responder/Fire/fire.php',
                'Medical' => 'Responder/Incident/medical.php',
                'Police' => 'Responder/Crime/police.php',
                'Flood' => 'Responder/Flood/flood.php'
            ];

            // Check if responder type is valid
            if (array_key_exists($responderType, $roleMapping)) {
                // Store user data in session
                $_SESSION['id'] = $row['id'];
                $_SESSION['responder_type'] = $responderType; // Optional, for future use

                // Redirect to the corresponding page based on responder type
                header("Location: " . $roleMapping[$responderType]);
                exit;
            } else {
                echo "<script>alert('Invalid responder type. Please contact admin.'); window.location.href='Responder/responder.php';</script>";
                exit;
            }
        } else {
            // Incorrect password
            echo "<script>alert('Invalid Code or Password.'); window.location.href='Responder/responder.php';</script>";
            exit;
        }
    } else {
        // No user found
        echo "<script>alert('Invalid Code or Password.'); window.location.href='Responder/responder.php';</script>";
        exit;
    }
}
// Close the database connection
mysqli_close($conn);
?>
