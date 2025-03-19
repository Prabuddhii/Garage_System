<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Connect to database
    $conn = new mysqli('localhost', 'root', '', 'garage');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch service details
    $sql = "SELECT * FROM services WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            color: #333;
        }

        .navbar {
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 15px 30px;
            background: rgba(0, 0, 0, 0.7);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        #navbar-logo {
           width: 80px; 
           height: auto;
           margin-right: 5px; 
        }

        .navbar h1 {
            font-size: 1.8rem;
            color: #f39c12;
            margin: 2;
            flex-grow: 1;
        }

        .navbar a {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            background: #e74c3c;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .navbar a:hover {
            background: #c0392b;
        }

        .form-container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            font-size: 2rem;
            color: #f39c12;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"], input[type="number"], button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            background: #2ecc71;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #27ae60;
        }

        footer {
            margin-top: 30px;
            padding: 15px;
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
            color: #ddd;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
    <img src="logo.png" alt="SINHE Auto Zone Logo" id="navbar-logo">
        <h1>Edit Service</h1>
        <a href="Services.php">Back to Services</a>
    </div>

    <!-- Form Section -->
    <div class="form-container">
        <h2>Edit Service Details</h2>
        <form action="update_service.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $service['id']; ?>">

            <label for="service_name">Service Name</label>
            <input type="text" id="service_name" name="service_name" value="<?php echo $service['service_name']; ?>" required>

            <label for="price_small">Small (Rs.)</label>
            <input type="number" id="price_small" name="price_small" value="<?php echo $service['price_small']; ?>" min="0" step="0.01" required>

            <label for="price_medium">Medium (Rs.)</label>
            <input type="number" id="price_medium" name="price_medium" value="<?php echo $service['price_medium']; ?>" min="0" step="0.01" required>

            <label for="price_large">Large (Rs.)</label>
            <input type="number" id="price_large" name="price_large" value="<?php echo $service['price_large']; ?>" min="0" step="0.01" required>

            <button type="submit">Update Service</button>
        </form>
    </div>

    <!-- Footer -->
    <footer>
    <p>&copy; SINHE Auto Zone. All rights reserved.</p>
    </footer>
</body>
</html>
