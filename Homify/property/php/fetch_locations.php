<?php
// Database connection setup (replace with your actual database credentials)
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "homify"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch unique locations
$sql = "SELECT DISTINCT location FROM properties";
$result = $conn->query($sql);

$locations = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row['location'];
    }
}

echo json_encode($locations);

$conn->close();
?>
