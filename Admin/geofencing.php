<?php
// Include MySQL connection configuration
include('../config/mysql.php');

// Fetch the current active geofence setting from the database
$sql_get_geofence = "SELECT * FROM geofence_settings WHERE is_active = 1 LIMIT 1";
$result = $conn->query($sql_get_geofence);
$active_geofence = $result->fetch_assoc();

$geofence_type = 'none';
$radius = null;
$coordinates = null;

// Check if an active geofence exists
if ($active_geofence) {
    $geofence_type = $active_geofence['geofence_type'];
    if ($geofence_type == 'radius') {
        $radius = $active_geofence['radius']; // Assuming the radius value is stored
    } elseif ($geofence_type == 'polygon') {
        $coordinates = json_decode($active_geofence['polygon_coords']); // Assuming coordinates are stored as JSON
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Geofencing Settings</title>
    <link rel="stylesheet" href="css/admin.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- LocationIQ API -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.locationiq.com/leaflet-locationiq/0.2.1/leaflet-locationiq.js"></script>
    <script src="https://unpkg.com/leaflet-geodesic@1.0.0/leaflet-geodesic.min.js"></script>

    <style>
        #map {
    height: 500px;
    width: 50%;
    margin: 0 auto; /* Horizontally centers the map */
    display: block; /* Ensures it behaves like a block element */
    justify-content: center; /* Centers the content inside the map */
    position: relative; /* Allow for relative positioning */
}
        
        .menu {
    position: fixed;
    top: 0;
    left: -200px;
    width: 200px;
    height: 100%;
    background-color: #333;
    transition: left 0.3s ease;
    padding: 0;
    box-sizing: border-box;
    z-index: 1;
}

    .index-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-grow: 1;
        height: auto;
        }

    .index-box {
        padding: 30px;
        border: 5px solid black; /* Adding a thick black border */
        border-color: #1B413A;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 1);
        width: auto;
        max-width: auto;
        box-sizing: border-box; /* Ensures padding does not overflow */
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
        
        .apply-btn {
            width: 100%;
            padding: 12px;
            background-color: #FFD700;
            color: black;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .apply-btn:hover {
            background-color: #FFEB00;
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
    margin: 0;
}

.menu ul li {
    padding: 10px 10px;
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
    /* Content container styling */
    .content-container {
        background-color: #1B413A;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        z-index: 1; /* Ensure content container has a higher z-index */
        position: relative;
        padding-top: 100px; /* Adjust as needed if the menu is fixed at the top */
    }


.menu-display,
.menu-back  {
    width: 100%;
    background-color: #FFE500;
    color: #000;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 8px;
    padding-left: 40px; /* Space for icons */
}

/* Individual button icons */
.menu-back {
    background-image: url('../pics/forum.png');
}

/* Icon styling for all buttons */
.menu-display,
.menu-forum-display,
{
    background-size: 20px;
    background-repeat: no-repeat;
    background-position: 10px center;
}

        .map-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-grow: 1;
        height: auto;
        width: 100%; /* Adjust container width */
        }

    .map-box {
        padding: 30px;
        border: 5px solid black; /* Adding a thick black border */
        border-color: #1B413A;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 1);
        width: 100%; /* Adjust box width for responsiveness */
        max-width: 1000px; /* Optional maximum width */
        box-sizing: border-box; /* Ensures padding does not overflow */
        }
        
    </style>
</head>


<body>
    
<input type="submit" class="menu-btn" value="URBANSAFE" onclick="toggleMenu()">
    <div id="menu" class="menu">
        <!-- Menu content goes here -->
        <ul>
            <li><input type="submit" class="menu-display" value="URBANSAFE" onclick="toggleMenu()"></li>
            <li><input type="submit" class="menu-edit-contact" value="Home" onclick="goToHome()"></li>
            <li><input type="submit" class="menu-edit-contact" value="Responder" onclick="goToEditContactNumber()"></li>
            <li><input type="submit" class="menu-analytics" value="Analytics" onclick="goToAnalytics()"></li>
            <li><input type="submit" class="menu-analytics" value="Add Responder" onclick="goToResponder()"></li>
            <li><input type="submit" class="menu-logout" value="Log out" onclick="index()"></li>
        </ul>
    </div><br><br><br><br><br><br><br>
        
    <div class="index-container">
    <div class="index-box">
    <center><h3>Admin Geofencing Settings</h3>
    
    <div class="input-group">
    <select id="geofenceMode">
        <option class="input-field" value="radius" <?php echo $geofence_type == 'radius' ? 'selected' : ''; ?>>1 km Radius</option>
        <option class="input-field" value="polygon" <?php echo $geofence_type == 'polygon' ? 'selected' : ''; ?>>Geofenced Area</option>
    </select> </div>
                
    <button class="apply-btn" onclick="applyGeofencing()">Apply</button> </center> </div></div><br>

<div class="map-container">
    <div class="map-box">
    <center><h1><p>
    <?php
    if ($geofence_type == 'polygon') {
        echo "Current Geofence: Barangay 600 Boundary";
    } elseif ($geofence_type == 'radius') {
        echo "Current Geofence: 1km Radius";
    } else {
        echo "No active geofence type.";
    }
    ?>
</p></h1></center>
  
    <div id="map"></div></div></div>

    <script>
    const radius = <?php echo isset($radius) ? $radius : 'null'; ?>;
    const coordinates = <?php echo isset($coordinates) ? json_encode($coordinates) : 'null'; ?>;

    const centerLatLng = { lat: 14.598395, lng: 121.019430 }; // Default center

    let map;
    let currentGeofence = null; // Store current geofencing layer

    function initMap() {
        map = L.map('map').setView([centerLatLng.lat, centerLatLng.lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // If there's an active radius, apply it
        if (radius) {
            applyRadiusMode(radius, centerLatLng.lat, centerLatLng.lng);
        }

        // If there are coordinates, apply the polygon
        if (coordinates) {
            applyPolygonMode(coordinates);
        }
    }

    // Apply radius geofencing mode
    function applyRadiusMode(radius, lat, lng) {
        if (currentGeofence) {
            map.removeLayer(currentGeofence);
        }
        currentGeofence = L.circle([lat, lng], {
            color: 'blue',
            fillColor: '#3388ff',
            fillOpacity: 0.5,
            radius: radius
        }).addTo(map);

        map.setView([lat, lng], 15);
    }

    // Apply polygon geofencing mode
    function applyPolygonMode(coords) {
        if (currentGeofence) {
            map.removeLayer(currentGeofence);
        }
        currentGeofence = L.polygon(coords, {
            color: 'red',
            weight: 2,
            fillColor: '#ff7800',
            fillOpacity: 0.5
        }).addTo(map);

        map.fitBounds(currentGeofence.getBounds());
    }

    // Function to apply geofencing and send to PHP for saving
    function applyGeofencing() {
        const selectedMode = document.getElementById('geofenceMode').value;

        // Prepare the data to send to save_geofence.php
        const data = {
            geofence_type: selectedMode
        };

        // Send the data to save_geofence.php via AJAX
        fetch('save_geofence.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);  // Log to check if coordinates are returned properly
            if (data.success) {
                alert('Geofence updated successfully!');
                // Re-apply geofence mode after updating
                if (selectedMode === 'radius') {
                    applyRadiusMode(1000, centerLatLng.lat, centerLatLng.lng);  // Example radius value, modify as needed
                } else if (selectedMode === 'polygon') {
                    // Assuming the server returns updated coordinates for the polygon
                    if (data.polygon_coords) {
                        applyPolygonMode(data.polygon_coords);
                    } else {
                        alert('No coordinates returned for the polygon.');
                    }
                }
            } else {
                alert('Failed to update geofence.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the geofence.');
        });
    }

    window.onload = initMap;
    
    function toggleMenu() {
        var menu = document.getElementById("menu");
        if (menu.style.left === "0px") {
            menu.style.left = "-200px"; // Hide the menu
        } else {
            menu.style.left = "0px"; // Show the menu
        }
    }

    function admin() {
        window.location.href = "admin.php";
    }
    
    function toggleMenu() {
        const menu = document.getElementById("menu");
        menu.style.left = menu.style.left === "0px" ? "-200px" : "0px";
    }
    function goToHome() {
        window.location.href = "admin.php";
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
    function goToResponder() {
        window.location.href = "add_responder.php";
    }
    function goToHistory() {
        window.location.href = "history.php";
    }
    function index() {
        alert("Log out successfully");
        window.location.href = "../index.php";
    }

    </script>

</body>
</html>
