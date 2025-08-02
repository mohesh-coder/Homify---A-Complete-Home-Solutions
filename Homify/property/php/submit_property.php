<?php
// Database configuration
$host = 'localhost';
$dbname = 'homify';
$username = 'root';
$password = '';

// Establish database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $propertyType = filter_input(INPUT_POST, 'property_type', FILTER_SANITIZE_STRING);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $saleOrRent = filter_input(INPUT_POST, 'sale_or_rent', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email_id', FILTER_SANITIZE_EMAIL);
    $description = filter_input(INPUT_POST, 'property_description', FILTER_SANITIZE_STRING);
    $latitude = filter_input(INPUT_POST, 'latitude', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $longitude = filter_input(INPUT_POST, 'longitude', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    // Validate inputs
    if (!$propertyType || !$location || $price === false || $price === null || !$saleOrRent || !$email || !$description || $latitude === false || $longitude === false) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }

    // Convert price to cents to avoid floating point issues
    $priceCents = round($price * 1);

    try {
        // Prepare SQL statement
        $stmt = $pdo->prepare("INSERT INTO properties (property_type, location, price, sale_or_rent, email, description, latitude, longitude) VALUES (:property_type, :location, :price, :sale_or_rent, :email, :description, :latitude, :longitude)");
        
        // Bind parameters
        $stmt->bindParam(':property_type', $propertyType, PDO::PARAM_STR);
        $stmt->bindParam(':location', $location, PDO::PARAM_STR);
        $stmt->bindParam(':price', $priceCents, PDO::PARAM_INT);
        $stmt->bindParam(':sale_or_rent', $saleOrRent, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':latitude', $latitude, PDO::PARAM_STR);
        $stmt->bindParam(':longitude', $longitude, PDO::PARAM_STR);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to insert data']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}