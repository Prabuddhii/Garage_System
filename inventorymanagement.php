<?php
// Start session to share data across pages
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "garage";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch items from the database and check for low stock
$sql = "SELECT * FROM inventory";
$result = $conn->query($sql);

// Initialize low stock items array
$lowStockItems = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if ((int)$row["stock"] < 2) {
            $lowStockItems[] = htmlspecialchars($row["item_name"]);
        }
    }
}
// Store low stock items in session
$_SESSION['lowStockItems'] = $lowStockItems;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage POS System - Inventory Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('garage-inventory-bg.jpg') no-repeat center center/cover;
            color: #fff;
        }

        .navbar {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            margin-right: 10px;
        }

        .navbar h1 {
            font-size: 1.8rem;
            color: #f39c12;
            margin: 0;
        }

        .navbar button, .navbar a {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            background: #e74c3c;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .navbar button:hover, .navbar a:hover {
            background: #c0392b;
        }

        #app {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin-bottom: 20px;
        }

        .section-header h2 {
            font-size: 2rem;
            color: #f39c12;
        }

        .section-header button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            background: #27ae60;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .section-header button:hover {
            background: #1e8449;
        }

        .inventory-table {
            width: 100%;
            max-width: 1200px;
            border-collapse: collapse;
            background: rgba(0, 0, 0, 0.7);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
        }

        .inventory-table thead {
            background: #f39c12;
            color: #fff;
        }

        .inventory-table th, .inventory-table td {
            padding: 15px;
            text-align: left;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .inventory-table tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.1);
        }

        .inventory-table tr:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        footer {
            margin-top: 30px;
            padding: 15px;
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
            width: 100%;
            color: #ddd;
        }

        .edit-button, .delete-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 5px 10px;
            margin: 2px;
            cursor: pointer;
            border-radius: 4px;
        }

        .delete-button {
            background-color: #e74c3c;
        }

        .edit-button:hover, .delete-button:hover {
            opacity: 0.8;
        }

        .add-item-button {
            background-color: #2ecc71;
        }

        .low-stock {
            background-color: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            font-weight: bold;
        }

        .error-message {
            position: fixed;
            top: 70px; /* Below navbar */
            left: 50%;
            transform: translateX(-50%);
            background: #e74c3c;
            color: white;
            padding: 15px;
            border-radius: 5px;
            display: none;
            z-index: 1001;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            max-width: 80%;
            text-align: center;
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="navbar">
            <img src="logo.png" alt="SINHE Auto Zone Logo" id="navbar-logo">
            <h1>Inventory Management</h1>
            <a href="dashboard.html">Back to Menu</a>
        </div>

        <!-- Error message container -->
        <div id="errorMessage" class="error-message">
            <p>Low stock warning: <span id="lowStockItems"></span></p>
        </div>

        <!-- Inventory Section -->
        <section id="inventory-page">
            <div class="section-header">
                <h2>Inventory Management</h2>
                <div>
                    <a href="add_new_item.html">
                        <button id="add-item-button" class="add-item-button">Add New Item</button>
                    </a>
                </div>
            </div>

            <table class="inventory-table">
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Name</th>
                        <th>Made by</th>
                        <th>Type</th>
                        <th>Model</th>
                        <th>Stock</th>
                        <th>Price (Rs.)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Reset result pointer and fetch again for display
                    $result->data_seek(0);
                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $id = htmlspecialchars($row["id"]);
                            $item_name = htmlspecialchars($row["item_name"]);
                            $made_by = htmlspecialchars($row["made_by"]);
                            $type = htmlspecialchars($row["type"]);
                            $model = htmlspecialchars($row["model"]);
                            $stock = htmlspecialchars($row["stock"]);
                            $price = number_format($row["price"], 2);
                            
                            $isLowStock = (int)$stock < 5;
                            
                            echo "<tr" . ($isLowStock ? " class='low-stock'" : "") . ">
                                    <td>$id</td>
                                    <td>$item_name</td>
                                    <td>$made_by</td>
                                    <td>$type</td>
                                    <td>$model</td>
                                    <td>$stock" . ($isLowStock ? " (Low Stock)" : "") . "</td>
                                    <td>Rs. $price</td>
                                    <td>
                                        <a href='edit_item.php?id=$id'>
                                            <button class='edit-button'>Edit</button>
                                        </a>
                                        <a href='delete_item.php?id=$id' onclick='return confirm(\"Are you sure you want to delete this item?\");'>
                                            <button class='delete-button'>Delete</button>
                                        </a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No items found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <footer>
            <p>© SINHE Auto Zone. All rights reserved.</p>
        </footer>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Edit and delete button handling
            const editButtons = document.querySelectorAll('.edit-button');
            editButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    console.log('Edit clicked for item:', this.closest('tr').children[0].textContent);
                });
            });

            const deleteButtons = document.querySelectorAll('.delete-button');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    console.log('Delete clicked for item:', this.closest('tr').children[0].textContent);
                });
            });

            // Error message handling
            const lowStockItems = <?php echo json_encode($lowStockItems); ?>;
            const errorMessage = document.getElementById('errorMessage');
            const lowStockItemsSpan = document.getElementById('lowStockItems');

            if (lowStockItems.length > 0) {
                lowStockItemsSpan.textContent = lowStockItems.join(', ');
                errorMessage.style.display = 'block';
                
                // Auto-hide after 7 seconds
                // setTimeout(() => {
                //     errorMessage.style.display = 'none';
                // }, 7000);
                
                // Click to dismiss
                errorMessage.addEventListener('click', function() {
                    this.style.display = 'none';
                });
            }
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>