<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Safe</title>
    <link rel="stylesheet" href="../css/index.css">
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
    
        h1 {
        color: #1B413A;
        font-size: 30px;
        text-align: center;
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
        margin-left: 0px; /* Adds space between the logo and title */
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
        width: 250px;
    }
    
    .report-btn:hover {
    background-color: #FF0000;
    }
    
    .login-btn {
    background-color: #1B413A;
    color: #ffffff;
    border-radius: 12px;
    width: 250px;
    height: 53px;
    }
    
    .login-btn:hover {
    background-color: #145a45;
    }
        
    .index-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-grow: 1;
        height: 80vh;
        }

    .index-box {
        padding: 30px;
        border: 5px solid black; /* Adding a thick black border */
        border-color: #1B413A;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 1);
        width: 350px;
        max-width: 100%;
        box-sizing: border-box; /* Ensures padding does not overflow */
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
    
    .home {
        text-align: center;   
    }
    
    @media (max-width: 768px) {
        .title {
            font-size: 1.2rem;
        }

        .urban-logo {
            width: 150px; /* Adjust width for smaller screens */
            height: auto; /* Allow height to adjust automatically */
        }

        .report-btn, .login-btn {
            padding: 8px 15px; /* Smaller padding for smaller screens */
            font-size: 1rem;
        }
    }
    
</style>
</head>

<body>
    <header>
        <div class="header-container">
            <a href="index.php"><img src="pics/logo.png" alt="Logo" class="logo"></a>
            <h1 class="title"><i>ES TECH INNOVATIONS</i></h1>
        </div>
    </header>

<div class="index-container">
    <div class="index-box">
        <div class="home">
            <form action="/User/Guest/report.php" method="post">
        <h1>URBANSAFE</h1>
        <img src="pics/urban-logo.png" alt="Logo" class="urban-logo"><br>
        <input type="submit" class="report-btn" value="MAKE A REPORT">
        <br><br> 
    </form>
    
    <form action="User/login.php" method="post">
        <input type="submit" class="login-btn" value="LOGIN">
        <br><br><br>
    </form>
        </div>
</div></div>

    <footer>
        <p>&copy; 2024 ES Tech Innovations. All Rights Reserved.</p>
    </footer>
</body>
</html>
