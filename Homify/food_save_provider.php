<?php
include 'config.php';

// Retrieve form data
$company_name = $_POST['company_name'];
$company_email = $_POST['company_email'];
$city = $_POST['city'];
$about_company = $_POST['about_company'];
$service_type = $_POST['service_type'];
$target_dir = "food_and_water/food_supply/uploads/";
$target_file = $target_dir . basename($_FILES["image"]["name"]);
move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

// Check for duplicate email
$sql = "SELECT * FROM food_providers WHERE company_email = '$company_email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Error: The email address is already registered.";
} else {
    // Insert data into the database
    $sql = "INSERT INTO food_providers (company_name, company_email, city, about_company, service_type, image) 
            VALUES ('$company_name', '$company_email', '$city', '$about_company', '$service_type', '$target_file')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: login.html");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
