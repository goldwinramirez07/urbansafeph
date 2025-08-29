<?php
// Include the database configuration file
include('config/mysql.php'); // Assuming mysql.php contains the connection details

// Array to store the contact numbers for each responder type
$responder_contacts = [
    'Medical' => '',
    'Firefighter' => '',
    'Flood' => '',
    'Police' => ''
];

// Fetch the contact numbers for each responder type from the database
$sql = "SELECT responder_type, contact_no FROM login WHERE role = 'responder'";
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

// Return the contact numbers as a JSON response
echo json_encode($responder_contacts);
?>
