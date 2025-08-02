// submit_provider.php
<?php
$conn = new mysqli('localhost', 'root', '', 'homify');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$location = $_POST['location'];
$services = $_POST['services'];
$vehicle_type = $_POST['vehicle'];
$num_vehicles = $_POST['number'];

if($name && $email && $phone && $location && $services && $vehicle_type && $num_vehicles)
{
$sql = "INSERT INTO package_service_providers (name, email, phone, location, services, vehicle_type, num_vehicles) 
        VALUES ('$name', '$email', '$phone', '$location', '$services', '$vehicle_type', '$num_vehicles')";

if ($conn->query($sql) === TRUE) {
    // Redirect to a success page
    header('Location: login.html');
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
}
else {
    echo "All fields are required.";
}
$conn->close();
?>
