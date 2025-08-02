<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$company_name = htmlspecialchars($_GET['company_name']);
$company_email = htmlspecialchars($_GET['company_email']);
$customer_name = htmlspecialchars($_GET['customer_name']);
$customer_email = htmlspecialchars($_GET['customer_email']);
$package = htmlspecialchars($_GET['package']);
$address = htmlspecialchars($_GET['address']);
$price = htmlspecialchars($_GET['price']);

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
    $message .= "<p><strong>Package:</strong> $package</p>";
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
            background-color: #333; /* Dark mode background */
            color: #fff; /* Dark mode text color */
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
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
            background-color: #444; /* Dark mode background */
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            border-radius: 10px;
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
            background-color: #333;
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
            <p><strong>Company Name:</strong> <?php echo $company_name; ?></p>
            <p><strong>Company Email:</strong> <?php echo $company_email; ?></p>
            <p><strong>Customer Name:</strong> <?php echo $customer_name; ?></p>
            <p><strong>Customer Email:</strong> <?php echo $customer_email; ?></p>
            <p><strong>Package:</strong> <?php echo $package; ?></p>
            <p><strong>Address to Deliver:</strong> <?php echo $address; ?></p>
            <p><strong>Price:</strong> $<?php echo $price; ?></p>
        </div>
        <div class="billing-total">
            <p>Total: $<?php echo $price; ?></p>
        </div>
        <form method="post">
            <button type="submit" id="accept-btn">OK</button>
        </form>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Food Supply. All rights reserved.</p>
    </footer>
    <script>
        // Apply dark mode styles on page load
        document.addEventListener('DOMContentLoaded', function() {
            const body = document.body;
            const billingContainer = document.getElementById('billing-container');

            // Apply dark mode styles
            body.classList.add('dark-mode');
            billingContainer.classList.add('dark-mode');
        });
    </script>
</body>
</html>
