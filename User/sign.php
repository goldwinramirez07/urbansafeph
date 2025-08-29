<?php
include('../config/mysql.php');
use PHPMailer\PHPMailer\PHPMailer;

// Function to generate a 5-digit OTP
function generateOTP($length = 5) {
    return substr(str_shuffle("0123456789"), 0, $length);
}

// Function to generate a random activation code
function generateActivationCode() {
    return str_shuffle("abcdefghijklmno" . rand(100000, 1000000));
}

if (isset($_POST['submit'])) {
    // Fetch values from the form
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $cn = mysqli_real_escape_string($conn, $_POST['contact']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password securely
    
    // Generate OTP and activation code
    $otp = generateOTP();
    $activation_code = generateActivationCode();

    // Check if the email already exists in the database
    $stmt = $conn->prepare("SELECT * FROM login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $status = $row['status'];

        if ($status == 'active') {
            echo "<script>alert('Email already registered.');</script>";
        } else {
            // If the email exists but is not active, update the record
            $updateStmt = $conn->prepare("UPDATE login SET name = ?, password = ?, contact_no = ?, role = ?, otp = ?, activation_code = ? WHERE email = ?");
            $updateStmt->bind_param("ssissss", $name, $password, $cn, $role, $otp, $activation_code, $email);
            $updateResult = $updateStmt->execute();

            if ($updateResult) {
                sendVerificationEmail($email, $otp, $activation_code);
                echo '<script>alert("Account updated. Please check your email for verification.");</script>';
            } else {
                echo '<script>alert("Failed to update the record. Error: ' . $conn->error . '");</script>';
            }
        }
    } else {
        // If the email doesn't exist, insert a new record
        $insertStmt = $conn->prepare("INSERT INTO login (name, email, password, contact_no, role, otp, activation_code) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insertStmt->bind_param("sssisss", $name, $email, $password, $cn, $role, $otp, $activation_code);
        $insertResult = $insertStmt->execute();

        if ($insertResult) {
            if ($insertResult) {
                sendVerificationEmail($email, $otp, $activation_code);
                echo '<script>alert("Registration successful. Please check your email for verification.");</script>';
            } else {
                echo '<script>alert("Failed to insert data into the admin table. Error: ' . $conn->error . '");</script>';
            }
        } else {
            echo '<script>alert("Failed to insert data into the login table. Error: ' . $conn->error . '");</script>';
        }
    }

    // Close statements
    $stmt->close();
    if (isset($updateStmt)) $updateStmt->close();
    if (isset($insertStmt)) $insertStmt->close();
}


// Function to send verification email
function sendVerificationEmail($email, $otp, $activation_code) {
    require '../vendor/autoload.php';
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = 'rielsabangan45@urbansafeph.com';
    $mail->Password = 'G@bsabangan123'; // Consider using environment variables
    $mail->SMTPSecure = 'ssl';
    $mail->From = 'rielsabangan45@urbansafeph.com';
    $mail->FromName = 'Signup Confirmation';
    $mail->addAddress($email);
    $mail->WordWrap = 50;
    $mail->isHTML(true);
    $mail->Subject = 'Verification code for Verify Your Email Address';

    $message_body = '
    <p>To verify your email address, enter this verification code when prompted: <b>' . $otp . '</b>.</p>
    <p>Sincerely,</p>';
    $mail->Body = $message_body;

    if ($mail->send()) {
        header('Location: Verification/email_verify.php?code=' . $activation_code);
        exit();
    } else {
        echo '<script>alert("Mailer Error: ' . $mail->ErrorInfo . '");</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Safe</title>
    <link rel="stylesheet" href="../css/login.css">
    <style>
        header {
            height: 100px;
            display: flex;
            align-items: center; /* Vertically centers the logo */
            justify-content: flex-start; /* Keeps the logo to the left */
            padding-left: 20px; /* Adds some padding from the left edge */
            white-space: nowrap; /* Prevents text from wrapping */
        }
        
        body {
        height: 100px;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #FFF;
    }

        .header-container {
            display: flex;
            align-items: center; /* Vertically aligns logo and text */
            white-space: nowrap; /* Prevents wrapping inside the container */
        }

        .logo {
            height: 110px; /* Adjust this value for logo size */
            width: auto;
        }

        .title {
            margin-left: 10px; /* Adds space between the logo and title */
            font-size: 2rem;
            white-space: nowrap; /* Ensures the text stays on one line */
        }

        body {
            margin: 0;
        }

        /* Center form and content on the page */
        form, .urban-logo {
            display: block;
            margin: 0 auto;
        }

        .urban-logo {
            margin-top: 20px;
            width: auto;
            height: auto;
        }

        .report-btn, .login-btn {
            padding: 10px 20px;
            font-size: 1.2rem;
            cursor: pointer;
        }
    
           /* Center the form on the page */
        .registration-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            height: 80vh;
        }

        /* Styling the registration box */
        .registration-box {
            background-color: #1B413A;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 1);
            width: 350px;
            max-width: 100%;
            box-sizing: border-box; /* Ensures padding does not overflow */
        }

        h1 {
            color: white;
            font-size: 24px;
            text-align: center;
        }

        form {
            margin-top: 20px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-field {
            width: 100%; /* Ensures the input fills the container */
            padding: 12px;
            padding-left: 40px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
            box-sizing: border-box; /* Prevents padding from affecting width */
        }

        .icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #888;
        }

        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .register-btn {
            width: 100%;
            padding: 12px;
            background-color: #FFD700;
            color: black;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .register-btn:hover {
            background-color: #FFEB00;
        }

        .login-link {
            text-align: center;
            color: white;
            font-size: 14px;
        }

        .login-link a {
            color: #FFD700;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 60%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 20px;
            color: #888;
        }
        
     footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 60px;
        background-color: #1B413A; /* Same as header background */
        color: #fff; /* Text color */
        display: flex;
        align-items: center; /* Vertical alignment */
        justify-content: center; /* Center text horizontally */
        padding: 0 20px; /* Add padding for spacing */
    }
        @media (max-width: 768px) {
            
            .title {
                font-size: 1.2rem;
            }

            .urban-logo {
                width: 100px; /* Adjust width for smaller screens */
            }

            .report-btn, .login-btn {
                padding: 8px 15px; /* Smaller padding for smaller screens */
                font-size: 1rem;
            }
            .registration-box {
                 width: 90%; /* Makes it responsive for mobile */
            }
        }
        .no-arrows::-webkit-outer-spin-button,
        .no-arrows::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        
        /* Remove arrows on Firefox */
        .no-arrows[type=number] {
            -moz-appearance: textfield;
        }
        
        /* Additional rule to ensure no arrows on hover, focus, and active states */
        .no-arrows:focus,
        .no-arrows:hover,
        .no-arrows:active {
            outline: none;
            -webkit-appearance: none;
            -moz-appearance: textfield;
        }
    </style>
    <script>
        function validatePassword() {
            const password = document.querySelector('input[name="pass"]').value;

            // Check if password is at least 8 characters
            if (password.length < 8) {
                alert("Password must be at least 8 characters long.");
                return false; // Prevent form submission
            }

            return true; // Allow form submission
        }
    </script>
</head>

<body>
    <header>
        <div class="header-container">
            <a href="../../index.php"><img src="../pics/logo.png" alt="Logo" class="logo"></a>
            <h1 class="title"><i>ES TECH INNOVATIONS</i></h1>
        </div>
    </header>

<div class="registration-container">
    <div class="registration-box">
        <h1>Register</h1>
          <form action="" method="POST" onsubmit="return validatePassword();">
        <div class="input-group">
            <select class="input-field" name="role" id="roleSelect" required onchange="toggleCodeInput()">
                <option value="Member" selected>Member</option>
            </select>
            <span class="icon"><ion-icon name="person-circle-outline"></ion-icon></span>
        </div>
    
        <div class="input-group">
            <input type="text" class="input-field" name="name" placeholder="Full Name" autocomplete="off" required>
            <span class="icon"><ion-icon name="person-outline"></ion-icon></span>
        </div>
    
        <div class="input-group">
            <input type="email" class="input-field" name="email" placeholder="Email Address" autocomplete="off" required>
            <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
        </div>
    
        <div class="input-group">
            <input type="number" class="input-field no-arrows" name="contact" placeholder="Contact Number" minlength="11" maxlength="11" oninput="enforceLength(this)" autocomplete="off" required>
            <span class="icon"><ion-icon name="call-outline"></ion-icon></span>
        </div>
    
        <div class="input-group">
            <input type="password" id="password" class="input-field no-arrows" name="password" placeholder="Password" required>
            <span class="toggle-password" onclick="togglePasswordVisibility('password')"><ion-icon name="eye-outline"></ion-icon>️</span>
            <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
        </div>
    
        <button type="submit" name="submit" class="register-btn">REGISTER</button>
    </form>
            <div class="login-link"><br>
                Already have an account? <a href="member.php">Login</a>
            </div>
            
        </div>
</div>
        
    </div>
    
<br><br><br>    
    <footer>
        <p>&copy; 2024 ES Tech Innovations. All Rights Reserved.</p>
    </footer>

</body>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script>
    function enforceLength(element) {
        if (element.value.length > 11) {
            element.value = element.value.slice(0, 11);
        }
    }

    // Function to toggle visibility of "Code" input and "Responder Type" dropdown
    function toggleCodeInput() {
        const roleSelect = document.getElementById('roleSelect');
        const codeGroup = document.getElementById('codeGroup');
        const typeGroup = document.getElementById('typeGroup');
        const codeInput = document.getElementById('codeInput');
        const typeSelect = document.getElementById('typeSelect');
    
        // Show fields if "Responder" is selected, hide them otherwise
        if (roleSelect.value === 'Responder') {
            codeGroup.style.display = 'block';
            typeGroup.style.display = 'block';
            codeInput.required = true;
            typeSelect.required = true;
        } else {
            codeGroup.style.display = 'none';
            typeGroup.style.display = 'none';
            codeInput.required = false;
            typeSelect.required = false;
            codeInput.value = ''; // Clear the value to avoid any hidden submission
            typeSelect.value = ''; // Clear the value to avoid any hidden submission
        }
    }
    
    // Call toggleCodeInput on page load to apply initial state
    window.onload = toggleCodeInput;


    // Custom password validation function (if needed)
    function validatePassword() {
        const password = document.querySelector('[name="password"]').value;
        if (password.length < 6) {
            alert('Password should be at least 6 characters long');
            return false;  // Prevent form submission
        }
        return true;  // Allow form submission if validation passes
    }
</script>

    <script>
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('ion-icon');
            
            if (input.type === "password") {
                input.type = "text"; // Show password
                icon.setAttribute('name', 'eye-off-outline'); // Change icon to "eye-off"
            } else {
                input.type = "password"; // Hide password
                icon.setAttribute('name', 'eye-outline'); // Change icon back to "eye"
            }
        }
    </script>
    

</html>
