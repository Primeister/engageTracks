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

if (isset($data['userId'])) {
    $userId = $conn->real_escape_string($data['userId']);

    // Retrieve email from the database
    $checkUserSql = "SELECT email FROM user WHERE userId = '$userId'";
    $result = $conn->query($checkUserSql);

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $email = $user['email'];

        // Generate a 5-digit OTP
        $otp = rand(10000, 99999);
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update the database with the OTP and expiry
        $updateTokenSql = "UPDATE user SET reset_token = '$otp', reset_token_expiry = '$expiry' WHERE userId = '$userId'";
        if ($conn->query($updateTokenSql)) {
            // Send OTP to the user's email
            $subject = "Your Password Reset OTP";
            $message = "Your OTP for resetting your password is: $otp. It is valid for 1 hour.";
            $headers = "From: no-reply@admittance-eng.co.za";

            if (mail($email, $subject, $message, $headers)) {
                echo json_encode(["success" => true, "message" => "OTP sent to your email."]);
            } else {
                echo json_encode(["success" => false, "message" => "Failed to send OTP email."]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Failed to generate OTP."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "User ID not found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
}

$conn->close();
?>