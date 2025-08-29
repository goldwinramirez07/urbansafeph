<!DOCTYPE html>
<?php
session_start();
include('../../config/mysql.php');
mysqli_close($conn);
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.heat/dist/leaflet-heat.css" />
    <style>
        #map {
            height: 600px;  /* Set the height of the map */
            width: 50%;    /* Set the width of the map */
        }
    </style>
    <title>Urban Safe</title>
</head>
<style>
    /* General Styles */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #fff;
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
        background-image: url('../../../pics/urban-logo.png'); /* Add your image */
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
        background-image: url('../../../pics/forum.png'); /* Add your image */
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
        background-image: url('../../../pics/phone.png'); /* Add your image */
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
        background-image: url('../../../pics/fire.png'); /* Add your image */
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
        background-image: url('../../../pics/flood.png'); /* Add your image */
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
        background-image: url('../../../pics/crime.png'); /* Add your image */
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
        background-image: url('../../../pics/incident.png'); /* Add your image */
        background-size: 35px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 15px center; /* Adjust positioning of the image */
        padding-left: 60px; /* Increase padding to make space for the image */
    }
    
    .menu-display {
        background-color: #FFE500; /* Button background color */
        color: #000; /* Button text color */
        border: none; /* Remove border */
        padding: 10px 20px; /* Add some padding */
        font-size: 16px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius:  8px; /* Rounded corners */
        background-image: url('../../../pics/urban-logo.png'); /* Add your image */
        background-size: 20px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 10px center; /* Adjust positioning of the image */
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
        background-color: #FFE500; /* Button background color */
        color: #000; /* Button text color */
        border: none; /* Remove border */
        padding: 10px 20px; /* Add some padding */
        font-size: 16px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius:  8px; /* Rounded corners */
        background-image: url('../../../pics/home.png'); /* Add your image */
        background-size: 20px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 10px center; /* Adjust positioning of the image */
        padding-left: 40px; /* Increase padding to make space for the image */
    }
    .menu-forum-display {
        background-color: #FFE500; /* Button background color */
        color: #000; /* Button text color */
        border: none; /* Remove border */
        padding: 10px 20px; /* Add some padding */
        font-size: 16px; /* Increase font size */
        cursor: pointer; /* Pointer cursor on hover */
        border-radius:  8px; /* Rounded corners */
        background-image: url('../../../pics/forum.png'); /* Add your image */
        background-size: 20px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 10px center; /* Adjust positioning of the image */
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
        background-image: url('../../../pics/report.png'); /* Add your image */
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
        background-image: url('../../../pics/notification.png'); /* Add your image */
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
/* Modal styles */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
}

.modal-content {
    background-color: #fefefe; /* White background */
    margin: 15% auto; /* 15% from the top and centered */
    padding: 20px; /* Padding */
    border: 1px solid #888; /* Grey border */
    width: 80%; /* Width */
    max-width: 600px; /* Maximum width */
}

.modal-header {
    border-bottom: 1px solid #888; /* Bottom border for header */
}

.modal-footer {
    border-top: 1px solid #888; /* Top border for footer */
}

.close {
    color: #aaa; /* Grey color */
    float: right; /* Float right */
    font-size: 28px; /* Font size */
    font-weight: bold; /* Bold */
}

.close:hover,
.close:focus {
    color: black; /* Change color on hover */
    text-decoration: none; /* No underline */
    cursor: pointer; /* Pointer cursor */
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
            <li><input type="button" class="menu-logout" value="USER INFO" onclick="goToInfo()"></li>
            <li><input type="submit" class="menu-logout" value="LOGOUT" onclick="logout()"></li>
        </ul>
    </div>
    
    <!-- User Info Modal -->
    <div class="modal" id="userInfoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userInfoModalLabel">User Information</h5>
                    <button type="button" class="close" onclick="closeUserInfoModal()" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="userInfoContent">Loading user information...</div>
                </div>
            </div>
        </div>
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
            <form action="contact1.php" method="post">
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
        </div><br>
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
            window.location.href = "report1.php";
        }
        
        function goToForum() {
            window.location.href = "../../Forum/forum.php";
        }
        
            
        function logout() {
            alert("Logout successfully"); // Show logout success message
            setTimeout(function() {
                window.location.href = '../logout-user.php'; // Redirect to login page after 1 second
            }, 1000); // 1000 milliseconds = 1 second
        }
        
        function goToInfo() { 
            // Fetch user data from PHP session variables
            var userId = <?php echo json_encode($_SESSION['id']); ?>;
            var userEmail = <?php echo json_encode($_SESSION['email']); ?>;
            var userName = <?php echo json_encode($_SESSION['name']); ?>;
            var userCn = <?php echo json_encode($_SESSION['cn']); ?>;
            var userRole = <?php echo json_encode($_SESSION['role']); ?>;
            
            // Construct the user info HTML
            var userInfoHtml = `
                <p><strong>User ID:</strong> ${userId}</p>
                <p><strong>Name:</strong> ${userName}</p>
                <p><strong>Email:</strong> ${userEmail}</p>
                <p><strong>Contact Number:</strong> ${userCn}</p>
                <p><strong>Role:</strong> ${userRole}</p>
            `;
    
            // Update the modal content
            document.getElementById('userInfoContent').innerHTML = userInfoHtml;
    
            // Show the modal
            document.getElementById('userInfoModal').style.display = 'block';
        }
    
        function closeUserInfoModal() {
            document.getElementById('userInfoModal').style.display = 'none';
        }
        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('userInfoModal');
            if (event.target === modal) {
                closeUserInfoModal();
            }
        }
    </script>
</body>
</html>