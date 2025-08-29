<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urban Safe</title>
    <link rel="stylesheet" href="../../css/report.css">
    <script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
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

        .input-field {
            width: 100%; /* Ensures the input fills the container */
            padding: 12px;
            padding-left: 40px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
            box-sizing: border-box; /* Prevents padding from affecting width */
        }


     .input-group-file {
                position: relative;
                margin-bottom: 20px;
            }
    
    .input-field-file {
            width: 100%; /* Ensures the input fills the container */
            padding: 12px;
            padding-left: 40px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 14px;
            box-sizing: border-box; /* Prevents padding from affecting width */
            color: white;
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

        .report-btn {
            width: 100%;
            padding: 12px;
            background-color: #FFD700;
            color: black;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .report-btn:hover {
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
    </style>
</head>

<body>
<br><br><br><br><br>
<div class="report-container">
    <div class="report-box">
        <h1>REPORT CRIME</h1>
        <form action="crime-report-process1.php" method="POST" enctype="multipart/form-data" id="reportForm">
        
        <div class="input-group"> 
            <input type="text" class="input-field" id="locationInput" name="loc" placeholder="Location" required>
            <span class="icon"><ion-icon name="location-outline"></ion-icon></ion-icon></span>
        </div>
        
        <div class="input-group"> 
            <input type="text" class="input-field" name="dti" id="dateTimeInput" placeholder="Date & Time" required>
            <span class="icon"><ion-icon name="time-outline"></ion-icon></ion-icon></span>
        </div>
        
        <div class="input-group"> 
            <input type="text" class="input-field" name="rep" placeholder="Description (Short Description)" required>
            <span class="icon"><ion-icon name="document-outline"></ion-icon></ion-icon></span>
        </div>
          
        <div class="input-group"> 
            <input type="file" class="input-field-file" name="img" accept="image/*" capture="camera" required>
            <span class="icon"><ion-icon name="folder-outline"></ion-icon></ion-icon></span>
        </div>
        
            <!-- Hidden inputs for latitude and longitude -->
            <input type="hidden" id="latitudeInput" name="latitude">
            <input type="hidden" id="longitudeInput" name="longitude">
            
            <input type="submit" class="report-btn" id="submitBtn" name="submit" value="SUBMIT"> 
            
            <div class="login-link"><br>
            <a href="report1.php">CANCEL</a>
            </div>
        </form>
    </div>
</div>
     <footer>
    <p>&copy; 2024 ES Tech Innovations. All Rights Reserved.</p>
    </footer>
    
    <script>
        const accessToken = "pk.48bd57a4d2ce4be8c7542cc52bbc98ea"; // Your LocationIQ access token

        // Automatically set the date and time
        function setDateTime() {
            const now = new Date();
            const formattedDateTime = now.toLocaleString('en-US', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            }).replace(',', ''); // Removes the comma to match the format
    
            document.getElementById("dateTimeInput").value = formattedDateTime;
        }
    
        // Define geofence coordinates
        const geofenceCoords = [
            { lat: 14.598515, lng: 121.017364 },
            { lat: 14.597663, lng: 121.017669 },
            { lat: 14.596980, lng: 121.018488 },
            { lat: 14.597302, lng: 121.019369 },
            { lat: 14.597484, lng: 121.019336 },
            { lat: 14.598197, lng: 121.021680 },
            { lat: 14.598720, lng: 121.021483 },
            { lat: 14.598428, lng: 121.020355 },
            { lat: 14.598558, lng: 121.020280 },
            { lat: 14.598728, lng: 121.020342 },
            { lat: 14.598703, lng: 121.020465 },
            { lat: 14.598941, lng: 121.020522 },
            { lat: 14.599050, lng: 121.020390 },
            { lat: 14.598951, lng: 121.020264 },
            { lat: 14.599431, lng: 121.019253 },
            { lat: 14.598515, lng: 121.017364 }
        ];
    
        // Automatically get the user's location using Geolocation and LocationIQ reverse geocoding 
        function setLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError, { timeout: 10000 }); // Set timeout to avoid long waits
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
    
        // Check if the point is inside the geofence polygon
        function isInsideGeoFence(lat, lng) {
            const point = turf.point([lng, lat]);
            const polygon = turf.polygon([geofenceCoords.map(coord => [coord.lng, coord.lat])]);
            return turf.booleanPointInPolygon(point, polygon);
        }
    
        // Function to fetch and set the user's address using reverse geocoding
        function fetchAddress(latitude, longitude) {
            const url = `https://us1.locationiq.com/v1/reverse.php?key=${accessToken}&lat=${latitude}&lon=${longitude}&format=json`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const address = data.display_name; // Extract the full address
                    document.getElementById("locationInput").value = address;
                })
                .catch(error => {
                    console.error("Error fetching address:", error);
                    document.getElementById("locationInput").value = "Unable to retrieve address.";
                });
        }
        
        // Updated showPosition function to include reverse geocoding
        function showPosition(position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            document.getElementById("latitudeInput").value = latitude;
            document.getElementById("longitudeInput").value = longitude;
        
            // Fetch and display the user's address
            fetchAddress(latitude, longitude);
        
            // Check if the user is inside the geofence
            if (isInsideGeoFence(latitude, longitude)) {
                console.log("User is inside the geofence.");
                enableInputs();
            } else {
                alert("You are outside the allowed area for this service.");
                disableInputs(); // Disable all input fields
            }
        }
    
    
        // Enable all input fields
        function enableInputs() {
            document.querySelectorAll("input, select, textarea, button").forEach(input => {
                input.disabled = false;
            });
        }
    
        // Disable all input fields
        function disableInputs() {
            document.querySelectorAll("input, select, textarea, button").forEach(input => {
                input.disabled = true;
            });
        }
    
        // Handle error scenarios for geolocation
        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("User denied the request for Geolocation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location information is unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("The request to get user location timed out.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("An unknown error occurred.");
                    break;
            }
            disableInputs();
        }
    
        // Automatically call setDateTime and setLocation when the page loads
        window.onload = function () {
            setDateTime();
            setLocation();
        };
    
        // Client-side date validation
        document.getElementById("reportForm").onsubmit = function(event) {
            const dateTimeInput = document.getElementById("dateTimeInput").value;
            const dateTimePattern = /^\d{1,2}\/\d{1,2}\/\d{4} \d{1,2}:\d{2}:\d{2} (AM|PM)$/;
            
            if (!dateTimePattern.test(dateTimeInput)) {
                alert("Invalid date format. Please enter in 'MM/DD/YYYY HH:MM:SS AM/PM' format.");
                event.preventDefault();
            }
        };
    </script>
</body>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</html>
