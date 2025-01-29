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

    // Query to fetch events based on userId
    $sql = "SELECT id, day, month, year, title, time FROM events WHERE userId = '$userId' ORDER BY year, month, day, time";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $eventsArr = [];

        while ($row = $result->fetch_assoc()) {
            $day = $row['day'];
            $month = $row['month'];
            $year = $row['year'];
            $eventData = [
                "title" => $row['title'],
                "time" => $row['time']
            ];

            // Find the existing entry for the same day, month, and year
            $found = false;
            foreach ($eventsArr as &$eventGroup) {
                if ($eventGroup['day'] == $day && $eventGroup['month'] == $month && $eventGroup['year'] == $year) {
                    $eventGroup['events'][] = $eventData;
                    $found = true;
                    break;
                }
            }

            // If not found, create a new entry
            if (!$found) {
                $eventsArr[] = [
                    "id" => $row['id'], // Assuming the first event ID for this date
                    "day" => $day,
                    "month" => $month,
                    "year" => $year,
                    "events" => [$eventData]
                ];
            }
        }

        echo json_encode(["success" => true, "eventsArr" => $eventsArr]);
    } else {
        echo json_encode(["success" => false, "message" => "No events found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
}

$conn->close();
?>