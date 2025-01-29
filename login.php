<?php
header("Content-Type: application/json"); // Ensure JSON response
header("Access-Control-Allow-Origin: *"); // Replace * with a specific domain for security
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");



$host = 'localhost';
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

if (isset($data['userId']) && isset($data['password'])) {
    $userId = $conn->real_escape_string($data['userId']);
    $password = $conn->real_escape_string($data['password']);

    // Query the user
    $sql = "SELECT * FROM user WHERE userId = '$userId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if ($password === $user['password']) {

            $token = uniqid('', true);

            $updateSql = "UPDATE user SET token = '$token' WHERE userId = {$user['userId']}";

            if($conn->query($updateSql) === TRUE){
            // Remove sensitive fields before returning the user data
            unset($user['password']);
            $user['token'] = $token;

            echo json_encode([
                "success" => true,
                "message" => "Login successful.",
                "user" => $user
            ]);
            }else{
                echo json_encode(["success" => false, "message" => "Failed to update token."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid password."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "User not found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
}

$conn->close();
?>