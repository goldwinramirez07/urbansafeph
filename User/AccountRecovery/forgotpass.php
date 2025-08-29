<?php
session_start();
include('../../config/mysql.php');
use PHPMailer\PHPMailer\PHPMailer;

if (isset($_POST['submit'])) {

    // Sanitize email input
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Generate a random token and hash it
    $token = bin2hex(random_bytes(16));
    $token_hash = hash("sha256", $token);

    // Set expiration time (30 minutes from now)
    $expiry = date("Y-m-d H:i:s", time() + 60 * 30);  // Correct date format for SQL
    
    $_SESSION['reset_token'] = $token; // Store the plain token in session
    
    // Prepare SQL statement to update reset token and expiry
    $sql = $conn->prepare("UPDATE login SET reset_token = ?, reset_token_expires = ? WHERE email = ?");
    $sql->bind_param("sss", $token_hash, $expiry, $email);
    $result = $sql->execute();

    // Check if the update was successful
    if ($conn->affected_rows) {
        require '../../vendor/autoload.php';
        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host = 'xxx';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->Username = 'xxxxx';
        $mail->Password = 'xxxx'; // Consider using environment variables
        $mail->SMTPSecure = 'ssl';
        $mail->From = 'xxxx';
        $mail->FromName = 'Forgot Password';
        $mail->addAddress($email);
        $mail->WordWrap = 50;
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset';
        
        // Use the token stored in the session for the link
        $mail->Body = 'Click <a href="http://urbansafeph.com/User/AccountRecovery/reset-password.php">here</a> to reset your password. Your token is valid for 30 minutes.';

        try {
            if ($mail->send()) {
                echo '<script>alert("Message sent. Please check your inbox.")</script>';
                header("Refresh: 1; url=http://urbansafeph.com"); // 1-second delay before redirecting
                exit();
            } else {
                echo '<script>alert("Message could not be sent. Mailer Error: {$mail->ErrorInfo}")</script>';
                header("Refresh: 1; url=http://urbansafeph.com/User/member.php"); // 1-second delay before redirecting
                exit();
            }
        } catch (Exception $e) {
            echo '<script>alert("Message could not be sent. Exception: {$e->getMessage()}")</script>';
            header("Refresh: 1; url=http://urbansafeph.com/User/member.php"); // 1-second delay before redirecting
            exit();
        }
    } else {
        echo '<script>alert("Error updating token.")</script>';
        header("Refresh: 1; url=http://urbansafeph.com/User/member.php"); // 1-second delay before redirecting
        exit();
    }
}
?>
