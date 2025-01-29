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

if (isset($data['employeeId']) && isset($data['firstName']) && isset($data['lastName']) && isset($data['role'])) {
    $employeeId = $conn->real_escape_string($data['employeeId']);
    $firstName = $conn->real_escape_string($data['firstName']);
    $lastName = $conn->real_escape_string($data['lastName']);
    $role = $conn->real_escape_string($data['role']);

   

        // Check if the employee already exists in the employees table
        $checkEmployeeSql = "SELECT name FROM employees WHERE employeeId = '$employeeId'";
        $employeeResult = $conn->query($checkEmployeeSql);

        if ($employeeResult->num_rows === 0) {
            // Insert into the employee table
            $insertEmployeeSql = "INSERT INTO employees (employeeId, name, surname, role) 
                              VALUES ('$employeeId', '$firstName', '$lastName', '$role')";
            if ($conn->query($insertEmployeeSql) === TRUE) {
                echo json_encode([
                    "success" => true,
                    "message" => "Employee successfully registered."
                ]);
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to register employee. Database error."
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Employee already exists."
            ]);
        }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
}

$conn->close();