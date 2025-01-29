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

if (isset($data['employeeId']) && isset($data['email']) && isset($data['password'])) {
    $employeeId = $conn->real_escape_string($data['employeeId']);
    $email = $conn->real_escape_string($data['email']);
    $password = $conn->real_escape_string($data['password']); // Store plain-text password

    // Check if employeeId exists in the employees table
    $checkEmployeeSql = "SELECT name, role FROM employees WHERE employeeId = '$employeeId'";
    $employeeResult = $conn->query($checkEmployeeSql);

    if ($employeeResult->num_rows === 1) {
        $employee = $employeeResult->fetch_assoc();
        $name = $employee['name'];
        $role = $employee['role'];

        // Check if the user already exists in the users table
        $checkUserSql = "SELECT * FROM user WHERE userId = '$employeeId'";
        $userResult = $conn->query($checkUserSql);

        if ($userResult->num_rows === 0) {
            // Insert into the users table
            $insertUserSql = "INSERT INTO user (userId, name, email, role, password) 
                              VALUES ('$employeeId', '$name', '$email', '$role', '$password')";
            if ($conn->query($insertUserSql) === TRUE) {
                echo json_encode([
                    "success" => true,
                    "message" => "User successfully registered."
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to register user. Database error."
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "User already exists."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Employee ID not found in employees table."
        ]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
}

$conn->close();