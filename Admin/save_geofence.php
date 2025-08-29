<?php
// Include the MySQL connection configuration
include('../config/mysql.php');

// Get the geofence type and data from the request (from JavaScript)
$geofence_data = json_decode(file_get_contents("php://input"), true);
$geofence_type = $geofence_data['geofence_type']; // 'radius' or 'polygon'

// Deactivate the other geofence before activating the selected one
if ($geofence_type == "radius") {
    // Deactivate the polygon geofence (id = 2) without modifying other columns
    $sql_deactivate_polygon = "UPDATE geofence_settings SET is_active = 0 WHERE id = 2";
    $conn->query($sql_deactivate_polygon);

    // Activate the radius geofence (id = 1)
    $sql_activate_radius = "UPDATE geofence_settings SET is_active = 1 WHERE id = 1";
    $conn->query($sql_activate_radius);

} elseif ($geofence_type == "polygon") {
    // Deactivate the radius geofence (id = 1) without modifying other columns
    $sql_deactivate_radius = "UPDATE geofence_settings SET is_active = 0 WHERE id = 1";
    $conn->query($sql_deactivate_radius);

    // Activate the polygon geofence (id = 2)
    $sql_activate_polygon = "UPDATE geofence_settings SET is_active = 1 WHERE id = 2";
    $conn->query($sql_activate_polygon);
}

// Fetch the current active geofence data from the database
$sql_get_geofence = "SELECT * FROM geofence_settings WHERE is_active = 1 LIMIT 1";
$result = $conn->query($sql_get_geofence);
$active_geofence = $result->fetch_assoc();

if ($active_geofence) {
    // Prepare the response data
    $response = [
        'success' => true,
        'geofence_type' => $active_geofence['geofence_type'],
        'polygon_coords' => json_decode($active_geofence['polygon_coords']) // Assuming it's stored as a JSON string
    ];
} else {
    $response = [
        'success' => false,
        'geofence_type' => 'none',
    ];
}

// Return the data as JSON
echo json_encode($response);

// Close the connection
$conn->close();
?>
