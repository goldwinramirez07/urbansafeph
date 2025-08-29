<?php
include('../config/mysql.php');

if (isset($_POST['submit'])) {
    // Fetch values from the form
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $cn = mysqli_real_escape_string($conn, $_POST['contact']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    $r_type = isset($_POST['type']) ? mysqli_real_escape_string($conn, $_POST['type']) : ''; // Handle undefined "type" field
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password securely

    // Insert data into the admin table
    $stmt = $conn->prepare("INSERT INTO admin (code, responder_type, name, email, contact_no, role, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiss", $code, $r_type, $name, $email, $cn, $role, $password);

    if ($stmt->execute()) {
        echo '<script>alert("Registration successful.");</script>';
    } else {
        echo '<script>alert("Failed to register. Error: ' . $conn->error . '");</script>';
    }

    // Close the statement
    $stmt->close();
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
    /* Menu styling */
    .menu {
        position: fixed;
        top: 0;
        left: -200px;
        width: 200px;
        height: 100%;
        background-color: #333;
        transition: left 0.3s ease;
        z-index: 2;
    }

.menu-btn {
    position: absolute;
    top: 50px;
    background-color: #1B413A;
    color: #FFFFFF;
    border: none;
    padding: 20px 40px;
    font-size: 20px;
    cursor: pointer;
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
    background-image: url('../pics/urban-logo.png');
    background-size: 40px;
    background-repeat: no-repeat;
    background-position: 10px center;
    padding-left: 60px;
}

    .menu ul {
        list-style: none;
        padding: 0;
    }

    .menu ul li {
        padding: 10px;
    }

    .menu ul li input {
        width: 100%;
        background-color: #FFE500;
        border: none;
        padding: 8px;
        font-size: 14px;
        cursor: pointer;
        border-radius: 4px;
    }
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
    <input type="submit" class="menu-btn" value="URBANSAFE" onclick="toggleMenu()">
    <div id="menu" class="menu">
        <ul>
            <li><input type="submit" class="menu-display" value="URBANSAFE" onclick="toggleMenu()"></li>
            <li><input type="submit" class="menu-edit-contact" value="Home" onclick="goToHome()"></li>
            <li><input type="submit" class="menu-edit-contact" value="Responder" onclick="goToEditContactNumber()"></li>
            <li><input type="submit" class="menu-edit-radius" value="Area Covered" onclick="goToEditRadiusGeofencing()"></li>
            <li><input type="submit" class="menu-analytics" value="Analytics" onclick="goToAnalytics()"></li>
            <li><input type="submit" class="menu-logout" value="Log out" onclick="index()"></li>
        </ul>
    </div><br><br><br>
<div class="registration-container">
    <div class="registration-box">
        <h1>Register</h1>
          <form action="" method="POST" onsubmit="return validatePassword();">
        <div class="input-group">
            <select class="input-field" name="role" id="roleSelect" required onchange="toggleCodeInput()">
                <option value="Responder" Selected>Responder</option>
            </select>
            <span class="icon"><ion-icon name="person-circle-outline"></ion-icon></span>
        </div>
    
        <!-- Responder Type Dropdown (will be shown if "Responder" is selected) -->
        <div class="input-group" id="typeGroup" style="display: none;">
            <select class="input-field" name="type" id="typeSelect" required>
                <option value="" disabled selected>Select Responder Type</option>
                <option value="Firefighter">Firefighter</option>
                <option value="Flood">Flood Rescuer</option>
                <option value="Police">Police</option>
                <option value="Medical">Medical</option>
            </select>
            <span class="icon"><ion-icon name="person-circle-outline"></ion-icon></span>
        </div>
    
        <!-- Code Input (will be shown if "Responder" is selected) -->
        <div class="input-group" id="codeGroup" style="display: none;">
            <input type="text" id="codeInput" class="input-field" name="code" placeholder="Code" required autocomplete="off">
            <span class="icon"><ion-icon name="lock-closed-outline"></ion-icon></span>
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
</body>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script>
    function toggleMenu() {
        const menu = document.getElementById("menu");
        menu.style.left = menu.style.left === "0px" ? "-200px" : "0px";
    }
    
    function goToHome() {
        window.location.href = "admin.php";
    }
    function goToHistory() {
        window.location.href = "history.php";
    }
    function goToEditContactNumber() {
        window.location.href = "econtactnum.php";
    }

    function goToEditRadiusGeofencing() {
        window.location.href = "geofencing.php";
    }

    function goToAnalytics() {
        window.location.href = "Analytics.php";
    }
    function index() {
        alert("Log out successfully");
        window.location.href = "../index.php";
    }
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
