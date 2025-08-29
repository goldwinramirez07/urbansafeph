<?php
include('../../config/mysql.php');

// Sanitize and validate input
$loc = filter_var($_POST['loc'], FILTER_SANITIZE_STRING);
$date = trim($_POST['dti']);
$report = filter_var($_POST['rep'], FILTER_SANITIZE_STRING);
$latitude = filter_var($_POST['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$longitude = filter_var($_POST['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

// Convert date to 'Y-m-d H:i:s' format
$dateTimeFormat = 'Y-m-d H:i:s A'; // Input format: 8/10/2024, 2:09:26 PM
$dateTime = DateTime::createFromFormat($dateTimeFormat, $date);

if (!$dateTime || $dateTime->format($dateTimeFormat) !== $date) {
    $timestamp = strtotime($date);
    if ($timestamp) {
        $formattedDate = date('Y-m-d H:i:s', $timestamp);
    } else {
        // Handle error if date conversion fails
        echo '<script>alert("Invalid date format. Please enter a valid date.")</script>';
        header("Refresh: 1; url=http://urbansafeph.com/User/Guest/incident-report.php");
        exit();
    }
} else {
    $formattedDate = $dateTime->format('Y-m-d H:i:s');
}

// Process the image upload
if (isset($_FILES['img']) && $_FILES['img']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['img']['tmp_name'];
    $fileName = $_FILES['img']['name'];
    $fileSize = $_FILES['img']['size'];
    $fileType = $_FILES['img']['type'];

    // Add a timestamp to the file name for validation purposes
    $currentTimestamp = date('Ymd_His'); // Example format: 20241024_145601
    $fileNameWithTimestamp = $currentTimestamp . "_" . $fileName;

    // Define the allowed file types
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($fileType, $allowedTypes)) {
        // Store the file in the database as a LONGBLOB
        $fileData = file_get_contents($fileTmpPath);

        // Prepare SQL query to insert data
        $sql = "INSERT INTO incidentreport (loc, date_time, report, img, user_id, latitude, longitude, active) VALUES (?, ?, ?, ?, ?, ?, ?, '1')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssddd", $loc, $formattedDate, $report, $fileData, $user_id, $latitude, $longitude);

        // Execute query
        if ($stmt->execute()) {
            echo '<script>alert("Incident report submitted successfully.")</script>';
            
            // Query for responders' phone numbers
            $responderQuery = "SELECT contact_no FROM admin WHERE responder_type = 'Medical'";
            $responderResult = mysqli_query($conn, $responderQuery);

            // Check if any responders exist
            if (mysqli_num_rows($responderResult) == 0) {
                error_log("No responders found.");
                echo '<script>alert("No medical responders found.")</script>';
                header("Refresh: 1; url=http://urbansafeph.com/User/Guest/report.php");
                exit();
            }

            // Prepare the message content
            $message = "New incident report received at $loc on $formattedDate. Description: $report. Check it: http://urbansafe.ph/User/member.php";

            // Send SMS to each responder
            while ($responder = mysqli_fetch_assoc($responderResult)) {
                // Ensure the phone number starts with +63
                $phone_number = $responder['contact_no'];
                if (substr($phone_number, 0, 1) === '0') {
                    $phone_number = '+63' . substr($phone_number, 1);  // Replace leading 0 with +63
                } elseif (substr($phone_number, 0, 3) !== '+63') {
                    $phone_number = '+63' . $phone_number;  // Add +63 if it doesn't start with it
                }
            
                // Log the phone number being sent to
                error_log("Formatted phone number for SMS: $phone_number");
            
                // Semaphore API configuration
                $ch = curl_init();
                $parameters = array(
                    'apikey' => '9faa37852ad977d1818195da520a0a0d', // Replace with your actual API key
                    'number' => $phone_number,
                    'message' => $message,
                    'sendername' => 'Urbansafeph'
                );
            
                // Semaphore API request
                curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $output = curl_exec($ch);
            
                // Log the response from Semaphore API
                if ($output === false) {
                    $error_message = curl_error($ch);
                    error_log("Failed to send SMS to $phone_number: $error_message");
                } else {
                    error_log("Semaphore API response for $phone_number: $output");
                }
            
                curl_close($ch);
            }

            header("Refresh: 1; url=http://urbansafeph.com/User/Guest/report.php");
            exit();
        } else {
            echo '<script>alert("Error submitting report")</script>';
            header("Refresh: 1; url=http://urbansafeph.com/User/Guest/report.php");
            exit();
        }
    } else {
        echo '<script>alert("Invalid file type. Only JPEG, PNG, and GIF files are allowed.")</script>';
        header("Refresh: 1; url=http://urbansafeph.com/User/Guest/incident-report.php");
        exit();
    }
} else {
    echo '<script>alert("Error uploading file.")</script>';
    header("Refresh: 1; url=http://urbansafeph.com/User/Guest/incident-report.php");
    exit();
}

$conn->close();
?>
