<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'homify');

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$sql = "SELECT DISTINCT location FROM package_service_providers  ORDER BY location";
$result = $conn->query($sql);

$locations = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $locations[] = $row['location'];
    }
}

echo json_encode($locations);

$conn->close();
?>