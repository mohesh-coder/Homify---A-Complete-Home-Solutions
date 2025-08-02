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

$customer_name = $_POST['customer_name'];
$customer_email = $_POST['customer_email'];
$address = $_POST['address'];
$liters = $_POST['liters'];
$company_id = $_POST['company_id'];

// Check for duplicate email
$sql = "SELECT * FROM water_customers WHERE customer_email = '$customer_email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Error: The email address is already registered.";
} else {
    $sql = "INSERT INTO water_customers (customer_name, customer_email, address, liters, company_id)
    VALUES ('$customer_name', '$customer_email', '$address', '$liters', '$company_id')";

    if ($conn->query($sql) === TRUE) {
        header("Location: water_billing.php?customer_id=" . $conn->insert_id);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
