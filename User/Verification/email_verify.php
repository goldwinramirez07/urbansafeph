<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Safe</title>
    <link rel="stylesheet" href="../../css/login.css">
    
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
            font-size: 19px;
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
    <?php 
    include "../../config/mysql.php";
    
    if(isset($_POST['submit'])) {
        if(isset($_GET['code'])) {
            $activation_code = $_GET['code'];
            $otp = $_POST['otp'];
            
            $sql = "SELECT * FROM login WHERE activation_code = '".$activation_code."'";
            $result = mysqli_query($conn, $sql);
            
            if(mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $rowOtp = $row['otp'];
                $rowSignuptime = $row['created_at'];
                
                // Correct date formatting to use 'h:i:s'
                $rowSignuptime = date('d-m-Y h:i:s', strtotime($rowSignuptime));
                $rowSignuptime = date_create($rowSignuptime);
                date_modify($rowSignuptime, "+1 minutes");
                $timeUp = date_format($rowSignuptime, 'd-m-Y h:i:s');
                
                if ($rowOtp !== $otp) {
                    echo "<script>alert('Please provide the correct OTP..!')</script>";
                } else {
                    if (date('d-m-Y h:i:s') >= $timeUp) {
                        echo "<script>alert('Your time is up... try it again..!')</script>";
                        header("Refresh:1; url=sign.php");
                    } else {
                        $sqlUpdate = "UPDATE login SET otp = '', status = 'active' WHERE otp = '".$otp."' AND activation_code = '" .$activation_code."'";
                        $resultUpdate = mysqli_query($conn, $sqlUpdate);
                        
                        if ($resultUpdate) {
                            echo "<script>alert('Your account has been successfully activated!'); window.location.href='http://urbansafeph.com/User/member.php';</script>";
                            exit;
                        } else {
                            echo "<script>alert('Opss.. Your account is not activated!') window.location.href='http://urbansafeph.com/User/sign.php'</script>";
                            exit;
                        }
                    }
                }
            } else {
                header("Location: sign.php");
            }
        }
    }
    ?>
    <header>
        <div class="header-container">
            <a href="../../index.php"><img src="../../pics/logo.png" alt="Logo" class="logo"></a>
            <h1 class="title"><i>ES TECH INNOVATIONS</i></h1>
        </div>
    </header>
    
<div class="registration-container">
    <div class="registration-box">
        <h1>OTP VERIFY</h1>
        <form action="" method="post">
            
            <div class="input-group">
                <input type="text" class= "input-field" placeholder="Enter OTP to verify email" autocomplete="off" name="otp" required>
                    <span class="icon"><ion-icon name="barcode-outline"></ion-icon></span>
            </div>
        
            <input type="submit" value="VERIFY" name="submit" class= "register-btn">
            
        </form>
        
            <div class="login-link"><br>
                Already have an account? <a href="../member.php">Login</a>
            </div>
    </div>
</div>
    
    <footer>
        <p>&copy; 2024 ES Tech Innovations. All Rights Reserved.</p>
    </footer>
    
</body>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</html>
