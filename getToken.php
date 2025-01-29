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

// Get JSON data from request
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['userId'])) {
    $userId = $conn->real_escape_string($data['userId']);

    // Fetch the token from the database
    $sql = "SELECT token FROM user WHERE userId = '$userId'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        echo json_encode([
            "success" => true,
            "token" => $row['token']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "User not found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
}

$conn->close();