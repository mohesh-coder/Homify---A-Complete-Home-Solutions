<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "homify";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : null;
    $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : null;
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    $is_service_provider = isset($_POST['is_service_provider']) ? 1 : 0;

    if ($name && $email && $password) {
        // Start transaction
        $conn->begin_transaction();

        try {
            // Insert into users table
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, is_service_provider) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssi", $name, $email, $password, $is_service_provider);

            if (!$stmt->execute()) {
                throw new Exception("Error registering user: " . $stmt->error);
            }

            $user_id = $stmt->insert_id;
            $stmt->close();

            if ($is_service_provider) {
                // Insert into service_providers table
                $phone_number = isset($_POST['phone_number']) ? $conn->real_escape_string($_POST['phone_number']) : null;
                $service_type = isset($_POST['service_type']) ? $conn->real_escape_string($_POST['service_type']) : null;
                $location = isset($_POST['location']) ? $conn->real_escape_string($_POST['location']) : null;

                $stmt = $conn->prepare("INSERT INTO service_providers (user_id, phone_number, service_type, location) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $user_id, $phone_number, $service_type, $location);

                if (!$stmt->execute()) {
                    throw new Exception("Error registering service provider: " . $stmt->error);
                }

                $stmt->close();
            }

            // Commit transaction
            $conn->commit();

            $response = [
                "status" => "success",
                "message" => $is_service_provider ? "Service provider registered successfully!" : "User registered successfully!",
                "user_id" => $user_id,
                "is_service_provider" => $is_service_provider,
                "service_type" => $service_type ?? null
            ];

            echo json_encode($response);

        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollback();
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Please fill in all required fields."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();