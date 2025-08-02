<?php
include 'config.php';

$service_type = $_POST['service_type'] ?? '';
$city = $_POST['city'] ?? '';

// Sanitize inputs
$service_type = mysqli_real_escape_string($conn, $service_type);
$city = mysqli_real_escape_string($conn, $city);

$sql = "SELECT * FROM food_providers WHERE service_type='$service_type' AND city='$city'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service food_providers</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #1e1e1e; /* Dark mode background */
            color: #ddd; /* Dark mode text color */
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 15px 0;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            background-color: #2e2e2e; /* Dark mode background */
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .provider {
            flex: 0 1 calc(33.333% - 20px); /* Adjust the width and margin as needed */
            margin: 10px;
            padding: 20px;
            background-color: #3a3a3a; /* Dark mode provider background */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .provider img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .provider h2 {
            margin-top: 10px;
            font-size: 1.25rem;
            color: #ddd; /* Dark mode text color */
        }

        .provider a {
            text-decoration: none;
            color: inherit;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
            margin-top: 20px;
            clear: both;
            border-radius: 0 0 8px 8px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Service food_providers</h1>
    </header>

    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Replace backslashes with forward slashes
                $imagePath = str_replace('\\', '/', $row['image']);
                
                echo '<div class="provider">';
                echo '<a href="food_customer_form.php?company_id=' . $row['id'] . '">';
                echo '<img src="' . htmlspecialchars($imagePath) . '" alt="' . htmlspecialchars($row['company_name']) . '">';
                echo '<h2>' . htmlspecialchars($row['company_name']) . '</h2>';
                echo '</a>';
                echo '</div>';
            }
        } else {
            echo '<p>No food providers found for the selected city and service type.</p>';
        }
        $conn->close();
        ?>
    </div>

    <footer>
        &copy; 2024 Water and Food Supply. All rights reserved.
    </footer>
</body>
</html>
