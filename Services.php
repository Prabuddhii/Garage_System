<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage POS System - Services</title>
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

        .navbar a {
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

        .navbar a:hover {
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

        .services-table {
            width: 100%;
            max-width: 1200px;
            border-collapse: collapse;
            background: rgba(0, 0, 0, 0.7);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
            overflow-x: auto;
            display: block;
        }

        .services-table thead {
            background: #f39c12;
            color: #fff;
        }

        .services-table th, 
        .services-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid rgba(255, 255, 255, 0.2);
            min-width: 100px;
        }

        .services-table tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.1);
        }

        .services-table tr:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .error-message {
            color: #e74c3c;
            padding: 10px;
            text-align: center;
        }

        footer {
            margin-top: 30px;
            padding: 15px;
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
            width: 100%;
            color: #ddd;
        }

        .edit-button, 
        .delete-button {
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

        .edit-button:hover, 
        .delete-button:hover {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .services-table th, 
            .services-table td {
                min-width: 100px;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="navbar">
            <img src="logo.png" alt="SINHE Auto Zone Logo" id="navbar-logo">
            <h1>Services</h1>
            <a href="dashboard.html">Back to Menu</a>
        </div>

        <section id="services-section">
            <div class="section-header">
                <h2>Services</h2>
                <div>
                    <a href="addservice.html">
                        <button id="add-item-button">Add New Service</button>
                    </a>
                </div>
            </div>

            <table class="services-table">
                <thead>
                    <tr>
                        <th>Service ID</th>
                        <th>Service Name</th>
                        <th>Small (Rs.)</th>
                        <th>Medium (Rs.)</th>
                        <th>Large (Rs.)</th>
                        <th>Mini Car (Rs.)</th>
                        <th>Car (Rs.)</th>
                        <th>Mini Van (Rs.)</th>
                        <th>Cab (Rs.)</th>
                        <th>Van (Rs.)</th>
                        <th>Jeep (Rs.)</th>
                        <th>Hiroof (Rs.)</th>
                        <th>Mini Lorry H (Rs.)</th>
                        <th>Mini Lorry (Rs.)</th>
                        <th>Lorry H (Rs.)</th>
                        <th>Lorry (Rs.)</th>
                        <th>Rosa Bus (Rs.)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "garage";

                    try {
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        
                        if ($conn->connect_error) {
                            throw new Exception("Connection failed: " . $conn->connect_error);
                        }

                        $sql = "SELECT * FROM services";
                        $result = $conn->query($sql);

                        if ($result === false) {
                            throw new Exception("Query failed: " . $conn->error);
                        }

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($row['id']) . "</td>
                                    <td>" . htmlspecialchars($row['service_name']) . "</td>
                                    <td>Rs. " . number_format($row['price_small'], 2) . "</td>
                                    <td>Rs. " . number_format($row['price_medium'], 2) . "</td>
                                    <td>Rs. " . number_format($row['price_large'], 2) . "</td>
                                    <td>Rs. " . number_format($row['price_mini_car'], 2) . "</td>
                                    <td>Rs. " . number_format($row['price_car'], 2) . "</td>
                                    <td>Rs. " . number_format($row['price_mini_van'], 2) . "</td>
                                    <td>Rs. " . number_format($row['price_cab'], 2) . "</td>
                                    <td>Rs. " . number_format($row['price_van'], 2) . "</td>
                                    <td>Rs. " . number_format($row['price_jeep'], 2) . "</td>
                                    <td>Rs. " . number_format($row['price_hiroof'], 2) . "</td>
                                    <td>Rs. " . number_format($row['price_mini_lorry_h'], 2) . "</td>
                                    <td>Rs. " . number_format($row['price_mini_lorry'], 2) . "</td>
                                    <td>Rs. " . number_format($row['price_lorry_h'], 2) . "</td>
                                    <td>Rs. " . number_format($row['price_lorry'], 2) . "</td>
                                    <td>Rs. " . number_format($row['price_bus'], 2) . "</td>
                                    <td>
                                        <a href='editservice.php?id=" . $row['id'] . "'>
                                            <button class='edit-button'>Edit</button>
                                        </a>
                                        <button class='delete-button' data-id='" . $row['id'] . "'>Delete</button>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='18' class='error-message'>No services found in the database</td></tr>";
                        }
                    } catch (Exception $e) {
                        echo "<tr><td colspan='18' class='error-message'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                    } finally {
                        if (isset($conn) && $conn->ping()) {
                            $conn->close();
                        }
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
        const deleteButtons = document.querySelectorAll(".delete-button");
        deleteButtons.forEach(button => {
            button.addEventListener("click", async function () {
                const serviceId = this.getAttribute("data-id");
                if (confirm("Are you sure you want to delete this service?")) {
                    try {
                        const response = await fetch("delete_service.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded",
                            },
                            body: `id=${encodeURIComponent(serviceId)}`
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            alert("Service deleted successfully!");
                            location.reload();
                        } else {
                            alert("Error deleting service: " + (data.message || "Unknown error"));
                        }
                    } catch (error) {
                        alert("Error: " + error.message);
                    }
                }
            });
        });
    </script>
</body>
</html>