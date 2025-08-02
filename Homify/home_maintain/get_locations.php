<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$servername = "localhost"; // Replace with your MySQL server hostname or IP address
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "homify"; // Replace with your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get distinct locations
$sql = "SELECT DISTINCT location FROM home_maintenance_service_providers";
$result = $conn->query($sql);

// Fetch locations
$locations = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $locations[] = $row["location"];
    }
}

// Close connection
$conn->close();

// Return locations as JSON
echo json_encode($locations);
?>
