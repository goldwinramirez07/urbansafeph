<?php 
session_start();
include "../config/mysql.php";

if (isset($_POST['login'])) {
    // Sanitize inputs
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // Corrected name

    // Check if the user is trying to log in as the admin
    if ($email == 'admin@admin.com' && $password == 'admin') {
        echo '<script>alert("Successfully login as Administrator"); window.location.href = "../Admin/admin.php";</script>';
        exit(); // Ensure to stop further script execution after the alert and redirection
    }

    // Use prepared statements for security
    $sql = "SELECT * FROM login WHERE email = ? AND status = 'active'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        // Verify the password using password_verify
        if (password_verify($password, $row['password'])) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['cn'] = $row['contact_no'];
            $_SESSION['role'] = $row['role'];
            $name = $row['name'];
            setcookie('username', $name, time() + (86400 * 30), "/"); // Cookie expires in 30 days
            header("Location: Member/report1.php");
            exit(); // Ensure to exit after header redirection
        } else {
            // Redirect with error message
            header("Location: member.php?error=password_incorrect");
            exit();
        }
    } else {
        // Redirect with error message
        header("Location: member.php?error=email_not_found");
        exit();
    }
}
?>
