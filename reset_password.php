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

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['userId']) && isset($data['otp']) && isset($data['new_password'])) {
    $userId = $conn->real_escape_string($data['userId']);
    $otp = $conn->real_escape_string($data['otp']);
    $newPassword = $conn->real_escape_string($data['new_password']); // No hashing as per your requirement

    // Verify OTP and expiry
    $checkTokenSql = "SELECT reset_token_expiry FROM user WHERE userId = '$userId' AND reset_token = '$otp'";
    $result = $conn->query($checkTokenSql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $expiry = $user['reset_token_expiry'];

        if (strtotime($expiry) > time()) {
            // Update the password and clear the token
            $updatePasswordSql = "UPDATE user SET password = '$newPassword', reset_token = NULL, reset_token_expiry = NULL WHERE userId = '$userId'";
            if ($conn->query($updatePasswordSql)) {
                echo json_encode(["success" => true, "message" => "Password has been reset successfully."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to update password."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "OTP has expired."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid OTP or User ID."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
}

$conn->close();
?>