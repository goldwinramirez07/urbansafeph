<?php
$host = 'xxxxxx';
$dbname = 'xxxxx';
$username = "xxxxxx";
$password = "xxxxx";

// Set the Content-Type to JSON
header("Content-Type: application/json");

try {
    // Retrieve the JSON input
    $input = json_decode(file_get_contents("php://input"), true);
    
    // Validate that 'token' exists and is a non-empty string
    if (isset($input['token']) && is_string($input['token']) && !empty(trim($input['token']))) {
        $token = trim($input['token']);

        // Create a new PDO instance for database connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Modify the SQL insert statement to use the correct column name 'fcm_token'
        $sql = "INSERT INTO notiftoken (fcm_token) VALUES (:token)";
        $stmt = $pdo->prepare($sql);

        // Bind the token parameter and execute the statement
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        // Return a success response with a 200 status code
        http_response_code(200);
        echo json_encode(["message" => "Token successfully inserted into the database."]);
    } else {
        // If token is missing or invalid, return a 400 Bad Request response
        http_response_code(400);
        echo json_encode(["error" => "Token is missing or invalid."]);
    }
} catch (PDOException $e) {
    // Return a 500 Internal Server Error for database-related issues
    http_response_code(500);
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
} catch (Exception $e) {
    // Catch any other general errors
    http_response_code(500);
    echo json_encode(["error" => "General error: " . $e->getMessage()]);
} finally {
    // Close the database connection
    if (isset($pdo)) {
        $pdo = null;
    }
}
?>
