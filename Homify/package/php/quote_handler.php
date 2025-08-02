<?php
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "homify";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => "Connection failed: " . $conn->connect_error]));
}

// Get and sanitize form data
$provider_email = $conn->real_escape_string($_POST['provider_name'] ?? ''); // This now contains the provider's email
$name = $conn->real_escape_string($_POST['name'] ?? '');
$email = $conn->real_escape_string($_POST['email'] ?? '');
$phone = $conn->real_escape_string($_POST['phone'] ?? '');
$move_from = $conn->real_escape_string($_POST['move_from'] ?? '');
$move_to = $conn->real_escape_string($_POST['move_to'] ?? '');
$move_date = $conn->real_escape_string($_POST['move_date'] ?? '');
$package_size = $conn->real_escape_string($_POST['package_size'] ?? '');
$additional_info = $conn->real_escape_string($_POST['additional_info'] ?? '');

// Fetch provider's details from the database
$providerQuery = "SELECT name, email FROM package_service_providers  WHERE email = ?";
$providerStmt = $conn->prepare($providerQuery);
if (!$providerStmt) {
    die(json_encode(['status' => 'error', 'message' => "Prepare failed: " . $conn->error]));
}

$providerStmt->bind_param("s", $provider_email);
if (!$providerStmt->execute()) {
    die(json_encode(['status' => 'error', 'message' => "Execute failed: " . $providerStmt->error]));
}

$providerResult = $providerStmt->get_result();
if ($providerResult->num_rows === 0) {
    die(json_encode(['status' => 'error', 'message' => "No provider found with email: $provider_email"]));
}

$providerData = $providerResult->fetch_assoc();
$providerName = $providerData['name'];
$providerEmail = $providerData['email'];

// Insert data into the database
$sql = "INSERT INTO package_quote_requests (provider_name, name, email, phone, move_from, move_to, move_date, package_size, additional_info)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(['status' => 'error', 'message' => "Prepare failed: " . $conn->error]));
}

$stmt->bind_param("sssssssss", $providerName, $name, $email, $phone, $move_from, $move_to, $move_date, $package_size, $additional_info);

if ($stmt->execute()) {
    // Function to send email
    function sendEmail($recipient, $recipientName, $subject, $body) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username = 'homifyservice.2024@gmail.com'; // SMTP username
            $mail->Password = 'nlpbbttqlklqqxdi';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('homify.packageandmovers@gmail.com', 'Packers&Movers');
            $mail->addAddress($recipient, $recipientName);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Email to customer
    $customerSubject = 'Quote Request Received';
    $customerBody = "
        <h2>Thank you for your quote request!</h2>
        <p>Dear $name,</p>
        <p>We have received your quote request for moving services. Here are the details:</p>
        <ul>
            <li>Service Provider assigned: $providerName</li>
            <li>Moving From: $move_from</li>
            <li>Moving To: $move_to</li>
            <li>Preferred Move Date: $move_date</li>
            <li>Package Size: $package_size</li>
        </ul>
        <p>Additional Information: $additional_info</p>
        <p>We will review your request and get back to you shortly with a quote.</p>
        <p>If you have any questions, please don't hesitate to contact us at homify.packageandmovers@gmail.com or call us at (123) 456-7890.</p>
        <p>Thank you for choosing our services!</p>
        <br>
        <p>Best regards,</p>
        <p>Homify.Packers&Movers</p>
    ";

    // Email to service provider
    $providerSubject = 'New Quote Request';
    $providerBody = "
        <h2>New Quote Request Received</h2>
        <p>Dear $providerName,</p>
        <p>A new quote request has been assigned to you. Here are the details:</p>
        <ul>
            <li>Customer Name: $name</li>
            <li>Customer Email: $email</li>
            <li>Customer Phone no: $phone</li>
            <li>Moving From: $move_from</li>
            <li>Moving To: $move_to</li>
            <li>Preferred Move Date: $move_date</li>
            <li>Package Size: $package_size</li>
        </ul>
        <p>Additional Information: $additional_info</p>
        <p>Please review this request and provide a quote to the customer as soon as possible.</p>
        <br>
        <p>Best regards,</p>
        <p>Homify.Packers&Movers</p>
    ";

    $customerEmailSent = sendEmail($email, $name, $customerSubject, $customerBody);
    $providerEmailSent = sendEmail($providerEmail, $providerName, $providerSubject, $providerBody);

    if ($customerEmailSent && $providerEmailSent) {
        echo json_encode(['status' => 'success', 'message' => 'Quote request submitted and confirmation emails sent to customer and service provider']);
    } else {
        echo json_encode(['status' => 'partial_success', 'message' => 'Quote request submitted but there was an issue sending one or both emails']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>