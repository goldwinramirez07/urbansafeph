<?php
include "../config/mysql.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the POST data and decode the JSON payload
    $data = json_decode(file_get_contents("php://input"), true);

    // Extract the ID and other fields
    $id = $data['id'];
    $code = $data['code'];
    $name = $data['name'];
    $email = $data['email'];
    $contact_no = $data['contact_no'];
    $password = $data['password'];
    $role = $data['role'];
    $responder_type = $data['responder_type'];

    // Prepare and execute the update query
    $stmt = $conn->prepare("UPDATE login SET code=?, name=?, email=?, contact_no=?, password=?, role=?, responder_type=? WHERE id=?");
    $stmt->bind_param("sssssssi", $code, $name, $email, $contact_no, $password, $role, $responder_type, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Record updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update record."]);
    }

    $stmt->close();
    $conn->close();
}
?>