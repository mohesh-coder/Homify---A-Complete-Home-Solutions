<?php
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

// Validate and sanitize input data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$location = isset($_POST['location']) ? trim($_POST['location']) : '';
$services = isset($_POST['services']) ? trim($_POST['services']) : '';
$experience = isset($_POST['experience']) ? trim($_POST['experience']) : '';
$co_workers = isset($_POST['co_workers']) ? (int) $_POST['co_workers'] : 0;

// Check for required fields
if ($name && $email && $phone && $location && $services && $experience && $co_workers) {
    $sql = "INSERT INTO home_maintenance_service_providers (name, email, phone, location, services, experience, co_workers)
            VALUES ('$name', '$email', '$phone', '$location', '$services', '$experience', '$co_workers')";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect to a success page
        header('Location: login.html');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "All fields are required.";
}

$conn->close();
?>
