<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // If installed via Composer

$response = ['success' => false, 'message' => 'An error occurred.'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $propertyId = $_POST['propertyId'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    // Fetch property details from the database using the property ID
    $propertyDetails = getPropertyDetailsById($propertyId);
    
    if (!$propertyDetails || !isset($propertyDetails['email'])) {
        $response['message'] = "Property details not found or seller email not found.";
        echo json_encode($response);
        exit();
    }

    $sellerEmail = $propertyDetails['email'];

    // Email to property seller
    $to_seller = $sellerEmail;
    $subject_seller = "New Quote Request for Property #" . $propertyId;
    $message_seller = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            h1 { color: #00b5b5; }
            .property-details, .user-details { background-color: #f0f0f0; padding: 15px; margin-bottom: 20px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>New Quote Request</h1>
            <div class='property-details'>
                <h2>Property Details:</h2>
                <p><strong>Property ID:</strong> {$propertyDetails['id']}</p>
                <p><strong>Type:</strong> {$propertyDetails['property_type']}</p>
                <p><strong>Location:</strong> {$propertyDetails['location']}</p>
                <p><strong>Price:</strong> {$propertyDetails['price']}</p>
            </div>
            <div class='user-details'>
                <h2>User Details:</h2>
                <p><strong>Name:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Message:</strong> $message</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Email to user
    $to_user = $email;
    $subject_user = "Your Quote Request for Property #" . $propertyId;
    $message_user = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            h1 { color: #00b5b5; }
            .property-details { background-color: #f0f0f0; padding: 15px; margin-bottom: 20px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>Your Quote Request</h1>
            <p>Thank you for your interest in our property. We have received your quote request and will get back to you soon.</p>
            <div class='property-details'>
                <h2>Property Details:</h2>
                <p><strong>Property ID:</strong> {$propertyDetails['id']}</p>
                <p><strong>Type:</strong> {$propertyDetails['property_type']}</p>
                <p><strong>Location:</strong> {$propertyDetails['location']}</p>
                <p><strong>Price:</strong> {$propertyDetails['price']}</p>
            </div>
            <p>If you have any questions, please don't hesitate to contact us.</p>
        </div>
    </body>
    </html>
    ";

    // Send emails using PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'homifyservice.2024@gmail.com'; // SMTP username
        $mail->Password = 'nlpbbttqlklqqxdi'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('no-reply@homify.com', 'Homify');
        
        // Email to seller
        $mail->addAddress($to_seller);
        $mail->Subject = $subject_seller;
        $mail->isHTML(true);
        $mail->Body = $message_seller;
        $mail->send();

        // Clear all recipients for the next email
        $mail->clearAddresses();

        // Email to user
        $mail->addAddress($to_user);
        $mail->Subject = $subject_user;
        $mail->isHTML(true);
        $mail->Body = $message_user;
        $mail->send();

        $response['success'] = true;
        $response['message'] = "Your message has been sent successfully.";
    } catch (Exception $e) {
        $response['message'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);

function getPropertyDetailsById($propertyId) {
    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'homify');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch property details
    $sql = "SELECT * FROM properties WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $propertyId);
    $stmt->execute();
    $result = $stmt->get_result();
    $propertyDetails = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    return $propertyDetails;
}
?>
