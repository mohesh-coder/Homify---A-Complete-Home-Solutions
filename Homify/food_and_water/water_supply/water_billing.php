<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "homify";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get customer_id from GET parameter (validate and sanitize input)
$customer_id = $_GET['customer_id'] ?? '';
$customer_id = intval($customer_id); // Ensure $customer_id is an integer

// Query to retrieve billing details
$sql = "SELECT water_customers.customer_name, water_customers.customer_email, water_customers.liters, water_customers.address, water_providers.company_name, water_providers.company_email
        FROM water_customers
        JOIN water_providers ON water_customers.company_id = water_providers.id
        WHERE water_customers.customer_id = $customer_id";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $company_name = $row['company_name'];
    $company_email = $row['company_email'];
    $customer_name = $row['customer_name'];
    $customer_email = $row['customer_email'];
    $liters = $row['liters'];
    $address = $row['address'];

    // Calculate total price (example: assuming $10 per liter)
    $price = $liters * 10;
} else {
    echo "No billing details found.";
    exit();
}

// Close database connection
$conn->close();

function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'homifyservice.2024@gmail.com'; // SMTP username
        $mail->Password = 'nlpbbttqlklqqxdi';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('homifyservice.2024@gmail.com', 'Homify');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        echo "<script>alert('Request accepted successfully. Email sent.');</script>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = "Billing Details for $customer_name";
    $message = "<h2>Billing Information</h2>";
    $message .= "<p><strong>Company Name:</strong> $company_name</p>";
    $message .= "<p><strong>Company Email:</strong> $company_email</p>";
    $message .= "<p><strong>Customer Name:</strong> $customer_name</p>";
    $message .= "<p><strong>Customer Email:</strong> $customer_email</p>";
    $message .= "<p><strong>Liters:</strong> $liters</p>";
    $message .= "<p><strong>Address to Deliver:</strong> $address</p>";
    $message .= "<p><strong>Price:</strong> ₹$price</p>";
    $message .= "<p>Total: ₹$price</p>";

    sendEmail($company_email, $subject, $message);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Details</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #121212;
            color: #fff;
            margin: 0;
            padding: 0;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        header {
            background-color: #1f1f1f;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            position: relative;
        }

        header h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .billing-container {
            width: 90%;
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background-color: rgba(50, 50, 50, 0.9);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        }

        .billing-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .billing-details p {
            margin-bottom: 10px;
        }

        .billing-details strong {
            display: inline-block;
            width: 150px;
        }

        .billing-total {
            text-align: right;
            font-weight: bold;
        }

        button {
            display: block;
            width: 100%;
            background-color: #333;
            color: #fff;
            border: none;
            padding: 12px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        button:hover {
            background-color: #555;
        }

        footer {
            background-color: #1f1f1f;
            color: #fff;
            text-align: center;
            padding: 15px 0;
            position: relative;
            margin-top: 40px;
            clear: both;
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Billing Details</h1>
    </header>

    <div class="billing-container" id="billing-container">
        <div class="billing-header">
            <h2>Billing Information</h2>
        </div>
        <div class="billing-details">
            <p><strong>Company Name:</strong> <?php echo htmlspecialchars($company_name); ?></p>
            <p><strong>Company Email:</strong> <?php echo htmlspecialchars($company_email); ?></p>
            <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($customer_name); ?></p>
            <p><strong>Customer Email:</strong> <?php echo htmlspecialchars($customer_email); ?></p>
            <p><strong>Liters:</strong> <?php echo htmlspecialchars($liters); ?></p>
            <p><strong>Address to Deliver:</strong> <?php echo htmlspecialchars($address); ?></p>
            <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($price); ?></p>
        </div>
        <div class="billing-total">
            <p>Total: ₹<?php echo htmlspecialchars($price); ?></p>
        </div>
        <form method="post">
            <button type="submit" id="accept-btn">OK</button>
        </form>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Water Supply. All rights reserved.</p>
    </footer>
</body>
</html>
