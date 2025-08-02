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

// Retrieve search criteria from URL parameters
$service_type = $_GET['service_type'] ?? '';
$city = $_GET['city'] ?? '';

// Query database based on search criteria
$sql = "SELECT * FROM water_providers WHERE service_type='$service_type' AND city='$city'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Providers</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Global styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #1e1e1e;
            color: #ddd;
            margin: 0;
            padding: 0;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        header {
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            text-align: center;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
            backdrop-filter: blur(10px);
        }

        header h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            width: 80%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            backdrop-filter: blur(10px);
        }

        .provider {
            flex: 0 1 calc(33.333% - 20px); /* Adjust the width and margin as needed */
            margin: 10px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .provider img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .provider h2 {
            margin-top: 10px;
            font-size: 1.25rem;
            color: #ddd;
            transition: color 0.3s ease;
        }

        .provider a {
            text-decoration: none;
            color: inherit;
        }

        footer {
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
            clear: both;
            border-radius: 0 0 8px 8px;
            backdrop-filter: blur(10px);
        }

        .search-form {
            margin: 20px auto;
            text-align: center;
        }

        .search-form input, .search-form select, .search-form button {
            padding: 10px;
            margin: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

    </style>
</head>
<body>
    <header>
        <h1>Service Providers</h1>
    </header>

    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="provider">';
                echo '<a href="water_customer_form.php?company_id=' . $row['id'] . '">';
                if (isset($row['image'])) {
                    echo '<img src="' . $row['image'] . '" alt="' . $row['company_name'] . '">';
                } else {
                    echo '<img src="default_image.jpg" alt="Default Image">';
                }
                echo '<h2>' . $row['company_name'] . '</h2>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No results found for your search criteria.</p>';
        }
        $conn->close();
        ?>
    </div>

    <footer>
        &copy; <?php echo date('Y'); ?> Water and Food Supply. All rights reserved.
    </footer>
</body>
</html>
