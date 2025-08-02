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

// Function to sanitize input data
function sanitizeData($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

// Initialize variables with empty values
$company_name = $company_email = $city = $about_company = $service_type = "";
$image = $target = "";

// Error and success messages
$errors = [];
$success = "";

// Form validation and data processing
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate company name
    if (empty($_POST["company_name"])) {
        $errors[] = "Company name is required";
    } else {
        $company_name = sanitizeData($_POST["company_name"]);
    }

    // Sanitize and validate company email
    if (empty($_POST["company_email"])) {
        $errors[] = "Company email is required";
    } else {
        $company_email = sanitizeData($_POST["company_email"]);
        // Check if email already exists
        $sql_check_email = "SELECT * FROM water_providers WHERE company_email = '$company_email'";
        $result_check_email = $conn->query($sql_check_email);
        if ($result_check_email->num_rows > 0) {
            $errors[] = "Error: The email address is already registered.";
        }
    }

    // Sanitize and validate city
    if (empty($_POST["city"])) {
        $errors[] = "City is required";
    } else {
        $city = sanitizeData($_POST["city"]);
    }

    // Sanitize and validate about company
    if (empty($_POST["about_company"])) {
        $errors[] = "About company is required";
    } else {
        $about_company = sanitizeData($_POST["about_company"]);
    }

    // Sanitize and validate service type
    if (empty($_POST["service_type"])) {
        $errors[] = "Service type is required";
    } else {
        $service_type = sanitizeData($_POST["service_type"]);
    }

    // Validate image upload
    if ($_FILES["image"]["error"] == 4) {
        $errors[] = "Image is required";
    } else {
        $image = $_FILES["image"]["name"];
        $target_dir = "food_and_water/water_supply/uploads/";
        $target_file = $target_dir . basename($image);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check === false) {
            $errors[] = "File is not an image.";
        }

        // Check file size
        if ($_FILES["image"]["size"] > 500000) { // Adjust size limit as needed
            $errors[] = "Sorry, your file is too large.";
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // If no errors, proceed to insert data into database and upload image
    if (empty($errors)) {
        // Create the target directory if it doesn't exist
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql_insert = "INSERT INTO water_providers (company_name, company_email, city, about_company, service_type, image)
            VALUES ('$company_name', '$company_email', '$city', '$about_company', '$service_type', '$target_file')";

            if ($conn->query($sql_insert) === TRUE) {
                $success = "Form submitted successfully.";
                // Redirect to success page or display success message
                header("Location: login.html");
                exit;
            } else {
                $errors[] = "Error: " . $sql_insert . "<br>" . $conn->error;
            }
        } else {
            $errors[] = "Sorry, there was an error uploading your file.";
        }
    }
}

// Close database connection
$conn->close();
?>
