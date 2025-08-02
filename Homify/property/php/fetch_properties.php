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

// Initialize variables from GET parameters
$pageType = $_GET['page_type'];
$location = $_GET['location'];
$maxPrice = $_GET['max_price'];

// Prepare SQL query based on filters
$sql = "SELECT * FROM properties WHERE sale_or_rent = '$pageType'";

if (!empty($location)) {
    $sql .= " AND location LIKE '%$location%'";
}

if (!empty($maxPrice)) {
    $sql .= " AND price <= $maxPrice";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data as JSON
    $properties = array();
    while ($row = $result->fetch_assoc()) {
        $properties[] = $row;
    }
    echo json_encode($properties);
} else {
    // No properties found, return empty array
    echo json_encode(array());
}

$conn->close();
?>