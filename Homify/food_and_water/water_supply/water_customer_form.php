<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Form</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Global styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #333;
            color: #fff;
            margin: 0;
            padding: 0;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        header {
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            position: relative;
            backdrop-filter: blur(10px);
        }

        header h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .form-container {
            width: 90%;
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
            transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        }

        form {
            display: grid;
            gap: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        textarea,
        input[type="number"],
        button {
            width: 100%;
            padding: 12px;
            border: 1px solid #555;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1rem;
            background-color: #666;
            color: #fff;
            transition: border-color 0.3s ease, background-color 0.3s ease, color 0.3s ease;
        }

        button {
            background-color: #444;
            color: #fff;
            border: none;
            padding: 12px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #555;
        }

        footer {
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            text-align: center;
            padding: 15px 0;
            position: relative;
            margin-top: 40px;
            clear: both;
            backdrop-filter: blur(10px);
        }

        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Water Supply Customer Form</h1>
    </header>

    <div class="form-container" id="form-container">
        <form action="water_save_customer.php" method="post" id="customer-form">
            <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($_GET['company_id']); ?>">

            <label for="customer_name">Customer Name:</label>
            <input type="text" id="customer_name" name="customer_name" required>

            <label for="customer_email">Customer Email:</label>
            <input type="email" id="customer_email" name="customer_email" required>

            <label for="address">Address to Deliver:</label>
            <textarea id="address" name="address" required></textarea>

            <label for="liters">How Many Liters:</label>
            <input type="number" id="liters" name="liters" required>

            <button type="submit">Submit</button>
        </form>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Water Supply. All rights reserved.</p>
    </footer>

    <script>
        // JavaScript for form validation and interactivity
        document.getElementById('customer-form').addEventListener('submit', function(event) {
            const customerName = document.getElementById('customer_name').value.trim();
            const customerEmail = document.getElementById('customer_email').value.trim();
            const address = document.getElementById('address').value.trim();
            const liters = document.getElementById('liters').value.trim();

            if (customerName === '' || customerEmail === '' || address === '' || liters === '') {
                alert('Please fill in all fields.');
                event.preventDefault(); // Prevent form submission
            }
        });
    </script>
</body>
</html>
