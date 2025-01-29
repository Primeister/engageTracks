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

// Query to fetch all employees
$sql = "SELECT * FROM employees";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $employees = [];

    while ($row = $result->fetch_assoc()) {
        $employees[] = $row;
    }

    echo json_encode(["success" => true, "employees" => $employees]);
} else {
    echo json_encode(["success" => false, "message" => "No employees found."]);
}

$conn->close();
?>