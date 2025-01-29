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

if (isset($data['userId'], $data['day'], $data['month'], $data['year'], $data['title'], $data['time'])) {
    // Sanitize input
    $userId = $conn->real_escape_string($data['userId']);
    $day = $conn->real_escape_string($data['day']);
    $month = $conn->real_escape_string($data['month']);
    $year = $conn->real_escape_string($data['year']);
    $title = $conn->real_escape_string($data['title']);
    $time = $conn->real_escape_string($data['time']);

    // Insert query
    $sql = "INSERT INTO events (userId, day, month, year, title, time) 
            VALUES ('$userId', '$day', '$month', '$year', '$title', '$time')";

    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => "Event inserted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to insert event."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
}

$conn->close();
?>