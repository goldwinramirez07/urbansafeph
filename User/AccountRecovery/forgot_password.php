<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Safe</title>
    <link rel="stylesheet" href="../../css/member.css">
    
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
        .forgot-password-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            height: 80vh;
        }

        /* Styling the login box */
        .forgot-password-box {
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

        .send-btn {
            width: 100%;
            padding: 12px;
            background-color: #FFD700;
            color: black;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .send-btn:hover {
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

            .forgot-password-box {
                width: 90%; /* Makes it responsive for mobile */
            }
        }
        
    </style>
</head>

<body>
    <header>
        <div class="header-container">
            <a href="../../index.php"><img src="../../pics/logo.png" alt="Logo" class="logo"></a>
            <h1 class="title"><i>ES TECH INNOVATIONS</i></h1>
        </div>
    </header>

    <div class="forgot-password-container">
        <div class="forgot-password-box">
        <h1>Forgot Password</h1>
        <form action="forgotpass.php" method="post">
            
            <div class="input-group">
                <input type="email" class="input-field" name="email" placeholder="Enter Email Address" required>
                <span class="icon"><ion-icon name="mail-outline"></ion-icon></span>
                </div>
            
                <input type="submit" class="send-btn" name="submit" value="SEND INSTRUCTIONS">
            </form>
            
            <div class="login-link"><br>
                Remembered your password? <a href="../member.php">Login</a>
            </div>
        </div>
    </div>
    
    <footer>
        <p>&copy; 2024 ES Tech Innovations. All Rights Reserved.</p>
    </footer>
    
</body>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</html>
