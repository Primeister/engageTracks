<?php
header("Content-Type: application/json"); // Ensure JSON response

$host = 'desdemona.aserv.co.za';
$dbname = 'admitdfa_engagetrack';
$user = 'admitdfa_mduduzi';
$pass = '+b9v9vK4u@oq';

// Database connection
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit;
}

// Get JSON data from the request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['user_id']) && isset($data['field']) && isset($data['value'])) {
    $user_id = $conn->real_escape_string($data['user_id']);
    $field = $conn->real_escape_string($data['field']);
    $value = $conn->real_escape_string($data['value']);

    // Validate that the field is allowed to be updated
    $allowedFields = ['username', 'email', 'status', 'last_login']; // Add allowed fields here
    if (!in_array($field, $allowedFields)) {
        echo json_encode(["success" => false, "message" => "Invalid field."]);
        exit;
    }

    // Update query
    $sql = "UPDATE users SET $field = '$value' WHERE id = '$user_id'";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["success" => true, "message" => "Field updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update field: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
}

$conn->close();
?>