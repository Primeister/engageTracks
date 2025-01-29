<?php

$host = 'localhost';
$db = 'vegasdyc_bookings';
$user = 'vegasdyc_bruceVega';
$pass = 'BA}h,OPv;{IC';


$conn = new mysqli($host, $user, $pass, $db);


if($conn->connect_error){
    die("Connection failed". $conn->connect_error);
}

$bookingFee = "Booking fee";
$shootBalance = "Shoot balance";

$sql = "SELECT * FROM client where description = '$bookingFee' AND name NOT IN (SELECT name FROM client where description = '$shootBalance') ORDER by date";

$result = $conn->query($sql);

$bookings = array();

if($result->num_rows > 0){

    while($row = $result->fetch_assoc()){
        $bookings[] = $row;
    }

    echo json_encode($bookings);
}else{
    echo json_encode([]);
}

$conn->close();

?>