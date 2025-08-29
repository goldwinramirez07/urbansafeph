<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Safe</title>
</head>
<style>
    /* General Styles */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: ##FFF;
        justify-content: center;
        align-items: center;
        height: 100vh; /* Full viewport height */
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
        width: 500px;
        max-width: auto;
        box-sizing: border-box; /* Ensures padding does not overflow */
        }
        
    .menu {
        position: fixed;
        top: 0;
        left: -200px; /* Start hidden on the left */
        width: 200px;
        height: 100%;
        background-color: #333;
        transition: left 0.3s ease;
        padding: 0px;
        box-sizing: border-box;
        z-index: 1;
    }
    .menu-btn {
        position: absolute;
        top: 50px;
        background-color: #1B413A; /* Button background color */
        color: #FFFFFF; /* Button text color */
        border: none; /* Remove border */
        padding: 20px 40px; /* Add some padding */
        font-size: 20px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-top-right-radius: 12px; /* Rounded corners */
        border-bottom-right-radius: 12px; /* Rounded corners */
        background-image: url('../../pics/urban-logo.png'); /* Add your image */
        background-size: 40px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 10px center; /* Adjust positioning of the image */
        padding-left: 60px; /* Increase padding to make space for the image */
    }
    
    .forum-open-btn {
        background-color: #1B413A;
        position: absolute;
        top: 50px;
        right: 10px;
        border: none; /* Remove border */
        padding: 20px 50px; /* Add some padding */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius: 12px;
        background-image: url('../../pics/forum.png'); /* Add your image */
        background-size: 40px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 10px center; /* Adjust positioning of the image */
        padding-left: 10px; /* Increase padding to make space for the image */
    }
    
    .contact-respond-btn {
        width: 100%; /* Ensures the input fills the container */
        background-color: #fff;
        color: #000; /* Button text color */
        border: 1px solid black; /* Adding a thick black border */
        padding: 15px 40px; /* Add some padding */
        font-size: 15px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius: 20px; /* Rounded corners */
        background-image: url('../../pics/phone.png'); /* Add your image */
        background-size: 35px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 15px center; /* Adjust positioning of the image */
        padding-left: 60px; /* Increase padding to make space for the image */
    }
    
    .report-fire-btn {
        width: 100%; /* Ensures the input fills the container */
        background-color: #A10707;
        color: #ffffff; /* Button text color */
        border: 1px solid black; /* Adding a thick black border */
        padding: 15px 40px; /* Add some padding */
        font-size: 15px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius: 20px; /* Rounded corners */
        background-image: url('../../pics/fire.png'); /* Add your image */
        background-size: 35px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 15px center; /* Adjust positioning of the image */
        padding-left: 60px; /* Increase padding to make space for the image */
    }
    
    .report-flood-btn {
        width: 100%; /* Ensures the input fills the container */
        background-color: #16C7C7;
        color: #ffffff; /* Button text color */
        border: 1px solid black; /* Adding a thick black border */
        padding: 15px 40px; /* Add some padding */
        font-size: 15px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius: 20px; /* Rounded corners */
        background-image: url('../../pics/flood.png'); /* Add your image */
        background-size: 35px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 15px center; /* Adjust positioning of the image */
        padding-left: 60px; /* Increase padding to make space for the image */
    }
    .report-crime-btn {
        width: 100%; /* Ensures the input fills the container */
        background-color: #9776C0;
        color: #ffffff; /* Button text color */
        border: 1px solid black; /* Adding a thick black border */
        padding: 15px 40px; /* Add some padding */
        font-size: 15px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius: 20px; /* Rounded corners */
        background-image: url('../../pics/crime.png'); /* Add your image */
        background-size: 35px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 15px center; /* Adjust positioning of the image */
        padding-left: 60px; /* Increase padding to make space for the image */
    }
    .report-incident-btn {
        width: 100%; /* Ensures the input fills the container */
        background-color: #FF5C00;
        color: #ffffff; /* Button text color */
        border: 1px solid black; /* Adding a thick black border */
        padding: 15px 40px; /* Add some padding */
        font-size: 15px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius: 20px; /* Rounded corners */
        background-image: url('../../pics/incident.png'); /* Add your image */
        background-size: 35px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 15px center; /* Adjust positioning of the image */
        padding-left: 60px; /* Increase padding to make space for the image */
    }
    
    .menu-display {
        width: 100%; /* Ensures the input fills the container */
        background-color: #FFE500; /* Button background color */
        color: #000; /* Button text color */
        border: none; /* Remove border */
        padding: 10px 20px; /* Add some padding */
        font-size: 16px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius:  8px; /* Rounded corners */
        background-image: url('../../pics/urban-logo.png'); /* Add your image */
        background-size: 20px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 20px center; /* Adjust positioning of the image */
        padding-left: 40px; /* Increase padding to make space for the image */
    }
    
    .menu-logout {
        background-color: #FFE500; /* Button background color */
        color: #000; /* Button text color */
        border: none; /* Remove border */
        padding: 10px 20px; /* Add some padding */
        font-size: 16px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius:  8px; /* Rounded corners */
    }
    
    .menu-home-display {
        width: 100%; /* Ensures the input fills the container */
        background-color: #FFE500; /* Button background color */
        color: #000; /* Button text color */
        border: none; /* Remove border */
        padding: 10px 20px; /* Add some padding */
        font-size: 16px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius:  8px; /* Rounded corners */
        background-image: url('../../pics/home.png'); /* Add your image */
        background-size: 20px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 20px center; /* Adjust positioning of the image */
        padding-left: 40px; /* Increase padding to make space for the image */
    }
    .menu-forum-display {
        width: 100%; /* Ensures the input fills the container */
        background-color: #FFE500; /* Button background color */
        color: #000; /* Button text color */
        border: none; /* Remove border */
        padding: 10px 20px; /* Add some padding */
        font-size: 16px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius:  8px; /* Rounded corners */
        background-image: url('../../pics/forum.png'); /* Add your image */
        background-size: 20px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 20px center; /* Adjust positioning of the image */
        padding-left: 40px; /* Increase padding to make space for the image */
    }
    .menu-report-display {
        background-color: #FFE500; /* Button background color */
        color: #000; /* Button text color */
        border: none; /* Remove border */
        padding: 10px 20px; /* Add some padding */
        font-size: 16px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius:  8px; /* Rounded corners */
        background-image: url('../../pics/report.png'); /* Add your image */
        background-size: 20px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 10px center; /* Adjust positioning of the image */
        padding-left: 40px; /* Increase padding to make space for the image */
    }
    .menu-notification-display {
        background-color: #FFE500; /* Button background color */
        color: #000; /* Button text color */
        border: none; /* Remove border */
        padding: 10px 20px; /* Add some padding */
        font-size: 16px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius:  8px; /* Rounded corners */
        background-image: url('../../pics/notification.png'); /* Add your image */
        background-size: 20px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 10px center; /* Adjust positioning of the image */
        padding-left: 40px; /* Increase padding to make space for the image */
    }
    
    .menu {
        position: fixed;
        top: 0;
        left: -200px;
        width: 200px;
        height: 100%;
        background-color: #333;
        transition: left 0.3s ease;
        padding: 0px;
        box-sizing: border-box;
        z-index: 1;
    }
    
    .menu ul {
        list-style: none;
        padding: 0;
    }
    
    .menu ul li {
        color: #ffffff;
        padding: 10px 10px;
    }
    
    /* Responsive Styles */
    @media (max-width: 600px) {
        .logo {
            height: 40px;
            margin-right: 10px;
        }
    
        .title {
            font-size: 20px;
        }
        .form {
            width: 100%;
            padding: 10px;
            
        }
        
        .menu-btn {
        width: 50%;
        padding: auto;
        font-size: 1.2em;
    }

    .forum {
        width: 100%;
        font-size: 1em;
        margin-top: 10px;
        padding-left: 10px;
        
    }
    .index-container {
        height: 100vh;
        }
    }
    
    footer {
        position: relative;
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
    
    .form {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        box-sizing: border-box;
        background-color: #1B413A;
        border: 2px solid;
        border-radius: 15px;
        max-width: 400px;
        margin: auto; /* Ensure it's centered */
        padding-top: 20px;
        padding-bottom: 40px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), /* Main shadow */
                    0 6px 20px rgba(0, 0, 0, 0.1); 
    }
    
    .record {
    display: flex; /* Enable flexbox layout */
    justify-content: space-between; /* Distribute space between the two divs */
    align-items: center; /* Center align vertically */
    text-align: center;
    box-sizing: border-box;
    background-color: #1B413A;
    border: 1px solid;
    border-radius: 15px;
    max-width: 400px; /* Adjust width of the container */
    margin: auto; /* Center the container */
    padding: 20px; /* Add padding around the content */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), /* Main shadow */
                0 6px 20px rgba(0, 0, 0, 0.1);
}

    .record {
    display: flex; /* Enable flexbox layout */
    justify-content: space-between; /* Distribute space between the two divs */
    align-items: center; /* Center align vertically */
    text-align: center;
    box-sizing: border-box;
    border: 2px solid;
    border-radius: 15px;
    max-width: 400px; /* Adjust width of the container */
    margin: auto; /* Center the container */
    padding: 20px; /* Add padding around the content */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1), /* Main shadow */
                0 6px 20px rgba(0, 0, 0, 0.1);
}

