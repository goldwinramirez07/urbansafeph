<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Safe</title>
    <link rel="stylesheet" href="../../css/report.css">
    
<style>
    body {
        height: 100px;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #FFF;
    }
    
       /* Center the form on the page */
        .report-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            height: 80vh;
        }

        /* Styling the registration box */
        .report-box {
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

        .contact-respond-btn {
            width: 100%; /* Ensures the input fills the container */
            padding: 12px;
            padding-left: 40px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 18px;
            box-sizing: border-box; /* Prevents padding from affecting width */
        }
        
        .report-fire-btn {
            width: 100%; /* Ensures the input fills the container */
            padding: 12px;
            padding-left: 40px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 17px;
            box-sizing: border-box; /* Prevents padding from affecting width */
        }
        
        .report-flood-btn {
            width: 100%; /* Ensures the input fills the container */
            padding: 12px;
            padding-left: 40px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 18px;
            box-sizing: border-box; /* Prevents padding from affecting width */
        }
        
        .report-police-btn{
        background-color: #9776C0;
        color: #ffffff; /* Button text color */
        width: 100%; /* Ensures the input fills the container */
        padding: 12px;
        padding-left: 40px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 18px;
        box-sizing: border-box; /* Prevents padding from affecting width */
        background-image: url('../../../pics/crime.png'); /* Add your image */
        background-size: 25px; /* Width of the image */
        background-repeat: no-repeat; /* Prevent repeating the image */
        background-position: 10px center; /* Adjust positioning of the image */
        padding-left: 40px; /* Increase padding to make space for the image */
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

        .back-btn {
            width: 100%;
            padding: 12px;
            background-color: #FFD700;
            color: black;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .back-btn:hover {
            background-color: #FFEB00;
        }

        .back-btn {
            text-align: center;
            color: black;
            font-size: 14px;
        }

        .back-btn a {
            color: #FFD700;
            text-decoration: none;
        }

        .back-btn a:hover {
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
    
</style>
</head>

<body>
    <br><br><br><br><br>
    <input type="submit" class="menu-btn" value="URBANSAFE" onclick="toggleMenu()">
    <div id="menu" class="menu">
        <!-- Menu content goes here -->
        <ul>
            <br><br>
            <li><input type="submit" class="menu-display" value="URBANSAFE" onclick="toggleMenu()"></li>
            <li><input type="submit" class="menu-forum-display" value="FORUM" onclick="goToForum()"></li>
            <li><input type="submit" class="menu-report-display" value="REPORT" onclick="goToReport()"></li>
        </ul>
    </div>

<?php
// Include the database configuration file
include('../../config/mysql.php'); // Assuming mysql.php contains the connection details

// Default contact number
$contact_no = '#'; // Default in case no selection is made

// Array to store the contact numbers for each responder type
$responder_contacts = [
    'Medical' => '',
    'Firefighter' => '',
    'Flood' => '',
    'Police' => ''
];

// Fetch the contact numbers for each responder type from the database
$sql = "SELECT responder_type, contact_no FROM login";
$stmt = $conn->prepare($sql);

if ($stmt && $stmt->execute()) {
    $result = $stmt->get_result();

    // Store each responder's contact in the array
    while ($row = $result->fetch_assoc()) {
        if (isset($responder_contacts[$row['responder_type']])) {
            $responder_contacts[$row['responder_type']] = $row['contact_no'];
        }
    }

    $stmt->close();
} else {
    echo "Error executing query: " . $conn->error;
}

$conn->close();
?>


    <div class="report-container">
        <div class="report-box">
            <h1>CONTACT A<br>RESPONDER</h1>
             <!-- Hidden contact number elements -->
            <div id="contact-medical" style="display: none;"><?= $responder_contacts['Medical'] ?: 'No contact available' ?></div>
            <div id="contact-firefighter" style="display: none;"><?= $responder_contacts['Firefighter'] ?: 'No contact available' ?></div>
            <div id="contact-flood" style="display: none;"><?= $responder_contacts['Flood'] ?: 'No contact available' ?></div>
            <div id="contact-police" style="display: none;"><?= $responder_contacts['Police'] ?: 'No contact available' ?></div>
            
            <!-- Buttons that call the makeCall function with the respective responder type -->
            <div class="input-group">
                <button class="contact-respond-btn" onclick="makeCall('Medical')">MEDICAL RESPONDER</button>
            </div>

            <div class="input-group">
                <button class="report-fire-btn" onclick="makeCall('Firefighter')">FIREFIGHTER RESPONDER</button>
            </div>

            <div class="input-group">
                <button class="report-flood-btn" onclick="makeCall('Flood')">FLOOD RESPONDER</button>
            </div>

            <div class="input-group">
                <button class="report-police-btn" onclick="makeCall('Police')">POLICE RESPONDER</button>
            </div>

            <div class="input-group">
                <form action="../logout.php" method="post">
                    <input type="submit" class="back-btn" name="backreport1" value="GO BACK">
                </form>
            </div>
        </div>
    </div>



    <footer>
        <p>&copy; 2024 ES Tech Innovations. All Rights Reserved.</p>
    </footer>
    
    <script>
        function toggleMenu() {
            var menu = document.getElementById("menu");
            if (menu.style.left === "0px") {
                menu.style.left = "-200px"; // Hide the menu
            } else {
                menu.style.left = "0px"; // Show the menu
            }
        }
        function goToReport() {
            window.location.href = "report1.php";
        }
        function goToForum() {
            window.location.href = "../../Forum/forum.php";
        }
        function makeCall(responder_type) {
            // Get the contact number based on the responder type
            var contactNumber = document.getElementById('contact-' + responder_type.toLowerCase()).textContent;
            
            if (contactNumber !== 'No contact available' && contactNumber.trim() !== '') {
                // If a valid contact number is found, open the dialer app
                window.location.href = "tel:+63" + contactNumber;
            } else {
                alert("No contact number available for this responder.");
            }
        }
    </script>

</body>
</html>
