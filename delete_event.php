<?php
header("Content-Type: application/json"); // Ensure JSON response
header("Access-Control-Allow-Origin: *"); // Replace * with a specific domain for security
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");



$host = 'localhost';
$dbname = 'admitdfa_engagetrack';
$user = 'admitdfa_mduduzi';
$pass = '+b9v9vK4u@oq';
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit;
}

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['userId'], $data['title'])) {
    // Sanitize input
    $userId = $conn->real_escape_string($data['userId']);
    $title = $conn->real_escape_string($data['title']);

    // Delete query
    $sql = "DELETE FROM events WHERE userId = '$userId' AND title = '$title'";

    if ($conn->query($sql)) {
        if ($conn->affected_rows > 0) {
            echo json_encode(["success" => true, "message" => "Event deleted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Event not found or unauthorized."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete event."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
}

$conn->close();
?>