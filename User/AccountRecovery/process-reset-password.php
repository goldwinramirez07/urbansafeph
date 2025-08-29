<?php
include('../../config/mysql.php');
session_start();

// Check if session has a reset token
if (!isset($_SESSION['reset_token'])) {
    echo '<script>
        alert("Invalid request. Please try again.");
        setTimeout(function() {
            window.location.href = "http://urbansafeph.com"; // Redirect to home or another appropriate page
        }, 1000);
    </script>';
    exit();
}

$token = $_SESSION['reset_token'];
$token_hash = hash("sha256", $token);

// Prepare the SQL statement
$sql = $conn->prepare("SELECT * FROM login WHERE reset_token = ?");
$sql->bind_param("s", $token_hash);
$sql->execute();

$result = $sql->get_result();
$user = $result->fetch_assoc();

// Check if user exists
if (!$user) {
    echo '<script>
        alert("Invalid token or user not found.");
        setTimeout(function() {
            window.location.href = "http://urbansafeph.com"; // Redirect to home or another appropriate page
        }, 1000);
    </script>';
    exit();
}

// Validate new password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (strlen($_POST["password"]) < 8) {
        alertAndRedirect("Password must be at least 8 characters long.", "http://urbansafeph.com/User/AccountRecovery/reset-password.php");
    }

    if (!preg_match("/[0-9]/", $_POST["password"])) {
        alertAndRedirect("Password must contain at least one number.", "http://urbansafeph.com/User/AccountRecovery/reset-password.php");
    }

    if ($_POST["password"] !== $_POST["password_confirmation"]) {
        alertAndRedirect("Passwords must match.", "http://urbansafeph.com/User/AccountRecovery/reset-password.php");
    }

    // Hash the new password
    $password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Update the password in the database and reset the token fields
    $sql = $conn->prepare("UPDATE login SET password = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?");
    $sql->bind_param("si", $password_hash, $user["id"]);  // 'i' is used for integer ID
    $sql->execute();

    // Check if the password was updated successfully
    if ($sql->affected_rows > 0) {
        unset($_SESSION['reset_token']); // Unset the reset token session variable
        alertAndRedirect("Password updated. You can now login.", "http://urbansafeph.com/User/member.php");
    } else {
        alertAndRedirect("Password update failed. Please try again.", "http://urbansafeph.com/User/AccountRecovery/reset-password.php");
    }
}

// Close statement and connection
$sql->close();
$conn->close();

// Function to show alert and redirect
function alertAndRedirect($message, $url) {
    echo '<script>
        alert("' . addslashes($message) . '");
        setTimeout(function() {
            window.location.href = "' . addslashes($url) . '";
        }, 1000);
    </script>';
    exit();
}
?>
