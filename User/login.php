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
        height: 100px;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #FFF;
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
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-grow: 1;
        height: 80vh;
        }

    /* Styling the registration box */
    .login-box {
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
        font-size: 30px;
        text-align: center;
        }

    form {
        margin-top: 20px;
        }
    
    .login-btn {
        width: 100%;
        height: 100px;
        padding: 12px;
        background-color: #9EDF9C;;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        }

    .login-btn:hover {
        background-color: #72BF78;;
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
        .login-box {
            width: 90%; /* Makes it responsive for mobile */
        }
    }

    
</style>
</head>

<body>
    <header>
        <div class="header-container">
            <a href="../index.php"><img src="../../pics/logo.png" alt="Logo" class="logo"></a>
            <h1 class="title"><i>ES TECH INNOVATIONS</i></h1>
        </div>
    </header>

<div class="login-container">
    <div class="login-box">
        <h1>LOGIN AS</h1>
        <form action="member.php" method="post">
            
            <input type="submit" class="login-btn" value="MEMBER">
        </form>
        
        <form action="Responder/responder.php" method="post">
            <input type="submit" class="login-btn" value="RESPONDER">
        </form>
        </form>
    </center>
    
    <footer>
        <p>&copy; 2024 ES Tech Innovations. All Rights Reserved.</p>
    </footer>
    
</body>
</html>