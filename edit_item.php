<?php
// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Connect to database
    $conn = new mysqli('localhost', 'root', '', 'garage');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch item details from the 'inventory' table
    $sql = "SELECT * FROM inventory WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // 'i' means integer
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            color: #333;
        }

        /* Navbar */
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

        /* Form Container */
        .form-container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Form Title */
        .form-container h2 {
            font-size: 2rem;
            color: #f39c12;
            margin-bottom: 20px;
            text-align: center;
        }

        /* Form Labels */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        /* Form Inputs */
        input[type="text"], input[type="number"], button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        /* Submit Button */
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

        /* Footer */
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
        <h1>Edit Item</h1>
        <a href="inventorymanagement.php">Back to Inventory</a>
    </div>

    <!-- Form Section -->
    <div class="form-container">
        <h2>Edit Item Details</h2>
        <form method="POST" action="update_item.php">
            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">

            <label for="item_name">Item Name</label>
            <input type="text" id="item_name" name="item_name" value="<?php echo htmlspecialchars($item['item_name']); ?>" required>

            <label for="made_by">Made By</label>
            <input type="text" id="made_by" name="made_by" value="<?php echo htmlspecialchars($item['made_by']); ?>" required>

            <label for="model">Model</label>
            <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($item['model']); ?>" required>

            <label for="stock">Stock</label>
            <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($item['stock']); ?>" required>

            <label for="price">Price (Rs.)</label>
            <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($item['price']); ?>" required>

            <button type="submit">Save Changes</button>
        </form>
    </div>

    <!-- Footer -->
    <footer>
    <p>&copy; SINHE Auto Zone. All rights reserved.</p>
    </footer>
</body>
</html>
