<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Safe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.heat/dist/leaflet-heat.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }

        header {
            background-color: #1B413A;
            height: 100px;
            display: flex;
            align-items: center; /* Vertically centers the logo */
            padding: 10px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative; /* Position relative for dropdown */
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between; /* Space between logo/title and notification bell */
            width: 100%; /* Use full width */
            height: 50px;
        }

        .logo {
            height: 110px; /* Adjust this value for logo size */
            width: auto;
         }   
    
        .title {
            margin-left: 10px; 
            font-size: 2rem;
            color: #FFFFFF;
        }

         .urban-logo {
          margin-top: 20px;
          width: auto;
          height: auto;
        }

        .report-btn {
            background-color: #EA1010;
            color: #FFFFFF;
            border-radius: 12px;
            width: 231px;    
            height: 88px;
        }

        .login-btn {
            background-color: #000000;
            color: #FFFFFF;
            border-radius: 12px;
            width: 212px;
            height: 53px;
        }

        /* Notification Styles */
        .notification-bell {
            position: relative;
            cursor: pointer;
            color: #FFFFFF;
            margin-left: 20px; /* Add margin for spacing */
        }

        .notification-dropdown {
            display: none;
            position: absolute;
            right: 0; /* Align dropdown to the right */
            background-color: #FFFFFF;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 10;
            width: 200px; /* Set width for dropdown */
        }

        .notification-dropdown.active {
            display: block;
        }

        .notification-item {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            color: black; /* Set text color to black */
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-item:hover {
            background-color: #f0f0f0;
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
    
        /* Responsive Styles */
        @media (max-width: 600px) {
            .header-container {
                flex-direction: row;
                justify-content: center;
            }

            .logo {
                height: 40px;
                margin-right: 10px;
            }

            .title {
                font-size: 20px;
            }
        }
    </style>
</head>
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('active');
            
            // Only update active status if dropdown is opened
            if (dropdown.classList.contains('active')) {
                updateNotificationStatus();
            }
        }

        function updateNotificationStatus() {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../../../update_notification_status2.php", true); // Create a new PHP file for this
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Optionally, you can handle the response here
                    document.getElementById("notification-count").textContent = "0"; // Set count to 0
                }
            };
            xhr.send(); // Send the request
        }

        // Close the dropdown if clicked outside
        window.onclick = function(event) {
            if (!event.target.matches('.notification-bell')) {
                const dropdowns = document.getElementsByClassName('notification-dropdown');
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('active')) {
                        openDropdown.classList.remove('active');
                    }
                }
            }
        }
    </script>
<body>
    <header>
        <div class="header-container">
            <div style="display: flex; align-items: center;">
                <img src="../../../pics/logo.png" alt="Logo" class="logo"></a>
                <h1 class="title"><i>ES TECH INNOVATIONS</i></h1>
            </div>
            <div class="notification-bell" onclick="toggleDropdown()">
                <i class="fa fa-bell fa-lg"></i>
                <span id="notification-count" style="color: red;">
                    <?php 
                    include('../../../config/mysql.php'); // Include your database connection file
                    
                    // Fetch the count of active notifications
                    $count_sql = "SELECT COUNT(*) as count FROM incidentreport WHERE active = 1";
                    $count_result = $conn->query($count_sql);
                    $notification_count = $count_result->fetch_assoc()['count'];
                    echo $notification_count; 
                    ?>
                </span> <!-- Dynamic notification count -->
                <div class="notification-dropdown" id="notificationDropdown">
                    <?php
                    // Fetch notifications from the firereport table
                    $sql = "SELECT * FROM incidentreport WHERE active = 1 ORDER BY date_time DESC LIMIT 5"; // Adjust the query as needed
                    $result = $conn->query($sql);

                    // Check if there are results
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo '<div class="notification-item">' . htmlspecialchars($row['loc']) . ' - ' . htmlspecialchars($row['report']) . '</div>'; // Display location and report
                        }
                    } else {
                        echo '<div class="notification-item">No new notifications</div>';
                    }

                    // Close the connection
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </header>
    <center>
        <h1><i>GREETINGS<br>MEDICAL RESPONDER!</i></h1>
        <img src="../../../pics/urban-logo.png" alt="Logo" class="urban-logo">
        <h1><i>THANK YOU FOR<br>YOUR SERVICE</i></h1>
            <form action="../see-report.php" method="post">
                <input type="submit" name="seemed" class="report-btn" value="SEE REPORT">
            <br><br> 
            </form>
            <form action="../../logout.php" method="post">
                <input type="submit" name="submit" class="login-btn" value="LOGOUT">
                <br><br><br>
            </form>
    </center>
    <footer>
        <p>&copy; 2024 ES Tech Innovations. All Rights Reserved.</p>
    </footer>
    
</body>
</html>
