<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Form</title>
    <style>
        /* Global styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #333; /* Dark mode background */
            color: #fff; /* Dark mode text color */
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            position: relative;
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
            background-color: #444; /* Dark mode background */
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
            border-radius: 10px;
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
        select,
        button {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.3s ease, background-color 0.3s ease, color 0.3s ease;
            background-color: #666; /* Dark mode form input background */
            color: #fff; /* Dark mode form input text color */
            border-color: #555; /* Dark mode form input border color */
        }

        button {
            background-color: #28a745; /* Button background color */
            color:  #0ABAB5.; /* Button text color */
            cursor: pointer;
        }

        button:hover {
            background-color: #218838; /* Button hover background color */
        }

        .notification {
            padding: 15px;
            margin-top: 20px;
            border-radius: 5px;
            display: none;
            text-align: center;
        }

        .notification.success {
            background-color: #28a745; /* Success background color */
            color: #fff;
        }

        .notification.error {
            background-color: #dc3545; /* Error background color */
            color: #fff;
        }
    </style>
</head>
<body>
    <header>
        <h1>Customer Form</h1>
    </header>

    <div class="form-container" id="form-container">
        <form action="food_save_customer.php" method="post" id="customer-form">
            <input type="hidden" name="company_id" value="<?php echo htmlspecialchars($_GET['company_id']); ?>">


            <label for="customer_name">Customer Name:</label>
            <input type="text" id="customer_name" name="customer_name" required>

            <label for="customer_email">Customer Email:</label>
            <input type="email" id="customer_email" name="customer_email" required>

            <label for="address">Address to Deliver:</label>
            <textarea id="address" name="address" required></textarea>

            <label for="package">Package:</label>
            <select id="package" name="package" required>
                <option value="annual">Annual Package</option>
                <option value="monthly">Monthly Package</option>
                <option value="occasionally">Occasionally</option>
            </select>

            <button type="submit">Submit</button>
        </form>

        <div class="notification" id="notification"></div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Food Supply. All rights reserved.</p>
    </footer>

    <script>
        // JavaScript for form validation and interactivity
        document.getElementById('customer-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const form = event.target;
            const formData = new FormData(form);

            fetch(form.action, {
                method: form.method,
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const notification = document.getElementById('notification');
                notification.className = 'notification ' + (data.status === 'success' ? 'success' : 'error');
                notification.textContent = data.message;
                notification.style.display = 'block';
            })
            .catch(error => {
                const notification = document.getElementById('notification');
                notification.className = 'notification error';
                notification.textContent = 'An error occurred while sending the request.';
                notification.style.display = 'block';
            });
        });

        // Apply dark mode styles on page load
        document.addEventListener('DOMContentLoaded', function() {
            const body = document.body;
            const formContainer = document.getElementById('form-container');

            // Apply dark mode styles
            body.classList.add('dark-mode');
            formContainer.classList.add('dark-mode');
        });
    </script>
</body>
</html>
