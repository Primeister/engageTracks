<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = $_POST['Name'];
    $phone = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $eventType = $_POST['shootType'];
    $eventName = $_POST['eventName'];
    $date = $_POST['date'];
    $duration = $_POST['duration'];
    $cost = $_POST['screen'];

    // Compose email message for admin
    $toAdmin = "bookings@vegashoot.co.za"; // Admin email address
    $subjectAdmin = "New Booking Request";
    $messageAdmin = "Name: $name\n";
    $messageAdmin .= "Phone: $phone\n";
    $messageAdmin .= "Email: $email\n";
    $messageAdmin .= "Event Type: $eventType\n";
    $messageAdmin .= "Event Name: $eventName\n";
    $messageAdmin .= "Date: $date\n";
    $messageAdmin .= "Duration: $duration hours\n";
    $messageAdmin .= "Estimated cost: R$cost\n";

    // Compose confirmation email for the user
    $toUser = $email;
    $subjectUser = "Your Photoshoot Booking Confirmation";
    $messageUser = "Hi $name,\n\n";
    $messageUser .= "Thank you for booking with Bruce Vega on vegashoot.co.za ! Here are your booking details:\n";
    $messageUser .= "Event Name: $eventName\n";
    $messageUser .= "Date: $date\n";
    $messageUser .= "Duration: $duration hours\n";
    $messageUser .= "Estimated cost: R$cost\n\n";
    $messageUser .= "We look forward to capturing amazing moments with you!\n";
    $messageUser .= "Here are the banking details for you to use to pay your 50% booking fee: || Account Holder - Bruce Vega (pty) ltd \n Account Number - 62933068977
\nBank - First National Bank
\nPhone number -
062 827 7468 ||\n";
    $messageUser .= "Best regards,\nBruce Vega Team";

    // Send email to admin
    if (mail($toAdmin, $subjectAdmin, $messageAdmin)) {
        // Send confirmation email to the user
        mail($toUser, $subjectUser, $messageUser);

        // Confirmation message for the user
        echo "Thank you! Your booking request has been submitted successfully. A confirmation email has been sent to you.";
    } else {
        echo "Oops! Something went wrong. Please try again later.";
    }
} else {
    echo "Oops! It seems you've accessed this page directly.";
}
?>