</style>
<body>
    <br><br><br><br><br><br>
    <input type="submit" class="menu-btn" value="URBANSAFE" onclick="toggleMenu()">
    <div id="menu" class="menu">
        <!-- Menu content goes here -->
        <ul>
            <li><input type="submit" class="menu-display" value="URBANSAFE" onclick="toggleMenu()"></li>
            <li><input type="submit" class="menu-home-display" value="HOME" onclick="goToHome()"></li>
            <li><input type="submit" class="menu-forum-display" value="FORUM" onclick="goToForum()"></li>
        </ul>
    </div>
    
    <div id="forum" class="forum">
        <form action="../../Forum/forum.php" method="post">
                <input type="submit" name="submit" class="forum-open-btn" value="">
        </form>
    </div>

<div class="index-container">
    <div class="index-box">
        <center><h2>DASHBOARD</h2></center>
        
        <div class="form">
            <form action="contact.php" method="post">
            <input type="submit" name="submit" class="contact-respond-btn" value="CONTACT RESPONDER">
            <br><br>
            <input type="submit" name="submit-fire" class="report-fire-btn" value="REPORT FIRE">
            <br><br>
            <input type="submit" name="submit-flood" class="report-flood-btn" value="REPORT FLOOD">
            <br><br>
            <input type="submit" name="submit-crime" class="report-crime-btn" value="REPORT CRIME">
            <br><br>
            <input type="submit" name="submit-incident" class="report-incident-btn" value="REPORT INCIDENT">
            </form>
        </div> <br>
    </div>
</div>

    <script>
        function toggleMenu() {
            var menu = document.getElementById("menu");
            if (menu.style.left === "0px") {
                menu.style.left = "-200px"; // Hide the menu
            } else {
                menu.style.left = "0px"; // Show the menu
            }
        }
        
        function goToHome() {
            window.location.href = "../../index.php";
        }
        
        function goToForum() {
            window.location.href = "../../Forum/forum.php";
        }
    </script>

</body>
</html>