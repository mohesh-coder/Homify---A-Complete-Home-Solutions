<?php
include 'config.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

$company_id = $_POST['company_id'];
$customer_name = $_POST['customer_name'];
$customer_email = $_POST['customer_email'];
$address = $_POST['address'];
$package = $_POST['package'];

// Check for duplicate email
$duplicate_check_sql = "SELECT * FROM food_customers WHERE customer_email = '$customer_email'";
$duplicate_check_result = $conn->query($duplicate_check_sql);

$response = [];

if ($duplicate_check_result->num_rows > 0) {
    $response['status'] = 'error';
    $response['message'] = 'Error: The email address is already registered.';
} else {
    $sql = "INSERT INTO food_customers (company_id, customer_name, customer_email, address, package) VALUES ('$company_id', '$customer_name', '$customer_email', '$address', '$package')";

    if ($conn->query($sql) === TRUE) {
        $provider_sql = "SELECT * FROM food_providers WHERE id='$company_id'";
        $provider_result = $conn->query($provider_sql);
        $provider = $provider_result->fetch_assoc();

        $price = 0;
        switch ($package) {
            case "annual":
                $price = 1200;
                break;
            case "monthly":
                $price = 100;
                break;
            case "occasionally":
                $price = 50;
                break;
        }

        $billing_details = [
            "company_name" => $provider['company_name'],
            "company_email" => $provider['company_email'],
            "customer_name" => $customer_name,
            "customer_email" => $customer_email,
            "package" => $package,
            "address" => $address,
            "price" => $price
        ];

        // Send email to the company
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'homifyservice.2024@gmail.com'; // SMTP username
        $mail->Password = 'nlpbbttqlklqqxdi'; // SMTP password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('homifyservice.2024@gmail.com', 'Water and Food Supply');
        $mail->addAddress($provider['company_email'], $provider['company_name']);
        $mail->Subject = 'New Customer Request';
        $mail->Body = "Company Name: " . $billing_details['company_name'] . "\n" .
                      "Customer Name: " . $billing_details['customer_name'] . "\n" .
                      "Customer Email: " . $billing_details['customer_email'] . "\n" .
                      "Package: " . $billing_details['package'] . "\n" .
                      "Address to Deliver: " . $billing_details['address'] . "\n" .
                      "Price: $" . $billing_details['price'];

        if ($mail->send()) {
            $response['status'] = 'success';
            $response['message'] = 'Request accepted successfully.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Mailer Error: ' . $mail->ErrorInfo;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error: ' . $sql . '<br>' . $conn->error;
    }
}

$conn->close();

// Encode response as JSON and output it
echo json_encode($response);
?>
