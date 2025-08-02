<?php
$conn = new mysqli('localhost', 'root', '', 'homify');

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$location = $_GET['location'];
$location = $conn->real_escape_string($location);

$sql = "SELECT * FROM package_service_providers WHERE location LIKE '%$location%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Name</th><th>Email</th><th>Phone</th><th>Location</th><th>Services</th><th>Vehicle Type</th><th>Number of Vehicles</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["phone"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["location"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["services"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["vehicle_type"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["num_vehicles"]) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No results found for the location: " . htmlspecialchars($location);
}

$conn->close();
?>